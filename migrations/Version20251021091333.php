<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251021091333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE global_document_type_identifier (id INT AUTO_INCREMENT NOT NULL, document_name VARCHAR(500) NOT NULL, external_reference VARCHAR(17) DEFAULT NULL, type VARCHAR(50) DEFAULT NULL, code VARCHAR(100) NOT NULL, enabled TINYINT(1) NOT NULL, deleted TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, application_identifier VARCHAR(10) NOT NULL, value VARCHAR(500) DEFAULT NULL, reference VARCHAR(16) DEFAULT NULL, UNIQUE INDEX UNIQ_58B3392677153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE global_location_number (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, location_name VARCHAR(255) NOT NULL, gps VARCHAR(100) DEFAULT NULL, location_address VARCHAR(255) DEFAULT NULL, code VARCHAR(100) NOT NULL, enabled TINYINT(1) NOT NULL, deleted TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, application_identifier VARCHAR(10) NOT NULL, value VARCHAR(500) DEFAULT NULL, reference VARCHAR(16) DEFAULT NULL, UNIQUE INDEX UNIQ_35463DB077153098 (code), INDEX IDX_35463DB0166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE global_service_relation_number (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, middlename VARCHAR(255) DEFAULT NULL, gender VARCHAR(10) DEFAULT NULL, title VARCHAR(100) DEFAULT NULL, birthdate DATE DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, code VARCHAR(100) NOT NULL, enabled TINYINT(1) NOT NULL, deleted TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, application_identifier VARCHAR(10) NOT NULL, value VARCHAR(500) DEFAULT NULL, reference VARCHAR(16) DEFAULT NULL, UNIQUE INDEX UNIQ_9FF8100677153098 (code), INDEX IDX_9FF81006166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, customer VARCHAR(255) NOT NULL, external_id VARCHAR(50) DEFAULT NULL, company_prefix VARCHAR(50) DEFAULT NULL, code VARCHAR(100) NOT NULL, enabled TINYINT(1) NOT NULL, deleted TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_2FB3D0EE9F75D7B0 (external_id), UNIQUE INDEX UNIQ_2FB3D0EE77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE global_location_number ADD CONSTRAINT FK_35463DB0166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE global_service_relation_number ADD CONSTRAINT FK_9FF81006166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE global_location_number DROP FOREIGN KEY FK_35463DB0166D1F9C');
        $this->addSql('ALTER TABLE global_service_relation_number DROP FOREIGN KEY FK_9FF81006166D1F9C');
        $this->addSql('DROP TABLE global_document_type_identifier');
        $this->addSql('DROP TABLE global_location_number');
        $this->addSql('DROP TABLE global_service_relation_number');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
