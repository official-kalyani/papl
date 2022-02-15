<?php

use yii\db\Migration;

/**
 * Class m210810_130607_alter_amount_salary_mapping
 */
class m210810_130607_alter_amount_salary_mapping extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%salary_mapping}}', 'amount', $this->decimal(9,2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210810_130607_alter_amount_salary_mapping cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210810_130607_alter_amount_salary_mapping cannot be reverted.\n";

        return false;
    }
    */
}
