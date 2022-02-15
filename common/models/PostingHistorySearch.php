<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PostingHistory;

/**
 * PostingHistorySearch represents the model behind the search form of `common\models\PostingHistory`.
 */
class PostingHistorySearch extends PostingHistory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'state_id', 'location_id', 'plant_id', 'purchase_orderid', 'section_id'], 'integer'],
            [['start_date', 'end_date', 'updated_by'], 'safe'],
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
        $query = PostingHistory::find();

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
            'state_id' => $this->state_id,
            'location_id' => $this->location_id,
            'plant_id' => $this->plant_id,
            'purchase_orderid' => $this->purchase_orderid,
            'section_id' => $this->section_id,
        ]);

        $query->andFilterWhere(['like', 'start_date', $this->start_date])
            ->andFilterWhere(['like', 'end_date', $this->end_date])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
