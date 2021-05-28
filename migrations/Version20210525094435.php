<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210525094435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents ADD destinataire_id INT NOT NULL');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288A4F84F6E FOREIGN KEY (destinataire_id) REFERENCES employe (id)');
        $this->addSql('CREATE INDEX IDX_A2B07288A4F84F6E ON documents (destinataire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288A4F84F6E');
        $this->addSql('DROP INDEX IDX_A2B07288A4F84F6E ON documents');
        $this->addSql('ALTER TABLE documents DROP destinataire_id');
    }
}
