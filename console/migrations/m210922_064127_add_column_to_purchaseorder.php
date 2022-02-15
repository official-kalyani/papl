<?php

use yii\db\Migration;

/**
 * Class m210922_064127_add_column_to_purchaseorder
 */
class m210922_064127_add_column_to_purchaseorder extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%purchaseOrder}}', 'po_number', $this->string(100)->defaultValue(null)->after('purchase_order_name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210922_064127_add_column_to_purchaseorder cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210922_064127_add_column_to_purchaseorder cannot be reverted.\n";

        return false;
    }
    */
}
