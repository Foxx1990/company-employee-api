<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241110220810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company ADD created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', ADD updated_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', CHANGE nip nip VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE employee ADD created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', ADD updated_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE company DROP created_at, DROP updated_at, CHANGE nip nip VARCHAR(255) NOT NULL');
    }
}
