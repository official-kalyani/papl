<?php

use yii\db\Migration;

/**
 * Class m210708_190710_alter_dob_enrollment_table
 */
class m210708_190710_alter_dob_enrollment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%enrollment}}', 'dob', $this->string(10)->defaultValue('00-00-0000')->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210708_190710_alter_dob_enrollment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210708_190710_alter_dob_enrollment_table cannot be reverted.\n";

        return false;
    }
    */
}
