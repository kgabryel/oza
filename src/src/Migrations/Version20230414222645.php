<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414222645 extends AbstractMigration
{
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE photo_id_seq CASCADE');
        $this->addSql('DROP TABLE photo');
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

        $this->addSql('CREATE SEQUENCE photo_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(
            'CREATE TABLE photo (id INT NOT NULL, product_id INT DEFAULT NULL, products_group_id INT DEFAULT NULL, user_id INT NOT NULL, file_name VARCHAR(36) NOT NULL, height INT NOT NULL, type VARCHAR(100) NOT NULL, width INT NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_14B784184584665A ON photo (product_id)');
        $this->addSql('CREATE INDEX IDX_14B78418EB55C9F4 ON photo (products_group_id)');
        $this->addSql('CREATE INDEX IDX_14B78418A76ED395 ON photo (user_id)');
        $this->addSql(
            'ALTER TABLE photo ADD CONSTRAINT FK_14B784184584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE photo ADD CONSTRAINT FK_14B78418EB55C9F4 FOREIGN KEY (products_group_id) REFERENCES products_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE photo ADD CONSTRAINT FK_14B78418A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
    }
}
