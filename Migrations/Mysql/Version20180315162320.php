<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration
 */
class Version20180315162320 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Migration to add subscriptionIdentifier, this will be the basis for global newsletter analytics.';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');
        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_newsletter ADD subscriptionidentifier VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');
        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_newsletter DROP subscriptionidentifier');
    }
}