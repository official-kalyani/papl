<?php

namespace backend\controllers;

use Yii;
use common\models\Section;
use common\models\SectionSearch;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use common\models\State;
use common\models\Location;

/**
 * SectionController implements the CRUD actions for Section model.
 */
class SectionController extends Controller
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
     * Lists all Section models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $updated_details = Section::find()->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
        $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'updated_details_name' => $updated_details_name,
        ]);
    }

    /**
     * Displays a single Section model.
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
     * Creates a new Section model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Section();
        $state= State::find()->all();//new
        $stateList=ArrayHelper::map($state,'state_id','state_name');//new
        $model->updated_by = Yii::$app->user->identity->id;
       
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->section_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'stateList'=>$stateList
        ]);
    }

    /**
     * Updates an existing Section model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $state= State::find()->all();//new
         $model->updated_by = Yii::$app->user->identity->id;
        $stateList=ArrayHelper::map($state,'state_id','state_name');//new
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->section_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'stateList' => $stateList,
        ]);
    }

    /**
     * Deletes an existing Section model.
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
     * Finds the Section model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Section the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Section::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionGetsection()
    {      
        if(Yii::$app->request->isAjax){    
              $po_id = Yii::$app->request->post('id');
             $sections=Section::find()->where(['po_id'=>$po_id,'is_delete' => 0])->all();
             
             echo '<option value="0"> Select Section</option>';
             foreach ($sections as $section) {
                echo '<option value="'.$section['section_id'].'">'.$section['section_name'].'</option>';
             }
             }

        
   
    }
    public function actionGetsectiondetails($id)
    {
      $sectiondetails=array();
      $model= Section :: find() -> where(['po_id'=>$id,'is_delete' => 0])->all();
       foreach($model as $key=> $value){
           $sectiondetails[$key]['section_id']= $value->section_id;
           $sectiondetails[$key]['section_name']= $value->section_name;
        
       }
           //echo "<pre>";var_dump($sectiondetails);
             echo json_encode($sectiondetails); 
    }
   
}
