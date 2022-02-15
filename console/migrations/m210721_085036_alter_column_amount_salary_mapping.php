<?php

use yii\db\Migration;

/**
 * Class m210721_085036_alter_column_amount_salary_mapping
 */
class m210721_085036_alter_column_amount_salary_mapping extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%salary_mapping}}', 'amount', $this->money());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210721_085036_alter_column_amount_salary_mapping cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210721_085036_alter_column_amount_salary_mapping cannot be reverted.\n";

        return false;
    }
    */
}
