<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acl}}`.
 */
class m210712_101649_create_acl_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acl}}', [
            'id' => $this->primaryKey(),
            'lvl1' => $this->string(40),
            'lvl2' => $this->string(40),
            'lvl3' => $this->string(40),
            'lvl4' => $this->string(40),
            'url' => $this->string(200),
            'is_delete' => $this->boolean()->defaultValue('0')->notNull(),
            'created_at' => $this->dateTime()->defaultValue(null),
            'updated_at' => $this->dateTime()->defaultValue(null),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%acl}}');
    }
}
