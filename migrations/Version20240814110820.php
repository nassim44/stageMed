<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240814110820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory_product DROP FOREIGN KEY FK_924EA2519EEA759');
        $this->addSql('ALTER TABLE inventory_product DROP FOREIGN KEY FK_924EA2514584665A');
        $this->addSql('DROP TABLE inventory_product');
        $this->addSql('ALTER TABLE inventory DROP image, CHANGE serial_number quantity INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inventory_product (inventory_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_924EA2519EEA759 (inventory_id), INDEX IDX_924EA2514584665A (product_id), PRIMARY KEY(inventory_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE inventory_product ADD CONSTRAINT FK_924EA2519EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inventory_product ADD CONSTRAINT FK_924EA2514584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inventory ADD image VARCHAR(255) NOT NULL, CHANGE quantity serial_number INT NOT NULL');
    }
}
