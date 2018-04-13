<?php

use yii\db\Migration;

/**
 * Class m180407_103103_news_table
 */
class m180407_103103_news_table extends Migration
{
    public function up()
    {
        $this->createTable('news', [
            'id' => 'INT(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'title' => 'VARCHAR(255) NOT NULL',
            'content' => 'TEXT',
            'user_id' => 'INT(10) unsigned NOT NULL',
            'created_at' => 'DATETIME NOT NULL',
        ]);
        $this->createIndex('news_user_id', 'news', 'user_id');
        $this->addForeignKey('news2user', 'news', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('news');
    }
}
