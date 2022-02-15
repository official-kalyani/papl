<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Enrollment;
use common\helpers\acl;
use yii\helpers\ArrayHelper;

/**
 * EnrollmentSearch represents the model behind the search form of `common\models\Enrollment`.
 */
class EnrollmentSearch extends Enrollment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'permanent_pincode', 'present_pincode', 'present_mobile_number', 'status', 'is_delete'], 'integer'],
            [['enrolement_id', 'adhar_name', 'father_husband_name', 'relation_with_employee', 'browse_adhar', 'browse_pp_photo', 'gender', 'marital_status', 'mobile_with_adhar', 'dob', 'permanent_addrs', 'permanent_state', 'permanent_district', 'permanent_ps', 'permanent_po', 'permanent_village', 'permanent_block', 'permanent_tehsil', 'permanent_GP', 'permanent_mobile_number', 'present_address', 'present_state', 'present_district', 'present_ps', 'present_po', 'present_village', 'present_block', 'present_tehsil', 'present_gp', 'blood_group', 'ID_mark_employee', 'nationality', 'religion', 'caste', 'referenced_remark', 'comment', 'created_at', 'updated_at'], 'safe'],
            [['adhar_number'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        
        $query = Enrollment::find();
        
        if(Yii::$app->request->get('type')){
            $type=Yii::$app->request->get('type');
        }else{
            $type=0;
        }
        if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
            $query=$query->andWhere(['status'=>$type]); 
        }else{
            $user_posting = PostingHistory::find()->where(['papl_id'=>Yii::$app->user->identity->username])
                                                  ->andWhere(['end_date' => null])->orderBy('id DESC')->one();
            $plants=ArrayHelper::map(Plant::find()->where(['is_delete' => 0,'location_id'=>$user_posting->location_id])->all(), 'plant_id', 'plant_name');
            $query=$query->andWhere(['status'=>$type,'plant_id' => array_keys($plants)]);
        }
        // add conditions that should always apply here

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
            'adhar_number' => $this->adhar_number,
            'dob' => $this->dob,
            'permanent_pincode' => $this->permanent_pincode,
            'present_pincode' => $this->present_pincode,
            'present_mobile_number' => $this->present_mobile_number,
            'status' => $this->status,
            'is_delete' => $this->is_delete,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'enrolement_id', $this->enrolement_id])
            ->andFilterWhere(['like', 'adhar_name', $this->adhar_name])
            ->andFilterWhere(['like', 'father_husband_name', $this->father_husband_name])
            ->andFilterWhere(['like', 'relation_with_employee', $this->relation_with_employee])
            ->andFilterWhere(['like', 'browse_adhar', $this->browse_adhar])
            ->andFilterWhere(['like', 'browse_pp_photo', $this->browse_pp_photo])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'marital_status', $this->marital_status])
            ->andFilterWhere(['like', 'mobile_with_adhar', $this->mobile_with_adhar])
            ->andFilterWhere(['like', 'permanent_addrs', $this->permanent_addrs])
            ->andFilterWhere(['like', 'permanent_state', $this->permanent_state])
            ->andFilterWhere(['like', 'permanent_district', $this->permanent_district])
            ->andFilterWhere(['like', 'permanent_ps', $this->permanent_ps])
            ->andFilterWhere(['like', 'permanent_po', $this->permanent_po])
            ->andFilterWhere(['like', 'permanent_village', $this->permanent_village])
            ->andFilterWhere(['like', 'permanent_block', $this->permanent_block])
            ->andFilterWhere(['like', 'permanent_tehsil', $this->permanent_tehsil])
            ->andFilterWhere(['like', 'permanent_GP', $this->permanent_GP])
            ->andFilterWhere(['like', 'permanent_mobile_number', $this->permanent_mobile_number])
            ->andFilterWhere(['like', 'present_address', $this->present_address])
            ->andFilterWhere(['like', 'present_state', $this->present_state])
            ->andFilterWhere(['like', 'present_district', $this->present_district])
            ->andFilterWhere(['like', 'present_ps', $this->present_ps])
            ->andFilterWhere(['like', 'present_po', $this->present_po])
            ->andFilterWhere(['like', 'present_village', $this->present_village])
            ->andFilterWhere(['like', 'present_block', $this->present_block])
            ->andFilterWhere(['like', 'present_tehsil', $this->present_tehsil])
            ->andFilterWhere(['like', 'present_gp', $this->present_gp])
            ->andFilterWhere(['like', 'blood_group', $this->blood_group])
            ->andFilterWhere(['like', 'ID_mark_employee', $this->ID_mark_employee])
            ->andFilterWhere(['like', 'nationality', $this->nationality])
            ->andFilterWhere(['like', 'religion', $this->religion])
            ->andFilterWhere(['like', 'caste', $this->caste])
            ->andFilterWhere(['like', 'referenced_remark', $this->referenced_remark])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
