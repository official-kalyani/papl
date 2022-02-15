<?php

use yii\db\Migration;

/**
 * Class m210709_092101_alter_wca_gpa_expire_date_enrollment_table
 */
class m210709_092101_alter_wca_gpa_expire_date_enrollment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%enrollment}}', 'wca_gpa_expire_date', $this->string(10)->defaultValue('00-00-0000')->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210709_092101_alter_wca_gpa_expire_date_enrollment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210709_092101_alter_wca_gpa_expire_date_enrollment_table cannot be reverted.\n";

        return false;
    }
    */
}
