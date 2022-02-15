<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Attendance;

/**
 * AttendanceSearch represents the model behind the search form of `common\models\Attendance`.
 */
class AttendanceSearch extends Attendance
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['papl_id', 'employee_name', 'att', 'att_type', 'nh', 'fh', 'nhfh_type', 'ot', 'ot_type', 'remark'], 'safe'],
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
        $query = Attendance::find();

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
        ]);

        $query->andFilterWhere(['like', 'papl_id', $this->papl_id])
            ->andFilterWhere(['like', 'employee_name', $this->employee_name])
            ->andFilterWhere(['like', 'att', $this->att])
            ->andFilterWhere(['like', 'att_type', $this->att_type])
            ->andFilterWhere(['like', 'nh', $this->nh])
            ->andFilterWhere(['like', 'fh', $this->fh])
            ->andFilterWhere(['like', 'nhfh_type', $this->nhfh_type])
            ->andFilterWhere(['like', 'ot', $this->ot])
            ->andFilterWhere(['like', 'ot_type', $this->ot_type])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
