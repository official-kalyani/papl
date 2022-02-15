<?php

namespace backend\controllers;

use Yii;
use common\models\Purchaseorder;
use common\models\PurchaseorderSearch;
use common\models\State;
use common\models\Plant;
use common\models\User;
use common\models\Location;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
/**
 * PurchaseorderController implements the CRUD actions for Purchaseorder model.
 */
class PurchaseorderController extends Controller
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
     * Lists all Purchaseorder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseorderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       
        $updated_details = Purchaseorder::find()->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
        $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'updated_details_name' => $updated_details_name,
        ]);
    }

    /**
     * Displays a single Purchaseorder model.
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
     * Creates a new Purchaseorder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Purchaseorder();
        $state= State::find()->all();//new

        $model->created_by = Yii::$app->user->identity->id;
        $stateList=ArrayHelper::map($state,'state_id','state_name');//new
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->po_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'stateList' => $stateList,
            
        ]);
    }

    /**
     * Updates an existing Purchaseorder model.
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
            return $this->redirect(['view', 'id' => $model->po_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'stateList' => $stateList,
        ]);
    }

    /**
     * Deletes an existing Purchaseorder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
           $model->is_delete = 1;
           if(!$model->save()){
             var_dump($model->getErrors());die();
           }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Purchaseorder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Purchaseorder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchaseorder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
      public function actionGetpurchaseorder()
    {      
        if(Yii::$app->request->isAjax){    
              $plant_id = Yii::$app->request->post('id');
             $purchaseorders=Purchaseorder::find()->where(['plant_id'=>$plant_id,'is_delete' => 0])->all();
             
             echo '<option value="0"> Select Purchaseorder</option>';
             foreach ($purchaseorders as $purchaseorder) {
                echo '<option value="'.$purchaseorder['po_id'].'">'.$purchaseorder['purchase_order_name'].'</option>';
             }
             }

        
   
    }
    public function actionGetpurchaseorderdetails($id)
    {
        $podetails=array();
        $model= Purchaseorder :: find() -> where(['plant_id'=>$id,'is_delete' => 0])->all();
           foreach($model as $key=> $value){
               $podetails[$key]['po_id']= $value->po_id;
               $podetails[$key]['purchase_order_name']= $value->purchase_order_name;
            
           }
           // echo "<pre>";print_r($model);
             echo json_encode($podetails);

    }
}
