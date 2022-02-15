 <?php

 use yii\helpers\Html;
 use yii\widgets\ActiveForm;
 use yii\helpers\Url;

 /* @var $this yii\web\View */
 /* @var $model common\models\Attendance */
 /* @var $form yii\widgets\ActiveForm */
 $this->title = Yii::t('app', 'NEW REGISTER FORM-XIX');
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
    <?php $form = ActiveForm::begin(['action' => ['posting-history/newregisterform_xix'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>



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
            <div class="form-group field-client">
                <div class="form-group field-attendance-location_id">
                    <label class="control-label" for="attendance-location_id">Year</label>
                    <select name="PostingHistory[year]" class="form-control" id="dropdownYear" style="width: 120px;" >
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group field-client">
                <div class="form-group field-attendance-location_id">
                    <label class="control-label" for="attendance-location_id">Month</label>
                    <select name="PostingHistory[month]" class="form-control" id="dropdownmonth" style="width: 120px;" >
                        <?php
                        foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $monthNumber => $month) {
                            echo "<option value='$month'>{$month}</option>";
                        }
                        ?>
                        
                    </select>
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
<div class="servicecertificate" style="width:96%;margin: 2%;border:none">
    <?php 
    // if ($posting_details) {
    //    foreach ($posting_details as $posting_detail) {
    ?>
    <div class="col-md-12" style="padding:0px; background:#fff;">
        <div class="slip" style="width:96%;margin: 2%; border:none;">
          
            <div style="width:80%; padding:10px;margin:auto;line-height: 1.5; margin-top:50px; border:1px solid #dedede;">
                <h3>
                    <span style="font-weight:normal; font-size:13px;">FORM-XIX<br><strong>COMBINED REGISTER OF OVERTIME WORKING AND PAYMENT</strong><br>[See rule – 77 (2) (e)]</span>
                </h3>
                <table class="table" style="width:96%;font-size: 10px;margin:2%;">
                 
                    <tbody><tr>
                        <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4"> Rule 79 of Orissa Factories Rules, 1950 (N.B.: Rule 80 &amp; Form 11 may be annulled)</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4"> Rule 25(2) of Orissa Minimum Wages Rules, 1954.</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4"> Rule 77(2)(e) of Orissa Contract Labour (R&amp;A) Rules, 1975.</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4">Rule 12(4) &amp; Rule 15(3) of Orissa Shops &amp; Commercial Establishment Rules, 1956.</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4">Rules 33(5) of Orissa B.C.W. (COE) Rules, 1969.</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4">Rule 37 of Orissa M.T. Workers Rules, 1966.</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4">Rule 52(2)(a) of Orissa ISMW (RE &amp; CS) Rules, 1980.</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4">Rule 239(1)(c) of Orissa Building and Other Construction Workers</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4">(Regulation of Employment &amp; Condition of Service) Rules, 2002.”</td>
                    </tr>
                    
                    
                    
                    
                    <tr>
                      <td style="border: 1px solid #444;text-align: center;" colspan="2">Month/Year </td>
                      <td style="border:1px solid #444;text-align: center;" colspan="2">09/2021 </td>

                  </tr>
                  
              </tbody></table>
              <div style="width:100%;overflow-x: scroll; ">
                  <table style="width:100%;font-size: 10px;" class="table">
                     <tbody><tr>
                        <td style="border:1px solid #444;" rowspan="2">Sl No</td>
                        <td style="border:1px solid #444;" rowspan="2">Name of Employees</td>
                        <td style="border:1px solid #444;" rowspan="2">Father's/ Husband's Name</td>
                        <td style="border:1px solid #444;" rowspan="2">Sex</td>
                        <td style="border:1px solid #444;" rowspan="2">Designation</td>
                        <td style="border:1px solid #444;" rowspan="2">Emp. No./ Sl.No. in register of employees</td>
                        
                        <td style="border:1px solid #444;" colspan="2">Particulars of OT work</td>
                        <td style="border:1px solid #444;" rowspan="2">Normal rate of the wages per hour</td>
                        <td style="border:1px solid #444;" rowspan="2">Overtime rate of wages per hour</td>
                        <td style="border:1px solid #444;" rowspan="2">Total OT earnings</td>
                        <td style="border:1px solid #444;" colspan="1">Signature of the employee</td>
                        <td style="border:1px solid #444;" rowspan="2">Signature of the paying Authority</td>
                        
                        
                    </tr>
                    
                    <tr>
                     
                        <td style="border:1px solid #444;">Date</td>
                        <td style="border:1px solid #444;">Hours</td>
                        <td style="border:1px solid #444;">Bank Account no</td>
                        
                    </tr>
                    
                </tbody></table>
            </div>
        </div>
    </div>
</div>
<?php 
// }
// }else{
//     echo 'No Record available';
// }
?>



</div>
<script>
    $('#dropdownYear').each(function() {

      var year = (new Date()).getFullYear();
      var current = year;
      year -= 3;
      for (var i = 0; i < 6; i++) {
        if ((year+i) == current)
          $(this).append('<option selected value="' + (year + i) + '">' + (year + i) + '</option>');
      else
          $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
  }

})

    


</script>