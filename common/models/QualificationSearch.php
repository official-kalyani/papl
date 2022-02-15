<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Qualification;

/**
 * QualificationSearch represents the model behind the search form of `common\models\Qualification`.
 */
class QualificationSearch extends Qualification
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'qualification_id', 'year_of_passout', 'is_delete'], 'integer'],
            [['enrolement_id', 'highest_qualification', 'university_name', 'college_name', 'division_percent', 'created_at', 'updated_at'], 'safe'],
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
        $query = Qualification::find();

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
            'qualification_id' => $this->qualification_id,
            'year_of_passout' => $this->year_of_passout,
            'is_delete' => $this->is_delete,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'enrolement_id', $this->enrolement_id])
            ->andFilterWhere(['like', 'highest_qualification', $this->highest_qualification])
            ->andFilterWhere(['like', 'university_name', $this->university_name])
            ->andFilterWhere(['like', 'college_name', $this->college_name])
            ->andFilterWhere(['like', 'division_percent', $this->division_percent]);

        return $dataProvider;
    }
}
