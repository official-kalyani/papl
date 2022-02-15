<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Papldesignation */

$this->title = 'Create Papldesignation';
$this->params['breadcrumbs'][] = ['label' => 'Papldesignations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="papldesignation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
