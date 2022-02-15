<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Plant */

$this->title = Yii::t('app', 'Update Plant: {name}', [
    'name' => $model->plant_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plants'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->plant_id, 'url' => ['view', 'id' => $model->plant_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="plant-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'stateList' => $stateList,
    ]) ?>

</div>
