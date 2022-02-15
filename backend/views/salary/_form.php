<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Salary */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    .mtmin {
    margin-top: 25px;
}
</style>
<div class="salary-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
<div class="col-2"> 
    <?= $form->field($model, 'attribute_name')->textInput(['maxlength' => true]) ?>
</div>
<div class="col-2">  

    <?= $form->field($model, 'type')->dropdownList([
                'amount' => 'Amount', 
                'percentage' => 'Percentage',
                ],
                ['prompt'=>'Select type']
                );?>
</div>

<div class="col-2"> 
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success mtmin']) ?>
    </div>
</div>
 </div>
    <?php ActiveForm::end(); ?>

</div>
