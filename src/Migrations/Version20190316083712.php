<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190316083712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE scrapping_results (id INT NOT NULL, request_id INT NOT NULL, keyword VARCHAR(255) NOT NULL, ad_words_count INT DEFAULT 0 NOT NULL, links_count INT DEFAULT 0 NOT NULL, result_stat VARCHAR(255) DEFAULT NULL, html TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9D820F3E427EB8A5 ON scrapping_results (request_id)');
        $this->addSql('CREATE TABLE scrapping_requets (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_completed BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE scrapping_results ADD CONSTRAINT FK_9D820F3E427EB8A5 FOREIGN KEY (request_id) REFERENCES scrapping_requets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE scrapping_results DROP CONSTRAINT FK_9D820F3E427EB8A5');
        $this->addSql('DROP TABLE scrapping_results');
        $this->addSql('DROP TABLE scrapping_requets');
    }
}
