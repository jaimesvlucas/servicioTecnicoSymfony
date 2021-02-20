<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210219111904 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE incidencia (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, cliente_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, fecha_creacion DATETIME NOT NULL, estado VARCHAR(255) NOT NULL, INDEX IDX_C7C6728CDB38439E (usuario_id), INDEX IDX_C7C6728CDE734E51 (cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE incidencia ADD CONSTRAINT FK_C7C6728CDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE incidencia ADD CONSTRAINT FK_C7C6728CDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE incidencia');
    }
}
