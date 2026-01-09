<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251118144029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create car_listings table with its final structure including new IDs and extraction date indexes.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE car_listings (
                id CHAR(36) NOT NULL,
                source VARCHAR(50) NOT NULL,
                source_id VARCHAR(255) NOT NULL,
                title_raw VARCHAR(255) NOT NULL,
                url VARCHAR(255) DEFAULT NULL,
                price_cash INT DEFAULT NULL,
                price_financed INT DEFAULT NULL,
                year INT DEFAULT NULL,
                fuel VARCHAR(50) DEFAULT NULL,
                km INT DEFAULT NULL,
                location VARCHAR(100) DEFAULT NULL,
                img_url VARCHAR(255) DEFAULT NULL,
                dealer_name VARCHAR(255) DEFAULT NULL,
                raw_data JSON NOT NULL,
                created_at TIMESTAMP NOT NULL,
                extraction_date DATE NOT NULL,
                make_id INT DEFAULT NULL,
                model_id INT DEFAULT NULL,
                generation_id INT DEFAULT NULL,
                serie_id INT DEFAULT NULL,
                trim_id INT DEFAULT NULL,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('CREATE INDEX idx_car_listings_source_id ON car_listings (source_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_car_listings_source_sourceid_date ON car_listings (source, source_id, extraction_date)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE car_listings');
    }
}
