<?php

namespace backend\controllers;

use Yii;
use common\models\Attendance;
use common\models\AttendanceSearch;
use common\models\Plant;
use common\models\Salary;
use common\models\Deduction;
use common\models\Location;
use common\models\Enrollment;
use common\models\PostingHistory;
use common\models\MonthlySalary;
use common\models\DailyEarning;
use common\models\SalaryMapping;
use common\models\DeductionMapping;
use common\models\Purchaseorder;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class CalculationController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    static function actionWorkdays($papl_id,$date){
        $tot_days=date('t',strtotime($date));
        $working_days=$extra_att=$leave=$coff=$nh_fh=$ot_bd=$ot_gd=$ot_gs=$ot_bs=$weekly_off=$misc_att=$other_ot_hour=$tot_paydays=$el=0;
        $tot_ot_hours=0.0;
        $att_from_date=$att_to_date="";
        $count=1;
        for($i=1;$i<=$tot_days;$i++){
            $ondate=date('Y-m-'.$i,strtotime($date));
            //echo $ondate;
            $attendance = Attendance::find()->where(['papl_id'=>$papl_id,'date'=>$ondate])->one();
            if($attendance){
                $att_to_date=$ondate;
                if($count==1){
                    $att_from_date=$ondate;
                    $count+=1;
                }
                if($attendance->att=='P' || $attendance->att=='A' || $attendance->att=='B' || $attendance->att=='C' || $attendance->att=='G'){
                    $working_days+=1;
                }
                if($attendance->att=='L'){
                    $leave+=1;
                }
                if($attendance->att=='O'){
                    $weekly_off+=1;
                }
                if($attendance->att=='COFF'){
                    $coff+=1;
                }
                if($attendance->nh){
                    $nh_fh+=1;
                }
                if($attendance->fh){
                    $nh_fh+=1;
                }
                if($attendance->ot){
                        if($attendance->ot_type=='BD'){
                        $ot_bd+=$attendance->ot;
                        }elseif($attendance->ot_type=='GS'){
                        $ot_gs+=$attendance->ot;
                        }elseif($attendance->ot_type=='GD'){
                        $ot_gd+=$attendance->ot;
                        }elseif($attendance->ot_type=='BS'){
                        $ot_bs+=$attendance->ot;
                        }
                }
            }
            
            //print_r($attendance);
        }
        $tot_paydays=$working_days+$extra_att+$leave+$coff+$nh_fh;
        $tot_ot_hours=$ot_bd+$ot_gd+$ot_gs+$ot_bs;
        $workdone=[];
        //$workdone['papl_id']=$papl_id;
        $workdone['working_days']=$working_days;
        $workdone['extra_att']=$extra_att;
        $workdone['leave']=$leave;
        $workdone['coff']=$coff;

        $workdone['nh_fh']=$nh_fh;
        $workdone['ot_bd']=$ot_bd;
        $workdone['ot_gs']=$ot_gs;
        $workdone['ot_gd']=$ot_gd;
        $workdone['ot_bs']=$ot_bs;

        $workdone['weekly_off']=$weekly_off;
        $workdone['misc_att']=$misc_att;
        $workdone['other_ot_hour']=$other_ot_hour;
        $workdone['tot_paydays']=$tot_paydays;
        //$workdone['att_from_date']=$att_from_date;
        //$workdone['att_to_date']=$att_to_date;
        $workdone['tot_ot_hours']=$tot_ot_hours;
        $workdone['el']=$el;

        //print_r($workdone);
        return $workdone;
        
    }

    //Total monthly salary calculation
    public function actionTotal_salary_calculation($plant_id,$po_id,$section_id,$date){
        if($po_id==='null'){
            $po_id='';
        }
        if($section_id==='null'){
            $section_id='';
        }
        $model = new PostingHistory();
        //if ($model->load(Yii::$app->request->post())) {
            
            //$date='2021-08-24';//date('Y-m-d');//
            $first_day_this_month = date('01-m-Y',strtotime($date)); // hard-coded '01' for first day
            //$last_day_this_month  = date('Y-m-t');
            $last_day_this_month = date('t-m-Y', strtotime($date));
            $tot_days=date('t',strtotime($date));
            //$plant_id=1;//$model->plant_id;
            $posting_history = ArrayHelper::map(PostingHistory::find()
                                //->where(['status' => 1])
                                ->andWhere(['plant_id' => $plant_id])
                                ->andWhere(['<=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`start_date`, '%d-%m-%Y'), '%Y-%m-%d')"), date('Y-m-d', strtotime($last_day_this_month))])
                                ->andWhere(['or',
                                          ['>=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`end_date`, '%d-%m-%Y'), '%Y-%m-%d')"), date('Y-m-d', strtotime($first_day_this_month)) ],
                                          ['end_date' => null]
                                      ])
                                //->andWhere(['between', 'end_date', $fromdate, $todate ])
                                ->andFilterWhere(['purchase_orderid' => $po_id])
                                ->andFilterWhere(['section_id' =>$section_id])
                                ->orderBy(['papl_id' => SORT_ASC])->all(), 'id','papl_id');
        
            //print_r($posting_history);exit;
           
            foreach($posting_history as $emp){
                //$monthly_att=$this->actionWorkdays($emp,$date);
                $enrollment=Enrollment::find()->select('id,papl_id,adhar_name,designation')->where(['papl_id'=>$emp])->one();
                
                //print_r($monthly_att);
                //print_r($emp_salary_details);exit;
                $monthly_salary= MonthlySalary::find()->where(['papl_id'=>$emp,'plant_id'=>$plant_id,'month_year'=>date('F Y', strtotime($date))])->one();
                if($monthly_salary){
                    $monthly_salary->misc_att=0;
                    $monthly_salary->other_ot_hour=0;
                    $monthly_salary->save();
                }
                $emp_salary_details=CalculationController::actionCalculate_salary($emp,$date,$plant_id);
                if(!$monthly_salary){
                    $monthly_salary=new MonthlySalary();
                    //$monthly_salary->misc_att=0;
                    $monthly_salary->lwf_refund=0;
                    $monthly_salary->esi_refund=0;
                }
                $monthly_salary->misc_att=0;
                $monthly_salary->other_ot_hour=0;
                $monthly_salary->other_ot_earning=0;
                $monthly_salary->papl_id=$emp;
                $monthly_salary->plant_id=$plant_id;
                $monthly_salary->designation=$enrollment->designation;
                $monthly_salary->month_year=date('F Y', strtotime($date));
                $monthly_salary->from_date=$emp_salary_details['sal_from_date'];
                $monthly_salary->to_date=$emp_salary_details['sal_to_date'];

                $monthly_salary->earning_detail=json_encode($emp_salary_details['earnings']);
                $monthly_salary->deduction_detail=json_encode($emp_salary_details['deductions']);

                $monthly_salary->total_basic=$emp_salary_details['total_basic'];
                $monthly_salary->total_gross=$emp_salary_details['total_gross'];
                //$monthly_salary->misc_att=0;
                $monthly_salary->misc_earning=$emp_salary_details['daily_gross']*$monthly_salary->misc_att;
                $monthly_salary->total_salary=$monthly_salary->total_gross+$monthly_salary->misc_earning+$monthly_salary->other_ot_earning;//$emp_salary_details['total_salary'];
                $monthly_salary->total_deduction=$emp_salary_details['deduction_amount'];
                $monthly_salary->net_payble=$monthly_salary->total_salary-$monthly_salary->total_deduction;//$emp_salary_details['net_payble'];
                
                $monthly_salary->total_payble=$monthly_salary->net_payble+$monthly_salary->lwf_refund+$monthly_salary->esi_refund;//$emp_salary_details['total_payble'];
                //print_r($monthly_salary);
                if($monthly_salary->save()){
                    echo "Salary calulated for ".$emp." for ".$monthly_salary->month_year." is successful <br />";;
                }else{
                    //print_r();
                    echo json_encode($monthly_salary->getErrors());
                }
            }      
        //}  
        exit;
     }
     //Monthly Salary Calculation for an employee
     static function actionCalculate_salary($emp,$date,$plant_id){
        //echo $emp;
        $misc_att=0;
        $other_ot=0;
        $monthly_salary= MonthlySalary::find()->where(['papl_id'=>$emp,'plant_id'=>$plant_id,'month_year'=>date('F Y', strtotime($date))])->one();
        if($monthly_salary){
            $misc_att=$monthly_salary->misc_att;
            $other_ot=$monthly_salary->other_ot_hour;
        }   
        $total_basic=0;
        $total_gross=0;
        $daily_gross=0;
        $last_daily_gross=0;
        $last_daily_basic=0;
        $current_daily_gross=0;
        $salary_sheet=[];
        $earnings=[];
        $sal_from_date=$sal_to_date="";

        $fromdate=date('Y-m-01', strtotime($date));
        $todate=date('Y-m-t', strtotime($date));
        $daily_earnings=DailyEarning::find()
                        ->andWhere(['between', 'ondate', $fromdate, $todate ])
                        ->andWhere(['papl_id'=>$emp])
                        ->all();
        //print_r($monthly_att);
        $last_daily_earnings=DailyEarning::find()
                        ->andWhere(['between', 'ondate', $fromdate, $todate ])
                        ->andWhere(['papl_id'=>$emp])
                        ->orderBy('ondate DESC')
                        ->one();

       if($last_daily_earnings){
         $last_earning=json_decode($last_daily_earnings->earnings,true);
          foreach($last_earning as $lastk=>$lastv){
            if($lastk=='ot_bd'|| $lastk=='ot_bs' || $lastk=='ot_gd'|| $lastk=='ot_gs'){
              unset($last_earning[$lastk]);
            }
          }
          $last_daily_gross=array_sum($last_earning)+$last_daily_earnings->basic;
          $last_daily_basic=$last_daily_earnings->basic;
       }
        

       //return $last_tot_earning;
        $count=1;
        foreach($daily_earnings as $daily_earning){
            //echo $daily_earning->earnings;
            $sal_to_date=$daily_earning->ondate;
            if($count==1){
                $sal_from_date=$daily_earning->ondate;
                $count+=1;
            }
            $earning=json_decode($daily_earning->earnings,true);
            //print_r($earning);
           
            $attendance=Attendance::find()->where(['papl_id'=>$emp,'date'=>$daily_earning->ondate])->one();
            $att=0;
            if($attendance->att=='P' || $attendance->att=='A' || $attendance->att=='B' || $attendance->att=='C' || $attendance->att=='G' || $attendance->att=='COFF'|| $attendance->att=='L'){
                $att=$att+1;
                if($earning){
                    foreach($earning as $k=>$v){
                        if(array_key_exists($k,$earnings)){
                            $earnings[$k]+=$v;
                        }else{
                            $earnings[$k]=$v;
                        }
                        
                    }
                    $current_daily_gross=array_sum(array_values($earning))+$daily_earning->basic;
                    $daily_gross=array_sum(array_values($earning));
                    $total_gross+=$daily_gross;
                }
            }
            if($attendance->nh){
                $att=$att+1;
            }
            if($attendance->fh){
                $att=$att+1;
            }
            //echo $daily_gross+$daily_earning->basic;
            $total_basic+=($daily_earning->att*$daily_earning->basic);
              
        }
        $salary_sheet['papl_id']=$emp;
        $salary_sheet['sal_from_date']=$sal_from_date;
        $salary_sheet['sal_to_date']=$sal_to_date;
        $salary_sheet['daily_gross']=$last_daily_gross;
        $salary_sheet['earnings']=$earnings;
        $salary_sheet['total_basic']=$total_basic;
        $salary_sheet['total_gross']=round($total_gross+$total_basic);
        $salary_sheet['misc_att']=$misc_att;
        $salary_sheet['other_ot_hour']=$other_ot;
        $salary_sheet['misc_earning']=$salary_sheet['daily_gross']*$misc_att;
        $salary_sheet['other_ot_earning']=($last_daily_basic/4)*$other_ot;
        $salary_sheet['total_salary']=$salary_sheet['total_gross']+$salary_sheet['misc_earning'];
        
        //print_r($salary_sheet);
        $deductions=[];
        $deduction_amount=0;
        $deduction_master=Deduction::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all();
                                
        $emp_deduction=ArrayHelper::map(DeductionMapping::find()->where(['papl_id'=>$emp])->all(), 'deduction_id','amount');
        foreach($deduction_master as $deduc){
            //return $deduc->deduction;
            if($deduc->attribute_name=="PT"){
                if($salary_sheet['total_salary']<=13303){
                    $deduction_amount+=0;
                    $deductions[$deduc->id]=0;
                }elseif($salary_sheet['total_salary']>=13304){
                    if($salary_sheet['total_salary']>=25001){
                        $deduction_amount+=200;
                        $deductions[$deduc->id]=200;
                    }else{
                        $deduction_amount+=125;
                        $deductions[$deduc->id]=125;
                    }
                }
                
            }elseif($deduc->attribute_name=="ESI"){
                $plant_esi=Plant::findOne($plant_id);
                $is_esi=$plant_esi->is_esi;
              if($is_esi && $salary_sheet['total_salary']<=21000 && isset($emp_deduction[$deduc->id])){
                  if($deduc->type=="amount"){
                      $deduction_amount+=$emp_deduction[$deduc->id];
                      $deductions[$deduc->id]=$emp_deduction[$deduc->id];
                  }elseif($deduc->type=="percentage"){
                      $deduction_amount+=($emp_deduction[$deduc->id] / 100) * $salary_sheet['total_salary'];
                      $deductions[$deduc->id]=($emp_deduction[$deduc->id] / 100) * $salary_sheet['total_salary'];
                  }
              }else{
                $deduction_amount+=0;
                $deductions[$deduc->id]=0;
              }
            }
//           elseif($deduc->attribute_name=="PF"){
//                 $deduction_amount+=round((12 / 100) * $total_basic);
//                 $deductions[$deduc->id]=round((12 / 100) * $total_basic);
//             }
          else{
                if(isset($emp_deduction[$deduc->id])){
                    if($deduc->type=="amount"){
                        $deduction_amount+=$emp_deduction[$deduc->id];
                        $deductions[$deduc->id]=$emp_deduction[$deduc->id];
                    }elseif($deduc->type=="percentage"){
                        $deduction_amount+=($emp_deduction[$deduc->id] / 100) * $total_basic;
                        $deductions[$deduc->id]=($emp_deduction[$deduc->id] / 100) * $total_basic;
                    }
                }else{
                    $deduction_amount+=0;
                    $deductions[$deduc->id]=0;
                }
            }
            
        }
        $salary_sheet['deductions']=$deductions;
        $salary_sheet['deduction_amount']=round($deduction_amount);
        $salary_sheet['net_payble']=$salary_sheet['total_salary']-$salary_sheet['deduction_amount'];
        $salary_sheet['lwf_refund']=0;
        $salary_sheet['esi_refund']=0;
        $salary_sheet['total_payble']=$salary_sheet['net_payble']+$salary_sheet['lwf_refund']+$salary_sheet['esi_refund'];
        //echo $deduction_amount;
        return $salary_sheet;
     }

     //Monthly Salary Sheet Download
    public function actionSalary_sheet($plant_id,$po_id,$section_id,$date){
        if($po_id==='null'){
            $po_id='';
        }
        if($section_id==='null'){
            $section_id='';
        }
		//echo $plant_id;exit;
        //$date=date('Y-m-d');
        //echo $po_id;echo $section_id;exit;
        $basepath=Yii::getAlias('@storage');
        $plant=Plant::findOne($plant_id);
        
		$filename="salry_sheet_".$plant->plant_name."_".date('F Y', strtotime($date)).".xls";
        if(file_exists(Yii::getAlias('@storage')."/export/".$filename))
            unlink(Yii::getAlias('@storage')."/export/".$filename);
        $spreadsheet = new Spreadsheet();
        
        
        $sheet_blank = $spreadsheet->getActiveSheet()->freezePane('E2');
       
        $styleArray = [
            'font' => [
                'bold'  =>  true,
                'size'  =>  10,
                'name'  =>  'Tahoma',
                //'color' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        
        $styleArray_att = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['rgb' => '12080B'],
                ),
            ),
        );
        $styleArray_workdone = array(
            'font' => [
                'bold'  =>  true,
                'size'  =>  14,
            ],
            'alignment' => [
                'textRotation' => 90,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            // 'fill' => array(
            //     'fill' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            //     'color' => ['rgb' => '12080B']
            // )
        );
        
        //$sheet_blank = $spreadsheet->createSheet(1); 
        
        
        $sheet_blank->getColumnDimension('B')->setWidth(16);
        $sheet_blank->getColumnDimension('C')->setWidth(22);
        $sheet_blank->getColumnDimension('D')->setWidth(20);
        $row = 1;
        $sheet_blank->mergeCells("A1:D1")->setCellValue("A1","Month/Year:");
        $sheet_blank->mergeCells("E1:G1")->setCellValue('E'.$row, date('F Y', strtotime($date)));

        $row++;
        $sheet_blank->mergeCells("A2:D2")->setCellValue("A2","Plant Name:");
        $sheet_blank->mergeCells("E2:Z2")->setCellValue('E'.$row, $plant->plant_name);

        $row++;
        $merge_row=$row;
        $sheet_blank->mergeCells("A".$merge_row.":D".$merge_row)->setCellValue("A".$merge_row,"WORKMEN DETAILS");

        $row++;
        $sheet_blank->setCellValue('A'.$row, 'Sl No.');
        $sheet_blank->setCellValue('B'.$row, 'EMP ID');
        $sheet_blank->setCellValue('C'.$row, 'Name of Employees ');
        $sheet_blank->setCellValue('D'.$row, 'Designation');

        $sheet_blank->setCellValue('E'.$row, 'No of Days Worked');
        $sheet_blank->setCellValue('F'.$row, 'Extra Attendance');
        $sheet_blank->setCellValue('G'.$row, 'Leave Days');
        $sheet_blank->setCellValue('H'.$row, 'COFF Days');
        $sheet_blank->setCellValue('I'.$row, 'No of NH/FH for which Wages have been paid');
        $sheet_blank->setCellValue('J'.$row, 'OT Hrs for the month (Basic Double)');
        $sheet_blank->setCellValue('K'.$row, 'OT Hrs for the month (Gross Single)');
        $sheet_blank->setCellValue('L'.$row, 'OT Hrs for the month (Gross Double)');
        $sheet_blank->setCellValue('M'.$row, 'OT Hrs for the month (Basic Single)');
        $sheet_blank->setCellValue('N'.$row, 'No. of Weekly Off If any');
        $sheet_blank->setCellValue('O'.$row, 'Misc. Attendance');
        $sheet_blank->setCellValue('P'.$row, 'Other OT Hours (BD)');
        $sheet_blank->setCellValue('Q'.$row, 'Total Payble Days');
        $sheet_blank->setCellValue('R'.$row, 'TOTAL OT HOURS');
        $sheet_blank->getStyle('R'.$row)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        $sheet_blank->setCellValue('S'.$row, 'E L Days for which wages have been paid');

        $sheet_blank->mergeCells("E".$merge_row.":S".$merge_row)->setCellValue("E".$merge_row,"WORK DONE");
        //$sheet_blank->getStyle('E'.$row)->getAlignment()->setTextRotation(90);
        
        $sheet_blank->getStyle('E'.$row.':S'.$row)->getFill()
          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
          ->getStartColor()->setARGB('FFE5CC');
        $header_col='T';
        $set_salary_col_start=$header_col;
        $sheet_blank->getRowDimension($row)->setRowHeight(110);
        $sheet_blank->setCellValue( $header_col.$row, 'Daily Rate of Wages/Piece Rate (Basic)');
        $header_col++;

        $salary_master=Salary::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all();
        foreach($salary_master as $sal){
            if($sal->attribute_name !="Basic"){
                $sheet_blank->setCellValue($header_col.$row, $sal->attribute_name);
                $header_col++;
            }
        }
        
        $sheet_blank->setCellValue($header_col.$row, 'Daily Rate of Wages/Piece Rate (Gross)');
        
        $sheet_blank->getStyle($set_salary_col_start.$row.':'.$header_col.$row)->getFill()
          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
          ->getStartColor()->setARGB('1E90FF');

        $sheet_blank->mergeCells($set_salary_col_start.$merge_row.":".$header_col.$merge_row)->setCellValue($set_salary_col_start.$merge_row,"SET SALARY");
        
        $header_col++;
        $earning_col_start=$header_col;
        $sheet_blank->setCellValue($header_col.$row, 'Total Basic Amt.');
        $header_col++;
        foreach($salary_master as $sal){
            if($sal->attribute_name !="Basic"){
                $sheet_blank->setCellValue($header_col.$row, "Net ".$sal->attribute_name);
                $header_col++;
            }
        }
        

        $sheet_blank->setCellValue($header_col.$row, 'OT Amount for the Month (Basic Double)');
        $header_col++;
        $sheet_blank->setCellValue($header_col.$row, 'OT Amount for the Month (Gross Single)');
        $header_col++;
        $sheet_blank->setCellValue($header_col.$row, 'OT Amount for the Month (Gross Double)');
        $header_col++;
        $sheet_blank->setCellValue($header_col.$row, 'OT Amount for the Month (Basic Single)');
        $header_col++;
        $sheet_blank->setCellValue($header_col.$row, 'Miscelleneous Earnings');
        $header_col++;
        $sheet_blank->setCellValue($header_col.$row, 'Other OT Earnings');
        $header_col++;
        $sheet_blank->setCellValue($header_col.$row, 'Total Amount');
        

        $sheet_blank->getStyle($earning_col_start.$row.':'.$header_col.$row)->getFill()
          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
          ->getStartColor()->setARGB('ADD8E6');

        $sheet_blank->mergeCells($earning_col_start.$merge_row.":".$header_col.$merge_row)->setCellValue($earning_col_start.$merge_row,"EARNING");
        
        $header_col++;
        $deduction_col_start=$header_col;
        $deduction_master=ArrayHelper::map(Deduction::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all(), 'id','attribute_name');
        foreach($deduction_master as $k=>$v){
                $sheet_blank->setCellValue($header_col.$row, $v);
                $header_col++;
        }
        $sheet_blank->setCellValue($header_col.$row, 'Total Deduction');
        $sheet_blank->getStyle($deduction_col_start.$row.':'.$header_col.$row)->getFill()
          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
          ->getStartColor()->setARGB('FFD700');
        
        $sheet_blank->mergeCells($deduction_col_start.$merge_row.":".$header_col.$merge_row)->setCellValue($deduction_col_start.$merge_row,"DEDUCTION");

        $header_col++;
        $netpay_col_start=$header_col;

        $sheet_blank->setCellValue($header_col.$row, 'Net Payble');
        $header_col++;
        $sheet_blank->setCellValue($header_col.$row, 'LWF Refund');
        $header_col++;
        $sheet_blank->setCellValue($header_col.$row, 'ESI Refund');
        $header_col++;
        $sheet_blank->setCellValue($header_col.$row, 'Total Payable');

        $sheet_blank->getStyle($netpay_col_start.$row.':'.$header_col.$row)->getFill()
          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
          ->getStartColor()->setARGB('9ACD32');
        
        $sheet_blank->mergeCells($netpay_col_start.$merge_row.":".$header_col.$merge_row)->setCellValue($netpay_col_start.$merge_row,"NET PAYABLE");

        $header_col++;
        $sheet_blank->getStyle('E'.$row.':'.$header_col.$row)->getAlignment()->setWrapText(true);
        $sheet_blank->getStyle('E'.$row.':'.$header_col.$row)->applyFromArray($styleArray_workdone);
        
        $column = 'E';
        
        $lastColumn = $sheet_blank->getHighestColumn();
        $sheet_blank->getStyle('A1:'.$lastColumn.$row)->applyFromArray($styleArray);
        $row++;
        //$sheet_blank->getStyle('A2:'.$lastColumn.$row)->applyFromArray($styleArray);
        $sl=1;
        $rowCount = $row;


        //$date='2021-08-24';//date('Y-m-d');//
        $first_day_this_month = date('01-m-Y',strtotime($date)); // hard-coded '01' for first day
        //$last_day_this_month  = date('Y-m-t');
        $last_day_this_month = date('t-m-Y', strtotime($date));
        $tot_days=date('t',strtotime($date));
        $posting_history = ArrayHelper::map(PostingHistory::find()
                            //->where(['status' => 1])
                            ->andWhere(['plant_id' => $plant_id])
                            ->andWhere(['<=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`start_date`, '%d-%m-%Y'), '%Y-%m-%d')"), date('Y-m-d', strtotime($last_day_this_month))])
                            ->andWhere(['or',
                                          ['>=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`end_date`, '%d-%m-%Y'), '%Y-%m-%d')"), date('Y-m-d', strtotime($first_day_this_month)) ],
                                          ['end_date' => null]
                                      ])
                            //->andWhere(['between', 'end_date', $fromdate, $todate ])
                            ->andFilterWhere(['purchase_orderid' =>$po_id])
                            ->andFilterWhere(['section_id' =>$section_id])
                            ->orderBy(['papl_id' => SORT_ASC])->all(), 'id','papl_id');
        //print_r($posting_history);exit;
        foreach($posting_history as $emp){
            $ph=new PostingHistory();
            $employee_details = Enrollment::find()->select('id,papl_id,adhar_name,designation')->where(['papl_id'=>$emp])->one();
            
            
            $sheet_blank->setCellValue('A' . $rowCount, $sl);
            $sheet_blank->setCellValue('B' . $rowCount, $employee_details['papl_id']);
            $sheet_blank->setCellValue('C' . $rowCount, $employee_details['adhar_name']);
            $sheet_blank->setCellValue('D' . $rowCount, $employee_details['designation']);
            $work_done=CalculationController::actionWorkdays($emp,$date);
            $column = 'E';
            foreach($work_done as $k=>$v){
                if($k=="misc_att"){
                    $msalary=$ph->getMonthlySalary($plant_id,$emp,$date);
                    if($msalary)
                        $misc_att=$msalary->misc_att;
                    else
                        $misc_att=0;
                    $sheet_blank->setCellValue($column.$rowCount, $misc_att);
                }elseif($k=="other_ot_hour"){
                    $msalary=$ph->getMonthlySalary($plant_id,$emp,$date);
                    if($msalary)
                        $other_ot_hour=$msalary->other_ot_hour;
                    else
                        $other_ot_hour=0;
                    $sheet_blank->setCellValue($column.$rowCount, $other_ot_hour);
                }else{
                    $sheet_blank->setCellValue($column.$rowCount, $v);
                }
                $column++;
            }

            $set_salary= ArrayHelper::map(SalaryMapping::find()->where(['papl_id'=>$emp])->all(), 'salary_id','amount');
            foreach($salary_master as $sal){
              if($sal->attribute_name=="Basic"){
                if(isset($set_salary[$sal->id])){
                    $base_salary=$set_salary[$sal->id];
                    $sheet_blank->setCellValue($column.$rowCount, $set_salary[$sal->id]);
                    $column++;
                    break;
                 }else{
                    $base_salary=0;
                    $sheet_blank->setCellValue($column.$rowCount, 0);
                    $column++;
                    break;
                }
              }
            }
          
            $daily_gross=0;
            foreach($salary_master as $sal){
              if($sal->attribute_name!="Basic"){
                if(isset($set_salary[$sal->id])){
                    if($sal->type=="amount"){
                        $sheet_blank->setCellValue($column.$rowCount, $set_salary[$sal->id]);
                        $daily_gross+=$set_salary[$sal->id];
                       
                    }elseif($sal->type=="percentage"){
                        $sheet_blank->setCellValue($column.$rowCount, ($set_salary[$sal->id] / 100) * $base_salary);
                        $daily_gross+=($set_salary[$sal->id] / 100) * $base_salary;
                        
                    }
                }else{
                    $sheet_blank->setCellValue($column.$rowCount, 0);
                    $daily_gross+=0;
                }
                   $column++;  
              }
            }
            
            $sheet_blank->setCellValue($column.$rowCount, $daily_gross+$base_salary);
            $column++;
            //$total_earning=$this->actionCalculate_salary($emp,$date);
            $total_earning= MonthlySalary::find()->where(['papl_id'=>$emp,'plant_id'=>$plant_id,'month_year'=>date('F Y', strtotime($date))])->one();
            if($total_earning){
                $total_earning['earning_detail']=json_decode($total_earning['earning_detail'],true);
                $total_earning['deduction_detail']=json_decode($total_earning['deduction_detail'],true);
                //print_r($total_earning);
                //echo $total_earning['total_basic'];exit;
                $sheet_blank->setCellValue($column.$rowCount, round($total_earning['total_basic']));
                $column++;
                
                foreach($salary_master as $sal){
                    if($sal->attribute_name!="Basic"){
                        //print_r($total_earning['earning_detail']);
                        if(array_key_exists($sal->id,$total_earning['earning_detail'])){
                            $sheet_blank->setCellValue($column.$rowCount, round($total_earning['earning_detail'][$sal->id]));
                        }else{
                            $sheet_blank->setCellValue($column.$rowCount, "0.00");
                        }
                        $column++;
                    }
                }
                if(array_key_exists('ot_bd',$total_earning['earning_detail'])){
                    $sheet_blank->setCellValue($column.$rowCount, round($total_earning['earning_detail']['ot_bd']));
                }else{
                    $sheet_blank->setCellValue($column.$rowCount, 0);
                }
                $column++;

                if(array_key_exists('ot_gs',$total_earning['earning_detail'])){
                    $sheet_blank->setCellValue($column.$rowCount, round($total_earning['earning_detail']['ot_gs']));
                }else{
                    $sheet_blank->setCellValue($column.$rowCount, 0);
                }
                $column++;

                if(array_key_exists('ot_gd',$total_earning['earning_detail'])){
                    $sheet_blank->setCellValue($column.$rowCount, round($total_earning['earning_detail']['ot_gd']));
                }else{
                    $sheet_blank->setCellValue($column.$rowCount, 0);
                }
                $column++;
                if(array_key_exists('ot_bs',$total_earning['earning_detail'])){
                    $sheet_blank->setCellValue($column.$rowCount, round($total_earning['earning_detail']['ot_bs']));
                }else{
                    $sheet_blank->setCellValue($column.$rowCount, 0);
                }
                $column++;
                
                $sheet_blank->setCellValue($column.$rowCount,round($total_earning['misc_earning']));//
                $column++;

                $sheet_blank->setCellValue($column.$rowCount,round($total_earning['other_ot_earning']));//
                $column++;

                $sheet_blank->setCellValue($column.$rowCount, round($total_earning['total_salary']));
                $column++;

                foreach($deduction_master as $k1=>$v1){
                    if(array_key_exists($k1,$total_earning['deduction_detail'])){
                        $sheet_blank->setCellValue($column.$rowCount, round($total_earning['deduction_detail'][$k1]));
                    }else{
                        $sheet_blank->setCellValue($column.$rowCount, "0,00");
                    }
                    $column++;
                }
                $sheet_blank->setCellValue($column.$rowCount, round($total_earning['total_deduction']));
                $column++;
                //net payble
                $sheet_blank->setCellValue($column.$rowCount, round($total_earning['net_payble']));
                $column++;
                $sheet_blank->setCellValue($column.$rowCount, round($total_earning['lwf_refund']));
                $column++;
                $sheet_blank->setCellValue($column.$rowCount, round($total_earning['esi_refund']));
                $column++;
                $sheet_blank->setCellValue($column.$rowCount, round($total_earning['total_payble']));
                $column++;
            }
            
            //print_r($total_earning['earning_detail']);exit;
            $rowCount++;
            $sl++;
        }  
        
        $sheet_blank->setTitle('Salary Sheet');
        $writer = new Xlsx($spreadsheet);
        $writer->save($basepath . '/export/'.$filename);
        echo Yii::getAlias('@storageUrl')."/export/".$filename;exit;
	}

    public function actionSalary_postingsheet($plant_id,$po_id,$section_id,$date){
        
        if($section_id==='null'){
            $section_id='';
        }
		//echo $plant_id;exit;
        //$date=date('Y-m-d');
        //echo $po_id;echo $section_id;exit;
        $basepath=Yii::getAlias('@storage');
        $plant=Plant::findOne($plant_id);
        $po=Purchaseorder::findOne($po_id);
        $po_no="";
        if($po){
            $po_no=substr($po->po_number, -4);
        }
        $loc_name=$plant->location->location_name;
		$filename="posting_salary_sheet_".$plant->plant_name."_".date('F Y', strtotime($date)).".xls";
        if(file_exists(Yii::getAlias('@storage')."/export/".$filename))
            unlink(Yii::getAlias('@storage')."/export/".$filename);
        $spreadsheet = new Spreadsheet();
        
        
        $sheet_blank = $spreadsheet->getActiveSheet()->freezePane('C2');
       
        $styleArray = [
            'font' => [
                'bold'  =>  true,
                'size'  =>  11,
                'name'  =>  'Calibri',
                //'color' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        
        $styleArray_att = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['rgb' => '12080B'],
                ),
            ),
        );
        $styleArray_workdone = array(
            'font' => [
                'bold'  =>  true,
                'size'  =>  11,
            ],
            'alignment' => [
                //'textRotation' => 90,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
        );
        
        
        
        $sheet_blank->getColumnDimension('A')->setWidth(40);
        $sheet_blank->getColumnDimension('B')->setWidth(40);
        $row = 1;
        
        $sheet_blank->mergeCells('A'.$row.":B".$row)->setCellValue('A'.$row,"WORKMEN DETAILS");
        
        foreach (range('D', 'O') as $letra) {            
            if($letra <'G'){
                $sheet_blank->setCellValue($letra.$row, 'DR');
            }elseif($letra >'G'){
                $sheet_blank->setCellValue($letra.$row, 'CR');
            }elseif($letra == 'G'){
                $sheet_blank->setCellValue($letra.$row, 'TOTAL');
            }
        }

        $row++;
        $column = 'A';
        $sheet_blank->setCellValue($column.$row, 'SALARY PAYABLE A/C NAME');
        $column++;
        $sheet_blank->setCellValue($column.$row, 'BONUS PAYABLE  A/C NAME');
        $column++;
        $sheet_blank->setCellValue($column.$row, 'DATE');
        $column++;
        $sheet_blank->setCellValue($column.$row, 'SALARY A/C DE ('.$po_no.')');
        $column++;

        $sheet_blank->setCellValue($column.$row, 'ALLOWANCES DE ('.$po_no.')');
        $column++;
        $sheet_blank->setCellValue($column.$row, 'BONUS DE ('.$po_no.')');
        $column++;
        $sheet_blank->setCellValue($column.$row, 'Gross Salary');
        $column++;
        $sheet_blank->setCellValue($column.$row, 'Advance To Staff(Insurance)');
        $column++;
        $sheet_blank->setCellValue($column.$row, 'PF Payable ('.$loc_name.')');
        $column++;
        $sheet_blank->setCellValue($column.$row, 'PT Payable ('.$loc_name.')');
        $column++;
        $sheet_blank->setCellValue($column.$row, 'TDS Payable ('.$loc_name.')');
        $column++;
        $sheet_blank->setCellValue($column.$row, 'LWF Payable');
        $column++;
        $sheet_blank->setCellValue($column.$row, 'ESI Payable  ('.$loc_name.')');
        $column++;
        $sheet_blank->setCellValue($column.$row, 'BONUS PAYABLE A/C AMOUNT');
        $column++;
        $sheet_blank->getStyle($column.$row)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        $sheet_blank->setCellValue($column.$row, 'SALARY PAYABLE A/C AMOUNT');
        $column++;

        $sheet_blank->getRowDimension($row)->setRowHeight(42);
        foreach (range('C', 'O') as $letra) {            
            $sheet_blank->getColumnDimension($letra)->setWidth(15);
        }

        $sheet_blank->getColumnDimension($column)->setWidth(60);
        $sheet_blank->setCellValue( $column.$row, 'NARRATION');
        $column++;
        $sheet_blank->getStyle('C'.$row.':'.$column.$row)->getAlignment()->setWrapText(true);
        $sheet_blank->getStyle('C'.$row.':'.$column.$row)->applyFromArray($styleArray_workdone);

        $lastColumn = $sheet_blank->getHighestColumn();
        $sheet_blank->getStyle('A1:'.$lastColumn.$row)->applyFromArray($styleArray);

        $row++;

        
        $first_day_this_month = date('01-m-Y',strtotime($date)); // hard-coded '01' for first day
        $last_day_this_month = date('t-m-Y', strtotime($date));
        $tot_days=date('t',strtotime($date));
        $posting_history = PostingHistory::find()
                            //->where(['status' => 1])
                            ->andWhere(['plant_id' => $plant_id])
                            ->andWhere(['<=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`start_date`, '%d-%m-%Y'), '%Y-%m-%d')"), date('Y-m-d', strtotime($last_day_this_month))])
                            ->andWhere(['or',
                                          ['>=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`end_date`, '%d-%m-%Y'), '%Y-%m-%d')"), date('Y-m-d', strtotime($first_day_this_month)) ],
                                          ['end_date' => null]
                                      ])
                            //->andWhere(['between', 'end_date', $fromdate, $todate ])
                            ->andFilterWhere(['purchase_orderid' =>$po_id])
                            ->andFilterWhere(['section_id' =>$section_id])
                            ->orderBy(['papl_id' => SORT_ASC])->all();
        //print_r($posting_history);exit;
        foreach($posting_history as $emp){
            $ph=new PostingHistory();
            $employee_details = Enrollment::find()->select('id,papl_id,adhar_name,designation')->where(['papl_id'=>$emp->papl_id])->one();
            
            $sheet_blank->setCellValue('A' . $row, $employee_details['adhar_name'].' ('.$po_no.') SALARY');
            $sheet_blank->setCellValue('B' . $row, $employee_details['adhar_name'] .' ('.$po_no.') BONUS');
            $sheet_blank->setCellValue('C' . $row,  $last_day_this_month);
            
            
            $total_earning= MonthlySalary::find()->where(['papl_id'=>$emp->papl_id,'plant_id'=>$emp->plant_id,'month_year'=>date('F Y', strtotime($date))])->one();
            if($total_earning){ 
                $earning_detail=json_decode($total_earning['earning_detail'],true);
								$total_earning['deduction_detail']=json_decode($total_earning['deduction_detail'],true);
                //print_r($earning_detail);exit;
                //echo $total_earning['total_basic'];
                $total_basic=$total_earning['total_basic'];
                $bonus=round(($total_earning['total_basic']/100)*8.33);
                unset($total_earning['total_basic']);
                if(array_key_exists('ot_gd',$earning_detail)){
                    $total_basic+=$earning_detail['ot_gd'];
                    unset($earning_detail['ot_gd']);
                }
                if(array_key_exists('ot_bd',$earning_detail)){
                    $total_basic+=$earning_detail['ot_bd'];
                    unset($earning_detail['ot_bd']);
                }
                if(array_key_exists('ot_gs',$earning_detail)){
                    $total_basic+=$earning_detail['ot_gs'];
                    unset($earning_detail['ot_gs']);
                }
                if(array_key_exists('ot_bs',$earning_detail)){
                    $total_basic+=$earning_detail['ot_bs'];
                    unset($earning_detail['ot_bs']);
                }
                $sheet_blank->setCellValue('D'.$row, round($total_basic));
                
                
                $allowances=round(array_sum($earning_detail)+$total_earning['misc_earning']+$total_earning['other_ot_earning']);
               
                $sheet_blank->setCellValue('E'.$row, $allowances);
                
                
                
                $sheet_blank->setCellValue('F'.$row,$bonus);
                

                $total_gross=$total_basic+$allowances+$bonus;
                $sheet_blank->setCellValue('G'.$row, round($total_gross));
            
                
                $deduction_master=ArrayHelper::map(Deduction::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all(), 'id','attribute_name');
                foreach($deduction_master as $k=>$v){
                  if($v=='PF'){
                    $sheet_blank->setCellValue('I'.$row, $total_earning['deduction_detail'][$k]);
                  }elseif($v=='PT'){
                    $sheet_blank->setCellValue('J'.$row, $total_earning['deduction_detail'][$k]);
                  }elseif($v=='TDS'){
                    $sheet_blank->setCellValue('K'.$row, $total_earning['deduction_detail'][$k]);
                  }elseif($v=='Health Insurance'){
                    $sheet_blank->setCellValue('H'.$row, $total_earning['deduction_detail'][$k]);
                  }
                }
                $sheet_blank->setCellValue('L'.$row, round($total_earning['lwf_refund']));
                $sheet_blank->setCellValue('M'.$row, round($total_earning['esi_refund']));
                $sheet_blank->setCellValue('N'.$row, $bonus);
                $sheet_blank->setCellValue('O'.$row, round($total_earning['net_payble']));
                $sheet_blank->setCellValue('P'.$row, 'BEING SALARY BOOKED FOR MONTH OF '.date('M-Y', strtotime($date)));
                
            }
            $row++;
        }  
        
        $sheet_blank->setTitle('Salary Sheet');
        $writer = new Xlsx($spreadsheet);
        $writer->save($basepath . '/export/'.$filename);
        echo Yii::getAlias('@storageUrl')."/export/".$filename;exit;
	}
    

}
