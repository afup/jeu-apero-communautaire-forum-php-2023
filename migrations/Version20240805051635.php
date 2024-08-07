<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240805051635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout du nom des users';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP name');
    }
}
