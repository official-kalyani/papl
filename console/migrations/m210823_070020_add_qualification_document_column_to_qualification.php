<?php

use yii\db\Migration;

/**
 * Class m210823_070020_add_qualification_document_column_to_qualification
 */
class m210823_070020_add_qualification_document_column_to_qualification extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%qualification}}', 'qualification_document', $this->string()->after('division_percent'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210823_070020_add_qualification_document_column_to_qualification cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210823_070020_add_qualification_document_column_to_qualification cannot be reverted.\n";

        return false;
    }
    */
}
