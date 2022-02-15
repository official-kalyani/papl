<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deduction".
 *
 * @property int $id
 * @property string|null $attribute_name
 * @property string|null $type
 */
class Deduction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deduction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_name', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'attribute_name' => Yii::t('app', 'Attribute Name'),
            'type' => Yii::t('app', 'Type'),
        ];
    }
}
