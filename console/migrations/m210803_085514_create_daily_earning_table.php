<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%daily_earning}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%papl}}`
 */
class m210803_085514_create_daily_earning_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%daily_earning}}', [
            'id' => $this->primaryKey(),
            'papl_id' => $this->string(),
            'type' => $this->string(),
            'amount' =>$this->money(),
            'att_type' => $this->string(),
            'ondate' => $this->date(),
            'created_date' => $this->dateTime(),
            'updated_date' => $this->dateTime(),
            'status' => $this->boolean()->defaultValue(0),
        ]);

        // creates index for column `papl_id`
        $this->createIndex(
            '{{%idx-daily_earning-papl_id}}',
            '{{%daily_earning}}',
            'papl_id'
        );

        // add foreign key for table `{{%papl}}`
        $this->addForeignKey(
            '{{%fk-daily_earning-papl_id}}',
            '{{%daily_earning}}',
            'papl_id',
            '{{%employee}}',
            'papl_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%papl}}`
        $this->dropForeignKey(
            '{{%fk-daily_earning-papl_id}}',
            '{{%daily_earning}}'
        );

        // drops index for column `papl_id`
        $this->dropIndex(
            '{{%idx-daily_earning-papl_id}}',
            '{{%daily_earning}}'
        );

        $this->dropTable('{{%daily_earning}}');
    }
}
