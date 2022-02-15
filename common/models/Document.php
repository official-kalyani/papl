<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "document".
 *
 * @property int $id
 * @property string|null $enrolement_id
 * @property string|null $voter_id_number
 * @property string|null $voter_copy_photo
 * @property string|null $dl_number
 * @property string|null $dl_expiry_date
 * @property string|null $drivinglicense_photo
 * @property string|null $pannumber
 * @property string|null $pan_photo
 * @property string|null $passportnumber
 * @property string|null $passport_expirydate
 * @property string|null $passport_photo
 * @property int|null $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Enrollment $enrolement
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_delete'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['enrolement_id'], 'string', 'max' => 25],
            //[['pannumber'], 'match', 'pattern' => '/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i','message' => 'Please provide proper pan number'],
            //[['passportnumber'], 'match', 'pattern' => '/^[A-PR-WY][1-9]\d\s?\d{4}[1-9]$/i','message' => 'Please provide proper passport number'],
            [['voter_id_number', 'voter_copy_photo', 'dl_number', 'dl_expiry_date', 'drivinglicense_photo', 'pannumber', 'pan_photo', 'passportnumber', 'passport_expirydate', 'passport_photo'], 'string', 'max' => 255],
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
            'voter_id_number' => Yii::t('app', 'Voter Id Number'),
            'voter_copy_photo' => Yii::t('app', 'Voter Copy'),
            'dl_number' => Yii::t('app', 'Drivinglicense Number'),
            'dl_expiry_date' => Yii::t('app', 'Drivinglicense Expiry Date'),
            'drivinglicense_photo' => Yii::t('app', 'Drivinglicense Photo'),
            'pannumber' => Yii::t('app', 'Pannumber'),
            'pan_photo' => Yii::t('app', 'Pan Photo'),
            'passportnumber' => Yii::t('app', 'Passportnumber'),
            'passport_expirydate' => Yii::t('app', 'Passport Expirydate'),
            'passport_photo' => Yii::t('app', 'Passport Photo'),
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
    public function fileupload($filename,$name){
        $extension=explode('.', $filename);
        $ext=end($extension);
        if ($name == 'voter_copy_photo') {
            $name = 'voter_copy_photo';
        }
        if ($name == 'drivinglicense_photo') {
            $name = 'drivinglicense_photo';
        }
        if ($name == 'pan_photo') {
            $name = 'pan_photo';
        }
        if ($name == 'passport_photo') {
            $name = 'passport_photo';
        }
        if ($ext == 'pdf' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
            $basepath=Yii::getAlias('@storage');
        $randnum=Yii::$app->security->generateRandomString();
        $file='/upload/'.$name.".{$ext}";
        $path=$basepath.$file;
        $filename->saveAs($path);
        return $file;
        }
        
    }
    public function imageupload($filename){
        $extension=explode('.', $filename);
        $ext=end($extension);
        if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' ||  $ext == 'pdf') {
            $basepath=Yii::getAlias('@storage');
        $randnum=Yii::$app->security->generateRandomString();
        $file='/upload/'.$randnum.".{$ext}";
        $path=$basepath.$file;
        $filename->saveAs($path);
        return $file;
        }
        
    }
}
