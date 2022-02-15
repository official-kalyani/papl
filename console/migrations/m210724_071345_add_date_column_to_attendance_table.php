<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%attendance}}`.
 */
class m210724_071345_add_date_column_to_attendance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%attendance}}', 'date', $this->date()->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%attendance}}', 'date');
    }
}
