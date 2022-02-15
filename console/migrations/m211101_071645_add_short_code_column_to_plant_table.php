<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plant}}`.
 */
class m211101_071645_add_short_code_column_to_plant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plant}}', 'short_code', $this->string()->after('plant_name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
