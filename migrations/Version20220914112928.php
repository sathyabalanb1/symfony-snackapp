<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220914112928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, rolename VARCHAR(255) NOT NULL, createdtime DATETIME DEFAULT NULL, modifiedtime DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE selection (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, assignment_id INT NOT NULL, createdtime DATETIME DEFAULT NULL, modifiedtime DATETIME DEFAULT NULL, isselected TINYINT(1) NOT NULL, INDEX IDX_96A50CD7A76ED395 (user_id), INDEX IDX_96A50CD7D19302F8 (assignment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE snackassignment (id INT AUTO_INCREMENT NOT NULL, snack_id INT NOT NULL, vendor_id INT NOT NULL, presentdate DATE NOT NULL, createdtime DATETIME DEFAULT NULL, modifiedtime DATETIME DEFAULT NULL, INDEX IDX_ABA7E908F469C3E0 (snack_id), INDEX IDX_ABA7E908F603EE73 (vendor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE snacks (id INT AUTO_INCREMENT NOT NULL, snackname VARCHAR(255) NOT NULL, createdtime DATETIME DEFAULT NULL, modifiedtime DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, employeename VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D6491ACD1141 (employeename), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, employeename VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, createdtime DATETIME DEFAULT NULL, modifiedtime DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), INDEX IDX_1483A5E9D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vendor (id INT AUTO_INCREMENT NOT NULL, vendorname VARCHAR(255) NOT NULL, vendorlocation VARCHAR(1000) NOT NULL, cnumber VARCHAR(255) NOT NULL, createdtime DATETIME DEFAULT NULL, modifiedtime DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE selection ADD CONSTRAINT FK_96A50CD7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE selection ADD CONSTRAINT FK_96A50CD7D19302F8 FOREIGN KEY (assignment_id) REFERENCES snackassignment (id)');
        $this->addSql('ALTER TABLE snackassignment ADD CONSTRAINT FK_ABA7E908F469C3E0 FOREIGN KEY (snack_id) REFERENCES snacks (id)');
        $this->addSql('ALTER TABLE snackassignment ADD CONSTRAINT FK_ABA7E908F603EE73 FOREIGN KEY (vendor_id) REFERENCES vendor (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D60322AC FOREIGN KEY (role_id) REFERENCES roles (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9D60322AC');
        $this->addSql('ALTER TABLE selection DROP FOREIGN KEY FK_96A50CD7D19302F8');
        $this->addSql('ALTER TABLE snackassignment DROP FOREIGN KEY FK_ABA7E908F469C3E0');
        $this->addSql('ALTER TABLE selection DROP FOREIGN KEY FK_96A50CD7A76ED395');
        $this->addSql('ALTER TABLE snackassignment DROP FOREIGN KEY FK_ABA7E908F603EE73');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE selection');
        $this->addSql('DROP TABLE snackassignment');
        $this->addSql('DROP TABLE snacks');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE vendor');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
