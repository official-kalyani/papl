 <?php

 use yii\helpers\Html;
 use yii\widgets\ActiveForm;
 use yii\helpers\Url;

 /* @var $this yii\web\View */
 /* @var $model common\models\Attendance */
 /* @var $form yii\widgets\ActiveForm */
 $this->title = Yii::t('app', 'NEW REGISTER FORM-B');
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
    <?php $form = ActiveForm::begin(['action' => ['posting-history/newregisterformb'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>



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
<div class="servicecertificate" style="width:96%;margin: 2%;border:none">
    
         <div style="width:100%; padding:10px;margin:auto;line-height: 1.5; margin-top:50px; border:1px solid #dedede;">
            <h3>
                <span style="font-weight:normal; font-size:13px;">FORM B<br>
                    <strong>FORMAT FOR WAGE REGISTER</strong>
                    <br>
                </span>
            </h3>
                <table class="table" style="width:96%;font-size: 10px;margin:2%;">
                    <tbody><tr>
                        <td style="border:1px solid #444;width:100%;text-align: center;" colspan="5">Rate of Minimum Wages and since the date 30/10/2018 TO .......</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #444;"></td>

                        <td style="border:1px solid #444;">SKILLED</td>

                        <td style="border:1px solid #444;">SEMI SKILLED</td>

                        <td style="border:1px solid #444;">HIGH SKILLED</td>

                        <td style="border:1px solid #444;">UN SKILLED</td>

                        <td style="border:1px solid #444;">UNSKILLED</td>

                        <td style="border:1px solid #444;">STAFF</td>

                        <td style="border:1px solid #444;">MANAGEMENT STAFF</td>

                        <td style="border:1px solid #444;">HIGH SKILLED</td>

                        <td style="border:1px solid #444;">SKILLED</td>

                        <td style="border:1px solid #444;">SEMI SKILLED</td>

                        <td style="border:1px solid #444;">UN SKILLED</td>

                        <td style="border:1px solid #444;">UNSKILLED</td>

                        <td style="border:1px solid #444;">HIGH SKILLED</td>

                        <td style="border:1px solid #444;">SKILLED</td>

                        <td style="border:1px solid #444;">SEMI SKILLED</td>

                        <td style="border:1px solid #444;">UN SKILLED</td>

                        <td style="border:1px solid #444;">UNSKILLED</td>

                        <td style="border:1px solid #444;">UNSKILLED</td>

                        <td style="border:1px solid #444;">UN SKILLED</td>

                        <td style="border:1px solid #444;">SEMI SKILLED</td>

                        <td style="border:1px solid #444;">SKILLED</td>

                        <td style="border:1px solid #444;">HIGH SKILLED</td>

                        <td style="border:1px solid #444;">SKILLED</td>

                        <td style="border:1px solid #444;">SEMI SKILLED</td>

                        <td style="border:1px solid #444;">HIGH SKILLED</td>

                        <td style="border:1px solid #444;">UN SKILLED</td>

                        <td style="border:1px solid #444;">UNSKILLED</td>

                    </tr>
                    <tr>
                        <td style="border:1px solid #444;">Minimum Basic</td>
                        <td style="border:1px solid #444;">376.3</td>
                        <td style="border:1px solid #444;">326.3</td>
                        <td style="border:1px solid #444;">436.3</td>
                        <td style="border:1px solid #444;">286.3</td>
                        <td style="border:1px solid #444;">286.3</td>
                        <td style="border:1px solid #444;">461.54</td>
                        <td style="border:1px solid #444;">576.92</td>
                        <td style="border:1px solid #444;">428.08</td>
                        <td style="border:1px solid #444;">398.08</td>
                        <td style="border:1px solid #444;">368.08</td>
                        <td style="border:1px solid #444;">343.08</td>
                        <td style="border:1px solid #444;">343.08</td>
                        <td style="border:1px solid #444;">432</td>
                        <td style="border:1px solid #444;">382</td>
                        <td style="border:1px solid #444;">329</td>
                        <td style="border:1px solid #444;">296</td>
                        <td style="border:1px solid #444;">296</td>
                        <td style="border:1px solid #444;">350.77</td>
                        <td style="border:1px solid #444;">350.77</td>
                        <td style="border:1px solid #444;">375.77</td>
                        <td style="border:1px solid #444;">405.77</td>
                        <td style="border:1px solid #444;">435.77</td>
                        <td style="border:1px solid #444;">388</td>
                        <td style="border:1px solid #444;">338</td>
                        <td style="border:1px solid #444;">448</td>
                        <td style="border:1px solid #444;">298</td>
                        <td style="border:1px solid #444;">298</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #444;">DA</td>
                        <td style="border:1px solid #444;">-</td>
                        <td style="border:1px solid #444;">-</td>
                        <td style="border:1px solid #444;">-</td>
                        <td style="border:1px solid #444;">-</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #444;">Overtime</td>
                        <td style="border:1px solid #444;">752.6</td>
                        <td style="border:1px solid #444;">652.6</td>
                        <td style="border:1px solid #444;">872.6</td>
                        <td style="border:1px solid #444;">572.6</td>
                        <td style="border:1px solid #444;">572.6</td>
                        <td style="border:1px solid #444;">923.08</td>
                        <td style="border:1px solid #444;">1153.84</td>
                        <td style="border:1px solid #444;">856.16</td>
                        <td style="border:1px solid #444;">796.16</td>
                        <td style="border:1px solid #444;">736.16</td>
                        <td style="border:1px solid #444;">686.16</td>
                        <td style="border:1px solid #444;">686.16</td>
                        <td style="border:1px solid #444;">864</td>
                        <td style="border:1px solid #444;">764</td>
                        <td style="border:1px solid #444;">658</td>
                        <td style="border:1px solid #444;">592</td>
                        <td style="border:1px solid #444;">592</td>
                        <td style="border:1px solid #444;">701.54</td>
                        <td style="border:1px solid #444;">701.54</td>
                        <td style="border:1px solid #444;">751.54</td>
                        <td style="border:1px solid #444;">811.54</td>
                        <td style="border:1px solid #444;">871.54</td>
                        <td style="border:1px solid #444;">776</td>
                        <td style="border:1px solid #444;">676</td>
                        <td style="border:1px solid #444;">896</td>
                        <td style="border:1px solid #444;">596</td>
                        <td style="border:1px solid #444;">596</td>
                    </tr>










                    <tr>
                      <td colspan="5" style="border: none;width:100%;font-size: 11px;"> Name of the Establishment PRADHAN ASSOCIATES PVT.LTD.……….………..Name of Owner KABI CHANDRA PRADHAN…………………LIN.</td>

                  </tr>
              </tbody></table>
              <div style="width:780px;overflow-x: scroll; ">
                            <table style="width:100%;font-size: 10px;" class="table">
                               <tbody>
                                <tr>
                                <td style="border:1px solid #444;" rowspan="2">Sl No</td>
                                <td style="border:1px solid #444;" rowspan="2">Sl. No. in Employee register</td>
                                <td style="border:1px solid #444;" rowspan="2">Name</td>
                                <td style="border:1px solid #444;" rowspan="2">Rate of Wage</td>
                                <td style="border:1px solid #444;" rowspan="2">No. of Days worked</td>
                                <td style="border:1px solid #444;" rowspan="2">Overtime hours worked</td>
                                <td style="border:1px solid #444;" rowspan="2">Basic</td>
                                <td style="border:1px solid #444;" rowspan="2">Special Basic</td>
                                <td style="border:1px solid #444;" rowspan="2">DA</td>
                                <td style="border:1px solid #444;" rowspan="2">Payments Overtime</td>
                                <td style="border:1px solid #444;" rowspan="2">HRA</td>
                                <td style="border:1px solid #444;" rowspan="2">*Others</td>
                                <td style="border:1px solid #444;" rowspan="2">Total</td>
                                <td style="border:1px solid #444;text-align: center;" colspan="8">Deduction</td>
                                <td style="border:1px solid #444;" rowspan="2">Net Payment</td>
                                <td style="border:1px solid #444;" rowspan="2">Employer Share PF Welfare Fund</td>
                                <td style="border:1px solid #444;" rowspan="2">Receipt by Employee/Bank Transaction ID</td>
                                <td style="border:1px solid #444;" rowspan="2">Date of Payment</td>
                                <td style="border:1px solid #444;" rowspan="2">Remarks</td>
                                
                               </tr>
                               <tr>
                                <td style="border:1px solid #444;"> ESIC</td>
                                <td style="border:1px solid #444;">PF</td>
                                <td style="border:1px solid #444;">Society</td>
                                <td style="border:1px solid #444;">Income Tax</td>
                                <td style="border:1px solid #444;">Insurance</td>
                                <td style="border:1px solid #444;">Others Salary Advance</td>
                                <td style="border:1px solid #444;">Recoveries</td>
                                <td style="border:1px solid #444;">Total</td>
                                
                               </tr>

                                
              <?php 
              $i=1;
    if ($posting_details) {
       foreach ($posting_details as $posting_detail) {
         ?>
              <tr>
                                <td style="border:1px solid #444;" rowspan="2"><?=$i?></td>
                                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->employee->workman_sl_no ?? ''?></td>
                                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->enrolement->adhar_name ?? ''?></td>
                                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->basic->amount ?? ''?></td>
                                <td style="border:1px solid #444;" rowspan="2">No. of Days worked</td>
                                <td style="border:1px solid #444;" rowspan="2">Overtime hours worked</td>
                                <td style="border:1px solid #444;" rowspan="2">Basic</td>
                                <td style="border:1px solid #444;" rowspan="2">Special Basic</td>
                                <td style="border:1px solid #444;" rowspan="2">DA</td>
                                <td style="border:1px solid #444;" rowspan="2">Payments Overtime</td>
                                <td style="border:1px solid #444;" rowspan="2">HRA</td>
                                <td style="border:1px solid #444;" rowspan="2">*Others</td>
                                <td style="border:1px solid #444;" rowspan="2">Total</td>
                                <td style="border:1px solid #444;text-align: center;" colspan="8"></td>
                                <td style="border:1px solid #444;" rowspan="2">Net Payment</td>
                                <td style="border:1px solid #444;" rowspan="2">Employer Share PF Welfare Fund</td>
                                <td style="border:1px solid #444;" rowspan="2">Receipt by Employee/Bank Transaction ID</td>
                                <td style="border:1px solid #444;" rowspan="2">Date of Payment</td>
                                <td style="border:1px solid #444;" rowspan="2"><?= $posting_detail->enrolement->comment ?? ''?></td>
                                
                               </tr>
                               <tr>
                                <td style="border:1px solid #444;"> <?= $posting_detail->enrolement->esic_code ?? ''?></td>
                                <td style="border:1px solid #444;"><?= $posting_detail->enrolement->pf_code ?? ''?></td>
                                <td style="border:1px solid #444;">Society</td>
                                <td style="border:1px solid #444;">Income Tax</td>
                                <td style="border:1px solid #444;">Insurance</td>
                                <td style="border:1px solid #444;">Others Salary Advance</td>
                                <td style="border:1px solid #444;">Recoveries</td>
                                <td style="border:1px solid #444;">Total</td>
                                
                               </tr>
                
                <?php 
                $i++;
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