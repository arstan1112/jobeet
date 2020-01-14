<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200111124534 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE blog_impressions (id INT AUTO_INCREMENT NOT NULL, blog_topic_id INT NOT NULL, user_id INT NOT NULL, type SMALLINT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7E7C8B5E6B08041A (blog_topic_id), UNIQUE INDEX UNIQ_7E7C8B5EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_impressions ADD CONSTRAINT FK_7E7C8B5E6B08041A FOREIGN KEY (blog_topic_id) REFERENCES blog_topic (id)');
        $this->addSql('ALTER TABLE blog_impressions ADD CONSTRAINT FK_7E7C8B5EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE blog_topic ADD likes INT DEFAULT NULL, ADD dislikes INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE blog_impressions');
        $this->addSql('ALTER TABLE blog_topic DROP likes, DROP dislikes');
    }
}
