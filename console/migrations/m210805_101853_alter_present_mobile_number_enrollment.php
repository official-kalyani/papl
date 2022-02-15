<?php

use yii\db\Migration;

/**
 * Class m210805_101853_alter_present_mobile_number_enrollment
 */
class m210805_101853_alter_present_mobile_number_enrollment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%enrollment}}', 'present_mobile_number', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210805_101853_alter_present_mobile_number_enrollment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210805_101853_alter_present_mobile_number_enrollment cannot be reverted.\n";

        return false;
    }
    */
}
