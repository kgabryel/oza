<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220325214409 extends AbstractMigration
{
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE supply ADD unit_id INT NOT NULL');
        $this->addSql('ALTER TABLE supply ADD amount DOUBLE PRECISION NOT NULL');
        $this->addSql(
            'ALTER TABLE supply ADD CONSTRAINT fk_d219948cf8bd700d FOREIGN KEY (unit_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('CREATE INDEX idx_d219948cf8bd700d ON supply (unit_id)');
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

        $this->addSql('ALTER TABLE supply DROP CONSTRAINT fk_d219948cf8bd700d');
        $this->addSql('DROP INDEX idx_d219948cf8bd700d');
        $this->addSql('ALTER TABLE supply DROP unit_id');
        $this->addSql('ALTER TABLE supply DROP amount');
    }
}
