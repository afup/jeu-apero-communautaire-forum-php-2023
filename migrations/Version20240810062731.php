<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\FlashType;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240810062731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'On ajoute les golden ticket et null ticket';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flash ADD type VARCHAR(20) NOT NULL DEFAULT \'' . FlashType::STANDARD->value . '\'');
        $this->addSql('ALTER TABLE user ADD golden_username VARCHAR(10) DEFAULT NULL, ADD null_username VARCHAR(10) DEFAULT NULL, CHANGE username username VARCHAR(10) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flash DROP type');
        $this->addSql('ALTER TABLE user DROP golden_username, DROP null_username, CHANGE username username VARCHAR(180) NOT NULL');
    }
}
