<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190502173230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create image_post table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE image_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, original_filename VARCHAR(255) NOT NULL, ponka_added_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE image_post');
    }
}
