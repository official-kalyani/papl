<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%daily_earning}}`.
 */
class m210807_101853_add_columns_to_daily_earning_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%daily_earning}}', 'location_id', $this->integer(11)->after('ondate'));
        $this->addColumn('{{%daily_earning}}', 'plant_id', $this->integer(11));
        $this->addColumn('{{%daily_earning}}', 'purchase_orderid', $this->integer(11));
        $this->addColumn('{{%daily_earning}}', 'section_id', $this->integer(11));
        $this->addColumn('{{%daily_earning}}', 'earnings', $this->text());
        $this->addColumn('{{%daily_earning}}', 'deduction', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%daily_earning}}', 'location_id');
        $this->dropColumn('{{%daily_earning}}', 'plant_id');
        $this->dropColumn('{{%daily_earning}}', 'purchase_orderid');
        $this->dropColumn('{{%daily_earning}}', 'section_id');
        $this->dropColumn('{{%daily_earning}}', 'earnings');
        $this->dropColumn('{{%daily_earning}}', 'deduction');
    }
}
