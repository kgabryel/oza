<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230907180820 extends AbstractMigration
{
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE log ALTER context TYPE TEXT');
        $this->addSql('ALTER TABLE log ALTER context DROP DEFAULT');
        $this->addSql('ALTER TABLE log ALTER extra TYPE TEXT');
        $this->addSql('ALTER TABLE log ALTER extra DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN log.context IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN log.extra IS \'(DC2Type:array)\'');
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

        $this->addSql('ALTER TABLE log ALTER context TYPE TEXT');
        $this->addSql('ALTER TABLE log ALTER context DROP DEFAULT');
        $this->addSql('ALTER TABLE log ALTER extra TYPE TEXT');
        $this->addSql('ALTER TABLE log ALTER extra DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN log.context IS NULL');
        $this->addSql('COMMENT ON COLUMN log.extra IS NULL');
    }
}
