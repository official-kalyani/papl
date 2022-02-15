<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%PAPLdesignation}}`.
 */
class m211106_092816_add_updated_by_column_to_PAPLdesignation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%PAPLdesignation}}', 'updated_by', $this->string(100)->after('status'));
        $this->addColumn('{{%PAPLdesignation}}', 'updated_at', $this->timestamp()->defaultExpression('NOW()')->after('updated_by'));
         $this->addColumn('{{%PAPLdesignation}}', 'created_at', $this->timestamp()->defaultExpression('NOW()')->after('updated_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
