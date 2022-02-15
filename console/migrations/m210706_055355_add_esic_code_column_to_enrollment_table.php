<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%enrollment}}`.
 */
class m210706_055355_add_esic_code_column_to_enrollment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
       $this->addColumn('{{%enrollment}}', 'esic_code', $this->string()->after('caste'));
        $this->addColumn('{{%enrollment}}', 'esic_ip_number', $this->string()->after('esic_code'));
        $this->addColumn('{{%enrollment}}', 'wca_gpa', $this->string()->after('esic_ip_number'));
        $this->addColumn('{{%enrollment}}', 'wca_gpa_expire_date', $this->dateTime()->after('wca_gpa'));
        $this->addColumn('{{%enrollment}}', 'esic_sheet', $this->string()->after('wca_gpa_expire_date'));
        $this->addColumn('{{%enrollment}}', 'pf_code', $this->string()->after('esic_sheet'));
        $this->addColumn('{{%enrollment}}', 'uan', $this->string()->after('pf_code'));
        $this->addColumn('{{%enrollment}}', 'pf_account_number', $this->string()->after('uan'));
        $this->addColumn('{{%enrollment}}', 'uan_sheet', $this->string()->after('pf_account_number'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%enrollment}}', 'esic_code');
    }
}
