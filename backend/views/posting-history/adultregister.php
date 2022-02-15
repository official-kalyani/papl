 <?php

 use yii\helpers\Html;
 use yii\widgets\ActiveForm;
 use yii\helpers\Url;

 /* @var $this yii\web\View */
 /* @var $model common\models\Attendance */
 /* @var $form yii\widgets\ActiveForm */
 $this->title = Yii::t('app', 'ADULT REGISTER');
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
    <?php $form = ActiveForm::begin(['action' => ['posting-history/adultregister'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>



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

    <?php 
    // if ($posting_details) {
    //    foreach ($posting_details as $posting_detail) {
    ?>
    <div class="col-md-12" style="padding:0px; background:#fff;">
                        <div class="slip" style="width:96%;margin: 2%; border:none;">
                       
                        <div style="width:800px; padding:10px;margin:auto; margin-top:50px;">
                                                           
                                    
                            <strong>REGISTER OF ADULT WORKERS</strong>
                            
                                
                            
                             <div style="width:800px;overflow-x: scroll; ">
                              <table style="width:100%;font-size: 10px;" class="table">
                               <tbody><tr>
                                <td style="border:1px solid #444;" rowspan="2">Worker's Distinguishing Number</td>
                                <td style="border:1px solid #444;" rowspan="2">Name</td>
                                <td style="border:1px solid #444;" rowspan="2">Father's Name</td>
                                <td style="border:1px solid #444;" colspan="3">Home Address</td>
                                <td style="border:1px solid #444;" rowspan="2">Caste or Religion</td>
                                <td style="border:1px solid #444;" rowspan="2">Residential Address of the Workers</td>
                                <td style="border:1px solid #444;" rowspan="2">Date of first Employment</td>
                                <td style="border:1px solid #444;" rowspan="2">Age at the time of   first Employment</td>
                                <td style="border:1px solid #444;" rowspan="2">Nature of work</td>
                                <td style="border:1px solid #444;" rowspan="2">If working under an exemption (State rule) number</td>
                                <td style="border:1px solid #444;" rowspan="2">Number of general certificate of fitness if an adolescent</td>
                                <td style="border:1px solid #444;" rowspan="2">Letter of group as in Form No. 11</td>
                                <td style="border:1px solid #444;" rowspan="2">Number of relay if working in sifts</td>
                                <td style="border:1px solid #444;" colspan="2">Rate of wage per</td>
                                <td style="border:1px solid #444;" rowspan="2">Total weekly hours</td>
                                <td style="border:1px solid #444;" rowspan="2">Date of Discharge</td>
                                
                               
                         
                               </tr>
                              
                               <tr>
                               
                                <td style="border:1px solid #444;">Village</td>
                                <td style="border:1px solid #444;">Police Station</td>
                                <td style="border:1px solid #444;">District</td>
                                <td style="border:1px solid #444;">Piece</td>
                                <td style="border:1px solid #444;">Time</td>
                             
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