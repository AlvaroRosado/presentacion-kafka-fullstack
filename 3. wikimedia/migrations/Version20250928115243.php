<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250928115243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user_preference table for UserPreference entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE user_preference (
                id CHAR(36) NOT NULL,
                avatar_url VARCHAR(50) NOT NULL,
                notification_channel VARCHAR(50) NOT NULL,
                time_zone VARCHAR(64) NOT NULL,
                date_time_format VARCHAR(20) NOT NULL,
                first_day_of_week VARCHAR(10) NOT NULL,
                PRIMARY KEY(id)
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_preference');
    }
}
