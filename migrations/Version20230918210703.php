<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230918210703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE team SET name="Axolotl" WHERE name="Axolot"');
        $this->addSql('UPDATE team SET name="Tatou" WHERE name="Jackalope"');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE team SET name="Axolot" WHERE name="Axolotl"');
        $this->addSql('UPDATE team SET name="Jackalope" WHERE name="Tatou"');
    }
}
