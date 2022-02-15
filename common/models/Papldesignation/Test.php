<?php

namespace common\models\Papldesignation;

use Yii;

/**
 * This is the model class for table "test".
 *
 * @property int $id
 * @property string $designation
 * @property int $status
 */
class Test extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'designation', 'status'], 'required'],
            [['id', 'status'], 'integer'],
            [['designation'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'designation' => 'Designation',
            'status' => 'Status',
        ];
    }
}
