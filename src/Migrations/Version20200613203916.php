<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200613203916 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE spoil (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, created_at DATETIME NOT NULL, image LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, share_goal INT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_A0DFBC1EB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spoil_share (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, spoil_id INT NOT NULL, social_id INT NOT NULL, share_at DATETIME NOT NULL, INDEX IDX_2E9A0397A76ED395 (user_id), INDEX IDX_2E9A03972EE5651B (spoil_id), INDEX IDX_2E9A0397FFEB5B27 (social_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE spoil ADD CONSTRAINT FK_A0DFBC1EB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE spoil_share ADD CONSTRAINT FK_2E9A0397A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE spoil_share ADD CONSTRAINT FK_2E9A03972EE5651B FOREIGN KEY (spoil_id) REFERENCES spoil (id)');
        $this->addSql('ALTER TABLE spoil_share ADD CONSTRAINT FK_2E9A0397FFEB5B27 FOREIGN KEY (social_id) REFERENCES socialmedia_type (id)');
        $this->addSql('ALTER TABLE action_log CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE exception_log CHANGE user_id user_id INT DEFAULT NULL, CHANGE method method VARCHAR(255) DEFAULT NULL, CHANGE exception_code exception_code SMALLINT DEFAULT NULL, CHANGE ip ip VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE sub_category_id sub_category_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion_answer CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE discussion_id discussion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion_status CHANGE info info VARCHAR(255) DEFAULT NULL, CHANGE color color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_sub_category CHANGE sub_title sub_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE staff_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE thanks_category CHANGE subtitle subtitle VARCHAR(255) DEFAULT NULL, CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE uuid uuid VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_ip CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_socialmedia CHANGE thanks_id thanks_id INT DEFAULT NULL, CHANGE staff_id staff_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE spoil_share DROP FOREIGN KEY FK_2E9A03972EE5651B');
        $this->addSql('DROP TABLE spoil');
        $this->addSql('DROP TABLE spoil_share');
        $this->addSql('ALTER TABLE action_log CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE exception_log CHANGE user_id user_id INT DEFAULT NULL, CHANGE method method VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE exception_code exception_code SMALLINT DEFAULT NULL, CHANGE ip ip VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE forum_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE sub_category_id sub_category_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion_answer CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE discussion_id discussion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion_status CHANGE info info VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE color color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE forum_sub_category CHANGE sub_title sub_title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE staff_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE thanks_category CHANGE subtitle subtitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE uuid uuid VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_ip CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_socialmedia CHANGE thanks_id thanks_id INT DEFAULT NULL, CHANGE staff_id staff_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
    }
}
