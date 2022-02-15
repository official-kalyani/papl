<?php

use yii\db\Migration;

/**
 * Class m210719_104625_alter_papl_id_to_foreignkey_posting_history
 */
class m210719_104625_alter_papl_id_to_foreignkey_posting_history extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%posting_history}}', 'papl_id', $this->string()->after('id')->unique());
        $this->createIndex(
            '{{%idx-posting_history-papl_id}}',
            '{{%posting_history}}',
            'papl_id'
        );

        // add foreign key for table `{{%PostingHistory}}`
        $this->addForeignKey(
            '{{%fk-posting_history-papl_id}}',
            '{{%posting_history}}',
            'papl_id',
            '{{%enrollment}}',
            'papl_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210719_104625_alter_papl_id_to_foreignkey_posting_history cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210719_104625_alter_papl_id_to_foreignkey_posting_history cannot be reverted.\n";

        return false;
    }
    */
}
