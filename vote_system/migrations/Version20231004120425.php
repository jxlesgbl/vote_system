<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231004120425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sondage_votants (sondage_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(sondage_id, user_id))');
        $this->addSql('CREATE INDEX IDX_C4AD8EADBAF4AE56 ON sondage_votants (sondage_id)');
        $this->addSql('CREATE INDEX IDX_C4AD8EADA76ED395 ON sondage_votants (user_id)');
        $this->addSql('ALTER TABLE sondage_votants ADD CONSTRAINT FK_C4AD8EADBAF4AE56 FOREIGN KEY (sondage_id) REFERENCES sondage (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sondage_votants ADD CONSTRAINT FK_C4AD8EADA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sondage_votants DROP CONSTRAINT FK_C4AD8EADBAF4AE56');
        $this->addSql('ALTER TABLE sondage_votants DROP CONSTRAINT FK_C4AD8EADA76ED395');
        $this->addSql('DROP TABLE sondage_votants');
    }
}
