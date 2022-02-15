<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Purchaseorder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchaseorder-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'state_id')->dropDownList(
        $stateList,
        ['prompt'=>'Select state',
        'onchange' => 'return checkStatus(this.value)'       
                
            ]
        ); ?>
            
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'location_id')->dropDownList($model->isNewRecord ? []:[$model->location_id=>$model->location->location_name],['class' => 'form-control location', 
        'onchange' => 'return checkPlant(this.value)'       
                ] ) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'plant_id')->dropDownList($model->isNewRecord ? []:[$model->plant_id=>$model->plant->plant_name],['class' => 'form-control plant',       
                ] ) ?>
        </div>
        <div class="col-md-3"><?= $form->field($model, 'purchase_order_name')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'po_number')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'short_code')->textInput(['maxlength' => true]) ?></div>
        
    </div>
    
    
    

     

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    function checkStatus(id){
        var id = id;       
        $.ajax({
            type:'post',
            url: "<?= Yii::$app->urlManager->createUrl('location/getlocation'); ?>",
            data:{id:id},
            success:function(data){                
                $('.location').html(data);
                
            }
        });

    }
    function checkPlant(id){
         var id = id;
          $.ajax({
            type:'post',
            url: "<?= Yii::$app->urlManager->createUrl('plant/getplant'); ?>",
            data:{id:id},
            success:function(data){                
                $('.plant').html(data);
                
            }
        });
    }
   
   
</script>