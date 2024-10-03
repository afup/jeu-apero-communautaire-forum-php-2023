<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240807055004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de la colonne score à la table flash';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE flash ADD score INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE flash DROP score');
    }
}
