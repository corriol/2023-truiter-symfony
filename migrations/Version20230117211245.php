<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117211245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_liked_tweets (user_id INT NOT NULL, tweet_id INT NOT NULL, INDEX IDX_8B6584A2A76ED395 (user_id), INDEX IDX_8B6584A21041E39B (tweet_id), PRIMARY KEY(user_id, tweet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_liked_tweets ADD CONSTRAINT FK_8B6584A2A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_liked_tweets ADD CONSTRAINT FK_8B6584A21041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_liked_tweets DROP FOREIGN KEY FK_8B6584A2A76ED395');
        $this->addSql('ALTER TABLE users_liked_tweets DROP FOREIGN KEY FK_8B6584A21041E39B');
        $this->addSql('DROP TABLE users_liked_tweets');
    }
}
