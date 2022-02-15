<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "salary".
 *
 * @property int $id
 * @property string|null $attribute_name
 * @property string|null $type
 */
class Salary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $state;
    public $location;
    public $plant_id;
    public $purchase_orderid;
    public $section_id;
    public static function tableName()
    {
        return 'salary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_name', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'attribute_name' => Yii::t('app', 'Attribute Name'),
            'type' => Yii::t('app', 'Type'),
            'plant_id' => Yii::t('app', 'Plant'),
            'purchase_orderid' => Yii::t('app', 'Purchase Order'),
            'section_id' => Yii::t('app', 'Section'),
        ];
    }
    public function salary_data($sal_attr_id,$emp_id){
        $salary_data = SalaryMapping::find()->where(['papl_id'=>$emp_id,'salary_id' => $sal_attr_id])->asArray()->one();
        return $salary_data;
    }
    
}
