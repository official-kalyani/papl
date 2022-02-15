<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PapldesignationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Papldesignations';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
    if (isset($updated_details_name->username)) {       
   
?>
<span class="badge badge-pill badge-info float-right">Last updated by : <?= $updated_details_name->username ?? '';?></span>
<?php  } ?>
<div class="papldesignation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Papldesignation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'PAPLdesignation',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
