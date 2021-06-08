<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210608162026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attestation (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT DEFAULT NULL, date_created DATETIME DEFAULT NULL, INDEX IDX_326EC63FDDEAB1A3 (etudiant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, classe_id INT DEFAULT NULL, matiere_id INT DEFAULT NULL, date_cours DATETIME DEFAULT NULL, heure_d DATETIME DEFAULT NULL, heure_f DATETIME DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, INDEX IDX_FDCA8C9C8F5EA509 (classe_id), INDEX IDX_FDCA8C9CF46CD258 (matiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, classe_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, date_naiss DATETIME DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, code_admission VARCHAR(255) NOT NULL, INDEX IDX_717E22E38F5EA509 (classe_id), INDEX IDX_717E22E3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frais (id INT AUTO_INCREMENT NOT NULL, classe_id INT DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_25404C988F5EA509 (classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, classe_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, date_naiss DATETIME DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, nom_pere VARCHAR(255) DEFAULT NULL, prenom_pere VARCHAR(255) DEFAULT NULL, email_pere VARCHAR(255) DEFAULT NULL, fonction_pere VARCHAR(255) DEFAULT NULL, tel_pere VARCHAR(255) DEFAULT NULL, nom_mere VARCHAR(255) DEFAULT NULL, prenom_mere VARCHAR(255) DEFAULT NULL, email_mere VARCHAR(255) DEFAULT NULL, fonction_mere VARCHAR(255) DEFAULT NULL, tel_mere VARCHAR(255) DEFAULT NULL, INDEX IDX_5E90F6D68F5EA509 (classe_id), INDEX IDX_5E90F6D6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professeur (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, date_naiss DATETIME DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, tel VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT FK_326EC63FDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E38F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE frais ADD CONSTRAINT FK_25404C988F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D68F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE epreuve ADD classe_id INT DEFAULT NULL, ADD matiere_id INT DEFAULT NULL, ADD heure_debut DATETIME DEFAULT NULL, ADD heure_fin DATETIME DEFAULT NULL, DROP heur_debut, DROP heur_fin');
        $this->addSql('ALTER TABLE epreuve ADD CONSTRAINT FK_D6ADE47F8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE epreuve ADD CONSTRAINT FK_D6ADE47FF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('CREATE INDEX IDX_D6ADE47F8F5EA509 ON epreuve (classe_id)');
        $this->addSql('CREATE INDEX IDX_D6ADE47FF46CD258 ON epreuve (matiere_id)');
        $this->addSql('ALTER TABLE note ADD etudiant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14DDEAB1A3 ON note (etudiant_id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BAB22EE9 FOREIGN KEY (professeur_id) REFERENCES professeur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C8F5EA509');
        $this->addSql('ALTER TABLE epreuve DROP FOREIGN KEY FK_D6ADE47F8F5EA509');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E38F5EA509');
        $this->addSql('ALTER TABLE frais DROP FOREIGN KEY FK_25404C988F5EA509');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D68F5EA509');
        $this->addSql('ALTER TABLE attestation DROP FOREIGN KEY FK_326EC63FDDEAB1A3');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14DDEAB1A3');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CF46CD258');
        $this->addSql('ALTER TABLE epreuve DROP FOREIGN KEY FK_D6ADE47FF46CD258');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BAB22EE9');
        $this->addSql('DROP TABLE attestation');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE frais');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE professeur');
        $this->addSql('DROP INDEX IDX_D6ADE47F8F5EA509 ON epreuve');
        $this->addSql('DROP INDEX IDX_D6ADE47FF46CD258 ON epreuve');
        $this->addSql('ALTER TABLE epreuve ADD heur_debut DATETIME DEFAULT NULL, ADD heur_fin DATETIME DEFAULT NULL, DROP classe_id, DROP matiere_id, DROP heure_debut, DROP heure_fin');
        $this->addSql('DROP INDEX IDX_CFBDFA14DDEAB1A3 ON note');
        $this->addSql('ALTER TABLE note DROP etudiant_id');
    }
}
