<?php

namespace App\Modules\UI\Cli;

use App\Modules\Car\Domain\Geoname\Geoname;
use Doctrine\ORM\EntityManagerInterface;
use Manticoresearch\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:import-geonames',
    description: 'Importa ciudades a MySQL y las indexa en Manticore',
)]
class ImportGeonamesCliCommand extends Command
{
    private const HEADERS = [
        'geonameid', 'name', 'asciiname', 'alternatenames',
        'latitude', 'longitude', 'feature_class', 'feature_code',
        'country_code', 'cc2', 'admin1_code', 'admin2_code',
        'admin3_code', 'admin4_code', 'population', 'elevation',
        'dem', 'timezone', 'modification_date'
    ];

    private Client $manticoreClient;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface $parameterBag
    ) {
        parent::__construct();
        $this->manticoreClient = new Client([
            'host' => $this->parameterBag->get('manticore_host'),
            'port' => $this->parameterBag->get('manticore_port'),
            'timeout' => 60,
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $this->parameterBag->get('kernel.project_dir') . '/database/geonames_es.txt';

        if (!file_exists($filePath)) {
            $io->error("Archivo no encontrado en: $filePath");
            return Command::FAILURE;
        }

        $this->ensureManticoreTable($io);

        $io->title("Iniciando importación Dual (MySQL + Manticore) desde: $filePath");


        $handle = fopen($filePath, 'r');
        $batchSize = 1000;
        $totalImported = 0;

        $manticoreBuffer = [];

        while (($data = fgetcsv($handle, 0, "\t")) !== false) {
            if (count($data) !== count(self::HEADERS)) {
                continue;
            }

            $row = array_combine(self::HEADERS, $data);

            if ($row['feature_class'] !== 'P') {
                continue;
            }

            $v = fn($k) => ($row[$k] === '') ? null : trim($row[$k]);

            $geoname = new Geoname(
                geonameid:       (int)$row['geonameid'],
                name:            (string)$v('name'),
                asciiname:       (string)$v('asciiname'),
                latitude:        (string)$v('latitude'),
                longitude:       (string)$v('longitude'),
                featureClass:    (string)$v('feature_class'),
                featureCode:     (string)$v('feature_code'),
                countryCode:     (string)$v('country_code'),
                population:      (string)$v('population') ?: '0',
                timezone:        (string)$v('timezone'),
                modificationDate: new \DateTime($v('modification_date')),
                alternatenames:  $v('alternatenames'),
                cc2:             $v('cc2'),
                admin1Code:      $v('admin1_code'),
                admin2Code:      $v('admin2_code'),
                admin3Code:      $v('admin3_code'),
                admin4Code:      $v('admin4_code'),
                elevation:       $v('elevation') !== null ? (int)$v('elevation') : null,
                dem:             $v('dem') !== null ? (int)$v('dem') : null
            );

            $this->entityManager->persist($geoname);

            $manticoreBuffer[] = [
                'id'             => (int)$row['geonameid'],
                'name'           => $row['name'],
                'asciiname'      => $row['asciiname'],
                'alternatenames' => $row['alternatenames'] ?? '',
                'feature_class'  => $row['feature_class'],
                'population'     => (int)($row['population'] ?: 0),
            ];

            if (count($manticoreBuffer) >= $batchSize) {
                $this->entityManager->flush();
                $this->entityManager->clear();

                $this->manticoreClient->table('geoname')->addDocuments($manticoreBuffer);

                $totalImported += count($manticoreBuffer);
                $manticoreBuffer = []; // Vaciar buffer
                $io->write('.');
            }
        }

        if (count($manticoreBuffer) > 0) {
            $this->entityManager->flush();
            $this->entityManager->clear();
            $this->manticoreClient->table('geoname')->addDocuments($manticoreBuffer);
            $totalImported += count($manticoreBuffer);
        }

        fclose($handle);
        $io->newLine();
        $io->success("Importación completada. $totalImported registros procesados en ambos sistemas.");

        return Command::SUCCESS;
    }

    private function ensureManticoreTable(SymfonyStyle $io): void
    {
        $io->text('Verificando tabla en Manticore...');
        return;
        $this->manticoreClient->table('geoname')->create([
            'name'           => ['type' => 'text'],
            'asciiname'      => ['type' => 'text'],
            'alternatenames' => ['type' => 'text'],
            'feature_class'  => ['type' => 'string'],
            'population'     => ['type' => 'bigint'], // Importante para ordenar
        ], [
            'min_infix_len' => '3',
            'charset_table' => 'non_cjk,0..9,A..Z->a..z,_,a..z',
        ]);
    }
}
