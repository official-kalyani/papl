<?php

use yii\db\Migration;

/**
 * Class m210903_090735_alter_column_is_delete_section_table
 */
class m210903_090735_alter_column_is_delete_section_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%section}}', 'is_delete', $this->boolean()->defaultValue('0')->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210903_090735_alter_column_is_delete_section_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210903_090735_alter_column_is_delete_section_table cannot be reverted.\n";

        return false;
    }
    */
}
