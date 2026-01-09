<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251120155805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add monthly partitions to car_listings from Dec 2025 to Jan 2027';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE car_listings DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE car_listings ADD PRIMARY KEY (id, extraction_date)');
        $this->addSql('
            ALTER TABLE car_listings
            PARTITION BY RANGE COLUMNS (extraction_date) (
                PARTITION p2025_12 VALUES LESS THAN ("2026-01-01"),
                PARTITION p2026_01 VALUES LESS THAN ("2026-02-01"),
                PARTITION p2026_02 VALUES LESS THAN ("2026-03-01"),
                PARTITION p2026_03 VALUES LESS THAN ("2026-04-01"),
                PARTITION p2026_04 VALUES LESS THAN ("2026-05-01"),
                PARTITION p2026_05 VALUES LESS THAN ("2026-06-01"),
                PARTITION p2026_06 VALUES LESS THAN ("2026-07-01"),
                PARTITION p2026_07 VALUES LESS THAN ("2026-08-01"),
                PARTITION p2026_08 VALUES LESS THAN ("2026-09-01"),
                PARTITION p2026_09 VALUES LESS THAN ("2026-10-01"),
                PARTITION p2026_10 VALUES LESS THAN ("2026-11-01"),
                PARTITION p2026_11 VALUES LESS THAN ("2026-12-01"),
                PARTITION p2026_12 VALUES LESS THAN ("2027-01-01"),
                PARTITION p2027_01 VALUES LESS THAN ("2027-02-01")
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE car_listings REMOVE PARTITIONING
        ');
    }
}
