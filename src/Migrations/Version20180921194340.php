<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180921194340 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE OneTimeEmailTemplate DROP INDEX UNIQ_CA8D190371F7E88B, ADD INDEX IDX_CA8D190371F7E88B (event_id)');
        $this->addSql('ALTER TABLE OneTimeEmailTemplate DROP INDEX UNIQ_CA8D19031FDCE57C, ADD INDEX IDX_CA8D19031FDCE57C (workshop_id)');
        $this->addSql('ALTER TABLE OneTimeEmailTemplate CHANGE event_id event_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE workshop_id workshop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE receiverField receiverField VARCHAR(255) DEFAULT NULL, CHANGE title subject VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE RegistrationEmailTemplate CHANGE receiverField receiverField VARCHAR(255) DEFAULT NULL, CHANGE title subject VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE OneTimeEmailTemplate DROP INDEX IDX_CA8D190371F7E88B, ADD UNIQUE INDEX UNIQ_CA8D190371F7E88B (event_id)');
        $this->addSql('ALTER TABLE OneTimeEmailTemplate DROP INDEX IDX_CA8D19031FDCE57C, ADD UNIQUE INDEX UNIQ_CA8D19031FDCE57C (workshop_id)');
        $this->addSql('ALTER TABLE OneTimeEmailTemplate CHANGE event_id event_id CHAR(36) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:guid)\', CHANGE workshop_id workshop_id CHAR(36) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:guid)\', CHANGE receiverField receiverField VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE subject title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE RegistrationEmailTemplate CHANGE receiverField receiverField VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE subject title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
