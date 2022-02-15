<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;
/**
 * This is the model class for table "qualification".
 *
 * @property int $id
 * @property int|null $qualification_id
 * @property string|null $enrolement_id
 * @property string|null $highest_qualification
 * @property string|null $university_name
 * @property string|null $college_name
 * @property int|null $year_of_passout
 * @property string|null $division_percent
 * @property int|null $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Enrollment $enrolement
 */
class Qualification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'qualification';
    }

    public $qualification_document_old;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'year_of_passout', 'is_delete'], 'number'],
            [['year_of_passout'],'match','pattern'=>'/^[0-9]{4}$/'],
            [['created_at', 'updated_at'], 'safe'],
            [['enrolement_id'], 'string', 'max' => 25],
            // [['qualification_document'],  'file','skipOnEmpty'=>true ],
            [['highest_qualification', 'university_name', 'college_name', 'division_percent'], 'string', 'max' => 255],
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
            'qualification_id' => Yii::t('app', 'Qualification ID'),
            'enrolement_id' => Yii::t('app', 'Enrolement ID'),
            'highest_qualification' => Yii::t('app', 'Qualification'),
            'university_name' => Yii::t('app', 'University/Board'),
            'college_name' => Yii::t('app', 'College/Institute Name'),
            'year_of_passout' => Yii::t('app', 'Year Of Passout'),
            'division_percent' => Yii::t('app', 'Division/Percent'),
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
    public function upload_qualification()
    {

        
            foreach ($this->qualification_document as $file) {
                $extension = explode('.', $file);
                $ext = end($extension);
                $basepath = Yii::getAlias('@storage');
                $randnum = Yii::$app->security->generateRandomString();
                $filepath = '/upload/' . $randnum .'.'. ".{$ext}";
                $path = $basepath . $filepath;
                $file->saveAs($path);
            }
           
    }
}
