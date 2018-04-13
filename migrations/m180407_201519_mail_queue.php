<?php

use yii\db\Migration;

/**
 * Class m180407_201519_mail_queue
 */
class m180407_201519_mail_queue extends Migration
{
    public function up()
    {
        $this->createTable('mail_queue', [
            'id' => 'int(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'recipient' => 'TEXT NOT NULL',
            'from' => 'VARCHAR(255) NOT NULL',
            'subject' => 'VARCHAR(500) NOT NULL',
            'body' => 'LONGTEXT NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('mail_queue');
    }
}
