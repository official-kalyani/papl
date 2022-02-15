<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AttendanceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="attendance-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'papl_id') ?>

    <?= $form->field($model, 'employee_name') ?>

    <?= $form->field($model, 'att') ?>

    <?= $form->field($model, 'att_type') ?>

    <?php // echo $form->field($model, 'nh') ?>

    <?php // echo $form->field($model, 'fh') ?>

    <?php // echo $form->field($model, 'nhfh_type') ?>

    <?php // echo $form->field($model, 'ot') ?>

    <?php // echo $form->field($model, 'ot_type') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
