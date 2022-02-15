<?php

use yii\db\Migration;

/**
 * Class m210721_075315_alter_column_amount_deduction_mapping
 */
class m210721_075315_alter_column_amount_deduction_mapping extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%deduction_mapping}}', 'amount', $this->money());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210721_075315_alter_column_amount_deduction_mapping cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210721_075315_alter_column_amount_deduction_mapping cannot be reverted.\n";

        return false;
    }
    */
}
