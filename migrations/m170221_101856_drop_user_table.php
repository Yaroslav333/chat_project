<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `user`.
 */
class m170221_101856_drop_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropTable('user');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
        ]);
    }
}
