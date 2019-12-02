<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191202075706 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE blog_topic_hash_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_topic_hash_tag_blog_topic (blog_topic_hash_tag_id INT NOT NULL, blog_topic_id INT NOT NULL, INDEX IDX_B9B5A437397B6833 (blog_topic_hash_tag_id), INDEX IDX_B9B5A4376B08041A (blog_topic_id), PRIMARY KEY(blog_topic_hash_tag_id, blog_topic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_topic_hash_tag_blog_topic ADD CONSTRAINT FK_B9B5A437397B6833 FOREIGN KEY (blog_topic_hash_tag_id) REFERENCES blog_topic_hash_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_topic_hash_tag_blog_topic ADD CONSTRAINT FK_B9B5A4376B08041A FOREIGN KEY (blog_topic_id) REFERENCES blog_topic (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog_topic_hash_tag_blog_topic DROP FOREIGN KEY FK_B9B5A437397B6833');
        $this->addSql('DROP TABLE blog_topic_hash_tag');
        $this->addSql('DROP TABLE blog_topic_hash_tag_blog_topic');
    }
}
