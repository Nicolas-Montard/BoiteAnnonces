<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250131153233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce ADD owner_id INT NOT NULL, DROP owner');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E57E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_F65593E57E3C61F9 ON annonce (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E57E3C61F9');
        $this->addSql('DROP INDEX IDX_F65593E57E3C61F9 ON annonce');
        $this->addSql('ALTER TABLE annonce ADD owner VARCHAR(255) NOT NULL, DROP owner_id');
    }
}
