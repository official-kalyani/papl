<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property int $location_id
 * @property string|null $location_name
 * @property int|null $state_id
 * @property int|null $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property State $state
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['state_id','is_delete'], 'integer'],
            [['state_id'], 'integer'],
            [['state_id','location_name','short_code'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['location_name'], 'match', 'pattern' => '/^[a-zA-Z0-9 _]+$/'],
            // [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['state_id' => 'state_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'location_id' => Yii::t('app', 'Location ID'),
            'location_name' => Yii::t('app', 'Location Name'),
            'state_id' => Yii::t('app', 'State Name'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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
