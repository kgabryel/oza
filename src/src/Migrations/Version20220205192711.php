<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220205192711 extends AbstractMigration
{
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE settings DROP delete_lists');
        $this->addSql('ALTER TABLE settings DROP delete_quick_lists');
        $this->addSql('ALTER TABLE settings DROP delete_list_days');
        $this->addSql('ALTER TABLE settings DROP delete_quick_list_days');
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

        $this->addSql('ALTER TABLE settings ADD delete_lists BOOLEAN NOT NULL default false');
        $this->addSql('ALTER TABLE settings ADD delete_quick_lists BOOLEAN NOT NULL default false');
        $this->addSql('ALTER TABLE settings ADD delete_list_days INT NOT NULL default 30');
        $this->addSql('ALTER TABLE settings ADD delete_quick_list_days INT NOT NULL default 30');
    }
}
