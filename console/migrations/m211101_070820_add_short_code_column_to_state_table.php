<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%state}}`.
 */
class m211101_070820_add_short_code_column_to_state_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%state}}', 'short_code', $this->string()->after('state_name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
