<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Salary */

$this->title = Yii::t('app', 'Create Salary');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Salaries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salary-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'attribute_name',
            [
				'attribute' => 'type',
				'value' => function ($data) {
				  return ucfirst($data->type) ;
				}
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
