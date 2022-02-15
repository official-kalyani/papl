<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "nominee".
 *
 * @property int $id
 * @property string|null $enrolement_id
 * @property string|null $nominee_name
 * @property string|null $nominee_relation
 * @property string|null $nominee_dob
 * @property string|null $nominee_adhar_number
 * @property string|null $nominee_adhar_photo
 * @property string|null $nominee_percentage
 * @property string|null $nominee_address
 * @property int|null $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Enrollment $enrolement
 */
class Nominee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nominee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_delete'], 'integer'],
            [['nominee_adhar_number'], 'match', 'pattern' => '/^[0-9]{12}$/','message'=>'Adhar number must be 12 digits.'],
            [['created_at', 'updated_at'], 'safe'],
            [['enrolement_id'], 'string', 'max' => 25],
            [['nominee_name', 'nominee_relation', 'nominee_dob', 'nominee_adhar_number', 'nominee_adhar_photo', 'nominee_percentage', 'nominee_address'], 'string', 'max' => 255],
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
            'nominee_name' => Yii::t('app', 'Nominee Name'),
            'nominee_relation' => Yii::t('app', 'Nominee Relation'),
            'nominee_dob' => Yii::t('app', 'Nominee Dob'),
            'nominee_adhar_number' => Yii::t('app', 'Nominee Aadhaar Number'),
            'nominee_adhar_photo' => Yii::t('app', 'Nominee Aadhaar Photo'),
            'nominee_percentage' => Yii::t('app', 'Nominee Percentage'),
            'nominee_address' => Yii::t('app', 'Nominee Address'),
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
    public function imageupload($filename,$name){
        $extension=explode('.', $filename);
        $ext=end($extension);
        if ($name == 'nominee_adhar_photo') {
            $name = 'nominee_adhar_photo';
        }
        if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'pdf' || $ext == 'PNG' || $ext == 'JPG' || $ext == 'JPEG' || $ext == 'PDF') {
            $basepath=Yii::getAlias('@storage');
        $randnum=Yii::$app->security->generateRandomString();
        $file='/upload/'.$name.$randnum.".{$ext}";
        $path=$basepath.$file;
        $filename->saveAs($path);
        return $file;
        }
        
    }
}
