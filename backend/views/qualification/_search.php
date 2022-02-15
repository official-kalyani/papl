<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\QualificationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="qualification-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'qualification_id') ?>

    <?= $form->field($model, 'enrolement_id') ?>

    <?= $form->field($model, 'highest_qualification') ?>

    <?= $form->field($model, 'university_name') ?>

    <?php // echo $form->field($model, 'college_name') ?>

    <?php // echo $form->field($model, 'year_of_passout') ?>

    <?php // echo $form->field($model, 'division_percent') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
