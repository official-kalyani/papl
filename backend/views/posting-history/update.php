<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PostingHistory */

$this->title = Yii::t('app', 'Update Posting History: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posting Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="posting-history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'emp_model' => $emp_model,
    ]) ?>

</div>
