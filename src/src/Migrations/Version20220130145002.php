<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220130145002 extends AbstractMigration
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
        $this->addSql('ALTER TABLE settings DROP skip_unmodified_supply');
        $this->addSql('ALTER TABLE settings ALTER create_supply SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE shopping ALTER original_price SET DEFAULT \'1\'');
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
        $this->addSql(
            'ALTER TABLE settings ADD skip_unmodified_supply BOOLEAN NOT NULL default false'
        );
        $this->addSql('ALTER TABLE settings ALTER create_supply DROP DEFAULT');
        $this->addSql('ALTER TABLE shopping ALTER original_price DROP DEFAULT');
    }
}
