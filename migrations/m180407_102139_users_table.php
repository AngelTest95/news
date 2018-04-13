<?php

use yii\db\Migration;

/**
 * Class m180407_102139_users_table
 */
class m180407_102139_users_table extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => 'INT(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'username' => 'VARCHAR(60) DEFAULT NULL',
            'email' => 'VARCHAR(60) NOT NULL',
            'password' => 'VARCHAR(60) DEFAULT NULL',
        ]);
        $this->createIndex('user_username', 'user', 'username', true);
        $this->createIndex('user_email', 'user', 'email', true);
    }

    public function down()
    {
        $this->dropTable('user');
    }
}
