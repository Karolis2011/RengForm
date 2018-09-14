<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180913191419 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Category CHANGE orderNo orderNo INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Event ADD groupFormConfig_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE capacity capacity INT DEFAULT NULL, CHANGE formConfig_id formConfig_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Event ADD CONSTRAINT FK_FA6F25A33CB32761 FOREIGN KEY (groupFormConfig_id) REFERENCES FormConfig (id)');
        $this->addSql('CREATE INDEX IDX_FA6F25A33CB32761 ON Event (groupFormConfig_id)');
        $this->addSql('ALTER TABLE Workshop ADD groupFormConfig_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE capacity capacity INT DEFAULT NULL, CHANGE formConfig_id formConfig_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Workshop ADD CONSTRAINT FK_621960923CB32761 FOREIGN KEY (groupFormConfig_id) REFERENCES FormConfig (id)');
        $this->addSql('CREATE INDEX IDX_621960923CB32761 ON Workshop (groupFormConfig_id)');
        $this->addSql('ALTER TABLE Registration CHANGE workshopTime_id workshopTime_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE eventTime_id eventTime_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Category CHANGE orderNo orderNo INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Event DROP FOREIGN KEY FK_FA6F25A33CB32761');
        $this->addSql('DROP INDEX IDX_FA6F25A33CB32761 ON Event');
        $this->addSql('ALTER TABLE Event DROP groupFormConfig_id, CHANGE capacity capacity INT DEFAULT NULL, CHANGE formConfig_id formConfig_id CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Registration CHANGE workshopTime_id workshopTime_id CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\', CHANGE eventTime_id eventTime_id CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE Workshop DROP FOREIGN KEY FK_621960923CB32761');
        $this->addSql('DROP INDEX IDX_621960923CB32761 ON Workshop');
        $this->addSql('ALTER TABLE Workshop DROP groupFormConfig_id, CHANGE capacity capacity INT DEFAULT NULL, CHANGE formConfig_id formConfig_id CHAR(36) DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\'');
    }
}
