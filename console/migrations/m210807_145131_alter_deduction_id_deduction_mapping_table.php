<?php

use yii\db\Migration;

/**
 * Class m210807_145131_alter_deduction_id_deduction_mapping_table
 */
class m210807_145131_alter_deduction_id_deduction_mapping_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%salary}}`
        $this->dropForeignKey(
            '{{%fk-deduction_mapping-salary_id}}',
            '{{%deduction_mapping}}'
        );

        // drops index for column `salary_id`
        $this->dropIndex(
            '{{%idx-deduction_mapping-salary_id}}',
            '{{%deduction_mapping}}'
        );
       // creates index for column `salary_id`
       $this->createIndex(
            '{{%idx-deduction_mapping-deduction_id}}',
            '{{%deduction_mapping}}',
            'deduction_id'
        );

        // add foreign key for table `{{%salary}}`
        $this->addForeignKey(
            '{{%fk-deduction_mapping-deduction_id}}',
            '{{%deduction_mapping}}',
            'deduction_id',
            '{{%deduction}}',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210807_145131_alter_deduction_id_deduction_mapping_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210807_145131_alter_deduction_id_deduction_mapping_table cannot be reverted.\n";

        return false;
    }
    */
}
