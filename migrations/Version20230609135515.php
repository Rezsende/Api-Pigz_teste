<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230609135515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE task_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sub_task_id_seq CASCADE');
        $this->addSql('ALTER TABLE sub_task DROP CONSTRAINT fk_75e844e48db60186');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE sub_task');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE task_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sub_task_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE task (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, task_finished INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN task.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN task.update_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE sub_task (id INT NOT NULL, task_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_75e844e48db60186 ON sub_task (task_id)');
        $this->addSql('ALTER TABLE sub_task ADD CONSTRAINT fk_75e844e48db60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
