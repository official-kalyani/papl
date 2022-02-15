<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Section */

$this->title = Yii::t('app', 'Update Section: {name}', [
    'name' => $model->section_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->section_id, 'url' => ['view', 'id' => $model->section_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="section-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'stateList' => $stateList,
    ]) ?>

</div>
