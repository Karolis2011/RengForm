<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180327202928 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Event (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', owner_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, place VARCHAR(255) NOT NULL, duration TIME NOT NULL, capacity INT DEFAULT NULL, created DATETIME NOT NULL, formConfig_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_FA6F25A339D04404 (formConfig_id), INDEX IDX_FA6F25A37E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE EventTime (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', event_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', startTime DATETIME NOT NULL, entries INT NOT NULL, INDEX IDX_11F369F171F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Event ADD CONSTRAINT FK_FA6F25A339D04404 FOREIGN KEY (formConfig_id) REFERENCES FormConfig (id)');
        $this->addSql('ALTER TABLE Event ADD CONSTRAINT FK_FA6F25A37E3C61F9 FOREIGN KEY (owner_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE EventTime ADD CONSTRAINT FK_11F369F171F7E88B FOREIGN KEY (event_id) REFERENCES Event (id)');
        $this->addSql('ALTER TABLE Category CHANGE orderNo orderNo INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Workshop CHANGE capacity capacity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE MultiEvent CHANGE endDate endDate DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE Registration ADD eventTime_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE workshopTime_id workshopTime_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Registration ADD CONSTRAINT FK_7A997C5F5AFD7476 FOREIGN KEY (eventTime_id) REFERENCES EventTime (id)');
        $this->addSql('CREATE INDEX IDX_7A997C5F5AFD7476 ON Registration (eventTime_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE EventTime DROP FOREIGN KEY FK_11F369F171F7E88B');
        $this->addSql('ALTER TABLE Registration DROP FOREIGN KEY FK_7A997C5F5AFD7476');
        $this->addSql('DROP TABLE Event');
        $this->addSql('DROP TABLE EventTime');
        $this->addSql('ALTER TABLE Category CHANGE orderNo orderNo INT DEFAULT NULL');
        $this->addSql('ALTER TABLE MultiEvent CHANGE endDate endDate DATETIME DEFAULT \'NULL\'');
        $this->addSql('DROP INDEX IDX_7A997C5F5AFD7476 ON Registration');
        $this->addSql('ALTER TABLE Registration DROP eventTime_id, CHANGE workshopTime_id workshopTime_id CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Workshop CHANGE capacity capacity INT DEFAULT NULL');
    }
}
