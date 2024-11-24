<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241124140429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE created created DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD created DATETIME DEFAULT NULL, ADD updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tag ADD created DATETIME DEFAULT NULL, ADD updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD created DATETIME DEFAULT NULL, ADD updated DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP created, DROP updated');
        $this->addSql('ALTER TABLE category CHANGE created created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tag DROP created, DROP updated');
        $this->addSql('ALTER TABLE post DROP created, DROP updated');
    }
}
