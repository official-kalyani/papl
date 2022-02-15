<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%enrollment}}`.
 */
class m210702_112516_add_designation_column_category_column_PAPLdesignation_column_experience_in_year_column_experience_column_to_enrollment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $this->addColumn('{{%enrollment}}', 'designation', $this->string());
        $this->addColumn('{{%enrollment}}', 'category', $this->string());
        $this->addColumn('{{%enrollment}}', 'PAPLdesignation', $this->string());
        $this->addColumn('{{%enrollment}}', 'experience', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%enrollment}}', 'designation');
        $this->dropColumn('{{%enrollment}}', 'category');
        $this->dropColumn('{{%enrollment}}', 'PAPLdesignation');
        $this->dropColumn('{{%enrollment}}', 'experience');
    }
}
