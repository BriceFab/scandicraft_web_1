<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200404050147 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE survey_answers_user');
        $this->addSql('ALTER TABLE survey ADD title VARCHAR(255) NOT NULL, ADD description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE survey_answers ADD user_id INT NOT NULL, CHANGE commment commment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE survey_answers ADD CONSTRAINT FK_14FCE5BDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_14FCE5BDA76ED395 ON survey_answers (user_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE survey_answers_user (survey_answers_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4B30861CA76ED395 (user_id), INDEX IDX_4B30861C3CC511F9 (survey_answers_id), PRIMARY KEY(survey_answers_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE survey_answers_user ADD CONSTRAINT FK_4B30861C3CC511F9 FOREIGN KEY (survey_answers_id) REFERENCES survey_answers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey_answers_user ADD CONSTRAINT FK_4B30861CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey DROP title, DROP description');
        $this->addSql('ALTER TABLE survey_answers DROP FOREIGN KEY FK_14FCE5BDA76ED395');
        $this->addSql('DROP INDEX IDX_14FCE5BDA76ED395 ON survey_answers');
        $this->addSql('ALTER TABLE survey_answers DROP user_id, CHANGE commment commment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\'');
    }
}
