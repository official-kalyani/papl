<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%salary_mapping}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%papl}}`
 * - `{{%salary}}`
 */
class m210720_112409_create_salary_mapping_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%salary_mapping}}', [
            'id' => $this->primaryKey(),
            'papl_id' => $this->string(),
            'salary_id' => $this->Integer(),
            'status' => $this->string(10),
            'is_delete' => $this->boolean(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        // creates index for column `papl_id`
        $this->createIndex(
            '{{%idx-salary_mapping-papl_id}}',
            '{{%salary_mapping}}',
            'papl_id'
        );

        // add foreign key for table `{{%papl}}`
        $this->addForeignKey(
            '{{%fk-salary_mapping-papl_id}}',
            '{{%salary_mapping}}',
            'papl_id',
            '{{%employee}}',
            'papl_id',
            'CASCADE'
        );

        // creates index for column `salary_id`
        $this->createIndex(
            '{{%idx-salary_mapping-salary_id}}',
            '{{%salary_mapping}}',
            'salary_id'
        );

        // add foreign key for table `{{%salary}}`
        $this->addForeignKey(
            '{{%fk-salary_mapping-salary_id}}',
            '{{%salary_mapping}}',
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
            '{{%fk-salary_mapping-papl_id}}',
            '{{%salary_mapping}}'
        );

        // drops index for column `papl_id`
        $this->dropIndex(
            '{{%idx-salary_mapping-papl_id}}',
            '{{%salary_mapping}}'
        );

        // drops foreign key for table `{{%salary}}`
        $this->dropForeignKey(
            '{{%fk-salary_mapping-salary_id}}',
            '{{%salary_mapping}}'
        );

        // drops index for column `salary_id`
        $this->dropIndex(
            '{{%idx-salary_mapping-salary_id}}',
            '{{%salary_mapping}}'
        );

        $this->dropTable('{{%salary_mapping}}');
    }
}
