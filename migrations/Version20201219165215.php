<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201219165215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $extensions = ['pdf'];
        $this->addSql("INSERT INTO cfg_document_type VALUES ('2d1206d8-dd2f-48e8-91ea-a359427c7816', 'standard_pdf', 20512, '[\"pdf\"]')");
        $this->addSql("INSERT INTO storage VALUES ('2ff7fa9e-e0f6-47d3-b408-00fd0d5d6ff4', 'Azure')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
