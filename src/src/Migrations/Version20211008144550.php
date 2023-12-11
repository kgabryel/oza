<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211008144550 extends AbstractMigration
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
        $this->addSql('DROP SEQUENCE settings_id_seq CASCADE');
        $this->addSql('DROP TABLE settings');
        $this->addSql('ALTER TABLE users ADD hide_bought BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE users ADD pagination INT NOT NULL');
        $this->addSql(
            'ALTER TABLE users ADD delete_unchecked_positions BOOLEAN DEFAULT \'false\' NOT NULL'
        );
        $this->addSql(
            'ALTER TABLE users ADD delete_unchecked_positions_quick BOOLEAN DEFAULT \'false\' NOT NULL'
        );
        $this->addSql('ALTER TABLE users ADD max_shops_group_count INT NOT NULL');
        $this->addSql('ALTER TABLE users ADD new_shopping_days INT NOT NULL');
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
        $this->addSql('CREATE SEQUENCE settings_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(
            'CREATE TABLE settings (id INT NOT NULL, user_id INT NOT NULL, hide_bought BOOLEAN NOT NULL, pagination INT NOT NULL, delete_unchecked_positions BOOLEAN NOT NULL, delete_unchecked_positions_quick BOOLEAN NOT NULL, max_shops_group_count INT NOT NULL, new_shopping_days INT NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E545A0C5A76ED395 ON settings (user_id)');
        $this->addSql(
            'ALTER TABLE settings ADD CONSTRAINT FK_E545A0C5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE users DROP hide_bought');
        $this->addSql('ALTER TABLE users DROP pagination');
        $this->addSql('ALTER TABLE users DROP delete_unchecked_positions');
        $this->addSql('ALTER TABLE users DROP delete_unchecked_positions_quick');
        $this->addSql('ALTER TABLE users DROP max_shops_group_count');
        $this->addSql('ALTER TABLE users DROP new_shopping_days');
    }
}
