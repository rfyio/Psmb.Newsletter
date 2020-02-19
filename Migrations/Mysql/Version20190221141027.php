<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration: Add current subscriber count
 */
class Version20190221141027 extends AbstractMigration
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

        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_newsletter ADD currentsubscribercount INT NOT NULL, CHANGE node node VARCHAR(40) DEFAULT NULL, CHANGE publicationdate publicationdate DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_newsletter DROP currentsubscribercount, CHANGE node node VARCHAR(40) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE publicationdate publicationdate DATETIME DEFAULT \'NULL\'');
    }
}