<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Enrollment */

$this->title = Yii::t('app', 'Create Enrollment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Enrollments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="enrollment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
