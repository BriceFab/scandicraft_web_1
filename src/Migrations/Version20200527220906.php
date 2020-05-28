<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200527220906 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE action_log CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE exception_log CHANGE user_id user_id INT DEFAULT NULL, CHANGE method method VARCHAR(255) DEFAULT NULL, CHANGE exception_code exception_code SMALLINT DEFAULT NULL, CHANGE ip ip VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE sub_category_id sub_category_id INT DEFAULT NULL, CHANGE pin pin TINYINT(1) DEFAULT NULL, CHANGE archive archive TINYINT(1) DEFAULT NULL, CHANGE staff_only staff_only TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_sub_category CHANGE writable writable TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE staff_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE thanks_category CHANGE subtitle subtitle VARCHAR(255) DEFAULT NULL, CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_ip CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_socialmedia CHANGE thanks_id thanks_id INT DEFAULT NULL, CHANGE staff_id staff_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE action_log CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE exception_log CHANGE user_id user_id INT DEFAULT NULL, CHANGE method method VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE exception_code exception_code SMALLINT DEFAULT NULL, CHANGE ip ip VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE forum_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE sub_category_id sub_category_id INT DEFAULT NULL, CHANGE pin pin TINYINT(1) DEFAULT \'NULL\', CHANGE archive archive TINYINT(1) DEFAULT \'NULL\', CHANGE staff_only staff_only TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE forum_sub_category CHANGE writable writable TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE staff_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE thanks_category CHANGE subtitle subtitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user_ip CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_socialmedia CHANGE thanks_id thanks_id INT DEFAULT NULL, CHANGE staff_id staff_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
    }
}
