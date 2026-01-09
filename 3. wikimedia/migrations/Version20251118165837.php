<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118165837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Convert car_listings.id from CHAR(36) to BINARY(16) UUID storage';
    }

    public function up(Schema $schema): void
    {
        // Step 1: Add binary column
        $this->addSql("
            ALTER TABLE car_listings
            ADD id_bin BINARY(16) DEFAULT NULL AFTER id
        ");

        // Step 2: Convert current UUID text values to binary
        // Only if existing rows exist
        $this->addSql("
            UPDATE car_listings
            SET id_bin = UNHEX(REPLACE(id, '-', ''))
            WHERE id IS NOT NULL
        ");

        // Step 3: Drop old PK and column, rename new one
        $this->addSql("ALTER TABLE car_listings DROP PRIMARY KEY");
        $this->addSql("ALTER TABLE car_listings DROP COLUMN id");
        $this->addSql("ALTER TABLE car_listings CHANGE id_bin id BINARY(16) NOT NULL");

        // Step 4: Add new PK
        $this->addSql("ALTER TABLE car_listings ADD PRIMARY KEY (id)");
    }

    public function down(Schema $schema): void
    {
        // Revert to CHAR(36)

        $this->addSql("
            ALTER TABLE car_listings
            ADD id_char CHAR(36) DEFAULT NULL AFTER id
        ");

        $this->addSql("
            UPDATE car_listings
            SET id_char = INSERT(INSERT(INSERT(INSERT(HEX(id),
                9, 0, '-'),
                14, 0, '-'),
                19, 0, '-'),
                24, 0, '-')
        ");

        $this->addSql("ALTER TABLE car_listings DROP PRIMARY KEY");
        $this->addSql("ALTER TABLE car_listings DROP COLUMN id");
        $this->addSql("ALTER TABLE car_listings CHANGE id_char id CHAR(36) NOT NULL");
        $this->addSql("ALTER TABLE car_listings ADD PRIMARY KEY (id)");
    }
}
