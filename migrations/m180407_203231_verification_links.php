<?php

use yii\db\Migration;

/**
 * Class m180407_203231_verification_links
 */
class m180407_203231_verification_links extends Migration
{
    public function up()
    {
        $this->createTable('verification_link', [
            'user_id' => 'INT(10) unsigned not null',
            'verification_token' => 'varchar(50)'
        ]);
        $this->createIndex('verification_link_user_id', 'verification_link', 'user_id', true);
        $this->addForeignKey('verification_link2user', 'verification_link', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('verification_link');
    }
}
