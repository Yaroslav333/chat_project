<?php

use yii\db\Migration;

/**
 * Handles adding position to table `user`.
 */
class m170222_093705_add_position_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'role', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'position');
    }
}
