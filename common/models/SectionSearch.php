<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Section;

/**
 * SectionSearch represents the model behind the search form of `common\models\Section`.
 */
class SectionSearch extends Section
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['section_id', 'is_delete'], 'integer'],
            [['section_name', 'created_at', 'updated_at','short_code', 'location_id', 'state_id', 'plant_id', 'po_id'], 'safe'],
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
        $query = Section::find()->joinWith(['state','location','po','plant']);

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
            'section_id' => $this->section_id,
            // 'location_id' => $this->location_id,
            // 'state_id' => $this->state_id,
            // 'plant_id' => $this->plant_id,
            // 'po.po_id' => $this->po_id,
            'section.is_delete' => 0,
            'section.short_code' => $this->short_code,
            'section.created_at' => $this->created_at,
            'section.updated_at' => $this->updated_at,
            
        ]);

        $query->andFilterWhere(['like', 'section_name', $this->section_name])
        ->andFilterWhere(['like','section.short_code',$this->short_code])
        ->andFilterWhere(['like','purchaseOrder.purchase_order_name',$this->po_id])
        ->andFilterWhere(['like','state.state_name',$this->state_id])
        ->andFilterWhere(['like','plant.plant_name',$this->plant_id])
        ->andFilterWhere(['like','location.location_name',$this->location_id])
        ;

        return $dataProvider;
    }
}
