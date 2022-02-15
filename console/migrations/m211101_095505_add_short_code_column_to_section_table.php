<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%section}}`.
 */
class m211101_095505_add_short_code_column_to_section_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%section}}', 'short_code', $this->string(100)->defaultValue(null)->after('section_name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
