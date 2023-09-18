<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230831050654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flash (id INT AUTO_INCREMENT NOT NULL, flasher_id INT NOT NULL, flashed_id INT NOT NULL, is_success TINYINT(1) NOT NULL, flashed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AFCE5F03A9A6055D (flasher_id), INDEX IDX_AFCE5F03DCD40D1E (flashed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flash ADD CONSTRAINT FK_AFCE5F03A9A6055D FOREIGN KEY (flasher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE flash ADD CONSTRAINT FK_AFCE5F03DCD40D1E FOREIGN KEY (flashed_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flash DROP FOREIGN KEY FK_AFCE5F03A9A6055D');
        $this->addSql('ALTER TABLE flash DROP FOREIGN KEY FK_AFCE5F03DCD40D1E');
        $this->addSql('DROP TABLE flash');
    }
}
