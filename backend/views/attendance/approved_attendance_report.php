<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\controllers\CalculationController;

/* @var $this yii\web\View */
/* @var $model common\models\Attendance */

$this->title = Yii::t('app', 'Attendance Report');
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
<div class="attendance-report">

    <h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(['action' => ['attendance/approved_attendance'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>
<div class="row">
  <div class="col-md-2">
    <div class="form-group field-client">
      <?= $form->field($model, 'state')->dropDownList($states, ['class' => 'form-control state','prompt' => 'Choose State...', 'onchange' => 'return getlocation(this.value)','required'=>true]) ?>
    </div>
  </div>

    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'location')->dropDownList([], ['class' => 'form-control location', 'onchange' => 'return checkPlantdetails(this.value)','required'=>true]) ?>
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
        <div class="form-group field-attendance-start_date">
          <label class="control-label" for="attendance-start_date">Date</label>
          <input type="text" id="attendance-start_date" class="form-control" name="Attendance[start_date]" value="<?php echo $model->start_date ?>" readonly='true'>
          <div class="help-block"></div>
        </div>       
      </div>
    </div>
    
    
    </div>
    <div class="row">
    <div class="col-md-10">
      <div class="form-group field-client">
      <button type="button" id="attendance_report" class="float-left btn btn-success"><span class='fa fa-download' aria-hidden='true'> Download Attendance Report</span></button>
      
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
      <label></label>
      <?= Html::submitButton("<span class='fa fa-search' aria-hidden='true'>View Attendance Report</span>", ['class' => "float-left btn btn-info", 'name' => 'view', 'value' => 'view']); ?>
      
      </div>
    </div>
    
  </div>
  
        </div>
  <?php ActiveForm::end(); ?>
  
  
  </div>
  <?php if($attendance_list){ ?>
  <div class="tableFixHead">
     <?php 
      if ($search_result == 1) {
        ?>
        <p class="h6">Attendance for <?php echo $state_short_code->short_code.'/'.$location_short_code->short_code.'/'.$plant_short_code->short_code;?></p>
     <?php  }
    ?>
    
    <h2>Attendance for <?= $model->start_date ?></h2>
    <table class="table table-bordered " style="text-align: center;">
      <thead>
        <tr>
          <th scope="col" rowspan=2>Employee ID</th>
          <th scope="col" rowspan=2>Employee Name</th>
          <th scope="col" rowspan=2>Designation</th>
          <?php 
            $days= (date('t', strtotime($model->start_date)));
            for ($l=1; $l <= $days; $l++) { 
          ?>
          <th scope="col"  colspan="7" style="border-right: 3px solid #adb5bd;"> <?php  echo date('d-M-y', strtotime($l.' '.$model->start_date)); ?></th>
           <?php  }
          ?>
          <th scope="col"  colspan="13" > SUMMARY</th>
        </tr>
        
        <tr>
            <?php 
              for ($k=1; $k <= $days; $k++) { 
                echo '<th>Att</th>';
                echo '<th>Att_Ty</th>';
                echo '<th>NH</th>';
                echo '<th>FH</th>';
                echo '<th>NH_FH_Ty</th>';
                echo '<th>OT</th>';
                echo '<th style="border-right: 3px solid #adb5bd;">OT_Ty</th>';
              }
        ?>
           <th>Att</th>
           <th>Ext_Att</th>
           <th>Leave</th>
           <th>Coff</th>
           <th>NH/FH</th>
           <th>OT BD</th>
           <th>OT GS</th>
           <th>OT GD</th>
           <th>OT BS</th>
           <th>Weekly Off</th>
           <th>Tot. Pay Days</th>
           <th>Total OT</th>
        </tr>
        </thead>
      <tbody>
      <?php
         foreach ($attendance_list as $key => $value) {
          $enrol=$model->getEnrolementDetail($key);
        ?>
        <tr>
          <th><?=$key?></th>
          <th><?= $enrol->adhar_name;?></th>
          <th><?= $enrol->PAPLdesignation;?></th>
          <?php  
          for ($k=1; $k <= $days; $k++) {
            $att='-';
            $att_type='-';
            $nh='-';
            $fh='-';
            $nhfh_type='-';
            $ot='-';
            $ot_type='-';

            $day_name= (date('Y-m-d', strtotime($k.$model->start_date)));
            foreach ($value as $value1) {
              if($value1['date']==$day_name){
                 $att = $value1['att']?$value1['att']:'-';
                 $att_type = $value1['att_type'] ?$value1['att_type']:'-';
                 $nh = $value1['nh'] ?$value1['nh']:'-';
                 $fh = $value1['fh'] ?$value1['fh']:'-';
                 $nhfh_type = $value1['nhfh_type'] ? $value1['nhfh_type'] : '-';
                 $ot = $value1['ot'] ? $value1['ot'] : '-';
                 $ot_type = $value1['ot_type'] ? $value1['ot_type']: '-';
                 
              }
            }
            
             ?>
          
          <td><?= $att?></td>
          <td><?= $att_type?></td>
          <td><?= $nh?></td>
          <td><?= $fh?></td>
          <td><?= $nhfh_type?></td>
          <td><?= $ot?></td>
          <td style="border-right: 3px solid #adb5bd;"><?= $ot_type?></td>
          <?php }
          $work_done=CalculationController::actionWorkdays($key,$model->start_date); 
            //print_r($work_done);
          ?>
          <td><?= $work_done['working_days']?></td>
          <td><?= $work_done['extra_att']?></td>
          <td><?= $work_done['leave']?></td>
          <td><?= $work_done['coff']?></td>
          <td><?= $work_done['nh_fh']?></td>
          <td><?= $work_done['ot_bd']?></td>
          <td><?= $work_done['ot_gs']?></td>
          <td><?= $work_done['ot_gd']?></td>
          <td><?= $work_done['ot_bs']?></td>
          <td><?= $work_done['weekly_off']?></td>
          <td><?= $work_done['tot_paydays']?></td>
          <td><?= $work_done['tot_ot_hours']?></td>
        </tr>
         <?php } ?>
      </tbody>
    </table>
  </div>
  <?php  } ?>
  <iframe id="iframeID" style="position: absolute;width:0;height:0;border:0;"></iframe>
  <script type="text/javascript">
    
    $(document).ready(function(){
      <?php 
        if ($model->state) {?>

          setTimeout(function() {
            $("#attendance-state").val(<?=$model->state?>);
            getlocation(<?=$model->state?>);
          }, 300);
     <?php 
       }?>
    })
  </script>
<?php
$js = <<<JS
 
     $( "#attendance-start_date" ).datepicker({
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
    $("#attendance-start_date").focus(function () {
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

<!-- Search location,plant,purchaseorderid,section -->
<script>
  function getlocation(id){
      $.ajax({url:"<?=Url::toRoute(['location/getlocationdetails'])?>?id="+id,
                success:function(results)
                { 
                    if(results)
                    {
                        $('#attendance-location').html('<option value="">Choose Location...</option>');
                        var locations=JSON.parse(results);
                        $.each(locations,function(key,value){
                            $('#attendance-location').append('<option value="'+key+'">'+value+'</option>');
                        });
                        <?php
                          if ($model->location != '') {
                          ?>
                            $("#attendance-location").val(<?=$model->location?>);
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
                        $('#attendance-plant_id').html('<option value="">Choose Plant...</option>');
                        var plants=JSON.parse(results);
                        $.each(plants,function(key,value){
                            $('#attendance-plant_id').append('<option value="'+key+'">'+value+'</option>');
                        });
                        <?php
                          if ($model->plant_id != '') {
                          ?>
                            $("#attendance-plant_id").val(<?=$model->plant_id?>);
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
                        $('#attendance-purchase_orderid').html('<option value="">Choose PO...</option>');
                        var pos=JSON.parse(results);
                        $.each(pos,function(key,value){
                            //$('#salary-purchase_orderid').append('<option value="'+key+'">'+value+'</option>');
                            $("#attendance-purchase_orderid").append('<option value="' +value.po_id + '" dataid="' + value.po_id + '">' + value.purchase_order_name + '</option>');
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
                        $('#attendance-section_id').html('<option value="">Choose Section...</option>');
                        var sections=JSON.parse(results);
                        $.each(sections,function(key,value){
                            $("#attendance-section_id").append('<option value="' +value.section_id +  '" dataid="' + value.section_id + '">' + value.section_name + '</option>');
                        });
                        
                    }
                }
        });
  }
  

    //Attendance REport Download
  $("#attendance_report").click(function(){
        var state_id=$("#attendance-state").val();
        var loc_id=$("#attendance-location").val();
        var plant_id=$("#attendance-plant_id").val();
        var po_id=$("#attendance-purchase_orderid").val();
        var section_id=$("#attendance-section_id").val();
        var month_year=$("#attendance-start_date").val();
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
        if(confirm("Do you want to Download Attendance Report!")){
          $.ajax({
            url: "<?=Url::to(['attendance/download_approved_attendance_report'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year,
            
            success:function(results)
            { 
              if(results){
                $("#iframeID").attr('src', results);
                alert("Attendance Report Downloaded Successfuly");
              }else{
                alert("No Employee found");
              }
              
            }
          });
        }else{
          return false;
        }
        
        //return false;  
      });
  
</script>