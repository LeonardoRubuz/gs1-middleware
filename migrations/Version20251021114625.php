<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251021114625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE global_document_type_identifier ADD project_id INT NOT NULL');
        $this->addSql('ALTER TABLE global_document_type_identifier ADD CONSTRAINT FK_58B33926166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_58B33926166D1F9C ON global_document_type_identifier (project_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE global_document_type_identifier DROP FOREIGN KEY FK_58B33926166D1F9C');
        $this->addSql('DROP INDEX IDX_58B33926166D1F9C ON global_document_type_identifier');
        $this->addSql('ALTER TABLE global_document_type_identifier DROP project_id');
    }
}
