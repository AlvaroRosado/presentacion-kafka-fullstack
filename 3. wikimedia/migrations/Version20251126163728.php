<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251126163728 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // Esta migración crea la tabla 'geoname'
        $this->addSql('CREATE TABLE geoname (
            geonameid INT NOT NULL,
            name VARCHAR(200) NOT NULL,
            asciiname VARCHAR(200) NOT NULL,
            alternatenames TEXT NOT NULL,
            latitude NUMERIC(10, 7) NOT NULL,
            longitude NUMERIC(10, 7) NOT NULL,
            feature_class CHAR(1) NOT NULL,
            feature_code VARCHAR(10) NOT NULL,
            country_code VARCHAR(2) NOT NULL,
            cc2 VARCHAR(200) DEFAULT NULL,
            admin1_code VARCHAR(20) DEFAULT NULL,
            admin2_code VARCHAR(80) DEFAULT NULL,
            admin3_code VARCHAR(20) DEFAULT NULL,
            admin4_code VARCHAR(20) DEFAULT NULL,
            population BIGINT NOT NULL,
            elevation INT DEFAULT NULL,
            dem INT DEFAULT NULL,
            timezone VARCHAR(40) NOT NULL,
            modification_date DATE NOT NULL,
            PRIMARY KEY(geonameid)
        )');

        // Opcional: añadir índices para mejorar las búsquedas comunes
        $this->addSql('CREATE INDEX idx_geoname_country_code ON geoname (country_code)');
        $this->addSql('CREATE INDEX idx_geoname_name ON geoname (name)');
    }

    public function down(Schema $schema): void
    {
        // Esta migración elimina la tabla 'geoname'
        $this->addSql('DROP TABLE geoname');
    }
}
