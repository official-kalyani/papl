<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Plant;

/**
 * PlantSearch represents the model behind the search form of `common\models\Plant`.
 */
class PlantSearch extends Plant
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plant_id',  'is_delete'], 'integer'],
            [['plant_name', 'short_code','created_at', 'updated_at','location_id', 'state_id'], 'safe'],
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
        $query = Plant::find()->joinWith(['state','location']);

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
            'plant_id' => $this->plant_id,
            'plant.short_code' => $this->short_code,
            // 'location_id' => $this->location_id,
            // 'state_id' => $this->state_id,
            'plant.is_delete' => 0,
            'plant.created_at' => $this->created_at,
            'plant.updated_at' => $this->updated_at,
            
        ]);

        $query->andFilterWhere(['like', 'plant_name', $this->plant_name])
        ->andFilterWhere(['like', 'state.state_name', $this->state_id])
        ->andFilterWhere(['like', 'plant.short_code', $this->short_code])
        ->andFilterWhere(['like', 'location.location_name', $this->location_id]);

        return $dataProvider;
    }
}
