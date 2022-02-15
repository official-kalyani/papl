<?php

use yii\db\Migration;

/**
 * Class m210706_071404_alter_column_is_delete_plant
 */
class m210706_071404_alter_column_is_delete_plant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%plant}}', 'is_delete', $this->boolean()->defaultValue('0')->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210706_071404_alter_column_is_delete_plant cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210706_071404_alter_column_is_delete_plant cannot be reverted.\n";

        return false;
    }
    */
}
