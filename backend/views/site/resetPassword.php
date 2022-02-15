<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please choose your new password:</p>

    <div class="row">
        <div class="col-lg-5">
            
            <?php $form = ActiveForm::begin(['action' => ['site/reset-password']]); ?>

                <?= $form->field($user, 'currentPassword')->passwordInput(['autofocus' => true,'maxlength' => true]) ?>
                <?= $form->field($user, 'newPassword')->passwordInput(['autofocus' => true,'maxlength' => true]) ?>
                <?= $form->field($user, 'confirmPassword')->passwordInput(['autofocus' => true,'maxlength' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
