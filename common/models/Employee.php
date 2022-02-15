<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "employee".
 *
 * @property int $id
 * @property string|null $papl_id
 * @property string|null $gate_pass
 * @property string|null $gate_pass_validity
 * @property string|null $workman_sl_no
 * @property int|null $is_exit
 * @property string|null $exit_date
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Enrollment $papl
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_exit'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['papl_id'], 'string', 'max' => 15],
            [['gate_pass'], 'string', 'max' => 20],
            [['gate_pass_validity', 'exit_date'], 'string', 'max' => 11],
            [['workman_sl_no'], 'string', 'max' => 30],
            [['papl_id'], 'exist', 'skipOnError' => true, 'targetClass' => Enrollment::className(), 'targetAttribute' => ['papl_id' => 'papl_id']],
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
            'gate_pass' => Yii::t('app', 'Gate Pass'),
            'gate_pass_validity' => Yii::t('app', 'Gate Pass Validity'),
            'workman_sl_no' => Yii::t('app', 'Workman Serial No'),
            'is_exit' => Yii::t('app', 'Is Exit'),
            'exit_date' => Yii::t('app', 'Exit Date'),
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
        return $this->hasOne(Enrollment::className(), ['papl_id' => 'papl_id']);
    }
    
    public function getPlantname($plant_id){
         $plantname = Plant::find()->where(['plant_id'=>$plant_id])->one();
        return $plantname;
    }
    public function getCurrentposting(){
        return $this->hasOne(PostingHistory::className(), ['papl_id' => 'papl_id'])->where(['end_date' => null]);
    }
    public function getAlldata($plant_id){
         $plantname = PostingHistory::find()->where(['posting_history.plant_id'=>$plant_id])->joinWith('purchaseorders')->joinWith('sections')->one();
         // $plantname = Section::find()->where(['Section.plant_id'=>$plant_id,'Section.is_delete' => 0])->joinWith('po')->one();
        return $plantname;
    }
    public function getPOname($plant_id){
         $plantname = Purchaseorder::find()->where(['plant_id'=>$plant_id,'is_delete' => 0])->one();
        return $plantname;
    }
    public function search($params)
    {
        $query = Employee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'papl_id', $this->papl_id])
            ->andFilterWhere(['like', 'id', $this->id]);

        return $dataProvider;
    }
}
