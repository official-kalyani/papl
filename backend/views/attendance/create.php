<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Attendance */

$this->title = Yii::t('app', 'Create Attendance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Attendances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
    if (isset($updated_details_name->username)) {       
   
?>
<span class="badge badge-pill badge-info float-right">Last updated by : <?= $updated_details_name->username ?? '';?></span>
<?php  } ?>
<div class="attendance-create">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
        //'locations' => $locations,
        'posting_historys' => $posting_historys,
        'date' => $date,
        'attendance_history' =>$attendance_history,
        'search_data' => $search_data,
        'plant' => $plant,
        'purchase_order' => $purchase_order,
        'section' => $section,
        'location' => $location,
        'states' => $states,
        'state_id' => $state_id,
        'role' => $role,
        'role_id' => $role_id,
        'user_plant_id' => $user_plant_id,
        'savestatus' => $savestatus,
        'search_result' => $search_result,
        'state_short_code' => $state_short_code,
        'location_short_code' => $location_short_code,
        'plant_short_code' => $plant_short_code,
        'updated_details_name' => $updated_details_name,
    ]) ?>

</div>