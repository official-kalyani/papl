<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PostingHistory */

$this->title = Yii::t('app', 'Create Posting History');
echo "&nbsp;";
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posting Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posting-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'emp_model' => $emp_model,
        'purchase_order' => $purchase_order,
        'purchase_order_model' => $purchase_order_model,
        'salaryattr' => $salaryattr,
        'salarymodel'=>$salarymodel,
        'deduction_attr'=>$deduction_attr,
        'deduction_model'=>$deduction_model,
        'emp_list' => $emp_list,
        'posting_list' => $posting_list,
        'salary_mapping_list' => $salary_mapping_list,
        'deduction_mapping_list' => $deduction_mapping_list,
        'salary_mapping_model' => $salary_mapping_model,
        'deduction_mapping_model' => $deduction_mapping_model,
        'url_papl_id' => $url_papl_id,
        'enrollment_data' => $enrollment_data,
        'plants' => $plants,
        'locations' => $locations,
        'plant_id_po' => $plant_id_po,
        'location_id_po' => $location_id_po,
        'enrolmentid' => $enrolmentid,
        'purchase_id_po' => $purchase_id_po,
        'sec_id_po' => $sec_id_po,
        'role_id' => $role_id,
        'role' => $role,
        'user_plant_id' => $user_plant_id,
            // 'compare_end_date' => $compare_end_date,
        
    ]) ?>

</div>
