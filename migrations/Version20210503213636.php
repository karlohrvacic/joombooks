<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503213636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posudbe ADD knjiznica_id INT NOT NULL');
        $this->addSql('ALTER TABLE posudbe ADD CONSTRAINT FK_4FF887651B5875BA FOREIGN KEY (knjiznica_id) REFERENCES knjiznice (id)');
        $this->addSql('CREATE INDEX IDX_4FF887651B5875BA ON posudbe (knjiznica_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posudbe DROP FOREIGN KEY FK_4FF887651B5875BA');
        $this->addSql('DROP INDEX IDX_4FF887651B5875BA ON posudbe');
        $this->addSql('ALTER TABLE posudbe DROP knjiznica_id');
    }
}
