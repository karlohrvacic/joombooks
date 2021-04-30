<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210430142624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE izdavaci (id INT AUTO_INCREMENT NOT NULL, drzava_id INT DEFAULT NULL, naziv VARCHAR(255) NOT NULL, adresa VARCHAR(255) DEFAULT NULL, INDEX IDX_A85647E2EE4B985A (drzava_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jezici (id INT AUTO_INCREMENT NOT NULL, ime VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE izdavaci ADD CONSTRAINT FK_A85647E2EE4B985A FOREIGN KEY (drzava_id) REFERENCES drzave (id)');
        $this->addSql('ALTER TABLE gradja ADD izdavac_id INT DEFAULT NULL, ADD jezici_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gradja ADD CONSTRAINT FK_4EC20C971B2F1379 FOREIGN KEY (izdavac_id) REFERENCES izdavaci (id)');
        $this->addSql('ALTER TABLE gradja ADD CONSTRAINT FK_4EC20C97D110C98A FOREIGN KEY (jezici_id) REFERENCES jezici (id)');
        $this->addSql('CREATE INDEX IDX_4EC20C971B2F1379 ON gradja (izdavac_id)');
        $this->addSql('CREATE INDEX IDX_4EC20C97D110C98A ON gradja (jezici_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gradja DROP FOREIGN KEY FK_4EC20C971B2F1379');
        $this->addSql('ALTER TABLE gradja DROP FOREIGN KEY FK_4EC20C97D110C98A');
        $this->addSql('DROP TABLE izdavaci');
        $this->addSql('DROP TABLE jezici');
        $this->addSql('DROP INDEX IDX_4EC20C971B2F1379 ON gradja');
        $this->addSql('DROP INDEX IDX_4EC20C97D110C98A ON gradja');
        $this->addSql('ALTER TABLE gradja DROP izdavac_id, DROP jezici_id');
    }
}
