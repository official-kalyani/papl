<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%all}}`.
 */
class m211101_115744_add_created_by_updated_by_column_to_all_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('{{%acl_mapping}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%acl_mapping}}', 'updated_by', $this->string(100)->after('created_by'));
        $this->addColumn('{{%attendance}}', 'created_by', $this->string(100)->after('remark'));
        $this->addColumn('{{%attendance}}', 'updated_by', $this->string(100)->after('created_by'));
        $this->addColumn('{{%bank_details}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%bank_details}}', 'updated_by', $this->string(100)->after('created_by'));
        $this->addColumn('{{%daily_earning}}', 'created_by', $this->string(100)->after('deduction'));
        $this->addColumn('{{%daily_earning}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%deduction}}', 'created_by', $this->string(100)->after('is_delete'));
        $this->addColumn('{{%deduction}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%deduction_mapping}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%deduction_mapping}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%document}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%document}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%employee}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%employee}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%enrollment}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%enrollment}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%family}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%family}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%location}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%location}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%monthly_salary}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%monthly_salary}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%nominee}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%nominee}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%plant}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%plant}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%posting_history}}', 'created_by', $this->string(100)->after('end_date'));
        $this->addColumn('{{%posting_history}}', 'updated_user', $this->string(100)->after('created_by'));
$this->addColumn('{{%purchaseOrder}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%purchaseOrder}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%qualification}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%qualification}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%salary}}', 'created_by', $this->string(100)->after('is_delete'));
        $this->addColumn('{{%salary}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%salary_mapping}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%salary_mapping}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%section}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%section}}', 'updated_by', $this->string(100)->after('created_by'));
$this->addColumn('{{%state}}', 'created_by', $this->string(100)->after('updated_at'));
        $this->addColumn('{{%state}}', 'updated_by', $this->string(100)->after('created_by'));




        

        




        

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%acl_mapping}}', 'created_by');
        $this->dropColumn('{{%acl_mapping}}', 'updated_by');
        $this->dropColumn('{{%attendance}}', 'created_by');
        $this->dropColumn('{{%attendance}}', 'updated_by');
        $this->dropColumn('{{%bank_details}}', 'created_by');
        $this->dropColumn('{{%bank_details}}', 'updated_by');
        $this->dropColumn('{{%daily_earning}}', 'created_by');
        $this->dropColumn('{{%daily_earning}}', 'updated_by');
        $this->dropColumn('{{%deduction}}', 'created_by');
        $this->dropColumn('{{%deduction}}', 'updated_by');
        $this->dropColumn('{{%deduction_mapping}}', 'created_by');
        $this->dropColumn('{{%deduction_mapping}}', 'updated_by');
        $this->dropColumn('{{%document}}', 'created_by');
        $this->dropColumn('{{%document}}', 'updated_by');
        $this->dropColumn('{{%employee}}', 'created_by');
        $this->dropColumn('{{%employee}}', 'updated_by');
        $this->dropColumn('{{%enrollment}}', 'created_by');
        $this->dropColumn('{{%enrollment}}', 'updated_by');
        $this->dropColumn('{{%family}}', 'created_by');
        $this->dropColumn('{{%family}}', 'updated_by');
        $this->dropColumn('{{%location}}', 'created_by');
        $this->dropColumn('{{%location}}', 'updated_by');
        $this->dropColumn('{{%monthly_salary}}', 'created_by');
        $this->dropColumn('{{%monthly_salary}}', 'updated_by');
        $this->dropColumn('{{%nominee}}', 'created_by');
        $this->dropColumn('{{%nominee}}', 'updated_by');
        $this->dropColumn('{{%plant}}', 'created_by');
        $this->dropColumn('{{%plant}}', 'updated_by');
        $this->dropColumn('{{%posting_history}}', 'created_by');
        $this->dropColumn('{{%posting_history}}', 'updated_user');
        $this->dropColumn('{{%purchaseOrder}}', 'created_by');
        $this->dropColumn('{{%purchaseOrder}}', 'updated_by');
        $this->dropColumn('{{%qualification}}', 'created_by');
        $this->dropColumn('{{%qualification}}', 'updated_by');
        $this->dropColumn('{{%salary}}', 'created_by');
        $this->dropColumn('{{%salary}}', 'updated_by');
         $this->dropColumn('{{%section}}', 'created_by');
        $this->dropColumn('{{%section}}', 'updated_by');
        $this->dropColumn('{{%state}}', 'created_by');
        $this->dropColumn('{{%state}}', 'updated_by');
       
    }
}
