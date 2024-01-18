<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240117201434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inbox (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, identifier VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, mail_number_sent INT NOT NULL, INDEX IDX_7E11F339166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mail (id INT AUTO_INCREMENT NOT NULL, inbox_id INT DEFAULT NULL, `from` VARCHAR(255) NOT NULL, `to` VARCHAR(255) NOT NULL, recipients LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', cc LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', reply_to VARCHAR(255) DEFAULT NULL, mime_version VARCHAR(255) DEFAULT NULL, content_type VARCHAR(255) DEFAULT NULL, sent_at DATETIME DEFAULT NULL, message_id VARCHAR(255) DEFAULT NULL, content_transfer_encoding VARCHAR(255) DEFAULT NULL, subject VARCHAR(255) DEFAULT NULL, text LONGTEXT NOT NULL, html LONGTEXT NOT NULL, raw LONGTEXT NOT NULL, is_seen TINYINT(1) NOT NULL, INDEX IDX_5126AC4818DA89DD (inbox_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, project_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_70E4FA78A76ED395 (user_id), INDEX IDX_70E4FA78166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inbox ADD CONSTRAINT FK_7E11F339166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE mail ADD CONSTRAINT FK_5126AC4818DA89DD FOREIGN KEY (inbox_id) REFERENCES inbox (id)');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inbox DROP FOREIGN KEY FK_7E11F339166D1F9C');
        $this->addSql('ALTER TABLE mail DROP FOREIGN KEY FK_5126AC4818DA89DD');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78A76ED395');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78166D1F9C');
        $this->addSql('DROP TABLE inbox');
        $this->addSql('DROP TABLE mail');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE user');
    }
}
