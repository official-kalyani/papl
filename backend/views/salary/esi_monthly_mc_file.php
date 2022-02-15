<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\PostingHistory */

$this->title = Yii::t('app', 'ESI - MONTHLY MC FILE');
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    

    .tableFixHead          { overflow: auto; height: 450px; width: 100%; }
    .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }
    
    .tableFixHead tbody th { position: sticky; left: 0;}
    
    .tableFixHead thead th:first-child,
    .tableFixHead tbody th:first-child {
                            position: -webkit-sticky;
                            position: sticky;
                            width:150px;
                            left: 0;
                            z-index: 2;
                            
                            }
    .tableFixHead thead th:first-child {
                            z-index: 4;
                        }
    .tableFixHead thead th:nth-child(2),
    .tableFixHead tbody th:nth-child(2) {
                            position: -webkit-sticky;
                            position: sticky;
                            width: 150px;
                            left: 130px;
                            z-index: 2;
                            }

    .tableFixHead thead th:nth-child(2) {
                            z-index: 4;
    }


    .tableFixHead thead th:nth-child(3),
    .tableFixHead tbody th:nth-child(3) {
                            position: -webkit-sticky;
                            position: sticky;
                            left: 320px;
                            z-index: 2;
                            }

    .tableFixHead thead th:nth-child(3) {
                            z-index: 4;
                            }

    /* Just common table stuff. Really. */
    .tableFixHead table  { border-collapse: collapse; width: 100%; }
    .tableFixHead th, td { padding: 8px 16px; white-space: nowrap; }
    .tableFixHead th     { background:#eee; }
      
</style>
<div class="posting-report">

    <h1><?= Html::encode($this->title) ?></h1>
    <!-- 'action' => ['salary/esi_monthly_mc_file'], -->

<?php $form = ActiveForm::begin([ 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform','id'=>'incement_report_form']]); ?>
<div class="row">
  <div class="col-md-2">
    <div class="form-group field-client">
      <?= $form->field($model, 'state_id')->dropDownList($states, ['class' => 'form-control state','prompt' => 'Choose State...', 'onchange' => 'return getlocation(this.value)','required'=>true]) ?>
    </div>
  </div>

    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'location_id')->dropDownList([], ['class' => 'form-control location', 'onchange' => 'return checkPlantdetails(this.value)','required'=>true]) ?>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'plant_id')->dropDownList([], ['class' => 'form-control plant', 'onchange' => 'return getPurchaseorderdetails(this.value)','required'=>true]) ?>
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
        <div class="form-group field-postinghistory-start_date">
          <label class="control-label" for="postinghistory-start_date">Date</label>
          <input type="text" id="postinghistory-start_date" class="form-control" name="PostingHistory[start_date]" value="<?php echo date('F Y',strtotime("first day of previous month")); ?>" readonly='true'>
          <div class="help-block"></div>
        </div>       
      </div>
    </div>
    </div>
    
    <div class="row">
    <div class="col-md-2">
      <div class="form-group field-client">
      <button type="button" id="esi_monthly_mc" class="float-left btn btn-success"><span class='fa fa-download' aria-hidden='true'> Download ESI  Monthly Report</span></button>
      
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
            $("#postinghistory-state_id").val(<?=$model->state?>);
            getlocation(<?=$model->state?>);
          }, 300);
     <?php 
       }?>
    })
  </script>
  <?php
$js = <<<JS
 
     $( "#postinghistory-start_date" ).datepicker({
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
    $("#postinghistory-start_date").focus(function () {
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
  <script type="text/javascript">
   
  
  </script>

<!-- Search location,plant,purchaseorderid,section -->
<script>
  function getlocation(id){
      $.ajax({url:"<?=Url::toRoute(['location/getlocationdetails'])?>?id="+id,
                success:function(results)
                { 
                    if(results)
                    {
                        $('#postinghistory-location_id').html('<option value="">Choose Location...</option>');
                        var locations=JSON.parse(results);
                        $.each(locations,function(key,value){
                            $('#postinghistory-location_id').append('<option value="'+key+'">'+value+'</option>');
                        });
                        <?php
                          if ($model->location_id != '') {
                          ?>
                            $("#postinghistory-location_id").val(<?=$model->location_id?>);
                            checkPlantdetails(<?=$model->location_id?>);
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
                        $('#postinghistory-plant_id').html('<option value="">Choose Plant...</option>');
                        var plants=JSON.parse(results);
                        $.each(plants,function(key,value){
                            $('#postinghistory-plant_id').append('<option value="'+key+'">'+value+'</option>');
                        });
                        <?php
                          if ($model->plant_id != '') {
                          ?>
                            $("#postinghistory-plant_id").val(<?=$model->plant_id?>);
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
                        $('#postinghistory-purchase_orderid').html('<option value="">Choose PO...</option>');
                        var pos=JSON.parse(results);
                        $.each(pos,function(key,value){
                            //$('#salary-purchase_orderid').append('<option value="'+key+'">'+value+'</option>');
                            $("#postinghistory-purchase_orderid").append('<option value="' +value.po_id + '" dataid="' + value.po_id + '">' + value.purchase_order_name + '</option>');
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
                        $('#postinghistory-section_id').html('<option value="">Choose Section...</option>');
                        var sections=JSON.parse(results);
                        $.each(sections,function(key,value){
                            $("#postinghistory-section_id").append('<option value="' +value.section_id +  '" dataid="' + value.section_id + '">' + value.section_name + '</option>');
                        });
                        
                    }
                }
        });
  }
  

    //esi_monthly_mc
  $("#esi_monthly_mc").click(function(){
        var state_id=$("#postinghistory-state_id").val();
        var loc_id=$("#postinghistory-location_id").val();
        var plant_id=$("#postinghistory-plant_id").val();
        var po_id=$("#postinghistory-purchase_orderid").val();
        var section_id=$("#postinghistory-section_id").val();
        var month_year=$("#postinghistory-start_date").val();
        //alert(month_year);
        
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
          
        
        
        if(confirm("Do you want to Download ESI Monthly Report!")){
          $.ajax({
            type: "post",
            url: "<?= Yii::$app->urlManager->createUrl('salary/getdownload_esi_monthly_mc_file'); ?>",
            data:{
                "plant_id": plant_id,
                'po_id': po_id,
                'section_id':section_id,
                'loc_id':loc_id,
                'state_id':state_id,
                'date':month_year,
            
            },
            success:function(results)
            { 
              console.log(results);
              if(results){
                $("#iframeID").attr('src', results);
                alert("Report Downloaded Successfuly");
              }else{
                alert("No Employee found");
              }
              
            }
          });
        }else{
          return false;
        }
        
        
      });

        
  
  
</script>