<?php

namespace backend\controllers;

use Yii;
use common\models\Papldesignation;
use common\models\User;
use common\models\PapldesignationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PapldesignationController implements the CRUD actions for Papldesignation model.
 */
class PapldesignationController extends Controller
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
     * Lists all Papldesignation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PapldesignationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $updated_details = Papldesignation::find()->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
        $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'updated_details_name' => $updated_details_name,
        ]);
    }

    /**
     * Displays a single Papldesignation model.
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
     * Creates a new Papldesignation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Papldesignation();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Papldesignation model.
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
     * Deletes an existing Papldesignation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $this->findModel($id)->delete();
        $model = $this->findModel($id);
           $model->status = 1;
           $model->save();
        return $this->redirect(['index']);

    }

    /**
     * Finds the Papldesignation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Papldesignation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Papldesignation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
