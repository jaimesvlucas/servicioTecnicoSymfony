<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210219112109 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lineas_incidencia (id INT AUTO_INCREMENT NOT NULL, incidencia_id INT NOT NULL, texto VARCHAR(255) NOT NULL, fecha_creacion DATETIME NOT NULL, INDEX IDX_23A092AEE1605BE2 (incidencia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lineas_incidencia ADD CONSTRAINT FK_23A092AEE1605BE2 FOREIGN KEY (incidencia_id) REFERENCES incidencia (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lineas_incidencia');
    }
}
