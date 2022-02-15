<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Purchaseorder;

/**
 * PurchaseorderSearch represents the model behind the search form of `common\models\Purchaseorder`.
 */
class PurchaseorderSearch extends Purchaseorder
{
    /**
     * {@inheritdoc}
     */
    
    public function rules()
    {
        return [
            [['po_id',  'is_delete'], 'integer'],
            [['purchase_order_name','po_number', 'created_at', 'updated_at','location_id', 'state_id', 'plant_id','short_code'], 'safe'],
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
        $query = Purchaseorder::find()->joinWith(['location','plant','state']);

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
            'po_id' => $this->po_id,
            // 'location_id' => $this->location_id,
            // 'state_id' => $this->state_id,
            // 'plant_id' => $this->plant_id,
            'purchaseOrder.is_delete' => 0,
            'purchaseOrder.short_code' => $this->short_code,
            'purchaseOrder.created_at' => $this->created_at,
            'purchaseOrder.updated_at' => $this->updated_at,
            
        ]);

        $query->andFilterWhere(['like', 'purchase_order_name', $this->purchase_order_name])
        ->andFilterWhere(['like', 'purchaseOrder.short_code', $this->short_code])
        ->andFilterWhere(['like', 'po_number', $this->po_number])
        ->andFilterWhere(['like', 'plant.plant_name', $this->plant_id])
        ->andFilterWhere(['like', 'location.location_name', $this->location_id])
        ->andFilterWhere(['like', 'state.state_name', $this->state_id]);

        return $dataProvider;
    }
}
