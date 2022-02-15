 <?php

 use yii\helpers\Html;
 use yii\widgets\ActiveForm;
 use yii\helpers\Url;

 /* @var $this yii\web\View */
 /* @var $model common\models\Attendance */
 /* @var $form yii\widgets\ActiveForm */
 $this->title = Yii::t('app', 'SERVICE CERTIFICATE');
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
    <?php $form = ActiveForm::begin(['action' => ['posting-history/servicecertificate'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>



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
    <?php 
    if ($posting_details) {
     foreach ($posting_details as $posting_detail) {
       ?>
      <div style="width:100%; padding:10px;margin:auto;line-height: 1.5; margin-top:50px; border:1px solid #dedede;">
                            <h3>
                                <span style="font-weight:normal; font-size:13px;">FORM XV <br> 
                                (See rule 77)<br><strong> SERVICE CERTIFICATE</strong><br></span>
                            </h3>
                            <table class="table" style="width:96%;font-size: 10px;margin:2%;">
                                <tbody><tr style="border: none;">
                                    <td width="50%" style="border: none;">Name and address of the contractor    </td>
                                    <td>M/s Pradhan Associates Pvt Ltd, Khandagiri Vihar, Khandagiri, BBSR-751030, Khurda (Odisha)</td>
                                </tr>
                                <tr>
                                    <td style="border: none;">Name and address of establishment in / under which contract is carried on</td>
                                    <td>M/s Pradhan Associates Pvt Ltd, Khandagiri Vihar, Khandagiri, BBSR-751030, Khurda (Odisha)</td>
                                </tr>
                                <tr>
                                    <td style="border: none;">Nature of work    </td>
                                    <td>Operation_Maintainance_Project</td>
                                </tr>
                                <tr>
                                    <td style="border: none;">Name and address of workman</td>
                                    <td><?= $posting_detail->enrolement->adhar_name ?? ''?></td>
                                </tr>
                                <tr>
                                    <td style="border: none;">Name and address of principal employer</td>
                                    <td><?= $posting_detail->plant->plant_name ?? ''?></td>
                                </tr>
                                <tr>
                                    <td style="border: none;">date of birth</td>
                                    <td><?= $posting_detail->enrolement->dob?></td>
                                </tr>
                                 <tr>
                                    <td style="border: none;">Identification marks</td>
                                    <td><?= $posting_detail->enrolement->ID_mark_employee?></td>
                                </tr>
                                 <tr>
                                    <td style="border: none;">Father's / Husband's Name</td>
                                    <td><?= $posting_detail->enrolement->father_husband_name?></td>
                                </tr>
                                  
                                <tr>
                                    <td colspan="2" style="border: none;">
                                        <table style="width: 94%;margin: 3%;border:1px solid #444;">
                                            
                                            
                                        
                                            <tbody><tr>
                                                
                                                <td style="border:1px solid #444;" rowspan="2">Sl. No</td>
                                                
                                            
                                                <td style=" border:1px solid #444;" colspan="2">Total period for whichemployed</td>
                                        

                                                <td style=" border:1px solid #444;" rowspan="2">Nature of work done</td>
                                                
                                            
                                                <td style="border: 1px solid #444;" rowspan="2">Rate of wages (with particulars of unit in case of piece work)</td>
                                            
                                                <td style="border: 1px solid #444;" rowspan="2">Remarks</td>
                                               
                                            </tr>
                                            <tr>
                                                <td style="border:1px solid #444;">From</td>
                                                <td style="border:1px solid #444;">To</td>
                                            </tr>
                                            <?php if($service_details){
                                                foreach ($service_details as $service_detail) { ?>
                                                    <tr>
                                                <td style="border:1px solid #444;"><?= $service_detail->id?></td>
                                                <td style="border:1px solid #444;"><?= date('d-m-Y',strtotime($service_detail->start_date))?></td>
                                                <td style="border:1px solid #444;"><?= date('d-m-Y',strtotime($service_detail->end_date))?></td>
                                                <td style="border:1px solid #444;"></td>
                                                <td style="border:1px solid #444;"><?= $service_detail->basic->amount ?? ''?></td>
                                                <td style="border:1px solid #444;"><?= $service_detail->enrolement->comment ?? '' ?></td>
                                                
                                            </tr>

                                                    
                                               <?php }

                                            }
                                            ?>
                                            <tr>
                                                <td style="border:1px solid #444;">1</td>
                                                <td style="border:1px solid #444;"><?= date('d-m-Y',strtotime($posting_detail->start_date))?></td>
                                                <td style="border:1px solid #444;">0000-00-00</td>
                                                <td style="border:1px solid #444;"></td>
                                                <td style="border:1px solid #444;"><?= $posting_detail->basic->amount ?? ''?></td>
                                                <td style="border:1px solid #444;"><?= $posting_detail->enrolement->comment ?? '' ?></td>
                                                
                                            </tr>
                                        </tbody></table>
                                    </td>
                                </tr>
                                
                               
                               
                            </tbody></table>
                        </div>
    <?php }
}else{
    echo 'No Record available';
}
?>



</div>