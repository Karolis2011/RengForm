<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180205221632 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created DATETIME NOT NULL, INDEX IDX_64C19C171F7E88B (event_id), INDEX IDX_64C19C112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL, place VARCHAR(255) NOT NULL, created DATETIME NOT NULL, INDEX IDX_3BAE0AA77E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_organisation (user_id INT NOT NULL, organisation_id INT NOT NULL, INDEX IDX_662D4EB6A76ED395 (user_id), INDEX IDX_662D4EB69E6B1585 (organisation_id), PRIMARY KEY(user_id, organisation_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workshop (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, form_config_id INT DEFAULT NULL, location_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, place VARCHAR(255) NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, capacity INT NOT NULL, entries INT NOT NULL, created DATETIME NOT NULL, INDEX IDX_9B6F02C412469DE2 (category_id), INDEX IDX_9B6F02C455DBB98F (form_config_id), INDEX IDX_9B6F02C464D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration (id INT AUTO_INCREMENT NOT NULL, workshop_id INT DEFAULT NULL, data LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created DATETIME NOT NULL, INDEX IDX_62A8A7A71FDCE57C (workshop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_config (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, config LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created DATETIME NOT NULL, INDEX IDX_D664FBD27E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C112469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA77E3C61F9 FOREIGN KEY (owner_id) REFERENCES organisation (id)');
        $this->addSql('ALTER TABLE user_organisation ADD CONSTRAINT FK_662D4EB6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_organisation ADD CONSTRAINT FK_662D4EB69E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE workshop ADD CONSTRAINT FK_9B6F02C412469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE workshop ADD CONSTRAINT FK_9B6F02C455DBB98F FOREIGN KEY (form_config_id) REFERENCES form_config (id)');
        $this->addSql('ALTER TABLE workshop ADD CONSTRAINT FK_9B6F02C464D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A71FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id)');
        $this->addSql('ALTER TABLE form_config ADD CONSTRAINT FK_D664FBD27E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C112469DE2');
        $this->addSql('ALTER TABLE workshop DROP FOREIGN KEY FK_9B6F02C412469DE2');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C171F7E88B');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA77E3C61F9');
        $this->addSql('ALTER TABLE user_organisation DROP FOREIGN KEY FK_662D4EB69E6B1585');
        $this->addSql('ALTER TABLE user_organisation DROP FOREIGN KEY FK_662D4EB6A76ED395');
        $this->addSql('ALTER TABLE form_config DROP FOREIGN KEY FK_D664FBD27E3C61F9');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A71FDCE57C');
        $this->addSql('ALTER TABLE workshop DROP FOREIGN KEY FK_9B6F02C464D218E');
        $this->addSql('ALTER TABLE workshop DROP FOREIGN KEY FK_9B6F02C455DBB98F');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE organisation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_organisation');
        $this->addSql('DROP TABLE workshop');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE registration');
        $this->addSql('DROP TABLE form_config');
    }
}
