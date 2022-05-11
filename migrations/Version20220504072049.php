<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220504072049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE snackassignment (id INT AUTO_INCREMENT NOT NULL, snack_id INT NOT NULL, vendor_id INT NOT NULL, presentdate DATE NOT NULL, createdtime DATETIME DEFAULT NULL, modifiedtime DATETIME DEFAULT NULL, INDEX IDX_ABA7E908F469C3E0 (snack_id), INDEX IDX_ABA7E908F603EE73 (vendor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE snackassignment ADD CONSTRAINT FK_ABA7E908F469C3E0 FOREIGN KEY (snack_id) REFERENCES snacks (id)');
        $this->addSql('ALTER TABLE snackassignment ADD CONSTRAINT FK_ABA7E908F603EE73 FOREIGN KEY (vendor_id) REFERENCES vendor (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE snackassignment');
    }
}
