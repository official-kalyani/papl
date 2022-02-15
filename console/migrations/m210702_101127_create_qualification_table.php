<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%qualification}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%enrolement}}`
 */
class m210702_101127_create_qualification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%qualification}}', [
            'id' => $this->primaryKey(),
            'qualification_id' => $this->integer(),
            'enrolement_id' => $this->string(10),
            'highest_qualification' => $this->string(),
            'university_name' => $this->string(),
            'college_name' => $this->string(),
            'year_of_passout' => $this->integer(),
            'division_percent' => $this->string(),
            'is_delete' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        // creates index for column `enrolement_id`
        $this->createIndex(
            '{{%idx-qualification-enrolement_id}}',
            '{{%qualification}}',
            'enrolement_id'
        );

        // add foreign key for table `{{%enrolement}}`
        $this->addForeignKey(
            '{{%fk-qualification-enrolement_id}}',
            '{{%qualification}}',
            'enrolement_id',
            '{{%enrollment}}',
            'enrolement_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%enrolement}}`
        $this->dropForeignKey(
            '{{%fk-qualification-enrolement_id}}',
            '{{%qualification}}'
        );

        // drops index for column `enrolement_id`
        $this->dropIndex(
            '{{%idx-qualification-enrolement_id}}',
            '{{%qualification}}'
        );

        $this->dropTable('{{%qualification}}');
    }
}
