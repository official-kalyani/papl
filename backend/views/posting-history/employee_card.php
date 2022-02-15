<?php

 use yii\helpers\Html;
 use yii\widgets\ActiveForm;
 use yii\helpers\Url;

 /* @var $this yii\web\View */
 /* @var $model common\models\Attendance */
 /* @var $form yii\widgets\ActiveForm */
 $this->title = Yii::t('app', 'Employment card');
$this->params['breadcrumbs'][] = $this->title;
 ?>
 <style>
 h3{text-align:center;border-bottom: 1px solid #333;}
 .textbox{border:none;border-bottom:1px solid #444; width:200px;}
 th{text-align:left;}
 td{height:30px;}
 table .table{border-bottom:1px solid #444; border-collapse:collapse;}
 .table tr th{ border-bottom:1px solid #444; border-collapse:collapse;  padding:3px;}
 .table tr td{ border-bottom:1px solid #444; border-collapse:collapse;  padding:3px;}
 .excelbtn{ position: absolute;z-index:999;top: 215px;right: 42px;}

</style>
<div class="container">
    <?php $form = ActiveForm::begin(['action' => ['posting-history/employee_card'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>



    <div class="row">

        <div class="col-md-2">
          <div class="form-group field-client">
            <div class="form-group field-attendance-location_id">
                <label class="control-label" for="attendance-location_id">Plant</label>
                <select id="postinghistory-plant_id" class="form-control location" name="PostingHistory[plant_id]" required=""  aria-invalid="false">
                    <option value="">Plant</option>
                    <?php
                    foreach ($plants as $key => $value) {?>

                      <option value="<?=$key?>"  <?=($key == $plant_id)?'selected':'';?>>
                       <?= $value?> 

                   </option> 
               <?php  }
               ?>
           </select>

           <div class="help-block"></div>
       </div>
   </div>
</div>





<div class="col-md-2">

    <label></label>
    <?= Html::submitButton("Search", ['class' => "btn btn-info", 'name' => 'search', 'value' => 'search']); ?>



</div>

</div>
<?php ActiveForm::end(); ?>
</div>
<div class="employementcard" style="width:96%;margin: 2%;border:none">
    <?php 
    if ($posting_details) {
     foreach ($posting_details as $posting_detail) {
       ?>
       <div style="width:80%; padding:10px;margin:auto;line-height: 1.5; margin-top:50px; border:1px solid #dedede;">
        <h3>
            <span style="font-weight:normal; font-size:13px;">FORM X <br> (See Rule 75)</span><br>
            EMPLOYMENT CARD<br>
        </h3>
        <table class="table" style="width:96%;font-size: 10px;margin:2%;">
            <tbody><tr>
                <td width="50%">Name and address of contractor.</td>
                <td>M/s. Pradhan Associates Pvt. Ltd., Plot No: 126/2262, Khandagiri Vihar, Khandagiri, Bhubaneswar - 30</td>
            </tr>

            <tr>
                <td>Name and address of establishment.</td>
                <td>M/s. Pradhan Associates Pvt. Ltd., Plot No: 126/2262, Khandagiri Vihar, Khandagiri, Bhubaneswar - 30</td>
            </tr>
            <tr>
                <td>Nature of Work.</td>
                <td>Operation_Maintainance_Project</td> 
            </tr>
            <tr>
                <td>Establishment in/under which contract is carried on.</td>
                <td><?= $posting_detail->plant->plant_name?> ,ODISHA</td>
            </tr>
            <tr>
                <td>Name and address of principal Employer.</td>
                <td><?= $posting_detail->plant->plant_name?> ,ODISHA</td>
            </tr>
            <tr>
                <td colspan="2" style="border: none;">
                    <table style="width: 94%;margin: 3%;">
                        <tbody>
                            <tr>
                                <td width="50%" style="border: none;">1. Name of the workman:</td>
                                <td><?= $posting_detail->enrolement->adhar_name ?? ''?></td>
                            </tr>
                            <tr>
                                <td width="50%" style="border: none;">2. Sl. No of the register of workman employed:</td>
                                <td><?= $posting_detail->employee->workman_sl_no ?? ''?></td>
                            </tr>
                            <tr>
                                <td width="50%" style="border: none;">3. Nature of Employment designation:</td>
                                <td><?= strtoupper($posting_detail->enrolement->designation ?? '')?></td>
                            </tr>
                            <tr>
                                <td width="50%" style="border: none;">4. Wage rate (with particulars of unit), in case of piece work:</td>
                                <td><?= $posting_detail->basic->amount ?? ''?></td>
                            </tr>
                            <tr>
                                <td width="50%" style="border: none;">5. Wage period:</td>
                                <td>MONTHLY</td>
                            </tr>
                            <tr>
                                <td width="50%" style="border: none;">6. Period of employment:</td>
                                <td>
                                    FROM &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; <?=  date('d-m-Y',strtotime($posting_detail->start_date))?> &nbsp; &nbsp;&nbsp;&nbsp;
                                    TO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="border: none;">7. Remarks:</td>
                                <td><?= $posting_detail->enrolement->comment ?? '' ?></td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>

                <tr>
                    <td style="border: none;"></td>
                    <td style="border: none;"></td>
                </tr>
                <tr>
                    <td style="border: none;"></td>
                    <td style="border: none;"></td>
                </tr>
                <tr>
                    <td style="border: none;"></td>
                    <td style="border: none;"></td>
                </tr>
                <tr>
                    <td style="border: none;"></td>
                    <td style="border: none;text-align: right">Signature of Contractor</td>
                </tr>
            </tbody></table>
        </div>
    <?php }
}else{
    echo 'No Record available';
}
?>



</div>