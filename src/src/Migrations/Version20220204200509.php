<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220204200509 extends AbstractMigration
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
        $this->addSql('ALTER TABLE settings ALTER skip_unmodified_supply SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE products_group DROP CONSTRAINT FK_8D99BA15CCBBC969');
        $this->addSql('DROP INDEX IDX_8D99BA15CCBBC969');
        $this->addSql('ALTER TABLE products_group DROP base_unit_id');
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
        $this->addSql('ALTER TABLE products_group ADD base_unit_id INT');
        $this->addSql(
            'ALTER TABLE products_group ADD CONSTRAINT FK_8D99BA15CCBBC969 FOREIGN KEY (base_unit_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('CREATE INDEX IDX_8D99BA15CCBBC969 ON products_group (base_unit_id)');
        $this->addSql('ALTER TABLE settings ALTER skip_unmodified_supply DROP DEFAULT');
        $this->addSql('UPDATE products_group set base_unit_id = unit_id');
        $this->addSql('ALTER TABLE products_group alter column base_unit_id set NOT NULL');
    }
}
