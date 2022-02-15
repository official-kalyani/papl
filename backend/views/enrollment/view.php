<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Enrollment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Enrollments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="enrollment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['enrollupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            'id',
            'enrolement_id',
            'adhar_name',
            'father_husband_name',
            'relation_with_employee',
            'adhar_number',
            [
              'attribute' => 'browse_adhar',
              'format' => 'raw',
              'value' => function ($data) {
              return Html::img(Yii::getAlias('@storageUrl'). $data->browse_adhar,
              ['width' => '60px']);
             },
            ],
            [
              'attribute' => 'browse_pp_photo',
              'format' => 'raw',
              'value' => function ($data) {
              return Html::img(Yii::getAlias('@storageUrl'). $data->browse_pp_photo,
              ['width' => '60px']);
             },
            ],            
            'gender',
            'marital_status',
            'mobile_with_adhar',
            'dob',
            'permanent_addrs',
            'permanent_state',
            'permanent_district',
            'permanent_ps',
            'permanent_po',
            'permanent_village',
            'permanent_block',
            'permanent_tehsil',
            'permanent_GP',
            'permanent_pincode',
            'permanent_mobile_number',
            'present_address',
            'present_state',
            'present_district',
            'present_ps',
            'present_po',
            'present_village',
            'present_block',
            'present_tehsil',
            'present_gp',
            'present_pincode',
            'present_mobile_number',
            'blood_group',
            'ID_mark_employee',
            'nationality',
            'religion',
            'caste',
            'referenced_remark',
            'status',
            'comment',
            'is_delete',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
