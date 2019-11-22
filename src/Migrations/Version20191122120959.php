<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191122120959 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE blog_topic (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_6DAF9DD3F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_comment (id INT AUTO_INCREMENT NOT NULL, topic_id INT NOT NULL, user_id INT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7882EFEF1F55203D (topic_id), UNIQUE INDEX UNIQ_7882EFEFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_topic ADD CONSTRAINT FK_6DAF9DD3F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE blog_comment ADD CONSTRAINT FK_7882EFEF1F55203D FOREIGN KEY (topic_id) REFERENCES blog_topic (id)');
        $this->addSql('ALTER TABLE blog_comment ADD CONSTRAINT FK_7882EFEFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog_comment DROP FOREIGN KEY FK_7882EFEF1F55203D');
        $this->addSql('DROP TABLE blog_topic');
        $this->addSql('DROP TABLE blog_comment');
    }
}
