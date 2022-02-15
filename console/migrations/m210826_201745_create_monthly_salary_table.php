<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%monthly_salary}}`.
 */
class m210826_201745_create_monthly_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%monthly_salary}}', [
            'id' => $this->primaryKey(),
            'papl_id' => $this->string(),
            'designation' => $this->string(),
            'plant_id' => $this->integer(),
            'from_date' => $this->date(),
            'to_date' => $this->date(),
            'month_year' => $this->string(100),
            'earning_detail' => $this->text(),
            'deduction_detail' => $this->text(),
            'total_basic' => $this->float(),
            'total_gross' => $this->float(),
            'misc_att' => $this->float(),
            'misc_earning' => $this->float(),
            'total_salary' => $this->float(),
            'total_deduction' => $this->float(),
            'net_payble' => $this->float(),
            'lwf_refund' => $this->float(),
            'esi_refund' => $this->float(),
            'total_payble' => $this->float(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        // creates index for column `papl_id`
        $this->createIndex(
            '{{%idx-monthly_salary-papl_id}}',
            '{{%monthly_salary}}',
            'papl_id'
        );

        // add foreign key for table `{{%papl}}`
        $this->addForeignKey(
            '{{%fk-monthly_salary-papl_id}}',
            '{{%monthly_salary}}',
            'papl_id',
            '{{%employee}}',
            'papl_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         // drops foreign key for table `{{%papl}}`
         $this->dropForeignKey(
            '{{%fk-monthly_salary-papl_id}}',
            '{{%monthly_salary}}'
        );

        // drops index for column `papl_id`
        $this->dropIndex(
            '{{%idx-monthly_salary-papl_id}}',
            '{{%monthly_salary}}'
        );
        $this->dropTable('{{%monthly_salary}}');
    }
}
