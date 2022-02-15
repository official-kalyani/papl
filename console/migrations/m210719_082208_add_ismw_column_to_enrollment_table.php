<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%enrollment}}`.
 */
class m210719_082208_add_ismw_column_to_enrollment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%enrollment}}', 'ismw', $this->string(11)->after('gender'));
        $this->createIndex(
            '{{%idx-enrollment-ismw}}',
            '{{%enrollment}}',
            'ismw'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
