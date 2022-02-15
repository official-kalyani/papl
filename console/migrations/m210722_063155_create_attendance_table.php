<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%attendance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%papl}}`
 */
class m210722_063155_create_attendance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%attendance}}', [
            'id' => $this->primaryKey(),
            'papl_id' => $this->string(),
            'employee_name' => $this->string(),
            'att' => $this->string(20),
            'att_type' => $this->string(),
            'nh' => $this->string(),
            'fh' => $this->string(),
            'nhfh_type' => $this->string(),
            'ot' => $this->string(),
            'ot_type' => $this->string(),
            'remark' => $this->string(),
        ]);

        // creates index for column `papl_id`
        $this->createIndex(
            '{{%idx-attendance-papl_id}}',
            '{{%attendance}}',
            'papl_id'
        );

        // add foreign key for table `{{%papl}}`
        $this->addForeignKey(
            '{{%fk-attendance-papl_id}}',
            '{{%attendance}}',
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
            '{{%fk-attendance-papl_id}}',
            '{{%attendance}}'
        );

        // drops index for column `papl_id`
        $this->dropIndex(
            '{{%idx-attendance-papl_id}}',
            '{{%attendance}}'
        );

        $this->dropTable('{{%attendance}}');
    }
}
