<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211008125600 extends AbstractMigration
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
        $this->addSql('ALTER TABLE supply_alerts DROP CONSTRAINT FK_26A577DA93035F72');
        $this->addSql('ALTER TABLE alert DROP CONSTRAINT FK_17FD46C1C54C8C93');
        $this->addSql('ALTER TABLE cron_report DROP CONSTRAINT FK_B6C6A7F5BE04EA9');
        $this->addSql('ALTER TABLE product_products_group DROP CONSTRAINT FK_DEF0C4394584665A');
        $this->addSql('ALTER TABLE recipe_position DROP CONSTRAINT FK_30BC416B4584665A');
        $this->addSql('ALTER TABLE shopping DROP CONSTRAINT FK_FB45F4394584665A');
        $this->addSql(
            'ALTER TABLE shopping_lists_clipboard_positions DROP CONSTRAINT FK_4CB220624584665A'
        );
        $this->addSql('ALTER TABLE shopping_lists_positions DROP CONSTRAINT FK_66FAB1A44584665A');
        $this->addSql('ALTER TABLE product_products_group DROP CONSTRAINT FK_DEF0C439EB55C9F4');
        $this->addSql('ALTER TABLE recipe_position DROP CONSTRAINT FK_30BC416BFE54D947');
        $this->addSql('ALTER TABLE shopping DROP CONSTRAINT FK_FB45F439FE54D947');
        $this->addSql(
            'ALTER TABLE shopping_lists_clipboard_positions DROP CONSTRAINT FK_4CB22062FE54D947'
        );
        $this->addSql('ALTER TABLE shopping_lists_positions DROP CONSTRAINT FK_66FAB1A4FE54D947');
        $this->addSql('ALTER TABLE supply DROP CONSTRAINT FK_D219948CFE54D947');
        $this->addSql('ALTER TABLE quick_lists_positions DROP CONSTRAINT FK_307E67243DAE168B');
        $this->addSql('ALTER TABLE recipe_position DROP CONSTRAINT FK_30BC416B59D8A214');
        $this->addSql('ALTER TABLE shopping_lists_positions DROP CONSTRAINT FK_66FAB1A43DAE168B');
        $this->addSql('ALTER TABLE shopping DROP CONSTRAINT FK_FB45F4394D16C4DD');
        $this->addSql('ALTER TABLE shopping_lists_positions DROP CONSTRAINT FK_66FAB1A44D16C4DD');
        $this->addSql('ALTER TABLE supply_alerts DROP CONSTRAINT FK_26A577DAFF28C0D8');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADF8BD700D');
        $this->addSql('ALTER TABLE products_group DROP CONSTRAINT FK_8D99BA15F8BD700D');
        $this->addSql('ALTER TABLE recipe_position DROP CONSTRAINT FK_30BC416BF8BD700D');
        $this->addSql('ALTER TABLE shopping DROP CONSTRAINT FK_FB45F439F8BD700D');
        $this->addSql(
            'ALTER TABLE shopping_lists_clipboard_positions DROP CONSTRAINT FK_4CB22062F8BD700D'
        );
        $this->addSql('ALTER TABLE shopping_lists_positions DROP CONSTRAINT FK_66FAB1A4F8BD700D');
        $this->addSql('ALTER TABLE units DROP CONSTRAINT FK_E9B07449627EA78A');
        $this->addSql('ALTER TABLE alert DROP CONSTRAINT FK_17FD46C1A76ED395');
        $this->addSql('ALTER TABLE note DROP CONSTRAINT FK_CFBDFA14A76ED395');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADA76ED395');
        $this->addSql('ALTER TABLE products_group DROP CONSTRAINT FK_8D99BA15A76ED395');
        $this->addSql('ALTER TABLE quick_lists DROP CONSTRAINT FK_50822821A76ED395');
        $this->addSql(
            'ALTER TABLE quick_lists_clipboard_positions DROP CONSTRAINT FK_5ADE467DA76ED395'
        );
        $this->addSql('ALTER TABLE recipe DROP CONSTRAINT FK_DA88B137A76ED395');
        $this->addSql('ALTER TABLE shopping DROP CONSTRAINT FK_FB45F439A76ED395');
        $this->addSql('ALTER TABLE shopping_lists DROP CONSTRAINT FK_984E7FFA76ED395');
        $this->addSql(
            'ALTER TABLE shopping_lists_clipboard_positions DROP CONSTRAINT FK_4CB22062A76ED395'
        );
        $this->addSql('ALTER TABLE shops DROP CONSTRAINT FK_237A6783A76ED395');
        $this->addSql('ALTER TABLE units DROP CONSTRAINT FK_E9B07449A76ED395');
        $this->addSql('DROP SEQUENCE alert_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE alert_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cron_job_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cron_report_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE note_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE products_group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quick_lists_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quick_lists_clipboard_positions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quick_lists_positions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recipe_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recipe_position_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE shopping_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE shopping_lists_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE shopping_lists_clipboard_positions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE shopping_lists_positions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE shops_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE supply_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE supply_alerts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE units_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP TABLE alert');
        $this->addSql('DROP TABLE alert_type');
        $this->addSql('DROP TABLE cron_job');
        $this->addSql('DROP TABLE cron_report');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_products_group');
        $this->addSql('DROP TABLE products_group');
        $this->addSql('DROP TABLE quick_lists');
        $this->addSql('DROP TABLE quick_lists_clipboard_positions');
        $this->addSql('DROP TABLE quick_lists_positions');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_position');
        $this->addSql('DROP TABLE shopping');
        $this->addSql('DROP TABLE shopping_lists');
        $this->addSql('DROP TABLE shopping_lists_clipboard_positions');
        $this->addSql('DROP TABLE shopping_lists_positions');
        $this->addSql('DROP TABLE shops');
        $this->addSql('DROP TABLE supply');
        $this->addSql('DROP TABLE supply_alerts');
        $this->addSql('DROP TABLE units');
        $this->addSql('DROP TABLE users');
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
        $this->addSql('CREATE SEQUENCE alert_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE alert_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cron_job_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cron_report_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE note_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE products_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quick_lists_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(
            'CREATE SEQUENCE quick_lists_clipboard_positions_id_seq INCREMENT BY 1 MINVALUE 1 START 1'
        );
        $this->addSql(
            'CREATE SEQUENCE quick_lists_positions_id_seq INCREMENT BY 1 MINVALUE 1 START 1'
        );
        $this->addSql('CREATE SEQUENCE recipe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE recipe_position_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE shopping_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE shopping_lists_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(
            'CREATE SEQUENCE shopping_lists_clipboard_positions_id_seq INCREMENT BY 1 MINVALUE 1 START 1'
        );
        $this->addSql(
            'CREATE SEQUENCE shopping_lists_positions_id_seq INCREMENT BY 1 MINVALUE 1 START 1'
        );
        $this->addSql('CREATE SEQUENCE shops_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE supply_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE supply_alerts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE units_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(
            'CREATE TABLE alert (id INT NOT NULL, user_id INT NOT NULL, type_id INT NOT NULL, description TEXT NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_17FD46C1A76ED395 ON alert (user_id)');
        $this->addSql('CREATE INDEX IDX_17FD46C1C54C8C93 ON alert (type_id)');
        $this->addSql(
            'CREATE TABLE alert_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql(
            'CREATE TABLE cron_job (id INT NOT NULL, name VARCHAR(191) NOT NULL, command VARCHAR(1024) NOT NULL, schedule VARCHAR(191) NOT NULL, description VARCHAR(191) NOT NULL, enabled BOOLEAN NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE UNIQUE INDEX un_name ON cron_job (name)');
        $this->addSql(
            'CREATE TABLE cron_report (id INT NOT NULL, job_id INT DEFAULT NULL, run_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, run_time DOUBLE PRECISION NOT NULL, exit_code INT NOT NULL, output TEXT NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_B6C6A7F5BE04EA9 ON cron_report (job_id)');
        $this->addSql(
            'CREATE TABLE note (id INT NOT NULL, user_id INT NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_CFBDFA14A76ED395 ON note (user_id)');
        $this->addSql(
            'CREATE TABLE product (id INT NOT NULL, unit_id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, default_amount DOUBLE PRECISION DEFAULT NULL, note TEXT DEFAULT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_D34A04ADF8BD700D ON product (unit_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADA76ED395 ON product (user_id)');
        $this->addSql(
            'CREATE TABLE product_products_group (product_id INT NOT NULL, products_group_id INT NOT NULL, PRIMARY KEY(product_id, products_group_id))'
        );
        $this->addSql('CREATE INDEX IDX_DEF0C4394584665A ON product_products_group (product_id)');
        $this->addSql(
            'CREATE INDEX IDX_DEF0C439EB55C9F4 ON product_products_group (products_group_id)'
        );
        $this->addSql(
            'CREATE TABLE products_group (id INT NOT NULL, unit_id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, note TEXT DEFAULT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_8D99BA15F8BD700D ON products_group (unit_id)');
        $this->addSql('CREATE INDEX IDX_8D99BA15A76ED395 ON products_group (user_id)');
        $this->addSql(
            'CREATE TABLE quick_lists (id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, note TEXT DEFAULT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_50822821A76ED395 ON quick_lists (user_id)');
        $this->addSql(
            'CREATE TABLE quick_lists_clipboard_positions (id INT NOT NULL, user_id INT NOT NULL, content VARCHAR(255) NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql(
            'CREATE INDEX IDX_5ADE467DA76ED395 ON quick_lists_clipboard_positions (user_id)'
        );
        $this->addSql(
            'CREATE TABLE quick_lists_positions (id INT NOT NULL, list_id INT NOT NULL, content VARCHAR(255) NOT NULL, checked BOOLEAN NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_307E67243DAE168B ON quick_lists_positions (list_id)');
        $this->addSql(
            'CREATE TABLE recipe (id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_DA88B137A76ED395 ON recipe (user_id)');
        $this->addSql(
            'CREATE TABLE recipe_position (id INT NOT NULL, unit_id INT NOT NULL, recipe_id INT NOT NULL, product_id INT DEFAULT NULL, group_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_30BC416BF8BD700D ON recipe_position (unit_id)');
        $this->addSql('CREATE INDEX IDX_30BC416B59D8A214 ON recipe_position (recipe_id)');
        $this->addSql('CREATE INDEX IDX_30BC416B4584665A ON recipe_position (product_id)');
        $this->addSql('CREATE INDEX IDX_30BC416BFE54D947 ON recipe_position (group_id)');
        $this->addSql(
            'CREATE TABLE shopping (id INT NOT NULL, user_id INT NOT NULL, product_id INT DEFAULT NULL, group_id INT DEFAULT NULL, shop_id INT NOT NULL, unit_id INT NOT NULL, date DATE NOT NULL, price DOUBLE PRECISION NOT NULL, promotion BOOLEAN NOT NULL, package BOOLEAN DEFAULT \'true\' NOT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_FB45F439A76ED395 ON shopping (user_id)');
        $this->addSql('CREATE INDEX IDX_FB45F4394584665A ON shopping (product_id)');
        $this->addSql('CREATE INDEX IDX_FB45F439FE54D947 ON shopping (group_id)');
        $this->addSql('CREATE INDEX IDX_FB45F4394D16C4DD ON shopping (shop_id)');
        $this->addSql('CREATE INDEX IDX_FB45F439F8BD700D ON shopping (unit_id)');
        $this->addSql(
            'CREATE TABLE shopping_lists (id INT NOT NULL, user_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, note TEXT DEFAULT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_984E7FFA76ED395 ON shopping_lists (user_id)');
        $this->addSql(
            'CREATE TABLE shopping_lists_clipboard_positions (id INT NOT NULL, group_id INT DEFAULT NULL, product_id INT DEFAULT NULL, unit_id INT NOT NULL, user_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql(
            'CREATE INDEX IDX_4CB22062FE54D947 ON shopping_lists_clipboard_positions (group_id)'
        );
        $this->addSql(
            'CREATE INDEX IDX_4CB220624584665A ON shopping_lists_clipboard_positions (product_id)'
        );
        $this->addSql(
            'CREATE INDEX IDX_4CB22062F8BD700D ON shopping_lists_clipboard_positions (unit_id)'
        );
        $this->addSql(
            'CREATE INDEX IDX_4CB22062A76ED395 ON shopping_lists_clipboard_positions (user_id)'
        );
        $this->addSql(
            'CREATE TABLE shopping_lists_positions (id INT NOT NULL, group_id INT DEFAULT NULL, product_id INT DEFAULT NULL, unit_id INT NOT NULL, list_id INT NOT NULL, shop_id INT DEFAULT NULL, unit_value DOUBLE PRECISION NOT NULL, checked BOOLEAN NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_66FAB1A4FE54D947 ON shopping_lists_positions (group_id)');
        $this->addSql('CREATE INDEX IDX_66FAB1A44584665A ON shopping_lists_positions (product_id)');
        $this->addSql('CREATE INDEX IDX_66FAB1A4F8BD700D ON shopping_lists_positions (unit_id)');
        $this->addSql('CREATE INDEX IDX_66FAB1A43DAE168B ON shopping_lists_positions (list_id)');
        $this->addSql('CREATE INDEX IDX_66FAB1A44D16C4DD ON shopping_lists_positions (shop_id)');
        $this->addSql(
            'CREATE TABLE shops (id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(40) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_237A6783A76ED395 ON shops (user_id)');
        $this->addSql(
            'CREATE TABLE supply (id INT NOT NULL, group_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, description TEXT DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_D219948CFE54D947 ON supply (group_id)');
        $this->addSql(
            'CREATE TABLE supply_alerts (id INT NOT NULL, alert_id INT NOT NULL, supply_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_26A577DA93035F72 ON supply_alerts (alert_id)');
        $this->addSql('CREATE INDEX IDX_26A577DAFF28C0D8 ON supply_alerts (supply_id)');
        $this->addSql(
            'CREATE TABLE units (id INT NOT NULL, main_id INT DEFAULT NULL, user_id INT NOT NULL, name VARCHAR(30) NOT NULL, shortcut VARCHAR(10) NOT NULL, converter DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_E9B07449627EA78A ON units (main_id)');
        $this->addSql('CREATE INDEX IDX_E9B07449A76ED395 ON units (user_id)');
        $this->addSql(
            'CREATE TABLE users (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, user_type INT NOT NULL, fb_id BIGINT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, hide_bought BOOLEAN NOT NULL, pagination INT NOT NULL, delete_unchecked_positions BOOLEAN DEFAULT \'false\' NOT NULL, delete_unchecked_positions_quick BOOLEAN DEFAULT \'false\' NOT NULL, max_shops_group_count INT NOT NULL, new_shopping_days INT NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql(
            'ALTER TABLE alert ADD CONSTRAINT FK_17FD46C1A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE alert ADD CONSTRAINT FK_17FD46C1C54C8C93 FOREIGN KEY (type_id) REFERENCES alert_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE cron_report ADD CONSTRAINT FK_B6C6A7F5BE04EA9 FOREIGN KEY (job_id) REFERENCES cron_job (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF8BD700D FOREIGN KEY (unit_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE product_products_group ADD CONSTRAINT FK_DEF0C4394584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE product_products_group ADD CONSTRAINT FK_DEF0C439EB55C9F4 FOREIGN KEY (products_group_id) REFERENCES products_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE products_group ADD CONSTRAINT FK_8D99BA15F8BD700D FOREIGN KEY (unit_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE products_group ADD CONSTRAINT FK_8D99BA15A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE quick_lists ADD CONSTRAINT FK_50822821A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE quick_lists_clipboard_positions ADD CONSTRAINT FK_5ADE467DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE quick_lists_positions ADD CONSTRAINT FK_307E67243DAE168B FOREIGN KEY (list_id) REFERENCES quick_lists (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE recipe_position ADD CONSTRAINT FK_30BC416BF8BD700D FOREIGN KEY (unit_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE recipe_position ADD CONSTRAINT FK_30BC416B59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE recipe_position ADD CONSTRAINT FK_30BC416B4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE recipe_position ADD CONSTRAINT FK_30BC416BFE54D947 FOREIGN KEY (group_id) REFERENCES products_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping ADD CONSTRAINT FK_FB45F439A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping ADD CONSTRAINT FK_FB45F4394584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping ADD CONSTRAINT FK_FB45F439FE54D947 FOREIGN KEY (group_id) REFERENCES products_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping ADD CONSTRAINT FK_FB45F4394D16C4DD FOREIGN KEY (shop_id) REFERENCES shops (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping ADD CONSTRAINT FK_FB45F439F8BD700D FOREIGN KEY (unit_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping_lists ADD CONSTRAINT FK_984E7FFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping_lists_clipboard_positions ADD CONSTRAINT FK_4CB22062FE54D947 FOREIGN KEY (group_id) REFERENCES products_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping_lists_clipboard_positions ADD CONSTRAINT FK_4CB220624584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping_lists_clipboard_positions ADD CONSTRAINT FK_4CB22062F8BD700D FOREIGN KEY (unit_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping_lists_clipboard_positions ADD CONSTRAINT FK_4CB22062A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping_lists_positions ADD CONSTRAINT FK_66FAB1A4FE54D947 FOREIGN KEY (group_id) REFERENCES products_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping_lists_positions ADD CONSTRAINT FK_66FAB1A44584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping_lists_positions ADD CONSTRAINT FK_66FAB1A4F8BD700D FOREIGN KEY (unit_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping_lists_positions ADD CONSTRAINT FK_66FAB1A43DAE168B FOREIGN KEY (list_id) REFERENCES shopping_lists (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shopping_lists_positions ADD CONSTRAINT FK_66FAB1A44D16C4DD FOREIGN KEY (shop_id) REFERENCES shops (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE shops ADD CONSTRAINT FK_237A6783A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE supply ADD CONSTRAINT FK_D219948CFE54D947 FOREIGN KEY (group_id) REFERENCES products_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE supply_alerts ADD CONSTRAINT FK_26A577DA93035F72 FOREIGN KEY (alert_id) REFERENCES alert (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE supply_alerts ADD CONSTRAINT FK_26A577DAFF28C0D8 FOREIGN KEY (supply_id) REFERENCES supply (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE units ADD CONSTRAINT FK_E9B07449627EA78A FOREIGN KEY (main_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE units ADD CONSTRAINT FK_E9B07449A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
    }
}
