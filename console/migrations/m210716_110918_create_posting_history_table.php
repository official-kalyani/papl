<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%posting_history}}`.
 */
class m210716_110918_create_posting_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%posting_history}}', [
            'id' => $this->primaryKey(),
            'state_id' => $this->integer(11),
            'location_id' => $this->integer(11),
            'plant_id' => $this->integer(11),
            'purchase_orderid' => $this->integer(11),
            'section_id' => $this->integer(11),
            'start_date' => $this->string(),
            'end_date' => $this->string(),
            'updated_by' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%posting_history}}');
    }
}
