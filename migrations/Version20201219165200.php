<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201219165200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cfg_document_type (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, size_limit_in_kb INT NOT NULL, allowed_extensions JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE document (id VARCHAR(36) NOT NULL, storage_id VARCHAR(36) DEFAULT NULL, type_id VARCHAR(36) DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, target_dir VARCHAR(255) DEFAULT NULL, extension VARCHAR(255) NOT NULL , mime_type varchar(255) DEFAULT NULL,  size INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8698A765CC5DB90 ON document (storage_id)');
        $this->addSql('CREATE INDEX IDX_D8698A76C54C8C93 ON document (type_id)');
        $this->addSql('CREATE TABLE storage (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A765CC5DB90 FOREIGN KEY (storage_id) REFERENCES storage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76C54C8C93 FOREIGN KEY (type_id) REFERENCES cfg_document_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE correspondance_document_storage');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A76C54C8C93');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A765CC5DB90');
        $this->addSql('CREATE TABLE correspondance_document_storage (id UUID NOT NULL, path VARCHAR(255) NOT NULL, storage VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN correspondance_document_storage.id IS \'(DC2Type:uuid)\'');
        $this->addSql('DROP TABLE cfg_document_type');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE storage');
    }
}
