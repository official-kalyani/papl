<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Papldesignation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="papldesignation-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'PAPLdesignation')->textInput(['maxlength' => true]) ?></div>
    </div>
    

    <!-- <?= $form->field($model, 'status')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
