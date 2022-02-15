<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "enrollment".
 *
 * @property int $id
 * @property string|null $enrolement_id
 * @property string|null $adhar_name
 * @property string|null $father_husband_name
 * @property string|null $relation_with_employee
 * @property float|null $adhar_number
 * @property string|null $browse_adhar
 * @property string|null $browse_pp_photo
 * @property string|null $gender
 * @property string|null $marital_status
 * @property string|null $mobile_with_adhar
 * @property string|null $dob
 * @property string|null $permanent_addrs
 * @property string|null $permanent_state
 * @property string|null $permanent_district
 * @property string|null $permanent_ps
 * @property string|null $permanent_po
 * @property string|null $permanent_village
 * @property string|null $permanent_block
 * @property string|null $permanent_tehsil
 * @property string|null $permanent_GP
 * @property int|null $permanent_pincode
 * @property string|null $permanent_mobile_number
 * @property string|null $present_address
 * @property string|null $present_state
 * @property string|null $present_district
 * @property string|null $present_ps
 * @property string|null $present_po
 * @property string|null $present_village
 * @property string|null $present_block
 * @property string|null $present_tehsil
 * @property string|null $present_gp
 * @property int|null $present_pincode
 * @property int|null $present_mobile_number
 * @property string|null $blood_group
 * @property string|null $ID_mark_employee
 * @property string|null $nationality
 * @property string|null $religion
 * @property string|null $caste
 * @property string|null $referenced_remark
 * @property int|null $status 0=>no action, 1=> transfer_to plant,2=>approved_by_plant_mgr,3=>epf_esi created,4=>rejected,5=>parmanent
 * @property string|null $comment
 * @property int|null $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $designation
 * @property string|null $category
 * @property string|null $PAPLdesignation
 * @property string|null $experience
 *
 * @property Qualification[] $qualifications
 * @property Bank[] $banks
 */
class Enrollment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $import_file;
    public $state,$location,$purchase_orderid,$section_id;
    
    public static function tableName()
    {
        return 'enrollment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adhar_number'], 'match', 'pattern' => '/^[0-9]{12}$/','message'=>'Adhar number must be 12 digits.'],
            [['adhar_number'], 'number','message' => 'Adhar number must be number.'],
            [['blood_group'], 'match', 'pattern' => '/^(A|B|AB|O)[+-]$/i'],
            
            
            [['blood_group','health_insurance_no'], 'string'],
            [['present_pincode', 'permanent_pincode'], 'match', 'pattern' => '/^[0-9]{6}$/'],
            [['adhar_name',  'ID_mark_employee', 'esic_code', 'esic_ip_number', 'wca_gpa', 'pf_code', 'uan', 'pf_account_number', 'uan_sheet', 'esic_sheet'], 'string'],
            [[ 'esic_code', 'esic_ip_number', 'wca_gpa', 'pf_code', 'uan', 'pf_account_number'], 'match', 'pattern' => '/^[a-zA-Z0-9]*$/','on'=>['epf_esic']],
            //[[ 'esic_code', 'esic_ip_number', 'wca_gpa', 'pf_code', 'uan', 'pf_account_number'], 'required', 'on' => ['epf_esic']],
            [['adhar_name', 'father_husband_name', 'relation_with_employee'], 'match', 'pattern' => '/^[a-zA-Z .]+$/', 'message' => 'Can only contain alphabets.'],
            [['present_mobile_number', 'mobile_with_adhar', 'permanent_mobile_number'], 'number','message' => 'Mobile number must be number.'],

            [['present_mobile_number', 'mobile_with_adhar', 'permanent_mobile_number'], 'match', 'pattern' => '/^[0-9]{10}$/', 'message' => 'Mobile number must be 10 digits.'],
            [['created_at', 'updated_at'], 'safe'],
            // [['dob','wca_gpa_expire_date'], 'string'],
            [['dob', 'wca_gpa_expire_date'],  'date', 'format' => 'php:d-m-Y','on'=>['epf_esic']],
            
            [['plant_id', 'permanent_pincode', 'present_pincode', 'present_mobile_number', 'status', 'is_delete'], 'integer'],
            [['plant_id', 'adhar_number', 'adhar_name', 'father_husband_name', 'relation_with_employee', 'gender', 'mobile_with_adhar', 'permanent_addrs', 'permanent_mobile_number', 'permanent_pincode'], 'required'],
            [['enrolement_id'], 'string', 'max' => 25],
            [['father_husband_name'], 'string', 'max' => 200],
            [['relation_with_employee'], 'string', 'max' => 150],
            [['browse_adhar', 'browse_pp_photo', 'gender', 'marital_status', 'mobile_with_adhar', 'permanent_addrs', 'permanent_state', 'permanent_district', 'permanent_ps', 'permanent_po', 'permanent_village', 'permanent_block', 'permanent_tehsil', 'permanent_GP', 'permanent_mobile_number', 'present_address', 'present_state', 'present_district', 'present_ps', 'present_po', 'present_village', 'present_block', 'present_tehsil', 'present_gp', 'blood_group', 'ID_mark_employee', 'nationality', 'religion', 'referenced_remark', 'comment', 'designation', 'category', 'PAPLdesignation', 'experience'], 'string', 'max' => 255],
            [['caste'], 'string', 'max' => 25],
            [['enrolement_id', 'adhar_number'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'enrolement_id' => Yii::t('app', 'Enrollment ID'),
            'adhar_name' => Yii::t('app', 'Name As per Aadhaar'),
            'father_husband_name' => Yii::t('app', 'Father or Husband Name'),
            'relation_with_employee' => Yii::t('app', 'Relation With Employee'),
            'adhar_number' => Yii::t('app', 'Aadhaar Number'),
            'browse_adhar' => Yii::t('app', 'Aadhaar'),
            'browse_pp_photo' => Yii::t('app', 'Passport Size Photo'),
            'gender' => Yii::t('app', 'Gender'),
            'marital_status' => Yii::t('app', 'Marital Status'),
            'mobile_with_adhar' => Yii::t('app', 'Mobile Number with Aadhaar'),
            'dob' => Yii::t('app', 'Date Of Birth'),
            'permanent_addrs' => Yii::t('app', 'Permanent Address'),
            'permanent_state' => Yii::t('app', 'State'),
            'permanent_district' => Yii::t('app', 'Permanent District'),
            'permanent_ps' => Yii::t('app', 'PS'),
            'permanent_po' => Yii::t('app', 'PO'),
            'permanent_village' => Yii::t('app', 'Village'),
            'permanent_block' => Yii::t('app', 'Block'),
            'permanent_tehsil' => Yii::t('app', 'Tehsil'),
            'permanent_GP' => Yii::t('app', 'GP'),
            'permanent_pincode' => Yii::t('app', 'Pincode'),
            'permanent_mobile_number' => Yii::t('app', 'Mobile Number'),
            'present_address' => Yii::t('app', 'Address'),
            'present_state' => Yii::t('app', 'State'),
            'present_district' => Yii::t('app', 'District'),
            'present_ps' => Yii::t('app', 'PS'),
            'present_po' => Yii::t('app', 'PO'),
            'present_village' => Yii::t('app', 'Village'),
            'present_block' => Yii::t('app', 'Block'),
            'present_tehsil' => Yii::t('app', 'Tehsil'),
            'present_gp' => Yii::t('app', 'GP'),
            'present_pincode' => Yii::t('app', 'Pincode'),
            'present_mobile_number' => Yii::t('app', 'Mobile Number'),
            'blood_group' => Yii::t('app', 'Blood Group'),
            'ID_mark_employee' => Yii::t('app', 'ID Mark of Employee'),
            'nationality' => Yii::t('app', 'Nationality'),
            'religion' => Yii::t('app', 'Religion'),
            'caste' => Yii::t('app', 'Caste'),
            'health_insurance_no' => Yii::t('app', 'Health Insurance Number'),
            'designation' => Yii::t('app', 'Designation'),
            'category' => Yii::t('app', 'Category'),
            'PAPLdesignation' => Yii::t('app', 'PAPL designation'),
            'experience' => Yii::t('app', 'Experience in Year'),
            'plant_id' => Yii::t('app', 'Plant'),
            'esic_code' => Yii::t('app', 'ESIC Code'),
            'esic_ip_number' => Yii::t('app', 'ESIC IP Number'),
            'wca_gpa' => Yii::t('app', 'WCA/GPA'),
            'wca_gpa_expire_date' => Yii::t('app', 'WCA/GPA Expire Date'),
            'esic_sheet' => Yii::t('app', 'Browse ESIC Sheet'),
            'pf_code' => Yii::t('app', 'PF Code'),
            'uan' => Yii::t('app', 'UAN'),
            'pf_account_number' => Yii::t('app', 'PF Account Number'),
            'uan_sheet' => Yii::t('app', 'Browse UAN Sheet'),
            'referenced_remark' => Yii::t('app', 'Referenced Remark'),
            'status' => Yii::t('app', '0=>no action, 1=> transfer_to plant,2=>approved_by_plant_mgr,3=>epf_esi created,4=>rejected,5=>parmanent'),
            'comment' => Yii::t('app', 'Comment'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Qualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQualifications()
    {
        return $this->hasMany(Qualification::className(), ['enrolement_id' => 'enrolement_id']);
    }
    public function getBankdetail()
    {
        return $this->hasOne(BankDetails::className(), ['enrolement_id' => 'enrolement_id']);
    }
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['enrolement_id' => 'enrolement_id']);
    }
    public function getPlant()
    {
        return $this->hasOne(Plant::className(), ['plant_id' => 'plant_id']);
    }
    public function getFamilies()
    {
        return $this->hasMany(Family::className(), ['enrolement_id' => 'enrolement_id']);
    }
    public function getNominee()
    {
        return $this->hasOne(Nominee::className(), ['enrolement_id' => 'enrolement_id']);
    }
    public function getPostinghistory()
    {
        return $this->hasMany(PostingHistory::className(), ['papl_id' => 'papl_id']);
    }
    public function getCurrentposting()
    {
        return $this->hasOne(PostingHistory::className(), ['papl_id' => 'papl_id',])->andWhere(['status' => 0]);
    }
    public function getAttendances()
    {
        return $this->hasMany(Attendance::className(), ['papl_id' => 'papl_id']);
    }
    public function fileupload($filename,$name)
    {
        $extension = explode('.', $filename);
        $ext = end($extension);
        // echo $ext;die();
        if ($name == 'PAPL_Ineternal_Experience') {
            $name = 'PAPL_Ineternal_Experience';
        }
        if ($ext == 'pdf' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
            $basepath = Yii::getAlias('@storage');
            $randnum = Yii::$app->security->generateRandomString();
            $file = '/upload/' .$name.$randnum . ".{$ext}";
            $path = $basepath . $file;
            $filename->saveAs($path);
            return $file;
        }
    }
    public function imageupload($filename,$name)
    {

        if ($name == 'Aadhaar_Photo') {
            $name = 'Aadhaar_Photo';
        }
        if ($name == 'Passport_Photo') {
            $name = 'Passport_Photo';
        }
        if ($name == 'UAN_Sheet') {
            $name = 'UAN_Sheet';
        }
        if ($name == 'ESIC_Sheet') {
            $name = 'ESIC_Sheet';
        }
        $extension = explode('.', $filename);
        $ext = end($extension);
        if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'pdf'|| $ext == 'JPEG'|| $ext == 'PDF'|| $ext == 'JPG' || $ext == 'PNG') {
            $basepath = Yii::getAlias('@storage');
            $randnum = Yii::$app->security->generateRandomString();
            $file = '/upload/' .$name.$randnum . ".{$ext}";
            $path = $basepath . $file;
            $filename->saveAs($path);
            return $file;
        }
    }
   
}
