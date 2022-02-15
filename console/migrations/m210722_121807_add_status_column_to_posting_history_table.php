<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%posting_history}}`.
 */
class m210722_121807_add_status_column_to_posting_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%posting_history}}', 'status', $this->boolean()->notNull()->after('start_date'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%posting_history}}', 'status');
    }
}
