<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251023142743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE user_notification (
                id CHAR(36) NOT NULL,
                user_id CHAR(36) NOT NULL,
                title VARCHAR(255) NOT NULL,
                message TEXT DEFAULT NULL,
                image_url VARCHAR(255) DEFAULT NULL,
                actions JSON DEFAULT NULL,
                type VARCHAR(50) NOT NULL,
                is_read TINYINT(1) DEFAULT 0 NOT NULL,
                created_at TIMESTAMP NOT NULL,
                PRIMARY KEY(id)
             );
       ');

        $this->addSql('
            ALTER TABLE user_notification
            ADD CONSTRAINT FK_USER_NOTIFICATION_USER
            FOREIGN KEY (user_id) REFERENCES users(id)
            ON DELETE CASCADE;
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_notification');
    }
}
