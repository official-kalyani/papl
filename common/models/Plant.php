<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "plant".
 *
 * @property int $plant_id
 * @property string|null $plant_name
 * @property int|null $location_id
 * @property int|null $state_id
 * @property int|null $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Location $location
 * @property State $state
 * @property Purchaseorder[] $purchaseorders
 * @property Section[] $sections
 */
class Plant extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_delete','is_esi'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['plant_name','location_id','state_id','short_code'], 'required'],
            [['plant_name'], 'match', 'pattern' => '/^[a-zA-Z0-9  _]+$/'],
            // [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'location_id']],
            // [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['state_id' => 'state_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'plant_id' => Yii::t('app', 'Plant ID'),
            'plant_name' => Yii::t('app', 'Plant Name'),
            'location_id' => Yii::t('app', 'Location Name'),
            'state_id' => Yii::t('app', 'State Name'),
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
     * Gets query for [[State]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['state_id' => 'state_id']);
    }

    /**
     * Gets query for [[Purchaseorders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseorders()
    {
        return $this->hasMany(Purchaseorder::className(), ['plant_id' => 'plant_id']);
    }

    /**
     * Gets query for [[Sections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSections()
    {
        return $this->hasMany(Section::className(), ['plant_id' => 'plant_id']);
    }
    public function getEnrollments()
    {
        return $this->hasMany(Enrollment::className(), ['plant_id' => 'plant_id']);
    }
}
