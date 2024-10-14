<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241014080505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car_pooling_offer (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, event_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, departure_location VARCHAR(255) NOT NULL, arrival_location VARCHAR(255) NOT NULL, departure_time VARCHAR(255) NOT NULL, seats_available INT DEFAULT NULL, INDEX IDX_20A5C19661220EA6 (creator_id), INDEX IDX_20A5C19671F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car_pooling_offer ADD CONSTRAINT FK_20A5C19661220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE car_pooling_offer ADD CONSTRAINT FK_20A5C19671F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A35E47E35');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A35E47E35 FOREIGN KEY (referent_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD phone_number INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car_pooling_offer DROP FOREIGN KEY FK_20A5C19661220EA6');
        $this->addSql('ALTER TABLE car_pooling_offer DROP FOREIGN KEY FK_20A5C19671F7E88B');
        $this->addSql('DROP TABLE car_pooling_offer');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A35E47E35');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A35E47E35 FOREIGN KEY (referent_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP phone_number');
    }
}
