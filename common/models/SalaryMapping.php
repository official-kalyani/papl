<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "salary_mapping".
 *
 * @property int $id
 * @property string|null $papl_id
 * @property int|null $salary_id
 * @property string|null $status
 * @property int|null $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Employee $papl
 * @property Salary $salary
 */
class SalaryMapping extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'salary_mapping';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['salary_id', 'is_delete'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['papl_id'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 10],
            [['papl_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['papl_id' => 'papl_id']],
            [['salary_id'], 'exist', 'skipOnError' => true, 'targetClass' => Salary::className(), 'targetAttribute' => ['salary_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'papl_id' => Yii::t('app', 'Papl ID'),
            'salary_id' => Yii::t('app', 'Salary ID'),
            'status' => Yii::t('app', 'Status'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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

    /**
     * Gets query for [[Salary]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSalary()
    {
        return $this->hasOne(Salary::className(), ['id' => 'salary_id']);
    }
    public function salary_data($sal_attr_id,$emp_id){
        $salary_data = SalaryMapping::find()->where(['papl_id'=>$emp_id,'salary_id' => $sal_attr_id])->asArray()->one();
        return $salary_data;
    }
    
}
