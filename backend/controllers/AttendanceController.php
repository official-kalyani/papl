<?php

namespace backend\controllers;

use Yii;
use common\models\Attendance;
use common\models\AttendanceSearch;
use common\models\Plant;
use common\models\User;
use common\models\Location;
use common\models\State;
use common\models\Enrollment;
use common\models\PostingHistory;
use common\models\DailyEarning;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use common\helpers\acl;
//Yii::import('ext.runactions.components.ERunActions');

/**
 * AttendanceController implements the CRUD actions for Attendance model.
 */
class AttendanceController extends Controller
{
    /**
     * {@inheritdoc}
     */


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Attendance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AttendanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Attendance model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Attendance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(acl::checkAcess(Yii::$app->user->identity->id,"Daily Attendance")){
            $session = Yii::$app->session;

            $updated_details = Attendance::find()->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
            $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();

            $model = new Attendance();
            $posting_history = new PostingHistory();
            $attendance_history = new Attendance();
            $date = date('d-m-Y');
            $search_data = [];
            $state_id =$location = $plant = $purchase_order = $section = '';
            // echo "<pre>";print_r(Yii::$app->user->identity->role_id);die();
            $role_id=Yii::$app->user->identity->username;
            $role=Yii::$app->user->identity->role_id;
            $user_plant_id = PostingHistory::find()->where(['papl_id'=>$role_id])
                                                  ->andWhere(['end_date' => null])->one();

            if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0])->all(), 'state_id', 'state_name');
            }else{
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0,'state_id'=>$user_plant_id->state_id])->all(), 'state_id', 'state_name');
            }
            $savestatus = '';
            $search_result = 0;
            $state_short_code = $location_short_code = $plant_short_code = '';
            if (!empty(Yii::$app->request->post()['search'])) {

                $input = Yii::$app->request->post();

                if (!empty($input['Attendance']['state_id'])) {
                    $state_id = $input['Attendance']['state_id'];
                }else{
                    $state_id = '';
                }
                if (!empty($input['Attendance']['location_id'])) {
                    if ($role == 3) {
                        $location = $user_plant_id->location_id;
                    }else{
                         $location = $input['Attendance']['location_id'];
                    }
                   
                }else{
                    $location = '';
                }
                if (!empty($input['Attendance']['plant_id'])) {
                    $plant = $input['Attendance']['plant_id'];
                }else{
                    $plant = '';
                }
                if (!empty($input['Attendance']['purchase_orderid'])) {
                   $purchase_order = $input['Attendance']['purchase_orderid'];
                }else{
                    $purchase_order = '';
                }
                
               if (!empty($input['Attendance']['section_id'])) {
                   $section = $input['Attendance']['section_id'];
               }else{
                $section = '';
               }
                $state_short_code = State::find()->where(['state_id'=>$state_id])->one(); 
                $location_short_code = Location::find()->where(['location_id'=>$location])->one(); 
                $plant_short_code = Plant::find()->where(['plant_id'=>$plant])->one(); 
                $search_result = 1;    
                $date = $input['Attendance']['start_date'];
              
                $attendance_history = Attendance::find()->where(['date'=> date('Y-m-d', strtotime($date))])->one();
                // $posting_history = PostingHistory::find()->select(['papl_id'])->distinct()->where(['status' => 0])
                //     ->andWhere(['location_id' => $input['Attendance']['location_id']])
                //    ->andWhere(['or',
                //        ['end_date'=>null],['and',['>=', 'end_date',$date],['<=', 'start_date', $date]]
                //    ])
                   
                //     ->andFilterWhere(['purchase_orderid' => $purchase_order])
                //     ->andFilterWhere(['section_id' => $section])
                //     ->orderBy([
                //         'papl_id' => SORT_ASC
                //     ])->all();

                $posting_history = PostingHistory::find()->select(['papl_id'])->distinct()->where(['status' => 0])
                     ->andWhere(['plant_id' => $plant])
                    ->andWhere(['or',
                       ['end_date'=>null],['and',['>=', 'end_date',$date],['<=', 'start_date', $date]]
                   ])
                    ->andFilterWhere(['location_id' => $input['Attendance']['location_id']])
                    ->andFilterWhere(['purchase_orderid' => $purchase_order])
                    ->andFilterWhere(['section_id' =>$section])
                    ->orderBy([
                        'papl_id' => SORT_ASC
                    ])->all();

                

                 // echo '<pre>';print_r($posting_history);exit();
            }
            
            if (!empty(Yii::$app->request->post()['save'])) 
            {
                $input_attendance = Yii::$app->request->post();
                if (!empty(Yii::$app->request->post()['state_id'])) {
                    $state_id = Yii::$app->request->post()['state_id'];
                }
                if (!empty(Yii::$app->request->post()['location'])) {
                    if ($role == 3) {
                        $location = $user_plant_id->location_id;
                    }else{
                         $location = Yii::$app->request->post()['location'];
                    }
                   
                }
                if (!empty(Yii::$app->request->post()['plant'])) {
                    $plant = Yii::$app->request->post()['plant'];
                }
                if (!empty(Yii::$app->request->post()['purchase_order'])) {
                   $purchase_order = Yii::$app->request->post()['purchase_order'];
                }
            
               if (!empty(Yii::$app->request->post()['section'])) {
                   $section = Yii::$app->request->post()['section'];
               }
                
                $date = Yii::$app->request->post()['att_date'];
                
                $search_data = array($state_id,$location,$plant,$purchase_order,$section,$date);
                $savestatus = 1;
                $posting_history = PostingHistory::find()->select(['papl_id'])->distinct()->where(['status' => 0])
                     ->andWhere(['plant_id' => $plant])
                    ->andWhere(['or',
                       ['end_date'=>null],['and',['>=', 'end_date',$date],['<=', 'start_date', $date]]
                   ])
                    ->andFilterWhere(['location_id' => $location])
                    ->andFilterWhere(['purchase_orderid' => $purchase_order])
                    ->andFilterWhere(['section_id' =>$section])
                    ->orderBy([
                        'papl_id' => SORT_ASC
                    ])->all();
                 
                $userData = count($input_attendance['Attendance']);
                
                foreach ($input_attendance['Attendance'] as $attendance) {
                   // var_dump($attendance);
                    $Attendance_model =Attendance::find()->where(['papl_id'=>$attendance['papl_id'],'date'=> date('Y-m-d', strtotime($input_attendance['att_date']))])->one();
                    if (empty($Attendance_model)) {
                        $Attendance_model = new Attendance();
                        $Attendance_model->employee_name = $attendance['employee_name'];
                        $Attendance_model->papl_id = $attendance['papl_id'];
                        $Attendance_model->att = $attendance['att'];
                        $Attendance_model->nh = $attendance['nh'];
                        $Attendance_model->fh = $attendance['fh'];
                        $Attendance_model->ot = $attendance['ot'];
                        $Attendance_model->ot_type = $attendance['ot_type'];
                        $Attendance_model->att_type = 'gross';
                        $Attendance_model->nhfh_type = 'gross';
                        $Attendance_model->papl_id = $attendance['papl_id'];
                        $Attendance_model->remark = $attendance['remark'];
                        $Attendance_model->date = date('Y-m-d', strtotime($input_attendance['att_date']));
                        $Attendance_model->created_by = Yii::$app->user->identity->id;
                        $Attendance_model->updated_by = Yii::$app->user->identity->id;
                    }else{
                        $Attendance_model =Attendance::find()->where(['papl_id'=>$attendance['papl_id'],'date'=> date('Y-m-d', strtotime($input_attendance['att_date']))])->one();
                        $Attendance_model->att = $attendance['att'];
                        $Attendance_model->nh = $attendance['nh'];
                        $Attendance_model->fh = $attendance['fh'];
                        $Attendance_model->ot = $attendance['ot'];
                        $Attendance_model->ot_type = $attendance['ot_type'];
                        $Attendance_model->att_type = 'gross';
                        $Attendance_model->nhfh_type = 'gross';
                        $Attendance_model->papl_id = $attendance['papl_id'];
                        $Attendance_model->remark = $attendance['remark'];
                        $Attendance_model->updated_by = Yii::$app->user->identity->id;

                    }
                    // echo "<pre>";print_r($input_attendance['Attendance']);die(); 
                   
                    

                    if ($Attendance_model->save()) {
                        // var_dump($Attendance_model->getErrors());
                        // Yii::$app->session->setFlash( 'success', 'Attendance saved .' );
                        // $Attendance_model= new Attendance();
                        // $this->refresh();
                        // print_r($search_data);die();
                        
                        
                    } else {
                        echo $attendance['papl_id'];
                        var_dump($Attendance_model->getErrors());
                    }
                }
               return $this->redirect(['create', 'location' => $location,'plant' => $plant,'state_id' =>$state_id,'purchase_order' =>$purchase_order,'section' =>$section,'date'=>$date,'savestatus'=>$savestatus]);
            }



            return $this->render('create', [
                'model' => $model,
                //'locations' => $locations,
                'posting_historys' => $posting_history,
                'attendance_history' =>$attendance_history,
                'date' => $date,
                'search_data' => $search_data,
                'plant' => $plant,
                'purchase_order' => $purchase_order,
                'section' => $section,
                'location' => $location,
                'states' => $states,
                'state_id' => $state_id,
                'role_id' => $role_id,
                'role' => $role,
                'user_plant_id' => $user_plant_id,
                'savestatus' => $savestatus,
                'search_result' => $search_result,
                'search_result' => $search_result,
                'state_short_code' => $state_short_code,
                'location_short_code' => $location_short_code,
                'plant_short_code' => $plant_short_code,
                'updated_details_name' => $updated_details_name,
            ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }
    }

    /**
     * Updates an existing Attendance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Attendance model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Attendance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Attendance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Attendance::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

     //Attendance Sample Download
     public function actionAttendance_sample($plant_id,$po_id,$section_id,$date){
		//echo $plant_id;exit;
        if($po_id==='null'){
            $po_id='';
        }
        if($section_id==='null'){
            $section_id='';
        }
        $first_day_this_month = date('01-m-Y',strtotime($date)); // hard-coded '01' for first day
        $last_day_this_month = date('t-m-Y', strtotime($date));
        $basepath=Yii::getAlias('@storage');
        $plant=Plant::findOne($plant_id);
        $attendance_columns = ["Sl. No.","EmployeeID","NAME","Designation","Att","NH","FH","OT","OT_Ty"];
		$filename="attendance_".$plant->plant_name.date('dmYhs')."_sample.xls";
        $spreadsheet = new Spreadsheet();
        
        
        $sheet_blank = $spreadsheet->getActiveSheet()->freezePane('E2');
       
        $styleArray = [
            'font' => [
                //'bold'  =>  true,
                'size'  =>  13,
                'name'  =>  'Arial',
                'color' => ['rgb' => '4472C4']
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
        
        //$sheet_blank = $spreadsheet->createSheet(1); 
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
        
        
        $model = Enrollment::find()->select('id,papl_id,adhar_name,designation')->where(['papl_id'=>array_values($posting_history)])->all();
        
        $sheet_blank->getColumnDimension('B')->setWidth(16);
        $sheet_blank->getColumnDimension('C')->setWidth(22);
        $sheet_blank->getColumnDimension('D')->setWidth(20);
        $row = 2;
        $sheet_blank->setCellValue('A'.$row, 'Sl No.');
        $sheet_blank->setCellValue('B'.$row, 'EmployeeID');
        $sheet_blank->setCellValue('C'.$row, 'NAME');
        $sheet_blank->setCellValue('D'.$row, 'Designation');
        
        //$lastColumn = $sheet_blank->getHighestColumn();
        //$lastColumn=154;
        //$lastColumn++;
        $column = 'E';
        $last_c ='J';
        //for ($column = 'A'; $column != $lastColumn; $column++) {
            //$cell = $worksheet->getCell($column.$row);
            for($day=1;$day<=date('t',strtotime($date));$day++){
                $first_c=$column;
               
                $sheet_blank->setCellValue($column.$row, 'Att');
                $sheet_blank->getStyle($column.$row)->applyFromArray($styleArray_att);
                
                $column++;
                $sheet_blank->setCellValue($column.$row, 'NH');
                $column++;
                $sheet_blank->setCellValue($column.$row, 'FH');
                $column++;
                $sheet_blank->setCellValue($column.$row, 'OT');
                $column++;
                $sheet_blank->setCellValue($column.$row, 'OT_Ty');
                
                $sheet_blank->mergeCells($first_c."1:".$column."1")->setCellValue($first_c."1",$day."-".date('M',strtotime($date)));
                $column++;
            }
            
        //}
        $lastColumn = $sheet_blank->getHighestColumn();
        $sheet_blank->getStyle('A1:'.$lastColumn.$row)->applyFromArray($styleArray);
        $sheet_blank->getStyle('A2:'.$lastColumn.$row)->applyFromArray($styleArray);
        $sl=1;
        $rowCount = 3;
        foreach ($model as $element) {
            $sheet_blank->setCellValue('A' . $rowCount, $sl);
            $sheet_blank->setCellValue('B' . $rowCount, $element['papl_id']);
            $sheet_blank->setCellValue('C' . $rowCount, $element['adhar_name']);
            $sheet_blank->setCellValue('D' . $rowCount, $element['designation']);
           
            $rowCount++;
            $sl++;
        }
        $sheet_blank->setTitle('Attendance Sample');
        $writer = new Xlsx($spreadsheet);
        $writer->save($basepath . '/export/'.$filename);
        echo Yii::getAlias('@storageUrl')."/export/".$filename;exit;
	}
    
    public function actionAttendance_import1($plant_id){
         echo $plant_id;exit;

        //Yii::import('ext.runactions.components.ERunActions');
       echo Yii::$app->runAction('ext.runactions.components.ERunActions/runBackground');
        if (Yii::$app->runAction('ext.runactions.components.ERunActions/runBackground'))

        {
            echo 'Starting background task...';
           Yii::log('Starting background task...');


           //... time-consuming code here

           

           //Inform the user if 'hasFlash' is implemented in all views

           //Yii::app()->user->setFlash('runbackground','Process finished');

            echo 'Background task executed';
           Yii::log('Background task executed');

           Yii::app()->end(); 

        }

        echo "Hello11";exit;

           //the stuff you normally would do 

           //for example:

           //$this->render('index');
    }
    public function actionAttendance_import($plant_id){
        //echo $plant_id;exit;
        $model=new Attendance();
		$basepath=Yii::getAlias('@storage');
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			if ($model->load(Yii::$app->request->post())) {
				
				$model->import_file = UploadedFile::getInstance($model, 'import_file');
				$filename=UploadedFile::getInstance($model, 'import_file');
				//echo $filename;exit;
				if($filename) {
				  $model->import_file->saveAs($basepath . '/import/attendance/'.$filename);
				  $handle = fopen($basepath . '/import/attendance/'.$filename, "r");
				  $counter = 0;

                  $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

                    $spreadSheet = $Reader->load($basepath . '/import/attendance/'.$filename);
                    $excelSheet = $spreadSheet->getActiveSheet();
                    $spreadSheetAry = $excelSheet->toArray();
                    $sheetCount = count($spreadSheetAry);
                    //echo $sheetCount;
                    //print_r($spreadSheetAry);exit;
                    $att_date=[];
                    for ($i = 0; $i < $sheetCount; $i ++) {
                        
                        // if($i==0){
                        //     $date_row=$spreadSheetAry[$i];
                        //     $c=count($spreadSheetAry[$i]);
                        //     for ($j=4; $j<$c;$j=$j+5){
                        //         //echo $date_row[$j];
                        //         $att_date[]=$date_row[$j];
                        //     }
                        //     continue;
                        //   }
                        if($i>=2){
                            $date_row=$spreadSheetAry[0];
                            $data_row=$spreadSheetAry[$i];
                            $c=count($spreadSheetAry[$i]);
                            $k=0;
                            for ($j=4; $j<$c;$j=$j+5){
                                if($data_row[1]){
                                 if($data_row[$j] || $data_row[$j+1] || $data_row[$j+2]){
                                    $model = Attendance::find()->where(['papl_id'=>$data_row[1],'date'=>date('Y-m-d', strtotime($date_row[$j]))])->one();
                                    if(!$model){
                                        $model=new Attendance();
                                    }
                                    
                                    $model->papl_id=$data_row[1];
                                    $model->employee_name=$data_row[2];

                                    $model->att=$data_row[$j];
                                    if($data_row[$j]=="P" || $data_row[$j]=="HP")
                                        $model->att_type="B";
                                    else
                                        $model->att_type="G";
                                    $model->nh=strval($data_row[$j+1]);
                                    $model->fh=strval($data_row[$j+2]);
                                    //$model->nhfh_type=$data_row[$j+3];
                                    $model->ot=strval($data_row[$j+3]);
                                    $model->ot_type=$data_row[$j+4];
                                    $model->status=1;
                                    $model->date=date('Y-m-d', strtotime($date_row[$j]));
                                    if($model->save()){
                                        echo ('Attendance Uploaded Successfully for '.$model->papl_id.' of date: '.$model->date.' !<br \>');
                                    }else{
                                        print_r(json_encode($model->getErrors()));
                                    }
                                 }
                                    
                                    $k++;
                                }
                                
                            }
                            //print_r($model);
                        }
                    }
                    //print_r($att_date);
				}
                //echo ('Attendance Uploaded Successfully !');exit;
            }
        }
        exit;
    }
     //Daily Salary Calculation
     public function actionDaily_salary_calculation($plant_id,$po_id,$section_id,$date){
         //echo $plant_id;echo $po_id;$section_id;exit;
         if($po_id==='null'){
            $po_id='';
        }
        if($section_id==='null'){
            $section_id='';
        }
        $model = new Attendance();
        //if ($model->load(Yii::$app->request->post())) {
            
            $first_day_this_month = date('01-m-Y',strtotime($date)); // hard-coded '01' for first day
            
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
//                                 ->andWhere(['<=','start_date', $last_day_this_month ])
//                                 ->andWhere(['or',
//                                             ['<=','end_date', $first_day_this_month ],
//                                             ['end_date' => null]
//                                         ])
                                //->andWhere(['between', 'start_date', $first_day_this_month, $last_day_this_month ])
                                //->andWhere(['between', 'end_date', $fromdate, $todate ])
                                ->andFilterWhere(['purchase_orderid' => $po_id])
                                ->andFilterWhere(['section_id' => $section_id])
                                ->orderBy(['papl_id' => SORT_ASC])->all(), 'id','papl_id');

        
            //print_r($posting_history);exit;
            //$update=0;
            foreach($posting_history as $emp){
                for($i=1;$i<=$tot_days;$i++){
                    $ondate=date('Y-m-'.$i,strtotime($date));
                    $salary=$model->CalculateDailyEarning($emp,$ondate);
                    //$salary=$model->CalculateDailyEarning('PAPL000006',$ondate,$update);
                    //print_r($salary);
                    echo $salary.'<br />';
                }
            }      
        //}  
        exit;
     }   

     
    public function actionApprove()
    {
        if(acl::checkAcess(Yii::$app->user->identity->id,"Approve Attendance")){
           $model = new Attendance();
           $updated_details = Attendance::find()->where(['status'=>1])->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
           $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();

           $model['start_date']=date('F Y',strtotime("first day of previous month"));
            $posting_history = new PostingHistory();
            $attendance_for = new Attendance();
            // $attendance_history = new Attendance();
            // $date = date('d-m-Y');
            $search_result = 0;
            $date = '';
            $posting_history_arr = array();
            $attendance_list = [];
            $search_data = [];
            $state_id = $location = $plant = $purchase_order = $section = '';
            $role_id=Yii::$app->user->identity->username;
            $role=Yii::$app->user->identity->role_id;
            $user_plant_id = PostingHistory::find()->where(['papl_id'=>$role_id])
                                                  ->andWhere(['end_date' => null])->one();
            if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0])->all(), 'state_id', 'state_name');
            }else{
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0,'state_id'=>$user_plant_id->state_id])->all(), 'state_id', 'state_name');
            }
            $user_plant_id = PostingHistory::find()->where(['papl_id'=>$role_id])->one();
            $state_short_code = $location_short_code = $plant_short_code = '';
            if (!empty(Yii::$app->request->post()['search'])) {
                $input = Yii::$app->request->post();
                if (!empty($input['Attendance']['state_id'])) {
                    $state_id = $input['Attendance']['state_id'];
                }else{
                    $state_id = '';
                }
                if (!empty($input['Attendance']['location_id'])) {
                    $location = $input['Attendance']['location_id'];
                }else{
                    $location = '';
                }
                if (!empty($input['Attendance']['plant_id'])) {
                    $plant = $input['Attendance']['plant_id'];
                }else{
                    $plant = '';
                }
                if (!empty($input['Attendance']['purchase_orderid'])) {
                    $purchase_order = $input['Attendance']['purchase_orderid'];
                }else{
                   $purchase_order = ''; 
                }
                
               if (!empty($input['Attendance']['section_id'])) {
                $section = $input['Attendance']['section_id']; 
                }else{
                 $section ='';   
                }
                
                $state_short_code = State::find()->where(['state_id'=>$state_id])->one(); 
                $location_short_code = Location::find()->where(['location_id'=>$location])->one(); 
                $plant_short_code = Plant::find()->where(['plant_id'=>$plant])->one(); 
                $search_result = 1;

                

                 $date = $input['Attendance']['start_date'];
                 $model['start_date']=$input['Attendance']['start_date'];
                $first_day_this_month = date('01-m-Y',strtotime($date)); // hard-coded '01' for first day
                $last_day_this_month = date('t-m-Y', strtotime($date));
                
                 $days= (date('t', strtotime($date)));
                $fromdate=date('Y-m-d', strtotime($date));
                 $todate=date('Y-m-d', strtotime($days.$date));        
                
                $posting_history =  ArrayHelper::map(PostingHistory::find()
                                    //->where(['status' => 1])
                                    ->andWhere(['location_id' => $location])
                                    ->andWhere(['<=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`start_date`, '%d-%m-%Y'), '%Y-%m-%d')"), $last_day_this_month])
                                    ->andWhere(['or',
                                          ['<=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`end_date`, '%d-%m-%Y'), '%Y-%m-%d')"), $first_day_this_month ],
                                          ['end_date' => null]
                                      ])
                                    //->andWhere(['between', 'end_date', $fromdate, $todate ])
                                    ->andFilterWhere(['plant_id' => $plant])
                                    ->andFilterWhere(['purchase_orderid' => $purchase_order])
                                    ->andFilterWhere(['section_id' =>$section])
                                    ->orderBy(['papl_id' => SORT_ASC])->all(), 'id','papl_id');
                foreach($posting_history as $emp){
                $attendance_for = Attendance::find()->where(['papl_id' => $emp])
                                                    ->andWhere(['between', 'date', $fromdate, $todate ])
                                                    ->asArray()->all();
                 $attendance_list[$emp] =$attendance_for;
               }
                // echo '<pre>';print_r($attendance_list);exit();
               
            }

            if (!empty(Yii::$app->request->post()['approve'])) {
                $input_attendance = Yii::$app->request->post();
                 
                 $attendance_model =  Attendance::find()->all();  
                
                $userData = $input_attendance['att_date'];
                $empData = $input_attendance['emp_id'];
                $user_id = Yii::$app->user->identity->id;
                // $askedfor = $month." ".$year;
                $askedfor = date("Y-m", strtotime($userData));
                 
                 foreach ($empData as $key => $value) {
                    Attendance::updateAll(['status' => 1,'updated_by' => $user_id], ['and', ["DATE_FORMAT(date,'%Y-%m')" => $askedfor], ['like', 'papl_id', $value] ]);


                     
                     
                 }
                 
            }



            return $this->render('approve', [
                'model' => $model,
                //'locations' => $locations,
                'posting_historys' => $posting_history,
                'attendance_lists' => $attendance_list,
                'date' => $date,
                'search_data' => $search_data,
                'plant' => $plant,
                'purchase_order' => $purchase_order,
                'section' => $section,
                'location' => $location,
                'state_id' => $state_id,
                'states' => $states,
                'attendance_for' => $attendance_for,
                // 'attendance_history' => $attendance_history,
                'role_id' => $role_id,
                'role'=>$role,
                'user_plant_id'=>$user_plant_id,
                'state_short_code' => $state_short_code,
                'location_short_code' => $location_short_code,
                'plant_short_code' => $plant_short_code,
                'search_result' => $search_result,
                'updated_details_name' => $updated_details_name,
            ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        } 
    }
    public function actionGetattendanceedit(){

        if(Yii::$app->request->isAjax){    
              // $attendance=new Attendance();
              $empid = Yii::$app->request->post('empid');
              $type = Yii::$app->request->post('type');
              $date = Yii::$app->request->post('date');
              $data = Yii::$app->request->post('data');
              if (isset($empid)) {
                   $attendance=Attendance::find()->where(['papl_id'=>$empid,'date'=>$date])->one();
              }
             // $attendance=Attendance::find()->where(['papl_idn'=>$empid,'date'=>$date])->one();
            //  if (isset($empid)) {
            //     // $attendance =  Attendance::deleteAll(['papl_id'=>$empid,'date'=>$date]);
            //    $attendance=Attendance::find()->where(['papl_id'=>$empid,'date'=>$date])->one(); 
            //      if ($type == 'att') {
            //     $attendance->att=$data;
            //      }elseif ($type == 'att_type') {
            //         $attendance->att_type=$data;
            //      }elseif ($type == 'nhfh_type') {
            //         $attendance->nhfh_type=$data;
            //      }elseif ($type == 'nh') {
            //         $attendance->nh=$data;
            //      }elseif ($type == 'fh') {
            //          $attendance->fh=$data;
            //      }elseif ($type == 'ot') {
            //          $attendance->ot=$data;
            //      }elseif ($type == 'ot_type') {
            //          $attendance->ot_type=$data;
            //      }
             
            // }
            if (!$attendance) {
               $attendance=new Attendance();
               $attendance->papl_id=$empid;
               $attendance->date=$date;
               if ($type == 'att') {
                $attendance->att=$data;
                 }elseif ($type == 'att_type') {
                    $attendance->att_type=$data;
                 }elseif ($type == 'nhfh_type') {
                    $attendance->nhfh_type=$data;
                 }elseif ($type == 'nh') {
                    $attendance->nh=$data;
                 }elseif ($type == 'fh') {
                     $attendance->fh=$data;
                 }elseif ($type == 'ot') {
                     $attendance->ot=$data;
                 }elseif ($type == 'ot_type') {
                     $attendance->ot_type=$data;
                 }
                 
               
            }else{
                // $attendance=Attendance::find()->where(['papl_id'=>$empid,'date'=>$date])->one(); 
                 if ($type == 'att') {
                $attendance->att=$data;
                 }elseif ($type == 'att_type') {
                    $attendance->att_type=$data;
                 }elseif ($type == 'nhfh_type') {
                    $attendance->nhfh_type=$data;
                 }elseif ($type == 'nh') {
                    $attendance->nh=$data;
                 }elseif ($type == 'fh') {
                     $attendance->fh=$data;
                 }elseif ($type == 'ot') {
                     $attendance->ot=$data;
                 }elseif ($type == 'ot_type') {
                     $attendance->ot_type=$data;
                 }

            }
             // echo '<pre>';print_r($attendance);exit();
           
             // $attendance->status= 1;
             // $attendance->save();
             if(!$attendance->save()){
             print_r($attendance->getErrors());
             die("not saved!");
            }
             $resp=['status'=> 'Successfull'];
             echo json_encode($resp);
         }
    }
    public function actionApproved_attendance()
    {
        if(acl::checkAcess(Yii::$app->user->identity->id,"Approved Attendance")){
           $model = new Attendance();
           $model['start_date']=date('F Y',strtotime("first day of previous month"));
           $attendance_list=[];
           $search_result = 0;
           $state_short_code = $location_short_code = $plant_short_code = '';
           if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
               $states = ArrayHelper::map(State::find()->where(['is_delete' => 0])->all(), 'state_id', 'state_name');
           }else{
               $user_plant_id = PostingHistory::find()->where(['papl_id'=>Yii::$app->user->identity->username])
                                                        ->andWhere(['end_date' => null])->one();
               $states = ArrayHelper::map(State::find()->where(['is_delete' => 0,'state_id'=>$user_plant_id->state_id])->all(), 'state_id', 'state_name');
               //print_r($states);
               $model['state']=$user_plant_id->state_id;
               $model['location']=$user_plant_id->location_id;
               $model['plant_id']=$user_plant_id->plant_id;
           }
           //print_r($states);
           if (!empty(Yii::$app->request->post()['view'])) {
           

            $input = Yii::$app->request->post()['Attendance'];
            $model['state']=$input['state'];
            $model['location']=$input['location'];
            $model['plant_id']=$input['plant_id'];
            $model['start_date']=$input['start_date'];

             $state_short_code = State::find()->where(['state_id'=>$input['state']])->one(); 
            $location_short_code = Location::find()->where(['location_id'=>$input['location']])->one(); 
            $plant_short_code = Plant::find()->where(['plant_id'=>$input['plant_id']])->one(); 
            $search_result = 1; 

            if(isset($input['purchase_orderid']) && !empty($input['purchase_orderid'])){
                $model['purchase_orderid']=$input['purchase_orderid'];
                $po_id=$input['purchase_orderid'];
            }else{
                $po_id='';
            }
            if(isset($input['section_id']) && !empty($input['section_id'])){
                $model['section_id']=$input['section_id'];
                $section_id=$input['section_id'];
            }else{
                $section_id='';
            }
    
            $first_day_this_month = date('01-m-Y',strtotime($input['start_date'])); // hard-coded '01' for first day
           
            $last_day_this_month = date('t-m-Y', strtotime($input['start_date']));
            $posting_history = PostingHistory::find()
                                ->andWhere(['plant_id' => $input['plant_id']])
                                ->andWhere(['<=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`start_date`, '%d-%m-%Y'), '%Y-%m-%d')"), date('Y-m-d', strtotime($last_day_this_month))])
                                ->andWhere(['or',
                                              ['>=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`end_date`, '%d-%m-%Y'), '%Y-%m-%d')"), date('Y-m-d', strtotime($first_day_this_month)) ],
                                              ['end_date' => null]
                                          ])
                                ->andFilterWhere(['purchase_orderid' =>$po_id])
                                ->andFilterWhere(['section_id' =>$section_id])
                                ->orderBy(['papl_id' => SORT_ASC])->all();
            
            if($posting_history) {
                $fromdate=date('Y-m-d', strtotime($first_day_this_month));
                $todate=date('Y-m-d', strtotime($last_day_this_month));  
                foreach($posting_history as $emp){
                    $attendance_for = Attendance::find()->where(['papl_id' => $emp->papl_id])
                                                    ->andWhere(['between', 'date', $fromdate, $todate ])
                                                    ->orderBy(['date'=>SORT_ASC])
                                                    ->asArray()->all();
                    $attendance_list[$emp->papl_id] =$attendance_for;
                }
                //print_r($attendance_list);
            }else{
                echo "No employee found";
            }

           }
           //$date = date('d-m-Y');
           
           return $this->render('approved_attendance_report', [
               'model' => $model,
               'states' => $states,
               'attendance_list' => $attendance_list,
               'state_short_code' => $state_short_code,
               'location_short_code' => $location_short_code,
               'plant_short_code' => $plant_short_code,
               'search_result' => $search_result,
           ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);

         } 
    }
    public function actionDownload_approved_attendance_report($plant_id,$po_id,$section_id,$date){
        if($po_id==='null'){
            $po_id='';
        }
        if($section_id==='null'){
            $section_id='';
        }

        $first_day_this_month = date('01-m-Y',strtotime($date)); // hard-coded '01' for first day
       
        $last_day_this_month = date('t-m-Y', strtotime($date));
        $tot_days=date('t',strtotime($date));
        $posting_history = PostingHistory::find()
                            //->where(['status' => 0])
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
        if($posting_history) {
            $basepath=Yii::getAlias('@storage');
            $export_columns = ["Sl. No.","EmployeeID","NAME","Att","NH","FH","OT","OT_Ty"];
            $filename="approve_attendance_report_".$plant_id.date('dmYhs').".xlsx";
            
            $spreadsheet = new Spreadsheet();
            
            $askedfor = date("Y-m", strtotime($date));
        
            $sheet_blank = $spreadsheet->getActiveSheet()->freezePane('D2');

            $styleArray = [
                'font' => [
                
                    'size'  =>  13,
                    'name'  =>  'Arial',
                    'color' => ['rgb' => '4472C4']
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
            $sheet_blank->getColumnDimension('B')->setWidth(16);
            $sheet_blank->getColumnDimension('C')->setWidth(22);
            $row = 2;
            $sheet_blank->setCellValue('A'.$row, 'Sl No.');
            $sheet_blank->setCellValue('B'.$row, 'EmployeeID');
            $sheet_blank->setCellValue('C'.$row, 'NAME');
            $column = 'D';
            $last_c ='E';

            for($day=1;$day<=$tot_days;$day++){
                    $first_c=$column;
                
                    $sheet_blank->setCellValue($column.$row, 'Att');
                    $sheet_blank->getStyle($column.$row)->applyFromArray($styleArray_att);
                    
                    $column++;
                    $sheet_blank->setCellValue($column.$row, 'NH');
                    $column++;
                    $sheet_blank->setCellValue($column.$row, 'FH');
                    $column++;
                    $sheet_blank->setCellValue($column.$row, 'OT');
                    $column++;
                    $sheet_blank->setCellValue($column.$row, 'OT_Ty');
                    
                    $sheet_blank->mergeCells($first_c."1:".$column."1")->setCellValue($first_c."1",$day."-".date('M',strtotime($date)));
                    $column++;
            }
            $summary_clmn=$column;
            $sheet_blank->setCellValue($column.$row, 'Att');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Ext_Att');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Leave');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Coff');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'NH/FH');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'OT BD');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'OT GS');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'OT GD');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'OT BS');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Weekly Off');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Tot. Pay Days');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Total OT');
            
            $sheet_blank->mergeCells($summary_clmn."1:".$column."1")->setCellValue($summary_clmn."1",'SUMMARY');
            

            $lastColumn = $sheet_blank->getHighestColumn();//echo $lastColumn;exit;
            $sheet_blank->getStyle('A1:'.$lastColumn.$row)->applyFromArray($styleArray);
            $sheet_blank->getStyle('A2:'.$lastColumn.$row)->applyFromArray($styleArray);
            $sl=1;
            $row = 3;
            $attendance_list=[];
            $fromdate=date('Y-m-d', strtotime($first_day_this_month));
            $todate=date('Y-m-d', strtotime($last_day_this_month)); 
            foreach($posting_history as $emp){
                $emp_name =$emp->enrolement->adhar_name;
                $sheet_blank->setCellValue('A' . $row, $sl);
                $sheet_blank->setCellValue('B' . $row, $emp['papl_id']);
                $sheet_blank->setCellValue('C' . $row, $emp_name);
                $column="D";
            
            for($day=1;$day<=$tot_days;$day++){
                $attendance_for = Attendance::find()->where(['papl_id' => $emp->papl_id])
                                                ->andWhere(['date'=>date('Y-m-'.$day, strtotime($date))])
                                                ->one();
                $first_c=$column;
                
                $sheet_blank->setCellValue($column.$row, $attendance_for?$attendance_for['att']:'');
                $sheet_blank->getStyle($column.$row)->applyFromArray($styleArray_att);
                
                $column++;
                $sheet_blank->setCellValue($column.$row, $attendance_for?$attendance_for['nh']:'');
                $column++;
                $sheet_blank->setCellValue($column.$row, $attendance_for?$attendance_for['fh']:'');
                $column++;
                $sheet_blank->setCellValue($column.$row, $attendance_for?$attendance_for['ot']:'');
                $column++;
                $sheet_blank->setCellValue($column.$row, $attendance_for?$attendance_for['ot_type']:'');
                // echo $first_c.'-'.$column;
                 $sheet_blank->mergeCells($first_c."1:".$column."1")->setCellValue($first_c."1",$day."-".date('M',strtotime($date)));
                 $column++;
            }
            $work_done=CalculationController::actionWorkdays($emp->papl_id,$date); 
            unset($work_done['misc_att']);
            unset($work_done['el']);
            foreach($work_done as $k=>$v){
                $sheet_blank->setCellValue($column.$row, $v);
                $column++;
            }
            $row++;
            $sl++;
               
            }
            
            $sheet_blank->setTitle('Attendance');
        
            $writer = new Xlsx($spreadsheet);
            $writer->save($basepath . '/reports/'.$filename);
            echo Yii::getAlias('@storageUrl')."/reports/".$filename;exit;
        }else{
            echo false;exit;
        }                  
        
        
    }

}
