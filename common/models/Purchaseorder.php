<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "purchaseorder".
 *
 * @property int $po_id
 * @property string|null $purchase_order_name
 * @property int|null $location_id
 * @property int|null $state_id
 * @property int|null $plant_id
 * @property int|null $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Location $location
 * @property Plant $plant
 * @property State $state
 * @property Section[] $sections
 */
class Purchaseorder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchaseOrder';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_id', 'state_id', 'plant_id', 'is_delete'], 'integer'],
           
            [['location_id', 'state_id', 'plant_id','purchase_order_name','po_number','short_code'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['purchase_order_name'], 'match', 'pattern' => '/^[a-zA-Z0-9()_\\- \& ]*$/'],
            // [['po_number'], 'match', 'pattern' => '/^[a-zA-Z0-9()_\\- ]*$/'],
            // [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'location_id']],
            // [['plant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Plant::className(), 'targetAttribute' => ['plant_id' => 'plant_id']],
            // [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['state_id' => 'state_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'po_id' => Yii::t('app', 'Po ID'),
            'purchase_order_name' => Yii::t('app', 'Purchase Order Name'),
            'po_number' => Yii::t('app', 'Purchase Order Number'),
            'location_id' => Yii::t('app', 'Location Name'),
            'state_id' => Yii::t('app', 'State Name'),
            'plant_id' => Yii::t('app', 'Plant Name'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Location]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['location_id' => 'location_id']);
    }

    /**
     * Gets query for [[Plant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlant()
    {
        return $this->hasOne(Plant::className(), ['plant_id' => 'plant_id']);
    }

    /**
     * Gets query for [[State]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['state_id' => 'state_id']);
    }

    /**
     * Gets query for [[Sections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSections()
    {
        return $this->hasMany(Section::className(), ['po_id' => 'po_id']);
    }
}
