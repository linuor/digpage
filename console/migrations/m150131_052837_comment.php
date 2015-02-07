<?php

use yii\db\Schema;
use yii\db\Migration;

class m150131_052837_comment extends Migration
{
    const TBL_NAME = '{{%comment}}';
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT = 100';
        }

        $this->createTable(self::TBL_NAME, [
            'id' => Schema::TYPE_PK,
            'section_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'parent' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'thumbsup' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'thumbsdown' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'content' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'created_by' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'updated_by' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
        ], $tableOptions);

        $this->addForeignKey('fk_comment_section', self::TBL_NAME,
                '[[section_id]]', '{{%section}}', '[[id]]', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_comment_parent', self::TBL_NAME, '[[parent]]',
                self::TBL_NAME, '[[id]]', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_comment_createdby', self::TBL_NAME, 
                '[[created_by]]', '{{%user}}', '[[id]]', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_comment_updatedby', self::TBL_NAME, 
                '[[updated_by]]', '{{%user}}', '[[id]]', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable(self::TBL_NAME);
    }
}
