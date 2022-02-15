<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EnrollmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="enrollment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'enrolement_id') ?>

    <?= $form->field($model, 'adhar_name') ?>

    <?= $form->field($model, 'father_husband_name') ?>

    <?= $form->field($model, 'relation_with_employee') ?>

    <?php // echo $form->field($model, 'adhar_number') ?>

    <?php // echo $form->field($model, 'browse_adhar') ?>

    <?php // echo $form->field($model, 'browse_pp_photo') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'marital_status') ?>

    <?php // echo $form->field($model, 'mobile_with_adhar') ?>

    <?php // echo $form->field($model, 'dob') ?>

    <?php // echo $form->field($model, 'permanent_addrs') ?>

    <?php // echo $form->field($model, 'permanent_state') ?>

    <?php // echo $form->field($model, 'permanent_district') ?>

    <?php // echo $form->field($model, 'permanent_ps') ?>

    <?php // echo $form->field($model, 'permanent_po') ?>

    <?php // echo $form->field($model, 'permanent_village') ?>

    <?php // echo $form->field($model, 'permanent_block') ?>

    <?php // echo $form->field($model, 'permanent_tehsil') ?>

    <?php // echo $form->field($model, 'permanent_GP') ?>

    <?php // echo $form->field($model, 'permanent_pincode') ?>

    <?php // echo $form->field($model, 'permanent_mobile_number') ?>

    <?php // echo $form->field($model, 'present_address') ?>

    <?php // echo $form->field($model, 'present_state') ?>

    <?php // echo $form->field($model, 'present_district') ?>

    <?php // echo $form->field($model, 'present_ps') ?>

    <?php // echo $form->field($model, 'present_po') ?>

    <?php // echo $form->field($model, 'present_village') ?>

    <?php // echo $form->field($model, 'present_block') ?>

    <?php // echo $form->field($model, 'present_tehsil') ?>

    <?php // echo $form->field($model, 'present_gp') ?>

    <?php // echo $form->field($model, 'present_pincode') ?>

    <?php // echo $form->field($model, 'present_mobile_number') ?>

    <?php // echo $form->field($model, 'blood_group') ?>

    <?php // echo $form->field($model, 'ID_mark_employee') ?>

    <?php // echo $form->field($model, 'nationality') ?>

    <?php // echo $form->field($model, 'religion') ?>

    <?php // echo $form->field($model, 'caste') ?>

    <?php // echo $form->field($model, 'referenced_remark') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
