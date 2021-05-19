<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210519140300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE absences (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, date_debut DATE NOT NULL, date_retour DATE NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_F9C0EFFF1B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documents (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, date_envoi DATE NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_A2B072881B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, service VARCHAR(50) NOT NULL, nb_conges INT NOT NULL, sexe VARCHAR(20) NOT NULL, photo LONGTEXT NOT NULL, role VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE absences ADD CONSTRAINT FK_F9C0EFFF1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B072881B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absences DROP FOREIGN KEY FK_F9C0EFFF1B65292');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B072881B65292');
        $this->addSql('DROP TABLE absences');
        $this->addSql('DROP TABLE documents');
        $this->addSql('DROP TABLE employe');
    }
}
