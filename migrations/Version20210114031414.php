<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210114031414 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property ADD property_owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE20BB55BD FOREIGN KEY (property_owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8BF21CDE20BB55BD ON property (property_owner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE20BB55BD');
        $this->addSql('DROP INDEX IDX_8BF21CDE20BB55BD ON property');
        $this->addSql('ALTER TABLE property DROP property_owner_id');
    }
}
