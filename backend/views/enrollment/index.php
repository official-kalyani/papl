<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\EnrollmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Enrollments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="enrollment-index">

    <h1><?= Html::encode($this->title) ?></h1>


<div class="row">

<div class="col-2"> 
  <p><?= Html::a(Yii::t('app', 'Create Enrollment'), ['create'], ['class' => 'btn btn-success']) ?></p>
</div>
<div class="col-2">
     <p>
        <?php if (Yii::$app->getRequest()->getQueryParam('type') != 3) {?>
        <?= Html::button('Transfer to Plant Head', ['class' => 'btn btn-success', 'onclick' => 'getRows(1)']) ?>
    <?php }?>
    </p>
</div>
<div class="col-2">
        <p>
        <?php 
            if (Yii::$app->getRequest()->getQueryParam('type') != 4 && Yii::$app->getRequest()->getQueryParam('type') != 3) {
                
        ?>
        <?= Html::button('Transfer to Epf/Esi Head', ['class' => 'btn btn-success', 'onclick' => 'getRows(2)']) ?>
    <?php }?>
    </p>
</div>

</div>

  
   


    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <?php 
    // echo $role;die();
    if (Yii::$app->getRequest()->getQueryParam('type') == 5 ) {
        if ($role == 3) {
            $template = '{update}   {appoint}';
        }else{
          $template = '{update} {reject}  {appoint}';  
        }
        
    }
    if (Yii::$app->getRequest()->getQueryParam('type') == 4) {
        $template = '{update} ';
    }
    if (Yii::$app->getRequest()->getQueryParam('type') == 0) {
        $template = ' {reject} {update} ';
        // $template = '{update} {reject} {approve} {appoint}';
    }
    if (Yii::$app->getRequest()->getQueryParam('type') == 1 ) {
        $template = ' {reject} {approve}{update}';
    }
    if (Yii::$app->getRequest()->getQueryParam('type') == 2) {
        $template = '{update} {reject} {approve}';
    }
    if (Yii::$app->getRequest()->getQueryParam('type') == 3) {
        $template = '{update} {reject} {appoint}';
    }
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'class' => 'yii\grid\CheckboxColumn', 'checkboxOptions' => function ($model) {
                    return ['value' => $model->enrolement_id];
                },
            ],
            'enrolement_id',
            'adhar_name',
            'father_husband_name',
            'relation_with_employee',
            'comment',
            //'adhar_number',
            //'browse_adhar',
            //'browse_pp_photo',
            //'gender',
            //'marital_status',
            //'mobile_with_adhar',
            //'dob',
            //'permanent_addrs',
            //'permanent_state',
            //'permanent_district',
            //'permanent_ps',
            //'permanent_po',
            //'permanent_village',
            //'permanent_block',
            //'permanent_tehsil',
            //'permanent_GP',
            //'permanent_pincode',
            //'permanent_mobile_number',
            //'present_address',
            //'present_state',
            //'present_district',
            //'present_ps',
            //'present_po',
            //'present_village',
            //'present_block',
            //'present_tehsil',
            //'present_gp',
            //'present_pincode',
            //'present_mobile_number',
            //'blood_group',
            //'ID_mark_employee',
            //'nationality',
            //'religion',
            //'caste',
            //'referenced_remark',
            //'status',
            //'comment',
            //'is_delete',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => $template,               
                'buttons' => [

                    'approve' => function ($url, $model) {

                        return Html::button('approve', [

                            'title' => Yii::t('yii', 'approve'),
                            'class' => 'btn btn-success approve',
                            'onclick' => "approvebymgr('".$model->enrolement_id."')",

                        ]);
                    },
                    'reject' => function ($url, $model) {

                        return Html::button('reject', [

                            'title' => Yii::t('yii', 'reject'),
                            'class' => 'btn btn-danger reject',
                            'onclick' => "reject('".$model->enrolement_id."')"

                        ]);
                    },
                    'appoint' => function ($url, $model) {

                        return Html::a('appoint',$url, [

                            'title' => Yii::t('yii', 'appoint'),
                            'class' => 'btn btn-success appoint',
                           

                        ]);
                    }

                ],

                'urlCreator' => function ($action, $model, $key, $index) {

                    if ($action == "update") {
                        if (Yii::$app->getRequest()->getQueryParam('type') == 2) {
                            return Url::to(['enrole', 'enrolementid' => $model->enrolement_id,'tag' => 'epf']);
                        }else{
                            return Url::to(['enrole', 'enrolementid' => $model->enrolement_id]);
                        }

                        
                    }
                   
                    if ($action == "approve") {

                        return Url::to(['enrole', 'enrolementid' => $model->enrolement_id]);
                    }
                    if ($action == 'appoint') {
                        return Url::to(['posting-history/create', 'enrolementid' => $model->enrolement_id]);
                    }
                }
            ],
        ],
    ]); ?>


</div>
<script>
    function getRows(status) {
        // status should follow 0=>no action, 1=> transfer_to plant,2=>approved_by_plant_mgr,3=>epf_esi created,4=>rejected,5=>parmanent
        var strvalue = "";
        $('input[name="selection[]"]:checked').each(function() {
            if (strvalue != "")
                strvalue = strvalue + "," + this.value;
            else
                strvalue = this.value;

        });


        $.post({
            url: '<?= Yii::$app->urlManager->createUrl('enrollment/approve'); ?>',
            dataType: 'json',
            data: {
                "tempids": strvalue,
                'status': status
            },
            success: function(data) {
                console.log(data);
                if (data.type == 1) {
                    alert('Transfer to plant head successful');
                    window.location.reload();
                }
                if (data.type == 2) {
                    alert('Transfer to Epf/esi head successful');
                    window.location.reload();
                }
                if (data.type == 3) {
                    alert('It is ready to be appointed');
                    window.location.reload();
                }
                if (data.type == 'nodesignation') {
                    alert('Designation has not been  updated');
                   
                }

            },


        });
    }
    function reject(tempid){
        var reason=prompt("Reason for rejection");
        alert(reason);
        
        if(reason=='' )
        {
            alert ('you cannot reject without reason');
            return false;
        }
        else{
            $.post({
            url: '<?= Yii::$app->urlManager->createUrl('enrollment/approve'); ?>',
            dataType: 'json',
            data: {
                "tempids": tempid,
                'status': 4,
                'reason':reason
            },
            success: function(data) {
                if (data.type == 4) {
                    alert('Rejected successful');
                    window.location.reload();
                } else {
                    alert('Something went wrong');
                }

            },


        });

        }
    }
    function approvebymgr(tempid){
        // status should follow 0=>no action, 1=> transfer_to plant,2=>approved_by_plant_mgr,3=>epf_esi created,4=>rejected,5=>parmanent
        // alert(tempid);
        const urlParams = new URLSearchParams(window.location.search);
        const myParam = urlParams.get('type');
        var status = '';
        if (myParam == 1) {
            status = 2;
        }
        if(myParam == 2){
            status = 3;
        }
       
        
         $.ajax({
            type:'post',
            dataType:'json',
            url: "<?= Yii::$app->urlManager->createUrl('enrollment/getapprovebymgr'); ?>",
            data:{
                "tempid": tempid,
                'status': status
            },
            success:function(data){                
                    if (data.type == 1) {
                    alert('Transfer to plant head successfully');
                    window.location.reload();
                } 
                if (data.type == 2) {
                    alert('Transfer to Epf/esi head successfully');
                    window.location.reload();
                }
                if (data.type == 3) {
                    alert('It is ready to be appointed');
                    window.location.reload();
                }
                
                if (data.status == 'error') {
                    alert('Epf/Esic has not been  updated');
                    window.location.reload();
                }
                if (data.status == 'nodesignation') {
                    alert('Designation has not been  updated');
                   
                }
                
                
            }
        });

       
    }

</script>