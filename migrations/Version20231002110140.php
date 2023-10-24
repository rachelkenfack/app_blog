<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002110140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaires_piece_jointe (commentaires_id INT NOT NULL, piece_jointe_id INT NOT NULL, INDEX IDX_D209844117C4B2B0 (commentaires_id), INDEX IDX_D2098441A3741A05 (piece_jointe_id), PRIMARY KEY(commentaires_id, piece_jointe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaires_piece_jointe ADD CONSTRAINT FK_D209844117C4B2B0 FOREIGN KEY (commentaires_id) REFERENCES commentaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires_piece_jointe ADD CONSTRAINT FK_D2098441A3741A05 FOREIGN KEY (piece_jointe_id) REFERENCES piece_jointe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires_piecejointe DROP FOREIGN KEY FK_6D85467B17C4B2B0');
        $this->addSql('ALTER TABLE commentaires_piecejointe DROP FOREIGN KEY FK_6D85467B8947BD0C');
        $this->addSql('DROP TABLE commentaires_piecejointe');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaires_piecejointe (commentaires_id INT NOT NULL, piecejointe_id INT NOT NULL, INDEX IDX_6D85467B17C4B2B0 (commentaires_id), INDEX IDX_6D85467B8947BD0C (piecejointe_id), PRIMARY KEY(commentaires_id, piecejointe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commentaires_piecejointe ADD CONSTRAINT FK_6D85467B17C4B2B0 FOREIGN KEY (commentaires_id) REFERENCES commentaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires_piecejointe ADD CONSTRAINT FK_6D85467B8947BD0C FOREIGN KEY (piecejointe_id) REFERENCES piece_jointe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires_piece_jointe DROP FOREIGN KEY FK_D209844117C4B2B0');
        $this->addSql('ALTER TABLE commentaires_piece_jointe DROP FOREIGN KEY FK_D2098441A3741A05');
        $this->addSql('DROP TABLE commentaires_piece_jointe');
    }
}

