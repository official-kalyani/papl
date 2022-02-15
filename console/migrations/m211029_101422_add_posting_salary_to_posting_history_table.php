<?php

use yii\db\Migration;

/**
 * Class m211029_101422_add_posting_salary_to_posting_history_table
 */
class m211029_101422_add_posting_salary_to_posting_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%posting_history}}', 'posting_salary', $this->money()->after('end_date'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%posting_history}}', 'posting_salary');
    }
    
}
