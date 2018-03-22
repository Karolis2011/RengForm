<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180322211622 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Category CHANGE created created DATETIME NOT NULL, CHANGE orderNo orderNo INT DEFAULT NULL, CHANGE eventId eventId CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE categoryId categoryId CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Event CHANGE endDate endDate DATETIME DEFAULT NULL, CHANGE organisationId organisationId CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Workshop CHANGE capacity capacity INT DEFAULT NULL, CHANGE created created DATETIME NOT NULL, CHANGE categoryId categoryId CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE formConfigId formConfigId CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE locationId locationId CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE WorkshopTime CHANGE startTime startTime DATETIME NOT NULL, CHANGE workshopId workshopId CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Registration CHANGE workshopTimeId workshopTimeId CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE FormConfig CHANGE config config JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', CHANGE organisationId organisationId CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Category CHANGE created created DATETIME NOT NULL, CHANGE orderNo orderNo INT NOT NULL, CHANGE eventId eventId CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\', CHANGE categoryId categoryId CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Event CHANGE endDate endDate DATETIME DEFAULT \'NULL\', CHANGE organisationId organisationId CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE FormConfig CHANGE config config JSON DEFAULT \'NULL\' COLLATE utf8mb4_bin COMMENT \'(DC2Type:json_array)\', CHANGE organisationId organisationId CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Registration CHANGE workshopTimeId workshopTimeId CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Workshop CHANGE capacity capacity INT DEFAULT NULL, CHANGE created created DATETIME NOT NULL, CHANGE categoryId categoryId CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\', CHANGE formConfigId formConfigId CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\', CHANGE locationId locationId CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE WorkshopTime CHANGE startTime startTime DATETIME NOT NULL, CHANGE workshopId workshopId CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\'');
    }
}
