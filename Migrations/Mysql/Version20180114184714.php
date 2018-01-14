<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs! This block will be used as the migration description if getDescription() is not used.
 */
class Version20180114184714 extends AbstractMigration
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
        
        $this->addSql('CREATE TABLE psmb_newsletter_domain_model_subscribertracking (persistence_object_identifier VARCHAR(40) NOT NULL, subscriber VARCHAR(40) DEFAULT NULL, newsletter VARCHAR(40) DEFAULT NULL, viewcount INT NOT NULL, INDEX IDX_470468FFAD005B69 (subscriber), INDEX IDX_470468FF7E8585C8 (newsletter), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_subscribertracking ADD CONSTRAINT FK_470468FFAD005B69 FOREIGN KEY (subscriber) REFERENCES psmb_newsletter_domain_model_subscriber (persistence_object_identifier)');
        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_subscribertracking ADD CONSTRAINT FK_470468FF7E8585C8 FOREIGN KEY (newsletter) REFERENCES psmb_newsletter_domain_model_newsletter (persistence_object_identifier)');
        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_newsletter CHANGE node node VARCHAR(40) DEFAULT NULL, CHANGE publicationdate publicationdate DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');
        
        $this->addSql('DROP TABLE psmb_newsletter_domain_model_subscribertracking');
    }
}