<?php

use yii\db\Migration;

/**
 * Class m211030_101024_papldesignation
 */
class m211030_101024_papldesignation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%PAPLdesignation}}', [
            'id' => $this->primaryKey(),
            'PAPLdesignation' => $this->string(),            
            'status' => $this->boolean()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211030_101024_papldesignation cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211030_101024_papldesignation cannot be reverted.\n";

        return false;
    }
    */
}
