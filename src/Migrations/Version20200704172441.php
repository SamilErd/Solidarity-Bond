<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200704172441 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13B79F37AE5');
        $this->addSql('DROP INDEX UNIQ_5F37A13B79F37AE5 ON token');
        $this->addSql('ALTER TABLE token ADD id_user_password_id INT DEFAULT NULL, CHANGE id_user_id id_user_register_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13B3E7573A FOREIGN KEY (id_user_register_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13B74DBE585 FOREIGN KEY (id_user_password_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F37A13B3E7573A ON token (id_user_register_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F37A13B74DBE585 ON token (id_user_password_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13B3E7573A');
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13B74DBE585');
        $this->addSql('DROP INDEX UNIQ_5F37A13B3E7573A ON token');
        $this->addSql('DROP INDEX UNIQ_5F37A13B74DBE585 ON token');
        $this->addSql('ALTER TABLE token ADD id_user_id INT DEFAULT NULL, DROP id_user_register_id, DROP id_user_password_id');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13B79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F37A13B79F37AE5 ON token (id_user_id)');
    }
}
