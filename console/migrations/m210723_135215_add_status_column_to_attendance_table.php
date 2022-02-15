<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%attendance}}`.
 */
class m210723_135215_add_status_column_to_attendance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%attendance}}', 'status', $this->boolean()->defaultValue(0)->after('ot_type'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%attendance}}', 'status');
    }
}
