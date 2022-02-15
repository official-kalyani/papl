<?php

use yii\db\Migration;

/**
 * Class m210629_100134_alter_column_is_delete_state
 */
class m210629_100134_alter_column_is_delete_state extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%state}}', 'is_delete', $this->boolean()->defaultValue('0')->notNull());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210629_100134_alter_column_is_delete_state cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210629_100134_alter_column_is_delete_state cannot be reverted.\n";

        return false;
    }
    */
}
