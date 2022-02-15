<?php

use yii\db\Migration;

/**
 * Class m210705_161956_alter_column_plant_id_foreignkey_to_enrollment_table
 */
class m210705_161956_alter_column_plant_id_foreignkey_to_enrollment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            '{{%idx-enrollment-plant_id}}',
            '{{%enrollment}}',
            'plant_id'
        );

        // add foreign key for table `{{%location}}`
        $this->addForeignKey(
            '{{%fk-enrollment-plant_id}}',
            '{{%enrollment}}',
            'plant_id',
            '{{%plant}}',
            'plant_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210705_161956_alter_column_plant_id_foreignkey_to_enrollment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210705_161956_alter_column_plant_id_foreignkey_to_enrollment_table cannot be reverted.\n";

        return false;
    }
    */
}
