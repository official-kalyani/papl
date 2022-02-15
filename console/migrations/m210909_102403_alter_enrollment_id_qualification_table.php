<?php

use yii\db\Migration;

/**
 * Class m210909_102403_alter_enrollment_id_qualification_table
 */
class m210909_102403_alter_enrollment_id_qualification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%qualification}}', 'enrolement_id', $this->string(25)
            ->defaultValue(''));
        $this->alterColumn('{{%nominee}}', 'enrolement_id', $this->string(25));
        $this->alterColumn('{{%bank_details}}', 'enrolement_id', $this->string(25));
        $this->alterColumn('{{%document}}', 'enrolement_id', $this->string(25));
        $this->alterColumn('{{%family}}', 'enrolement_id', $this->string(25));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210909_102403_alter_enrollment_id_qualification_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210909_102403_alter_enrollment_id_qualification_table cannot be reverted.\n";

        return false;
    }
    */
}
