<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250928115848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add foreign key from users.user_preference_id to user_preference.id with ON DELETE CASCADE';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_preference ADD COLUMN user_id CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE user_preference ADD CONSTRAINT FK_user_preference_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_preference DROP FOREIGN KEY FK_user_preference_user');
        $this->addSql('ALTER TABLE user_preference DROP COLUMN user_id');
    }
}
