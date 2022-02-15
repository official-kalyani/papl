<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PurchaseorderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchaseorder-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'po_id') ?>

    <?= $form->field($model, 'purchase_order_name') ?>
    <?= $form->field($model, 'po_number') ?>

    <?= $form->field($model, 'location_id') ?>

    <?= $form->field($model, 'state_id') ?>

    <?= $form->field($model, 'plant_id') ?>
    <?= $form->field($model, 'short_code') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
