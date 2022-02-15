<?php

namespace backend\controllers;

use Yii;
use common\models\Plant;
use common\models\PlantSearch;
use common\models\Location;
use common\models\User;
use common\models\State;
use common\models\PostingHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * PlantController implements the CRUD actions for Plant model.
 */
class PlantController extends Controller
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
     * Lists all Plant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $updated_details = Plant::find()->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
        $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();
      
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'updated_details_name' => $updated_details_name,
        ]);
    }

    /**
     * Displays a single Plant model.
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
     * Creates a new Plant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Plant();
        $state= State::find()->all();//new
        $stateList=ArrayHelper::map($state,'state_id','state_name');//new
        $model->updated_by = Yii::$app->user->identity->id;
        // echo "<pre>";print_r(Yii::$app->request->post());die();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'stateList' => $stateList,
        ]);
    }

    /**
     * Updates an existing Plant model.
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
        // echo "<pre>";print_r(Yii::$app->request->post());die();     
        if ($model->load(Yii::$app->request->post()) ) {
            if ($model->save()) {
                 $this->redirect(['index']);
            }else{
                var_dump($model->getErrors());
            }
            
        }
           return $this->render('update', [
            'model' => $model,
            'stateList' => $stateList,
        ]);
        

        
    }

    /**
     * Deletes an existing Plant model.
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
           // else{
           //  var_dump($model->getErrors());die();
           // }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Plant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plant::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionGetplant()
    {      
        if(Yii::$app->request->isAjax){    
              $location_id = Yii::$app->request->post('id');
             $plants=Plant::find()->where(['location_id'=>$location_id,'is_delete' => 0])->all();
             
             echo '<option value="0"> Select Plant</option>';
             foreach ($plants as $plant) {
                echo '<option value="'.$plant['plant_id'].'">'.$plant['plant_name'].'</option>';
             }
             }

        
   
    }
    
    public function actionGetplantdetails($id){
        $plants = ArrayHelper::map(Plant::find()->where(['location_id'=>$id,'is_delete' => 0])->all(), 'plant_id', 'plant_name');
        // if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
        //     $plants = ArrayHelper::map(Plant::find()->where(['location_id'=>$id,'is_delete' => 0])->all(), 'plant_id', 'plant_name');
        // }else{
        //     $user_posting = PostingHistory::find()->where(['papl_id'=>Yii::$app->user->identity->username])
        //                                         ->andWhere(['end_date' => null])->one();
        //     $plants = ArrayHelper::map(Plant::find()->where(['is_delete' => 0,'plant_id'=>$user_posting->plant_id])->all(), 'plant_id', 'plant_name');
        // }
        echo json_encode($plants);
    }
}
