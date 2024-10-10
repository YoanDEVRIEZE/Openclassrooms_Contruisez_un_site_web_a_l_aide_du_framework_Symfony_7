<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010155011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name=\'book\' AND column_name=\'createby_id\') THEN
                    ALTER TABLE book ADD createby_id INT NOT NULL;
                END IF;
            END $$;');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3317663CD76 FOREIGN KEY (createby_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CBE5A3317663CD76 ON book (createby_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A3317663CD76');
        $this->addSql('DROP INDEX IDX_CBE5A3317663CD76');
        $this->addSql('ALTER TABLE book DROP createby_id');
    }
}
