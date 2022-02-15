<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model common\models\PostingHistorySearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="posting-history-search">

    <?php $form = ActiveForm::begin([
        'action' => ['transfer'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($employee_lists, 'id') ?>

    <?= $form->field($employee_lists, 'papl_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
