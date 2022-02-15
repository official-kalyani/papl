<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%location}}`.
 */
class m211101_071340_add_short_code_column_to_location_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('{{%location}}', 'short_code', $this->string()->after('location_name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
