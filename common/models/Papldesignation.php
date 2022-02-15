<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "papldesignation".
 *
 * @property int $id
 * @property string|null $PAPLdesignation
 * @property int|null $status
 */
class Papldesignation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'PAPLdesignation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['PAPLdesignation'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'PAPLdesignation' => 'Papl designation',
            'status' => 'Status',
        ];
    }
}
