<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230609140059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sub_task ADD initial_date_task TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE sub_task ADD final_date_task TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN sub_task.initial_date_task IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN sub_task.final_date_task IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sub_task DROP initial_date_task');
        $this->addSql('ALTER TABLE sub_task DROP final_date_task');
    }
}
