<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration: Add Link entity and relationship
 */
class Version20181022104340 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Add Link entity and relationship';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');
        
        $this->addSql('CREATE TABLE psmb_newsletter_domain_model_link (persistence_object_identifier VARCHAR(40) NOT NULL, newsletter VARCHAR(40) DEFAULT NULL, viewscount INT NOT NULL, uniqueviewcount INT NOT NULL, viewsondevice LONGTEXT NOT NULL COMMENT \'(DC2Type:flow_json_array)\', viewsonos LONGTEXT NOT NULL COMMENT \'(DC2Type:flow_json_array)\', INDEX IDX_A075A1B67E8585C8 (newsletter), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_link ADD CONSTRAINT FK_A075A1B67E8585C8 FOREIGN KEY (newsletter) REFERENCES psmb_newsletter_domain_model_newsletter (persistence_object_identifier)');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');
        
        $this->addSql('DROP TABLE psmb_newsletter_domain_model_link');
    }
}