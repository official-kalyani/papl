<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Purchaseorder */

$this->title = Yii::t('app', 'Update Purchaseorder: {name}', [
    'name' => $model->po_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Purchaseorders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->po_id, 'url' => ['view', 'id' => $model->po_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="purchaseorder-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'stateList' => $stateList,
    ]) ?>

</div>
