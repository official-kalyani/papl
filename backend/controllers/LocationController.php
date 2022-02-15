<?php

namespace backend\controllers;

use Yii;
use common\models\Location;
use common\models\LocationSearch;
use common\models\State;
use common\models\User;
use common\models\PostingHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
/**
 * LocationController implements the CRUD actions for Location model.
 */
class LocationController extends Controller
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
     * Lists all Location models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $updated_details = Location::find()->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
        $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'updated_details_name' => $updated_details_name,
        ]);
    }

    /**
     * Displays a single Location model.
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
     * Creates a new Location model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Location();
        $state= State::find()->all();//new
         $model->updated_by = Yii::$app->user->identity->id;
         
        $stateList=ArrayHelper::map($state,'state_id','state_name');//new
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->location_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'stateList' => $stateList,
        ]);
    }

    /**
     * Updates an existing Location model.
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
            return $this->redirect(['view', 'id' => $model->location_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'stateList' => $stateList,
        ]);
    }

    /**
     * Deletes an existing Location model.
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
     * Finds the Location model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Location the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Location::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
     public function actionGetlocation()
    {      
        if(Yii::$app->request->isAjax){    
              $state_id = Yii::$app->request->post('id');
             $locations=Location::find()->where(['state_id'=>$state_id,'is_delete' => 0])->all();
             
             echo '<option value="0"> Select Location</option>';
             foreach ($locations as $location) {
                echo '<option value="'.$location['location_id'].'">'.$location['location_name'].'</option>';
             }
             }

        
   
    }
    public function actionGetlocationdetails($id){
        $user_posting = PostingHistory::find()->where(['papl_id'=>Yii::$app->user->identity->username])
                                                ->andWhere(['end_date' => null])->one();
        if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
            $locations = ArrayHelper::map(Location::find()->where(['state_id'=>$id,'is_delete' => 0])->all(), 'location_id', 'location_name');
        }else{
            $locations = ArrayHelper::map(Location::find()->where(['is_delete' => 0,'location_id'=>$user_posting->location_id])->all(), 'location_id', 'location_name');
        }
        echo json_encode($locations);
    }
}
