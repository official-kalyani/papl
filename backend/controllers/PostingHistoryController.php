<?php

namespace backend\controllers;

use Yii;
use common\models\PostingHistory;
use common\models\Employee;
use common\models\Family;
use common\models\Location;
use common\models\Attendance;
use common\models\Document;
use common\models\Qualification;
use common\models\Nominee;
use common\models\Enrollment;
use common\models\BankDetails;
use common\models\Salary;
use common\models\Plant;
use common\models\SalaryMapping;
use common\models\DeductionMapping;
use common\models\Deduction;
use common\models\Section;
use common\models\Purchaseorder;
use common\models\PostingHistorySearch;
use common\models\User;
use common\models\State;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\helpers\acl;
use ZipArchive;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
/**
 * PostingHistoryController implements the CRUD actions for PostingHistory model.
 */
class PostingHistoryController extends Controller
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
     * Lists all PostingHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostingHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PostingHistory model.
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
     * Creates a new PostingHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $role_id=Yii::$app->user->identity->username;
        $role=Yii::$app->user->identity->role_id;
        $user_plant_id = PostingHistory::find()->where(['papl_id'=>$role_id])
                                                  ->andWhere(['end_date' => null])->one();

        if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
            $locations = ArrayHelper::map(Location::find()->where(['is_delete' => 0])->all(), 'location_id', 'location_name');
        }else{
            $locations = ArrayHelper::map(Location::find()->where(['is_delete' => 0,'location_id'=>$user_plant_id->location_id])->all(), 'location_id', 'location_name');
        }
        
        $model = new PostingHistory();
        $plant_model = new Plant();
        $emp_model = new Employee();
        $salarymodel = new Salary();
        $salary_mapping_model = new SalaryMapping();
        $deduction_mapping_model = new DeductionMapping();
        $purchase_order_model = new Purchaseorder();
        $salaryattr = Salary::find()->where(['is_delete' => 0])->all();
        $deduction_model = new Deduction();
        $deduction_attr = Deduction::find()->where(['is_delete' => 0])->all();
        $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name');
        $enrolmentid = Yii::$app->request->get('enrolementid');
        $url_papl_id = Yii::$app->request->get('papl_id');
        $sal_arr = [];
        // $compare_end_date = '';
        if (isset($enrolmentid)) {
            $enrollment_data = Enrollment::find()->where(['enrolement_id' => $enrolmentid])->one();
            $enrollment_data->papl_id = str_replace('TEMP', 'PAPL', $enrolmentid);
            $designation = $enrollment_data->designation;
            $PAPLdesignation = $enrollment_data->PAPLdesignation;
           
            $enrollment_data->status = '5';
            
            
            $papl_id = str_replace('TEMP', 'PAPL', $enrolmentid);
            $emp_list = '';
            $posting_list = '';
            // $posting_list = PostingHistory::find()->where(['papl_id' => $papl_id])->one();
            $salary_mapping_list = '';
            $deduction_mapping_list = '';
            $model->status = 1;
            
        }
        if (isset($url_papl_id)) {
            $enrollment_data = Enrollment::find()->where(['papl_id' => $url_papl_id])->one();
            $papl_id =$url_papl_id;
            $emp_list = Employee::find()->where(['papl_id' => $url_papl_id])->one();            
            
            

            $posting_list = PostingHistory::find()->where(['papl_id'=>$papl_id])->andWhere(['is', 'end_date',new \yii\db\Expression('null')])->one();
            
            $salary_mapping_list = SalaryMapping::find()->where(['papl_id' => $url_papl_id])->one();          
            
            $deduction_mapping_list = DeductionMapping::find()->where(['papl_id' => $url_papl_id])->one();
        }
        //echo '<pre>';print_r($posting_list);die();
        $purchase_order = ArrayHelper::map(Purchaseorder::find()->where(['is_delete' => 0,'plant_id'=>$enrollment_data->plant_id])->all(), 'po_id', 'purchase_order_name');
        $purchase_details = Purchaseorder::find()->where(['plant_id'=>$enrollment_data->plant_id])->one();
        $plant_details = Plant::find()->where(['plant_id'=>$enrollment_data->plant_id])->one();
        
        if (isset($enrolmentid)) {
            $sec_details = Section::find()->where(['plant_id'=>$enrollment_data->plant_id])->one();

        }else{
          $sec_details = Section::find()->where(['plant_id'=>$plant_details->plant_id])->one();  

      }


      if (isset($enrolmentid)) {
        $plant_id_po = $plant_details->plant_id ?? '';
        $location_id_po = $plant_details->location_id ?? '';
        $purchase_id_po = $purchase_details->po_id ?? '';
        $sec_id_po = $sec_details->section_id ?? '';

    }else{
        $plant_id_po = $purchase_details->plant_id ?? '';
        $location_id_po = $purchase_details->location_id ?? '';
        $purchase_id_po = $purchase_details->po_id ?? '';
        $sec_id_po = $sec_details->section_id ?? '';
    }



    if ($model->load(Yii::$app->request->post()) && $emp_model->load(Yii::$app->request->post())) 
    {
        
        $enrollment_data->save();
        $purchase_order_id = Yii::$app->request->post()['PostingHistory']['purchase_orderid'];
        $plant_id = Yii::$app->request->post()['PostingHistory']['plant_id'];
        $purchaseorder_data = Plant::find()->where(['plant_id' => $plant_id])->one();

        if (Yii::$app->request->post()['Employee']) {

            $emp_d = Employee::find()->where(['papl_id'=>$papl_id])->one();

            
            if (empty(($emp_d))) {

                $emp_model = new Employee();
                $emp_model->papl_id = $papl_id;
                $emp_model->gate_pass = Yii::$app->request->post()['Employee']['gate_pass'];
                $emp_model->gate_pass_validity = Yii::$app->request->post()['Employee']['gate_pass_validity'];
                $emp_model->workman_sl_no = Yii::$app->request->post()['Employee']['workman_sl_no'];

                $emp_model->save();
            }else{
                $emp_model = Employee::find()->where(['papl_id'=>$papl_id])->one();
                // $emp_model->papl_id = $papl_id;
                $emp_model->gate_pass = Yii::$app->request->post()['Employee']['gate_pass'];
                $emp_model->gate_pass_validity = Yii::$app->request->post()['Employee']['gate_pass_validity'];
                $emp_model->workman_sl_no = Yii::$app->request->post()['Employee']['workman_sl_no'];

                $emp_model->save();

            }

           
        }
        if (isset(Yii::$app->request->post()['PostingHistory'])) {
            $old_date_timestamp = strtotime(Yii::$app->request->post()['PostingHistory']['start_date']);
            $start_date = date('d-m-Y', $old_date_timestamp);
            $posting_data =PostingHistory::find()->where(['papl_id'=>$papl_id])->one();
            
            
            $enroll_data =Enrollment::find()->where(['papl_id'=>$papl_id])->one();
            $enroll_data->plant_id = $plant_id;
            $enroll_data->save();

            if (empty($posting_data) ) {
                       

                $model = new PostingHistory();
                $model->start_date = $start_date;
                $model->state_id = $purchaseorder_data->state_id;
                $model->location_id = $purchaseorder_data->location_id;
                $model->purchase_orderid = Yii::$app->request->post()['PostingHistory']['purchase_orderid'];
                $model->section_id = Yii::$app->request->post()['PostingHistory']['section_id'];
                $model->plant_id = $plant_id;
                $model->papl_id = $papl_id;
                $model->updated_user = Yii::$app->user->identity->id;
                $model->created_by = Yii::$app->user->identity->id;

                $model->save();


            }else{
            
                $posting_data = PostingHistory::find()->where(['papl_id'=>$papl_id])->andWhere(['is', 'end_date',new \yii\db\Expression('null')])->one();
                
                // $end_date = date_create($start_date)->modify('-1 days')->format('Y-m-d');
                // echo $end_date;die();
                // $start_date = $posting_data->start_date;

                $end_date = date_create($start_date)->modify('-1 days')->format('d-m-Y');
                // $compare_end_date = date_create($start_date)->modify('+1 days')->format('d-m-Y');
                 
                $posting_data->end_date = $end_date;
                $posting_data->status = 1;
                $posting_data->save();
                $model = new PostingHistory();
                $model->start_date = $start_date;
                $model->state_id = $purchaseorder_data->state_id;
                $model->location_id = $purchaseorder_data->location_id;
                $model->purchase_orderid = Yii::$app->request->post()['PostingHistory']['purchase_orderid'];
                $model->section_id = Yii::$app->request->post()['PostingHistory']['section_id'];
                $model->plant_id = $plant_id;
                $model->status = 0;
                $model->papl_id = $url_papl_id;
                $model->updated_user = Yii::$app->user->identity->id;
                $model->created_by = Yii::$app->user->identity->id;
                $model->save();


            }
            $user=User::find()->where(['username'=>$papl_id])->one();
            if(!$user){
                $user = new User();
                $user->username=$papl_id;
                $user->password_hash=Yii::$app->security->generatePasswordHash('qwerty');
                $user->role_id=3;
                $user->status=10;
                $user->save();
            } 

        }

           

        if (Yii::$app->request->post()['salary_value']) {
            if (isset($url_papl_id)) {
                SalaryMapping::deleteAll(['papl_id'=>$url_papl_id]);
            }
                    
            $sal_count = count(Yii::$app->request->post()['salary_value']);
            $posting_salary=0; 
            $base_salary= Yii::$app->request->post()['salary_value'][0];
            //echo $base_salary;exit;
            for ($i=0; $i < $sal_count; $i++) { 
                if(Yii::$app->request->post()['salary_value'][$i]!='')
                {
                    $salary_mapping_model = new SalaryMapping();
                    $salary_mapping_model->papl_id  = $papl_id;
                    $salary_mapping_model->salary_id   = Yii::$app->request->post()['salary_id'][$i];
                    $salary_mapping_model->amount   = Yii::$app->request->post()['salary_value'][$i];
                    
                    $salary_master=Salary::findOne(Yii::$app->request->post()['salary_id'][$i]);
                    
                    if($salary_master->type=="amount"){
                        $posting_salary+=Yii::$app->request->post()['salary_value'][$i];
                    }elseif($salary_master->type=="percentage"){
                        $posting_salary+=(Yii::$app->request->post()['salary_value'][$i] / 100) * $base_salary;
                    }
                    $salary_mapping_model->updated_by = Yii::$app->user->identity->id;
                    $salary_mapping_model->created_by = Yii::$app->user->identity->id;
                    if (!$salary_mapping_model->save()) {
                        var_dump($salary_mapping_model->getErrors());
                        die();
                    }
                }
            }
            
            $model->posting_salary=$posting_salary*26;
            $model->save();
                        
        }
        if (Yii::$app->request->post()['deduction_id']) {
            // print_r(Yii::$app->request->post()['deduction_id']);die();
            if (isset($url_papl_id)) {

                DeductionMapping::deleteAll(['papl_id'=>$url_papl_id]);
            }
            foreach (Yii::$app->request->post()['deduction_id'] as $key => $value) {
                $deduction_mapping_model = new DeductionMapping();
                $deduction_mapping_model->papl_id  = $papl_id;
                $deduction_mapping_model->deduction_id   = Yii::$app->request->post()['deduction_id'][$key];
                $deduction_mapping_model->amount   = Yii::$app->request->post()['deduction_value'][$key];
                $deduction_mapping_model->updated_by = Yii::$app->user->identity->id;
                $deduction_mapping_model->created_by = Yii::$app->user->identity->id;
                if (!$deduction_mapping_model->save()) {
                   var_dump($deduction_mapping_model->getErrors());die();
                }
            }
        }

        // if(!$enrollment_data->save()){
        //     var_dump($enrollment_data->getErrors());
           
        // }
                    // return $this->redirect(['index', 'id' => $model->id, 'papl_id' => $papl_id]);
                // } 
                // else {
                //     var_dump($model->getErrors());
                //     die();
                // }
        return $this->redirect(['create','papl_id' => $papl_id]);
            // }
        }
        return $this->render('create', [
            'model' => $model,
            'emp_model' => $emp_model,
            'salaryattr' => $salaryattr,
            'purchase_order' => $purchase_order,
            'purchase_order_model' => $purchase_order_model,
            'salarymodel' => $salarymodel,
            'deduction_attr' => $deduction_attr,
            'deduction_model' => $deduction_model,
            'emp_list' => $emp_list,
            'posting_list' => $posting_list,
            'salary_mapping_list' => $salary_mapping_list,
            'deduction_mapping_list' => $deduction_mapping_list,
            'enrolmentid' => $enrolmentid,
            'url_papl_id' => $url_papl_id,
            'salary_mapping_model' => $salary_mapping_model,
            'deduction_mapping_model' => $deduction_mapping_model,
            'enrollment_data' => $enrollment_data,
            'plants' => $plants,
            'locations' => $locations,
            'plant_id_po' => $plant_id_po,
            'location_id_po' => $location_id_po,
            'enrolmentid' => $enrolmentid,
            'purchase_id_po' => $purchase_id_po,
            'sec_id_po' => $sec_id_po,
            'role_id' => $role_id,
            'role' => $role,
            'user_plant_id' => $user_plant_id,
            // 'compare_end_date' => $compare_end_date,
        ]);
    }

    /**
     * Updates an existing PostingHistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $emp_model = $this->findModel($id);
        // $deduction_data = Deduction::find()->where(['papl_id' => $emp_model->papl_id])->one();
        // echo "<pre>";print_r($deduction_data);exit();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'emp_model' => $emp_model,
        ]);
    }

    /**
     * Deletes an existing PostingHistory model.
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
     * Finds the PostingHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PostingHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PostingHistory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionGetsection()
    {
        if (Yii::$app->request->isAjax) {
            $po_id = Yii::$app->request->post('po_id');
            $sections = Section::find()->where(['po_id' => $po_id])->all();


            foreach ($sections as $section) {
                echo '<option value="' . $section->po_id . '">' . $section->section_name . '</option>';
            }
        }
    }

    public function actionTransfer()
    {
        //if(Yii::$app->user->identity->role_id==3 || Yii::$app->user->identity->role_id==1 ){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Employee")){
            $employee_lists = new Employee();
            $posting_lists = new PostingHistory();
            $searchModel = new Employee();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $user_papl_id=Yii::$app->user->identity->username;
            $role=Yii::$app->user->identity->role_id;
            if ($role != 1 && $role != 5) {
                 $user_plant_id = PostingHistory::find()->where(['papl_id'=>$user_papl_id])->one();
           
                

               $employee_lists = PostingHistory::find()
                ->alias('ph')
                ->select('*')
                ->innerJoin('employee emp','ph.papl_id = emp.papl_id')
                //->where(['ph.plant_id' => $user_plant_id->plant_id,])
                ->where(['ph.location_id' => $user_plant_id->location_id,'emp.is_exit'=>0])
                ->all();
            }else{
                $employee_lists = $employee_lists::find()->where(['is_exit'=>0])->orderBy('papl_id')->all();
            }
           
            
            
            
            // $employee_lists = $employee_lists::find()->select('papl_id')->distinct('papl_id')->orderBy('gate_pass_validity')->all();
            
            return $this->render('transfer', [
            'employee_lists' => $employee_lists,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'role' => $role,
            ]); 
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            // if(Yii::$app->user->identity->role_id==2)
            //     return $this->redirect(['index','type' => 1]);
            // elseif(Yii::$app->user->identity->role_id==4)
            //     return $this->redirect(['index','type' => 2]);
            return $this->redirect(['/']);
        }
    }
    public function actionDownload2(){
        $emp_id = Yii::$app->request->get('papl_id');
        $temp_id = str_replace('PAPL', 'TEMP', $emp_id);        
        $enrollment_table = Enrollment::find()->select(['browse_adhar','browse_pp_photo','browse_experience','esic_sheet','uan_sheet'])->where(['enrolement_id'=>$temp_id])->one();
        $family_table = Family::find()->select(['family_nominee_adhar_photo'])->where(['enrolement_id'=>$temp_id])->one();
        $document_table = Document::find()->select(['voter_copy_photo','drivinglicense_photo','pan_photo','passport_photo'])->where(['enrolement_id'=>$temp_id])->one();
        $nominee_table = Nominee::find()->select(['nominee_adhar_photo'])->where(['enrolement_id'=>$temp_id])->one();
        // echo "<pre>";print_r($document_table);die();
        // echo Yii::getAlias('@storage').$enrollment_table->browse_adhar;die();
        // echo '<pre>';print_r($document_table);die();
        $doc_arr = [];
        
        if($document_table){
            $doc_arr[]=$document_table->drivinglicense_photo;
            $doc_arr[]=$document_table->voter_copy_photo;
            $doc_arr[]=$document_table->pan_photo;
            $doc_arr[]=$document_table->passport_photo;
        }
        if ($enrollment_table) {
            $doc_arr[]=$enrollment_table->browse_adhar;
            $doc_arr[]=$enrollment_table->browse_pp_photo;
            $doc_arr[]=$enrollment_table->browse_experience;
            $doc_arr[]=$enrollment_table->esic_sheet;
            $doc_arr[]=$enrollment_table->uan_sheet;
        }
        if ($family_table) {
            $doc_arr[]=$family_table->family_nominee_adhar_photo;
        }
        if ($nominee_table) {
            $doc_arr[]=$nominee_table->nominee_adhar_photo;
        }
         
       
       $destination= Yii::getAlias('@storage');
       // $destination= Yii::getAlias('@storageUrl');
            $zip=new ZipArchive();
      
              $zipname='document.zip';
             
        
        if ($zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            die("Failed to create a zip archive. zipFilePath = $zipFilePath");
        }
        $pathdir = $destination.'/upload/';
        foreach($doc_arr as $thefile)
        {
       
            $full_file = $pathdir.basename($thefile);
            
             
            if (is_file($full_file)) {
          
             $zip->addFile($pathdir.basename($thefile));
           
            
        }else{
           echo "error";  
        }
            
          
        }
       
        $zip->close();
       header('Cache-Control: public');  
        header('Content-Description: File Transfer');  
        header('Content-type: application/force-download');
        header('Content-Disposition: attachment; filename="'.$zipname.'"');
       // header('content-type:application/octet-stream');
       // header("content-disposition: attachment; filename=$zipname");
       //  header('Content-Length: ' . filesize($zipname));
       //  readfile($zipname);

       //  unlink($zipname);
        // var_dump($status);die();
        
    }
    public function actionDownload(){
        $emp_id = Yii::$app->request->get('papl_id');        
        // $temp_id = str_replace('PAPL', 'TEMP', $emp_id);        
        $enrollment_table = Enrollment::find()->select(['adhar_name','browse_adhar','browse_pp_photo','browse_experience','esic_sheet','uan_sheet'])->where(['enrolement_id'=>$emp_id])->one();
        $family_table = Family::find()->select(['family_nominee_adhar_photo'])->where(['enrolement_id'=>$emp_id])->one();
        $document_table = Document::find()->select(['voter_copy_photo','drivinglicense_photo','pan_photo','passport_photo'])->where(['enrolement_id'=>$emp_id])->one();
        $qualification_table = Qualification::find()->select(['qualification_document'])->where(['enrolement_id'=>$emp_id])->one();
        $nominee_table = Nominee::find()->select(['nominee_adhar_photo'])->where(['enrolement_id'=>$emp_id])->one();
        $bank_table = BankDetails::find()->select(['pass_book_photo'])->where(['enrolement_id'=>$emp_id])->one();
        
        $doc_arr = [];
        
        if($document_table){
            $name = 'document';
            $doc_arr[]=$document_table->drivinglicense_photo;
            $doc_arr[]=$document_table->voter_copy_photo;
            $doc_arr[]=$document_table->pan_photo;
            $doc_arr[]=$document_table->passport_photo;
        }
        if($qualification_table){
            $name = 'qualification';
            $doc_arr[]=$qualification_table->qualification_document;
        }
        if ($enrollment_table) {
            $name = 'master';
            $doc_arr[]=$enrollment_table->browse_adhar;
            $doc_arr[]=$enrollment_table->browse_pp_photo;
            $doc_arr[]=$enrollment_table->browse_experience;
            $doc_arr[]=$enrollment_table->esic_sheet;
            $doc_arr[]=$enrollment_table->uan_sheet;
        }
        if ($family_table) {
            $name = 'family';
            $doc_arr[]=$family_table->family_nominee_adhar_photo;
        }       
        if ($nominee_table) {
            $name = 'nominee';
            $doc_arr[]=$nominee_table->nominee_adhar_photo;
        }         
        if ($bank_table) {
            $name = 'bank';
            $doc_arr[]=$bank_table->pass_book_photo;
        }
         
        

        // temporary directory
        $tempDir = sys_get_temp_dir();
        // zip file name in full path
        $zipFilePath =  Yii::getAlias('@storage'). DIRECTORY_SEPARATOR  ;
        // $zipFilePath =  Yii::getAlias('@storage'). DIRECTORY_SEPARATOR .'upload'. DIRECTORY_SEPARATOR ;
       
        $zip =  new ZipArchive();
        // zip file name
        $zipFileName = $zipFilePath."archive/doc_" . time() . ".zip";
        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            die("Failed to create a zip archive. zipFilePath = $zipFilePath");
        }
        
        // file storage directory
        $storage = Yii::getAlias('@storage'). DIRECTORY_SEPARATOR ;
         
          // echo "<pre>";print_r($doc_arr);die();
       
        if (!empty($doc_arr) || isset($doc_arr) || $doc_arr != '') {
            foreach($doc_arr as $thefile)
            {
                
               if (!empty($thefile)) {
                    $zip->addFile($zipFilePath.$thefile);
                    $file_url = $storage.$thefile;

                     if(file_exists($file_url)){
                        $download_file = file_get_contents($file_url);
                        $zip->addFromString(basename($file_url),$download_file); 
                     }else{
                        // echo $file_url.'No file found or user has missed some file to upload';die();
                        Yii::$app->session->setFlash('danger', "'No file found for download or '".$enrollment_table->adhar_name."' has missed some file to upload'");
                        return $this->redirect(['/posting-history/transfer']);
                     }
                    
                   
               }
                   
                   
            }
          }  
         
         
        $zip->close();

        
       header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="'.basename($zipFileName).'"');
        header("Content-length: " . filesize($zipFileName));
        header("Pragma: no-cache");
        header("Expires: 0");
        ob_clean();
        flush();
        // readfile($zipFileName);
        // unlink($zipFileName);
        echo("Please see archive folder");
           
                    exit();
       

        
     
        
    }
    public function actionExit_employee()
    {
       
       // $emp_model = new Employee();
       if (Yii::$app->request->post()) {
          $emp_id = Yii::$app->request->post()['emp_id'];
          $exit_date = date('d-m-Y',strtotime(Yii::$app->request->post()['exit_date']));
          $comment = Yii::$app->request->post()['comment'];

          $emp_model = Employee::find()->where(['papl_id'=> $emp_id])->one();
            $emp_model->is_exit = '1';
            $emp_model->exit_date = $exit_date;
            $emp_model->exit_reason = $comment;
             $emp_model->save();

            $enr_model = Enrollment::find()->where(['papl_id'=> $emp_id])->one();
            $enr_model->comment = $comment;
            $enr_model->save();

            $posting_history_model = PostingHistory::find()->where(['papl_id'=> $emp_id])->andWhere(['is', 'end_date', new \yii\db\Expression('null')])->one();
            // $posting_history_model = PostingHistory::find()->where(['papl_id'=> $emp_id])->orderBy([
            //   'id' => SORT_DESC])->one();
            $posting_history_model->status = 1;
            $posting_history_model->end_date = $exit_date;
            $posting_history_model->save();
          
            return $this->redirect(['transfer']);
       }
    }
    public function actionEmployee_card(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Salary Update")){
            $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name'); 
            $plant_id = '';
             $posting_details = [];
            if (!empty(Yii::$app->request->post()['search'])) {
                $input = Yii::$app->request->post();
                $plant_id = $input['PostingHistory']['plant_id'];   
                $posting_details = PostingHistory::find()->where(['plant_id'=>$plant_id])->andWhere(['is', 'end_date', new \yii\db\Expression('null')])->all();         
                
            }
            return $this->render('employee_card', [
                'plants' => $plants,
                'plant_id' => $plant_id,
                'posting_details' => $posting_details,
                ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        } 
        
    }


    public function actionNomineedeclaration(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Salary Update")){
            $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name'); 
            $plant_id = '';
             $posting_details = [];
            if (!empty(Yii::$app->request->post()['search'])) {
                $input = Yii::$app->request->post();
                $plant_id = $input['PostingHistory']['plant_id'];   
                $posting_details = PostingHistory::find()->where(['plant_id'=>$plant_id])->andWhere(['is', 'end_date', new \yii\db\Expression('null')])->all();         
                
            }
            return $this->render('nomineedeclaration', [
                'plants' => $plants,
                'plant_id' => $plant_id,
                'posting_details' => $posting_details,
                
                ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }  
    }
    public function actionNomineedeclarationtwo(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Salary Update")){
            $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name'); 
            $plant_id = '';
             $posting_details = [];
            if (!empty(Yii::$app->request->post()['search'])) {
                $input = Yii::$app->request->post();
                $plant_id = $input['PostingHistory']['plant_id'];   
                $posting_details = PostingHistory::find()->where(['plant_id'=>$plant_id])->andWhere(['is', 'end_date', new \yii\db\Expression('null')])->all();         
                
            }
            return $this->render('nomineedeclarationtwo', [
                'plants' => $plants,
                'plant_id' => $plant_id,
                'posting_details' => $posting_details,
                
                ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }  
    }
    public function actionServicecertificate(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Salary Update")){
            $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name'); 
            $plant_id = '';
             $posting_details = [];
             $service_details = [];
            if (!empty(Yii::$app->request->post()['search'])) {
                $input = Yii::$app->request->post();
                $plant_id = $input['PostingHistory']['plant_id'];   
                $posting_details = PostingHistory::find()->where(['plant_id'=>$plant_id])->andWhere(['is', 'end_date', new \yii\db\Expression('null')])->all();         
                $service_details = PostingHistory::find()->where(['plant_id'=>$plant_id])->andWhere(['is', 'end_date', new \yii\db\Expression('null')])->all();         
                
            }
            return $this->render('servicecertificate', [
                'plants' => $plants,
                'plant_id' => $plant_id,
                'posting_details' => $posting_details,
                'service_details' => $service_details,
                
                ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }   

    }
    public function actionNewregisterformb(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Salary Update")){
            $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name'); 
            $plant_id = '';
             $posting_details = [];
            if (!empty(Yii::$app->request->post()['search'])) {
                $input = Yii::$app->request->post();
                $plant_id = $input['PostingHistory']['plant_id'];   
                $posting_details = PostingHistory::find()->where(['plant_id'=>$plant_id])->andWhere(['is', 'end_date', new \yii\db\Expression('null')])->all();         
                     
                
            }
            return $this->render('newregisterformb', [
                'plants' => $plants,
                'plant_id' => $plant_id,
                'posting_details' => $posting_details,
                
                ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        } 

    }
    public function actionNewregisterform_xii(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Salary Update")){
            $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name'); 
            $plant_id = $month = $syear = $days = '';
             $posting_details = [];
            if (!empty(Yii::$app->request->post()['search'])) {
                $input = Yii::$app->request->post();
                // echo '<pre>';print_r($input);die();
                $plant_id = $input['PostingHistory']['plant_id'];   
                $month = $input['PostingHistory']['month'];   
                $syear = $input['PostingHistory']['year'];  
                $days= (date('t', strtotime($month))); 
                // echo $days;die();
                $posting_details = PostingHistory::find()->where(['plant_id'=>$plant_id])->andWhere(['is', 'end_date', new \yii\db\Expression('null')])->all();   
                // foreach ($posting_details as $posting_detail) {
                //           $attendance_details = Attendance::find()->where(['papl_id'=>$posting_detail->papl_id])->all(); 
                //       }      
                  
                // echo '<pre>';print_r($attendance_details);die();      
                     
                
            }
            return $this->render('newregisterformxii', [
                'plants' => $plants,
                'plant_id' => $plant_id,
                'posting_details' => $posting_details,
                'smonth' => $month,
                'syear' => $syear,
                'days' => $days,
                
                ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }  

    }
     public function actionNewregisterform_xvi(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Salary Update")){
            $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name'); 
            $plant_id = '';
             $posting_details = [];
            if (!empty(Yii::$app->request->post()['search'])) {
                $input = Yii::$app->request->post();
                $plant_id = $input['PostingHistory']['plant_id'];   
                $posting_details = PostingHistory::find()->where(['plant_id'=>$plant_id])->andWhere(['is', 'end_date', new \yii\db\Expression('null')])->all();         
                     
                
            }
            return $this->render('newregisterformxvi', [
                'plants' => $plants,
                'plant_id' => $plant_id,
                'posting_details' => $posting_details,
                
                ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }  

    }
    public function actionNewregisterform_xix(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Salary Update")){
            $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name'); 
            $plant_id = '';
             $posting_details = [];
            if (!empty(Yii::$app->request->post()['search'])) {
                $input = Yii::$app->request->post();
                $plant_id = $input['PostingHistory']['plant_id'];   
                $posting_details = PostingHistory::find()->where(['plant_id'=>$plant_id])->andWhere(['is', 'end_date', new \yii\db\Expression('null')])->all();         
                     
                
            }
            return $this->render('newregisterformxix', [
                'plants' => $plants,
                'plant_id' => $plant_id,
                'posting_details' => $posting_details,
                
                ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);

         } 
          

    }
    public function actionAdultregister(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Salary Update")){
            $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name'); 
            $plant_id = '';
             $posting_details = [];
            if (!empty(Yii::$app->request->post()['search'])) {
                $input = Yii::$app->request->post();
                $plant_id = $input['PostingHistory']['plant_id'];   
                $posting_details = PostingHistory::find()->where(['plant_id'=>$plant_id])->andWhere(['is', 'end_date', new \yii\db\Expression('null')])->all();         
                     
                
            }
            return $this->render('adultregister', [
                'plants' => $plants,
                'plant_id' => $plant_id,
                'posting_details' => $posting_details,
                
                ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);

         }  

    }

    public function actionFinalsettlement(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Salary Update")){
            $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name'); 
            $plant_id = '';
             $posting_details = [];
            if (!empty(Yii::$app->request->post()['search'])) {
                $input = Yii::$app->request->post();
                $plant_id = $input['PostingHistory']['plant_id'];   
                $posting_details = PostingHistory::find()->where(['plant_id'=>$plant_id])->andWhere(['is', 'end_date', new \yii\db\Expression('null')])->all();         
                     
                
            }
            return $this->render('finalsettlement', [
                'plants' => $plants,
                'plant_id' => $plant_id,
                'posting_details' => $posting_details,
                
                ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);

         }  

    }
    public function actionGetcomparedate(){
        if(Yii::$app->request->isAjax){    
              $start_date = Yii::$app->request->post('start_date');
              $papl_id = Yii::$app->request->post('papl_id');
             $posting_data = PostingHistory::find()->where(['papl_id'=>$papl_id,'start_date' => $start_date])->all();
             
             
             }
    }
    //Report of Posting
    public function actionReport(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Posting Report")){
            $model = new PostingHistory();
            $posting_history=[];
            $search_result = 0;
            $state_short_code = $location_short_code = $plant_short_code = '';
            //$all_employee=ArrayHelper::map(Employee::find()->where(['is_exit' => 0])->all(), 'id', 'papl_id');
            $all_employee=ArrayHelper::map(Employee::find()
                                    ->where(['is_exit' => 0])
                                    ->orderBy([
                                          'papl_id' => SORT_ASC,
                                        ])
                                    ->with('papl')
                                    ->all(), 'papl_id', function ($model) {
                                                return $model['papl']['adhar_name'] . ' - ' . $model->papl_id;});
            //print_r($all_employee);exit;
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
            //echo $_POST['papl_id'];
            //$post_data=unserialize()
            if (!empty(Yii::$app->request->post())) {
                $input = Yii::$app->request->post()['PostingHistory'];
                //print_r($input);
                if($input['papl_id']){
                    $posting_history = PostingHistory::find()
                                                ->where(['papl_id' => $input['papl_id']])
                                                ->orderBy(['start_date' => SORT_ASC])->all();
                    $model['papl_id']=$input['papl_id'];
                }else{
                    $model['state_id']=$input['state_id'];
                    $model['location_id']=$input['location_id'];
                    $model['plant_id']=$input['plant_id'];
                    $model['status']=$input['status'];
                    
                    $state_short_code = State::find()->where(['state_id'=>$input['state_id']])->one(); 
                    $location_short_code = Location::find()->where(['location_id'=>$input['location_id']])->one(); 
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
                
                    if($model['status']=='current'){
                        $posting_history = PostingHistory::find()
                                                ->andWhere(['plant_id' => $input['plant_id']])
                                                ->andWhere(['status'=>0])
                                                ->andWhere(['end_date' => null])
                                                ->andFilterWhere(['purchase_orderid' =>$po_id])
                                                ->andFilterWhere(['section_id' =>$section_id])
                                                ->orderBy(['papl_id' => SORT_ASC])->all();
                    }elseif($model['status']=='exit'){
                        $posting_history = PostingHistory::find()
                                                ->andWhere(['plant_id' => $input['plant_id']])
                                                ->andWhere(['status'=>1])
                                                ->andWhere(['not', ['end_date' => null]])
                                                ->andFilterWhere(['purchase_orderid' =>$po_id])
                                                ->andFilterWhere(['section_id' =>$section_id])
                                                ->orderBy(['papl_id' => SORT_ASC])->all();

                    }elseif($model['status']=='all'){
                        $posting_history = PostingHistory::find()
                                            ->andWhere(['plant_id' => $input['plant_id']])
                                            ->andFilterWhere(['purchase_orderid' =>$po_id])
                                            ->andFilterWhere(['section_id' =>$section_id])
                                            ->orderBy(['papl_id' => SORT_ASC])->all();
                    }
                }
                
                
 
            }
            //$date = date('d-m-Y');
            //print_r($posting_history);
            return $this->render('posting_report', [
                'model' => $model,
                'states' => $states,
                'posting_history'=>$posting_history,
                'state_short_code' => $state_short_code,
               'location_short_code' => $location_short_code,
               'plant_short_code' => $plant_short_code,
               'search_result' => $search_result,
               'all_employee' => $all_employee,
            ]);
         }else{
             Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
             return $this->redirect(['/']);
 
          } 
    }

    public function actionDownload_posting_report($plant_id,$po_id,$section_id,$status,$papl_id){
        if($po_id==='null'){
            $po_id='';
        }
        if($section_id==='null'){
            $section_id='';
        }
        if($papl_id){
            $posting_history = PostingHistory::find()
                                            ->where(['papl_id' => $papl_id])
                                            ->orderBy(['start_date' => SORT_ASC])->all();
        }else{
            if($status=='current'){
                $posting_history = PostingHistory::find()
                                        ->andWhere(['plant_id' => $plant_id])
                                        ->andWhere(['status'=>0])
                                        ->andWhere(['end_date' => null])
                                        ->andFilterWhere(['purchase_orderid' =>$po_id])
                                        ->andFilterWhere(['section_id' =>$section_id])
                                        ->orderBy(['papl_id' => SORT_ASC])->all();
            }elseif($status=='exit'){
                $posting_history = PostingHistory::find()
                                        ->andWhere(['plant_id' => $plant_id])
                                        ->andWhere(['status'=>1])
                                        ->andWhere(['not', ['end_date' => null]])
                                        ->andFilterWhere(['purchase_orderid' =>$po_id])
                                        ->andFilterWhere(['section_id' =>$section_id])
                                        ->orderBy(['papl_id' => SORT_ASC])->all();
    
            }elseif($status=='all'){
                $posting_history = PostingHistory::find()
                                    ->andWhere(['plant_id' => $plant_id])
                                    ->andFilterWhere(['purchase_orderid' =>$po_id])
                                    ->andFilterWhere(['section_id' =>$section_id])
                                    ->orderBy(['papl_id' => SORT_ASC])->all();
            }
        }
        
        if($posting_history) {
            $basepath=Yii::getAlias('@storage');
            $filename="posting_report_".$plant_id.date('dmYhs').".xlsx";
            
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
            
            $sheet_blank->getColumnDimension('B')->setWidth(16);
            $sheet_blank->getColumnDimension('C')->setWidth(22);
            $sheet_blank->getColumnDimension('D')->setWidth(22);
            $row = 2;
            $sheet_blank->setCellValue('A'.$row, 'Sl No.');
            $sheet_blank->setCellValue('B'.$row, 'EmployeeID');
            $sheet_blank->setCellValue('C'.$row, 'NAME');
            $sheet_blank->setCellValue('D'.$row, 'DESIGNATION');
            $column = 'E';
            $sheet_blank->setCellValue($column.$row, 'State');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Location');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Plant');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Purchase Order No.');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Purchase Order Name');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Section Name');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Start Date');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'End Date');
            $column++;
            $sheet_blank->setCellValue($column.$row, 'Posting Salary');
            $column++;
            //$lastColumn = $sheet_blank->getHighestColumn();//echo $lastColumn;exit;
            $sheet_blank->getStyle('A1:'.$column.$row)->applyFromArray($styleArray);
            for ($i='E';$i!=$column;$i++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
            }
            $row++;
            $sl=1;
            foreach($posting_history as $emp){
                $sheet_blank->setCellValue('A' . $row, $sl);
                $sheet_blank->setCellValue('B' . $row, $emp['papl_id']);
                $sheet_blank->setCellValue('C' . $row, $emp->enrolement->adhar_name);
                $sheet_blank->setCellValue('D' . $row, $emp->enrolement->PAPLdesignation);
                $column="E";
                $sheet_blank->setCellValue($column.$row, $emp->state->state_name);
                $column++;
                $sheet_blank->setCellValue($column.$row, $emp->location->location_name);
                $column++;
                $sheet_blank->setCellValue($column.$row, $emp->plant->plant_name);
                $column++;
                $sheet_blank->setCellValue($column.$row, $emp->purchaseorder->po_number);
                $column++;
                $sheet_blank->setCellValue($column.$row, $emp->purchaseorder->purchase_order_name);
                $column++;
                $sheet_blank->setCellValue($column.$row, $emp->section->section_name);
                $column++;
                $sheet_blank->setCellValue($column.$row, $emp->start_date);
                $column++;
                $sheet_blank->setCellValue($column.$row, $emp->end_date);
                $column++;
                $sheet_blank->setCellValue($column.$row, $emp->posting_salary);
                $column++;


                $row++;
            $sl++;
            }
            $sheet_blank->setTitle('Posting History Report');
        
            $writer = new Xlsx($spreadsheet);
            $writer->save($basepath . '/reports/'.$filename);
            echo Yii::getAlias('@storageUrl')."/reports/".$filename;exit;
        }else{
            echo false;exit;
        } 
    }


    
}
