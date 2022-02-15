<?php

namespace backend\controllers;
use Yii;
use common\models\Salary;
use common\models\Plant;
use common\models\SalaryMapping;
use common\models\DeductionMapping;
use common\models\Deduction;
use common\models\PostingHistory;
use common\models\SalarySearch;
use common\models\Location;
use common\models\Enrollment;
use common\models\MonthlySalary;
use common\models\Purchaseorder;
use common\models\Section;
use common\models\User;
use common\models\BankDetails;
use common\models\State;
use common\models\SalaryMappingLog;
use common\models\DailyEarning;
use common\models\Employee;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\helpers\acl;
use yii\data\Pagination;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends \yii\web\Controller
{
    public function actionProfessional_tax_statement()
    {
        if(acl::checkAcess(Yii::$app->user->identity->id,"Professional Tax Statement")){
            $model = new PostingHistory();
            $model['start_date']=date('F Y',strtotime("first day of previous month"));
            $posting_history=[];
            
            if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0])->all(), 'state_id', 'state_name');
            }else{
                $user_plant_id = PostingHistory::find()->where(['papl_id'=>Yii::$app->user->identity->username])
                                                         ->andWhere(['end_date' => null])->one();
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0,'state_id'=>$user_plant_id->state_id])->all(), 'state_id', 'state_name');
                
                $model['state_id']=$user_plant_id->state_id;
                $model['location_id']=$user_plant_id->location_id;
                $model['plant_id']=$user_plant_id->plant_id;
            }
            //print_r($posting_history);
            return $this->render('professional_tax_statement', [
                'model' => $model,
                'states' => $states,
                'posting_history'=>$posting_history,
                
            ]);
         }else{
             Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
             return $this->redirect(['/']);
 
          }
    }

    public function actionDownload_professional_tax_statement($plant_id,$po_id,$section_id,$date){
        if($po_id==='null'){
            $po_id='';
        }
        if($section_id==='null'){
            $section_id='';
        }
        $plant=Plant::findOne($plant_id);
        //print_r($po);exit;
        $first_day_this_month = date('01-m-Y',strtotime($date)); // hard-coded '01' for first day
        //$last_day_this_month  = date('Y-m-t');
        $last_day_this_month = date('t-m-Y', strtotime($date));
        $posting_history = PostingHistory::find()
                            ->andWhere(['plant_id' => $plant_id])
                            ->andWhere(['<=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`start_date`, '%d-%m-%Y'), '%Y-%m-%d')"), date('Y-m-d', strtotime($last_day_this_month))])
                            ->andWhere(['or',
                                          ['>=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`end_date`, '%d-%m-%Y'), '%Y-%m-%d')"), date('Y-m-d', strtotime($first_day_this_month)) ],
                                          ['end_date' => null]
                                      ])
                            ->andFilterWhere(['purchase_orderid' =>$po_id])
                            ->andFilterWhere(['section_id' =>$section_id])
                            ->orderBy(['papl_id' => SORT_ASC])->all();
        if($posting_history) {
            
            $basepath=Yii::getAlias('@storage');
            $filename="PT(06 Form VI STATEMENT -".$plant->plant_name.' -'.date('M_Y',strtotime($date)).".xlsx";
            
            $spreadsheet = new Spreadsheet();
            $sheet_blank = $spreadsheet->getActiveSheet();
            $styleArray = [
                'font' => [
                    'size'  =>  14,
                    'name'  =>  'Calibri',
                    //'color' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ];
            // for ($i = 1; $i <= 80; $i++) {
            //     $sheet_blank->getRowDimension($i)->setRowHeight(23.4, 'in');
            // }
            $sheet_blank->getRowDimension(6)->setRowHeight(40, 'in');

            $sheet_blank->getColumnDimension('B')->setWidth(35);
            $sheet_blank->getColumnDimension('C')->setWidth(17);
            $sheet_blank->getColumnDimension('D')->setWidth(17);
            $sheet_blank->getColumnDimension('E')->setWidth(17);

            $sheet_blank->getStyle('A1:E7')->getAlignment()->setWrapText(true);
            $sheet_blank->getStyle('A1:E7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet_blank->getStyle('A1:E7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet_blank->getStyle('A1:E7')->getFont()->setBold(true);
            $row = 1;
            
            $sheet_blank->mergeCells("A".$row.":E".$row)->setCellValue("A".$row,"FORM VI");
            $sheet_blank->getStyle("A".$row)->applyFromArray($styleArray);
            
            $row++;
            $sheet_blank->mergeCells("A".$row.":E".$row)->setCellValue("A".$row,"[ See Rule 14 (2) & (3)");
            $row++;
            $sheet_blank->mergeCells("A".$row.":E".$row)->setCellValue("A".$row,"SCHEDULE OF DEDUCTION");
            $row++;
            $sheet_blank->mergeCells("A".$row.":E".$row)->setCellValue("A".$row,"Schedule of Recovery of Tax on Profession for the Month of Sep 2021");
            $row++;
            $sheet_blank->mergeCells("A".$row.":E".$row)->setCellValue("A".$row,"* (To be accompained with the Salary Bill in Duplicate)");
            $row++;
            $sheet_blank->mergeCells("A".$row.":E".$row)->setCellValue("A".$row,"M/s. PRADHAN ASSOCIATES PRIVATE LIMITED, PLOT NO. 126/2262, LANE-1, KHANDAGIRI, BHUBANESWAR, KHURDHA, ODISHA, 751030 (Professional Tax Registration No: 21465605812, 23/12/2019)");
            
            $row++;
            $sheet_blank->setCellValue("A".$row,'SL.NO.');
            $sheet_blank->setCellValue("B".$row,'NAME OF THE EMPLOYEE');
            $sheet_blank->setCellValue("C".$row,'GROSS SALARY');
            $sheet_blank->setCellValue("D".$row,'TAX DUE');
            $sheet_blank->setCellValue("E".$row,'TAX ON PROFESSION DEDUCTED & PAID');
            
            $row++;
            $sl=1;
            foreach($posting_history as $emp){
                $sheet_blank->setCellValue('A'.$row, $sl);
                $sheet_blank->setCellValue('B'.$row, $emp->enrolement->adhar_name);
                $monthly_salary=$emp->getMonthlySalary($plant_id,$emp->papl_id,$date);
                if($monthly_salary){
                    $sheet_blank->setCellValue('C'.$row,round($monthly_salary->total_salary));
                    if($monthly_salary->total_salary>=25001){
                        $sheet_blank->setCellValue('D'.$row, 200);
                        $sheet_blank->setCellValue('E'.$row, 200);
                    }else{
                        if($monthly_salary->total_salary>=13304){
                            $sheet_blank->setCellValue('D'.$row, 125);
                            $sheet_blank->setCellValue('E'.$row, 125);
                        }else{
                            $sheet_blank->setCellValue('D'.$row, 0);
                            $sheet_blank->setCellValue('E'.$row, 0);
                        }
                    }
                }else{
                    $sheet_blank->setCellValue('C'.$row, 0);
                    $sheet_blank->setCellValue('D'.$row, 0);
                    $sheet_blank->setCellValue('E'.$row, 0);
                }
                $row++;
                $sl++;
            }
            $SUMRANGE = 'C8:C'.$row;
            $sheet_blank->setCellValue('C'.$row , '=SUM('.$SUMRANGE.')');
            $SUMRANGE = 'D8:D'.$row;
            $sheet_blank->setCellValue('D'.$row , '=SUM('.$SUMRANGE.')');
            $SUMRANGE = 'E8:E'.$row;
            $sheet_blank->setCellValue('E'.$row , '=SUM('.$SUMRANGE.')');

            $row++;
            $sheet_blank->mergeCells("A".$row.":E".$row)->setCellValue("A".$row,"Total Amount of Profession Tax Deducted (Rupees Thirtynine Thousand Four Hundred Seventyfive Only)");
            $sheet_blank->getStyle("A".$row.":E".$row)->getFont()->setBold(true);
            
            $row++;
            $sheet_blank->mergeCells("A".$row.":E".$row)->setCellValue("A".$row,"To be credited to Government Account by transfer credit under Head of Account 0028 - Other Taxes on Income and Expenditure - 107 - Taxes on Professions, Trades, Callings and Employments - 9913780 - Taxes on Professions");
            $sheet_blank->getRowDimension($row)->setRowHeight(30, 'in');
            $sheet_blank->getStyle("A".$row.":E".$row)->getAlignment()->setWrapText(true);


            $sheet_blank->setTitle('Professional Tax Statement');
            $writer = new Xlsx($spreadsheet);
            $writer->save($basepath . '/reports/'.$filename);
            echo Yii::getAlias('@storageUrl')."/reports/".$filename;exit;
        }else{
            echo false;exit;
        } 
    }

}
