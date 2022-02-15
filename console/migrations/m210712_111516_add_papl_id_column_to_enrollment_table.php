<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%enrollment}}`.
 */
class m210712_111516_add_papl_id_column_to_enrollment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%enrollment}}', 'papl_id', $this->string(11)->after('plant_id'));
        $this->createIndex(
            '{{%idx-enrollment-papl_id}}',
            '{{%enrollment}}',
            'papl_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%enrollment}}', 'papl_id');
    }
}
