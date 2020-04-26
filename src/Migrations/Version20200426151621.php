<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200426151621 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE survey_comments (id INT AUTO_INCREMENT NOT NULL, survey_id INT NOT NULL, user_id INT NOT NULL, comment LONGTEXT NOT NULL, comment_at DATETIME NOT NULL, INDEX IDX_369D00DB3FE509D (survey_id), INDEX IDX_369D00DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE survey_comments ADD CONSTRAINT FK_369D00DB3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE survey_comments ADD CONSTRAINT FK_369D00DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE survey ADD slug VARCHAR(255) NOT NULL, CHANGE created_at from_the_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE survey_answer_list DROP created_at');
        $this->addSql('ALTER TABLE survey_answers ADD answer_id INT NOT NULL, DROP answer, DROP commment');
        $this->addSql('ALTER TABLE survey_answers ADD CONSTRAINT FK_14FCE5BDAA334807 FOREIGN KEY (answer_id) REFERENCES survey_answer_list (id)');
        $this->addSql('CREATE INDEX IDX_14FCE5BDAA334807 ON survey_answers (answer_id)');
        $this->addSql('ALTER TABLE thanks_category CHANGE subtitle subtitle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_socialmedia ADD user_id INT DEFAULT NULL, CHANGE thanks_id thanks_id INT DEFAULT NULL, CHANGE staff_id staff_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_socialmedia ADD CONSTRAINT FK_D103CBE6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D103CBE6A76ED395 ON user_socialmedia (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE survey_comments');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE survey DROP slug, CHANGE from_the_date created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE survey_answer_list ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE survey_answers DROP FOREIGN KEY FK_14FCE5BDAA334807');
        $this->addSql('DROP INDEX IDX_14FCE5BDAA334807 ON survey_answers');
        $this->addSql('ALTER TABLE survey_answers ADD answer VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD commment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, DROP answer_id');
        $this->addSql('ALTER TABLE thanks_category CHANGE subtitle subtitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user_socialmedia DROP FOREIGN KEY FK_D103CBE6A76ED395');
        $this->addSql('DROP INDEX IDX_D103CBE6A76ED395 ON user_socialmedia');
        $this->addSql('ALTER TABLE user_socialmedia DROP user_id, CHANGE thanks_id thanks_id INT DEFAULT NULL, CHANGE staff_id staff_id INT DEFAULT NULL');
    }
}
