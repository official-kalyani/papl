<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "monthly_salary".
 *
 * @property int $id
 * @property string|null $papl_id
 * @property string|null $designation
 * @property int|null $plant_id
 * @property string|null $from_date
 * @property string|null $to_date
 * @property string|null $month_year
 * @property string|null $earning_detail
 * @property string|null $deduction_detail
 * @property float|null $total_basic
 * @property float|null $total_gross
 * @property float|null $misc_att
 * @property float|null $total_salary
 * @property float|null $total_deduction
 * @property float|null $net_payble
 * @property float|null $lwf_refund
 * @property float|null $esi_refund
 * @property float|null $total_payble
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Employee $papl
 */
class MonthlySalary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'monthly_salary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plant_id'], 'integer'],
            [['from_date', 'to_date', 'created_at', 'updated_at'], 'safe'],
            [['earning_detail', 'deduction_detail'], 'string'],
            [['total_basic', 'total_gross', 'misc_att', 'total_salary', 'total_deduction', 'net_payble', 'lwf_refund', 'esi_refund', 'total_payble'], 'number'],
            [['papl_id', 'designation'], 'string', 'max' => 255],
            [['month_year'], 'string', 'max' => 100],
            [['papl_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['papl_id' => 'papl_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'papl_id' => 'Papl ID',
            'designation' => 'Designation',
            'plant_id' => 'Plant ID',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'month_year' => 'Month Year',
            'earning_detail' => 'Earning Detail',
            'deduction_detail' => 'Deduction Detail',
            'total_basic' => 'Total Basic',
            'total_gross' => 'Total Gross',
            'misc_att' => 'Misc Att',
            'total_salary' => 'Total Salary',
            'total_deduction' => 'Total Deduction',
            'net_payble' => 'Net Payble',
            'lwf_refund' => 'Lwf Refund',
            'esi_refund' => 'Esi Refund',
            'total_payble' => 'Total Payble',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Papl]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPapl()
    {
        return $this->hasOne(Employee::className(), ['papl_id' => 'papl_id']);
    }
}
