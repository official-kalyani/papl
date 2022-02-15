<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\enrollment */

$this->title = Yii::t('app', 'Employee Master Report');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee_export">

    <h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(['action' => ['enrollment/download_employee_data'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>
<div class="row">
  <div class="col-md-2">
    <div class="form-group field-client">
      <?= $form->field($model, 'state')->dropDownList($states, ['class' => 'form-control state','prompt' => 'Choose State...', 'onchange' => 'return getlocation(this.value)']) ?>
    </div>
  </div>

    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'location')->dropDownList([], ['class' => 'form-control location required', 'onchange' => 'return checkPlantdetails(this.value)']) ?>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'plant_id')->dropDownList([], ['class' => 'form-control plant', 'onchange' => 'return getPurchaseorderdetails(this.value)']) ?>
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
    <div class="col-md-2">
      <div class="form-group field-client">
        <div class="form-group field-enroll-start_date">
          <label class="control-label" for="enroll-start_date">Date</label>
          <input type="text" id="enroll-start_date" class="form-control" name="enroll[start_date]" value="<?php echo date('F Y',strtotime("first day of previous month")); ?>">
          <div class="help-block"></div>
        </div>       
      </div>
    </div>
    
    </div>
    <div class="row">
    <div class="col-md-2">
      <div class="form-group field-client">
      <button type="button" id="employee_data_report" class="float-left btn btn-success"><span class='fa fa-download' aria-hidden='true'> Download Employee Data Report</span></button>
      
      </div>
    </div>
    
  </div>
  
        </div>
  <?php ActiveForm::end(); ?>
  
  
  </div>
  
  <iframe id="iframeID" style="position: absolute;width:0;height:0;border:0;"></iframe>
  <script type="text/javascript">
    
    $(document).ready(function(){
      <?php 
        if ($model->state) {?>

          setTimeout(function() {
            $("#enrollment-state").val(<?=$model->state?>);
            getlocation(<?=$model->state?>);
          }, 300);
     <?php 
       }?>
    })
  </script>
  <?php
$js = <<<JS
 
     $( "#enroll-start_date" ).datepicker({
        dateFormat: 'MM yy',
        changeMonth: true,
        changeYear: true,
        defaultDate: '-1m',
        showButtonPanel: true, 
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('MM yy', new Date(year, month, 1)));

        }
    });
    $("#enroll-start_date").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });
   

JS;


$this->registerJs($js);
?>
<!-- Search location,plant -->
<script>
  function getlocation(id){
      $.ajax({url:"<?=Url::toRoute(['location/getlocationdetails'])?>?id="+id,
                success:function(results)
                { 
                    if(results)
                    {
                        $('#enrollment-location').html('<option value="">Choose Location...</option>');
                        var locations=JSON.parse(results);
                        $.each(locations,function(key,value){
                            $('#enrollment-location').append('<option value="'+key+'">'+value+'</option>');
                        });
                        <?php
                          if ($model->location != '') {
                          ?>
                            $("#enrollment-location").val(<?=$model->location?>);
                            checkPlantdetails(<?=$model->location?>);
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
                        $('#enrollment-plant_id').html('<option value="">Choose Plant...</option>');
                        var plants=JSON.parse(results);
                        $.each(plants,function(key,value){
                            $('#enrollment-plant_id').append('<option value="'+key+'">'+value+'</option>');
                        });
                        <?php
                          if ($model->plant_id != '') {
                          ?>
                            $("#enrollment-plant_id").val(<?=$model->plant_id?>);
                            getPurchaseorderdetails(<?=$model->plant_id?>);
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
                        $('#enrollment-purchase_orderid').html('<option value="">Choose PO...</option>');
                        var pos=JSON.parse(results);
                        $.each(pos,function(key,value){
                            //$('#salary-purchase_orderid').append('<option value="'+key+'">'+value+'</option>');
                            $("#enrollment-purchase_orderid").append('<option value="' +value.po_id + '" dataid="' + value.po_id + '">' + value.purchase_order_name + '</option>');
                        });
                        
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
                        $('#enrollment-section_id').html('<option value="">Choose Section...</option>');
                        var sections=JSON.parse(results);
                        $.each(sections,function(key,value){
                            $("#enrollment-section_id").append('<option value="' +value.section_id +  '" dataid="' + value.section_id + '">' + value.section_name + '</option>');
                        });
                        
                    }
                }
        });
  }
  

    //enrollment REport Download
  $("#employee_data_report").click(function(){
        var state_id=$("#enrollment-state").val();
        var loc_id=$("#enrollment-location").val();
        var plant_id=$("#enrollment-plant_id").val();
        var po_id=$("#enrollment-purchase_orderid").val();
        var section_id=$("#enrollment-section_id").val();
        var month_year=$("#enroll-start_date").val();
        if(!state_id){
          alert("Choose State !");
          return false;
        }
        if(loc_id==0){
          alert("Choose Location !");
          return false;
        }
        if(plant_id==0){
          alert("Choose Plant !");
          return false;
        }
        if(confirm("Do you want to Download Employee data Report!")){
          $.ajax({
            url: "<?=Url::to(['enrollment/download_employee_data'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year,
            
            success:function(results)
            { 
              if(results){
                $("#iframeID").attr('src', results);
                alert("enrollment Report Downloaded Successfuly");
              }else{
                alert("No Employee found");
              }
              
            }
          });
        }else{
          return false;
        }
        
        return false;  
      });
  
</script>