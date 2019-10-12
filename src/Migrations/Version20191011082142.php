<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191011082142 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer_order_line ADD customer_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_order_line ADD CONSTRAINT FK_612C8A63A15A2E17 FOREIGN KEY (customer_order_id) REFERENCES customer_order (id)');
        $this->addSql('CREATE INDEX IDX_612C8A63A15A2E17 ON customer_order_line (customer_order_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer_order_line DROP FOREIGN KEY FK_612C8A63A15A2E17');
        $this->addSql('DROP INDEX IDX_612C8A63A15A2E17 ON customer_order_line');
        $this->addSql('ALTER TABLE customer_order_line DROP customer_order_id');
    }
}
