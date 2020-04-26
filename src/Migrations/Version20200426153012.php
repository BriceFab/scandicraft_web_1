<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200426153012 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE survey CHANGE created_at from_the_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE survey_answers ADD answer_id INT NOT NULL, DROP answer');
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

        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE survey CHANGE from_the_date created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE survey_answers DROP FOREIGN KEY FK_14FCE5BDAA334807');
        $this->addSql('DROP INDEX IDX_14FCE5BDAA334807 ON survey_answers');
        $this->addSql('ALTER TABLE survey_answers ADD answer VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP answer_id');
        $this->addSql('ALTER TABLE thanks_category CHANGE subtitle subtitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user_socialmedia DROP FOREIGN KEY FK_D103CBE6A76ED395');
        $this->addSql('DROP INDEX IDX_D103CBE6A76ED395 ON user_socialmedia');
        $this->addSql('ALTER TABLE user_socialmedia DROP user_id, CHANGE thanks_id thanks_id INT DEFAULT NULL, CHANGE staff_id staff_id INT DEFAULT NULL');
    }
}
