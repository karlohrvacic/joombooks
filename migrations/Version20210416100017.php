<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210416100017 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE autori (id INT AUTO_INCREMENT NOT NULL, drzava_id INT DEFAULT NULL, ime VARCHAR(255) NOT NULL, prezime VARCHAR(255) NOT NULL, INDEX IDX_CDE96BBDEE4B985A (drzava_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clanstva (id INT AUTO_INCREMENT NOT NULL, korisnik_id INT NOT NULL, knjiznica_id INT NOT NULL, broj_iskaznice_korisnika VARCHAR(255) NOT NULL, INDEX IDX_FB580B332714722E (korisnik_id), INDEX IDX_FB580B331B5875BA (knjiznica_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drzave (id INT AUTO_INCREMENT NOT NULL, naziv VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gradja (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, knjiznica_vlasnik_id INT NOT NULL, isbn VARCHAR(255) DEFAULT NULL, naslov VARCHAR(512) NOT NULL, oib_knjiznice VARCHAR(255) NOT NULL, fotografija VARCHAR(2048) NOT NULL, opis LONGTEXT DEFAULT NULL, datum_dodavanja DATE NOT NULL, godina_izdanja DATE DEFAULT NULL, jezik VARCHAR(255) DEFAULT NULL, broj_inventara VARCHAR(255) NOT NULL, INDEX IDX_4EC20C976BF700BD (status_id), INDEX IDX_4EC20C9714EEC35B (knjiznica_vlasnik_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gradja_autori (gradja_id INT NOT NULL, autori_id INT NOT NULL, INDEX IDX_8169904247C2879B (gradja_id), INDEX IDX_81699042F418400E (autori_id), PRIMARY KEY(gradja_id, autori_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gradja_zanrovi (gradja_id INT NOT NULL, zanrovi_id INT NOT NULL, INDEX IDX_D226C93847C2879B (gradja_id), INDEX IDX_D226C938A7719700 (zanrovi_id), PRIMARY KEY(gradja_id, zanrovi_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE knjiznice (id INT AUTO_INCREMENT NOT NULL, oib_knjiznice VARCHAR(255) NOT NULL, naziv VARCHAR(255) NOT NULL, adresa VARCHAR(255) NOT NULL, cijena_zakasnine NUMERIC(5, 2) NOT NULL, email VARCHAR(255) NOT NULL, lozinka VARCHAR(255) NOT NULL, max_posudjenih INT NOT NULL, max_rezerviranih INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE korisnici (id INT AUTO_INCREMENT NOT NULL, ime VARCHAR(255) NOT NULL, prezime VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, lozinka VARCHAR(255) NOT NULL, broj_telefona VARCHAR(255) DEFAULT NULL, fotografija VARCHAR(2048) DEFAULT NULL, razred INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posudbe (id INT AUTO_INCREMENT NOT NULL, gradja_id INT NOT NULL, status_id INT NOT NULL, id_gradje INT NOT NULL, broj_iskaznice_korisnika VARCHAR(255) NOT NULL, datum_posudbe DATE NOT NULL, datum_roka_vracanja DATE NOT NULL, datum_vracanja DATE DEFAULT NULL, INDEX IDX_4FF8876547C2879B (gradja_id), INDEX IDX_4FF887656BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statusi (id INT AUTO_INCREMENT NOT NULL, naziv VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zanrovi (id INT AUTO_INCREMENT NOT NULL, naziv VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE autori ADD CONSTRAINT FK_CDE96BBDEE4B985A FOREIGN KEY (drzava_id) REFERENCES drzave (id)');
        $this->addSql('ALTER TABLE clanstva ADD CONSTRAINT FK_FB580B332714722E FOREIGN KEY (korisnik_id) REFERENCES korisnici (id)');
        $this->addSql('ALTER TABLE clanstva ADD CONSTRAINT FK_FB580B331B5875BA FOREIGN KEY (knjiznica_id) REFERENCES knjiznice (id)');
        $this->addSql('ALTER TABLE gradja ADD CONSTRAINT FK_4EC20C976BF700BD FOREIGN KEY (status_id) REFERENCES statusi (id)');
        $this->addSql('ALTER TABLE gradja ADD CONSTRAINT FK_4EC20C9714EEC35B FOREIGN KEY (knjiznica_vlasnik_id) REFERENCES knjiznice (id)');
        $this->addSql('ALTER TABLE gradja_autori ADD CONSTRAINT FK_8169904247C2879B FOREIGN KEY (gradja_id) REFERENCES gradja (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gradja_autori ADD CONSTRAINT FK_81699042F418400E FOREIGN KEY (autori_id) REFERENCES autori (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gradja_zanrovi ADD CONSTRAINT FK_D226C93847C2879B FOREIGN KEY (gradja_id) REFERENCES gradja (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gradja_zanrovi ADD CONSTRAINT FK_D226C938A7719700 FOREIGN KEY (zanrovi_id) REFERENCES zanrovi (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posudbe ADD CONSTRAINT FK_4FF8876547C2879B FOREIGN KEY (gradja_id) REFERENCES gradja (id)');
        $this->addSql('ALTER TABLE posudbe ADD CONSTRAINT FK_4FF887656BF700BD FOREIGN KEY (status_id) REFERENCES statusi (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gradja_autori DROP FOREIGN KEY FK_81699042F418400E');
        $this->addSql('ALTER TABLE autori DROP FOREIGN KEY FK_CDE96BBDEE4B985A');
        $this->addSql('ALTER TABLE gradja_autori DROP FOREIGN KEY FK_8169904247C2879B');
        $this->addSql('ALTER TABLE gradja_zanrovi DROP FOREIGN KEY FK_D226C93847C2879B');
        $this->addSql('ALTER TABLE posudbe DROP FOREIGN KEY FK_4FF8876547C2879B');
        $this->addSql('ALTER TABLE clanstva DROP FOREIGN KEY FK_FB580B331B5875BA');
        $this->addSql('ALTER TABLE gradja DROP FOREIGN KEY FK_4EC20C9714EEC35B');
        $this->addSql('ALTER TABLE clanstva DROP FOREIGN KEY FK_FB580B332714722E');
        $this->addSql('ALTER TABLE gradja DROP FOREIGN KEY FK_4EC20C976BF700BD');
        $this->addSql('ALTER TABLE posudbe DROP FOREIGN KEY FK_4FF887656BF700BD');
        $this->addSql('ALTER TABLE gradja_zanrovi DROP FOREIGN KEY FK_D226C938A7719700');
        $this->addSql('DROP TABLE autori');
        $this->addSql('DROP TABLE clanstva');
        $this->addSql('DROP TABLE drzave');
        $this->addSql('DROP TABLE gradja');
        $this->addSql('DROP TABLE gradja_autori');
        $this->addSql('DROP TABLE gradja_zanrovi');
        $this->addSql('DROP TABLE knjiznice');
        $this->addSql('DROP TABLE korisnici');
        $this->addSql('DROP TABLE posudbe');
        $this->addSql('DROP TABLE statusi');
        $this->addSql('DROP TABLE zanrovi');
    }
}
