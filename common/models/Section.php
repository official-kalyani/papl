<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "section".
 *
 * @property int $section_id
 * @property string|null $section_name
 * @property int|null $location_id
 * @property int|null $state_id
 * @property int|null $plant_id
 * @property int|null $po_id
 * @property int|null $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Location $location
 * @property Plant $plant
 * @property Purchaseorder $po
 * @property State $state
 */
class Section extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_delete'], 'integer'],
            [['location_id', 'state_id', 'plant_id', 'po_id'], 'integer'],
            [['location_id', 'state_id', 'plant_id', 'po_id', 'section_name','short_code'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['section_name'],  'match', 'pattern' => '/^[a-zA-Z0-9()_\\- ]*$/'],
            // [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'location_id']],
            // [['plant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Plant::className(), 'targetAttribute' => ['plant_id' => 'plant_id']],
            // [['po_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purchaseorder::className(), 'targetAttribute' => ['po_id' => 'po_id']],
            // [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['state_id' => 'state_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'section_id' => Yii::t('app', 'Section ID'),
            'section_name' => Yii::t('app', 'Section Name'),
            'location_id' => Yii::t('app', 'Location Name'),
            'state_id' => Yii::t('app', 'State Name'),
            'plant_id' => Yii::t('app', 'Plant Name'),
            'po_id' => Yii::t('app', 'Purchaseorder'),
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
     * Gets query for [[Po]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPo()
    {
        return $this->hasOne(Purchaseorder::className(), ['po_id' => 'po_id']);
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
}
