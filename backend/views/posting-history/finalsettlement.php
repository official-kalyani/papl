 <?php

 use yii\helpers\Html;
 use yii\widgets\ActiveForm;
 use yii\helpers\Url;

 /* @var $this yii\web\View */
 /* @var $model common\models\Attendance */
 /* @var $form yii\widgets\ActiveForm */
 $this->title = Yii::t('app', 'FULLFINAL SETTLEMENT');
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
    <?php $form = ActiveForm::begin(['action' => ['posting-history/finalsettlement'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>



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


<div class="col-md-12" style="padding:0px; background:#fff;">
    <div class="finalsettlement" style="width:96%;margin: 2%; border:none;">

        <div style="width:800px; padding:10px;margin:auto; margin-top:50px;">


           <div style="width:800px;overflow-x: scroll; ">
              <table style="width:100%;font-size: 10px;" class="table">
                 <tbody><tr>
                    <td style="border:1px solid #444;">Sl No</td>
                    <td style="border:1px solid #444;">Name of Workmen</td>
                    <td style="border:1px solid #444;">Father's Name</td>
                    <td style="border:1px solid #444;">Designation</td>
                    <td style="border:1px solid #444;">Category</td>
                    <td style="border:1px solid #444;">Rate of Wages</td>
                    <td style="border:1px solid #444;">DOJ</td>
                    <td style="border:1px solid #444;">DOL</td>
                    <td style="border:1px solid #444;">Total Days</td>
                    <td style="border:1px solid #444;">Leave every 20 days 1</td>
                    <td style="border:1px solid #444;">Retrenchment Benfit @ 15 Days per every Completed Year &amp; 240-319, 15 days retrenchment/ 320 above days 30 days retrenchment benfit paid</td>
                    <td style="border:1px solid #444;">90-239 days 3 days Notice Pay, 240-319 or 320 above 26 days notice pay </td>
                    <td style="border:1px solid #444;">Bonus Days from oct to sep</td>
                    <td style="border:1px solid #444;">Bonus Basic days x Basic Rate x 8.33% </td>
                    <td style="border:1px solid #444;">Exgratia</td>
                    <td style="border:1px solid #444;">Total</td>
                    <td style="border:1px solid #444;">Gratuity calculation minimum 10 to above employees working &amp; continue 5 yrs working, per year 15 days payment on basic salary</td>
                    <td style="border:1px solid #444;">Signature</td>
                    <td style="border:1px solid #444;">Previous Year Amt</td>
                    <td style="border:1px solid #444;">Previous Year Days</td>
                    <td style="border:1px solid #444;">Previous Year Bonus Days</td>
                </tr>
<?php 
    $i = 1;
    if ($posting_details) {
       foreach ($posting_details as $posting_detail) {
?>

                <tr>

                    <td style="border:1px solid #444;"><?=$i?></td>
                    <td style="border:1px solid #444;"><?= $posting_detail->enrolement->adhar_name ?? ''?></td>
                    <td style="border:1px solid #444;"><?= $posting_detail->enrolement->father_husband_name ?? ''?></td>
                    <td style="border:1px solid #444;"><?= $posting_detail->enrolement->designation ?? ''?></td>
                    <td style="border:1px solid #444;"><?= $posting_detail->enrolement->category ?? ''?></td>
                    <td style="border:1px solid #444;"><?= $posting_detail->basic->amount ?? ''?></td>
                    <td style="border:1px solid #444;"><?= date('d-m-Y',strtotime($posting_detail->start_date)) ?? ''?></td>
                    <td style="border:1px solid #444;"></td>
                    <td style="border:1px solid #444;"></td>
                    <td style="border:1px solid #444;"></td>                                
                    <td style="border:1px solid #444;"></td>
                    <td style="border:1px solid #444;"></td>
                    <td style="border:1px solid #444;"></td>
                    <td style="border:1px solid #444;"></td>
                    <td style="border:1px solid #444;"></td>
                    <td style="border:1px solid #444;"></td>
                    <td style="border:1px solid #444;"></td>
                    <td style="border:1px solid #444;"></td>
                    <td style="border:1px solid #444;"></td>
                    <td style="border:1px solid #444;"></td>
                    <td style="border:1px solid #444;"></td>


                </tr>


               <?php 
               $i++;
}
}else{
    echo 'No Record available';
}
?>


              






            </tbody></table>
        </div>

    </div>
</div>
</div>




</div>