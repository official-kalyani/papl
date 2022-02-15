<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "family".
 *
 * @property int $id
 * @property string|null $enrolement_id
 * @property string|null $family_member
 * @property string $family_member_dob
 * @property string|null $family_member_relation
 * @property string|null $family_member_residence
 * @property string|null $family_nominee_adhar_photo
 * @property int|null $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Enrollment $enrolement
 */
class Family extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'family';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_delete'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['enrolement_id', 'family_member_dob'], 'string', 'max' => 25],
            [['family_member', 'family_member_relation', 'family_member_residence', 'family_nominee_adhar_photo'], 'string', 'max' => 255],
            [['enrolement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Enrollment::className(), 'targetAttribute' => ['enrolement_id' => 'enrolement_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'enrolement_id' => Yii::t('app', 'Enrolement ID'),
            'family_member' => Yii::t('app', 'Family Member'),
            'family_member_dob' => Yii::t('app', 'Family Member Dob'),
            'family_member_relation' => Yii::t('app', 'Family Member Relation'),
            'family_member_residence' => Yii::t('app', 'Family Member Residence'),
            'family_nominee_adhar_photo' => Yii::t('app', 'Family Nominee Aadhaar Photo'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Enrolement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEnrolement()
    {
        return $this->hasOne(Enrollment::className(), ['enrolement_id' => 'enrolement_id']);
    }
     public function fileupload($filename){
        $extension=explode('.', $filename);
        $ext=end($extension);
        if ($ext == 'pdf' || $ext == 'jpg' || $ext == 'jpeg' ) {
            $basepath=Yii::getAlias('@storage');
        $randnum=Yii::$app->security->generateRandomString();
        $file='/upload/'.$randnum.".{$ext}";
        $path=$basepath.$file;
        $filename->saveAs($path);
        return $file;
        }
        
    }
    public function imageupload($filename){
        $extension=explode('.', $filename);
        $ext=end($extension);
        if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png') {
            $basepath=Yii::getAlias('@storage');
        $randnum=Yii::$app->security->generateRandomString();
        $file='/upload/'.$randnum.".{$ext}";
        $path=$basepath.$file;
        $filename->saveAs($path);
        return $file;
        }
        
    }
}
