<?php

use yii\db\Migration;

/**
 * Class m211004_073504_add_column_is_esi_to_table_plant
 */
class m211004_073504_add_column_is_esi_to_table_plant extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%plant}}', 'is_esi', $this->boolean()->defaultValue('0')->notNull()->after('is_delete'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211004_073504_add_column_is_esi_to_table_plant cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211004_073504_add_column_is_esi_to_table_plant cannot be reverted.\n";

        return false;
    }
    */
}
