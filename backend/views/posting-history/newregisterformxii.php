 <?php

 use yii\helpers\Html;
 use yii\widgets\ActiveForm;
 use yii\helpers\Url;

 /* @var $this yii\web\View */
 /* @var $model common\models\Attendance */
 /* @var $form yii\widgets\ActiveForm */
 $this->title = Yii::t('app', 'NEW REGISTER FORM-XII');
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
    <?php $form = ActiveForm::begin(['action' => ['posting-history/newregisterform_xii'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>



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
                foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $monthNumber => $month) {?>
                   <option value='<?=$month?>' <?php if ($smonth == $month) {
                       echo 'selected';
                   }else{echo '';}
               ?>
               >
               <?=$month?>
                   
               </option>;
                <?php 
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
   
         <div style="width:100%; padding:10px;margin:auto;line-height: 1.5; margin-top:50px; border:1px solid #dedede;">
            <h3>
                <span style="font-weight:normal; font-size:13px;">FORM XII<br>[See rule 72 and rule 77 (2)]<br><strong>Combined Muster Roll -Cum- Register OF Wages
                </strong><br></span>
            </h3>
            <table class="table" style="width:96%;font-size: 10px;margin:2%;">
                <tbody><tr>
                    <td style="border:1px solid #444;width:100%;text-align: left;" colspan="4">
                        <table>
                            <tbody><tr>
                                <td style="border: none;">1. Under Rule 104 of Orissa Factories Rules, 1950</td>
                            </tr>
                            <tr>
                                <td style="border: none;">2. Under Rule 26(5) of Orissa Minimum Wages Rules, 1954.</td>
                            </tr>
                            <tr>
                                <td style="border: none;">3. Under Rule 26(1) of Orissa Minimum Wages Rules, 1954</td>
                            </tr>
                            <tr>
                                <td style="border: none;">4. Under Rule 33(1) of Orissa Beedi &amp; Cigar Workers (Condition of employment) Rules, 1969.</td>
                            </tr>
                            <tr>
                                <td style="border: none;">5. Under Rule 239(1) a of Orissa Building &amp; Other Construction Workers etc. Rules, 2002.</td>
                            </tr>
                            <tr>
                                <td style="border: none;">6. Under Rule 52(2)(a) of Orissa Inter-State Migrant Workmen (RE&amp;CS) Rules, 1980.</td>
                            </tr>
                            <tr>
                                <td style="border: none;">7. (Register of payment) of Orissa Shops and Commercial Establishment Rules, 1958.</td>
                            </tr>
                            <tr>
                                <td style="border: none;">8. (Daily record of works &amp; orders) relating to compensating Leave and Deduction from wages of Orissa Shops and Commercial Establishment Rules, 1958."</td>
                            </tr>
                            <tr>
                                <td style="border: none;">9.Under Rule 36 of Orissa Motor Transport Workers Rules, 1966.</td>
                            </tr>
                            <tr>
                                <td style="border: none;">10. Under Rule 77(2)(a) of Orissa Contract Labour (R&amp;A), Rules, 1975.</td>
                            </tr>
                            <tr>
                                <td style="border: none;">11. Under Rule 9 of Orissa Industrial Employment (N&amp;F) H. Rules, 1972.</td>
                            </tr>
                        </tbody></table>

                    </td>
                </tr>

                <tr>
                  <td style="border: 1px solid #444;text-align: center;" colspan="2">Contract Labour (R&amp;A) Act &amp; Odisha Rules </td>
                  <td style="border:1px solid #444;text-align: center;" colspan="2"> [See rule 72 and rule 77 (2)]</td>

              </tr>

              <tr>
                  <td style="border: 1px solid #444;text-align: center;" colspan="2">Name &amp; Address of the Factory/Establishment</td>
                  <td style="border:1px solid #444;text-align: center;" colspan="2">PRADHAN ASSOCIATES PVT.LTD. PLOT NO.126/2262,KHANDAGIRI VIHAR,KHANDAGIRI,BBSR-30,ODISHA </td>

              </tr>
              <tr>
                  <td style="border: 1px solid #444;text-align: center;" colspan="2">Name &amp; Address of the Contractor (if any) Place OF Work  </td>
                  <td style="border:1px solid #444;text-align: center;" colspan="2">PRADHAN ASSOCIATES PVT.LTD. BHURKAMUNDA,JHARSUGUDA,ODISHA </td>

              </tr>
          </tbody></table>
          <div style="width:100%;overflow-x: scroll; ">
            <table style="width:100%;font-size: 10px;" class="table">
               <tbody>
                <tr>
                <td style="border:1px solid #444;" rowspan="2">Sl No</td>
                <td style="border:1px solid #444;" rowspan="2">Gate Pass ID No.</td>
                <td style="border:1px solid #444;" rowspan="2">Name of Employees</td>
                <td style="border:1px solid #444;" rowspan="2">Father's/ Husband's Name</td>
                <td style="border:1px solid #444;" rowspan="2">Sex M/F</td>
                <td style="border:1px solid #444;" rowspan="2">Date Of Birth</td>
                <td style="border:1px solid #444;" rowspan="2">Emp.No./Sl. No.in register of employees</td>
                <td style="border:1px solid #444;" rowspan="2">Degn./Deptt.</td>
                <td style="border:1px solid #444;" rowspan="2">Category</td>
                <td style="border:1px solid #444;" rowspan="2">Date of Joining</td>
                <td style="border:1px solid #444;" rowspan="2">ESI NO.</td>
                <td style="border:1px solid #444;" rowspan="2">PF-UAN No.</td>
                <td style="border:1px solid #444;" colspan="<?=$days?>">ATTENDANCE</td>



                <td style="border:1px solid #444;" rowspan="2">No. of Payble days/Total Units Of Workdone</td>
                <td style="border:1px solid #444;" rowspan="2">No. of Weekly Off If any</td>
                <td style="border:1px solid #444;" rowspan="2">Name of NH &amp; FH for which Wages have been paid</td>
                <td style="border:1px solid #444;" rowspan="2">Leave for which wages have been paid</td>
                <td style="border:1px solid #444;" rowspan="2">Daily Rate of Wages/Piece Rate</td>

                <td style="border:1px solid #444;" colspan="11">Earnings</td>

                <td style="border:1px solid #444;" colspan="14">Deductions</td>



            </tr>
            <tr>
                <?php 
                    for ($j=1; $j <= $days; $j++) { 
                        
                ?>
               <td style="border:1px solid #444;" >
                <?= $j?>
                    
                </td>
               
                <?php 
            }
                ?>
               


               <td style="border:1px solid #444;">Basic</td>
               <td style="border:1px solid #444;">DA/VDA</td>
               <td style="border:1px solid #444;">HRA</td>
               <td style="border:1px solid #444;">Conveyance Allowance</td>
               <td style="border:1px solid #444;">Medical Allowance</td>
               <td style="border:1px solid #444;">Att/Bonus</td>
               <td style="border:1px solid #444;">Special Allowances</td>
               <td style="border:1px solid #444;">Overtime</td>
               <td style="border:1px solid #444;">Miscelleneous Earnings</td>
               <td style="border:1px solid #444;">Others</td>
               <td style="border:1px solid #444;">Total</td>

               <td style="border:1px solid #444;">ESI</td>
               <td style="border:1px solid #444;">PF</td>
               <td style="border:1px solid #444;">PT</td>
               <td style="border:1px solid #444;">TDS</td>
               <td style="border:1px solid #444;">Socy</td>
               <td style="border:1px solid #444;">Insurance</td>
               <td style="border:1px solid #444;">Salary Advance</td>
               <td style="border:1px solid #444;">Fine</td>
               <td style="border:1px solid #444;">Damage</td>
               <td style="border:1px solid #444;">Others</td>
               <td style="border:1px solid #444;">Total</td>
               <td style="border:1px solid #444;">Net Payble</td>
               <td style="border:1px solid #444;">Receipt by Employee/Bank Transaction ID</td>
               <td style="border:1px solid #444;">Date Of Payment</td>
           </tr>
           
            <?php 
            $i = 1;

    if ($posting_details) {
       foreach ($posting_details as $posting_detail) {
        $exit_status = $posting_detail->employee->is_exit ?? 0;
        // if ($exit_status) {
            
         ?>
         <tr>
                <td style="border:1px solid #444;" rowspan="2"><?= $i?></td>
                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->employee->gate_pass ?? ''?></td>
                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->enrolement->adhar_name ?? ''?></td>
                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->enrolement->father_husband_name ?? ''?></td>
                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->enrolement->gender ?? ''?></td>
                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->enrolement->dob ?? ''?></td>
                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->enrolement->workman_sl_no ?? ''?></td>
                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->enrolement->designation ?? ''?></td>
                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->enrolement->category ?? ''?></td>
                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->start_date ?? ''?></td>
                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->esic_code ?? ''?></td>
                <td style="border:1px solid #444;" rowspan="2"><?php echo ($posting_detail->enrolement->pf_account_number ?? '').'-'.($posting_detail->enrolement->uan ?? ''); ?>
                   
                </td>
                <td style="border:1px solid #444;" colspan="<?=$days?>">ATTENDANCE</td>



                <td style="border:1px solid #444;" rowspan="2">No. of Payble days/Total Units Of Workdone</td>
                <td style="border:1px solid #444;" rowspan="2">No. of Weekly Off If any</td>
                <td style="border:1px solid #444;" rowspan="2">Name of NH &amp; FH for which Wages have been paid</td>
                <td style="border:1px solid #444;" rowspan="2">Leave for which wages have been paid</td>
                <td style="border:1px solid #444;" rowspan="2">Daily Rate of Wages/Piece Rate</td>

                <td style="border:1px solid #444;" colspan="11">Earnings</td>

                <td style="border:1px solid #444;" colspan="14">Deductions</td>



            </tr>
            <tr>
                
               <td style="border:1px solid #444;" colspan="<?= $days?>">
               <!-- <td style="border:1px solid #444;" colspan="<?= $days?>"> -->
                
                 <?= $posting_detail->attendances->att ?? ''?>   
                </td>
                <!-- <td style="border:1px solid #444;"><?= $posting_detail->attendances->att_type ?? ''?>  </td>
                <td style="border:1px solid #444;"><?= $posting_detail->attendances->nh ?? ''?>  </td>
                <td style="border:1px solid #444;"><?= $posting_detail->attendances->fh ?? ''?>  </td>
                <td style="border:1px solid #444;"><?= $posting_detail->attendances->nhfh_type ?? ''?>  </td>
                <td style="border:1px solid #444;"><?= $posting_detail->attendances->ot ?? ''?>  </td>
                <td style="border:1px solid #444;"><?= $posting_detail->attendances->ot_type ?? ''?>  </td> -->
               
                

               <td style="border:1px solid #444;">Basic</td>
               <td style="border:1px solid #444;">DA/VDA</td>
               <td style="border:1px solid #444;">HRA</td>
               <td style="border:1px solid #444;">Conveyance Allowance</td>
               <td style="border:1px solid #444;">Medical Allowance</td>
               <td style="border:1px solid #444;">Att/Bonus</td>
               <td style="border:1px solid #444;">Special Allowances</td>
               <td style="border:1px solid #444;">Overtime</td>
               <td style="border:1px solid #444;">Miscelleneous Earnings</td>
               <td style="border:1px solid #444;">Others</td>
               <td style="border:1px solid #444;">Total</td>

               <td style="border:1px solid #444;">ESI</td>
               <td style="border:1px solid #444;">PF</td>
               <td style="border:1px solid #444;">PT</td>
               <td style="border:1px solid #444;">TDS</td>
               <td style="border:1px solid #444;">Socy</td>
               <td style="border:1px solid #444;">Insurance</td>
               <td style="border:1px solid #444;">Salary Advance</td>
               <td style="border:1px solid #444;">Fine</td>
               <td style="border:1px solid #444;">Damage</td>
               <td style="border:1px solid #444;">Others</td>
               <td style="border:1px solid #444;">Total</td>
               <td style="border:1px solid #444;">Net Payble</td>
               <td style="border:1px solid #444;">Receipt by Employee/Bank Transaction ID</td>
               <td style="border:1px solid #444;">Date Of Payment</td>
           </tr>
         <?php 
         $i++;
         // code...
        // }
     }

}else{
    echo 'No Record available';
}
?>
       </tbody>
   </table>
   </div>

</div>




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