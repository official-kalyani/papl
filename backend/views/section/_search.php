<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="section-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'section_id') ?>

    <?= $form->field($model, 'section_name') ?>

    <?= $form->field($model, 'location_id') ?>

    <?= $form->field($model, 'state_id') ?>

    <?= $form->field($model, 'plant_id') ?>

    <?php // echo $form->field($model, 'po_id') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
