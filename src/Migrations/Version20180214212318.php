<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180214212318 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created DATETIME NOT NULL, eventId INT DEFAULT NULL, categoryId INT DEFAULT NULL, INDEX IDX_FF3A7B972B2EBB6C (eventId), INDEX IDX_FF3A7B979C370B71 (categoryId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Event (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL, endDate DATETIME DEFAULT NULL, place VARCHAR(255) NOT NULL, created DATETIME NOT NULL, organisationId INT DEFAULT NULL, INDEX IDX_FA6F25A362C39F2F (organisationId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Organisation (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisations_users (organisationId INT NOT NULL, userId INT NOT NULL, INDEX IDX_14BDC08062C39F2F (organisationId), INDEX IDX_14BDC08064B64DCC (userId), PRIMARY KEY(organisationId, userId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE User (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Workshop (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, place VARCHAR(255) NOT NULL, startTime DATETIME NOT NULL, endTime DATETIME NOT NULL, capacity INT NOT NULL, entries INT NOT NULL, created DATETIME NOT NULL, categoryId INT DEFAULT NULL, formConfigId INT DEFAULT NULL, locationId INT DEFAULT NULL, INDEX IDX_621960929C370B71 (categoryId), INDEX IDX_62196092BEB47FCB (formConfigId), INDEX IDX_6219609296D7286D (locationId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Location (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Registration (id INT AUTO_INCREMENT NOT NULL, data LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created DATETIME NOT NULL, workshopId INT DEFAULT NULL, INDEX IDX_7A997C5F6B25ABFB (workshopId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE FormConfig (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, config JSON NOT NULL COMMENT \'(DC2Type:json_array)\', created DATETIME NOT NULL, organisationId INT DEFAULT NULL, INDEX IDX_950461C562C39F2F (organisationId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Category ADD CONSTRAINT FK_FF3A7B972B2EBB6C FOREIGN KEY (eventId) REFERENCES Event (id)');
        $this->addSql('ALTER TABLE Category ADD CONSTRAINT FK_FF3A7B979C370B71 FOREIGN KEY (categoryId) REFERENCES Category (id)');
        $this->addSql('ALTER TABLE Event ADD CONSTRAINT FK_FA6F25A362C39F2F FOREIGN KEY (organisationId) REFERENCES Organisation (id)');
        $this->addSql('ALTER TABLE organisations_users ADD CONSTRAINT FK_14BDC08062C39F2F FOREIGN KEY (organisationId) REFERENCES Organisation (id)');
        $this->addSql('ALTER TABLE organisations_users ADD CONSTRAINT FK_14BDC08064B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Workshop ADD CONSTRAINT FK_621960929C370B71 FOREIGN KEY (categoryId) REFERENCES Category (id)');
        $this->addSql('ALTER TABLE Workshop ADD CONSTRAINT FK_62196092BEB47FCB FOREIGN KEY (formConfigId) REFERENCES FormConfig (id)');
        $this->addSql('ALTER TABLE Workshop ADD CONSTRAINT FK_6219609296D7286D FOREIGN KEY (locationId) REFERENCES Location (id)');
        $this->addSql('ALTER TABLE Registration ADD CONSTRAINT FK_7A997C5F6B25ABFB FOREIGN KEY (workshopId) REFERENCES Workshop (id)');
        $this->addSql('ALTER TABLE FormConfig ADD CONSTRAINT FK_950461C562C39F2F FOREIGN KEY (organisationId) REFERENCES Organisation (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Category DROP FOREIGN KEY FK_FF3A7B979C370B71');
        $this->addSql('ALTER TABLE Workshop DROP FOREIGN KEY FK_621960929C370B71');
        $this->addSql('ALTER TABLE Category DROP FOREIGN KEY FK_FF3A7B972B2EBB6C');
        $this->addSql('ALTER TABLE Event DROP FOREIGN KEY FK_FA6F25A362C39F2F');
        $this->addSql('ALTER TABLE organisations_users DROP FOREIGN KEY FK_14BDC08062C39F2F');
        $this->addSql('ALTER TABLE FormConfig DROP FOREIGN KEY FK_950461C562C39F2F');
        $this->addSql('ALTER TABLE organisations_users DROP FOREIGN KEY FK_14BDC08064B64DCC');
        $this->addSql('ALTER TABLE Registration DROP FOREIGN KEY FK_7A997C5F6B25ABFB');
        $this->addSql('ALTER TABLE Workshop DROP FOREIGN KEY FK_6219609296D7286D');
        $this->addSql('ALTER TABLE Workshop DROP FOREIGN KEY FK_62196092BEB47FCB');
        $this->addSql('DROP TABLE Category');
        $this->addSql('DROP TABLE Event');
        $this->addSql('DROP TABLE Organisation');
        $this->addSql('DROP TABLE organisations_users');
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE Workshop');
        $this->addSql('DROP TABLE Location');
        $this->addSql('DROP TABLE Registration');
        $this->addSql('DROP TABLE FormConfig');
    }
}
