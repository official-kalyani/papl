<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%deduction}}`.
 */
class m210721_053055_add_is_delete_column_to_deduction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%deduction}}', 'is_delete', $this->boolean()->notNULL());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%deduction}}', 'is_delete');
    }
}
