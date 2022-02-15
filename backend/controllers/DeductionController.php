<?php

namespace backend\controllers;

use Yii;
use common\models\Deduction;
use common\models\User;
use common\models\DeductionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helpers\acl;

/**
 * DeductionController implements the CRUD actions for Deduction model.
 */
class DeductionController extends Controller
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
     * Lists all Deduction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeductionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $updated_details = Deduction::find()->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
        $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'updated_details_name' => $updated_details_name,
        ]);
    }

    /**
     * Displays a single Deduction model.
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
     * Creates a new Deduction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(acl::checkAcess(Yii::$app->user->identity->id,"Create Deduction")){
            $model = new Deduction();
            $searchModel = new DeductionSearch();
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
     * Updates an existing Deduction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->created_by = Yii::$app->user->identity->id;
        $model->updated_by = Yii::$app->user->identity->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Deduction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->created_by = Yii::$app->user->identity->id;
        $model->updated_by = Yii::$app->user->identity->id;
       
           $model->is_delete = 1;
           $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Deduction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Deduction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Deduction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
