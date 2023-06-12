<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230612172616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP INDEX UNIQ_9474526CF675F31B, ADD INDEX IDX_9474526CF675F31B (author_id)');
        $this->addSql('ALTER TABLE comment DROP INDEX UNIQ_9474526C362B62A0, ADD INDEX IDX_9474526C362B62A0 (episode_id)');
        $this->addSql('ALTER TABLE comment CHANGE episode_id episode_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP INDEX IDX_9474526CF675F31B, ADD UNIQUE INDEX UNIQ_9474526CF675F31B (author_id)');
        $this->addSql('ALTER TABLE comment DROP INDEX IDX_9474526C362B62A0, ADD UNIQUE INDEX UNIQ_9474526C362B62A0 (episode_id)');
        $this->addSql('ALTER TABLE comment CHANGE episode_id episode_id INT DEFAULT NULL');
    }
}
