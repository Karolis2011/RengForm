<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180209203817 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category CHANGE created created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE event ADD end_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE workshop CHANGE start_time start_time DATETIME NOT NULL, CHANGE end_time end_time DATETIME NOT NULL, CHANGE created created DATETIME NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category CHANGE created created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE event DROP end_date');
        $this->addSql('ALTER TABLE workshop CHANGE start_time start_time DATETIME NOT NULL, CHANGE end_time end_time DATETIME NOT NULL, CHANGE created created DATETIME NOT NULL');
    }
}
