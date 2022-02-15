<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MonthlySalary;

/**
 * MonthlySalarySearch represents the model behind the search form of `common\models\MonthlySalary`.
 */
class MonthlySalarySearch extends MonthlySalary
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'plant_id'], 'integer'],
            [['papl_id', 'designation', 'from_date', 'to_date', 'month_year', 'earning_detail', 'deduction_detail', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
            [['total_basic', 'total_gross', 'misc_att', 'misc_earning', 'other_ot_hour', 'other_ot_earning', 'total_salary', 'total_deduction', 'net_payble', 'lwf_refund', 'esi_refund', 'total_payble'], 'number'],
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
        $query = MonthlySalary::find();

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
            'plant_id' => $this->plant_id,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'total_basic' => $this->total_basic,
            'total_gross' => $this->total_gross,
            'misc_att' => $this->misc_att,
            'misc_earning' => $this->misc_earning,
            'other_ot_hour' => $this->other_ot_hour,
            'other_ot_earning' => $this->other_ot_earning,
            'total_salary' => $this->total_salary,
            'total_deduction' => $this->total_deduction,
            'net_payble' => $this->net_payble,
            'lwf_refund' => $this->lwf_refund,
            'esi_refund' => $this->esi_refund,
            'total_payble' => $this->total_payble,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'papl_id', $this->papl_id])
            ->andFilterWhere(['like', 'designation', $this->designation])
            ->andFilterWhere(['like', 'month_year', $this->month_year])
            ->andFilterWhere(['like', 'earning_detail', $this->earning_detail])
            ->andFilterWhere(['like', 'deduction_detail', $this->deduction_detail])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
