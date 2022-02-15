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
use yii\web\UploadedFile;
use common\helpers\acl;
use yii\data\Pagination;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * SalaryController implements the CRUD actions for Salary model.
 */
class SalaryController extends Controller
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
     * Lists all Salary models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SalarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $updated_details = Salary::find()->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
        $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'updated_details_name' => $updated_details_name,
        ]);
    }

    /**
     * Displays a single Salary model.
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
     * Creates a new Salary model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(acl::checkAcess(Yii::$app->user->identity->id,"Create Salary")){
            $model = new Salary();
            $searchModel = new SalarySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $model->created_by = Yii::$app->user->identity->id;
            $model->updated_by = Yii::$app->user->identity->id;
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
         return $this->render('create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
         }

    }

    /**
     * Updates an existing Salary model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->updated_by = Yii::$app->user->identity->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Salary model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

       
           $model->is_delete = 1;
           $model->updated_by = Yii::$app->user->identity->id;
           $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Salary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Salary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Salary::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionSalary_update(){
         
        if(acl::checkAcess(Yii::$app->user->identity->id,"Salary Update")){
            $model = new Salary();
            $search_result = 0;
            $plant= $purchase_order=$section=$location='';
            $state_short_code = $location_short_code = $plant_short_code = '';
            $user_plant_id = PostingHistory::find()->where(['papl_id'=>Yii::$app->user->identity->username])
                                                  ->andWhere(['end_date' => null])->one();
            $updated_details = SalaryMapping::find()->orderBy(['salary_mapping.updated_by' => SORT_DESC])->limit(1)->one();
            $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();
            
            if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0])->all(), 'state_id', 'state_name');
            }else{
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0,'state_id'=>$user_plant_id->state_id])->all(), 'state_id', 'state_name');
                //print_r($states);
                $model['state']=$user_plant_id->state_id;
                $model['location']=$user_plant_id->location_id;
                $model['plant_id']=$user_plant_id->plant_id;
            }
            $po_id=$section_id=$search_plant = $search_state = $search_po = $search_location= $search_section ='';

            $employeelists =[];
            $salarymodel = new Salary();
            $salary_mapping_model = new SalaryMapping();
            $deduction_mapping_model = new DeductionMapping();
            $salaryattr = Salary::find()->where(['is_delete' => 0])->all();
            $deduction_model = new Deduction();
            $deduction_attr = Deduction::find()->where(['is_delete' => 0])->all();
            
            if (!empty(Yii::$app->request->post()['search'])) {
                $input = Yii::$app->request->post();
                //print_r($input);
                if (isset($input['Salary'])) {
                    $search_location= $input['Salary']['location'] ?? '';
                    $search_po= $input['Salary']['purchase_orderid'] ?? '';
                    $search_state= $input['Salary']['state'] ?? '';
                    $search_section= $input['Salary']['section_id'] ?? '';
                    $search_plant = $input['Salary']['plant_id'] ?? '';

                    $state_short_code = State::find()->where(['state_id'=>$search_state])->one(); 
                    $location_short_code = Location::find()->where(['location_id'=>$search_location])->one(); 
                    $plant_short_code = Plant::find()->where(['plant_id'=>$search_plant])->one(); 
                    $search_result = 1; 
                }
                $model['state']=$input['Salary']['state'] ?? '';
                $model['location']=$input['Salary']['location'] ?? '';
               
                $model['plant_id']=$input['Salary']['plant_id'] ?? '';
                
                if(isset($input['Salary']['purchase_orderid']) && !empty($input['Salary']['purchase_orderid'])){
                    $model['purchase_orderid']=$input['Salary']['purchase_orderid'];
                    $po_id=$input['Salary']['purchase_orderid'];
                }
                if(isset($input['Salary']['section_id']) && !empty($input['Salary']['section_id'])){
                    $model['section_id']=$input['Salary']['section_id'];
                    $section_id=$input['Salary']['section_id'];
                }
                
                

                $employeelists= PostingHistory::find()->select('papl_id')->distinct('papl_id')
                                                                    ->andWhere(['plant_id' => $input['Salary']['plant_id']])
                                                                    ->andWhere(['is', 'end_date',new \yii\db\Expression('null')])
                                                                    ->andWhere(['status'=>0])
                                                                    ->andFilterWhere(['purchase_orderid' =>$po_id])
                                                                    ->andFilterWhere(['section_id' =>$section_id])
                                                                    ->orderBy(['papl_id' => SORT_ASC])->all();
            }
      
            return $this->render('salary_update', [
                'model' => $model,
                'states' => $states,
                'purchase_order' => $purchase_order,
                'section' => $section,
                'location' => $location,
                'employeelists' => $employeelists,
                'search_plant' => $search_plant,
                'search_location' => $search_location,
                'search_po' => $search_po,
                'search_state' => $search_state,
                'search_section' => $search_section,
                
                'salaryattr' => $salaryattr,
                'deduction_attr' => $deduction_attr,
                'deduction_mapping_model' => $deduction_mapping_model,
                'salary_mapping_model' => $salary_mapping_model,
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

    public function actionGetsalary_attr_update(){
        if (Yii::$app->request->isAjax) {
            $attval = Yii::$app->request->post('attval');
            $attid = Yii::$app->request->post('attid');
            $empid = Yii::$app->request->post('empid');
            $attname = Yii::$app->request->post('attname');
            $res=0;
            $result=[];
            if ($attname == 'salary_value') {
                if (isset($empid)) {
                    SalaryMapping::deleteAll(['papl_id'=>$empid,'salary_id'=> $attid]);
                }
                // $salary_mapping_model = SalaryMapping::find()->where(['papl_id' => $empid,'salary_id'=> $attid])->one();
                $salary_mapping_model = new SalaryMapping();
                if($salary_mapping_model != null){
                    $salary_mapping_model->papl_id  = $empid;
                    $salary_mapping_model->salary_id   = $attid;
                    $salary_mapping_model->amount   = $attval;
                    $salary_mapping_model->updated_by   = Yii::$app->user->identity->id;
                    if (!$salary_mapping_model->save()) {
                        $res = 0;
                        var_dump($salary_mapping_model->getErrors());
                        die("not saved!");   
                    }else{
                        $res = 1;
                    }
                    }
                       
                        $result['sal_status']=$res;
            }
            echo json_encode($result);
        if ($attname == 'deduction_value') {
            if (isset($empid)) {
                $deduction_mapping_model =  DeductionMapping::deleteAll(['papl_id'=>$empid,'deduction_id'=> $attid]);
                // $deduction_mapping_model =  DeductionMapping::find()->where(['papl_id' => $empid,'deduction_id'=> $attid])->one();
            }
            $deduction_mapping_model = new DeductionMapping();
            $deduction_mapping_model->papl_id  = $empid;
            $deduction_mapping_model->deduction_id   = $attid;
            $deduction_mapping_model->amount   = $attval;
            $deduction_mapping_model->updated_by   = Yii::$app->user->identity->id;
            if (!$deduction_mapping_model->save()) {
                $res = 0;
                var_dump($deduction_mapping_model->getErrors());
                die();
            }else{
                $res = 1;
            }
            $result['ded_status']=$res;
        }
        echo json_encode($result);
            
        }
    }

    public function actionSheet()
    {
        if(acl::checkAcess(Yii::$app->user->identity->id,"Salary Sheet")){
            $model = new Salary();
            $plant= $purchase_order=$section=$location='';
            $user_plant_id = PostingHistory::find()->where(['papl_id'=>Yii::$app->user->identity->username])
                                                  ->andWhere(['end_date' => null])->one();
            if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0])->all(), 'state_id', 'state_name');
            }else{
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0,'state_id'=>$user_plant_id->state_id])->all(), 'state_id', 'state_name');
                //print_r($states);
                $model['state']=$user_plant_id->state_id;
                $model['location']=$user_plant_id->location_id;
                $model['plant_id']=$user_plant_id->plant_id;
            }
            //print_r($states);
            
            $date = date('d-m-Y');

            return $this->render('salry_sheet', [
                'model' => $model,
                'states' => $states,
                'plant' => $plant,
                'purchase_order' => $purchase_order,
                'section' => $section,
                'location' => $location,
                'date' => $date,
            ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }
    }

    public function actionSalary_sheet($plant_id,$po_id,$section_id,$date){

        $updated_details = MonthlySalary::find()->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
        $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();
        if($po_id==='null'){
            $po_id='';
            $po='';
        }else{
            $po=PurchaseOrder::find()->where(['po_id'=>$po_id])->one();
        }
        if($section_id==='null'){
            $section_id='';
            $section='';
        }
        else{
            $section=Section::find()->where(['section_id'=>$section_id])->one();
        }
        $plant=Plant::find()->where(['plant_id'=>$plant_id])->one();
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
                            ->orderBy(['papl_id' => SORT_ASC]);
        //$query = Article::find()->where(['status' => 1]);
        $countQuery = clone $posting_history;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->setPageSize (10);
        $posting_history = $posting_history->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
        //$salary_master=ArrayHelper::map(Salary::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all(), 'id','attribute_name');
        $salary_master=Salary::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all();
        if(!empty(Yii::$app->request->post()['update'])) {
            $input = Yii::$app->request->post();
            //print_r($input);exit;
            foreach($input['att'] as $k=>$emp_salary){
                $monthly_salary= MonthlySalary::find()->where(['papl_id'=>$k,'plant_id'=>$plant_id,'month_year'=>date('F Y', strtotime($date))])->one();
                if($monthly_salary){
                    //print_r($monthly_salary);
                    //Calculate misc earning
                    if($emp_salary['misc_att'] || $emp_salary['other_ot_hour']){
                        $last_daily_gross=0;
                        $fromdate=date('Y-m-01', strtotime($date));
                        $todate=date('Y-m-t', strtotime($date));
                        
                        $last_daily_earnings=DailyEarning::find()
                                        ->andWhere(['between', 'ondate', $fromdate, $todate ])
                                        ->andWhere(['papl_id'=>$k])
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
                        }
                        $monthly_salary->misc_att=$emp_salary['misc_att'];
                        $monthly_salary->misc_earning=$emp_salary['misc_att']*$last_daily_gross;

                        $monthly_salary->other_ot_hour=$emp_salary['other_ot_hour'];
                        $monthly_salary->other_ot_earning=($last_daily_earnings->basic/4)*$emp_salary['other_ot_hour'];
                    }else{
                        $monthly_salary->misc_att=0;
                        $monthly_salary->misc_earning=0;

                        $monthly_salary->other_ot_hour=0;
                        $monthly_salary->other_ot_earning=0;
                    }
                    
                    $monthly_salary->total_basic=$input['salary'][$k]['total_basic'];
                    unset($input['salary'][$k]['total_basic']);
                    $monthly_salary->earning_detail=json_encode($input['salary'][$k]);

                    $monthly_salary->total_gross=$monthly_salary->total_basic+array_sum(array_values($input['salary'][$k]));
                    
                    $monthly_salary->total_salary=$monthly_salary->total_gross+$monthly_salary->misc_earning+$monthly_salary->other_ot_earning;


                    //Deduction Calculation
                    $deductions=[];
                    $deduction_amount=0;
                    $deduction_master=Deduction::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all();
                                            
                    $emp_deduction=ArrayHelper::map(DeductionMapping::find()->where(['papl_id'=>$k])->all(), 'deduction_id','amount');
                    foreach($deduction_master as $deduc){
                        //return $deduc->deduction;
                        if($deduc->attribute_name=="PT"){
                            if($monthly_salary->total_salary<=13303){
                                $deduction_amount+=0;
                                $deductions[$deduc->id]=0;
                            }elseif($monthly_salary->total_salary>=13304){
                                if($monthly_salary->total_salary>=25001){
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
                        if($is_esi && $monthly_salary->total_salary<=21000 && isset($emp_deduction[$deduc->id])){
                            if($deduc->type=="amount"){
                                $deduction_amount+=$emp_deduction[$deduc->id];
                                $deductions[$deduc->id]=$emp_deduction[$deduc->id];
                            }elseif($deduc->type=="percentage"){
                                $deduction_amount+=($emp_deduction[$deduc->id] / 100) * $monthly_salary->total_salary;
                                $deductions[$deduc->id]=($emp_deduction[$deduc->id] / 100) * $monthly_salary->total_salary;
                            }
                        }else{
                            $deduction_amount+=0;
                            $deductions[$deduc->id]=0;
                        }
                        }
            //           elseif($deduc->attribute_name=="PF"){
            //                 $deduction_amount+=round((12 / 100) * $monthly_salary->total_basic);
            //                 $deductions[$deduc->id]=round((12 / 100) * $monthly_salary->total_basic);
            //             }
                    else{
                            if(isset($emp_deduction[$deduc->id])){
                                if($deduc->type=="amount"){
                                    $deduction_amount+=$emp_deduction[$deduc->id];
                                    $deductions[$deduc->id]=$emp_deduction[$deduc->id];
                                }elseif($deduc->type=="percentage"){
                                    $deduction_amount+=($emp_deduction[$deduc->id] / 100) * $monthly_salary->total_basic;
                                    $deductions[$deduc->id]=($emp_deduction[$deduc->id] / 100) * $monthly_salary->total_basic;
                                }
                            }else{
                                $deduction_amount+=0;
                                $deductions[$deduc->id]=0;
                            }
                        }
                        
                    }
                    
                    $monthly_salary->deduction_detail=json_encode($deductions);
                    $monthly_salary->total_deduction=round($deduction_amount);
                    
                
                    $monthly_salary->net_payble=$monthly_salary->total_salary-$monthly_salary->total_deduction;//$emp_salary_details['net_payble'];
                    $monthly_salary->updated_by = Yii::$app->user->identity->id;
                    
                    $monthly_salary->total_payble=$monthly_salary->net_payble+$monthly_salary->lwf_refund+$monthly_salary->esi_refund;//$emp_salary_details['total_payble'];
                    
                    
                    $monthly_salary->save();
                    
                    

                    
                    //$monthly_salary->misc_att=$emp_salary['misc_att'];
                    //$monthly_salary->save();
                    //$emp_salary_details=CalculationController::actionCalculate_salary($k,$date,$plant_id);
                    //$monthly_salary->misc_earning=$emp_salary_details['misc_earning'];
                    // $monthly_salary->deduction_detail=json_encode($emp_salary_details['deductions']);
                    // $daily_gross=$monthly_salary->misc_earning/$monthly_salary->misc_att;
                    // $monthly_salary->misc_att=$emp_salary['misc_att'];
                    // $monthly_salary->total_salary=$monthly_salary->total_gross-$monthly_salary->misc_earning;
                    // $monthly_salary->misc_earning=$emp_salary['misc_att']*$emp_salary_details['daily_gross'];
                    // $monthly_salary->total_deduction=$emp_salary_details['deduction_amount'];
                    // $monthly_salary->net_payble=$monthly_salary->total_salary-$monthly_salary->total_deduction;
                    // $monthly_salary->total_payble=$monthly_salary->net_payble+$monthly_salary->lwf_refund+$monthly_salary->esi_refund;
                    //$monthly_salary->earning_detail=json_encode($emp_salary_details['earnings']);
                    //$monthly_salary->total_basic=$emp_salary_details['total_basic'];
                    //$monthly_salary->total_gross=$emp_salary_details['total_gross'];
                    //$monthly_salary->misc_att=0;
                    
                    //$monthly_salary->total_salary=$emp_salary_details['total_salary'];
                    //print_r($input['salary'][$k]);
                    //exit;
                }
            }
            Yii::$app->session->setFlash('success', 'Monthly Salary Updated Successfully');
            //exit;
        }
        $model = new MonthlySalary();
        return $this->render('salry_sheet_details', [
            'posting_history' => $posting_history,
            'date'=>$date,
            'pages' => $pages,
            'salary_master'=>$salary_master,
            'model'=>$model,
            'plant'=>$plant,
            'po'=>$po_id,
            'section'=>$section_id,
            'sections'=>$section,
            'pos'=>$po,
            'updated_details_name'=>$updated_details_name,
            
        ]);
    }
    public function actionSalary_slip($plant_id,$po_id,$section_id,$date){
        $this->layout = 'salarylayout.php';
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
        //$salary_master=ArrayHelper::map(Salary::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all(), 'id','attribute_name');
        
        return $this->render('salary_slip', [
            'posting_history' => $posting_history,
            'date'=>$date,
            //'salary_master'=>$salary_master,
        ]);
    }
    
    public function actionBank_debit_report($plant_id,$po_id,$section_id,$date){
       
        if($section_id==='null'){
            $section_id='';
        }
        $basepath=Yii::getAlias('@storage');
        $plant=Plant::findOne($plant_id);
        $po=Purchaseorder::findOne($po_id);
        $po_no="";
        if($po){
            $po_no=substr($po->po_number, -4);
        }
		$filename="bank_debit_report_".$plant->plant_name."_".date('F Y', strtotime($date)).".xlsx";
        if(file_exists(Yii::getAlias('@storage')."/export/".$filename))
            unlink(Yii::getAlias('@storage')."/export/".$filename);
        $spreadsheet = new Spreadsheet();
        $sheet_blank = $spreadsheet->getActiveSheet()->freezePane('A1');
        $sheet_blank->setCellValue('A1', 'Sl No.');
        $sheet_blank->setCellValue('B1', 'Type of Transfer');
        $sheet_blank->setCellValue('C1', 'Ifsc Code');
        $sheet_blank->setCellValue('D1', 'Account No');
        $sheet_blank->setCellValue('E1', 'Name As Per Passbook');
        $sheet_blank->setCellValue('F1', 'Net Amount');
        $sheet_blank->setCellValue('G1', 'Name As Per AADHAR');
        $sheet_blank->setCellValue('H1', 'Net Amount');
        foreach (range('A','H') as $col) {
            $sheet_blank->getColumnDimension($col)->setAutoSize(true);
        }
        $sl=1;
        $row=2;

        $first_day_this_month = date('01-m-Y',strtotime($date)); // hard-coded '01' for first day
        $last_day_this_month = date('t-m-Y', strtotime($date));
        $tot_days=date('t',strtotime($date));
        $posting_history = PostingHistory::find()
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

        foreach($posting_history as $emp){
            $sheet_blank->setCellValue('A' . $row, $sl);
            $employee_details = Enrollment::find()->select(['enrolement_id','adhar_name'])->where(['papl_id'=>$emp->papl_id])->one();
            $bank_details = BankDetails::find()->select(['enrolement_id','name_passbook','IFSC','bank_account_number','name_bank'])->where(['enrolement_id'=>$employee_details['enrolement_id']])->one();
            if($bank_details){
                if($bank_details['name_bank']=='CANARA BANK'){
                    $sheet_blank->setCellValue('B' . $row, 'INTERNAL TRANSFER');
                }else{
                    $sheet_blank->setCellValue('B' . $row, 'NEFT TRANSFER');
                }
                $sheet_blank->setCellValue('C' . $row, $bank_details['IFSC']);
                $sheet_blank->setCellValue('D' . $row, $bank_details['bank_account_number']);
                $sheet_blank->setCellValue('E' . $row, $bank_details['name_passbook']);
            }
            
            $sheet_blank->setCellValue('G' . $row, $employee_details['adhar_name'] .' ('.$po_no.') SALARY');
            $total_earning= MonthlySalary::find()->where(['papl_id'=>$emp->papl_id,'plant_id'=>$emp->plant_id,'month_year'=>date('F Y', strtotime($date))])->one();
            if($total_earning){ 
                $sheet_blank->setCellValue('F'.$row, round($total_earning['net_payble']));
                $sheet_blank->setCellValue('H'.$row, round($total_earning['net_payble']));
            }
            $row++;
            $sl++;
        }

        $sheet_blank->setTitle('Bank Debit Report');
        $writer = new Xlsx($spreadsheet);
        $writer->save($basepath . '/export/'.$filename);
        echo Yii::getAlias('@storageUrl')."/export/".$filename;exit;
    }

    public function actionSalary_postingxml($plant_id,$po_id,$section_id,$date){
        
        if($section_id==='null'){
            $section_id='';
        }

        $basepath=Yii::getAlias('@storage');
        $plant=Plant::findOne($plant_id);
        $po=Purchaseorder::findOne($po_id);
        $po_no="";
        if($po){
            $po_no=substr($po->po_number, -4);
        }
        $loc_name=$plant->location->location_name;
		$filename="posting_salary_XML_".$plant->plant_name."_".date('F Y', strtotime($date)).".xml";
        if(file_exists(Yii::getAlias('@storage')."/export/".$filename))
            unlink(Yii::getAlias('@storage')."/export/".$filename);
        
        $envelope = new \SimpleXMLElement('<ENVELOPE/>');
        $header=$envelope->addChild('HEADER');
        $header->addChild('TALLYREQUEST','Import Data');
        $body=$envelope->addChild('BODY');
        $importdata=$body->addChild('IMPORTDATA');
        $requestdesc=$importdata->addChild('REQUESTDESC');
        $requestdesc->addChild('REPORTNAME','All Masters');
        $staticvar=$requestdesc->addChild('STATICVARIABLES');
        $staticvar->addChild('SVCURRENTCOMPANY','NIKASH');

        $requestdata=$importdata->addChild('REQUESTDATA');

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
                            ->andFilterWhere(['purchase_orderid' =>$po_id])
                            ->andFilterWhere(['section_id' =>$section_id])
                            ->orderBy(['papl_id' => SORT_ASC])->all();
        //print_r($posting_history);exit;
        foreach($posting_history as $emp){
            $employee_details = Enrollment::find()->select('adhar_name')->where(['papl_id'=>$emp->papl_id])->one();
            
            $tallymsg=$requestdata->addChild('TALLYMESSAGE');
            $tallymsg->addAttribute("xmlns:xmlns:UDF", 'TallyUDF');
            $voucher=$tallymsg->addChild('VOUCHER');
            
            $voucher->addAttribute("ACTION", 'Create');
            $voucher->addAttribute("VCHTYPE", 'Journal');
            $voucher->addChild('VOUCHERTYPENAME','Journal');
            $voucher->addChild('DATE',date('Ymd',strtotime($last_day_this_month)));
            $voucher->addChild('VOUCHERTYPENAME','Journal');
            $voucher->addChild('NARRATION','BEING SALARY BOOKED FOR MONTH OF '.strtoupper(date('M-Y', strtotime($date))));

            $all_ledger=$voucher->addChild('ALLLEDGERENTRIES.LIST');
            $all_ledger->addChild('LEDGERNAME','SALARY A/C DE ('.$po_no.')');
            $all_ledger->addChild('ISDEEMEDPOSITIVE','Yes');
            $all_ledger->addChild('LEDGERFROMITEM','No');
            $all_ledger->addChild('REMOVEZEROENTRIES','No');
            $all_ledger->addChild('ISPARTYLEDGER','No');
            $all_ledger->addChild('ISLASTDEEMEDPOSITIVE','Yes');
            

            $total_earning= MonthlySalary::find()->where(['papl_id'=>$emp->papl_id,'plant_id'=>$emp->plant_id,'month_year'=>date('F Y', strtotime($date))])->one();
            if($total_earning){ 
                //print_r($total_earning);
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
                //$sheet_blank->setCellValue('D'.$row, round($total_basic));
                $all_ledger->addChild('AMOUNT',-round($total_basic));

                $all_ledger=$voucher->addChild('ALLLEDGERENTRIES.LIST');
                $all_ledger->addChild('LEDGERNAME','ALLOWANCES DE ('.$po_no.')');
                $all_ledger->addChild('ISDEEMEDPOSITIVE','Yes');
                $all_ledger->addChild('LEDGERFROMITEM','No');
                $all_ledger->addChild('REMOVEZEROENTRIES','No');
                $all_ledger->addChild('ISPARTYLEDGER','No');
                $all_ledger->addChild('ISLASTDEEMEDPOSITIVE','Yes');
                $allowances=round(array_sum($earning_detail)+$total_earning['misc_earning']+$total_earning['other_ot_earning']);
                $all_ledger->addChild('AMOUNT',-$allowances);

                $all_ledger=$voucher->addChild('ALLLEDGERENTRIES.LIST');
                $all_ledger->addChild('LEDGERNAME','BONUS DE ('.$po_no.')');
                $all_ledger->addChild('ISDEEMEDPOSITIVE','Yes');
                $all_ledger->addChild('LEDGERFROMITEM','No');
                $all_ledger->addChild('REMOVEZEROENTRIES','No');
                $all_ledger->addChild('ISPARTYLEDGER','No');
                $all_ledger->addChild('ISLASTDEEMEDPOSITIVE','Yes');
                $all_ledger->addChild('AMOUNT',-$bonus);

                

                $deduction_master=ArrayHelper::map(Deduction::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all(), 'id','attribute_name');
                foreach($deduction_master as $k=>$v){
                    
                  if($v=='PF'){
                    $all_ledger=$voucher->addChild('ALLLEDGERENTRIES.LIST');
                    $all_ledger->addChild('LEDGERNAME','PF Payable ('.$loc_name.')');
                    $all_ledger->addChild('ISDEEMEDPOSITIVE','No');
                    $all_ledger->addChild('LEDGERFROMITEM','No');
                    $all_ledger->addChild('REMOVEZEROENTRIES','No');
                    $all_ledger->addChild('ISPARTYLEDGER','No');
                    $all_ledger->addChild('ISLASTDEEMEDPOSITIVE','No');
                    $all_ledger->addChild('AMOUNT',$total_earning['deduction_detail'][$k]);
                  }elseif($v=='PT'){
                    $all_ledger=$voucher->addChild('ALLLEDGERENTRIES.LIST');
                    $all_ledger->addChild('LEDGERNAME','PT Payable ('.$loc_name.')');
                    $all_ledger->addChild('ISDEEMEDPOSITIVE','No');
                    $all_ledger->addChild('LEDGERFROMITEM','No');
                    $all_ledger->addChild('REMOVEZEROENTRIES','No');
                    $all_ledger->addChild('ISPARTYLEDGER','No');
                    $all_ledger->addChild('ISLASTDEEMEDPOSITIVE','No');
                    $all_ledger->addChild('AMOUNT',$total_earning['deduction_detail'][$k]);
                  }elseif($v=='TDS'){
                    $all_ledger=$voucher->addChild('ALLLEDGERENTRIES.LIST');
                    $all_ledger->addChild('LEDGERNAME','TDS Payable ('.$loc_name.')');
                    $all_ledger->addChild('ISDEEMEDPOSITIVE','No');
                    $all_ledger->addChild('LEDGERFROMITEM','No');
                    $all_ledger->addChild('REMOVEZEROENTRIES','No');
                    $all_ledger->addChild('ISPARTYLEDGER','No');
                    $all_ledger->addChild('ISLASTDEEMEDPOSITIVE','No');
                    $all_ledger->addChild('AMOUNT',$total_earning['deduction_detail'][$k]);
                  }elseif($v=='Health Insurance'){
                    $all_ledger=$voucher->addChild('ALLLEDGERENTRIES.LIST');
                    $all_ledger->addChild('LEDGERNAME','HDFC Insurance Payable');
                    $all_ledger->addChild('ISDEEMEDPOSITIVE','No');
                    $all_ledger->addChild('LEDGERFROMITEM','No');
                    $all_ledger->addChild('REMOVEZEROENTRIES','No');
                    $all_ledger->addChild('ISPARTYLEDGER','No');
                    $all_ledger->addChild('ISLASTDEEMEDPOSITIVE','No');
                    $all_ledger->addChild('AMOUNT',$total_earning['deduction_detail'][$k]);
                  }
                    
                }
                $all_ledger=$voucher->addChild('ALLLEDGERENTRIES.LIST');
                $all_ledger->addChild('LEDGERNAME','ESI Payable ('.$loc_name.')');
                $all_ledger->addChild('ISDEEMEDPOSITIVE','No');
                $all_ledger->addChild('LEDGERFROMITEM','No');
                $all_ledger->addChild('REMOVEZEROENTRIES','No');
                $all_ledger->addChild('ISPARTYLEDGER','No');
                $all_ledger->addChild('ISLASTDEEMEDPOSITIVE','No');
                $all_ledger->addChild('AMOUNT',round($total_earning['esi_refund']));

                $all_ledger=$voucher->addChild('ALLLEDGERENTRIES.LIST');
                $all_ledger->addChild('LEDGERNAME','LWF Payable');
                $all_ledger->addChild('ISDEEMEDPOSITIVE','No');
                $all_ledger->addChild('LEDGERFROMITEM','No');
                $all_ledger->addChild('REMOVEZEROENTRIES','No');
                $all_ledger->addChild('ISPARTYLEDGER','No');
                $all_ledger->addChild('ISLASTDEEMEDPOSITIVE','No');
                $all_ledger->addChild('AMOUNT',round($total_earning['lwf_refund']));

                $all_ledger=$voucher->addChild('ALLLEDGERENTRIES.LIST');
                $all_ledger->addChild('LEDGERNAME',$employee_details['adhar_name'] .' ('.$po_no.') BONUS');
                $all_ledger->addChild('ISDEEMEDPOSITIVE','No');
                $all_ledger->addChild('LEDGERFROMITEM','No');
                $all_ledger->addChild('REMOVEZEROENTRIES','No');
                $all_ledger->addChild('ISPARTYLEDGER','No');
                $all_ledger->addChild('ISLASTDEEMEDPOSITIVE','No');
                $all_ledger->addChild('AMOUNT',$bonus);

                $all_ledger=$voucher->addChild('ALLLEDGERENTRIES.LIST');
                $all_ledger->addChild('LEDGERNAME',$employee_details['adhar_name'] .' ('.$po_no.') SALARY');
                $all_ledger->addChild('ISDEEMEDPOSITIVE','No');
                $all_ledger->addChild('LEDGERFROMITEM','No');
                $all_ledger->addChild('REMOVEZEROENTRIES','No');
                $all_ledger->addChild('ISPARTYLEDGER','No');
                $all_ledger->addChild('ISLASTDEEMEDPOSITIVE','No');
                $all_ledger->addChild('AMOUNT',round($total_earning['net_payble']));
            }
        }
        // header('Content-type: text/xml');
        // header('Content-Disposition: attachment; filename="text.xml"');
        header("Content-type: text/xml");
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        echo $envelope->asXML();
        exit();
        //echo $envelope;exit;

        //Header('Content-type: text/xml');
        
        // Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        // Yii::$app->response->headers->add('Content-Type', 'text/xml');
       
        // return $this->renderPartial('posting_salaryxml',["xml"=>$envelope->asXML()]);
    }
    //Report of Posting
    public function actionIncreament_report(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Increment Report")){
            $model = new PostingHistory();
            $model['start_date']=date('F Y');
            $posting_history=[];
            $all_employee=ArrayHelper::map(Employee::find()
                                    ->where(['is_exit' => 0])
                                    ->orderBy([
                                          'papl_id' => SORT_ASC,
                                        ])
                                    ->with('papl')
                                    ->all(), 'papl_id', function ($model) {
                                                return $model['papl']['adhar_name'] . ' - ' . $model->papl_id;});
            if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0])->all(), 'state_id', 'state_name');
            }else{
                $user_plant_id = PostingHistory::find()->where(['papl_id'=>Yii::$app->user->identity->username])
                                                         ->andWhere(['end_date' => null])->one();
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0,'state_id'=>$user_plant_id->state_id])->all(), 'state_id', 'state_name');
                //print_r($states);
                $model['state_id']=$user_plant_id->state_id;
                $model['location_id']=$user_plant_id->location_id;
                $model['plant_id']=$user_plant_id->plant_id;
            }
            //print_r($states);
            $salary_master=$increment_dates=[];
            if (!empty(Yii::$app->request->post())) {
                $salary_master=Salary::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all();
                $input = Yii::$app->request->post()['PostingHistory'];
                if($input['papl_id']){
                    $increment_dates=SalaryMappingLog::find()->select(['papl_id','updated_at'])->distinct()
                                                            ->where(['papl_id'=>$input['papl_id']])
                                                            ->orderBy(['updated_at' => SORT_DESC])->all();
                    //print_r($increment_dates);exit;
                    // $increment_history=SalaryMappingLog::find()
                    //                                 ->where(['papl_id'=>$input['papl_id']])
                    //                                 ->orderBy(['updated_at' => SORT_DESC])->all();
                    //print_r($increment_history);
                    $posting_history = PostingHistory::find()
                                                ->where(['papl_id' => $input['papl_id']])
                                                ->orderBy(['start_date' => SORT_ASC])->all();
                    $model['papl_id']=$input['papl_id'];
                }else{
                    $model['state_id']=$input['state_id'];
                    $model['location_id']=$input['location_id'];
                    $model['plant_id']=$input['plant_id'];
                    
        
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
                
                    $posting_history = PostingHistory::find()
                                                ->andWhere(['plant_id' => $input['plant_id']])
                                                ->andWhere(['status'=>0])
                                                ->andWhere(['end_date' => null])
                                                ->andFilterWhere(['purchase_orderid' =>$po_id])
                                                ->andFilterWhere(['section_id' =>$section_id])
                                                ->orderBy(['papl_id' => SORT_ASC])->all();
                    
                }
            }
            //$date = date('d-m-Y');
            //print_r($posting_history);
            return $this->render('increament_report', [
                'model' => $model,
                'states' => $states,
                'posting_history'=>$posting_history,
                'increment_dates'=>$increment_dates,
                'all_employee' => $all_employee,
                'salary_master' => $salary_master,
            ]);
         }else{
             Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
             return $this->redirect(['/']);
 
          }
    }
    public function actionDownload_increament_report($plant_id,$po_id,$section_id,$papl_id){
        if($po_id==='null'){
            $po_id='';
        }
        if($section_id==='null'){
            $section_id='';
        }
        if($papl_id){
            $posting_history = PostingHistory::find()
                                            ->where(['papl_id' => $papl_id])
                                            ->andWhere(['status'=>0])
                                            ->orderBy(['start_date' => SORT_ASC])->all();
        }else{
            $posting_history = PostingHistory::find()
                                    ->andWhere(['plant_id' => $plant_id])
                                    ->andWhere(['status'=>0])
                                    ->andWhere(['end_date' => null])
                                    ->andFilterWhere(['purchase_orderid' =>$po_id])
                                    ->andFilterWhere(['section_id' =>$section_id])
                                    ->orderBy(['papl_id' => SORT_ASC])->all();
        }
        if($posting_history) {
            $basepath=Yii::getAlias('@storage');
            $filename="Increment_report_".$plant_id.date('dmYhs').".xlsx";
            
            $spreadsheet = new Spreadsheet();
            $sheet_blank = $spreadsheet->getActiveSheet()->freezePane('E2');

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
            
            $sheet_blank->getColumnDimension('B')->setWidth(18);
            $sheet_blank->getColumnDimension('C')->setWidth(25);
            $sheet_blank->getColumnDimension('D')->setAutoSize(TRUE);
            $row = 2;
            $sheet_blank->setCellValue('A'.$row, 'Sl No.');
            $sheet_blank->setCellValue('B'.$row, 'EmployeeID');
            $sheet_blank->setCellValue('C'.$row, 'NAME');
            $sheet_blank->setCellValue('D'.$row, 'Section');
            $column = 'E';
            $sheet_blank->setCellValue( $column.$row, 'Basic');
            $column++;
            $salary_master=Salary::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all();
            foreach($salary_master as $sal){
                if($sal->attribute_name !="Basic"){
                    $sheet_blank->setCellValue($column.$row, $sal->attribute_name);
                    $column++;
                }
            }
            $sheet_blank->setCellValue($column.$row, 'Total Unit Rate/Day');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Monthly Gross Salary');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Last Increment Amt.');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Last Increment Month');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Due Month');
            $column++;
            //$lastColumn = $sheet_blank->getHighestColumn();//echo $lastColumn;exit;
            $sheet_blank->getStyle('A1:'.$column.$row)->applyFromArray($styleArray);
            $sheet_blank->getStyle('A1:'.$column.$row)->getAlignment()->setWrapText(true);
            for ($i='E';$i!=$column;$i++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($i)->setWidth(10);
            }
            $row++;
            $sl=1;
            foreach($posting_history as $emp){
                $sheet_blank->setCellValue('A' . $row, $sl);
                $sheet_blank->setCellValue('B' . $row, $emp['papl_id']);
                $sheet_blank->setCellValue('C' . $row, $emp->enrolement->adhar_name);
                $sheet_blank->setCellValue('D' . $row, $emp->section->section_name);
                $column="E";
                $set_salary= ArrayHelper::map(SalaryMapping::find()->where(['papl_id'=>$emp['papl_id']])->all(), 'salary_id','amount');
                foreach($salary_master as $sal){
                if($sal->attribute_name=="Basic"){
                    if(isset($set_salary[$sal->id])){
                        $base_salary=$set_salary[$sal->id];
                        $sheet_blank->setCellValue($column.$row, $set_salary[$sal->id]);
                        $column++;
                        break;
                    }else{
                        $base_salary=0;
                        $sheet_blank->setCellValue($column.$row, 0);
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
                            $sheet_blank->setCellValue($column.$row, $set_salary[$sal->id]);
                            $daily_gross+=$set_salary[$sal->id];
                        
                        }elseif($sal->type=="percentage"){
                            $sheet_blank->setCellValue($column.$row, ($set_salary[$sal->id] / 100) * $base_salary);
                            $daily_gross+=($set_salary[$sal->id] / 100) * $base_salary;
                            
                        }
                    }else{
                        $sheet_blank->setCellValue($column.$row, 0);
                        $daily_gross+=0;
                    }
                    $column++;  
                }
                }
            
                $sheet_blank->setCellValue($column.$row, $daily_gross+$base_salary);
                $column++;
                $sheet_blank->setCellValue($column.$row, round(($daily_gross+$base_salary)*26));
                $column++;
                $inc_sal=0;
                $last_increament_date='';
                $last_inc_date=SalaryMappingLog::find()->select(['updated_at'], 'DISTINCT')->where(['papl_id'=>$emp['papl_id']])->orderBy(['created_at' => SORT_DESC])->one();
               
              if($last_inc_date){
                    $last_increament_date=date('M-y',strtotime($last_inc_date['updated_at']));
                    $last_inc_salary=SalaryMappingLog::find()
                                                ->where(['papl_id'=>$emp['papl_id']])
                                                ->andWhere(['updated_at'=>$last_inc_date['updated_at']])
                                                ->orderBy(['updated_at' => SORT_DESC])->all();
                    $inc_sal=0;                               
                    foreach($last_inc_salary as $inc_salary){
                        $current_sal=$set_salary[$inc_salary->salary_id];
                        $inc_sal+=$current_sal-$inc_salary->amount;
                        //echo $inc_salary->amount;
                    }
                }
                
                
                $sheet_blank->setCellValue($column.$row, $inc_sal*26);
                $column++;
                $sheet_blank->setCellValue($column.$row, $last_increament_date);
                $column++;
                
                if($last_increament_date){
                    $sheet_blank->setCellValue($column.$row, date("M-y",strtotime ( '+1 year' , strtotime ( $last_increament_date ) )));
                }else{
                    $first_join=PostingHistory::find()->select('start_date')
                            ->where(['papl_id' => $emp['papl_id']])
                            ->orderBy(['start_date' => SORT_ASC])->one();
                    $sheet_blank->setCellValue($column.$row, date('M',strtotime($first_join->start_date))."-".date('y'));
                }
                $column++;
                //print_r($first_join);

                $row++;
                $sl++;
            }
            $sheet_blank->setTitle('Increment Report');
        
            $writer = new Xlsx($spreadsheet);
            $writer->save($basepath . '/reports/'.$filename);
            echo Yii::getAlias('@storageUrl')."/reports/".$filename;exit;
        }else{
            echo false;exit;
        } 
    }
    public function actionDownload_increament_format($plant_id,$po_id,$section_id,$date){
        $plant=Plant::findOne($plant_id);
        $po=Purchaseorder::findOne($po_id);
        //print_r($po);exit;
        $posting_history = PostingHistory::find()
                                    ->andWhere(['plant_id' => $plant_id])
                                    ->andWhere(['status'=>0])
                                    ->andWhere(['end_date' => null])
                                    ->andFilterWhere(['purchase_orderid' =>$po_id])
                                    ->andFilterWhere(['section_id' =>$section_id])
                                    ->orderBy(['papl_id' => SORT_ASC])->all();
        if($posting_history) {
            $salary_master=Salary::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all();
            $basepath=Yii::getAlias('@storage');
            $filename="Increment_format_".$plant->plant_name.'_'.date('M_Y',strtotime($date)).".xlsx";
            
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
            for ($i = 1; $i <= 80; $i++) {
                $sheet_blank->getRowDimension($i)->setRowHeight(23.4, 'in');
            }
            $sheet_blank->getRowDimension(4)->setRowHeight(44, 'in');

            $sheet_blank->getColumnDimension('B')->setWidth(17);
            $sheet_blank->getColumnDimension('C')->setWidth(24);
            $sheet_blank->getColumnDimension('L')->setWidth(20);
            $sheet_blank->getColumnDimension('D')->setAutoSize(TRUE);
            for ($j = 'E'; $j <= 'K'; $j++) {
                $sheet_blank->getColumnDimension($j)->setWidth(13);;
            }
            $sheet_blank->getColumnDimension('M')->setWidth(20);
            $sheet_blank->getColumnDimension('N')->setWidth(30);
            $sheet_blank->getStyle('I4:K4')->getAlignment()->setWrapText(true);
            $sheet_blank->getStyle('A4:N100')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet_blank->getStyle('A4:N100')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $row = 1;
            $sheet_blank->mergeCells("A".$row.":N".$row)->setCellValue("A".$row,"INCREMENT APPLICATION SHEET");
            $sheet_blank->getStyle("A".$row.":N".$row)->getFont()->setUnderline(true);
            $sheet_blank->getStyle("A".$row.":N".$row)->applyFromArray($styleArray);
            
            $row++;
            $sheet_blank->setCellValue("A".$row,'MONTH : -');
            $sheet_blank->mergeCells("B".$row.":H".$row)->setCellValue("B".$row,date('M-y',strtotime($date)));
            $sheet_blank->setCellValue("I".$row,'LOCATION: -');
            $sheet_blank->mergeCells("J".$row.":N".$row)->setCellValue("J".$row,$plant->location->location_name);

            $row++;
            $sheet_blank->setCellValue("A".$row,'PO NO : -');
            $sheet_blank->setCellValue("B".$row,$po->po_number);
            $sheet_blank->mergeCells("D".$row.":E".$row)->setCellValue("D".$row,"PO MANAGER NAME");
            $sheet_blank->mergeCells("F".$row.":H".$row)->setCellValue("F".$row,"po manager name");
            $sheet_blank->setCellValue("I".$row,'AREA: -');
            $sheet_blank->mergeCells("J".$row.":N".$row)->setCellValue("J".$row,$plant->plant_name);

            $row++;
            $sheet_blank->setCellValue("A".$row,'SL NO');
            $sheet_blank->setCellValue("B".$row,'Employee Id');
            $sheet_blank->setCellValue("C".$row,'NAME');
            $sheet_blank->setCellValue("D".$row,'DESIGNATION');
            $sheet_blank->mergeCells("E".$row.":F".$row)->setCellValue("E".$row,"CURRENT GROSS SALARY");
            $sheet_blank->mergeCells("G".$row.":H".$row)->setCellValue("G".$row,"LAST INCREMENT");
            $sheet_blank->setCellValue("I".$row,'INCREMENT PROPOSED BY PO MANAGER');
            $sheet_blank->setCellValue("J".$row,'INCREMENT PROPOSED BY RM');
            $sheet_blank->setCellValue("K".$row,'INCREMENT PROPOSED BY SLM');
            $sheet_blank->mergeCells("M".$row.":N".$row)->setCellValue("M".$row,"SLM");

            $row++;
            $sheet_blank->mergeCells("A".$row.":B".$row)->setCellValue("A".$row,"INCREMENT DUE");
            $sheet_blank->getStyle("A".$row.":B".$row)->getFont()->setBold(true);
            $sheet_blank->setCellValue("E".$row,'MONTH');
            $sheet_blank->setCellValue("F".$row,'AMOUNT');
            $sheet_blank->setCellValue("G".$row,'MONTH');
            $sheet_blank->setCellValue("H".$row,'AMOUNT');
            $sheet_blank->setCellValue("I".$row,'AMOUNT');
            $sheet_blank->setCellValue("J".$row,'AMOUNT');
            $sheet_blank->setCellValue("K".$row,'AMOUNT');
            $sheet_blank->mergeCells("L".($row-1).":L".$row)->setCellValue("L".($row-1),'REMARKS-RM');
            $sheet_blank->setCellValue("M".$row,'Next Increament Cycle');
            $sheet_blank->getStyle("M".$row)->getAlignment()->setWrapText(true);
            $sheet_blank->setCellValue("N".$row,'Remarks');

            $row++;
            $sl=1;
            foreach($posting_history as $emp){
                $sheet_blank->setCellValue('A'.$row, $sl);
                $sheet_blank->setCellValue('B'.$row, $emp['papl_id']);
                $sheet_blank->setCellValue('C'.$row, $emp->enrolement->adhar_name);
                $sheet_blank->setCellValue('D'.$row, $emp->enrolement->designation);
                $sheet_blank->setCellValue('E'.$row, date("M-y", strtotime ( '-1 month' , strtotime ( $date ) ))) ;

                $set_salary= ArrayHelper::map(SalaryMapping::find()->where(['papl_id'=>$emp['papl_id']])->all(), 'salary_id','amount');
                foreach($salary_master as $sal){
                    if($sal->attribute_name=="Basic"){
                        if(isset($set_salary[$sal->id])){
                            $base_salary=$set_salary[$sal->id];
                            break;
                        }else{
                            $base_salary=0;
                            break;
                        }
                    }
                }
          
                $daily_gross=0;
                foreach($salary_master as $sal){
                    if($sal->attribute_name!="Basic"){
                        if(isset($set_salary[$sal->id])){
                            if($sal->type=="amount"){
                                $daily_gross+=$set_salary[$sal->id];
                            
                            }elseif($sal->type=="percentage"){
                                $daily_gross+=($set_salary[$sal->id] / 100) * $base_salary;
                                
                            }
                        }else{
                            $daily_gross+=0;
                        }
                        
                    }
                }
                //echo $daily_gross+$base_salary;exit;
                $sheet_blank->setCellValue('F'.$row, round(($daily_gross+$base_salary)*26));

                $last_increament_date='';
                $last_inc_date=SalaryMappingLog::find()->select(['updated_at'], 'DISTINCT')->where(['papl_id'=>$emp['papl_id']])->orderBy(['created_at' => SORT_DESC])->one();
                if($last_inc_date){
                    $last_increament_date=date('M-y',strtotime($last_inc_date['updated_at']));
                }
                $sheet_blank->setCellValue('G'.$row,$last_increament_date);
                $row++;
                $sl++;
            }
            $sheet_blank->mergeCells("A".$row.":C".$row)->setCellValue("A".$row,"ADDITIONAL INCREMENT IF ANY");
            $sheet_blank->getStyle("A".$row.":C".$row)->getFont()->setBold(true);
            $row++;$row++;
            $sheet_blank->setCellValue("A".$row,1);
            $row++;
            $sheet_blank->setCellValue("A".$row,2);
            $row++;
            $sheet_blank->setCellValue("A".$row,3);
            $row++;
            $sheet_blank->setCellValue("A".$row,4);
            $row++;
            $sheet_blank->setCellValue("A".$row,5);
            
            $row++;
            $sheet_blank->mergeCells("A".$row.":C".$row)->setCellValue("A".$row,"PBDT OF PO AS PER LATEST P & L (%):");
            $sheet_blank->mergeCells("E".$row.":H".$row)->setCellValue("E".$row,"PBDT OF PO IN YTD AS PER LATEST P & L (%):");
            $sheet_blank->mergeCells("J".$row.":L".$row)->setCellValue("J".$row,"NET COST IMPACT (TO BE FILLED BY SLM):");
            
            $row++;
            $sheet_blank->mergeCells("A".$row.":D".$row)->setCellValue("A".$row,"Comments if any");
            $sheet_blank->mergeCells("E".$row.":I".$row)->setCellValue("E".$row,"Cost Impact & Efficiency Improvement if any");
            $sheet_blank->mergeCells("J".$row.":L".$row)->setCellValue("J".$row,"Cost Impact & Efficiency Improvement if any");
            $row++;$row++;$row++;
            $sheet_blank->mergeCells("A".$row.":D".$row)->setCellValue("A".$row,"Signature of HR");
            $sheet_blank->mergeCells("E".$row.":I".$row)->setCellValue("E".$row,"Signature of RM");
            $sheet_blank->mergeCells("J".$row.":L".$row)->setCellValue("J".$row,"Signature of SLM");


            $sheet_blank->setTitle('Increment Apllication Format');
            $writer = new Xlsx($spreadsheet);
            $writer->save($basepath . '/reports/'.$filename);
            echo Yii::getAlias('@storageUrl')."/reports/".$filename;exit;
        }else{
            echo false;exit;
        } 
    }
    public function actionEsi_monthly_mc_file(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Esi_monthly_mc_file")){
            $model = new PostingHistory();
            $model['start_date']=date('F Y');
            
            if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0])->all(), 'state_id', 'state_name');
            }
            else{
                $user_plant_id = PostingHistory::find()->where(['papl_id'=>Yii::$app->user->identity->username])
                                                         ->andWhere(['end_date' => null])->one();
                $states = ArrayHelper::map(State::find()->where(['is_delete' => 0,'state_id'=>$user_plant_id->state_id])->all(), 'state_id', 'state_name');
                //print_r($states);
                $model['state_id']=$user_plant_id->state_id;
                $model['location_id']=$user_plant_id->location_id;
                $model['plant_id']=$user_plant_id->plant_id;
            }
            
            return $this->render('esi_monthly_mc_file', [
                'model' => $model,
                'states' => $states,
                
            ]);
         }else{
             Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
             return $this->redirect(['/']);
 
          }
        
    }

    public function actionGetdownload_esi_monthly_mc_file(){
        $plant_id = $loc_id = $po_id = $section_id = $date = $state_id = '';
        $plant_id=$this->request->post()['plant_id'];
        $loc_id=$this->request->post()['loc_id'];
        $po_id=$this->request->post()['po_id'];
        $section_id=$this->request->post()['section_id'];
        $date=$this->request->post()['date'];
        $state_id=$this->request->post()['state_id'];
        
         $print_date= date('M Y',strtotime($date));
        if($po_id===''){
            $po_id='';
        }
        if($section_id===''){
            $section_id='';
        }
        if ($section_id == 'null') {
            $section_id='';
        }
        $first_day_this_month = date('01-m-Y',strtotime($date)); 
        $last_day_this_month = date('t-m-Y', strtotime($date));
      
        $posting_history = PostingHistory::find()
                             ->andWhere(['location_id' => $loc_id])
                            ->andWhere(['plant_id' => $plant_id])
                            ->andWhere(['state_id' => $state_id])
                            ->andWhere(['status'=>0])
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
            // $filename="ESI - MONTHLY MC FILE".$plant_id.date('dmYhs').".xlsx";
            
            $spreadsheet = new Spreadsheet();
            $sheet_blank = $spreadsheet->getActiveSheet()->freezePane('A1');
            // $sheet_blank = $spreadsheet->getActiveSheet();

            $styleArray = [

                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ],
                
            ];
            
            $styleArray_att = [  
            'font' => [
                
                    'size'  =>  11,
                    'name'  =>  'Arial',
                    // 'color' => ['rgb' => '4472C4']
                ],             
                'borders' => [
                    'allBorders' => [
                        'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ];
            
            $sheet_blank->getColumnDimension('A')->setWidth(25);
            $sheet_blank->getColumnDimension('B')->setWidth(25);
            $sheet_blank->getColumnDimension('C')->setWidth(15);
            $sheet_blank->getColumnDimension('D')->setWidth(15);
            $sheet_blank->getColumnDimension('E')->setWidth(15);
            $row=1;
           

            $sheet_blank->setCellValue('A'.$row, 'ESIC IP No');
            $sheet_blank->getStyle("A".$row)->getFont()->setBold(true);
            $sheet_blank->getStyle('A'.$row)->getAlignment()->setWrapText(true);

            $sheet_blank->setCellValue('B'.$row, 'IP Name');
            $sheet_blank->getStyle("B".$row)->getFont()->setBold(true);
            $sheet_blank->getStyle('B'.$row)->getAlignment()->setWrapText(true);

            $sheet_blank->setCellValue('C'.$row, "No of Days for which wages paid/payable during the month");
            $sheet_blank->getStyle("C".$row)->getFont()->setBold(true);
            $sheet_blank->getStyle('C'.$row)->getAlignment()->setWrapText(true);
            $sheet_blank->getStyle('C'.$row)->applyFromArray($styleArray);


            $sheet_blank->setCellValue('D'.$row, 'Total Monthly Wages');
            $sheet_blank->getStyle("D".$row)->getFont()->setBold(true);
            $sheet_blank->getStyle('E'.$row)->getAlignment()->setWrapText(true);

            
            $sheet_blank->getStyle("A".$row.":F".$row)->applyFromArray($styleArray_att);
            $sheet_blank->setCellValue('E'.$row, ' Reason Code for Zero workings days(numeric only; provide 0 for all other reasons- Click on the link for reference)');
            $sheet_blank->getStyle('E'.$row)->getAlignment()->setWrapText(true);
            $sheet_blank->getStyle("E".$row)->getFont()->setBold(true)->setUnderline(true);
            $sheet_blank->setCellValue('F'.$row, ' Last Working Day');
            $sheet_blank->getStyle('F'.$row)->getAlignment()->setWrapText(true);
            $sheet_blank->getStyle("F".$row)->getFont()->setBold(true);
           

            $row++;
          
            $total_payble=  0;
            // echo "<pre>";print_r($posting_history);exit;
            foreach($posting_history as $emp){
                // echo "<pre>";print_r($posting_history);
                $plantname = $emp->plant->plant_name;
                // RETURN ESIC (07 MC_Oct_2021 Balco Code)
                $filename="ESI_MONTHLY_MC_FILE (".$plantname.'_'.$print_date.")".".xlsx";
                // $filename="RETURN ESIC (".$print_date.$plantname.")".".xlsx";    

                $sheet_blank->getStyle("A".$row.":F".$row)->applyFromArray($styleArray_att);
                $sheet_blank->setCellValue('A' . $row, $emp->enrolement->esic_ip_number ?? '');
                $sheet_blank->getStyle("A".$row)->getNumberFormat()->setFormatCode('0');
                
                $sheet_blank->setCellValue('B' . $row, $emp->enrolement->adhar_name);
                
                
                $set_salary= Salary::find()->where(['attribute_name'=>'DA'])->one();
               
                $salary_master=MonthlySalary::find()->where(['papl_id'=>$emp['papl_id'],'month_year'=>date('F Y', strtotime($date))])->one();

                 $work_done=CalculationController::actionWorkdays($emp,$date);
                
                $da_id = $set_salary['id'];
                if (isset($salary_master)) {
                   
                    $total_payble = $salary_master['total_payble'] ;
                    
                }
                
                                
                
                $sheet_blank->setCellValue('C' . $row, $work_done['tot_paydays'] ?? '');
                $sheet_blank->getStyle('C'.$row)->applyFromArray($styleArray);
                $sheet_blank->setCellValue('D'.$row, $total_payble);
                $sheet_blank->getStyle('D'.$row)->applyFromArray($styleArray);
                // $sheet_blank->setCellValue('E'.$row, $total_payble);
                $sheet_blank->setCellValue('F'.$row, $emp->end_date);
                $row++;
               
            }
           
            // exit;
            $sheet_blank->setTitle('ESI - MONTHLY MC FILE');
        
            $writer = new Xlsx($spreadsheet);
            $writer->save($basepath . '/reports/'.$filename);
            echo Yii::getAlias('@storageUrl')."/reports/".$filename;exit;
        }else{
            echo false;exit;
        } 
        
     }


//  PF_Monthly_Statement Document
public function actionPt_monthly_pt_satement(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"PT - MONTHLY PT STATEMENT")){
            $model = new PostingHistory();
            $model['start_date']=date('F Y');
            
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
            return $this->render('pt_monthly_pt_satement', [
                'model' => $model,
                'states' => $states,
            ]);
         }else{
             Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
             return $this->redirect(['/']);
 
          }
        
    }
    // It is PF_Monthly_Statement
public function actionDownload_pt_monthly_pt_stmt(){
        
         $plant_id = $this->request->post()['plant_id'];
        $loc_id=$this->request->post()['loc_id'];
       $po_id=$this->request->post()['po_id'];
         $section_id=$this->request->post()['section_id'];
        $date=$this->request->post()['date'];
        $state_id=$this->request->post()['state_id'];
       $print_date= date('M Y',strtotime($date));
       // $posting_history = new PostingHistory();
       $plantname = '';
        $da = '';
        if($po_id===''){
            $po_id='';
        }
        if($section_id===''){
            $section_id='';
        }
        if ($section_id == 'null') {
            $section_id='';
        }
        $first_day_this_month = date('01-m-Y',strtotime($date)); 
        $last_day_this_month = date('t-m-Y', strtotime($date));
        
            $posting_history = PostingHistory::find()->andWhere(['location_id' => $loc_id])
                            ->andWhere(['plant_id' => $plant_id])
                            ->andWhere(['state_id' => $state_id])
                            ->andWhere(['status'=>0])
                            ->andWhere(['<=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`start_date`, '%d-%m-%Y'), '%Y-%m-%d')"), date('Y-m-d', strtotime($last_day_this_month))])
                            ->andWhere(['or',
                                          ['>=',new \yii\db\Expression("DATE_FORMAT(STR_TO_DATE(`end_date`, '%d-%m-%Y'), '%Y-%m-%d')"), date('Y-m-d', strtotime($first_day_this_month)) ],
                                          ['end_date' => null]
                                      ])
                            // ->andWhere(['between', 'end_date', $first_day_this_month, $last_day_this_month ])
                            ->andFilterWhere(['purchase_orderid' =>$po_id])
                            ->andFilterWhere(['section_id' =>$section_id])
                            ->orderBy(['papl_id' => SORT_ASC])->all();
                            // 
     
       // echo "<pre>";print_r($posting_history->plant);die();
            
        if($posting_history) {
            $basepath=Yii::getAlias('@storage');
            // $filename="PF Monthly Statement_".$plant_id.date('dmYhs').".xlsx";
            
            
            $spreadsheet = new Spreadsheet();
            $sheet_blank = $spreadsheet->getActiveSheet()->freezePane('K7');
            $sheet_blank = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                
                    'size'  =>  11,
                    'name'  =>  'Arial',
                    // 'color' => ['rgb' => '4472C4']
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
            
            $styleArray_att = [                
                'borders' => [
                    'allBorders' => [
                        'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ];
            
            $sheet_blank->getColumnDimension('B')->setWidth(18);
            $sheet_blank->getColumnDimension('C')->setWidth(25);
            $sheet_blank->getColumnDimension('D')->setAutoSize(TRUE);
            $sheet_blank->getColumnDimension('E')->setWidth(25);
            $sheet_blank->getColumnDimension('F')->setAutoSize(TRUE);
            $sheet_blank->getColumnDimension('G')->setAutoSize(TRUE);
            $sheet_blank->getColumnDimension('H')->setAutoSize(TRUE);
            $sheet_blank->getColumnDimension('I')->setAutoSize(TRUE);
            $sheet_blank->getColumnDimension('J')->setAutoSize(TRUE);
            $sheet_blank->getColumnDimension('K')->setAutoSize(TRUE);
            $row=1;
            $sheet_blank->mergeCells("A".$row.":D".$row)->setCellValue("A".$row,'M/s Pradhan Associates Pvt. Ltd.');
            $sheet_blank->getStyle("A".$row.":D".$row)->getFont()->setBold(true);
            $row++;
            $row=2;
            $sheet_blank->mergeCells("A".$row.":C".$row)->setCellValue("A".$row,'Plot No.126/2262, Khandagiri Vihar, BBSR-30.');
            $row++;
           $row=3;
            $sheet_blank->mergeCells("A".$row.":E".$row)->setCellValue("A".$row,'Employees Provident Fund Computation Statement for the month of '.date('F Y', strtotime($date)));
            $sheet_blank->mergeCells("H".$row.":I".$row)->setCellValue("H".$row,'VEDANTA SITE A/C');
            $row++;
            $column2 = 'E';
            // $sheet_blank->getStyle('A1:'.$column2.$row)->getAlignment()->setWrapText(true);
            // $sheet_blank->getStyle('A4:I3')->applyFromArray($styleArray_att);
            // $sheet_blank->getStyle("A4:I3")->applyFromArray($styleArray_att);
            

            $sheet_blank->setCellValue('A'.$row, 'Sl No.');
            $sheet_blank->setCellValue('B'.$row, 'Name of Employee');
            $sheet_blank->setCellValue('C'.$row, "Employee's UAN");
            $sheet_blank->setCellValue('D'.$row, 'Basic Salary');
            $column = 'E';
            $sheet_blank->getStyle("A".$row.":I".$row)->applyFromArray($styleArray_att);
            $sheet_blank->setCellValue('E'.$row, 'DA');
            $sheet_blank->setCellValue('F'.$row, 'Total');
            $sheet_blank->setCellValue('G'.$row, 'Employees');
            // $sheet_blank->setCellValue('I'.$row, "Employer's Share");
            $sheet_blank->mergeCells("H".$row.":I".$row)->setCellValue("H".$row,"Employer's Share");

            $row++;
            $sheet_blank->getStyle("A".$row.":I".$row)->applyFromArray($styleArray_att);
            $sheet_blank->setCellValue('F'.$row, 'Salary');
            $sheet_blank->setCellValue('G'.$row, 'Share 12%');
            $sheet_blank->setCellValue('H'.$row, 'Pension');
            $sheet_blank->setCellValue('I'.$row, 'E.P.F.');

            $row++;
            $sheet_blank->getStyle("A".$row.":I".$row)->applyFromArray($styleArray_att);
            $sheet_blank->setCellValue('F'.$row, '(Rs.)');
            $sheet_blank->setCellValue('G'.$row, 'E.P.F.');
            $sheet_blank->setCellValue('H'.$row, 'Fund 8.33%');
            $sheet_blank->setCellValue('I'.$row, 'Fund 3.67%');
            
            $sheet_blank->setCellValue('K'.$row, 'PF Account Number');
           $sheet_blank->getStyle("K".$row)->applyFromArray($styleArray_att);
            $row++;
            $sl=1;
            $tot_basic = $tot_sal = $epf_12 = $pf_8_33 = $epf_3_67 =  0;
            foreach($posting_history as $emp){
                $plantname = $emp->plant->plant_name;
                $filename="PF_MONTHLY_STATEMENT(".$plantname.'_'.$print_date.")".".xlsx";

                $uan = $emp->enrolement->uan ?? '';
                $sheet_blank->getStyle("A".$row.":I".$row)->applyFromArray($styleArray_att);
                $sheet_blank->setCellValue('A' . $row, $sl);
                // $sheet_blank->setCellValue('B' . $row, $emp['papl_id']);
                $sheet_blank->setCellValue('B' . $row, $emp->enrolement->adhar_name);
                $sheet_blank->setCellValue('C' . $row, $uan);
                $sheet_blank->getStyle("C".$row)->getNumberFormat() ->setFormatCode('0');
                // $column="E";
                // $set_salary= ArrayHelper::map(Salary::find()->all(), 'id','attribute_name');
                $set_salary= Salary::find()->where(['attribute_name'=>'DA'])->one();
               
                $salary_master=MonthlySalary::find()->where(['papl_id'=>$emp['papl_id'],'month_year'=>date('F Y', strtotime($date))])->one();

                // echo '<pre>';print_r($set_salary['id']);exit;
                // echo '<pre>';print_r($salary_master['earning_detail']);exit;
                $da_id = $set_salary['id'];
               
                if (isset($salary_master)) {
                    $earning_detail=json_decode($salary_master['earning_detail'],true);                                
                    if(array_key_exists($da_id,$earning_detail)){
                        $da =$earning_detail[$da_id] ;
                        unset($earning_detail[$da_id]);
                    }
                    $total_basic = $salary_master['total_basic'] ;
                    $total_salary = $da + $total_basic;
                    $sheet_blank->setCellValue('D' . $row, $total_basic );
                    $epf= round($total_basic * 0.12);
                    $pension_fund= round($total_basic * 0.0833);
                    $epf_fund= $epf - $pension_fund;
                    $tot_basic += $total_basic;
                    $tot_sal += $total_salary;
                    $epf_12 += $epf;
                    $pf_8_33 += $pension_fund;
                    $epf_3_67 += $epf_fund;
                    $sheet_blank->setCellValue('E'.$row, $da);
                    $sheet_blank->setCellValue('F'.$row, $total_salary);
                    $sheet_blank->setCellValue('G'.$row, $epf);
                    $sheet_blank->setCellValue('H'.$row, $pension_fund);
                    $sheet_blank->setCellValue('I'.$row, $epf_fund);
                    $sheet_blank->setCellValue('K' . $row, $emp->enrolement->pf_account_number ?? '');
                    $sheet_blank->getStyle("K".$row)->applyFromArray($styleArray_att);
                   
                   
                }
                $row++;
                $sl++;
            }
             $sheet_blank->setCellValue('B'.$row, 'Total :');
             $sheet_blank->setCellValue('D'.$row, $tot_basic);
             $sheet_blank->setCellValue('F'.$row, $tot_sal);
             $sheet_blank->setCellValue('G'.$row, $epf_12);
             $sheet_blank->setCellValue('H'.$row, $pf_8_33);
             $sheet_blank->setCellValue('I'.$row, $epf_3_67);
             $row++;
             $sheet_blank->setCellValue('B'.$row, 'Summary:');
             $sheet_blank->getStyle("B".$row)->getFont()->setBold(true);
             $sheet_blank->setCellValue('F'.$row, '(Rs.)');
             $sheet_blank->getStyle("F".$row)->getFont()->setUnderline(true);
             $row++;
             $sheet_blank->setCellValue('A'.$row, 'a)');
             $sheet_blank->setCellValue('B'.$row, 'Total Salary (Basic + D.A)');
             $sheet_blank->setCellValue('F'.$row, $tot_sal);
             $sheet_blank->setCellValue('G'.$row, $tot_sal);
             // $sheet_blank->setCellValue('G'.$row, $epf_12);
             $row++;
             $sheet_blank->setCellValue('A'.$row, 'b)');
             $sheet_blank->setCellValue('B'.$row, 'A/c. No. 1 (E.P.F.) (15.67%) :');
             $sheet_blank->getStyle("B".$row)->getFont()->setBold(true);
             $row++;
             $sheet_blank->setCellValue('B'.$row, 'Employees Cont. @ 12% of Total Salary');
             $sheet_blank->setCellValue('E'.$row, $epf_12);
             $row++;
             $sheet_blank->setCellValue('B'.$row, "Employer's Cont. @ 3.67% of Total Salary");
             $sheet_blank->setCellValue('E'.$row, $epf_3_67);
             $sheet_blank->getStyle("E".$row)->getFont()->setUnderline(true);
             $sum_12_3_67_epf = $epf_12+$epf_3_67;
             $sheet_blank->setCellValue('F'.$row, $sum_12_3_67_epf);
             // $sheet_blank->setCellValue('F'.$row, $tot_sal);
             $row++;
             $sheet_blank->setCellValue('A'.$row, 'c)');
             $sheet_blank->setCellValue('B'.$row, "A/c No. 10 (Pension Fund) :");
             $row++;
             $sheet_blank->setCellValue('B'.$row, "Employer's Cont. @ 8.33% of Total Salary");
             $sheet_blank->setCellValue('F'.$row, $pf_8_33);
             $row++;
             $sheet_blank->setCellValue('A'.$row, 'd)');
             $sheet_blank->setCellValue('B'.$row, "A/c. No 2: EPF Admn. Charges @ 0.5% of Total Salary");
             $x = round($tot_sal*.005);
             $sheet_blank->setCellValue('F'.$row, $x);
             $row++;
             $sheet_blank->setCellValue('A'.$row, 'e)');
             $sheet_blank->setCellValue('B'.$row, "A/c. No. 21: EDLI Contribution @ 0.5% of Total EPS Wages");
             // $y = round($epf_12*.005);
             $y = round($tot_sal*.005);
             $sheet_blank->setCellValue('F'.$row, $y);
             $sheet_blank->getStyle('F'.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00');
             // $sheet_blank->getStyle('F'.$row)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
             $row++;
             $sheet_blank->setCellValue('A'.$row, 'f)');
             $sheet_blank->setCellValue('B'.$row, "A/c. No. 22: EDLI Admn. Charges @ 0.01% of Total Salary");
             // $z = round($epf_12*.0001)+168;
             $z = round($tot_sal*.0001)+168;
             $sheet_blank->setCellValue('F'.$row, $z);
             // $sheet_blank->getStyle("F".$row)->getFont()->setUnderline(true);
             $sheet_blank->getStyle("F".$row)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
             $row++;
             $sheet_blank->setCellValue('E'.$row, 'Total :');
             $total_addition = $sum_12_3_67_epf+$x+$y+$z+$pf_8_33;
             $sheet_blank->setCellValue('F'.$row, $total_addition);
             $sheet_blank->getStyle('F'.$row)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
             // $sheet_blank->getStyle("F".$row)->getFont()->setUnderline(true);
             $total_subtraction = $total_addition - $epf_12;
             $sheet_blank->setCellValue('H'.$row, $total_subtraction);
             $row++;$row++;$row++;$row++;$row++;$row++;$row++;
             $sheet_blank->mergeCells("H".$row.":I".$row)->setCellValue("H".$row,"Authorised Signatory");

            $sheet_blank->setTitle("PF MONTHLY STATEMENT");
            // $sheet_blank->setTitle("PFMONTHLYSTATEMENT(".$print_date.")");
        
            $writer = new Xlsx($spreadsheet);
            $writer->save($basepath . '/reports/'.$filename);
            echo Yii::getAlias('@storageUrl')."/reports/".$filename;exit;
        }else{
            echo false;exit;
        } 
        
     }
}



