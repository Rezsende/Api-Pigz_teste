<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230609140453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE sub_task_id_seq');
        $this->addSql('SELECT setval(\'sub_task_id_seq\', (SELECT MAX(id) FROM sub_task))');
        $this->addSql('ALTER TABLE sub_task ALTER id SET DEFAULT nextval(\'sub_task_id_seq\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sub_task ALTER id DROP DEFAULT');
    }
}
