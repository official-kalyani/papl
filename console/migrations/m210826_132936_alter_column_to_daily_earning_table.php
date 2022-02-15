<?php

use yii\db\Migration;

/**
 * Class m210826_132936_alter_column_to_daily_earning_table
 */
class m210826_132936_alter_column_to_daily_earning_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%daily_earning}}', 'type');
        $this->dropColumn('{{%daily_earning}}', 'att_type');
        $this->addColumn('{{%daily_earning}}', 'att', $this->float()->after('papl_id'));
        $this->addColumn('{{%daily_earning}}', 'basic', $this->float()->after('att'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210826_132936_alter_column_to_daily_earning_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210826_132936_alter_column_to_daily_earning_table cannot be reverted.\n";

        return false;
    }
    */
}
