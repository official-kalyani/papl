<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%purchaseOrder}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%location}}`
 * - `{{%state}}`
 * - `{{%plant}}`
 */
class m210630_061553_create_purchaseOrder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%purchaseOrder}}', [
            'po_id' => $this->primaryKey(),
            'purchase_order_name' => $this->string(),
            'location_id' => $this->integer(),
            'state_id' => $this->integer(),
            'plant_id' => $this->integer(),
            'is_delete' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        // creates index for column `location_id`
        $this->createIndex(
            '{{%idx-purchaseOrder-location_id}}',
            '{{%purchaseOrder}}',
            'location_id'
        );

        // add foreign key for table `{{%location}}`
        $this->addForeignKey(
            '{{%fk-purchaseOrder-location_id}}',
            '{{%purchaseOrder}}',
            'location_id',
            '{{%location}}',
            'location_id',
            'CASCADE'
        );

        // creates index for column `state_id`
        $this->createIndex(
            '{{%idx-purchaseOrder-state_id}}',
            '{{%purchaseOrder}}',
            'state_id'
        );

        // add foreign key for table `{{%state}}`
        $this->addForeignKey(
            '{{%fk-purchaseOrder-state_id}}',
            '{{%purchaseOrder}}',
            'state_id',
            '{{%state}}',
            'state_id',
            'CASCADE'
        );

        // creates index for column `plant_id`
        $this->createIndex(
            '{{%idx-purchaseOrder-plant_id}}',
            '{{%purchaseOrder}}',
            'plant_id'
        );

        // add foreign key for table `{{%plant}}`
        $this->addForeignKey(
            '{{%fk-purchaseOrder-plant_id}}',
            '{{%purchaseOrder}}',
            'plant_id',
            '{{%plant}}',
            'plant_id',
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
            '{{%fk-purchaseOrder-location_id}}',
            '{{%purchaseOrder}}'
        );

        // drops index for column `location_id`
        $this->dropIndex(
            '{{%idx-purchaseOrder-location_id}}',
            '{{%purchaseOrder}}'
        );

        // drops foreign key for table `{{%state}}`
        $this->dropForeignKey(
            '{{%fk-purchaseOrder-state_id}}',
            '{{%purchaseOrder}}'
        );

        // drops index for column `state_id`
        $this->dropIndex(
            '{{%idx-purchaseOrder-state_id}}',
            '{{%purchaseOrder}}'
        );

        // drops foreign key for table `{{%plant}}`
        $this->dropForeignKey(
            '{{%fk-purchaseOrder-plant_id}}',
            '{{%purchaseOrder}}'
        );

        // drops index for column `plant_id`
        $this->dropIndex(
            '{{%idx-purchaseOrder-plant_id}}',
            '{{%purchaseOrder}}'
        );

        $this->dropTable('{{%purchaseOrder}}');
    }
}
