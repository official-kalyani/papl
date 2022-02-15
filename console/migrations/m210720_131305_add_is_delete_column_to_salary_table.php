<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%salary}}`.
 */
class m210720_131305_add_is_delete_column_to_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%salary}}', 'is_delete', $this->boolean()->notNULL());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%salary}}', 'is_delete');
    }
}
