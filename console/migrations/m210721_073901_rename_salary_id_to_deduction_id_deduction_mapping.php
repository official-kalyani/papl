<?php

use yii\db\Migration;

/**
 * Class m210721_073901_rename_salary_id_to_deduction_id_deduction_mapping
 */
class m210721_073901_rename_salary_id_to_deduction_id_deduction_mapping extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $this->renameColumn ( '{{%deduction_mapping}}', 'salary_id', 'deduction_id' );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210721_073901_rename_salary_id_to_deduction_id_deduction_mapping cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210721_073901_rename_salary_id_to_deduction_id_deduction_mapping cannot be reverted.\n";

        return false;
    }
    */
}
