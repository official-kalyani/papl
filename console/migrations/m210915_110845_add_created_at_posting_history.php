<?php

use yii\db\Migration;

/**
 * Class m210915_110845_add_created_at_posting_history
 */
class m210915_110845_add_created_at_posting_history extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%posting_history}}', 'created_at', $this->timestamp()->defaultExpression('NOW()')->after('end_date'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210915_110845_add_created_at_posting_history cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210915_110845_add_created_at_posting_history cannot be reverted.\n";

        return false;
    }
    */
}
