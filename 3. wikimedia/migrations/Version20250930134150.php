<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250930134150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add mobile field (nullable) to user_preference table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE user_preference
            ADD COLUMN mobile VARCHAR(20) DEFAULT NULL
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE user_preference
            DROP COLUMN mobile
        ');
    }
}
