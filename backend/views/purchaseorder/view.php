<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Purchaseorder */

$this->title = $model->po_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Purchaseorders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="purchaseorder-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->po_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->po_id], [
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
            'po_id',
            'purchase_order_name',
            'po_number',
             [

                'label' => 'Loction Name',

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
            'short_code',

            
        ],
    ]) ?>

</div>
