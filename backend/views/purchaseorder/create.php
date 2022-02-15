<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Purchaseorder */

$this->title = Yii::t('app', 'Create Purchaseorder');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Purchaseorders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchaseorder-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'stateList' => $stateList,
    ]) ?>

</div>
