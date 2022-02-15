<?php

namespace backend\controllers;

use Yii;
use common\models\State;
use common\models\StateSearch;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helpers\acl;

/**
 * StateController implements the CRUD actions for State model.
 */
class StateController extends Controller
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
     * Lists all State models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(acl::checkAcess(Yii::$app->user->identity->id,"View State")){
            $searchModel = new StateSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $updated_details = State::find()->orderBy(['state.updated_by' => SORT_DESC])->limit(1)->one();
            $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'updated_details_name' => $updated_details_name,
            ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
         }
    }

    /**
     * Displays a single State model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(acl::checkAcess(Yii::$app->user->identity->id,"View State")){
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
         }
    }

    /**
     * Creates a new State model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(acl::checkAcess(Yii::$app->user->identity->id,"Create State")){
            $model = new State();
            
            $model->updated_by = Yii::$app->user->identity->id;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->state_id]);
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }
    }

    /**
     * Updates an existing State model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(acl::checkAcess(Yii::$app->user->identity->id,"Edit State")){
            $model = $this->findModel($id);
            $model->updated_by = Yii::$app->user->identity->id;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->state_id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }
    }

    /**
     * Deletes an existing State model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $this->findModel($id)->delete();
        $model = $this->findModel($id);
           $model->is_delete = 1;
           $model->save();
  
        return $this->redirect(['index']);
    }

    /**
     * Finds the State model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return State the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = State::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
