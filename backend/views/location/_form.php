<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Location */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="location-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'state_id')->dropDownList(
        $stateList,
        ['prompt'=>'Select state'     
                
            ]
        ); ?></div>
        <div class="col-md-4"> <?= $form->field($model, 'location_name')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4"> <?= $form->field($model, 'short_code')->textInput(['maxlength' => true]) ?></div>
        
       
    </div>

   

    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
