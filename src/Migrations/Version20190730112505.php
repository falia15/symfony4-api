<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190730112505 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE anime CHANGE season season INT NOT NULL, CHANGE type type INT NOT NULL');
        $this->addSql('ALTER TABLE opening CHANGE type type SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE opening ADD CONSTRAINT FK_E35D4C3794BBE89 FOREIGN KEY (anime_id) REFERENCES anime (id)');
        $this->addSql('CREATE INDEX IDX_E35D4C3794BBE89 ON opening (anime_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE anime CHANGE season season INT DEFAULT NULL, CHANGE type type INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opening DROP FOREIGN KEY FK_E35D4C3794BBE89');
        $this->addSql('DROP INDEX IDX_E35D4C3794BBE89 ON opening');
        $this->addSql('ALTER TABLE opening CHANGE type type TINYINT(1) NOT NULL');
    }
}
