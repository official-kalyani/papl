<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%location}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%state}}`
 */
class m210629_095507_add_state_id_column_to_location_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%location}}', 'state_id', $this->integer()->after('location_name'));

        // creates index for column `state_id`
        $this->createIndex(
            '{{%idx-location-state_id}}',
            '{{%location}}',
            'state_id'
        );

        // add foreign key for table `{{%state}}`
        $this->addForeignKey(
            '{{%fk-location-state_id}}',
            '{{%location}}',
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
        // drops foreign key for table `{{%state}}`
        $this->dropForeignKey(
            '{{%fk-location-state_id}}',
            '{{%location}}'
        );

        // drops index for column `state_id`
        $this->dropIndex(
            '{{%idx-location-state_id}}',
            '{{%location}}'
        );

        $this->dropColumn('{{%location}}', 'state_id');
    }
}
