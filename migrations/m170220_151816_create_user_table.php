<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m170220_151816_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'name' => 'string NOT NULL', 'email' => 'string NOT NULL', 'login' => 'string NOT NULL',
            'password' => 'string NOT NULL', 'role' => 'string NOT NULL', 'status' => 'int NOT NULL'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
