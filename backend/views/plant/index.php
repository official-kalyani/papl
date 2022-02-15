<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PlantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Plants');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
    if (isset($updated_details_name->username)) {       
   
?>
<span class="badge badge-pill badge-info float-right">Last updated by : <?= $updated_details_name->username ?? '';?></span>
<?php  } ?>
<div class="plant-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Plant'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'plant_id',
            'plant_name',         
            
            [
             'attribute' => 'location_id',
             'value' => 'location.location_name'
             ],
            [
             'attribute' => 'state_id',
             'value' => 'state.state_name'
             ],
             'short_code',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
