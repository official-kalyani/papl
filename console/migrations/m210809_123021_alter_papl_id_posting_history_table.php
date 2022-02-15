<?php

use yii\db\Migration;

/**
 * Class m210809_123021_alter_papl_id_posting_history_table
 */
class m210809_123021_alter_papl_id_posting_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('ALTER TABLE `posting_history` DROP INDEX `papl_id`;')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210809_123021_alter_papl_id_posting_history_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210809_123021_alter_papl_id_posting_history_table cannot be reverted.\n";

        return false;
    }
    */
}
