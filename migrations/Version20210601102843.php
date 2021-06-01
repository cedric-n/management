<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210601102843 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE income ADD budget_id INT NOT NULL');
        $this->addSql('ALTER TABLE income ADD CONSTRAINT FK_3FA862D036ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id)');
        $this->addSql('CREATE INDEX IDX_3FA862D036ABA6B8 ON income (budget_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE income DROP FOREIGN KEY FK_3FA862D036ABA6B8');
        $this->addSql('DROP INDEX IDX_3FA862D036ABA6B8 ON income');
        $this->addSql('ALTER TABLE income DROP budget_id');
    }
}
