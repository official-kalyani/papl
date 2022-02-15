<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Attendance */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Salary Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Salaries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// echo 'Updated By:'.$updated_details_name->username;

?>
<span class="badge badge-pill badge-info float-right">Last updated by : <?= $updated_details_name->username?></span>
<style>
  .form-control{
        font-size: 12px !important;  
    }

    .tableFixHead          { overflow: auto; height: 450px; width: 100%; }
    .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }
    
    .tableFixHead tbody th { position: sticky; left: 0;}
    
    .tableFixHead thead th:first-child,
    .tableFixHead tbody th:first-child,
    .tableFixHead tbody td:first-child {
                            position: -webkit-sticky;
                            position: sticky;
                            width:150px;
                            left: 0;
                            z-index: 2;
                            background:#eee;
                            
                            }
    .tableFixHead thead th:first-child {
                            z-index: 4;
                        }
    .tableFixHead thead th:nth-child(2),
    .tableFixHead tbody th:nth-child(2),
    .tableFixHead tbody td:nth-child(2) {
                            position: -webkit-sticky;
                            position: sticky;
                            width: 150px;
                            left: 117px;
                            z-index: 2;
                            background:#eee;
                            }

    .tableFixHead thead th:nth-child(2) {
                            z-index: 4;
    }


    .tableFixHead thead th:nth-child(3),
    .tableFixHead tbody th:nth-child(3), 
    .tableFixHead tbody td:nth-child(3){
                            position: -webkit-sticky;
                            position: sticky;
                            left: 285px;
                            z-index: 2;
                            background:#eee;
                            }

    .tableFixHead thead th:nth-child(3) {
                            z-index: 4;
                            }

    /* Just common table stuff. Really. */
    .tableFixHead table  { border-collapse: collapse; width: 100%; }
    .tableFixHead th, td { padding: 8px 16px; white-space: nowrap; }
    .tableFixHead th     { background:#eee; }
</style>
<div class="salary-attribute-form">
<h1><?= Html::encode($this->title) ?></h1>
  
  <?php $form = ActiveForm::begin(['action' => ['salary/salary_update'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>



<div class="row sticky-top">
    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'state')->dropDownList($states, ['class' => 'form-control state','prompt' => 'Choose State...','required'=>true, 'onchange' => 'return getlocation(this.value)']) ?>
      </div>
    </div>

    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'location')->dropDownList([], ['class' => 'form-control location','required'=>true, 'onchange' => 'return checkPlantdetails(this.value)']) ?>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'plant_id')->dropDownList([], ['class' => 'form-control plant','required'=>true, 'onchange' => 'return getPurchaseorderdetails(this.value)']) ?>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'purchase_orderid')->dropDownList([], ['class' => 'form-control purchaseorder', 'onchange' => 'return getSectiondetails(this.value)']) ?>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'section_id')->dropDownList([], ['class' => 'form-control section']) ?>
      </div>
    </div>
    <div class="col-md-2" style="margin-top: auto;">
      <div class="form-group field-client">
          <label></label>
          <?= Html::submitButton("Search", ['class' => "btn btn-info", 'name' => 'search', 'value' => 'search']); ?>
        </div>
    </div>
    
</div>
  <?php ActiveForm::end(); ?>
  <div style="font-size: 12px;" class="tableFixHead">    
    <?php 
      if ($search_result == 1) {
        ?>
        <p class="h6">Attendance for <?php echo $state_short_code->short_code.'/'.$location_short_code->short_code.'/'.$plant_short_code->short_code;?></p>
     <?php  }
    ?>
    <table class="table table-bordered">
       <tr>
      <thead>
          <th >Employee ID</th>
          <th >Employee Name</th>
          <th >Designation</th>
          <?php
            if ($salaryattr) {
            foreach ($salaryattr as $key => $value) {?>
              <th ><?=$value->attribute_name?></th>
            <?php
          }
          }
          ?>
          <?php 
            if ($deduction_attr) {
              foreach ($deduction_attr as $key => $value) {
                
                // $deduction_data = $deduction_mapping_model->deduction_data($value->id,$url_papl_id);
                 if(strtoupper($value->attribute_name) == 'PT' ) {
                 // if(strtoupper($value->attribute_name) == 'PF' || strtoupper($value->attribute_name) == 'ESI' ||strtoupper($value->attribute_name) == 'PT' ) {
                       
                ?>
                <th  style="display:none;"><?=$value->attribute_name?></th>
                <?php
                }else{ ?>
                  <th  ><?=$value->attribute_name?></th>
                 <?php
               }
                
              }
            }
             ?>
       
      </thead>
       </tr>
       <tr>
      <tbody>
        <?php 
        if ($employeelists) {
         
          foreach ($employeelists as $employeelist) {
            $exit_status = $employeelist->employee->is_exit ?? 0;
            if ($exit_status == 0) {
             
           
            ?>
            <tr>
              <td><?= $employeelist->papl_id?></td>
              <td><?= $employeelist->enrolement->adhar_name ?? ''?></td>
              <td><?= $employeelist->enrolement->PAPLdesignation ?? ''?></td>
              <?php
            if ($salaryattr) {
            foreach ($salaryattr as $key => $value) {
              $sal_data = $salary_mapping_model->salary_data($value->id,$employeelist->papl_id);
              
              ?>
              <td>
                <input type="text" name="salary_value[]" value="<?= isset($sal_data['amount'])?$sal_data['amount']:0?>" onkeypress="return isNumberKey(event)" onblur="insert(this.value,'<?= $employeelist->papl_id?>',<?=  $value->id?>,'salary_value')">
                <input type="hidden" name="salary_column_name[]" value="<?= $value->attribute_name ?>" class="form-control" >
                <input type="hidden" name="salary_id[]" value="<?= $value->id ?>" class="form-control" >
              </td>
            <?php
          }
          }?>
          <?php
            if ($deduction_attr) {
            foreach ($deduction_attr as $key => $value) {
              $deduction_data = $deduction_mapping_model->deduction_data($value->id,$employeelist->papl_id);
              if(strtoupper($value->attribute_name) == 'PT' ) {
              // (strtoupper($value->attribute_name) == 'PF' || strtoupper($value->attribute_name) == 'ESI' ||strtoupper($value->attribute_name) == 'PT' 
              ?>
              <td style="display:none;">
                <input type="text" name="deduction_value[]" value="<?php 
                // if(strtoupper($value->attribute_name) == 'PF') {echo '12';}
                // elseif(strtoupper($value->attribute_name) == 'ESI') {echo '0.75';}
                if(strtoupper($value->attribute_name) == 'PT') {echo ' ';}
              ?>" onkeypress="return isNumberKey(event)" onblur="insert(this.value,'<?= $employeelist->papl_id?>',<?=  $value->id?>,'deduction_value')">
                 <input type="hidden" name="deduction_column_name[]" value="<?= $value->attribute_name ?>" class="form-control" >
                 <input type="hidden" name="deduction_id[]" value="<?= $value->id ?>" class="form-control" >

              </td>
            <?php
          }else{?>
             <td >
                <input type="text" name="deduction_value[]" value="<?= isset($deduction_data['amount'])?$deduction_data['amount']:0?>" onkeypress="return isNumberKey(event)" onblur="insert(this.value,'<?= $employeelist->papl_id?>',<?=  $value->id?>,'deduction_value')">
                 <input type="hidden" name="deduction_column_name[]" value="<?= $value->attribute_name ?>" class="form-control" >
                 <input type="hidden" name="deduction_id[]" value="<?= $value->id ?>" class="form-control" >

              </td>
          <?php
        }
          }
          }?>
            </tr>
          <?php 
        
        ?>
        
      <?php 

            }

    }
    }
          ?>

      </tbody>
      </tr>
    </table>
  </div>

  

</div>

<script type="text/javascript">
$(document).ready(function(){
      <?php 
        if ($search_state) {
       

          ?>

          setTimeout(function() {
            $("#salary-state").val(<?=$search_state?>);
            getlocation(<?=$search_state?>);
          }, 300);
     <?php 
       }?>
    })
  <!-- Search location,plant,purchaseorderid,section -->
  function getlocation(id){
      $.ajax({url:"<?=Url::toRoute(['location/getlocationdetails'])?>?id="+id,
                success:function(results)
                { 
                    if(results)
                    {
                        $('#salary-location').html('<option value="">Choose Location...</option>');
                        var locations=JSON.parse(results);
                        $.each(locations,function(key,value){
                            $('#salary-location').append('<option value="'+key+'">'+value+'</option>');
                        });
                        <?php
                          if ($search_location != '') {
                          ?>
                            $("#salary-location").val(<?=$search_location?>);
                            checkPlantdetails(<?=$search_location?>);
                          <?php
                          }
                          ?> 
                        
                    }
                }
        });
  }

  function checkPlantdetails(id){
      $.ajax({url:"<?=Url::toRoute(['plant/getplantdetails'])?>?id="+id,
                success:function(results)
                { 
                    if(results)
                    {
                        $('#salary-plant_id').html('<option value="">Choose Plant...</option>');
                        var plants=JSON.parse(results);
                        $.each(plants,function(key,value){
                            $('#salary-plant_id').append('<option value="'+key+'">'+value+'</option>');
                        });
                        <?php
                          if ($search_plant != '') {
                          ?>
                           $("#salary-plant_id").val(<?=$search_plant?>);
                            var plant_id = $('#salary-plant_id option:selected').val();
                            getPurchaseorderdetails(plant_id);
                           
                          <?php
                          }
                          ?> 
                        
                    }
                }
        });
  }
  function getPurchaseorderdetails(id){
      $.ajax({url:"<?=Url::toRoute(['purchaseorder/getpurchaseorderdetails'])?>?id="+id,
                success:function(results)
                { 
                    if(results)
                    {
                        $('#salary-purchase_orderid').html('<option value="">Choose PO...</option>');
                        var pos=JSON.parse(results);
                        $.each(pos,function(key,value){
                            //$('#salary-purchase_orderid').append('<option value="'+key+'">'+value+'</option>');
                            $("#salary-purchase_orderid").append('<option value="' +value.po_id + '" dataid="' + value.po_id + '">' + value.purchase_order_name + '</option>');
                        });
                        <?php
                         
                        if ($search_plant != '') {
                        ?>
                             $("#salary-purchase_orderid").val(<?=$search_po?>);
                             var po_id = $('#salary-purchase_orderid option:selected').val();
                             getSectiondetails(po_id);
                        <?php
                        }
                        ?> 
                        
                    }
                }
        });
  }

  function getSectiondetails(id){
      $.ajax({url:"<?=Url::toRoute(['section/getsectiondetails'])?>?id="+id,
                success:function(results)
                { 
                    if(results)
                    {
                        $('#salary-section_id').html('<option value="">Choose Section...</option>');
                        var sections=JSON.parse(results);
                        $.each(sections,function(key,value){
                            $("#salary-section_id").append('<option value="' +value.section_id +  '" dataid="' + value.section_id + '">' + value.section_name + '</option>');
                        });
                        <?php
                          
                          if ($search_plant != '') {
                        ?>
                            $("#salary-section_id").val(<?=$search_section?>);
                        <?php
                        }
                        ?> 
                    }
                }
        });
  }
  
  function insert(value,empid,attid,attname){
           
            $.ajax({
              
              url: "<?=Url::to(['salary/getsalary_attr_update'])?>",
              // url: "<?=Url::to(['salary/getsalary_attr_update'])?>?empid="+empid+"&attval="+value+"&attid="+attid+"&attname="+attname,
              type:'post',
              data:{empid:empid,attval:value,attid:attid,attname:attname},
              success: function(results) {
                
              }
            });
          }
  function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
</script>
