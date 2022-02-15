<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "posting_history".
 *
 * @property int $id
 * @property string|null $papl_id
 * @property int|null $state_id
 * @property int|null $location_id
 * @property int|null $plant_id
 * @property int|null $purchase_orderid
 * @property int|null $section_id
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $updated_by
 */
class PostingHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posting_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['state_id', 'location_id', 'plant_id', 'purchase_orderid', 'section_id'], 'integer'],
            [['papl_id'], 'string', 'max' => 15],
            [['start_date', 'end_date', 'updated_by'], 'string', 'max' => 255],
            [['start_date','end_date'],  'datetime', 'format' => 'php:d-m-Y'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'papl_id' => Yii::t('app', 'Papl'),
            'state_id' => Yii::t('app', 'State '),
            'location_id' => Yii::t('app', 'Location '),
            'plant_id' => Yii::t('app', 'Plant '),
            'purchase_orderid' => Yii::t('app', 'Purchase Order'),
            'section_id' => Yii::t('app', 'Section '),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
   public function getEnrolement()
    {
        return $this->hasOne(Enrollment::className(), ['papl_id' => 'papl_id']);
    }
    public function getAttendances()
    {
        return $this->hasMany(Attendance::className(), ['papl_id' => 'papl_id']);
    }
    public function getState()
    {
        return $this->hasOne(State::className(), ['state_id' => 'state_id']);
    }
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['location_id' => 'location_id']);
    }
    public function getPlant()
    {
        return $this->hasOne(Plant::className(), ['plant_id' => 'plant_id']);
    }
    public function getPurchaseorder()
    {
        return $this->hasOne(Purchaseorder::className(), ['po_id' => 'purchase_orderid']);
    }
    public function getSection()
    {
        return $this->hasOne(Section::className(), ['section_id' => 'section_id']);
    }

    public function getSalary()
    {
        return $this->hasMany(SalaryMapping::className(),['papl_id' => 'papl_id']);
    }

    public function getBasic()
    {

        return $this->hasOne(SalaryMapping::className(),['papl_id' => 'papl_id'])->andWhere(['salary_id'=>14]);   
    }

    public function getMonthlySalary($plant_id,$papl_id,$date){
        $msalary = MonthlySalary::find()->where(['papl_id'=>$papl_id,'plant_id'=>$plant_id,'month_year'=>date('F Y', strtotime($date))])->one();
       return $msalary;
   }
  
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['papl_id' => 'papl_id']);
    }
    // public function getExitedemployee(){
    //    return $this->hasOne(Employee::className(), ['papl_id' => 'papl_id'])->andWhere(['is_exit'=>1]); 
    // }
    
    
}
