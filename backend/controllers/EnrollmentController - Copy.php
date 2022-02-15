<?php

namespace backend\controllers;

use Yii;
use common\models\Plant;
use common\models\Qualification;
use common\models\BankDetails;
use common\models\PostingHistory;
use common\models\Nominee;
use common\models\User;
use common\models\Family;
use common\models\Document;
use common\models\Papldesignation;
use common\models\State;
use common\models\Enrollment;
use common\models\EnrollmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\Request;
use yii\helpers\ArrayHelper;
use common\helpers\acl;

//require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * EnrollmentController implements the CRUD actions for Enrollment model.
 */
class EnrollmentController extends Controller
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
     * Lists all Enrollment models.
     * @return mixed
     */
    public function actionIndex($type=0)
    {
        $type=Yii::$app->request->queryParams['type'];
        $role=Yii::$app->user->identity->role_id;
        if(!acl::checkAcess(Yii::$app->user->identity->id,"Enrollment") && $type==0){
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }elseif(!acl::checkAcess(Yii::$app->user->identity->id,"Plant Head Approve") && $type==1){
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }elseif(!acl::checkAcess(Yii::$app->user->identity->id,"EPF/ESIC") && $type==2){
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }elseif(!acl::checkAcess(Yii::$app->user->identity->id,"Appointment") && $type==3){
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }elseif(!acl::checkAcess(Yii::$app->user->identity->id,"Rejected") && $type==4){
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }
        // if(($role==4)&& ($type==0 || $type==4 || $type==1)){
        //     Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
        //     return $this->redirect(['index','type' => 2]);
        // }elseif(($role==2)&& ($type==0 || $type==4 || $type==2)){
        //     Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
        //     return $this->redirect(['index','type' => 1]);
        // }elseif($role==3 && ($type==2|| $type==1)){
        //     Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
        //     return $this->redirect(['index','type' => 0]);
        // }

         $user_papl_id=Yii::$app->user->identity->username;
         $user_plant_id = PostingHistory::find()->where(['papl_id'=>$user_papl_id])->one();
         

        $searchModel = new EnrollmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'role' => $role,
            'user_plant_id' => $user_plant_id,
        ]);
    }

    /**
     * Displays a single Enrollment model.
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
     * Creates a new Enrollment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Enrollment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
       
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Enrollment model.
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
     * Deletes an existing Enrollment model.
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
     * Finds the Enrollment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Enrollment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Enrollment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionEnrole()
    {
        if(acl::checkAcess(Yii::$app->user->identity->id,"Create Enrollment")){
        //if(Yii::$app->user->identity->role_id==1 || Yii::$app->user->identity->role_id==3){
            $qualification_model = new Qualification();
            $bank_model = new BankDetails();
            $document_model = new Document();
            $nominee_model = new Nominee();
            $family_model = new Family();
            $model = new Enrollment();
            $prev_status = '';
            $papldesignation_list =  ArrayHelper::map(Papldesignation::find()->where(['status' => 0])->all(), 'PAPLdesignation', 'PAPLdesignation');
            $updated_details = Enrollment::find()->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
            $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();

            $updated_details_qualification = Qualification::find()->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
            $updated_details_name_qualification = User::find()->where(['id' => $updated_details->updated_by])->one();

            

            if(!empty(Yii::$app->request->get('enrolementid')) || !empty(Yii::$app->request->get('papl_id'))){
                $enrolmentid=Yii::$app->request->get('enrolementid');
                $papl_id=Yii::$app->request->get('papl_id');
                $tag=Yii::$app->request->get('tag');
                // echo $papl_id;die();
                
                if(!empty(Yii::$app->request->get('papl_id'))){
                    $enrolmentid=Yii::$app->request->get('papl_id');
                    //  $result = preg_replace("/[^a-zA-Z]{2}+/", "", $enrolmentid);
                    // $str = substr($result, 0,2);
                }elseif(!empty(Yii::$app->request->get('enrolementid'))){
                    $enrolmentid=Yii::$app->request->get('enrolementid');
                    // $result = preg_replace("/[^a-zA-Z]{2}+/", "", $enrolmentid);
                    // $str = substr($result, 0,2);
                }else{
                    $enrolmentid=$this->request->post()['Enrollment']['enrolement_id'];
                    //  $result = preg_replace("/[^a-zA-Z]{2}+/", "", $enrolmentid);
                    // $str = substr($result, 0,2);
                }
                // if ($papl_id) {
                //     $model=Enrollment::find()->where(['papl_id'=>$enrolmentid])->one();
                // }else{
                    // echo $str;die();
                    // if ($str == 'PA') {
                    //     // $enrolmentid = str_replace('PAPL', 'TEMP', $enrolmentid);
                    //     $model=Enrollment::find()->where(['papl_id'=>$enrolmentid])->one();
                    // }else{
                        $model=Enrollment::find()->where(['enrolement_id'=>$enrolmentid])->one();
                    // }
                    
                    
                    $prev_status = $model->status;
                    if ($prev_status == 4) {
                    $model->status = 0;
                    }
                // }
            // echo "here";die();
            }
            elseif($this->request->post() && isset($this->request->post()['Enrollment']['enrolement_id']))

            {
                // echo "<pre>";print_r($this->request->post());exit();
                if(!empty(Yii::$app->request->get('papl_id'))){
                    $enrolmentid=Yii::$app->request->get('papl_id');
                }elseif(!empty(Yii::$app->request->get('enrolementid'))){
                    $enrolmentid=Yii::$app->request->get('enrolementid');
                }else{
                    $enrolmentid=$this->request->post()['Enrollment']['enrolement_id'];
                }
                // $papl_id=Yii::$app->request->get('papl_id');
                // if ($papl_id) {
                //     $model=Enrollment::find()->where(['papl_id'=>$papl_id])->one();
                // }else{
                    $model=Enrollment::find()->where(['enrolement_id'=>$enrolmentid])->one();
                    $prev_status = $model->status;
                    if ($prev_status == 4) {
                        $model->status = 0;
                    }
                // }
                
                $tag='qualification';
            }
            else{
                $tag='';
            $model = new Enrollment();
            $lastId = Enrollment::find()->orderBy('id DESC')->one();
            if($lastId){
                $lastID =  $lastId->id;
                $idd = str_replace("TEMP", "", $lastID);
                $id = str_pad($idd+1, 10,0,STR_PAD_LEFT);            
                $enrolmentid = "TEMP".$id;
                }else{
                        $enrolmentid = "TEMP000001";
                }
            }
            $role_id=Yii::$app->user->identity->username;
            $role=Yii::$app->user->identity->role_id;
            $user_plant_id = PostingHistory::find()->where(['papl_id'=>$role_id])
                                                  ->andWhere(['end_date' => null])->one();
            if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
              $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name');
            }else{
              $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0,'location_id'=>$user_plant_id->location_id])->all(), 'plant_id', 'plant_name');
            }
            
            
            $states = ArrayHelper::map(State::find()->where(['is_delete' => 0])->all(), 'state_id', 'state_name');
        
                if ($model->load(Yii::$app->request->post())) {
                    // print_r($this->request->post());
                    $filename = UploadedFile::getInstance($model, 'browse_adhar');
                    if($filename){
                        $file= $model->imageupload($filename);
                        $model->browse_adhar=$file;
                    }
                    $pp_photo = UploadedFile::getInstance($model, 'browse_pp_photo');
                    if($pp_photo){
                        $pp_photo_file= $model->imageupload($pp_photo);
                        $model->browse_pp_photo=$pp_photo_file;
                    }
                    $model->enrolement_id=$enrolmentid;
                    $model->dob=Yii::$app->request->post()['Enrollment']['dob'];
                    $model->created_by = Yii::$app->user->identity->id;
                    $model->updated_by = Yii::$app->user->identity->id;
                
                    if($model->save()){
                    // Yii::$app->session->setFlash('success', 'Data inserted successfully');
                        if(Yii::$app->request->post('master')=='master')
                            $tag='qualification';

                        return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag]);
                    }else{
                        Yii::$app->session->setFlash('error', json_encode($model->getErrors()));
                    }
                    
                }
        
            
    


            return $this->render('enrole', [
                'model' => $model,
                'enrolmentid' => $enrolmentid,
                'qualification_model' => $qualification_model,
                'bank_model' => $bank_model,
                'plants' => $plants,
                'states' => $states,
                'tag'=>$tag,
                'document_model' => $document_model,
                'nominee_model' => $nominee_model,
                'family_model' => $family_model,
                'prev_status' => $prev_status,
                'user_plant_id' => $user_plant_id,
                'role' => $role,
                'papldesignation_list' => $papldesignation_list,
                'updated_details_name' => $updated_details_name,
                'updated_details_name_qualification' => $updated_details_name_qualification,
            ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
         }
       
    }

     public function actionEnrollupdate($id)
    {
        $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', function ($model) {
		return $model->plant_name;});
		$model = $this->findModel($id);
        $oldfile = $model->browse_adhar;
        $pp_photo_oldfile = $model->browse_pp_photo;
         $file_name='';
         $pp_file_name='';
        if ($model->load($this->request->post())) {
            $filename = UploadedFile::getInstance($model, 'browse_adhar');
            if ($filename) {
                $file= $model->fileupload($filename);
                $file_name=$file;
            }else{
                $file_name=$oldfile;
            }
            $model->browse_adhar=$file_name;
            $pp_photo = UploadedFile::getInstance($model, 'browse_pp_photo');                
            if ($pp_photo) {
                $pp_photo_file= $model->fileupload($pp_photo);
                $pp_file_name = $pp_photo_file;
            }else{
                $pp_file_name = $pp_photo_oldfile;
            }
            $model->browse_pp_photo = $pp_file_name;

                if($model->save()){
                    Yii::$app->session->setFlash('success', 'Data updated successfully');
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{
                    Yii::$app->session->setFlash('success', json_encode($model->getErrors()));
                }
                
        }
        
         else {
            $model->loadDefaultValues();
        }

        return $this->render('enrole', [
            'model' => $model,
            'plants' => $plants,
        ]);
    }
    public function actionQualification(){
        
        if(!empty(Yii::$app->request->get('enrolementid')) ){
          $enrolmentid=Yii::$app->request->get('enrolementid');  
        }elseif (!empty(Yii::$app->request->get('papl_id'))) {
            $enrolmentid=Yii::$app->request->get('papl_id'); 
        }else{
            $enrolmentid=Yii::$app->request->post('enrolementid');
        }


        $qualification_model = new Qualification();
        $tag='';
         $msg ='';
        if ($qualification_model->load($this->request->post())) {
         // echo '<pre>';print_r($this->request->post());die();
            $qualification_model=Qualification::find()->where(['enrolement_id'=>$enrolmentid])->one();
            
                $userData = count($this->request->post()['Qualification']['university_name']);
           
            if ($userData > 0) {
                
                    Qualification::deleteAll(['enrolement_id'=>$enrolmentid]);
                
                
                for ($i=0; $i < $userData; $i++) { 
                    if(!empty(Yii::$app->request->post()['Qualification']['university_name'][$i]) && isset(Yii::$app->request->post()['Qualification']['university_name'][$i]) && Yii::$app->request->post()['Qualification']['university_name'][$i] != '')
                    {
                          // echo "<pre>";print_r($this->request->post()['Qualification']);
                            $qualification_model = new Qualification();
                            $university_name   = $this->request->post()['Qualification']['university_name'][$i];
                            $college_name  = $this->request->post()['Qualification']['college_name'][$i];
                            $year_of_passout  = $this->request->post()['Qualification']['year_of_passout'][$i];
                            $division_percent  = $this->request->post()['Qualification']['division_percent'][$i];
                            $highest_qualification  = $this->request->post()['Qualification']['highest_qualification'][$i];
                            $qualification_model->enrolement_id=$enrolmentid;
                            $qualification_model->university_name=$university_name;
                            $qualification_model->college_name=$college_name;
                            $qualification_model->year_of_passout=$year_of_passout;
                            $qualification_model->division_percent=$division_percent;

                            $qualification_model->created_by=Yii::$app->user->identity->id;
                            $qualification_model->updated_by=Yii::$app->user->identity->id;
                            $qualification_model->highest_qualification=$highest_qualification;
                           
                            
                             $qualification_documents = UploadedFile::getInstances($qualification_model, 'qualification_document');
                            
                             // echo "<pre>";var_dump($qualification_documents);
                                if (isset($qualification_documents)) {
                                    $basepath = Yii::getAlias('@storage');
                                    $randnum = Yii::$app->security->generateRandomString();
                                    $file = '/upload/' . $randnum .'.'. $qualification_documents[$i]->extension;
                                    $path = $basepath . $file;
                                    $qualification_documents[$i]->saveAs($path);
                                    $qualification_model->qualification_document=$file;
                                }
                                    
                                    
                                    if($qualification_model->save())
                                    {
                                            $msg .= "";
                                            // echo $imagen;
                                    }else{
                                         $msg .= 'error '.json_encode($qualification_model->getErrors());
                                    }
                               
                            
                               
                                // var_dump($qualification_model->getErrors());
                    }
                     // die();
                   
                }
            }

            if($msg == ''){
                if(Yii::$app->request->post('Qualification'))
                    $tag='internal';
                return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag]);
            }else{
                $tag='qualification';
                return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag,'msg' => $msg]);
            }
                
            }
            
  
    }
	public function actionInternal()
    {
        if(!empty(Yii::$app->request->get('enrolementid')) ){
          $enrolmentid=Yii::$app->request->get('enrolementid');  
        }elseif (!empty(Yii::$app->request->get('papl_id'))) {
            $enrolmentid=Yii::$app->request->get('papl_id'); 
        }else{
            $enrolmentid=Yii::$app->request->post('enrolementid');
        }

        $tag=''; 
        if (Yii::$app->request->get('papl_id')) {
            $model = Enrollment::find()->where(['papl_id' => $enrolmentid])->one(); 
        }else{
          $model = Enrollment::find()->where(['enrolement_id' => $enrolmentid])->one();   
        }
        
        // var_dump($this->request->post());
         if ($model->load($this->request->post())) {
                $filename = UploadedFile::getInstance($model, 'browse_experience');
                // $ext = explode('.', $filename);
                // $type = $ext[1];
                // print_r($type);exit();
                if($filename){                     
                    $file= $model->fileupload($filename);
                    $model->browse_experience=$file;
                }   
                $model->designation=$this->request->post()['Enrollment']['designation'];
                $model->category=$this->request->post()['Enrollment']['category'];
                $model->PAPLdesignation=$this->request->post()['Enrollment']['PAPLdesignation'];
                $model->experience=$this->request->post()['Enrollment']['experience'];
                $model->created_by = Yii::$app->user->identity->id;
                $model->updated_by = Yii::$app->user->identity->id;
                // $model->browse_experience=$this->request->post()['Enrollment']['browse_experience'];
                if($model->save()){
                    if(Yii::$app->request->post('internal'))
                        $tag='epf';                    
                    return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag]);
                }else{
                    var_dump($model->getErrors());
                }
                
            }  
    }
	public function actionEpf()
    {
        // $enrolmentid=Yii::$app->request->post('enrolementid');
        if(!empty(Yii::$app->request->get('enrolementid')) ){
          $enrolmentid=Yii::$app->request->get('enrolementid');  
        }elseif (!empty(Yii::$app->request->get('papl_id'))) {
            $enrolmentid=Yii::$app->request->get('papl_id'); 
        }else{
            $enrolmentid=Yii::$app->request->post('enrolementid');
        }
        $tag=''; 
        if (Yii::$app->request->get('papl_id')) {
            $model = Enrollment::find()->where(['papl_id' => $enrolmentid])->one();
        }else{
           $model = Enrollment::find()->where(['enrolement_id' => $enrolmentid])->one();  
        }
        
        $model->scenario = 'epf_esic';
        
         if ($model->load($this->request->post())) {
                $model->created_by = Yii::$app->user->identity->id;
                $model->updated_by = Yii::$app->user->identity->id;
                $uan_sheet = UploadedFile::getInstance($model, 'uan_sheet');
                if($uan_sheet){
                    $filesheet= $model->fileupload($uan_sheet);
                    $model->uan_sheet=$filesheet;
                }   
                $esic_sheet = UploadedFile::getInstance($model, 'esic_sheet');
                if($esic_sheet){
                    $file= $model->fileupload($esic_sheet);
                    $model->esic_sheet=$file;
                }   
                
                
                if($model->save()){
                    if(Yii::$app->request->post('epf'))
                        $tag='bank';                    
                    return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag]);
                }else{
                    var_dump($model->getErrors());
                }
                
            }  
    }
   public function actionBank()
    {
        if( !empty(Yii::$app->request->get('enrolementid'))){
          $enrolmentid=Yii::$app->request->get('enrolementid');
          $result = preg_replace("/[^a-zA-Z]{2}+/", "", $enrolmentid);
          $str = substr($result, 0,2);  
        }elseif (!empty(Yii::$app->request->get('papl_id'))) {
            $enrolmentid=Yii::$app->request->get('papl_id');
            $result = preg_replace("/[^a-zA-Z]{2}+/", "", $enrolmentid);
            $str = substr($result, 0,2);  
        }else{
            $enrolmentid=Yii::$app->request->post('enrolementid');
            $result = preg_replace("/[^a-zA-Z]{2}+/", "", $enrolmentid);
            $str = substr($result, 0,2); 
        }
        $tag='';
            $bank_model = new BankDetails();
        if ($bank_model->load($this->request->post())) {
          if (Yii::$app->request->get('papl_id')) {
                $bank_model = BankDetails::find()->where(['papl_id'=>$enrolmentid])->one();
            }else{
                $bank_model = BankDetails::find()->where(['enrolement_id'=>$enrolmentid])->one();
            }
            

            
            if (empty(($bank_model))) {

                $bank_model = new BankDetails();
                $pass_book_photo = UploadedFile::getInstance($bank_model, 'pass_book_photo');
                if($pass_book_photo){
                    $file= $bank_model->fileupload($pass_book_photo);
                    $bank_model->pass_book_photo=$file;
                }   
                    
                $bank_model->enrolement_id =$enrolmentid;
                $bank_model->transaction_id = Yii::$app->request->post()['BankDetails']['transaction_id'];
                $bank_model->IFSC = Yii::$app->request->post()['BankDetails']['IFSC'];
                $bank_model->name_passbook = Yii::$app->request->post()['BankDetails']['name_passbook'];
                $bank_model->name_bank = Yii::$app->request->post()['BankDetails']['name_bank'];
                $bank_model->bank_account_number = Yii::$app->request->post()['BankDetails']['bank_account_number'];
                $bank_model->name_branch = Yii::$app->request->post()['BankDetails']['name_branch'];
                $bank_model->created_by = Yii::$app->user->identity->id;
                $bank_model->updated_by = Yii::$app->user->identity->id;

            }else{
                $bank_model = BankDetails::find()->where(['enrolement_id'=>$enrolmentid])->one();
                // $emp_model->papl_id = $papl_id;
                $bank_model->transaction_id = Yii::$app->request->post()['BankDetails']['transaction_id'];
                $bank_model->IFSC = Yii::$app->request->post()['BankDetails']['IFSC'];
                $bank_model->name_passbook = Yii::$app->request->post()['BankDetails']['name_passbook'];
                $bank_model->name_bank = Yii::$app->request->post()['BankDetails']['name_bank'];
                $bank_model->bank_account_number = Yii::$app->request->post()['BankDetails']['bank_account_number'];
                $bank_model->name_branch = Yii::$app->request->post()['BankDetails']['name_branch'];
                $bank_model->created_by = Yii::$app->user->identity->id;
                $bank_model->updated_by = Yii::$app->user->identity->id;
                // $emp_model->save();
                $pass_book_photo = UploadedFile::getInstance($bank_model, 'pass_book_photo');
                if($pass_book_photo){
                    $file= $bank_model->fileupload($pass_book_photo);
                    $bank_model->pass_book_photo=$file;
                }  

            }
            $msg ='';
            

            if($bank_model->save())
            {
                // var_dump($bank_model->getErrors());die();
                $msg .= "";
            }else{
                $msg .= 'error '.json_encode($bank_model->getErrors());
            }
             if($msg == ''){
                if(Yii::$app->request->post('bank'))
                    $tag='bank';
                return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag]);
            }else{
            $tag='bank';
            return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag,'msg' => $msg]);
            }
                
                
            }  
    }
   public function actionDocument()
    {
        if( !empty(Yii::$app->request->get('enrolementid'))){
          $enrolmentid=Yii::$app->request->get('enrolementid');  
        }elseif (!empty(Yii::$app->request->get('papl_id'))) {
            $enrolmentid=Yii::$app->request->get('papl_id'); 
        }else{
            $enrolmentid=Yii::$app->request->post('enrolementid');
        }
        $tag='';
        $document_model = new Document(); 

        if ($document_model->load($this->request->post())) {
            if (Yii::$app->request->get('papl_id')) {
                $document_model = Document::find()->where(['papl_id'=>$enrolmentid])->one();
            }else{
                $document_model = Document::find()->where(['enrolement_id'=>$enrolmentid])->one();
            }
            
            if (empty($document_model)) {
                $document_model = new Document();
                $voter_copy_photo = UploadedFile::getInstance($document_model, 'voter_copy_photo');
                if($voter_copy_photo){
                    $file= $document_model->fileupload($voter_copy_photo);
                    $document_model->voter_copy_photo=$file;
                }   
                $drivinglicense_photo = UploadedFile::getInstance($document_model, 'drivinglicense_photo');
                if($drivinglicense_photo){
                    $file= $document_model->fileupload($drivinglicense_photo);
                    $document_model->drivinglicense_photo=$file;
                }   
                $pan_photo = UploadedFile::getInstance($document_model, 'pan_photo');
                if($pan_photo){
                    $file= $document_model->fileupload($pan_photo);
                    $document_model->pan_photo=$file;
                }   
                $passport_photo = UploadedFile::getInstance($document_model, 'passport_photo');
                if($passport_photo){
                    $file= $document_model->fileupload($passport_photo);
                    $document_model->passport_photo=$file;
                } 
                $document_model->enrolement_id =$enrolmentid;
                $document_model->voter_id_number=Yii::$app->request->post()['Document']['voter_id_number'];
                $document_model->dl_number=Yii::$app->request->post()['Document']['dl_number'];
                $document_model->dl_expiry_date=Yii::$app->request->post()['Document']['dl_expiry_date'];
                $document_model->pannumber=Yii::$app->request->post()['Document']['pannumber'];
                $document_model->passportnumber=Yii::$app->request->post()['Document']['passportnumber'];
                $document_model->passport_expirydate=Yii::$app->request->post()['Document']['passport_expirydate'];
                $document_model->created_by = Yii::$app->user->identity->id;
                $document_model->updated_by = Yii::$app->user->identity->id;
            }else{
                $document_model = Document::find()->where(['enrolement_id'=>$enrolmentid])->one();
                $document_model->voter_id_number=Yii::$app->request->post()['Document']['voter_id_number'];
                $document_model->dl_number=Yii::$app->request->post()['Document']['dl_number'];
                $document_model->dl_expiry_date=Yii::$app->request->post()['Document']['dl_expiry_date'];
                $document_model->pannumber=Yii::$app->request->post()['Document']['pannumber'];
                $document_model->passportnumber=Yii::$app->request->post()['Document']['passportnumber'];
                $document_model->passport_expirydate=Yii::$app->request->post()['Document']['passport_expirydate'];
                $document_model->created_by = Yii::$app->user->identity->id;
                $document_model->updated_by = Yii::$app->user->identity->id;
                $voter_copy_photo = UploadedFile::getInstance($document_model, 'voter_copy_photo');
                if($voter_copy_photo){
                    $file= $document_model->fileupload($voter_copy_photo);
                    $document_model->voter_copy_photo=$file;
                }   
                $drivinglicense_photo = UploadedFile::getInstance($document_model, 'drivinglicense_photo');
                if($drivinglicense_photo){
                    $file= $document_model->fileupload($drivinglicense_photo);
                    $document_model->drivinglicense_photo=$file;
                }   
                $pan_photo = UploadedFile::getInstance($document_model, 'pan_photo');
                if($pan_photo){
                    $file= $document_model->fileupload($pan_photo);
                    $document_model->pan_photo=$file;
                }   
                $passport_photo = UploadedFile::getInstance($document_model, 'passport_photo');
                if($passport_photo){
                    $file= $document_model->fileupload($passport_photo);
                    $document_model->passport_photo=$file;
                } 
               
            }
            
            $msg ='';
            
            if($document_model->save())
                    $msg .= "";
            else
                $msg .= 'error '.json_encode($document_model->getErrors());
           
           
             if($msg == ''){
                if(Yii::$app->request->post('document'))
                    $tag='nominee';
                return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag]);
            }else{
            $tag='bank';
            return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag,'msg' => $msg]);
            }
                
                
        }  
    }
    public function actionNominee()
    {
        if( !empty(Yii::$app->request->get('enrolementid'))){
          $enrolmentid=Yii::$app->request->get('enrolementid');  
        }elseif (!empty(Yii::$app->request->get('papl_id'))) {
            $enrolmentid=Yii::$app->request->get('papl_id'); 
        }else{
            $enrolmentid=Yii::$app->request->post('enrolementid');
        }
        $tag=''; 
        // $nominee_model = Nominee::find()->where(['enrolement_id' => $enrolmentid])->one();
        $nominee_model = new Nominee();
         if ($nominee_model->load($this->request->post())) {
                if (Yii::$app->request->get('papl_id')) {
                    $nominee_model = Nominee::find()->where(['papl_id'=>$enrolmentid])->one();
                }else{
                    $nominee_model = Nominee::find()->where(['enrolement_id'=>$enrolmentid])->one();
                }
                
                // new
                if (empty($nominee_model)) {
                $nominee_model = new Nominee();
                $nominee_model->enrolement_id =$enrolmentid;
                $nominee_model->created_by = Yii::$app->user->identity->id;
                $nominee_model->updated_by = Yii::$app->user->identity->id;
                $nominee_model->nominee_name=Yii::$app->request->post()['Nominee']['nominee_name'];
                $nominee_model->nominee_relation=Yii::$app->request->post()['Nominee']['nominee_relation'];
                $nominee_model->nominee_dob=Yii::$app->request->post()['Nominee']['nominee_dob'];
                $nominee_model->nominee_adhar_number=Yii::$app->request->post()['Nominee']['nominee_adhar_number'];
                $nominee_adhar_photo = UploadedFile::getInstance($nominee_model, 'nominee_adhar_photo');
                if($nominee_adhar_photo){
                    $file= $nominee_model->imageupload($nominee_adhar_photo);
                    $nominee_model->nominee_adhar_photo=$file;
                } 
                $nominee_model->nominee_address=Yii::$app->request->post()['Nominee']['nominee_address'];
            }else{
                $nominee_model = Nominee::find()->where(['enrolement_id'=>$enrolmentid])->one();
                $nominee_model->created_by = Yii::$app->user->identity->id;
                $nominee_model->updated_by = Yii::$app->user->identity->id;
                $nominee_model->nominee_name=Yii::$app->request->post()['Nominee']['nominee_name'];
                $nominee_model->nominee_relation=Yii::$app->request->post()['Nominee']['nominee_relation'];
                $nominee_model->nominee_dob=Yii::$app->request->post()['Nominee']['nominee_dob'];
                $nominee_model->nominee_adhar_number=Yii::$app->request->post()['Nominee']['nominee_adhar_number'];
                $nominee_model->nominee_adhar_photo=Yii::$app->request->post()['Nominee']['nominee_adhar_photo'];
                $nominee_model->nominee_address=Yii::$app->request->post()['Nominee']['nominee_address'];
                $nominee_adhar_photo = UploadedFile::getInstance($nominee_model, 'nominee_adhar_photo');
                if($nominee_adhar_photo){
                    $file= $nominee_model->imageupload($nominee_adhar_photo);
                    $nominee_model->nominee_adhar_photo=$file;
                }   
                
            }
                // new


                $msg ='';
                if($nominee_model->save())
                    $msg .= "";
                else
                $msg .= 'error '.json_encode($nominee_model->getErrors());           
                 if($msg == ''){
                    if(Yii::$app->request->post('nominee'))
                        $tag='family';
                    return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag]);
                }else{
                $tag='nominee';
                return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag,'msg' => $msg]);
                }
               
                
            }  
    }
    public function actionFamily()
    {
        if( !empty(Yii::$app->request->get('enrolementid'))){
          $enrolmentid=Yii::$app->request->get('enrolementid');  
        }elseif (!empty(Yii::$app->request->get('papl_id'))) {
            $enrolmentid=Yii::$app->request->get('papl_id'); 
        }else{
            $enrolmentid=Yii::$app->request->post('enrolementid');
        }
        $tag=''; 
        $msg ='';
        $family_model = new Family(); 
        if ($family_model->load($this->request->post())) {
                $userData = count($this->request->post()['Family']['family_member']);
                if ($userData > 0) {
                // if (Yii::$app->request->get('papl_id')) {
                //     Family::deleteAll(['papl_id'=>$enrolmentid]);

                // }else{
                    Family::deleteAll(['enrolement_id'=>$enrolmentid]);
                   
                // }
                
                // $msg ='';
                for ($i=0; $i < $userData; $i++) { 
                    if(Yii::$app->request->post()['Family']['family_member'][$i] !='' && isset(Yii::$app->request->post()['Family']['family_member'][$i]) && !empty(Yii::$app->request->post()['Family']['family_member'][$i]))
                    {
                        // echo "<pre>";print_r($this->request->post()['Family']);
                            $family_model = new Family();
                            $family_member   = $this->request->post()['Family']['family_member'][$i];
                            $family_member_dob  = $this->request->post()['Family']['family_member_dob'][$i];
                            $family_member_relation  = $this->request->post()['Family']['family_member_relation'][$i];
                            $family_member_residence  = $this->request->post()['Family']['family_member_residence'][$i];
                            $family_model->enrolement_id=$enrolmentid;
                            $family_model->family_member=$family_member;
                            $family_model->family_member_dob=$family_member_dob;
                            $family_model->family_member_relation=$family_member_relation;
                            $family_model->family_member_residence=$family_member_residence;
                            $family_nominee_adhar_photos = UploadedFile::getInstances($family_model, 'family_nominee_adhar_photo');

                            $family_model->created_by = Yii::$app->user->identity->id;
                            $family_model->updated_by = Yii::$app->user->identity->id;
                            // echo "<pre>";print_r($family_nominee_adhar_photos);
                            if (!empty($family_nominee_adhar_photos[$i]) && isset($family_nominee_adhar_photos[$i])) {
                                $basepath = Yii::getAlias('@storage');
                                $randnum = Yii::$app->security->generateRandomString();
                                $file = '/upload/' . $randnum .'.'. $family_nominee_adhar_photos[$i]->extension;
                                $path = $basepath . $file;
                                $family_nominee_adhar_photos[$i]->saveAs($path);
                                $family_model->family_nominee_adhar_photo=$file;
                             }  
                   
                            if($family_model->save())
                                    $msg .= "";
                            else
                                $msg .= 'error '.json_encode($family_model->getErrors());
                    }
                   // die();
                }
            }
            if($msg == ''){
                    if(Yii::$app->request->post('family'))
                        $tag='';
                    // return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag]);
                    return $this->redirect(['index','type' => 0]);
                }else{
                $tag='family';
                return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag,'msg' => $msg]);
                }
                
        }  
    }
    public function actionMaster_import($plant_id){
		//echo $plant_id;exit;
		$model=new Enrollment();
		$basepath=Yii::getAlias('@storage');
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			if ($model->load(Yii::$app->request->post())) {
				
				$model->import_file = UploadedFile::getInstance($model, 'import_file');
				$filename=UploadedFile::getInstance($model, 'import_file');
				//echo $filename;exit;
				if($filename) {
				  $model->import_file->saveAs($basepath . '/import/master/'.$filename);
				  $handle = fopen($basepath . '/import/master/'.$filename, "r");
				  $counter = 0;
				}
				
				//header('Content-Type: text/csv');
				//header('Content-Disposition: attachment; filename="qualification.csv"');
				//$qualif_columns = ["Enrolement Id","Qualification if any","University/Board","College/Institute Name","Year of Passout","Division/Percent", "Qualification"];
				//$fp = fopen($basepath.'\sample\qualification.csv', 'wb');
				//fputcsv($fp,$qualif_columns);
				
				while (($fileop = fgetcsv($handle, 10000, ",")) !== false) {
					 if($counter<=0){
					   $counter++;
					   continue;
					 }
					 $lastId = Enrollment::find()->orderBy('id DESC')->one();
					if($lastId){
						$lastID =  $lastId->id;
						$idd = str_replace("TEMP", "", $lastID);
						$id = str_pad($idd+1, 10,0,STR_PAD_LEFT);            
						$enrolmentid = "TEMP".$id;
					}else{
						$enrolmentid = "TEMP0000000001";
					}
					$model=new Enrollment();
                    
					$model->plant_id=$plant_id;
					$model->enrolement_id=$enrolmentid;
					$model->adhar_name=$fileop[1];
					$model->father_husband_name=$fileop[2];
					$model->relation_with_employee=$fileop[3];
					$model->adhar_number=$fileop[4];
					$model->gender=$fileop[5];
					$model->marital_status=$fileop[6];
					$model->mobile_with_adhar=$fileop[7];
					$model->dob=$fileop[8];
                    
					//Permanent
					$model->permanent_addrs=$fileop[9];
					$model->permanent_state=$fileop[10];
					$model->permanent_district=$fileop[11];
					$model->permanent_ps=$fileop[12];
					$model->permanent_po=$fileop[13];
					$model->permanent_village=$fileop[14];
					$model->permanent_block=$fileop[15];
					$model->permanent_tehsil=$fileop[16];
					$model->permanent_GP=$fileop[17];
					$model->permanent_pincode=$fileop[18];
					$model->permanent_mobile_number=$fileop[19];
					//Present
					$model->present_address=$fileop[20];
					$model->present_state=$fileop[21];
					$model->present_district=$fileop[22];
					$model->present_ps=$fileop[23];
					$model->present_po=$fileop[24];
					$model->present_village=$fileop[25];
					$model->present_block=$fileop[26];
					$model->present_tehsil=$fileop[27];
					$model->present_gp=$fileop[28];
					$model->present_pincode=$fileop[29];
					$model->present_mobile_number=$fileop[30];
					//Other
					$model->blood_group=$fileop[31];
					$model->ID_mark_employee=$fileop[32];
					$model->nationality=$fileop[33];
					$model->religion=$fileop[34];
					$model->caste=$fileop[35];
                    $model->status=0;
                    
					if($model->save()){
						//$row = array_combine($qualif_columns, []);
						//$row[0]=$model->enrolement_id;
						//fputcsv($fp,$row);
                        echo "Master Data imported successfully: ".$model->enrolement_id."\n";
						
					}else{
                        if($model->getErrors()){
                            $error_msg="Sl No. - ".$fileop[0].":\n";
                            foreach($model->getErrors() as $k=>$errors){
                                $error_msg.=$k." : ";
                                foreach($errors as $msg){
                                    $error_msg.=$msg."\n";
                                }
                            }
                        }
                        echo $error_msg;
						
					}
					
					 //print_r($model);
				}
                //exit;
				//fclose($fp);
				//fclose($fileop);
			}
		}
		
		exit;
	}
	
	public function actionQualification_import(){
		//echo "Success";
		$model=new Enrollment();
		$basepath=Yii::getAlias('@storage');
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			if ($model->load(Yii::$app->request->post())) {
				
				$model->import_file = UploadedFile::getInstance($model, 'import_file');
				$filename=UploadedFile::getInstance($model, 'import_file');
				//echo $filename;exit;
				if($filename) {
				  $model->import_file->saveAs($basepath . '/import/qualification/'.$filename);
				  $handle = fopen($basepath . '/import/qualification/'.$filename, "r");
				  $counter = 0;
				}
				$d='';
				while (($fileop = fgetcsv($handle, 10000, ",")) !== false) {
					 if($counter<=0){
					   $counter++;
					   continue;
					 }
					
                     $enrolmentid = $fileop[0];
                     
                     $qualification=Qualification::find()->where(['enrolement_id' =>$enrolmentid])->all();
                     if($qualification && $d!=$enrolmentid){
                        foreach($qualification as $qdelete)
                        {
                            $qdelete->delete();
                        }
                        $d=$enrolmentid;
                     }
					$new_qualification=new Qualification();
					$new_qualification->enrolement_id=$fileop[0];
					$new_qualification->university_name=$fileop[3];
					$new_qualification->college_name=$fileop[4];
					$new_qualification->year_of_passout=$fileop[5];
					$new_qualification->division_percent=$fileop[6];
					$new_qualification->highest_qualification=$fileop[7];
					
					if($new_qualification->save()){
						echo "Qualification Details Data imported successfully: ".$new_qualification->enrolement_id."\n";
					}else{
						var_dump($new_qualification->getErrors());exit;
					}
					
				}
				//fclose($fp);
				
			}
		}
		
		exit;
	}
	
	public function actionInternal_import(){
		//echo "Success";
		$model=new Enrollment();
		$basepath=Yii::getAlias('@storage');
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			if ($model->load(Yii::$app->request->post())) {
				
				$model->import_file = UploadedFile::getInstance($model, 'import_file');
				$filename=UploadedFile::getInstance($model, 'import_file');
				//echo $filename;exit;
				if($filename) {
				  $model->import_file->saveAs($basepath . '/import/internal/'.$filename);
				  $handle = fopen($basepath . '/import/internal/'.$filename, "r");
				  $counter = 0;
				}
				
				
				while (($fileop = fgetcsv($handle, 10000, ",")) !== false) {
					 if($counter<=0){
					   $counter++;
					   continue;
					 }
					
					$enrolmentid = $fileop[0];
					
					$internal = Enrollment::find()->where(['enrolement_id' => $enrolmentid])->one(); 
					if($internal->id){
						$internal->designation=$fileop[3];
						$internal->category=$fileop[4];
						$internal->PAPLdesignation=$fileop[5];
						$internal->experience=$fileop[6];
						$internal->status=0;
						if($internal->save()){
							
							echo "PAPL Internal Data imported successfully: ".$internal->enrolement_id."\n";
						}else{
							var_dump($internal->getErrors());exit;
						}
					}
				}
				
			}
		}
		
		exit;
	}
	
	public function actionBank_import(){
        $model=new Enrollment();
		$basepath=Yii::getAlias('@storage');
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			if ($model->load(Yii::$app->request->post())) {
				
				$model->import_file = UploadedFile::getInstance($model, 'import_file');
				$filename=UploadedFile::getInstance($model, 'import_file');
				//echo $filename;exit;
				if($filename) {
				  $model->import_file->saveAs($basepath . '/import/bank/'.$filename);
				  $handle = fopen($basepath . '/import/bank/'.$filename, "r");
				  $counter = 0;
				}
				
				
				while (($fileop = fgetcsv($handle, 10000, ",")) !== false) {
					 if($counter<=0){
					   $counter++;
					   continue;
					 }
					
					$enrolmentid = $fileop[0];

                    $bank_details=BankDetails::find()->where(['enrolement_id' =>$enrolmentid])->one();
                    $document_details=Document::find()->where(['enrolement_id' =>$enrolmentid])->one();
                    if(!$bank_details){
                        $bank_details=new BankDetails();
                        $bank_details->enrolement_id=$fileop[0];
                    }
                    if(!$document_details){
                        $document_details=new Document();
                        $document_details->enrolement_id=$fileop[0];
                    }
					$bank_details->name_bank=$fileop[3];
                    $bank_details->name_passbook=$fileop[4];
                    $bank_details->IFSC=$fileop[5];
                    $bank_details->bank_account_number=$fileop[6];
                    $bank_details->name_branch=$fileop[7];
                    
                    $bank_details->transaction_id=$fileop[8];
                    $document_details->voter_id_number=$fileop[9];
                    $document_details->dl_number=$fileop[10];
                    $document_details->dl_expiry_date=$fileop[11];
                    $document_details->pannumber=$fileop[12];
                    $document_details->passportnumber=$fileop[13];
                    $document_details->passport_expirydate=$fileop[14];
					
					if($bank_details->save() && $document_details->save()){
						echo "Bank Details Data imported successfully: ".$bank_details->enrolement_id."\n";
					}else{
						$error_msg="<br/>Sl No. - ".$fileop[0].":<br/>";
                        if($bank_details->getErrors()){
                            foreach($bank_details->getErrors() as $k=>$errors){
                                $error_msg.=$k." : ";
                                foreach($errors as $msg){
                                    $error_msg.=$msg."<br/>";
                                }
                            }
                        }
                        if($document_details->getErrors()){
                            foreach($document_details->getErrors() as $k=>$errors){
                                $error_msg.=$k." : ";
                                foreach($errors as $msg){
                                    $error_msg.=$msg."<br/>";
                                }
                            }
                        }
                        echo $error_msg;
					}
					
				}
				//fclose($fp);
				
			}
		}
		
		exit;
	}
    //EPF/ESIC Import
    public function actionEpf_import(){
		//echo "Success";
		$model=new Enrollment();
		$basepath=Yii::getAlias('@storage');
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			if ($model->load(Yii::$app->request->post())) {
				
				$model->import_file = UploadedFile::getInstance($model, 'import_file');
				$filename=UploadedFile::getInstance($model, 'import_file');
				//echo $filename;exit;
				if($filename) {
				  $model->import_file->saveAs($basepath . '/import/epf/'.$filename);
				  $handle = fopen($basepath . '/import/epf/'.$filename, "r");
				  $counter = 0;
				}
				
				
				while (($fileop = fgetcsv($handle, 10000, ",")) !== false) {
					 if($counter<=0){
					   $counter++;
					   continue;
					 }
					
					$enrolmentid = $fileop[0];
					
					$epf = Enrollment::find()->where(['enrolement_id' => $enrolmentid])->one(); 
					if($epf->id){
						$epf->esic_code=$fileop[3];
						$epf->esic_ip_number=$fileop[4];
						$epf->wca_gpa=$fileop[5];
						$epf->wca_gpa_expire_date=$fileop[6];
                        $epf->pf_code=$fileop[7];
                        $epf->uan=$fileop[8];
                        $epf->pf_account_number=$fileop[9];
						$epf->status=0;
						if($epf->save()){
							
							echo "PAPL EPF/ESIC Data imported successfully: ".$epf->enrolement_id."\n";
						}else{
							var_dump($epf->getErrors());exit;
						}
					}
				}
				
			}
		}
		
		exit;
	}
    //Nominee Data Import
    public function actionNominee_import(){
        $model=new Enrollment();
		$basepath=Yii::getAlias('@storage');
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			if ($model->load(Yii::$app->request->post())) {
				
				$model->import_file = UploadedFile::getInstance($model, 'import_file');
				$filename=UploadedFile::getInstance($model, 'import_file');
				//echo $filename;exit;
				if($filename) {
				  $model->import_file->saveAs($basepath . '/import/nominee/'.$filename);
				  $handle = fopen($basepath . '/import/nominee/'.$filename, "r");
				  $counter = 0;
				}
				
				
				while (($fileop = fgetcsv($handle, 10000, ",")) !== false) {
					 if($counter<=0){
					   $counter++;
					   continue;
					 }
					
					$enrolmentid = $fileop[0];

                    $bank_details=Nominee::find()->where(['enrolement_id' =>$enrolmentid])->one();
                    if(!$bank_details){
                        $bank_details=new Nominee();
                        $bank_details->enrolement_id=$fileop[0];
                    }
                    $bank_details->nominee_name=$fileop[3];
                    $bank_details->nominee_relation=$fileop[4];
                    $bank_details->nominee_dob=$fileop[5];
                    $bank_details->nominee_adhar_number=$fileop[6];
                    $bank_details->nominee_address=$fileop[7];
                    
					
					if($bank_details->save()){
						echo "Nominee Data imported successfully: ".$bank_details->enrolement_id."\n";
					}else{
						var_dump($bank_details->getErrors());
					}
					
				}
				//fclose($fp);
				
			}
		}
		
		exit;
	}

    //Family Data Import
    public function actionFamily_import(){
        $model=new Enrollment();
		$basepath=Yii::getAlias('@storage');
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			if ($model->load(Yii::$app->request->post())) {
				
				$model->import_file = UploadedFile::getInstance($model, 'import_file');
				$filename=UploadedFile::getInstance($model, 'import_file');
				//echo $filename;exit;
				if($filename) {
				  $model->import_file->saveAs($basepath . '/import/family/'.$filename);
				  $handle = fopen($basepath . '/import/family/'.$filename, "r");
				  $counter = 0;
				}
				
				
				while (($fileop = fgetcsv($handle, 10000, ",")) !== false) {
					 if($counter<=0){
					   $counter++;
					   continue;
					 }
					
					$enrolmentid = $fileop[0];

                    $bank_details=BankDetails::find()->where(['enrolement_id' =>$enrolmentid])->one();
                    if(!$bank_details){
                        $bank_details=new BankDetails();
                        $bank_details->enrolement_id=$fileop[0];
                    }
                    $bank_details->family_member=$fileop[3];
                    $bank_details->family_member_dob=$fileop[4];
                    $bank_details->family_member_relation=$fileop[5];
                    $bank_details->family_member_residence=$fileop[6];
					
					if($bank_details->save()){
						echo "Family Data imported successfully: ".$bank_details->enrolement_id."\n";
					}else{
						var_dump($bank_details->getErrors());
					}
					
				}
				//fclose($fp);
				
			}
		}
		
		exit;
	}

    //Qualification Sample Download
    public function actionQualification_sample($plant_id){
		
        $basepath=Yii::getAlias('@storage');
        $qualification_columns = ["Enrolement Id","Employee Name","Father/Husband Name","University/Board",
                            "College/Institute Name","Year of Passout","Division/Percent", "Qualification"];
				
        $filename="qualification_".$plant_id."_0.csv";
        $fp = fopen($basepath . '/sample/'.$filename, "wb");
		
		fputcsv($fp,$qualification_columns); 
        $model = Enrollment::find()->select('id, enrolement_id,adhar_name,father_husband_name')->where(['status' => [0,4],'plant_id'=>$plant_id])->all();
        foreach($model as $enrole){
            $qualification_details=Qualification::find()->where(['enrolement_id' =>$enrole->enrolement_id])->all();
            if($qualification_details){
                foreach($qualification_details as $qualification_detail){
                    $row[0]=$enrole->enrolement_id;
                    $row[1]=$enrole->adhar_name;
                    $row[2]=$enrole->father_husband_name;
                    $row[3]=$qualification_detail->university_name;
                    $row[4]=$qualification_detail->college_name;
                    $row[5]=$qualification_detail->year_of_passout;
                    $row[6]=$qualification_detail->division_percent;
                    $row[7]=$qualification_detail->highest_qualification;
                    fputcsv($fp,$row);
                }
            }
            else{
                $row[0]=$enrole->enrolement_id;
                $row[1]=$enrole->adhar_name;
                $row[2]=$enrole->father_husband_name;
                $row[3]=$row[4]=$row[5]=$row[6]=$row[7]='';
                
                fputcsv($fp,$row);
            }
            
        } 
        //exit;
        fclose($fp);
        
       
        echo Yii::getAlias('@storageUrl')."/sample/".$filename;exit;
	}

    //Internal Sample Download
    public function actionInternal_sample($plant_id){
		
        $basepath=Yii::getAlias('@storage');
        $internal_columns = ["Enrolement Id","Employee Name","Father/Husband Name","Designation",
                            "Category","PAPL designation","Experience in Year"];
				
        $filename="internal_".$plant_id."_0.csv";
        $fp = fopen($basepath . '/sample/'.$filename, "wb");
		
		fputcsv($fp,$internal_columns); 
        $model = Enrollment::find()->select('id, enrolement_id,adhar_name,father_husband_name')->where(['status' => [0,4],'plant_id'=>$plant_id])->all();
        foreach($model as $enrole){
            $row[0]=$enrole->enrolement_id;
            $row[1]=$enrole->adhar_name;
            $row[2]=$enrole->father_husband_name;
            $internal_details=Enrollment::find()->where(['enrolement_id' =>$enrole->enrolement_id])->one();
            if($internal_details){
                    $row[3]=$internal_details->designation;
                    $row[4]=$internal_details->category;
                    $row[5]=$internal_details->PAPLdesignation;
                    $row[6]=$internal_details->experience;
                    
            }else{
                $row[3]=$row[4]=$row[5]=$row[6]=$row[7]='';
            }
            fputcsv($fp,$row);
        } 
        //exit;
        fclose($fp);
        
       
        echo Yii::getAlias('@storageUrl')."/sample/".$filename;exit;
	}

    //Bank Sample Download
    public function actionBank_sample($plant_id){
		
        $basepath=Yii::getAlias('@storage');
        $bank_columns = ["Enrolement Id","Employee Name","Father/Husband Name","Name of Bank","Name as per Bank Pass Book","IFSC","Bank Account Number","Name of Branch with (Distrct and State)","Receipt by Employee/Bank Transaction ID",
                         "Voter Id Number","DL Number","DL Expire Date","Pan Number","Passport Number","Passport Expire Date"];
        $filename="bank_".$plant_id."_0.csv";
        $fp = fopen($basepath . '/sample/'.$filename, "wb");
		
		fputcsv($fp,$bank_columns); 
        $model = Enrollment::find()->select('id, enrolement_id,adhar_name,father_husband_name')->where(['status' => [0,4],'plant_id'=>$plant_id])->all();
        foreach($model as $enrole){
            $row[0]=$enrole->enrolement_id;
            $row[1]=$enrole->adhar_name;
            $row[2]=$enrole->father_husband_name;
            $bank_details=BankDetails::find()->where(['enrolement_id' =>$enrole->enrolement_id])->one();
            $document_details=Document::find()->where(['enrolement_id' =>$enrole->enrolement_id])->one();
            if($bank_details){
                $row[3]=$bank_details->name_bank;
                $row[4]=$bank_details->name_passbook;
                $row[5]=$bank_details->IFSC;
                $row[6]=$bank_details->bank_account_number;
                $row[7]=$bank_details->name_branch;
                
                $row[8]=$bank_details->transaction_id;
                if ($document_details) {
                   $row[9]=$document_details->voter_id_number;
                    $row[10]=$document_details->dl_number;
                    $row[11]=$document_details->dl_expiry_date;
                    $row[12]=$document_details->pannumber;
                    $row[13]=$document_details->passportnumber;
                    $row[14]=$document_details->passport_expirydate;
                }else{
                    $row[9]=$row[10]=$row[11]=$row[12]=$row[13]=$row[14]='';
                }
                
            }else{
                $row[3]=$row[4]=$row[5]=$row[6]=$row[7]=$row[8]='';
            }
            
            fputcsv($fp,$row);
        } 
        //exit;
        fclose($fp);
        
        echo Yii::getAlias('@storageUrl')."/sample/".$filename;exit;
	}
    //Nominee Sample Download
    public function actionNominee_sample($plant_id){
		
        $basepath=Yii::getAlias('@storage');
        $nominee_columns = ["Enrolement Id","Employee Name","Father/Husband Name","Nominee Name","Nominee Relationship","Nominee Dob","Nominee Adhar Number","Nominee Address"];
        $filename="nominee_".$plant_id."_0.csv";
        $fp = fopen($basepath . '/sample/'.$filename, "wb");
		
		fputcsv($fp,$nominee_columns); 
        $model = Enrollment::find()->select('id, enrolement_id,adhar_name,father_husband_name')->where(['status' => [0,4],'plant_id'=>$plant_id])->all();
        foreach($model as $enrole){
            $row[0]=$enrole->enrolement_id;
            $row[1]=$enrole->adhar_name;
            $row[2]=$enrole->father_husband_name;
            $nominee_details=Nominee::find()->where(['enrolement_id' =>$enrole->enrolement_id])->one();
            if($nominee_details){
                $row[3]=$nominee_details->nominee_name;
                $row[4]=$nominee_details->nominee_relation;
                $row[5]=$nominee_details->nominee_dob;
                $row[6]=$nominee_details->nominee_adhar_number;
                $row[7]=$nominee_details->nominee_address;
            }else{
                $row[3]=$row[4]=$row[5]=$row[6]=$row[7]='';
            }
            //echo $enrole->enrolement_id;
            

            fputcsv($fp,$row);
        } 
        //exit;
        fclose($fp);
       
        echo Yii::getAlias('@storageUrl')."/sample/".$filename;exit;
	}

    //Family Sample Download
    public function actionFamily_sample($plant_id){
		
        $basepath=Yii::getAlias('@storage');
        $family_columns = ["Enrolement Id","Employee Name","Father/Husband Name","Family Member Name","Family Member Dob","Relation","Residence with (Yes/No)"];
        $filename="family_".$plant_id."_0.csv";
        $fp = fopen($basepath . '/sample/'.$filename, "wb");
		
		fputcsv($fp,$family_columns); 
        $model = Enrollment::find()->select('id, enrolement_id,adhar_name,father_husband_name')->where(['status' => [0,4],'plant_id'=>$plant_id])->all();
        foreach($model as $enrole){
            $row[0]=$enrole->enrolement_id;
            $row[1]=$enrole->adhar_name;
            $row[2]=$enrole->father_husband_name;
            $bank_details=Family::find()->where(['enrolement_id' =>$enrole->enrolement_id])->one();
            if($bank_details){
                $row[3]=$bank_details->family_member;
                $row[4]=$bank_details->family_member_dob;
                $row[5]=$bank_details->family_member_relation;
                $row[6]=$bank_details->family_member_residence;
            }else{
                $row[3]=$row[4]=$row[5]=$row[6]='';
            }
            
            fputcsv($fp,$row);
        } 
        //exit;
        fclose($fp);
        
        echo Yii::getAlias('@storageUrl')."/sample/".$filename;exit;
	}

    //EPF/ESIC Sample Download
    public function actionEpf_sample($plant_id){
		
        $basepath=Yii::getAlias('@storage');
        $epf_columns = ["Enrolement Id","Employee Name","Father/Husband Name","ESIC Code","ESIC IP Number","WCA/GPA","WCA/GPA Expire Date","PF Code","UAN","PF Account Number"];
        $filename="epf_".$plant_id."_0.csv";
        $fp = fopen($basepath . '/sample/'.$filename, "wb");
		
		fputcsv($fp,$epf_columns); 
        $model = Enrollment::find()->select('id, enrolement_id,adhar_name,father_husband_name')->where(['status' => [0,4],'plant_id'=>$plant_id])->all();
        foreach($model as $enrole){
            $row[0]=$enrole->enrolement_id;
            $row[1]=$enrole->adhar_name;
            $row[2]=$enrole->father_husband_name;
            $epf_details=Enrollment::find()->where(['enrolement_id' =>$enrole->enrolement_id])->one();
            if($epf_details){
                $row[3]=$epf_details->esic_code;
                $row[4]=$epf_details->esic_ip_number;
                $row[5]=$epf_details->wca_gpa;
                $row[6]=$epf_details->wca_gpa_expire_date;
                $row[7]=$epf_details->pf_code;
                $row[8]=$epf_details->uan;
                $row[9]=$epf_details->pf_account_number;
            }else{
                $row[3]=$row[4]=$row[5]=$row[6]=$row[7]=$row[8]=$row[9]='';
            }
            
            fputcsv($fp,$row);
        } 
        //exit;
        fclose($fp);
        
        echo Yii::getAlias('@storageUrl')."/sample/".$filename;exit;
	}
    
    public function actionApprove(){
        $tempids=$this->request->post()['tempids'];
        $tempids=explode(',',$tempids);
        $status=$this->request->post()['status'];
        $reason='';
        if(isset($this->request->post()['reason'])){
            $reason=$this->request->post()['reason'];
        }
        foreach($tempids as $tempid){
            $enrole=Enrollment::find()->where(['enrolement_id' => $tempid])->one();
            // if ($enrole->designation == '' || empty($enrole->designation) && $enrole->PAPLdesignation == '' || empty($enrole->PAPLdesignation)) {
            //     $status = 'nodesignation';
            
            // }else{
                    $enrole->status=$status;
            // }

                
            
            
            $enrole->comment=$reason;
            if($enrole->save()){
             $res= 'success';
          }else{
            $res='error';

             var_dump($enrole->getErrors());
          }
        }
        $resp=['status'=>'success','type' => $status,'response'=>$res];
        echo json_encode($resp);
    }
    public function actionGetapprovebymgr(){
        $res=0;
        $result=[];
        if(isset($this->request->post()['tempid'])){    
          $tempid = Yii::$app->request->post('tempid');
          $status = Yii::$app->request->post('status');
          $enrole=Enrollment::find()->where(['enrolement_id' => $tempid])->one();
          // 
         // if ($enrole->status < 2){ //&& $enrole->pf_account_number == '' && $enrole->esic_code == '' && $enrole->esic_ip_number == '') {
         //    $status = 'error';
            
         // }else{
            $enrole->status =$status;
         // }
          if ($enrole->designation == '' || empty($enrole->designation) && $enrole->PAPLdesignation == '' || empty($enrole->PAPLdesignation)) {
            $status = 'nodesignation';
            
         }
          if($enrole->save()){
             $res=1;
          }else{
            $res=0;

            // var_dump($enrole->getErrors());die();
          }
        }
        $result=['status'=>$status,'type' => $status];
        echo json_encode($result);
              
    }
    public function actionEmployee_all_report(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Employee Master Data Report")){
            $model = new Enrollment();
 
            $plant= $purchase_order=$section=$location='';
            
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
            //print_r($model);
            
            $date = date('d-m-Y');
 
            return $this->render('export_all', [
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


    public function actionDownload_employee_data($plant_id,$po_id,$section_id,$date){
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
            $filename="employee_data_".$plant_id.date('dmYhs')."_all.xlsx";
            $spreadsheet = new Spreadsheet();
            $sheet_master = $spreadsheet->getActiveSheet();
            //Master
            $i=0;
            $sheet_master = $spreadsheet->createSheet($i);
            $master_columns = ["Enrolement Id","PAPL ID","Employee Name","Father/Husband Name","Relation",
                            "Gender","Marital Status","DOB","Adhar No","Mobile with Adhar",
                            "Designation","Category","PAPL Designation",
                            "Address","Village","PO","GP","Block","Tehsil","PS","District","State","PIN","Mobile no",
                            "Address","Village","PO","GP","Block","Tehsil","PS","District","State","PIN","Mobile no",
                            "Blood Group","ID Mark","Nationality","Religion","Caste"
                            ];
        
            $row=1;
            $column='A';
            $sheet_master->setCellValue($column.$row, 'Sl No.');
            $column++;
            foreach($master_columns as $mk=>$mv){
                $sheet_master->setCellValue($column.$row, $mv);
                $column++;
            }
    
            $row++;
            $sl_no=1;
            foreach($posting_history as $emp){
                $emp_master= Enrollment::find()->select('enrolement_id,papl_id,adhar_name,father_husband_name,relation_with_employee,
                                                        gender,marital_status,dob,adhar_number,mobile_with_adhar,
                                                        designation,category,PAPLdesignation,
                                                        permanent_addrs,permanent_village,permanent_po,permanent_GP,permanent_block,permanent_tehsil,permanent_ps,permanent_district,permanent_state,permanent_pincode,permanent_mobile_number,
                                                        present_address,present_village,present_po,present_GP,present_block,present_tehsil,present_ps,present_district,present_state,present_pincode,present_mobile_number,
                                                        blood_group,ID_mark_employee,nationality,religion,caste')
                                                        ->where(['papl_id'=>$emp->papl_id])
                                                        ->asArray()->one();
                $column='A';
                $sheet_master->setCellValue($column.$row, $sl_no);
                $column++;
                foreach($emp_master as $ek=>$ev){
                    $sheet_master->setCellValue($column.$row, $ev);
                    $column++;
                }
                
                $row++;
                $sl_no++;
            }
            $sheet_master->setTitle('Master');

            //Qualification
            $i=1;
            $sheet_qual = $spreadsheet->createSheet($i);
            $qual_columns = ["Enrolement Id","PAPL ID","Employee Name","Father/Husband Name","University/Board",
                                    "College/Institute Name","Year of Passout","Division/Percent", "Qualification"];
        
            $row=1;
            $column='A';
            $sheet_qual->setCellValue($column.$row, 'Sl No.');
            $column++;
            foreach($qual_columns as $qk=>$qv){
                $sheet_qual->setCellValue($column.$row, $qv);
                $column++;
            }
    
            $row++;
            $sl_no=1;
            foreach($posting_history as $emp){
                $qualification_details=Qualification::find()->where(['enrolement_id' =>$emp->enrolement->enrolement_id])->all();
                if($qualification_details){
                    foreach($qualification_details as $qualification_detail){
                        $column='A';
                        $sheet_qual->setCellValue($column.$row, $sl_no);
                        $column++;
                        $sheet_qual->setCellValue($column.$row,$qualification_detail->enrolement_id);
                        $column++;
                        $sheet_qual->setCellValue($column.$row,$emp->papl_id);
                        $column++;
                        $sheet_qual->setCellValue($column.$row,$emp->enrolement->adhar_name);
                        $column++;
                        $sheet_qual->setCellValue($column.$row,$emp->enrolement->father_husband_name);
                        $column++;
                        $sheet_qual->setCellValue($column.$row,$qualification_detail->university_name);
                        $column++;
                        $sheet_qual->setCellValue($column.$row,$qualification_detail->college_name);
                        $column++;
                        $sheet_qual->setCellValue($column.$row,$qualification_detail->year_of_passout);
                        $column++;
                        $sheet_qual->setCellValue($column.$row,$qualification_detail->division_percent);
                        $column++;
                        $sheet_qual->setCellValue($column.$row,$qualification_detail->highest_qualification);
                        $column++;
                        
                        $row++;
                        $sl_no++;
                    }
                }
            }
            $sheet_qual->setTitle('Qualification');
            //Internal
            $i++;
            $sheet_internal = $spreadsheet->createSheet($i);
            $internal_columns = ["Enrolement Id","PAPL_id","Employee Name","Father/Husband Name","Designation",
                            "Category","PAPL designation","Experience in Year"];
        
            $row=1;
            $column='A';
            $sheet_internal->setCellValue($column.$row, 'Sl No.');
            $column++;
            foreach($internal_columns as $ik=>$iv){
                $sheet_internal->setCellValue($column.$row, $iv);
                $column++;
            }
    
            $row++;
            $sl_no=1;
            foreach($posting_history as $emp){
                $emp_internal= Enrollment::find()->select('enrolement_id,papl_id,adhar_name,father_husband_name,designation,category,PAPLdesignation,experience')
                                                        ->where(['papl_id'=>$emp->papl_id])
                                                        ->asArray()->one();
                $column='A';
                $sheet_internal->setCellValue($column.$row, $sl_no);
                $column++;
                foreach($emp_internal as $ek=>$ev){
                    $sheet_internal->setCellValue($column.$row, $ev);
                    $column++;
                }
                
                $row++;
                $sl_no++;
            }
            $sheet_internal->setTitle('Internal');
            //EPF/ESI
            $i++;
            $sheet_epf = $spreadsheet->createSheet($i);
            $epf_columns = ["Enrolement Id","PAPL_id","Employee Name","Father/Husband Name","ESIC Code","ESIC IP Number","WCA/GPA","WCA/GPA Expire Date","PF Code","UAN","PF Account Number"];
        
            $row=1;
            $column='A';
            $sheet_epf->setCellValue($column.$row, 'Sl No.');
            $column++;
            foreach($epf_columns as $epfk=>$epfv){
                $sheet_epf->setCellValue($column.$row, $epfv);
                $column++;
            }
    
            $row++;
            $sl_no=1;
            foreach($posting_history as $emp){
                $emp_epf= Enrollment::find()->select('enrolement_id,papl_id,adhar_name,father_husband_name,esic_code,esic_ip_number,wca_gpa,wca_gpa_expire_date,pf_code,uan,pf_account_number')
                                                        ->where(['papl_id'=>$emp->papl_id])
                                                        ->asArray()->one();
                $column='A';
                $sheet_epf->setCellValue($column.$row, $sl_no);
                $column++;
                foreach($emp_epf as $ek=>$ev){
                    $sheet_epf->setCellValue($column.$row, $ev);
                    $column++;
                }
                
                $row++;
                $sl_no++;
            }
            $sheet_epf->setTitle('EPF-ESI');

            //Bank Details
            $i++;
            $sheet_bank = $spreadsheet->createSheet($i);
            $bank_columns = ["Enrolement Id","PAPL Id","Employee Name","Father/Husband Name","Name of Bank","Name as per Bank Pass Book","IFSC","Bank Account Number","Name of Branch with (Distrct and State)","Receipt by Employee/Bank Transaction ID",
                         "Voter Id Number","DL Number","DL Expire Date","Pan Number","Passport Number","Passport Expire Date"];
            $row=1;
            $column='A';
            $sheet_bank->setCellValue($column.$row, 'Sl No.');
            $column++;
            foreach($bank_columns as $bk=>$bv){
                $sheet_bank->setCellValue($column.$row, $bv);
                $column++;
            }
    
            $row++;
            $sl_no=1;
            foreach($posting_history as $emp){
                $column='A';
                $sheet_bank->setCellValue($column.$row, $sl_no);
                $column++;
                $sheet_bank->setCellValue($column.$row,$qualification_detail->enrolement_id);
                $column++;
                $sheet_bank->setCellValue($column.$row,$emp->papl_id);
                $column++;
                $sheet_bank->setCellValue($column.$row,$emp->enrolement->adhar_name);
                $column++;
                $sheet_bank->setCellValue($column.$row,$emp->enrolement->father_husband_name);
                $column++;
                
                $bank_details=BankDetails::find()->where(['enrolement_id' =>$emp->enrolement->enrolement_id])->one();
                $document_details=Document::find()->where(['enrolement_id' =>$emp->enrolement->enrolement_id])->one();
                if($bank_details){
                    $sheet_bank->setCellValue($column.$row,$bank_details->name_bank);
                    $column++;
                    $sheet_bank->setCellValue($column.$row,$bank_details->name_passbook);
                    $column++;
                    $sheet_bank->setCellValue($column.$row,$bank_details->IFSC);
                    $column++;
                    $sheet_bank->setCellValue($column.$row,$bank_details->bank_account_number);
                    $column++;
                    $sheet_bank->setCellValue($column.$row,$bank_details->name_branch);
                    $column++;
                    $sheet_bank->setCellValue($column.$row,$bank_details->transaction_id);
                    $column++;
                }
                if ($document_details) {
                    $column='L';
                    $sheet_bank->setCellValue($column.$row,$document_details->voter_id_number);
                    $column++;
                    $sheet_bank->setCellValue($column.$row,$document_details->dl_number);
                    $column++;
                    $sheet_bank->setCellValue($column.$row,$document_details->dl_expiry_date);
                    $column++;
                    $sheet_bank->setCellValue($column.$row,$document_details->pannumber);
                    $column++;
                    $sheet_bank->setCellValue($column.$row,$document_details->passportnumber);
                    $column++;
                    $sheet_bank->setCellValue($column.$row,$document_details->passport_expirydate);
                    $column++;
                }
                $row++;
                $sl_no++;
            }
            $sheet_bank->setTitle('Bank Details');

            //Nominee
            $i++;
            $sheet_nominee = $spreadsheet->createSheet($i);
            $nominee_columns = ["Enrolement Id","Employee Name","Father/Husband Name","Nominee Name","Nominee Relationship","Nominee Dob","Nominee Adhar Number","Nominee Address"];
            $row=1;
            $column='A';
            $sheet_nominee->setCellValue($column.$row, 'Sl No.');
            $column++;
            foreach($nominee_columns as $nk=>$nv){
                $sheet_nominee->setCellValue($column.$row, $nv);
                $column++;
            }
    
            $row++;
            $sl_no=1;
            foreach($posting_history as $emp){
                $emp_nominee= Nominee::find()->select('nominee_name,nominee_relation,nominee_dob,nominee_adhar_number,nominee_address')
                                        ->where(['enrolement_id' =>$emp->enrolement->enrolement_id])
                                        ->asArray()->one();
                 if($emp_nominee){
                    $column='A';
                    $sheet_nominee->setCellValue($column.$row, $sl_no);
                    $column++;
                    $sheet_nominee->setCellValue($column.$row,$qualification_detail->enrolement_id);
                    $column++;
                    $sheet_nominee->setCellValue($column.$row,$emp->papl_id);
                    $column++;
                    $sheet_nominee->setCellValue($column.$row,$emp->enrolement->adhar_name);
                    $column++;
                    $sheet_nominee->setCellValue($column.$row,$emp->enrolement->father_husband_name);
                    $column++;
                    foreach($emp_nominee as $nomineek=>$nomineev){
                        $sheet_nominee->setCellValue($column.$row, $nomineev);
                        $column++;
                    }
                    
                    $row++;
                    $sl_no++;
                 }                       
               
            }
            $sheet_nominee->setTitle('Nominee');
            
            $writer = new Xlsx($spreadsheet);
            $writer->save($basepath . '/export/'.$filename);
            echo Yii::getAlias('@storageUrl')."/export/".$filename;exit;
        }else{
            echo false;exit;
        } 
        
        
        
        // (B) CREATE A NEW SPREADSHEET
        
        
        // (C) GET WORKSHEET
        
         //Setting index when creating
        
        //$sheet_master->setCellValue('A1', 'Hello World !');
        //$sheet_master->setCellValue('A2', 'Goodbye World !');
        

        
        //print_r($master_data);exit;
        
        // $i=1;
        // $sheet_internal = $spreadsheet->createSheet($i);
        // $internal_model = Enrollment::find()->select('enrolement_id,designation, category,PAPLdesignation,experience')->where(['plant_id'=>$plant_id])->all();
        // $sheet_internal->setCellValue('A1', 'Enrolement Id');
        // $sheet_internal->setCellValue('B1', 'Designation');
        // $sheet_internal->setCellValue('C1', 'Category');
        // $sheet_internal->setCellValue('D1', 'PAPL designation');
        // $sheet_internal->setCellValue('E1', 'Experience in Year');

        // $rowCount = 2;
        // foreach ($internal_model as $element) {
        //     $sheet_internal->setCellValue('A' . $rowCount, $element['enrolement_id']);
        //     $sheet_internal->setCellValue('B' . $rowCount, $element['designation']);
        //     $sheet_internal->setCellValue('C' . $rowCount, $element['category']);
        //     $sheet_internal->setCellValue('D' . $rowCount, $element['PAPLdesignation']);
        //     $sheet_internal->setCellValue('E' . $rowCount, $element['experience']);
           
        //     $rowCount++;
        // }
        // $sheet_internal->setTitle('PAPL Internal');
        // $cRow = 0; $cCol = 0;
        // foreach ($model as $row) {
        // $cRow ++; // NEXT ROW
        // $cCol = 65; // RESET COLUMN "A"
        //     foreach ($row as $cell) {
        //         $sheet_master->setCellValue(chr($cCol) . $cRow, $cell);
        //         $cCol++;
        //     }
        // } 
        

        
    }

    public function actionRejected_reports(){
        if(acl::checkAcess(Yii::$app->user->identity->id,"Rejected Reports")){
            $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name');
            $model = new Enrollment();
            return $this->render('rejected_reports', [
                'model' => $model,
                'plants' => $plants,
            ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);

         } 
         
    }
    public function actionDownload_rejected_report($plant_id){
        $basepath=Yii::getAlias('@storage');
        $export_columns = ["Enrolement Id","Employee Name","Father/Husband Name","ESIC Code","ESIC IP Number","WCA/GPA","WCA/GPA Expire Date","PF Code","UAN","PF Account Number"];
        $filename="rejected_".$plant_id.date('dmYhs')."_all.xlsx";
        //$fp = fopen($basepath . '/export/'.$filename, "wb");
        
        //fputcsv($fp,$epf_columns); 
        // (B) CREATE A NEW SPREADSHEET
        $spreadsheet = new Spreadsheet();
        
        // (C) GET WORKSHEET
        $sheet_master = $spreadsheet->getActiveSheet();
        $i=0;
        $sheet_master = $spreadsheet->createSheet($i); //Setting index when creating
        
        //$sheet_master->setCellValue('A1', 'Hello World !');
        //$sheet_master->setCellValue('A2', 'Goodbye World !');
        if ($plant_id == 0) {
            $model = Enrollment::find()->joinWith(['plant'])->where(['enrollment.status'=>4])->all();
        }else{
           $model = Enrollment::find()->joinWith(['plant'])->where(['plant.plant_id'=>$plant_id,'enrollment.status'=>4])->all(); 
        }

        
        $sheet_master->setCellValue('A1', 'Sl No.');
        $sheet_master->setCellValue('B1', 'Enrolement Id');
        $sheet_master->setCellValue('C1', 'Employee Name');
        $sheet_master->setCellValue('D1', 'Reason for rejection');        
        $sheet_master->setCellValue('E1', 'State');
        $sheet_master->setCellValue('F1', 'Location');
        $sheet_master->setCellValue('G1', 'Plant');
        // $sheet_master->setCellValue('H1', 'Purchase Order');
        // $sheet_master->setCellValue('I1', 'Section');
        $sheet_master->getColumnDimension('B')->setWidth(16);
        $sheet_master->getColumnDimension('C')->setWidth(22);
        $sheet_master->getColumnDimension('D')->setWidth(22);
        $sheet_master->getColumnDimension('E')->setWidth(22);
        $sheet_master->getColumnDimension('F')->setWidth(22);
        $sheet_master->getColumnDimension('G')->setWidth(22);
        $rowCount = 2;
        foreach ($model as $element) {
           
            $plant_name = $element->plant->plant_name ?? '';
            $state_name = $element->plant->state->state_name ?? '';
            $location_name = $element->plant->location->location_name ?? '';
            // $purchase_order_name = $element->plant->purchaseorders->purchase_order_name;
            // $section_name = $element->plant->sections->section_name;
            $sheet_master->setCellValue('A' . $rowCount, $element['id']);
            $sheet_master->setCellValue('B' . $rowCount, $element['enrolement_id']);
            $sheet_master->setCellValue('C' . $rowCount, $element['adhar_name']);
            $sheet_master->setCellValue('D' . $rowCount, $element['comment']);            
            $sheet_master->setCellValue('E' . $rowCount, $state_name);
            $sheet_master->setCellValue('F' . $rowCount, $plant_name);
            $sheet_master->setCellValue('G' . $rowCount, $location_name);
            // $sheet_master->setCellValue('H' . $rowCount, $purchase_order_name);
            // $sheet_master->setCellValue('I' . $rowCount, $section_name);
           
            $rowCount++;
        }
        $sheet_master->setTitle('Master');
        // $i=1;
        // $sheet_internal = $spreadsheet->createSheet($i);
        

        // $internal_model = Enrollment::find()->select('enrolement_id,designation, category,PAPLdesignation,experience')->where(['plant_id'=>$plant_id])->all();
        // $sheet_internal->setCellValue('A1', 'Enrolement Id');
        // $sheet_internal->setCellValue('B1', 'Designation');
        // $sheet_internal->setCellValue('C1', 'Category');
        // $sheet_internal->setCellValue('D1', 'PAPL designation');
        // $sheet_internal->setCellValue('E1', 'Experience in Year');

        // $rowCount = 2;
        // foreach ($internal_model as $element) {
        //     $sheet_internal->setCellValue('A' . $rowCount, $element['enrolement_id']);
        //     $sheet_internal->setCellValue('B' . $rowCount, $element['designation']);
        //     $sheet_internal->setCellValue('C' . $rowCount, $element['category']);
        //     $sheet_internal->setCellValue('D' . $rowCount, $element['PAPLdesignation']);
        //     $sheet_internal->setCellValue('E' . $rowCount, $element['experience']);
           
        //     $rowCount++;
        // }
        // $sheet_internal->setTitle('PAPL Internal');
        // $cRow = 0; $cCol = 0;
        // foreach ($model as $row) {
        // $cRow ++; // NEXT ROW
        // $cCol = 65; // RESET COLUMN "A"
        //     foreach ($row as $cell) {
        //         $sheet_master->setCellValue(chr($cCol) . $cRow, $cell);
        //         $cCol++;
        //     }
        // } 
        $writer = new Xlsx($spreadsheet);
        $writer->save($basepath . '/export/'.$filename);
        echo Yii::getAlias('@storageUrl')."/export/".$filename;exit;
    }
    public function actionExport_exit_report()
    {
        if(acl::checkAcess(Yii::$app->user->identity->id,"Exit Reports")){
            $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0])->all(), 'plant_id', 'plant_name');
            $model = new Enrollment();
            return $this->render('exit_report', [
                'model' => $model,
                'plants' => $plants,
            ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);

         } 
        
    }
    public function actionDownload_exited_report($plant_id){
        $basepath=Yii::getAlias('@storage');
        $export_columns = ["Enrolement Id","Employee Name","Father/Husband Name","ESIC Code","ESIC IP Number","WCA/GPA","WCA/GPA Expire Date","PF Code","UAN","PF Account Number"];
        $filename="exited_employee".$plant_id.date('dmYhs')."_all.xlsx";
        //$fp = fopen($basepath . '/export/'.$filename, "wb");
        
        //fputcsv($fp,$epf_columns); 
        // (B) CREATE A NEW SPREADSHEET
        $spreadsheet = new Spreadsheet();
        
        // (C) GET WORKSHEET
        $sheet_master = $spreadsheet->getActiveSheet();
        $i=0;
        $sheet_master = $spreadsheet->createSheet($i); //Setting index when creating
        
        //$sheet_master->setCellValue('A1', 'Hello World !');
        //$sheet_master->setCellValue('A2', 'Goodbye World !');
        
        if ($plant_id == 0) {
            $model = PostingHistory::find()->joinWith(['employee'])->where(['employee.is_exit'=>1])->all();
             // echo '<pre>';var_dump($model);die();
        }else{
           $model = PostingHistory::find()->joinWith(['employee'])->where(['posting_history.plant_id'=>$plant_id,'employee.is_exit'=>1])->orderBy(['posting_history.end_date' => SORT_DESC])->all(); 
        }
        
        $sheet_master->setCellValue('A1', 'Sl No.');
        $sheet_master->setCellValue('B1', 'Employee ID');
        $sheet_master->setCellValue('C1', 'Employee Name');
        $sheet_master->setCellValue('D1', 'Exit Date');        
        $sheet_master->setCellValue('E1', 'State');
        $sheet_master->setCellValue('F1', 'Location');
        $sheet_master->setCellValue('G1', 'Plant');
        $sheet_master->setCellValue('H1', 'Purchase Order');
        $sheet_master->setCellValue('I1', 'Section');
        $sheet_master->getColumnDimension('B')->setWidth(16);
        $sheet_master->getColumnDimension('C')->setWidth(22);
        $sheet_master->getColumnDimension('D')->setWidth(22);
        $sheet_master->getColumnDimension('E')->setWidth(22);
        $sheet_master->getColumnDimension('F')->setWidth(22);
        $sheet_master->getColumnDimension('G')->setWidth(22);
        $sheet_master->getColumnDimension('H')->setWidth(22);
        $sheet_master->getColumnDimension('I')->setWidth(22);
        $rowCount = 2;
        foreach ($model as $element) {
           
            $employee_name = $element->enrolement->adhar_name ?? '';
            $plant_name = $element->plant->plant_name ?? '';
            $state_name = $element->plant->state->state_name ?? '';
            $location_name = $element->plant->location->location_name ?? '';
            $purchase_order_name = $element->purchaseorder->purchase_order_name ?? '';
            $section_name = $element->section->section_name ?? '';
            $exit_date = $element->employee->exit_date ?? '';
            $sheet_master->setCellValue('A' . $rowCount, $element['id']);
            $sheet_master->setCellValue('B' . $rowCount, $element['papl_id']);
            $sheet_master->setCellValue('C' . $rowCount, $employee_name);
            $sheet_master->setCellValue('D' . $rowCount, $exit_date);            
            $sheet_master->setCellValue('E' . $rowCount, $state_name);
            $sheet_master->setCellValue('F' . $rowCount, $plant_name);
            $sheet_master->setCellValue('G' . $rowCount, $location_name);
            $sheet_master->setCellValue('H' . $rowCount, $purchase_order_name);
            $sheet_master->setCellValue('I' . $rowCount, $section_name);
           
            $rowCount++;
        }
        $sheet_master->setTitle('Master');
        // $i=1;
        // $sheet_internal = $spreadsheet->createSheet($i);
        

        // $internal_model = Enrollment::find()->select('enrolement_id,designation, category,PAPLdesignation,experience')->where(['plant_id'=>$plant_id])->all();
        // $sheet_internal->setCellValue('A1', 'Enrolement Id');
        // $sheet_internal->setCellValue('B1', 'Designation');
        // $sheet_internal->setCellValue('C1', 'Category');
        // $sheet_internal->setCellValue('D1', 'PAPL designation');
        // $sheet_internal->setCellValue('E1', 'Experience in Year');

        // $rowCount = 2;
        // foreach ($internal_model as $element) {
        //     $sheet_internal->setCellValue('A' . $rowCount, $element['enrolement_id']);
        //     $sheet_internal->setCellValue('B' . $rowCount, $element['designation']);
        //     $sheet_internal->setCellValue('C' . $rowCount, $element['category']);
        //     $sheet_internal->setCellValue('D' . $rowCount, $element['PAPLdesignation']);
        //     $sheet_internal->setCellValue('E' . $rowCount, $element['experience']);
           
        //     $rowCount++;
        // }
        // $sheet_internal->setTitle('PAPL Internal');
        // $cRow = 0; $cCol = 0;
        // foreach ($model as $row) {
        // $cRow ++; // NEXT ROW
        // $cCol = 65; // RESET COLUMN "A"
        //     foreach ($row as $cell) {
        //         $sheet_master->setCellValue(chr($cCol) . $cRow, $cell);
        //         $cCol++;
        //     }
        // } 
        $writer = new Xlsx($spreadsheet);
        $writer->save($basepath . '/export/'.$filename);
        echo Yii::getAlias('@storageUrl')."/export/".$filename;exit;
    }
}
