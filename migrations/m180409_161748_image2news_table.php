<?php

use yii\db\Migration;

/**
 * Class m180409_161748_image2news_table
 */
class m180409_161748_image2news_table extends Migration
{
    public function up()
    {
        $this->createTable('image2news_table', [
            'id' => 'INT(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'news_id' => 'INT(10) unsigned NOT NULL',
            'image' => 'VARCHAR(200)'
        ]);
        $this->createIndex('image2news_table_news_id', 'image2news_table', 'news_id');
        $this->addForeignKey('image2news2news', 'image2news_table', 'news_id', 'news', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('image2news_table');
    }
}
