<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240807092140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_wishlist_products (user_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_FBEA7585A76ED395 (user_id), INDEX IDX_FBEA75854584665A (product_id), PRIMARY KEY(user_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_liked_products (user_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_4E4C35CEA76ED395 (user_id), INDEX IDX_4E4C35CE4584665A (product_id), PRIMARY KEY(user_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_wishlist_products ADD CONSTRAINT FK_FBEA7585A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_wishlist_products ADD CONSTRAINT FK_FBEA75854584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_liked_products ADD CONSTRAINT FK_4E4C35CEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_liked_products ADD CONSTRAINT FK_4E4C35CE4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA74584665A');
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA7A76ED395');
        $this->addSql('DROP TABLE user_product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_product (user_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_8B471AA7A76ED395 (user_id), INDEX IDX_8B471AA74584665A (product_id), PRIMARY KEY(user_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA74584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_wishlist_products DROP FOREIGN KEY FK_FBEA7585A76ED395');
        $this->addSql('ALTER TABLE user_wishlist_products DROP FOREIGN KEY FK_FBEA75854584665A');
        $this->addSql('ALTER TABLE user_liked_products DROP FOREIGN KEY FK_4E4C35CEA76ED395');
        $this->addSql('ALTER TABLE user_liked_products DROP FOREIGN KEY FK_4E4C35CE4584665A');
        $this->addSql('DROP TABLE user_wishlist_products');
        $this->addSql('DROP TABLE user_liked_products');
    }
}
