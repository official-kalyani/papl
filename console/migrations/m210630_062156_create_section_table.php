<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%section}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%location}}`
 * - `{{%state}}`
 * - `{{%plant}}`
 * - `{{%po}}`
 */
class m210630_062156_create_section_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%section}}', [
            'section_id' => $this->primaryKey(),
            'section_name' => $this->string(),
            'location_id' => $this->integer(),
            'state_id' => $this->integer(),
            'plant_id' => $this->integer(),
            'po_id' => $this->integer(),
            'is_delete' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        // creates index for column `location_id`
        $this->createIndex(
            '{{%idx-section-location_id}}',
            '{{%section}}',
            'location_id'
        );

        // add foreign key for table `{{%location}}`
        $this->addForeignKey(
            '{{%fk-section-location_id}}',
            '{{%section}}',
            'location_id',
            '{{%location}}',
            'location_id',
            'CASCADE'
        );

        // creates index for column `state_id`
        $this->createIndex(
            '{{%idx-section-state_id}}',
            '{{%section}}',
            'state_id'
        );

        // add foreign key for table `{{%state}}`
        $this->addForeignKey(
            '{{%fk-section-state_id}}',
            '{{%section}}',
            'state_id',
            '{{%state}}',
            'state_id',
            'CASCADE'
        );

        // creates index for column `plant_id`
        $this->createIndex(
            '{{%idx-section-plant_id}}',
            '{{%section}}',
            'plant_id'
        );

        // add foreign key for table `{{%plant}}`
        $this->addForeignKey(
            '{{%fk-section-plant_id}}',
            '{{%section}}',
            'plant_id',
            '{{%plant}}',
            'plant_id',
            'CASCADE'
        );

        // creates index for column `po_id`
        $this->createIndex(
            '{{%idx-section-po_id}}',
            '{{%section}}',
            'po_id'
        );

        // add foreign key for table `{{%po}}`
        $this->addForeignKey(
            '{{%fk-section-po_id}}',
            '{{%section}}',
            'po_id',
            '{{%purchaseOrder}}',
            'po_id',
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
            '{{%fk-section-location_id}}',
            '{{%section}}'
        );

        // drops index for column `location_id`
        $this->dropIndex(
            '{{%idx-section-location_id}}',
            '{{%section}}'
        );

        // drops foreign key for table `{{%state}}`
        $this->dropForeignKey(
            '{{%fk-section-state_id}}',
            '{{%section}}'
        );

        // drops index for column `state_id`
        $this->dropIndex(
            '{{%idx-section-state_id}}',
            '{{%section}}'
        );

        // drops foreign key for table `{{%plant}}`
        $this->dropForeignKey(
            '{{%fk-section-plant_id}}',
            '{{%section}}'
        );

        // drops index for column `plant_id`
        $this->dropIndex(
            '{{%idx-section-plant_id}}',
            '{{%section}}'
        );

        // drops foreign key for table `{{%po}}`
        $this->dropForeignKey(
            '{{%fk-section-po_id}}',
            '{{%section}}'
        );

        // drops index for column `po_id`
        $this->dropIndex(
            '{{%idx-section-po_id}}',
            '{{%section}}'
        );

        $this->dropTable('{{%section}}');
    }
}
