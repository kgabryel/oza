<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220204205050 extends AbstractMigration
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
        $this->addSql('ALTER TABLE supply DROP CONSTRAINT FK_D219948CF8BD700D');
        $this->addSql('DROP INDEX IDX_D219948CF8BD700D');
        $this->addSql('ALTER TABLE supply DROP unit_id');
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
        $this->addSql('ALTER TABLE supply ADD unit_id INT');
        $this->addSql(
            'ALTER TABLE supply ADD CONSTRAINT FK_D219948CF8BD700D FOREIGN KEY (unit_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('CREATE INDEX IDX_D219948CF8BD700D ON supply (unit_id)');
        $this->addSql(
            'UPDATE supply set unit_id = products_group.unit_id from products_group where products_group.id = supply.group_id'
        );
        $this->addSql('ALTER TABLE supply alter column unit_id set NOT NULL');
    }
}
