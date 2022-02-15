<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AttendanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Attendances');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendance-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Attendance'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'papl_id',
            'employee_name',
            'att',
            'att_type',
            //'nh',
            //'fh',
            //'nhfh_type',
            //'ot',
            //'ot_type',
            //'remark',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
