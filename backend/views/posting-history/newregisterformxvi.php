 <?php

 use yii\helpers\Html;
 use yii\widgets\ActiveForm;
 use yii\helpers\Url;

 /* @var $this yii\web\View */
 /* @var $model common\models\Attendance */
 /* @var $form yii\widgets\ActiveForm */
 $this->title = Yii::t('app', 'NEW REGISTER FORM-XVI');
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
    <?php $form = ActiveForm::begin(['action' => ['posting-history/newregisterform_xvi'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>



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
    <div style="width:80%; padding:10px;margin:auto;line-height: 1.5; margin-top:50px; border:1px solid #dedede;">
        <div style="width: 100%;height: auto;">
            <h3>
                <span style="font-weight:normal; font-size:13px;">FORM-XVI<br><strong>Combined Register of Fines, Deductions for Damage or Loss and Advances</strong><br>[See rule 77(2) (d)]</span>
            </h3>
            <table class="table" style="width:96%;font-size: 10px;margin:2%;">
                <tbody><tr>
                    <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4">
                        Under Rule 21 (4) of Orissa Minimum Wages Rules, 1954                                                                                                               <table>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4">
                        Under Rule, 78 (d) (fine), 77 (22) (d) (dedu.), 77 (2) (d) (adv.) of Orissa Contract Labour (R &amp; A) Rules, 1975.                                                                                                                <table>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4">
                        
                       Under Rule 3 (1) (fine), 4 (deductions) and 17 (3) (advances) of Orissa Payment of Wages Rules, 1936.                                                                                                               <table></table>
                   </td>
               </tr>
               <tr>
                <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4">
                    
                   Under Rule 52 (2) C of Orissa I.S.M.W (RE &amp; CS) Rules, 1980.                                                                                                               <table></table>
               </td>
           </tr>
           <tr>
            <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4">
                
               Under Rule-239 (1) (b) of Orissa Building &amp; Other Construction Workers (RE &amp; CS) Rules, 2002.”                                                                                                             <table></table>
           </td>
       </tr>
       <tr>
          <td style="border: 1px solid #444;text-align: left;" colspan="2">Contract Labour (R&amp;A) Act &amp; Odisha Rules </td>
          <td style="border:1px solid #444;text-align: left;" colspan="2"> [See rule 77(2) (d)]</td>

      </tr>
      
      <tr>
          <td style="border: 1px solid #444;text-align: left;" colspan="2">Name &amp; Address of the Factory/Establishment</td>
          <td style="border:1px solid #444;text-align: left;" colspan="2">PRADHAN ASSOCIATES PVT.LTD. PLOT NO.126/2262,KHANDAGIRI VIHAR,KHANDAGIRI,BBSR-30,ODISHA </td>

      </tr>
      <tr>
          <td style="border: 1px solid #444;text-align: left;" colspan="2">Name &amp; Address of the Contractor (if any) Place OF Work  </td>
          <td style="border:1px solid #444;text-align: left;" colspan="2">PRADHAN ASSOCIATES PVT.LTD. BHURKAMUNDA,JHARSUGUDA,ODISHA </td>

      </tr>
      <tr>
          <td style="border: 1px solid #444;text-align: center;" colspan="2">Name &amp; Address Of the Principal Employer</td>
          <td style="border:1px solid #444;text-align: center;" colspan="2">HIL_SMELTER_HIRAKUD</td>

      </tr>
      
      <tr>
          <td style="border: 1px solid #444;text-align: center;" colspan="2">Month/Year </td>
          <td style="border:1px solid #444;text-align: center;" colspan="2">09/2019 </td>

      </tr>
      
      
      <tr>
        <td style="border: none;">Wage Period From  29/05/2021             To   -- -- --                          (Monthly/Fortnightly/Weekly/Daily/Piece rated)
        </td>
    </tr>
</tbody></table>
<div style="width:100%;overflow-x: scroll; ">
  <table style="width:100%;font-size: 10px;" class="table">
     <tbody><tr>
        <td style="border:1px solid #444;" rowspan="2">Sl No</td>
        <td style="border:1px solid #444;" rowspan="2">Name of Employees</td>
        <td style="border:1px solid #444;" rowspan="2">Father's/ Husband's Name</td>
        <td style="border:1px solid #444;" rowspan="2">Designation Emp. No./ Sl.No. in register of employees</td>
        <td style="border:1px solid #444;" rowspan="2">Nature &amp; date of offence for which fine imposed</td>
        <td style="border:1px solid #444;" rowspan="2">Date and particulars of damages/loss caused</td>
        <td style="border:1px solid #444;" rowspan="2">Whether worker showed cause against fine or deductions</td>
        <td style="border:1px solid #444;" rowspan="2">Amount of the fine imposed/deduction made</td>
        <td style="border:1px solid #444;" rowspan="2">Date &amp; purpose for which advance was made</td>
        <td style="border:1px solid #444;" rowspan="2">Amount of advance made &amp; purpose thereof</td>
        <td style="border:1px solid #444;" rowspan="2">No. of instalments granted for repayment of fines/deductions/advance</td>
        <td style="border:1px solid #444;" rowspan="2">Wages period and rate of wages payable</td>
        <td style="border:1px solid #444;" colspan="2">Date of recovery of fine/deduction/advance</td>
        
        <td style="border:1px solid #444;" rowspan="2">Remarks</td>
        
        
        
        
    </tr>
    
    <tr>
     
        <td style="border:1px solid #444;">First Installment</td>
        <td style="border:1px solid #444;">Last Installment</td>
        
    </tr>
    
</tbody></table>
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