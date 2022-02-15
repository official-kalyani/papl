<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%nominee}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%enrolement}}`
 */
class m210709_132214_create_nominee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%nominee}}', [
            'id' => $this->primaryKey(),
            'enrolement_id' => $this->string(10),
            'nominee_name' => $this->string(),
            'nominee_relation' => $this->string(),
            'nominee_dob' => $this->string(),
            'nominee_adhar_number' => $this->string(),
            'nominee_adhar_photo' => $this->string(),
            'nominee_percentage' => $this->string(),
            'nominee_address' => $this->string(),
            'is_delete' => $this->Integer(1)->defaultValue(0),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        // creates index for column `enrolement_id`
        $this->createIndex(
            '{{%idx-nominee-enrolement_id}}',
            '{{%nominee}}',
            'enrolement_id'
        );

        // add foreign key for table `{{%enrolement}}`
        $this->addForeignKey(
            '{{%fk-nominee-enrolement_id}}',
            '{{%nominee}}',
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
            '{{%fk-nominee-enrolement_id}}',
            '{{%nominee}}'
        );

        // drops index for column `enrolement_id`
        $this->dropIndex(
            '{{%idx-nominee-enrolement_id}}',
            '{{%nominee}}'
        );

        $this->dropTable('{{%nominee}}');
    }
}
