<?php
namespace common\helpers;
use \common\models\AclMapping;
use Yii;

class acl 
{
    public static function dt(){
        return date('Y-m-d H:i:s');
    }
    public static function checkAcess($uid,$acl){

        if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 5){
            return true;
        }else{
            $acl_id=\common\models\Acl::find()->where(['name'=>$acl])->one();
            if($acl_id){
                $user_acl = AclMapping::find()->where(['user_id'=>$uid,'acl_id'=>$acl_id->id])->one();  
                if($user_acl)                                 
                    return true;
                else
                    return false;
                
                    
            }else{
                return false;
            }
        }
        
        
    }
}