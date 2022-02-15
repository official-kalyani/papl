<?php

use yii\db\Migration;

/**
 * Class m210719_091848_alter_column_is_delete_purchaseorder
 */
class m210719_091848_alter_column_is_delete_purchaseorder extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%purchaseOrder}}', 'is_delete', $this->boolean()->defaultValue('0')->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210719_091848_alter_column_is_delete_purchaseorder cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210719_091848_alter_column_is_delete_purchaseorder cannot be reverted.\n";

        return false;
    }
    */
}
