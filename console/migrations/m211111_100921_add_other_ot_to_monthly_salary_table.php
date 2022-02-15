<?php

use yii\db\Migration;

/**
 * Class m211111_100921_add_other_ot_to_monthly_salary_table
 */
class m211111_100921_add_other_ot_to_monthly_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%monthly_salary}}', 'other_ot_hour', $this->float()->after('misc_earning'));
        $this->addColumn('{{%monthly_salary}}', 'other_ot_earning', $this->float()->after('other_ot_hour'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%monthly_salary}}', 'other_ot_bd');
        $this->dropColumn('{{%monthly_salary}}', 'other_ot_earning');
    }
}
