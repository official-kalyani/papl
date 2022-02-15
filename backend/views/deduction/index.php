<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DeductionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Deductions');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
    if (isset($updated_details_name->username)) {       
   
?>
<span class="badge badge-pill badge-info float-right">Last updated by : <?= $updated_details_name->username ?? '';?></span>
<?php  } ?>
<div class="deduction-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Deduction'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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


</div>
