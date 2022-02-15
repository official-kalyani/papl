<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "acl".
 *
 * @property int $id
 * @property string|null $lvl1
 * @property string|null $lvl2
 * @property string|null $lvl3
 * @property string|null $lvl4
 * @property string|null $url
 * @property int $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property AclMapping[] $aclMappings
 */
class Acl extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acl';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_delete'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['lvl1', 'lvl2', 'lvl3', 'lvl4'], 'string', 'max' => 40],
            [['url'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lvl1' => 'Lvl1',
            'lvl2' => 'Lvl2',
            'lvl3' => 'Lvl3',
            'lvl4' => 'Lvl4',
            'url' => 'Url',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[AclMappings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAclMappings()
    {
        return $this->hasMany(AclMapping::className(), ['acl_id' => 'id']);
    }
}
