<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%location}}`.
 */
class m210629_095125_create_location_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%location}}', [
            'location_id' => $this->primaryKey(),
            'location_name' => $this->string(50),
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
        $this->dropTable('{{%location}}');
    }
}
