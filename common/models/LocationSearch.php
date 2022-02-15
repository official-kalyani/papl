<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Location;

/**
 * LocationSearch represents the model behind the search form of `common\models\Location`.
 */
class LocationSearch extends Location
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_id',  'is_delete'], 'integer'],
            [['location_name', 'created_at', 'updated_at', 'state_id','short_code'], 'safe'],
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
        $query = Location::find()->joinWith(['state']);

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
            'location_id' => $this->location_id,
            'location.short_code' => $this->short_code,
            // 'state_id' => $this->state_id,
            'location.is_delete' => 0,
            'location.created_at' => $this->created_at,
            'location.updated_at' => $this->updated_at,
            
        ]);

        $query->andFilterWhere(['like', 'location_name', $this->location_name])->andFilterWhere(['like', 'state.state_name', $this->state_id])->andFilterWhere(['like', 'location.short_code', $this->short_code]);

        return $dataProvider;
    }
}
