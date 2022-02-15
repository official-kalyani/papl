<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%family}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%enrolement}}`
 */
class m210709_132629_create_family_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%family}}', [
            'id' => $this->primaryKey(),
            'enrolement_id' => $this->string(10),
            'family_member' => $this->string(),
            'family_member_dob' => $this->string(10)->defaultValue('00-00-0000')->notNull(),
            'family_member_relation' => $this->string(),
            'family_member_residence' => $this->string(),
            'family_nominee_adhar_photo' => $this->string(),
            'is_delete' => $this->Integer(1)->defaultValue(0),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        // creates index for column `enrolement_id`
        $this->createIndex(
            '{{%idx-family-enrolement_id}}',
            '{{%family}}',
            'enrolement_id'
        );

        // add foreign key for table `{{%enrolement}}`
        $this->addForeignKey(
            '{{%fk-family-enrolement_id}}',
            '{{%family}}',
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
            '{{%fk-family-enrolement_id}}',
            '{{%family}}'
        );

        // drops index for column `enrolement_id`
        $this->dropIndex(
            '{{%idx-family-enrolement_id}}',
            '{{%family}}'
        );

        $this->dropTable('{{%family}}');
    }
}
