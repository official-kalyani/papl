<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%deduction_mapping}}`.
 */
class m210721_070856_add_amount_column_to_deduction_mapping_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%deduction_mapping}}', 'amount', $this->string(20)->after('salary_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%deduction_mapping}}', 'amount');
    }
}
