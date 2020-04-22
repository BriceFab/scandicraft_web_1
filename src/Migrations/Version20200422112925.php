<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200422112925 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE socialmedia_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thanks (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, thanks_category_id INT NOT NULL, presentation LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_6E5365EA76ED395 (user_id), INDEX IDX_6E5365EFBBF98EE (thanks_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thanks_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_socialmedia (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, socialmedia_type_id INT NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_D103CBE6A76ED395 (user_id), INDEX IDX_D103CBE62263C14C (socialmedia_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE thanks ADD CONSTRAINT FK_6E5365EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE thanks ADD CONSTRAINT FK_6E5365EFBBF98EE FOREIGN KEY (thanks_category_id) REFERENCES thanks_category (id)');
        $this->addSql('ALTER TABLE user_socialmedia ADD CONSTRAINT FK_D103CBE6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_socialmedia ADD CONSTRAINT FK_D103CBE62263C14C FOREIGN KEY (socialmedia_type_id) REFERENCES socialmedia_type (id)');
        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE survey_answers CHANGE commment commment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_socialmedia DROP FOREIGN KEY FK_D103CBE62263C14C');
        $this->addSql('ALTER TABLE thanks DROP FOREIGN KEY FK_6E5365EFBBF98EE');
        $this->addSql('DROP TABLE socialmedia_type');
        $this->addSql('DROP TABLE thanks');
        $this->addSql('DROP TABLE thanks_category');
        $this->addSql('DROP TABLE user_socialmedia');
        $this->addSql('ALTER TABLE dev_progression CHANGE under_maintenance under_maintenance TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE survey_answers CHANGE commment commment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\'');
    }
}
