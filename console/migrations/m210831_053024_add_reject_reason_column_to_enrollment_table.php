<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%enrollment}}`.
 */
class m210831_053024_add_reject_reason_column_to_enrollment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%enrollment}}', 'reject_reason', $this->string()->after('browse_experience'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
