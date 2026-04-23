<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260405110802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course ADD department_id INT NOT NULL, CHANGE credits credits INT DEFAULT 3 NOT NULL');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_169E6FB977153098 ON course (code)');
        $this->addSql('CREATE INDEX IDX_169E6FB9AE80F5DF ON course (department_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CD1DE18A77153098 ON department (code)');
        $this->addSql('ALTER TABLE enrollment ADD student_id INT NOT NULL, ADD course_id INT NOT NULL, CHANGE status status VARCHAR(20) DEFAULT \'active\' NOT NULL');
        $this->addSql('ALTER TABLE enrollment ADD CONSTRAINT FK_DBDCD7E1CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE enrollment ADD CONSTRAINT FK_DBDCD7E1591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_DBDCD7E1CB944F1A ON enrollment (student_id)');
        $this->addSql('CREATE INDEX IDX_DBDCD7E1591CC992 ON enrollment (course_id)');
        $this->addSql('ALTER TABLE student ADD department_id INT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B723AF33E7927C74 ON student (email)');
        $this->addSql('CREATE INDEX IDX_B723AF33AE80F5DF ON student (department_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9AE80F5DF');
        $this->addSql('DROP INDEX UNIQ_169E6FB977153098 ON course');
        $this->addSql('DROP INDEX IDX_169E6FB9AE80F5DF ON course');
        $this->addSql('ALTER TABLE course DROP department_id, CHANGE credits credits INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_CD1DE18A77153098 ON department');
        $this->addSql('ALTER TABLE enrollment DROP FOREIGN KEY FK_DBDCD7E1CB944F1A');
        $this->addSql('ALTER TABLE enrollment DROP FOREIGN KEY FK_DBDCD7E1591CC992');
        $this->addSql('DROP INDEX IDX_DBDCD7E1CB944F1A ON enrollment');
        $this->addSql('DROP INDEX IDX_DBDCD7E1591CC992 ON enrollment');
        $this->addSql('ALTER TABLE enrollment DROP student_id, DROP course_id, CHANGE status status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33AE80F5DF');
        $this->addSql('DROP INDEX UNIQ_B723AF33E7927C74 ON student');
        $this->addSql('DROP INDEX IDX_B723AF33AE80F5DF ON student');
        $this->addSql('ALTER TABLE student DROP department_id, CHANGE created_at created_at DATETIME DEFAULT NULL');
    }
}
