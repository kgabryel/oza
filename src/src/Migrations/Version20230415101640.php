<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230415101640 extends AbstractMigration
{
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE products_group DROP CONSTRAINT FK_8D99BA15A7BC5DF9');
        $this->addSql('DROP INDEX IDX_8D99BA15A7BC5DF9');
        $this->addSql('ALTER TABLE products_group DROP main_photo_id');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADA7BC5DF9');
        $this->addSql('DROP INDEX IDX_D34A04ADA7BC5DF9');
        $this->addSql('ALTER TABLE product DROP main_photo_id');
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

        $this->addSql('ALTER TABLE product ADD main_photo_id INT DEFAULT NULL');
        $this->addSql(
            'ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA7BC5DF9 FOREIGN KEY (main_photo_id) REFERENCES photo (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('CREATE INDEX IDX_D34A04ADA7BC5DF9 ON product (main_photo_id)');
        $this->addSql('ALTER TABLE products_group ADD main_photo_id INT DEFAULT NULL');
        $this->addSql(
            'ALTER TABLE products_group ADD CONSTRAINT FK_8D99BA15A7BC5DF9 FOREIGN KEY (main_photo_id) REFERENCES photo (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('CREATE INDEX IDX_8D99BA15A7BC5DF9 ON products_group (main_photo_id)');
    }
}
