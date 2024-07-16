<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716144645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coordinates (id INT AUTO_INCREMENT NOT NULL, longitude INT DEFAULT NULL, latitude INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE events ADD coordinates_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A158B0682 FOREIGN KEY (coordinates_id) REFERENCES coordinates (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5387574A158B0682 ON events (coordinates_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A158B0682');
        $this->addSql('DROP TABLE coordinates');
        $this->addSql('DROP INDEX UNIQ_5387574A158B0682 ON events');
        $this->addSql('ALTER TABLE events DROP coordinates_id');
    }
}
