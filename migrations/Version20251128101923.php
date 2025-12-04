<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251128101923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE global_trade_item_number (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, item_name VARCHAR(255) DEFAULT NULL, code VARCHAR(100) NOT NULL, enabled TINYINT(1) NOT NULL, deleted TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, application_identifier VARCHAR(10) NOT NULL, value VARCHAR(500) DEFAULT NULL, reference VARCHAR(16) DEFAULT NULL, UNIQUE INDEX UNIQ_F0DB913D77153098 (code), INDEX IDX_F0DB913D166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE global_trade_item_number ADD CONSTRAINT FK_F0DB913D166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE global_trade_item_number DROP FOREIGN KEY FK_F0DB913D166D1F9C');
        $this->addSql('DROP TABLE global_trade_item_number');
    }
}
