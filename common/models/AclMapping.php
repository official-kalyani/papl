<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "acl_mapping".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $acl_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Acl $acl
 * @property User $user
 */
class AclMapping extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acl_mapping';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['user_id', 'acl_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['acl_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acl::className(), 'targetAttribute' => ['acl_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User Name',
            'acl_id' => 'Acl ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Acl]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcl()
    {
        return $this->hasOne(Acl::className(), ['id' => 'acl_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
