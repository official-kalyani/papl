<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%enrollment}}`
 */
class m210716_072908_create_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee}}', [
            'id' => $this->primaryKey(),
            'papl_id' => $this->string(15),
            'gate_pass' => $this->string(20),
            'gate_pass_validity' => $this->string(11),
            'workman_sl_no' => $this->string(30),
            'is_exit' => $this->boolean(),
            'exit_date'=> $this->string(11),
            'created_at'=> $this->dateTime()->defaultValue(null),
            'updated_at' => $this->dateTime()->defaultValue(null),
        ]);

        // creates index for column `papl_id`
        $this->createIndex(
            '{{%idx-employee-papl_id}}',
            '{{%employee}}',
            'papl_id'
        );

        // add foreign key for table `{{%enrollment}}`
        $this->addForeignKey(
            '{{%fk-employee-papl_id}}',
            '{{%employee}}',
            'papl_id',
            '{{%enrollment}}',
            'papl_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%enrollment}}`
        $this->dropForeignKey(
            '{{%fk-employee-papl_id}}',
            '{{%employee}}'
        );

        // drops index for column `papl_id`
        $this->dropIndex(
            '{{%idx-employee-papl_id}}',
            '{{%employee}}'
        );

        $this->dropTable('{{%employee}}');
    }
}
