<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200526221237 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE action_log (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, method VARCHAR(255) NOT NULL, uri LONGTEXT NOT NULL, ip VARCHAR(255) NOT NULL, request_at DATETIME NOT NULL, response_code SMALLINT NOT NULL, INDEX IDX_B2C5F685A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exception_log (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, method VARCHAR(255) DEFAULT NULL, uri LONGTEXT DEFAULT NULL, exception_message LONGTEXT NOT NULL, exception_code SMALLINT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_3A3168C1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_category (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, priority SMALLINT DEFAULT NULL, created_at DATETIME NOT NULL, active TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, entity_type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_21BF94265E237E06 (name), INDEX IDX_21BF9426B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_sub_category (id INT NOT NULL, category_id INT NOT NULL, writable TINYINT(1) NOT NULL, INDEX IDX_843D01DF12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_ip (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, ip VARCHAR(255) NOT NULL, login_at DATETIME NOT NULL, INDEX IDX_BDB407E8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE action_log ADD CONSTRAINT FK_B2C5F685A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exception_log ADD CONSTRAINT FK_3A3168C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_category ADD CONSTRAINT FK_21BF9426B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_sub_category ADD CONSTRAINT FK_843D01DF12469DE2 FOREIGN KEY (category_id) REFERENCES forum_category (id)');
        $this->addSql('ALTER TABLE forum_sub_category ADD CONSTRAINT FK_843D01DFBF396750 FOREIGN KEY (id) REFERENCES forum_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_ip ADD CONSTRAINT FK_BDB407E8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE staff_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE thanks_category CHANGE subtitle subtitle VARCHAR(255) DEFAULT NULL, CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_socialmedia CHANGE thanks_id thanks_id INT DEFAULT NULL, CHANGE staff_id staff_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_sub_category DROP FOREIGN KEY FK_843D01DF12469DE2');
        $this->addSql('ALTER TABLE forum_sub_category DROP FOREIGN KEY FK_843D01DFBF396750');
        $this->addSql('DROP TABLE action_log');
        $this->addSql('DROP TABLE exception_log');
        $this->addSql('DROP TABLE forum_category');
        $this->addSql('DROP TABLE forum_sub_category');
        $this->addSql('DROP TABLE user_ip');
        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE staff_category CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE thanks_category CHANGE subtitle subtitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE priority priority SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user_socialmedia CHANGE thanks_id thanks_id INT DEFAULT NULL, CHANGE staff_id staff_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
    }
}
