<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%purchaseorder}}`.
 */
class m211101_081259_add_short_code_column_to_purchaseorder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%purchaseOrder}}', 'short_code', $this->string(100)->defaultValue(null)->after('po_number'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
