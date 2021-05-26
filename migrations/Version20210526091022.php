<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210526091022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE documents_employe');
        $this->addSql('ALTER TABLE documents ADD destinataire_id INT NOT NULL, DROP statut');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288A4F84F6E FOREIGN KEY (destinataire_id) REFERENCES employe (id)');
        $this->addSql('CREATE INDEX IDX_A2B07288A4F84F6E ON documents (destinataire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE documents_employe (documents_id INT NOT NULL, employe_id INT NOT NULL, INDEX IDX_6748B22A1B65292 (employe_id), INDEX IDX_6748B22A5F0F2752 (documents_id), PRIMARY KEY(documents_id, employe_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE documents_employe ADD CONSTRAINT FK_6748B22A1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documents_employe ADD CONSTRAINT FK_6748B22A5F0F2752 FOREIGN KEY (documents_id) REFERENCES documents (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288A4F84F6E');
        $this->addSql('DROP INDEX IDX_A2B07288A4F84F6E ON documents');
        $this->addSql('ALTER TABLE documents ADD statut VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP destinataire_id');
    }
}
