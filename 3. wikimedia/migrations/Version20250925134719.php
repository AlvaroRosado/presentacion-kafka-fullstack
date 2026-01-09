<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250925134719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user table for App\Modules\Auth\Domain\User';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE users (
                id CHAR(36) NOT NULL,
                email VARCHAR(180) NOT NULL,
                first_name VARCHAR(180) NOT NULL,
                last_name VARCHAR(180) NOT NULL,
                roles JSON NOT NULL,
                password VARCHAR(255) NOT NULL,
                origin VARCHAR(50) NOT NULL,
                google_id VARCHAR(255) DEFAULT NULL,
                google_email VARCHAR(255) DEFAULT NULL,
                google_avatar VARCHAR(255) DEFAULT NULL,
                UNIQUE (email),
                PRIMARY KEY(id)
            )'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}
