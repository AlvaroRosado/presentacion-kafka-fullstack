<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250630133329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the event_store table with proper indexes on stream and stream_id.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE event_store (
                id BIGINT AUTO_INCREMENT PRIMARY KEY,
                stream VARCHAR(255) NOT NULL,
                stream_id VARCHAR(255) NOT NULL,
                type VARCHAR(255) NOT NULL,
                payload JSON NOT NULL,
                metadata JSON NOT NULL,
                version INT NOT NULL,
                created_at TIMESTAMP NOT NULL
            )
        ');

        $this->addSql('CREATE UNIQUE INDEX uniq_stream_version ON event_store (stream, stream_id, version)');
        $this->addSql('CREATE INDEX idx_stream ON event_store (stream)');
        $this->addSql('CREATE INDEX idx_stream_id ON event_store (stream_id)');
        $this->addSql('CREATE INDEX idx_stream_stream_id ON event_store (stream, stream_id)');
        $this->addSql('CREATE INDEX idx_created_at ON event_store (created_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE event_store');
    }
}
