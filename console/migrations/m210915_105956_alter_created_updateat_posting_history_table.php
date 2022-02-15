<?php

use yii\db\Migration;

/**
 * Class m210915_105956_alter_created_updateat_posting_history_table
 */
class m210915_105956_alter_created_updateat_posting_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%posting_history}}', 'updated_by', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210915_105956_alter_created_updateat_posting_history_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210915_105956_alter_created_updateat_posting_history_table cannot be reverted.\n";

        return false;
    }
    */
}
