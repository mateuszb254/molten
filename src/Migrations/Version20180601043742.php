<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180601043742 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, question VARCHAR(255) NOT NULL, answer VARCHAR(255) NOT NULL, coins INT DEFAULT 0 NOT NULL, last_activity DATETIME NOT NULL, registered_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, more LONGTEXT DEFAULT NULL, image LONGTEXT DEFAULT NULL, INDEX IDX_23A0E66F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(35) NOT NULL, slug VARCHAR(35) NOT NULL, status INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(65) NOT NULL, description TEXT NOT NULL, price INT NOT NULL, INDEX IDX_D079448712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, category_id INT DEFAULT NULL, title VARCHAR(50) NOT NULL, content LONGTEXT NOT NULL, status INT NOT NULL, created_at DATETIME NOT NULL, attachment VARCHAR(255) DEFAULT NULL, INDEX IDX_97A0ADA3F675F31B (author_id), INDEX IDX_97A0ADA312469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, level INT NOT NULL, kingdom INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guild (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, wins INT DEFAULT 0 NOT NULL, loses INT DEFAULT 0 NOT NULL, kingdom INT NOT NULL, points INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_product (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_8B471AA74584665A (product_id), INDEX IDX_8B471AA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_answer (id INT AUTO_INCREMENT NOT NULL, ticket_id INT DEFAULT NULL, content LONGTEXT NOT NULL, is_admin_answer TINYINT(1) DEFAULT \'0\', INDEX IDX_A09F20EE700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_log (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, product_id INT DEFAULT NULL, type VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_6429094EA76ED395 (user_id), INDEX IDX_6429094E4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D079448712469DE2 FOREIGN KEY (category_id) REFERENCES shop_category (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA312469DE2 FOREIGN KEY (category_id) REFERENCES ticket_category (id)');
        $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA74584665A FOREIGN KEY (product_id) REFERENCES shop_product (id)');
        $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA7A76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE ticket_answer ADD CONSTRAINT FK_A09F20EE700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE user_log ADD CONSTRAINT FK_6429094EA76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_log ADD CONSTRAINT FK_6429094E4584665A FOREIGN KEY (product_id) REFERENCES shop_product (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3F675F31B');
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA7A76ED395');
        $this->addSql('ALTER TABLE user_log DROP FOREIGN KEY FK_6429094EA76ED395');
        $this->addSql('ALTER TABLE shop_product DROP FOREIGN KEY FK_D079448712469DE2');
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA74584665A');
        $this->addSql('ALTER TABLE user_log DROP FOREIGN KEY FK_6429094E4584665A');
        $this->addSql('ALTER TABLE ticket_answer DROP FOREIGN KEY FK_A09F20EE700047D2');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA312469DE2');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE shop_category');
        $this->addSql('DROP TABLE shop_product');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_category');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE guild');
        $this->addSql('DROP TABLE user_product');
        $this->addSql('DROP TABLE ticket_answer');
        $this->addSql('DROP TABLE user_log');
    }
}
