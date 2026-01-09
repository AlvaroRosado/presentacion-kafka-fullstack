<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251205093445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fixes foreign key incompatibility by using generic varchar for user_id';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_subscriptions (
            id INT AUTO_INCREMENT NOT NULL,
            user_id VARCHAR(255) NOT NULL,
            starts_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            stripe_session_id VARCHAR(255) NOT NULL,
            INDEX IDX_USER_SUBSCRIPTION_USER (user_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB');

        $this->addSql('ALTER TABLE users ADD access_expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');

        $this->addSql('ALTER TABLE user_subscriptions ADD CONSTRAINT FK_USER_SUBSCRIPTION_USER FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_subscriptions DROP FOREIGN KEY FK_USER_SUBSCRIPTION_USER');
        $this->addSql('DROP TABLE user_subscriptions');
        $this->addSql('ALTER TABLE users DROP access_expires_at');
    }
}
