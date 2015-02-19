<?php
/**
 * Create section table
 *
 * @author linuor <linuor@gmail.com>
 */
use yii\db\Schema;
use yii\db\Migration;

class m150126_120408_section extends Migration
{
    const TBL_NAME = '{{%section}}';
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT = 100';
        }

        $this->createTable(self::TBL_NAME, [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . ' NOT NULL DEFAULT ""',
            'parent' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'ancestor' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'next' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'prev' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'toc_mode' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'comment_mode' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'comment_num' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'content' => Schema::TYPE_TEXT,
            'ver' => Schema::TYPE_BIGINT . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'created_by' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'updated_by' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
        ], $tableOptions);

        $this->addForeignKey('fk_section_parent', self::TBL_NAME, '[[parent]]',
                self::TBL_NAME, '[[id]]', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_section_ancestor', self::TBL_NAME, '[[ancestor]]',
                self::TBL_NAME, '[[id]]', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_section_next', self::TBL_NAME, '[[next]]',
                self::TBL_NAME, '[[id]]', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_section_prev', self::TBL_NAME, '[[prev]]',
                self::TBL_NAME, '[[id]]', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_section_createdby', self::TBL_NAME,
                '[[created_by]]', '{{%user}}', '[[id]]', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_section_updatedby', self::TBL_NAME,
                '[[updated_by]]', '{{%user}}', '[[id]]', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable(self::TBL_NAME);
    }
}
