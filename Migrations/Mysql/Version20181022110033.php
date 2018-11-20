<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs! This block will be used as the migration description if getDescription() is not used.
 */
class Version20181022110033 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_link ADD node VARCHAR(40) DEFAULT NULL, CHANGE newsletter newsletter VARCHAR(40) DEFAULT NULL');
        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_link ADD CONSTRAINT FK_A075A1B6857FE845 FOREIGN KEY (node) REFERENCES typo3_typo3cr_domain_model_nodedata (persistence_object_identifier) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_A075A1B6857FE845 ON psmb_newsletter_domain_model_link (node)');
        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_newsletter CHANGE node node VARCHAR(40) DEFAULT NULL, CHANGE publicationdate publicationdate DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');
        
        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_link DROP FOREIGN KEY FK_A075A1B6857FE845');
        $this->addSql('DROP INDEX IDX_A075A1B6857FE845 ON psmb_newsletter_domain_model_link');
        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_link DROP node, CHANGE newsletter newsletter VARCHAR(40) DEFAULT \'NULL\' COLLATE utf8_unicode_ci');
    }
}