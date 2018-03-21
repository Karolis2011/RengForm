<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180321205603 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Category CHANGE created created DATETIME NOT NULL, CHANGE eventId eventId INT DEFAULT NULL, CHANGE categoryId categoryId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Event CHANGE endDate endDate DATETIME DEFAULT NULL, CHANGE organisationId organisationId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Workshop CHANGE capacity capacity INT DEFAULT NULL, CHANGE created created DATETIME NOT NULL, CHANGE categoryId categoryId INT DEFAULT NULL, CHANGE formConfigId formConfigId INT DEFAULT NULL, CHANGE locationId locationId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE WorkshopTime CHANGE startTime startTime DATETIME NOT NULL, CHANGE workshopId workshopId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Registration DROP FOREIGN KEY FK_7A997C5F6B25ABFB');
        $this->addSql('DROP INDEX IDX_7A997C5F6B25ABFB ON Registration');
        $this->addSql('ALTER TABLE Registration ADD workshopTimeId INT DEFAULT NULL, DROP workshopId');
        $this->addSql('ALTER TABLE Registration ADD CONSTRAINT FK_7A997C5F3C7136E0 FOREIGN KEY (workshopTimeId) REFERENCES WorkshopTime (id)');
        $this->addSql('CREATE INDEX IDX_7A997C5F3C7136E0 ON Registration (workshopTimeId)');
        $this->addSql('ALTER TABLE FormConfig CHANGE config config JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', CHANGE organisationId organisationId INT DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Category CHANGE created created DATETIME NOT NULL, CHANGE eventId eventId INT DEFAULT NULL, CHANGE categoryId categoryId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Event CHANGE endDate endDate DATETIME DEFAULT \'NULL\', CHANGE organisationId organisationId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE FormConfig CHANGE config config JSON DEFAULT \'NULL\' COLLATE utf8mb4_bin COMMENT \'(DC2Type:json_array)\', CHANGE organisationId organisationId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Registration DROP FOREIGN KEY FK_7A997C5F3C7136E0');
        $this->addSql('DROP INDEX IDX_7A997C5F3C7136E0 ON Registration');
        $this->addSql('ALTER TABLE Registration ADD workshopId INT DEFAULT NULL, DROP workshopTimeId');
        $this->addSql('ALTER TABLE Registration ADD CONSTRAINT FK_7A997C5F6B25ABFB FOREIGN KEY (workshopId) REFERENCES Workshop (id)');
        $this->addSql('CREATE INDEX IDX_7A997C5F6B25ABFB ON Registration (workshopId)');
        $this->addSql('ALTER TABLE Workshop CHANGE capacity capacity INT DEFAULT NULL, CHANGE created created DATETIME NOT NULL, CHANGE categoryId categoryId INT DEFAULT NULL, CHANGE formConfigId formConfigId INT DEFAULT NULL, CHANGE locationId locationId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE WorkshopTime CHANGE startTime startTime DATETIME NOT NULL, CHANGE workshopId workshopId INT DEFAULT NULL');
    }
}
