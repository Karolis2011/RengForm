<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180216132523 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Category CHANGE created created DATETIME NOT NULL, CHANGE eventId eventId INT DEFAULT NULL, CHANGE categoryId categoryId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Event CHANGE endDate endDate DATETIME DEFAULT NULL, CHANGE organisationId organisationId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE User ADD username VARCHAR(25) NOT NULL, ADD password VARCHAR(64) NOT NULL, ADD email VARCHAR(60) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA17977F85E0677 ON User (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA17977E7927C74 ON User (email)');
        $this->addSql('ALTER TABLE Workshop CHANGE startTime startTime DATETIME NOT NULL, CHANGE endTime endTime DATETIME NOT NULL, CHANGE created created DATETIME NOT NULL, CHANGE categoryId categoryId INT DEFAULT NULL, CHANGE formConfigId formConfigId INT DEFAULT NULL, CHANGE locationId locationId INT DEFAULT NULL');
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
        $this->addSql('DROP INDEX UNIQ_2DA17977F85E0677 ON User');
        $this->addSql('DROP INDEX UNIQ_2DA17977E7927C74 ON User');
        $this->addSql('ALTER TABLE User DROP username, DROP password, DROP email');
        $this->addSql('ALTER TABLE Workshop CHANGE startTime startTime DATETIME NOT NULL, CHANGE endTime endTime DATETIME NOT NULL, CHANGE created created DATETIME NOT NULL, CHANGE categoryId categoryId INT DEFAULT NULL, CHANGE formConfigId formConfigId INT DEFAULT NULL, CHANGE locationId locationId INT DEFAULT NULL');
    }
}
