<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Qualification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="qualification-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'qualification_id')->textInput() ?>

    <?= $form->field($model, 'enrolement_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'highest_qualification')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'university_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'college_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year_of_passout')->textInput() ?>

    <?= $form->field($model, 'division_percent')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_delete')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
