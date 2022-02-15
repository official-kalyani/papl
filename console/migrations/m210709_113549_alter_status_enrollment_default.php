<?php

use yii\db\Migration;

/**
 * Class m210709_113549_alter_status_enrollment_default
 */
class m210709_113549_alter_status_enrollment_default extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%enrollment}}', 'status', $this->tinyInteger()->comment('0=>no action, 1=> transfer_to plant,2=>approved_by_plant_mgr,3=>epf_esi created,4=>rejected,5=>parmanent')
            ->defaultValue('0')->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210709_113549_alter_status_enrollment_default cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210709_113549_alter_status_enrollment_default cannot be reverted.\n";

        return false;
    }
    */
}
