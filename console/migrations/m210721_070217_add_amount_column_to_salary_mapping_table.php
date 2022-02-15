<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%salary_mapping}}`.
 */
class m210721_070217_add_amount_column_to_salary_mapping_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%salary_mapping}}', 'amount', $this->string(20)->after('salary_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%salary_mapping}}', 'amount');
    }
}
