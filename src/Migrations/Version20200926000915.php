<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200926000915 extends AbstractMigration
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
        $this->addSql('CREATE TABLE store_article (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, duration INT DEFAULT NULL, enable TINYINT(1) NOT NULL, price INT NOT NULL, until_date DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_1B322F81B03A8386 (created_by_id), INDEX IDX_1B322F8112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_article_attachment (store_article_id INT NOT NULL, attachment_id INT NOT NULL, INDEX IDX_333B5EE85C7DDD71 (store_article_id), INDEX IDX_333B5EE8464E68B (attachment_id), PRIMARY KEY(store_article_id, attachment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_offers ADD CONSTRAINT FK_FF7E9FB1DC058279 FOREIGN KEY (payment_type_id) REFERENCES payment_types (id)');
        $this->addSql('ALTER TABLE payment_offers ADD CONSTRAINT FK_FF7E9FB1B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE payment_types ADD CONSTRAINT FK_E79BF82B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE store_article ADD CONSTRAINT FK_1B322F81B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE store_article ADD CONSTRAINT FK_1B322F8112469DE2 FOREIGN KEY (category_id) REFERENCES store_category (id)');
        $this->addSql('ALTER TABLE store_article_attachment ADD CONSTRAINT FK_333B5EE85C7DDD71 FOREIGN KEY (store_article_id) REFERENCES store_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_article_attachment ADD CONSTRAINT FK_333B5EE8464E68B FOREIGN KEY (attachment_id) REFERENCES attachment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE action_log CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE attachment CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE alt alt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE exception_log CHANGE user_id user_id INT DEFAULT NULL, CHANGE method method VARCHAR(255) DEFAULT NULL, CHANGE exception_code exception_code SMALLINT DEFAULT NULL, CHANGE ip ip VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE sub_category_id sub_category_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion_answer CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE discussion_id discussion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion_status CHANGE info info VARCHAR(255) DEFAULT NULL, CHANGE color color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_sub_category CHANGE sub_title sub_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE spoil CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE goal_type_id goal_type_id INT DEFAULT NULL, CHANGE share_goal share_goal INT DEFAULT NULL');
        $this->addSql('ALTER TABLE staff_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE thanks_category CHANGE subtitle subtitle VARCHAR(255) DEFAULT NULL, CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD credit INT NOT NULL, CHANGE roles roles JSON NOT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE uuid uuid VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_ip CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_socialmedia CHANGE thanks_id thanks_id INT DEFAULT NULL, CHANGE staff_id staff_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_vote CHANGE user_id user_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE vote_id vote_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vote_site CHANGE created_at created_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE payment_offers DROP FOREIGN KEY FK_FF7E9FB1DC058279');
        $this->addSql('ALTER TABLE store_article_attachment DROP FOREIGN KEY FK_333B5EE85C7DDD71');
        $this->addSql('ALTER TABLE store_article DROP FOREIGN KEY FK_1B322F8112469DE2');
        $this->addSql('DROP TABLE payment_offers');
        $this->addSql('DROP TABLE payment_types');
        $this->addSql('DROP TABLE store_article');
        $this->addSql('DROP TABLE store_article_attachment');
        $this->addSql('DROP TABLE store_category');
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
        $this->addSql('ALTER TABLE thanks_category CHANGE subtitle subtitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP credit, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE uuid uuid VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_ip CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_socialmedia CHANGE thanks_id thanks_id INT DEFAULT NULL, CHANGE staff_id staff_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_vote CHANGE user_id user_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT \'NULL\', CHANGE vote_id vote_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE vote_site CHANGE created_at created_at DATETIME DEFAULT \'NULL\'');
    }
}
