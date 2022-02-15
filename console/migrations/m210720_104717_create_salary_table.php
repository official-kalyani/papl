<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%salary}}`.
 */
class m210720_104717_create_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%salary}}', [
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
        $this->dropTable('{{%salary}}');
    }
}
