<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%state}}`.
 */
class m210629_094314_create_state_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%state}}', [
            'state_id' => $this->primaryKey(),
            'state_name' => $this->string(30),
            'is_delete' => $this->boolean(),
            'created_at' => $this->dateTime()->defaultValue(null),
            'updated_at' => $this->dateTime()->defaultValue(null),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%state}}');
    }
}
