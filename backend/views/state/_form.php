<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\State */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="state-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'state_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'short_code')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
