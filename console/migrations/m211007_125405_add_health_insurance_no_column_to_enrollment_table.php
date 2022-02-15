<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%enrollment}}`.
 */
class m211007_125405_add_health_insurance_no_column_to_enrollment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%enrollment}}', 'health_insurance_no', $this->string()->after('browse_experience')->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%enrollment}}', 'health_insurance_no');
    }
}
