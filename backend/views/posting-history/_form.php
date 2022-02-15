<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\PostingHistory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="posting-history-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <?php 
            // echo '<pre>';print_r($locations);
            // echo $posting_list['plant_id'];die();
        ?>
        <div class="col-sm-3">
            <div class="form-group field-postinghistory-location_id has-success">
                <label class="control-label" for="postinghistory-location_id">Location </label>
                <select id="postinghistory-plant_id" class="form-control location" name="PostingHistory[location_id]" required="" onchange="return checkPlantdetails(this.value)" aria-invalid="false">
                    <option value="">Choose Location</option>
                    <?php

                    foreach ($locations as $key => $value) {?>

                      <option value="<?=$key?>" <?php 
                      if($url_papl_id){
                        if($key == $posting_list['location_id']) {
                            echo 'selected';
                        }
                    }else{
                      if($key == $location_id_po) {echo 'selected';}  
                  }
              ?>>
              <?= $value?>

          </option>
      <?php  }
      ?>
  </select>
  <div class="help-block"></div>
</div>
</div>
<div class="col-sm-3">
    <?php 
            // if($url_papl_id){

    ?>
    <div class="form-group field-postinghistory-plant_id has-success">
        <label class="control-label" for="postinghistory-plant_id">Plant </label>
        <select id="postinghistory-plant_id" class="form-control plant" name="PostingHistory[plant_id]" required="true" onchange="return getPurchaseorderdetails(this.value)" aria-invalid="false">
            

        </select>
        <div class="help-block"></div>
    </div>


    <?php 
    // }

    ?>


</div>

<div class="col-sm-3">

    <?= $form->field($model, 'purchase_orderid')->dropDownList([],['prompt' => 'Choose Purchase order...','value' => '','onchange' => 'return getSectiondetails(this.value)','class' => 'form-control purchaseorder','required'=>true]);?>
    <!-- <?= $form->field($model, 'purchase_orderid')->dropDownList($purchase_order,['prompt' => 'Choose Purchase order...','value' => isset($_GET['enrolementid']) ? $purchase_order : $posting_list['purchase_orderid'],'required'=>true,'onchange' => 'return getSectiondetails(this.value)','class' => 'form-control purchaseorder']);?> -->

</div>
<div class="col-sm-3">
    <?= $form->field($model, 'section_id')->dropDownList([], ['prompt'=>'Select Section','class' => 'form-control section','required'=>true]) ?>
</div>


        <!-- <div class="col-sm-3">
            <div class="form-group field-postinghistory-purchase_orderid has-success">
                <label class="control-label" for="postinghistory-purchase_orderid">Purchase Orderid</label>
                <select id="postinghistory-purchase_orderid" class="form-control" name="PostingHistory[purchase_orderid]" required="" onchange="return checkSection(this.value)" aria-invalid="false">
                <option value="">Choose Purchase order...</option>
                <option value="1" selected="">purchaseorder1</option>
                </select>

                <div class="help-block"></div>
            </div>
        </div> -->
    </div>

    <div class="row"> 

        <div class="col-sm-3">
            <?= $form->field($emp_model, 'workman_sl_no')->textInput(['value' => $emp_list->workman_sl_no ?? '','required'=>true]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($emp_model, 'gate_pass')->textInput(['value' => $emp_list->gate_pass ?? '','required'=>true]) ?>
        </div>       
        <div class="col-sm-3">
            <?= $form->field($emp_model, 'gate_pass_validity')->textInput(['value' => $emp_list->gate_pass_validity ?? '','readonly'=>true,'required'=>true]) ?>
        </div>
        
        <div class="col-sm-3">
            <?php if (!$url_papl_id) {?>
                <?= $form->field($model, 'start_date')->textInput(['maxlength' => true,'value' => '','readonly'=>true,'required'=>true]) ?>

            <?php }else{?>
                <?= $form->field($model, 'start_date')->textInput(['maxlength' => true,'value' => $posting_list['start_date'] ?? '','required'=>true]) ?>
            <?php }?>
            <input type="hidden" name="previous_date" id="previous_date" value="<?= $posting_list['start_date'] ?? ''?>">
        </div>

    </div>
    <div class="row">
        <div class="col-12">
          <h1 class="heading"> Earnings </h1>
      </div>
  </div>
  <div class="row">    
    <?php 
    if ($salaryattr) {
        foreach ($salaryattr as $key => $value) {
            if ($url_papl_id) {
                $sal_data = $salary_mapping_model->salary_data($value->id,$url_papl_id);
            }

            ?>

            <div class="col-sm-3">
                <label><?= $value->attribute_name ?></label>
                <input type="text" name="salary_value[]" class="form-control salary_value" value="<?= isset($sal_data['amount'])?$sal_data['amount']:0?>" onkeypress="return isNumberKey(event)">
                <input type="hidden" name="salary_column_name[]" value="<?= $value->attribute_name ?>" class="form-control" >
                <input type="hidden" name="salary_id[]" value="<?= $value->id ?>" class="form-control" >
            </div>

        <?php }
    }
    ?>
</div>
<div class="row">
    <div class="col-12">
      <h1 class="heading"> Deductions </h1>
  </div>
</div>
<div class="row">    
    <?php 
    if ($deduction_attr) {
        foreach ($deduction_attr as $key => $value) {
            if ($url_papl_id) {
                $deduction_data = $deduction_mapping_model->deduction_data($value->id,$url_papl_id);
            }
            ?>


            <?php if(strtoupper($value->attribute_name) == 'PT' ) {
                ?>
                <div class="col-sm-3" style="display: none;">
                    <label><?= $value->attribute_name ?></label>
                    <input type="text" name="deduction_value[]" value="<?php  
                    // if(strtoupper($value->attribute_name) == 'PF'){
                    //     echo '12';                    }
                    //     elseif(strtoupper($value->attribute_name) == 'ESI'){echo '0.75';}
                        if(strtoupper($value->attribute_name) == 'PT'){echo '';}
                    ?>" class="form-control deduction_value" onkeypress="return isNumberKey(event)">
                    <input type="hidden" name="deduction_column_name[]" value="<?= $value->attribute_name ?>" class="form-control" >
                    <input type="hidden" name="deduction_id[]" value="<?= $value->id ?>" class="form-control" >
                </div>


                <?php
            }elseif(strtoupper($value->attribute_name) == 'PF' || strtoupper($value->attribute_name) == 'ESI'){
                ?>
                <div class="col-sm-3">
                    <label><?= $value->attribute_name ?></label>
                    <input type="text" class="form-control deduction_value" name="deduction_value[]" value="<?php   if(strtoupper($value->attribute_name) == 'PF'){
                        echo '12';                    }
                        elseif(strtoupper($value->attribute_name) == 'ESI'){echo '0.75';}?>" onkeypress="return isNumberKey(event)">
                    <input type="hidden" name="deduction_column_name[]" value="<?= $value->attribute_name ?>" class="form-control" >
                    <input type="hidden" name="deduction_id[]" value="<?= $value->id ?>" class="form-control" >
                </div>
                <?php
            }else{
                ?>
                <div class="col-sm-3">
                    <label><?= $value->attribute_name ?></label>
                    <input type="text" name="deduction_value[]" value="<?= isset($deduction_data['amount'])?$deduction_data['amount']:0?>" class="form-control deduction_value" onkeypress="return isNumberKey(event)">
                    <input type="hidden" name="deduction_column_name[]" value="<?= $value->attribute_name ?>" class="form-control" >
                    <input type="hidden" name="deduction_id[]" value="<?= $value->id ?>" class="form-control" >
                </div>
                <?php
            }
            ?>


            <!-- <input type="hidden" name="dedu<?= $value->id ?>" class="form-control" data-id="<?= $value->id ?>"> -->


        <?php }
    }
    ?>
</div>
<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>
<!-- old code for displaying plant -->

    <!-- old code for displaying plant -->

<script>
    <?php 
        if ($role != 1 && $role != 5) {
    ?>
    setTimeout(function() {
                  $(".plant").val(<?=$user_plant_id->plant_id?>);
                  checkPlantdetails(<?=$user_plant_id->location_id;?>);
              }, 1000);
     <?php    }else{
        if ($url_papl_id) {
        ?>
        
        setTimeout(function() {
              $(".plant").val(<?=$posting_list['plant_id'];?>);
              checkPlantdetails(<?=$posting_list['location_id'];?>);
          }, 1000);
    <?php
}else{?>
    setTimeout(function() {
              $(".plant").val(<?=$plant_id_po?>);
              checkPlantdetails(<?=$location_id_po;?>);
          }, 1000);
<?php } 
}
    ?>
</script>
<script type="text/javascript">
    function checkSection(id){
        var id = id;       
        $.ajax({
            type:'post',
            url: "<?= Yii::$app->urlManager->createUrl('posting-history/getsection'); ?>",
            data:{po_id:id},
            success:function(data){                
                $('.section').html(data);
                
            }
        });

    }
    
</script>

<script type="text/javascript">
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    function checkPlantdetails(id) {
        var key = $('.location option:selected').attr('dataid');
        $.ajax({
            type: 'post',
            url: "<?=Url::to(['plant/getplantdetails'])?>?id="+id,
            data: {
                id: id,
            },
            success: function(data) { 
                $('.plant').html('<option value="">Select Plant</option>');
                var result = JSON.parse(data);
                $.each(result, function (index, value) {
                    $(".plant").append('<option value="' + index +'" dataid="' + index + '">' + value + '</option>');
                });
                <?php
                if ($role != 1 && $role != 5) {
                ?>
                setTimeout(function() {
                    $(".plant").val(<?=$user_plant_id->plant_id?>);
                    var plant_id = $('.plant option:selected').val();
                        getPurchaseorderdetails(plant_id);
                  }, 302);
                <?php
                    }else{
                if ($url_papl_id) {
                    ?>
                    setTimeout(function() {
                      $(".plant").val(<?=$posting_list['plant_id'];?>);
                      var plant_id = $('.plant option:selected').val();
                      getPurchaseorderdetails(plant_id);
                  }, 302);
               <?php 
           }else{ ?>
            setTimeout(function() {
                      $(".plant").val(<?=$plant_id_po;?>);
                      var plant_id = $('.plant option:selected').val();
                      getPurchaseorderdetails(plant_id);
                  }, 302);
          <?php 
           }
            }
                    ?>
                }
            });
    }
   function getPurchaseorderdetails(id) {
        var key = $('.plant option:selected').attr('dataid');
        $.ajax({
          type: 'post',
          url: "<?=Url::to(['purchaseorder/getpurchaseorderdetails'])?>?id="+id,
          data: {
            id: id
          },
          success: function(data) {
            if(data){
              $('.purchaseorder').empty; 
              $('.purchaseorder').html('<option value="">Select PO</option>');
                var result = JSON.parse(data);
                $.each(result, function (index, value) {
                $(".purchaseorder").append('<option value="' +value.po_id + '" dataid="' + value.po_id + '">' + value.purchase_order_name + '</option>');
                // $(".purchaseorder").append('<option value="' + value.purchase_order_name +','+value.po_id + '" dataid="' + value.po_id + '">' + value.purchase_order_name + '</option>');
                               
              }); 
                <?php 
                    if ($role != 1 && $role != 5) {?>

                   setTimeout(function() {
                    $(".purchaseorder").val(<?=$user_plant_id->purchase_orderid?>);
                    var po_id = $('.purchaseorder option:selected').val();
                        getSectiondetails(po_id);
                  }, 303);
                
                   <?php
                    }else{
                         if ($url_papl_id) {?>
                            setTimeout(function() {
                              $(".purchaseorder").val(<?=$posting_list['purchase_orderid'];?>);
                              var po_id = $('.purchaseorder option:selected').val();
                              getSectiondetails(po_id);
                          }, 303);
                        <?php  }else{ ?>
                           setTimeout(function() {
                              $(".purchaseorder").val(<?=$purchase_id_po;?>);
                              var po_id = $('.purchaseorder option:selected').val();
                              getSectiondetails(po_id);
                          }, 303); 
                       <?php }

                    }
                      
                
                ?>
                    }
                 
                
            }
            

          
        });
    }
  function getSectiondetails(id) {
        var key = $('.purchaseorder option:selected').attr('dataid');
        $.ajax({
          type: 'post',
          url: "<?=Url::to(['section/getsectiondetails'])?>?id="+id,
          data: {
            id: id
          },
          success: function(data) {
            if (data) {
            $('.section').html('<option value="">Select Section</option>');
            var result = JSON.parse(data);
            $.each(result, function (index, value) {
                $(".section").append('<option value="' +value.section_id +  '" dataid="' + value.section_id + '">' + value.section_name + '</option>');
                               
           }); 
             <?php 
                    if ($role != 1 && $role != 5) {?>

                   setTimeout(function() {
                    $(".section").val(<?=$user_plant_id->section_id?>);
                    
                  }, 304);
                
                   <?php
                    }else{
                        if ($url_papl_id) {
                            ?>
                            setTimeout(function() {
                    $(".section").val(<?=$posting_list['section_id']?>);
                    
                  }, 304);
                        <?php }else{ ?>
                             setTimeout(function() {
                    $(".section").val(<?=$sec_id_po?>);
                    
                  }, 304);
                       <?php }
                    }
                
                ?>
                
            }
          }
        });
    }
</script>
<script type="text/javascript">
    var selectedDate = $('#previous_date').val();
    $( "#postinghistory-start_date" ).datepicker({
     showButtonPanel: true,
     dateFormat: "dd-mm-yy",
     changeMonth:true,
     changeYear:true,
     minDate:selectedDate
 }).val();
    $( "#employee-gate_pass_validity" ).datepicker({
   showButtonPanel: true,
   dateFormat: "dd-mm-yy",
   changeMonth:true,
   changeYear:true,        
   }).val();
</script>