<?php

use yii\db\Migration;

/**
 * Class m210921_044143_add_name_to_acl_table
 */
class m210921_044143_add_name_to_acl_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%acl}}', 'name', $this->string(150)->after('url')->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210921_044143_add_name_to_acl_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210921_044143_add_name_to_acl_table cannot be reverted.\n";

        return false;
    }
    */
}
