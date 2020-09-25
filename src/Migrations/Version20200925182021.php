<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200925182021 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE payment_offers (id INT AUTO_INCREMENT NOT NULL, payment_type_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, enable TINYINT(1) NOT NULL, debit_price NUMERIC(5, 2) NOT NULL, credit_receive INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_FF7E9FB1DC058279 (payment_type_id), INDEX IDX_FF7E9FB1B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_types (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, help_text VARCHAR(255) NOT NULL, dynamic_key VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_E79BF82B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_offers ADD CONSTRAINT FK_FF7E9FB1DC058279 FOREIGN KEY (payment_type_id) REFERENCES payment_types (id)');
        $this->addSql('ALTER TABLE payment_offers ADD CONSTRAINT FK_FF7E9FB1B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE payment_types ADD CONSTRAINT FK_E79BF82B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_sub_category CHANGE sub_title sub_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE sub_category_id sub_category_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion_answer CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE discussion_id discussion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE spoil CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE goal_type_id goal_type_id INT DEFAULT NULL, CHANGE share_goal share_goal INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vote_site CHANGE created_at created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE action_log CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE attachment CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE alt alt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE exception_log CHANGE user_id user_id INT DEFAULT NULL, CHANGE method method VARCHAR(255) DEFAULT NULL, CHANGE exception_code exception_code SMALLINT DEFAULT NULL, CHANGE ip ip VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion_status CHANGE info info VARCHAR(255) DEFAULT NULL, CHANGE color color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE staff_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_article CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE duration duration INT DEFAULT NULL, CHANGE until_date until_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE thanks_category CHANGE subtitle subtitle VARCHAR(255) DEFAULT NULL, CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE uuid uuid VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_ip CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_socialmedia CHANGE thanks_id thanks_id INT DEFAULT NULL, CHANGE staff_id staff_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_vote CHANGE user_id user_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE vote_id vote_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE payment_offers DROP FOREIGN KEY FK_FF7E9FB1DC058279');
        $this->addSql('DROP TABLE payment_offers');
        $this->addSql('DROP TABLE payment_types');
        $this->addSql('ALTER TABLE action_log CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE attachment CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE alt alt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE exception_log CHANGE user_id user_id INT DEFAULT NULL, CHANGE method method VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE exception_code exception_code SMALLINT DEFAULT NULL, CHANGE ip ip VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE forum_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE sub_category_id sub_category_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion_answer CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE discussion_id discussion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion_status CHANGE info info VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE color color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE forum_sub_category CHANGE sub_title sub_title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE spoil CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE goal_type_id goal_type_id INT DEFAULT NULL, CHANGE share_goal share_goal INT DEFAULT NULL');
        $this->addSql('ALTER TABLE staff_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_article CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE duration duration INT DEFAULT NULL, CHANGE until_date until_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE thanks_category CHANGE subtitle subtitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE uuid uuid VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_ip CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_socialmedia CHANGE thanks_id thanks_id INT DEFAULT NULL, CHANGE staff_id staff_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_vote CHANGE user_id user_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT \'NULL\', CHANGE vote_id vote_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE vote_site CHANGE created_at created_at DATETIME DEFAULT \'NULL\'');
    }
}
