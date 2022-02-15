<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plant}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%location}}`
 * - `{{%state}}`
 */
class m210629_132027_create_plant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plant}}', [
            'plant_id' => $this->primaryKey(),
            'plant_name' => $this->string(),
            'location_id' => $this->integer(),
            'state_id' => $this->integer(),
            'is_delete' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        // creates index for column `location_id`
        $this->createIndex(
            '{{%idx-plant-location_id}}',
            '{{%plant}}',
            'location_id'
        );

        // add foreign key for table `{{%location}}`
        $this->addForeignKey(
            '{{%fk-plant-location_id}}',
            '{{%plant}}',
            'location_id',
            '{{%location}}',
            'location_id',
            'CASCADE'
        );

        // creates index for column `state_id`
        $this->createIndex(
            '{{%idx-plant-state_id}}',
            '{{%plant}}',
            'state_id'
        );

        // add foreign key for table `{{%state}}`
        $this->addForeignKey(
            '{{%fk-plant-state_id}}',
            '{{%plant}}',
            'state_id',
            '{{%state}}',
            'state_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%location}}`
        $this->dropForeignKey(
            '{{%fk-plant-location_id}}',
            '{{%plant}}'
        );

        // drops index for column `location_id`
        $this->dropIndex(
            '{{%idx-plant-location_id}}',
            '{{%plant}}'
        );

        // drops foreign key for table `{{%state}}`
        $this->dropForeignKey(
            '{{%fk-plant-state_id}}',
            '{{%plant}}'
        );

        // drops index for column `state_id`
        $this->dropIndex(
            '{{%idx-plant-state_id}}',
            '{{%plant}}'
        );

        $this->dropTable('{{%plant}}');
    }
}
