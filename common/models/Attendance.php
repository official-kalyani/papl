<?php

namespace common\models;
use common\models\DailyEarning;
use common\models\SalaryMapping;
use common\models\DeductionMapping;
use common\models\PostingHistory;
use Yii;

/**
 * This is the model class for table "attendance".
 *
 * @property int $id
 * @property string|null $papl_id
 * @property string|null $employee_name
 * @property string|null $att
 * @property string|null $att_type
 * @property string|null $nh
 * @property string|null $fh
 * @property string|null $nhfh_type
 * @property string|null $ot
 * @property string|null $ot_type
 * @property string|null $remark
 *
 * @property Employee $papl
 */
class Attendance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $state;
    public $location;
    public $import_file,$plant_id, $plant_name, $plants, $locations, $location_id, $location_name, $purchase_orderid, $section_id, $start_date;
    public static function tableName()
    {
        return 'attendance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['papl_id', 'employee_name', 'att_type', 'nh', 'fh', 'nhfh_type', 'ot', 'ot_type', 'remark'], 'string', 'max' => 255],
            [['att'], 'string', 'max' => 20],
            [['date', 'papl_id'], 'required'],
            [['papl_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['papl_id' => 'papl_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'papl_id' => Yii::t('app', 'Papl ID'),
            'employee_name' => Yii::t('app', 'Employee Name'),
            'att' => Yii::t('app', 'Att'),
            'att_type' => Yii::t('app', 'Att Type'),
            'nh' => Yii::t('app', 'Nh'),
            'fh' => Yii::t('app', 'Fh'),
            'nhfh_type' => Yii::t('app', 'Nhfh Type'),
            'ot' => Yii::t('app', 'Ot'),
            'ot_type' => Yii::t('app', 'Ot Type'),
            'remark' => Yii::t('app', 'Remark'),
        ];
    }

    /**
     * Gets query for [[Papl]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPapl()
    {
        return $this->hasOne(Employee::className(), ['papl_id' => 'papl_id']);
    }
    public function getPostinghistory(){
       return $this->hasOne(PostingHistory::className(), ['papl_id' => 'papl_id']); 
    }
    public function getEnrolement()
    {
        return $this->hasOne(Enrollment::className(), ['papl_id' => 'papl_id']);
    }
     public function getEnrolementDetail($papl_id)
    {
        $enrol = Enrollment::find()->where(['papl_id'=>$papl_id])->one();
        return $enrol;
    }

    public function CalculateDailyEarning($papl_id,$ondate)
    {
        $salary=SalaryMapping::find()->where(['papl_id'=>$papl_id])->all();
        $deduction=DeductionMapping::find()->where(['papl_id'=>$papl_id])->all();
        $attendance = Attendance::find()->where(['papl_id'=>$papl_id,'date'=>$ondate])->one();
        if($attendance){
            $posting_history = PostingHistory::find()->where(['papl_id'=>$papl_id])->one();
            $att=0;
            $base_salary=0;
            $earning_amount=0;
            $deduction_amount=0;
            $loc_allowance=0;
            $earnings=[];
            $deductions=[];
            foreach($salary as $sal){
                if($sal->salary->attribute_name=="Basic"){
                    $base_salary=$sal->amount;
                    //$earnings['Basic']=$sal->amount;
                    break;
                }
                
            }
            foreach($salary as $sal){
                if($sal->salary->attribute_name !="Basic"){
                    if($sal->salary->type=="amount"){
                        $earning_amount+=$sal->amount;
                        $earnings[$sal->salary_id]=$sal->amount;
                    }elseif($sal->salary->type=="percentage"){
                        $earning_amount+=($sal->amount / 100) * $base_salary;
                        $earnings[$sal->salary_id]=($sal->amount / 100) * $base_salary;
                    }
                    if($sal->salary->attribute_name =="Location Allowance"){
                        $loc_allowance=$earnings[$sal->salary_id];
                    }
                }
            }
            
            
            if($attendance->att=='P' || $attendance->att=='A' || $attendance->att=='B' || $attendance->att=='C' || $attendance->att=='G' || $attendance->att=='COFF'|| $attendance->att=='L'){
                $att=$att+1;
            }
            if($attendance->nh){
                $att=$att+1;
            }
            if($attendance->fh){
                $att=$att+1;
            }
            // if($attendance->ot){
            //     if($attendance->ot>=4){
            //         $att=$att+1;
            //     }else{
            //         $att=$att+0.5;
            //     }
            //     // if($attendance->ot_type=='BD'){
            //     //     $att=2;
            //     // }elseif($attendance->ot_type=='BS'){
            //     //     $att=1;
            //     // }
            // }
            //return $att;
            // if($attendance->att_type =='B'){
            //     $total_earning=$base_salary*$att;
            // }else{
            //     $total_earning=($earning_amount-$deduction_amount)*$att;
            // }

            if($attendance->ot){
                if($attendance->ot_type=='BD'){
                    $earnings['ot_bd']=($base_salary/4)*$attendance->ot;
                }elseif($attendance->ot_type=='GD'){
                    $earnings['ot_gd']=($earning_amount*$attendance->ot)*2;
                }elseif($attendance->ot_type=='GS'){
                    $earnings['ot_gs']=(($base_salary+$loc_allowance)/8)*$attendance->ot;
                    //$earnings['ot_gs']=($earning_amount*$attendance->ot);
                }elseif($attendance->ot_type=='BS'){
                    $earnings['ot_bs']=($base_salary/8)*$attendance->ot;
                }
            }
            $total_earning=array_sum(array_values($earnings))+$base_salary;
            //$total_earning=$total_earning*$att;
            $daily_earning= DailyEarning::find()->where(['papl_id'=>$papl_id,'ondate'=>$ondate])->one();
            
            if(!$daily_earning){
                $daily_earning=new DailyEarning();
            }
            $daily_earning->papl_id=$papl_id;
            //$daily_earning->type='basic';//basic/hra/pf/esi
            $daily_earning->att=$att;
            $daily_earning->location_id=$posting_history->location_id;
            $daily_earning->plant_id=$posting_history->plant_id;
            $daily_earning->purchase_orderid=$posting_history->purchase_orderid;
            $daily_earning->section_id=$posting_history->section_id;
            $daily_earning->basic=$base_salary;
            $daily_earning->earnings=json_encode($earnings);
            $daily_earning->deduction=json_encode($deductions);

            $daily_earning->amount=$total_earning;
            $daily_earning->ondate=$ondate;
            $daily_earning->status=0;
            if($daily_earning->save()){
                return "Salary calulated for ".$papl_id." for ".$ondate." is successful \n";;
            }else{
                //print_r();
                return json_encode($daily_earning->getErrors());
            }
        }else{
            return "No attendance record for ".$papl_id. " on ".$ondate."\n";
        }
        
    }
    public function getAttendanceDetailByDate($papl_id,$date){
        $enrol = Attendance::find()->where(['papl_id'=>$papl_id,'date' => $date,'status' => 0])->asArray()->one();
        return $enrol;
    }
    public function getAttendanceStatusByDate($papl_id,$date){
        $enrol = Attendance::find()->where(['papl_id'=>$papl_id,'date' => $date])->one();
        return $enrol;
    }
}
