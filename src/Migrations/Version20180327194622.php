<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180327194622 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Category (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', event_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created DATETIME NOT NULL, orderNo INT DEFAULT NULL, INDEX IDX_FF3A7B9771F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE User (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', username VARCHAR(50) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_2DA17977F85E0677 (username), UNIQUE INDEX UNIQ_2DA17977E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Workshop (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', category_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, place VARCHAR(255) NOT NULL, duration TIME NOT NULL, capacity INT DEFAULT NULL, created DATETIME NOT NULL, formConfig_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_6219609239D04404 (formConfig_id), INDEX IDX_6219609212469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE MultiEvent (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', owner_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL, endDate DATETIME DEFAULT NULL, place VARCHAR(255) NOT NULL, created DATETIME NOT NULL, INDEX IDX_BE2FAF1C7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE WorkshopTime (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', workshop_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', startTime DATETIME NOT NULL, entries INT NOT NULL, INDEX IDX_A8847C181FDCE57C (workshop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Registration (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', data JSON NOT NULL COMMENT \'(DC2Type:json_array)\', created DATETIME NOT NULL, workshopTime_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_7A997C5F95EE780D (workshopTime_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE FormConfig (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', owner_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, config JSON NOT NULL COMMENT \'(DC2Type:json_array)\', created DATETIME NOT NULL, INDEX IDX_950461C57E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Category ADD CONSTRAINT FK_FF3A7B9771F7E88B FOREIGN KEY (event_id) REFERENCES MultiEvent (id)');
        $this->addSql('ALTER TABLE Workshop ADD CONSTRAINT FK_6219609239D04404 FOREIGN KEY (formConfig_id) REFERENCES FormConfig (id)');
        $this->addSql('ALTER TABLE Workshop ADD CONSTRAINT FK_6219609212469DE2 FOREIGN KEY (category_id) REFERENCES Category (id)');
        $this->addSql('ALTER TABLE MultiEvent ADD CONSTRAINT FK_BE2FAF1C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE WorkshopTime ADD CONSTRAINT FK_A8847C181FDCE57C FOREIGN KEY (workshop_id) REFERENCES Workshop (id)');
        $this->addSql('ALTER TABLE Registration ADD CONSTRAINT FK_7A997C5F95EE780D FOREIGN KEY (workshopTime_id) REFERENCES WorkshopTime (id)');
        $this->addSql('ALTER TABLE FormConfig ADD CONSTRAINT FK_950461C57E3C61F9 FOREIGN KEY (owner_id) REFERENCES User (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Workshop DROP FOREIGN KEY FK_6219609212469DE2');
        $this->addSql('ALTER TABLE MultiEvent DROP FOREIGN KEY FK_BE2FAF1C7E3C61F9');
        $this->addSql('ALTER TABLE FormConfig DROP FOREIGN KEY FK_950461C57E3C61F9');
        $this->addSql('ALTER TABLE WorkshopTime DROP FOREIGN KEY FK_A8847C181FDCE57C');
        $this->addSql('ALTER TABLE Category DROP FOREIGN KEY FK_FF3A7B9771F7E88B');
        $this->addSql('ALTER TABLE Registration DROP FOREIGN KEY FK_7A997C5F95EE780D');
        $this->addSql('ALTER TABLE Workshop DROP FOREIGN KEY FK_6219609239D04404');
        $this->addSql('DROP TABLE Category');
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE Workshop');
        $this->addSql('DROP TABLE MultiEvent');
        $this->addSql('DROP TABLE WorkshopTime');
        $this->addSql('DROP TABLE Registration');
        $this->addSql('DROP TABLE FormConfig');
    }
}
