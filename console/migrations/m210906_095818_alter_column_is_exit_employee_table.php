<?php

use yii\db\Migration;

/**
 * Class m210906_095818_alter_column_is_exit_employee_table
 */
class m210906_095818_alter_column_is_exit_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->alterColumn('{{%employee}}', 'is_exit', $this->boolean()->defaultValue('0')->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210906_095818_alter_column_is_exit_employee_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210906_095818_alter_column_is_exit_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
