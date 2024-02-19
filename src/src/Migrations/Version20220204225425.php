<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220204225425 extends AbstractMigration
{
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()
                ->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE supply_alerts DROP CONSTRAINT FK_26A577DAF8BD700D');
        $this->addSql('DROP INDEX IDX_26A577DAF8BD700D');
        $this->addSql('ALTER TABLE supply_alerts DROP unit_id');
    }

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()
                ->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );
        $this->addSql('ALTER TABLE supply_alerts ADD unit_id INT');
        $this->addSql(
            'ALTER TABLE supply_alerts ADD CONSTRAINT FK_26A577DAF8BD700D FOREIGN KEY (unit_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('CREATE INDEX IDX_26A577DAF8BD700D ON supply_alerts (unit_id)');
        $this->addSql(
            'UPDATE supply_alerts set unit_id = supply.unit_id from supply where supply.id = supply_alerts.supply_id'
        );
        $this->addSql('ALTER TABLE supply_alerts alter column unit_id set NOT NULL');
    }
}
