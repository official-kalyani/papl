<?php

use yii\db\Migration;

/**
 * Class m210629_100444_alter_column_is_delete_location
 */
class m210629_100444_alter_column_is_delete_location extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%location}}', 'is_delete', $this->boolean()->defaultValue('0'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210629_100444_alter_column_is_delete_location cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210629_100444_alter_column_is_delete_location cannot be reverted.\n";

        return false;
    }
    */
}
