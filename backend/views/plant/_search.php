<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PlantSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plant-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'plant_id') ?>

    <?= $form->field($model, 'plant_name') ?>

    <?= $form->field($model, 'location_id') ?>

    <?= $form->field($model, 'state_id') ?>
    <?= $form->field($model, 'short_code') ?>

    <?= $form->field($model, 'is_delete') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
