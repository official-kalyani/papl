<?php

use yii\db\Migration;

/**
 * Class m210715_203739_alter
 */
class m210715_203739_alter_enrollment_papl_id_enrolement_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%enrollment}}', 'papl_id', $this->string(15)
            ->defaultValue(''));
        $this->alterColumn('{{%enrollment}}', 'enrolement_id', $this->string(15));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210715_203739_alter cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210715_203739_alter cannot be reverted.\n";

        return false;
    }
    */
}
