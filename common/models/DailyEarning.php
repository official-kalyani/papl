<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "daily_earning".
 *
 * @property int $id
 * @property string|null $papl_id
 * @property string|null $type
 * @property float|null $amount
 * @property string|null $att_type
 * @property string|null $ondate
 * @property int|null $location_id
 * @property string|null $created_date
 * @property string|null $updated_date
 * @property int|null $status
 * @property int|null $plant_id
 * @property int|null $purchase_orderid
 * @property int|null $section_id
 * @property string|null $earnings
 * @property string|null $deduction
 *
 * @property Employee $papl
 */
class DailyEarning extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'daily_earning';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount'], 'number'],
            [['ondate', 'created_date', 'updated_date'], 'safe'],
            [['location_id', 'status', 'plant_id', 'purchase_orderid', 'section_id'], 'integer'],
            [['earnings', 'deduction'], 'string'],
            [['papl_id'], 'string', 'max' => 255],
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
            'amount' => 'Amount',
            'ondate' => 'Ondate',
            'location_id' => 'Location ID',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
            'status' => 'Status',
            'plant_id' => 'Plant ID',
            'purchase_orderid' => 'Purchase Orderid',
            'section_id' => 'Section ID',
            'earnings' => 'Earnings',
            'deduction' => 'Deduction',
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
