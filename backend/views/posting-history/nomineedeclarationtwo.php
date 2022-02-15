 <?php

 use yii\helpers\Html;
 use yii\widgets\ActiveForm;
 use yii\helpers\Url;

 /* @var $this yii\web\View */
 /* @var $model common\models\Attendance */
 /* @var $form yii\widgets\ActiveForm */
 $this->title = Yii::t('app', 'NOMINEE DECLARATION FORM 2');
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
    <?php $form = ActiveForm::begin(['action' => ['posting-history/nomineedeclarationtwo'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>



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
       <div style="width:100%; padding:10px;margin:auto;line-height: 1.5; margin-top:50px; border:1px solid #dedede;">
                            <h3>
                                <span style="font-weight:normal; font-size:13px;">FORM-2(REVISED) <br> <strong>NOMMINATION AND DECLARATION FORM FOR UNEXEMPTED/EXEMPTED ESTABLISHMENTS</strong><br>
                                Declaration and Nomination Form under the Employees Provident Funds and Employees Penson Scheemes<br>(Paragraph 33 and 61 (1) of the Employees Provident Fond Scheme 1952 and Paragraph 18 of the Employees Penson Scheme 1995)<br></span>
                            </h3>
                            <table class="table" style="width:96%;font-size: 10px;margin:2%;">
                                <tbody><tr style="border: none;">
                                    <td style="border: none; width:50%; float:left;">1. Name of Person making nomination(in block letters)</td>
                                    <td style="width:50%;"><?= $posting_detail->enrolement->adhar_name ?? ''?></td>
                                </tr>
                                <tr>
                                    <td style="border: none;">2. Father's / Husband's Name</td>
                                    <td><?= $posting_detail->enrolement->father_husband_name ?? ''?></td>
                                </tr>
                                <tr>
                                    <td style="border: none;">3. Date of Birth</td>
                                    <td><?= $posting_detail->enrolement->dob ?? ''?></td>
                                </tr>
                                   

                                <tr>
                                    <td style="border: none;">4. Sex    </td>
                                    <td><?= $posting_detail->enrolement->gender ?? ''?></td>
                                </tr>
                                <tr>
                                    <td style="border: none;">5. Marital Status</td>
                                    <td><?= $posting_detail->enrolement->marital_status ?? ''?></td>
                                </tr>
                                <tr>
                                    <td style="border: none;">6. Address :</td>
                                    <td></td>
                                </tr>
                                 <tr>
                                    <td style="border: none;">  Temporary :</td>
                                    <td><?= $posting_detail->enrolement->present_address ?? ''?></td>
                                </tr>
                                 <tr>
                                    <td colspan="2" style="border-bottom:0px solid #444;">I hereby nominate the person(s)/Cancel the nomination made by me previously and nominate the person(s)                    
mentioned bellow to receive any amount due to me from the employer, in the event to my death.   </td>
                                 </tr>
                                <tr>
                                    <td colspan="2" style="border: none; ">
                                        <table style="width: 94%;margin: 3%;border:1px solid #444;">
                                            <tbody><tr>
                                                <td style="border:1px solid #444;">Name of the Nominee/Nominees</td>
                                                
                                            
                                                <td style=" border:1px solid #444;">Address</td>
                                                
                                                <td style=" border:1px solid #444;">Nominee's Relationship with the Member</td>
                                                
                                            
                                                <td style="border: 1px solid #444;">Date of Birth</td>
                                            
                                                <td style="border: 1px solid #444;">Total Amount of share of accumulations in credit to be paid to each Nominee</td>
                                                <td style="border: 1px solid #444;">If the nominee is minor,name,relationship and address of the guardian who may receive the amount during the minority of nominee</td>
                                            </tr>
                                            <tr>
                                                <td style="border:1px solid #444;"></td>
                                                <td style="border:1px solid #444;"></td>
                                                <td style="border:1px solid #444;"></td>
                                                <td style="border:1px solid #444;"></td>
                                                <td style="border:1px solid #444;">100%</td>
                                                <td style="border:1px solid #444;">NA</td>
                                            </tr>
                                        </tbody></table>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td colspan="2" style="border: none;">1.Certified that I have no family and should I acquire a family hereafter, the above nomination shall be deemed                   
    as cancelled.</td>
                                   
                                </tr>
                                <tr>
                                    <td colspan="2" width="100%" style="border: none;"> 2. *Certified that my father/mother is /are dependent me.</td>
                                   
                                </tr>
                                 <tr>
                                    <td colspan="2" width="100%" style="border: none;"> 3   *Strike out whichever is not applicable.</td>
                                   
                                </tr>
                                
                                <tr>
                                    <!-- <td style="border: none;float: right"></td> -->
                                    <td colspan="2" style="border: none;text-align: right;">Signature or the thumb impression
of the employed person
</td>
                                </tr>
                                <tr>
                                <td colspan="2" style="border: none;width:100%;text-align: center;">
                                <strong>CERTIFICATE BY EMPLOYER</strong>
                            
                            </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: none;width:100%;">Certified that the above declaration and nomination has been signed/thumb impressed before me by Shri/                 
Smt/Kum.  <?= $posting_detail->enrolement->adhar_name ?? ''?>  employed in my eastablishment after he/she has read         
the entry/entries have been read over to him/her by me and got confirmed by him/her.    </td>
                                </tr>
                                <tr>
                                    <td style="border: none;"></td>
                                    <td style="border: none;float: right">Signature of the employer or other authorised
officer of the establishment and 
Desination
</td>
                                </tr>
                                <tr>
                                    <td style="border: none;">Place :</td>
                                    <td style="border: none;"></td>
                                
                                </tr>
                                <tr>
                                    <td style="border: none;">Date :</td>
                                    <td style="border: none;"></td>
                                </tr>
                           <tr>
                             <td style="border: none;"></td>
                            <td style="border: none;float: right">Name and Address of the Factory/
Establishment and rubber stamp thereof</td></tr>

                            
                               
                            </tbody></table>
                        </div>
    <?php }
}else{
    echo 'No Record available';
}
?>



</div>