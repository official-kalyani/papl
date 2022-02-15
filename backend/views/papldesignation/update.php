<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Papldesignation */

$this->title = 'Update Papldesignation: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Papldesignations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="papldesignation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
