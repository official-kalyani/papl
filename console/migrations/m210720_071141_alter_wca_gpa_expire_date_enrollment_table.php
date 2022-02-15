<?php

use yii\db\Migration;

/**
 * Class m210720_071141_alter_wca_gpa_expire_date_enrollment_table
 */
class m210720_071141_alter_wca_gpa_expire_date_enrollment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%enrollment}}', 'wca_gpa_expire_date', $this->string(10)->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210720_071141_alter_wca_gpa_expire_date_enrollment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210720_071141_alter_wca_gpa_expire_date_enrollment_table cannot be reverted.\n";

        return false;
    }
    */
}
