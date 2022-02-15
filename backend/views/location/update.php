<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Location */

$this->title = Yii::t('app', 'Update Location: {name}', [
    'name' => $model->location_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Locations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->location_id, 'url' => ['view', 'id' => $model->location_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="location-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'stateList' => $stateList,
    ]) ?>

</div>
