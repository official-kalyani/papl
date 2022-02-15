<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bank_details}}`.
 */
class m210707_103859_add_bank_account_number_column_to_bank_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bank_details}}', 'bank_account_number', $this->string()->after('name_bank'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bank_details}}', 'bank_account_number');
    }
}
