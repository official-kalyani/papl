<?php

use yii\db\Migration;

/**
 * Class m211101_102140_add_comment_to_employee_table
 */
class m211101_102140_add_comment_to_employee_table extends Migration
{
     /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%employee}}', 'exit_reason', $this->text()->after('exit_date'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%employee}}', 'exit_reason');
    }
    
}
