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
            'parent' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'next' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'prev' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'child_num' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'toc_mode' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'comment_mode' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'comment_num' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'content' => Schema::TYPE_TEXT,
            'ver' => Schema::TYPE_BIGINT . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'created_by' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'updated_by' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable(self::TBL_NAME);
    }
}
