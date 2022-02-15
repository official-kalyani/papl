<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Section */

$this->title = $model->section_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="section-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->section_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->section_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'section_id',
            'section_name',
            [

                'label' => 'Location Name',

                'value' => $model->location->location_name,

            ],
            [

                'label' => 'State Name',

                'value' => $model->state->state_name,

            ],
            [

                'label' => 'Plant Name',

                'value' => $model->plant->plant_name,

            ],
            [

                'label' => 'Purchaseorder Name',

                'value' => $model->po->purchase_order_name,

            ],
            'short_code',

            
        ],
    ]) ?>

</div>
