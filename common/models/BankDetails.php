<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bank_details".
 *
 * @property int $id
 * @property int|null $detail_id
 * @property string|null $enrolement_id
 * @property string|null $transaction_id
 * @property string|null $IFSC
 * @property string|null $name_passbook
 * @property string|null $name_bank
 * @property string|null $bank_account_number
 * @property string|null $name_branch
 * @property string|null $pass_book_photo
 * @property int|null $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Enrollment $enrolement
 */
class BankDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['detail_id', 'is_delete'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['enrolement_id'], 'string', 'max' => 25],
            [['transaction_id', 'IFSC', 'name_passbook', 'name_bank', 'bank_account_number', 'name_branch', 'pass_book_photo'], 'string', 'max' => 255],
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
            'detail_id' => Yii::t('app', 'Detail ID'),
            'enrolement_id' => Yii::t('app', 'Enrolement ID'),
            'transaction_id' => Yii::t('app', 'Receipt by Employee/Bank Transaction ID'),
            'IFSC' => Yii::t('app', 'IFSC'),
            'name_passbook' => Yii::t('app', 'Name as per Bank Pass Book'),
            'name_bank' => Yii::t('app', 'Name of Bank'),
            'bank_account_number' => Yii::t('app', 'Bank Account Number'),
            'name_branch' => Yii::t('app', 'Name of Branch with (Distrct and State)'),
            'pass_book_photo' => Yii::t('app', 'Bank Pass Book/Cheque'),
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
        if ($name == 'pass_book_photo') {
            $name= 'pass_book_photo';
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
    public function imageupload($filename){
        $extension=explode('.', $filename);
        $ext=end($extension);
        if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png'|| $ext == 'pdf') {
            $basepath=Yii::getAlias('@storage');
        $randnum=Yii::$app->security->generateRandomString();
        $file='/upload/'.$randnum.".{$ext}";
        $path=$basepath.$file;
        $filename->saveAs($path);
        return $file;
        }
        
    }
}
