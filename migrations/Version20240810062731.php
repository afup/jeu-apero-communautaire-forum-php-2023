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
        return 'On ajoute le type de flash pour dissocier Golden et Error';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flash ADD type VARCHAR(20) NOT NULL DEFAULT \'' . FlashType::STANDARD->value . '\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flash DROP type');
    }
}
