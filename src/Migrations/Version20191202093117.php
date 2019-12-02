<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191202093117 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE blog_topic_blog_topic_hash_tag (blog_topic_id INT NOT NULL, blog_topic_hash_tag_id INT NOT NULL, INDEX IDX_592658A6B08041A (blog_topic_id), INDEX IDX_592658A397B6833 (blog_topic_hash_tag_id), PRIMARY KEY(blog_topic_id, blog_topic_hash_tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_topic_blog_topic_hash_tag ADD CONSTRAINT FK_592658A6B08041A FOREIGN KEY (blog_topic_id) REFERENCES blog_topic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_topic_blog_topic_hash_tag ADD CONSTRAINT FK_592658A397B6833 FOREIGN KEY (blog_topic_hash_tag_id) REFERENCES blog_topic_hash_tag (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE blog_topic_hash_tag_blog_topic');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE blog_topic_hash_tag_blog_topic (blog_topic_hash_tag_id INT NOT NULL, blog_topic_id INT NOT NULL, INDEX IDX_B9B5A437397B6833 (blog_topic_hash_tag_id), INDEX IDX_B9B5A4376B08041A (blog_topic_id), PRIMARY KEY(blog_topic_hash_tag_id, blog_topic_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE blog_topic_hash_tag_blog_topic ADD CONSTRAINT FK_B9B5A437397B6833 FOREIGN KEY (blog_topic_hash_tag_id) REFERENCES blog_topic_hash_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_topic_hash_tag_blog_topic ADD CONSTRAINT FK_B9B5A4376B08041A FOREIGN KEY (blog_topic_id) REFERENCES blog_topic (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE blog_topic_blog_topic_hash_tag');
    }
}
