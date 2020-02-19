<?php

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs! This block will be used as the migration description if getDescription() is not used.
 */
class Version20180828084710 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Create persistent Subscription entity';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('CREATE TABLE psmb_newsletter_domain_model_63f99_subscribedsubscriptions_join (newsletter_subscriber VARCHAR(40) NOT NULL, newsletter_subscription VARCHAR(40) NOT NULL, INDEX IDX_15E394DE401562C3 (newsletter_subscriber), INDEX IDX_15E394DEA82B55AD (newsletter_subscription), PRIMARY KEY(newsletter_subscriber, newsletter_subscription)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psmb_newsletter_domain_model_subscription (persistence_object_identifier VARCHAR(40) NOT NULL, fusionidentifier VARCHAR(80) NOT NULL, name VARCHAR(80) NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_63f99_subscribedsubscriptions_join ADD CONSTRAINT FK_15E394DE401562C3 FOREIGN KEY (newsletter_subscriber) REFERENCES psmb_newsletter_domain_model_subscriber (persistence_object_identifier)');
        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_63f99_subscribedsubscriptions_join ADD CONSTRAINT FK_15E394DEA82B55AD FOREIGN KEY (newsletter_subscription) REFERENCES psmb_newsletter_domain_model_subscription (persistence_object_identifier)');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('ALTER TABLE psmb_newsletter_domain_model_63f99_subscribedsubscriptions_join DROP FOREIGN KEY FK_15E394DEA82B55AD');
        $this->addSql('DROP TABLE psmb_newsletter_domain_model_63f99_subscribedsubscriptions_join');
        $this->addSql('DROP TABLE psmb_newsletter_domain_model_subscription');
    }
}