<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220325214203 extends AbstractMigration
{
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE supply_part_id_seq CASCADE');
        $this->addSql('DROP TABLE supply_part');
    }

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SEQUENCE supply_part_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(
            'CREATE TABLE supply_part (id INT NOT NULL, supply_id INT NOT NULL, unit_id INT NOT NULL, description TEXT NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_of_consumption DATE DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_F2E714DFFF28C0D8 ON supply_part (supply_id)');
        $this->addSql('CREATE INDEX IDX_F2E714DFF8BD700D ON supply_part (unit_id)');
        $this->addSql(
            'ALTER TABLE supply_part ADD CONSTRAINT FK_F2E714DFFF28C0D8 FOREIGN KEY (supply_id) REFERENCES supply (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE supply_part ADD CONSTRAINT FK_F2E714DFF8BD700D FOREIGN KEY (unit_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
    }
}
