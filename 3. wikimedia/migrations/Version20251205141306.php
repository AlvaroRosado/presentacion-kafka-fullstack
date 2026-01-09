<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251205141306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crea la tabla professional_leads con soporte para tracking de contacto';
    }

    public function up(Schema $schema): void
    {
        // Creamos la tabla con el campo 'contacted' incluido
        $this->addSql('CREATE TABLE professional_leads (
            id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
            name VARCHAR(255) NOT NULL,
            company VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            priority VARCHAR(50) NOT NULL,
            contacted TINYINT(1) DEFAULT 0 NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            UNIQUE INDEX UNIQ_EMAIL (email),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE professional_leads');
    }
}
