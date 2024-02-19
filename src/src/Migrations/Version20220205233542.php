<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220205233542 extends AbstractMigration
{
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE supply_supply_group');
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

        $this->addSql(
            'CREATE TABLE supply_supply_group (supply_id INT NOT NULL, supply_group_id INT NOT NULL, PRIMARY KEY(supply_id, supply_group_id))'
        );
        $this->addSql('CREATE INDEX IDX_881DE67DFF28C0D8 ON supply_supply_group (supply_id)');
        $this->addSql('CREATE INDEX IDX_881DE67D568FB14E ON supply_supply_group (supply_group_id)');
        $this->addSql(
            'ALTER TABLE supply_supply_group ADD CONSTRAINT FK_881DE67DFF28C0D8 FOREIGN KEY (supply_id) REFERENCES supply (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE supply_supply_group ADD CONSTRAINT FK_881DE67D568FB14E FOREIGN KEY (supply_group_id) REFERENCES supply_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
    }
}
