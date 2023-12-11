<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211010204500 extends AbstractMigration
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
        $this->addSql('CREATE SEQUENCE recipe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE recipe_position_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(
            'CREATE TABLE recipe (id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX idx_da88b137a76ed395 ON recipe (user_id)');
        $this->addSql(
            'CREATE TABLE recipe_position (id INT NOT NULL, unit_id INT NOT NULL, recipe_id INT NOT NULL, product_id INT DEFAULT NULL, group_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX idx_30bc416b4584665a ON recipe_position (product_id)');
        $this->addSql('CREATE INDEX idx_30bc416bfe54d947 ON recipe_position (group_id)');
        $this->addSql('CREATE INDEX idx_30bc416b59d8a214 ON recipe_position (recipe_id)');
        $this->addSql('CREATE INDEX idx_30bc416bf8bd700d ON recipe_position (unit_id)');
        $this->addSql(
            'ALTER TABLE recipe ADD CONSTRAINT fk_da88b137a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE recipe_position ADD CONSTRAINT fk_30bc416bf8bd700d FOREIGN KEY (unit_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE recipe_position ADD CONSTRAINT fk_30bc416b59d8a214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE recipe_position ADD CONSTRAINT fk_30bc416b4584665a FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE recipe_position ADD CONSTRAINT fk_30bc416bfe54d947 FOREIGN KEY (group_id) REFERENCES products_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
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
        $this->addSql('ALTER TABLE recipe_position DROP CONSTRAINT fk_30bc416b59d8a214');
        $this->addSql('DROP SEQUENCE recipe_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recipe_position_id_seq CASCADE');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_position');
    }
}
