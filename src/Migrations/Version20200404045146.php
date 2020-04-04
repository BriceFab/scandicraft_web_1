<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200404045146 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE survey (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, answer_delay INT NOT NULL, INDEX IDX_AD5F9BFCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_survey_answer_list (survey_id INT NOT NULL, survey_answer_list_id INT NOT NULL, INDEX IDX_B3C93869B3FE509D (survey_id), INDEX IDX_B3C93869C6E3B857 (survey_answer_list_id), PRIMARY KEY(survey_id, survey_answer_list_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_answer_list (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, answer VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_A21BC4DDB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_answers (id INT AUTO_INCREMENT NOT NULL, survey_id INT NOT NULL, date DATETIME NOT NULL, answer VARCHAR(255) NOT NULL, commment VARCHAR(255) DEFAULT NULL, INDEX IDX_14FCE5BDB3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_answers_user (survey_answers_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4B30861C3CC511F9 (survey_answers_id), INDEX IDX_4B30861CA76ED395 (user_id), PRIMARY KEY(survey_answers_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE survey ADD CONSTRAINT FK_AD5F9BFCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE survey_survey_answer_list ADD CONSTRAINT FK_B3C93869B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey_survey_answer_list ADD CONSTRAINT FK_B3C93869C6E3B857 FOREIGN KEY (survey_answer_list_id) REFERENCES survey_answer_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey_answer_list ADD CONSTRAINT FK_A21BC4DDB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE survey_answers ADD CONSTRAINT FK_14FCE5BDB3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE survey_answers_user ADD CONSTRAINT FK_4B30861C3CC511F9 FOREIGN KEY (survey_answers_id) REFERENCES survey_answers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey_answers_user ADD CONSTRAINT FK_4B30861CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE survey_survey_answer_list DROP FOREIGN KEY FK_B3C93869B3FE509D');
        $this->addSql('ALTER TABLE survey_answers DROP FOREIGN KEY FK_14FCE5BDB3FE509D');
        $this->addSql('ALTER TABLE survey_survey_answer_list DROP FOREIGN KEY FK_B3C93869C6E3B857');
        $this->addSql('ALTER TABLE survey_answers_user DROP FOREIGN KEY FK_4B30861C3CC511F9');
        $this->addSql('DROP TABLE survey');
        $this->addSql('DROP TABLE survey_survey_answer_list');
        $this->addSql('DROP TABLE survey_answer_list');
        $this->addSql('DROP TABLE survey_answers');
        $this->addSql('DROP TABLE survey_answers_user');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\'');
    }
}
