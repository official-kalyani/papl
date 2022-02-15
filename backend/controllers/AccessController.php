<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use common\models\Plant;
use common\models\AclMapping;
use common\models\Acl;
use common\models\Purchaseorder;
use common\models\PostingHistory; 
use yii\helpers\ArrayHelper;
use common\helpers\acl as Useracl;
use common\models\Roles;


class AccessController extends Controller
{
    
    public function actionIndex($uid=null)
    {   
        if(Useracl::checkAcess(Yii::$app->user->identity->id,"User ACL") || Yii::$app->user->identity->role_id==1){
            $model = new AclMapping();

            $updated_details = AclMapping::find()->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
            $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();

            // $user = ArrayHelper::map(User::find()->with('enrollment')->all(), 'id', 'enrollment.adhar_name');
            $user = ArrayHelper::map(User::find()
                                    ->where(['NOT',['role_id'=> [1,5]]])
                                    ->orderBy([
                                          'username' => SORT_ASC,
                                        ])
                                    ->with('enrollment','role')
                                    ->all(), 'id', function ($model) {
                                                return $model['enrollment']['adhar_name'] . ' - ' . $model->username.' ('.$model['role']['name'].')';});
            $plants = ArrayHelper::map(Plant::find()->all(), 'plant_id', 'plant_name');
            $purchase_order = ArrayHelper::map(Purchaseorder::find()->all(), 'po_id', 'purchase_order_name');
           
            $acl_master=array();
            
            $lvl1 = ArrayHelper::map(Acl::find()->where(['lvl2'=>null,'lvl3'=>null,'lvl4'=>null])->orderBy([
                                                                                                            'lvl1' => SORT_ASC,
                                                                                                        ])->all(), 'id', 'lvl1');
            foreach($lvl1 as $id=>$lvl1){
                $lvl2 = ArrayHelper::map(Acl::find()->where(['lvl1'=>$lvl1])
                                                    ->andWhere(['not', ['lvl2' => null]])
                                                    ->groupBy(['lvl2'])->all(), 'id', 'lvl2');
                 //$acl_master[$lvl1]=array();
                // //print_r($lvl2);
                // array_push($acl_master[$lvl1],$lvl2);
                foreach($lvl2 as $id2=>$lvl2){
                    $lvl3 = ArrayHelper::map(Acl::find()->where(['lvl2'=>$lvl2])
                                                        ->andWhere(['not', ['lvl3' => null]])
                                                        ->groupBy(['lvl3'])->all(), 'id', 'lvl3');
                    
                    if($lvl3){
                        $acl_master[$lvl1][$lvl2]=array();
                        array_push($acl_master[$lvl1][$lvl2],$lvl3);
                        //print_r($lvl3);
                    }else{
                        $acl_master[$lvl1][$lvl2]=$id2;
                    }
                    
                }
                
            }//print_r($acl_master);exit;
            
            
            return $this->render('index', [
                'user'=>$user,
                'model'=>$model,
                'plants'=>$plants,
                'purchase_order'=>$purchase_order,
                'uid'=>$uid,
                'acl_master'=>$acl_master,
                'updated_details_name'=>$updated_details_name,
            ]);
        }else{
            Yii::$app->session->setFlash('error', 'You are not authorized to access this !!');
            return $this->redirect(['/']);
        }
        
    }
    public function actionGetuserdetails($uid){
        $user_detail = User::findOne($uid);
        $posting_history=PostingHistory::find()->where(['papl_id'=>$user_detail->username])
                                            ->andWhere(['end_date' => null])->one();
        
        $user_access_detail=ArrayHelper::map(AclMapping::find()->where(['user_id'=>$uid])->all(), 'id', 'acl_id');
        $userdata['plant']=$posting_history->plant->plant_name;
        $userdata['po']=$posting_history->purchaseorder->purchase_order_name;
        $userdata['user_access_detail']=$user_access_detail;
        echo json_encode($userdata);
    }
    public function actionUpdate(){
        if(!empty(Yii::$app->request->post())) {
            //print_r(Yii::$app->request->post()['acl']);exit;
            $uid=Yii::$app->request->post()['AclMapping']['user_id'];
            $user_acls= ArrayHelper::map(AclMapping::find()->where(['user_id'=>$uid])->all(), 'id', 'acl_id');
            if(isset(Yii::$app->request->post()['acl'])){
              $deleted_acls=array_diff($user_acls,Yii::$app->request->post()['acl']);
              $new_acls=array_diff(Yii::$app->request->post()['acl'],$user_acls);
            }else{
              $deleted_acls=$user_acls;
              $new_acls=[];
            }
            foreach($deleted_acls as $acl_id){
                $model = AclMapping::find()->where(['user_id'=>$uid,'acl_id'=>$acl_id])->one();
                $model->delete();
                
            }
            if($new_acls){
                foreach($new_acls as $acl_id){
                    $model = new AclMapping();
                    $model->user_id=$uid;
                    $model->acl_id=$acl_id;
                    $model->created_by = Yii::$app->user->identity->id;
                    $model->updated_by = Yii::$app->user->identity->id;
                    $model->save();
                }
            }
            
            Yii::$app->session->setFlash('success', 'User Access Updated Successfully');
            return $this->redirect(['index','uid'=>$uid]);
            
        }
        
    }

    public function actionRolebasedaccess(){
        // $model = new User();
        // $user = ArrayHelper::map(User::find()->all(), 'id', 'username');
        $model = new AclMapping();
        $updated_details = AclMapping::find()->orderBy(['updated_by' => SORT_DESC])->limit(1)->one();
        $updated_details_name = User::find()->where(['id' => $updated_details->updated_by])->one();
            // $user = ArrayHelper::map(User::find()->with('enrollment')->all(), 'id', 'enrollment.adhar_name');
        $user = ArrayHelper::map(User::find()
                                    ->where(['NOT',['role_id'=> [1,5]]])
                                    ->orderBy([
                                          'username' => SORT_ASC,
                                        ])
                                    ->with('enrollment','role')
                                    ->all(), 'id', function ($model) {
                                                return $model['enrollment']['adhar_name'] . ' - ' . $model->username.' ('.$model['role']['name'].')';});

        $roles = ArrayHelper::map(Roles::find()->where(['NOT',['id'=> [1,5]]])->all(), 'id', 'name');
        $uid = '';
        $user_model = new User();
        $user_model->scenario = 'role';
       if(!empty(Yii::$app->request->post())) {
        
        $uid=Yii::$app->request->post()['User']['id'];
        $role_id=Yii::$app->request->post()['User']['role_id'];
        $user_model = User::find()->where(['id'=>$uid])->one();
        
        $user_model->role_id = $role_id;
        $model->created_by = Yii::$app->user->identity->id;
        $model->updated_by = Yii::$app->user->identity->id;
        $model->save();
        if(!$user_model->save()){
            var_dump($user_model->getErrors());
        }else{
            $this->refresh();
        }

       }
        return $this->render('role_based_access', [
            'user'=>$user,
            'model'=>$model,
            'roles'=>$roles,
            'uid'=>$uid,
            'updated_details_name'=>$updated_details_name,
        ]);

    }
    public function actionGetroles()
    {
       if(Yii::$app->request->isAjax){    
          $user_id = Yii::$app->request->post('user_id');
          $roledetails=array();
          $user= User :: find() -> where(['id'=>$user_id])->one();
          $model= Roles :: find() -> where(['id'=>$user->role_id])->one();
          // foreach($model as $key=> $value){
             $roledetails['id']= $model->id;
             $roledetails['name']= $model->name;

         // }
           //echo "<pre>";var_dump($roledetails);
         echo json_encode($roledetails); 
     }
     
 }
}