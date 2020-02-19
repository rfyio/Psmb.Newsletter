<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add migration for newsletter information
 */
class Version20180104210416 extends AbstractMigration
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

        $this->addSql('CREATE TABLE psmb_newsletter_domain_model_newsletter (persistence_object_identifier VARCHAR(40) NOT NULL, node VARCHAR(40) DEFAULT NULL, publicationdate DATETIME DEFAULT NULL, viewscount INT NOT NULL, uniqueviewcount INT NOT NULL, viewsondevice LONGTEXT NOT NULL COMMENT \'(DC2Type:flow_json_array)\', viewsonos LONGTEXT NOT NULL COMMENT \'(DC2Type:flow_json_array)\', INDEX IDX_5F4B7E0A857FE845 (node), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_newsletter ADD CONSTRAINT FK_5F4B7E0A857FE845 FOREIGN KEY (node) REFERENCES typo3_typo3cr_domain_model_nodedata (persistence_object_identifier) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');
        
        $this->addSql('DROP TABLE psmb_newsletter_domain_model_newsletter');
    }
}