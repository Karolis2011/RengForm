<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180219202947 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Category CHANGE created created DATETIME NOT NULL, CHANGE eventId eventId INT DEFAULT NULL, CHANGE categoryId categoryId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Event CHANGE endDate endDate DATETIME DEFAULT NULL, CHANGE organisationId organisationId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Workshop CHANGE startTime startTime DATETIME NOT NULL, CHANGE endTime endTime DATETIME NOT NULL, CHANGE capacity capacity INT DEFAULT NULL, CHANGE created created DATETIME NOT NULL, CHANGE categoryId categoryId INT DEFAULT NULL, CHANGE formConfigId formConfigId INT DEFAULT NULL, CHANGE locationId locationId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Registration CHANGE workshopId workshopId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE FormConfig CHANGE organisationId organisationId INT DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Category CHANGE created created DATETIME NOT NULL, CHANGE eventId eventId INT DEFAULT NULL, CHANGE categoryId categoryId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Event CHANGE endDate endDate DATETIME DEFAULT \'NULL\', CHANGE organisationId organisationId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE FormConfig CHANGE organisationId organisationId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Registration CHANGE workshopId workshopId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Workshop CHANGE startTime startTime DATETIME NOT NULL, CHANGE endTime endTime DATETIME NOT NULL, CHANGE capacity capacity INT NOT NULL, CHANGE created created DATETIME NOT NULL, CHANGE categoryId categoryId INT DEFAULT NULL, CHANGE formConfigId formConfigId INT DEFAULT NULL, CHANGE locationId locationId INT DEFAULT NULL');
    }
}
