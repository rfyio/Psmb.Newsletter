<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration: Add subscriptionIdentifier field
 */
class Version20180316084557 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Add subscriptionIdentifier field to subscriber tracking.';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_subscribertracking ADD subscriptionidentifier VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_subscribertracking DROP subscriptionidentifier');
    }
}