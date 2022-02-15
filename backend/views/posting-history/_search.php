<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PostingHistorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="posting-history-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'state_id') ?>

    <?= $form->field($model, 'location_id') ?>

    <?= $form->field($model, 'plant_id') ?>

    <?= $form->field($model, 'purchase_orderid') ?>

    <?php // echo $form->field($model, 'section_id') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
