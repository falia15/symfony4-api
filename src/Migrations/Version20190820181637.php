<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190820181637 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE opening (id INT AUTO_INCREMENT NOT NULL, anime_id INT NOT NULL, type SMALLINT NOT NULL, number INT NOT NULL, title VARCHAR(255) NOT NULL, artist VARCHAR(255) DEFAULT NULL, moe_link VARCHAR(255) NOT NULL, INDEX IDX_E35D4C3794BBE89 (anime_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE anime (id INT AUTO_INCREMENT NOT NULL, name_jap VARCHAR(255) NOT NULL, name_us VARCHAR(255) DEFAULT NULL, year INT NOT NULL, season INT NOT NULL, image VARCHAR(255) DEFAULT NULL, level INT NOT NULL, type INT NOT NULL, myanimelist_id INT NOT NULL, anilist_id INT DEFAULT NULL, kitsu_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE opening ADD CONSTRAINT FK_E35D4C3794BBE89 FOREIGN KEY (anime_id) REFERENCES anime (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opening DROP FOREIGN KEY FK_E35D4C3794BBE89');
        $this->addSql('DROP TABLE opening');
        $this->addSql('DROP TABLE anime');
    }
}
