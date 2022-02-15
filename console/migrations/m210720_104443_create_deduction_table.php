<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%deduction}}`.
 */
class m210720_104443_create_deduction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%deduction}}', [
            'id' => $this->primaryKey(),
            'attribute_name' => $this->string(),
            'type' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%deduction}}');
    }
}
