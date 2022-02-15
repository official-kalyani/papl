<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "state".
 *
 * @property int $state_id
 * @property string|null $state_name
 * @property int|null $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Location[] $locations
 */
class State extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'state';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_delete'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['state_name','short_code'], 'required'],
            [['state_name'],'match', 'pattern' => '/^[a-zA-Z  ]+$/'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'state_id' => Yii::t('app', 'State ID'),
            'state_name' => Yii::t('app', 'State Name'),
            'short_code' => Yii::t('app', 'Short code'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Locations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['state_id' => 'state_id']);
    }
}
