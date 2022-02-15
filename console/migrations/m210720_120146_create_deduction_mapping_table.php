<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%deduction_mapping}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%papl}}`
 * - `{{%salary}}`
 */
class m210720_120146_create_deduction_mapping_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%deduction_mapping}}', [
            'id' => $this->primaryKey(),
            'papl_id' => $this->string(),
            'salary_id' => $this->Integer(),
            'status' => $this->string(10),
            'type' => $this->string(10),
            'is_delete' => $this->boolean(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        // creates index for column `papl_id`
        $this->createIndex(
            '{{%idx-deduction_mapping-papl_id}}',
            '{{%deduction_mapping}}',
            'papl_id'
        );

        // add foreign key for table `{{%papl}}`
        $this->addForeignKey(
            '{{%fk-deduction_mapping-papl_id}}',
            '{{%deduction_mapping}}',
            'papl_id',
            '{{%employee}}',
            'papl_id',
            'CASCADE'
        );

        // creates index for column `salary_id`
        $this->createIndex(
            '{{%idx-deduction_mapping-salary_id}}',
            '{{%deduction_mapping}}',
            'salary_id'
        );

        // add foreign key for table `{{%salary}}`
        $this->addForeignKey(
            '{{%fk-deduction_mapping-salary_id}}',
            '{{%deduction_mapping}}',
            'salary_id',
            '{{%salary}}',
            'id',
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
            '{{%fk-deduction_mapping-papl_id}}',
            '{{%deduction_mapping}}'
        );

        // drops index for column `papl_id`
        $this->dropIndex(
            '{{%idx-deduction_mapping-papl_id}}',
            '{{%deduction_mapping}}'
        );

        // drops foreign key for table `{{%salary}}`
        $this->dropForeignKey(
            '{{%fk-deduction_mapping-salary_id}}',
            '{{%deduction_mapping}}'
        );

        // drops index for column `salary_id`
        $this->dropIndex(
            '{{%idx-deduction_mapping-salary_id}}',
            '{{%deduction_mapping}}'
        );

        $this->dropTable('{{%deduction_mapping}}');
    }
}
