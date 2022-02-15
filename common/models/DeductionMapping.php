<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deduction_mapping".
 *
 * @property int $id
 * @property string|null $papl_id
 * @property int|null $deduction_id
 * @property float|null $amount
 * @property string|null $status
 * @property string|null $type
 * @property int|null $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Employee $papl
 * @property Salary $deduction
 */
class DeductionMapping extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deduction_mapping';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deduction_id', 'is_delete'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['papl_id'], 'string', 'max' => 255],
            [['status', 'type'], 'string', 'max' => 10],
            [['papl_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['papl_id' => 'papl_id']],
            [['deduction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Deduction::className(), 'targetAttribute' => ['deduction_id' => 'id']],
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
            'deduction_id' => Yii::t('app', 'Deduction ID'),
            'amount' => Yii::t('app', 'Amount'),
            'status' => Yii::t('app', 'Status'),
            'type' => Yii::t('app', 'Type'),
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
     * Gets query for [[Deduction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeduction()
    {
        return $this->hasOne(Deduction::className(), ['id' => 'deduction_id']);
    }
    public function deduction_data($ded_attr_id,$emp_id){
        $salary_data = DeductionMapping::find()->where(['papl_id'=>$emp_id,'deduction_id' => $ded_attr_id])->asArray()->one();
        return $salary_data;
    }
}
