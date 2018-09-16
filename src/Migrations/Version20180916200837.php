<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180916200837 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE EmailTemplate (id INT AUTO_INCREMENT NOT NULL, sender VARCHAR(255) NOT NULL, receiverField VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, formConfig_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_14E0BF9239D04404 (formConfig_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE EmailTemplate ADD CONSTRAINT FK_14E0BF9239D04404 FOREIGN KEY (formConfig_id) REFERENCES FormConfig (id)');
        $this->addSql('ALTER TABLE Category CHANGE orderNo orderNo INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Event CHANGE capacity capacity INT DEFAULT NULL, CHANGE formConfig_id formConfig_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE groupFormConfig_id groupFormConfig_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Workshop CHANGE capacity capacity INT DEFAULT NULL, CHANGE formConfig_id formConfig_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE groupFormConfig_id groupFormConfig_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Registration CHANGE data data JSON NOT NULL, CHANGE workshopTime_id workshopTime_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE eventTime_id eventTime_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE EmailTemplate');
        $this->addSql('ALTER TABLE Category CHANGE orderNo orderNo INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Event CHANGE capacity capacity INT DEFAULT NULL, CHANGE formConfig_id formConfig_id CHAR(36) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:guid)\', CHANGE groupFormConfig_id groupFormConfig_id CHAR(36) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Registration CHANGE data data LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE workshopTime_id workshopTime_id CHAR(36) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:guid)\', CHANGE eventTime_id eventTime_id CHAR(36) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Workshop CHANGE capacity capacity INT DEFAULT NULL, CHANGE formConfig_id formConfig_id CHAR(36) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:guid)\', CHANGE groupFormConfig_id groupFormConfig_id CHAR(36) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:guid)\'');
    }
}
