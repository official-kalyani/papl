<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Plant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plant-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-1">
          
           <?php
              $model->is_esi = $model->is_esi ?? 1;
              ?>
            <?=  $form->field($model, 'is_esi')->checkBox([ 'selected' => true]); ?>
        </div> 
         <div class="col-md-3"><?= $form->field($model, 'state_id')->dropDownList(
        $stateList,
        ['prompt'=>'Select state',
        'onchange' => 'return checkStatus(this.value)'       
                
            ]
        ); ?></div>
        <div class="col-md-3">
             <?= $form->field($model, 'location_id')->dropDownList($model->isNewRecord ? []:[$model->location_id=>$model->location->location_name],['class' => 'form-control location',       
                ] ) ?>
        </div> 
        <div class="col-md-3">
            <?= $form->field($model, 'plant_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'short_code')->textInput(['maxlength' => true]) ?>
        </div>
       
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
</script>
