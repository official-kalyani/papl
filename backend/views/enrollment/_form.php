<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Enrollment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="enrollment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'enrolement_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'adhar_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'father_husband_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'relation_with_employee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'adhar_number')->textInput() ?>

    <?= $form->field($model, 'browse_adhar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'browse_pp_photo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'marital_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile_with_adhar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dob')->textInput() ?>

    <?= $form->field($model, 'permanent_addrs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'permanent_state')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'permanent_district')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'permanent_ps')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'permanent_po')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'permanent_village')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'permanent_block')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'permanent_tehsil')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'permanent_GP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'permanent_pincode')->textInput() ?>

    <?= $form->field($model, 'permanent_mobile_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'present_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'present_state')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'present_district')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'present_ps')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'present_po')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'present_village')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'present_block')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'present_tehsil')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'present_gp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'present_pincode')->textInput() ?>

    <?= $form->field($model, 'present_mobile_number')->textInput() ?>

    <?= $form->field($model, 'blood_group')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ID_mark_employee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nationality')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'religion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'caste')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'referenced_remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_delete')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
