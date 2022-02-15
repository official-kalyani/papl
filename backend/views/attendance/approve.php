<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\Attendance */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
  .approve-color{

    background-color: #0e9c19;

  }
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
.tableFixHead table  { border-collapse: collapse; width: 100%; }
.tableFixHead th, td { padding: 8px 16px; white-space: nowrap; }
.tableFixHead th,td     { background:#eee; }
.loader,
        .loader:after {
            border-radius: 50%;
            width: 10em;
            height: 10em;
        }
        .loader {            
            margin: 60px auto;
            font-size: 10px;
            position: relative;
            text-indent: -9999em;
            border-top: 1.1em solid rgba(255, 255, 255, 0.2);
            border-right: 1.1em solid rgba(255, 255, 255, 0.2);
            border-bottom: 1.1em solid rgba(255, 255, 255, 0.2);
            border-left: 1.1em solid #ffffff;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-animation: load8 1.1s infinite linear;
            animation: load8 1.1s infinite linear;
        }
        @-webkit-keyframes load8 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes load8 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        #loadingDiv {
            position:absolute;;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background-color:#000;
        }
</style>
<div  id="loadingDiv"><div id="overlay"><div class="loader">Loading...</div></div></div>
<?php 
    if (isset($updated_details_name->username)) {       
   
?>
<span class="badge badge-pill badge-info float-right">Last updated by : <?= $updated_details_name->username ?? '';?></span>
<?php  } ?>
<div class="attendance-approve">

  <?php $form = ActiveForm::begin(['action' => ['attendance/approve'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'attendanceform']]); ?>
  <div class="row">
    <div class="col-md-2">
      <div class="form-group field-client">
        <div class="form-group field-attendance-location_id">
        <label class="control-label" for="attendance-location_id">State</label>
          <select id="attendance-location_id" class="form-control state" name="Attendance[state_id]" onchange="return getlocation(this.value)">
            <option value="">State</option>
            <?php
                foreach ($states as $key => $value) {?>
                  
                  <option value="<?=$key?>" dataid="<?=$key?>" <?php if ($role != 1 && $role != 5) {
                    if ($key == $user_plant_id->state_id) {
                      echo 'selected';
                    }
                  }elseif ($key == $state_id) {
                      echo 'selected';
                    }else{
                      echo '';
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
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
        <div class="form-group field-attendance-location_id">
        <label class="control-label" for="attendance-location_id">Location</label>
          <select id="attendance-location_id" class="form-control location" required="required" name="Attendance[location_id]" onchange="return checkPlantdetails(this.value)">
            <option value="">Location</option>
            <?php
                // foreach ($locations as $key => $value) {
                  ?>
                  
                  <!-- <option value="<?=$key?>" dataid="<?=$key?>" <?=($key == $location)?'selected':'';?>> -->
                    <!-- <?= $value?> -->
                      
                    </option>
               <?php  
             // }
            ?>
          </select>
          
          <div class="help-block"></div>
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'plant_id')->dropDownList([], [
           'class' => 'form-control plant',
          'onchange' => 'return getPurchaseorderdetails(this.value)',
          'required' => true
          
        ]) ?>

      </div>

    </div>

    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'purchase_orderid')->dropDownList([], [ 'class' => 'form-control purchaseorder', 'onchange' => 'return getSectiondetails(this.value)']) ?>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'section_id')->dropDownList([], ['class' => 'form-control section', 'value' => $date]) ?>
      </div>
    </div>
    <!-- <div class="col-md-2">
        <?= $form->field($model, 'start_date')->textInput(); ?>
    </div> -->
    <div class="col-md-2">
      <div class="form-group field-attendance-start_date">
        <label class="control-label" for="attendance-start_date">Choose Month</label>
        <input type="text" id="attendance-start_date" class="form-control" name="Attendance[start_date]" value="<?php echo $model->start_date ?>" readonly='true'>

        <div class="help-block"></div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
        <label></label>
        <?= Html::submitButton("Search", ['class' => "btn btn-info", 'name' => 'search', 'value' => 'search']); ?>

      </div>
    </div>

  </div>
  <?php ActiveForm::end(); ?>
  
  
  <div class="tableFixHead">
    <?php 
      if ($search_result == 1) {
        ?>
        <p class="h6">Attendance for <?php echo $state_short_code->short_code.'/'.$location_short_code->short_code.'/'.$plant_short_code->short_code;?></p>
     <?php  }
    ?>
    <h2>Attendance for <?= $date ?></h2>
    <table class="table table-bordered ">
      <thead>
        <tr>
          <th scope="col">Employee ID</th>
          <th scope="col">Employee Name</th>
          <th scope="col">Designation</th>
          <!-- <th scope="col" class="att-month"> <?php  echo $date; ?></th> -->
          <?php 
                  $days= (date('t', strtotime($date)));
                  for ($l=1; $l <= $days; $l++) { 
                    ?>
          <th scope="col" class="att-month" colspan="7" > <?php  echo $l.' '.$date; ?></th>
           <?php  }
          ?>
        </tr>
        
        <tr>
          <td colspan="3"></td>
         
            <?php 
              $days= (date('t', strtotime($date)));
             
              for ($k=1; $k <= $days; $k++) { 
                echo '<th>ATT</th>';
                echo '<th>ATT_TYPE</th>';
                echo '<th>NH</th>';
                echo '<th>FH</th>';
                echo '<th>NHFH_TYPE</th>';
                echo '<th>OT</th>';
                echo '<th>OT_TYPE</th>';
                
              }
        ?>
             
        </tr>
        </thead>
      <tbody>
        
            <?php
        // $i = 0;
           
         foreach ($attendance_lists as $key => $value) {
          $enrol=$model->getEnrolementDetail($key);
          // echo "<pre>";print_r($attendance_lists[$key]);
        ?>
        <tr>
          <th>
          
              <?=$key?>
            <input type="hidden" name="emp_id[]" value="<?= $key ?>">
          
          </th>
          <th>
           
              <?= $enrol->adhar_name;?>
              
            
          </th>
          <th>
           
              <?= $enrol->PAPLdesignation;?>
           
                  
          </th>
          <?php  
          for ($k=1; $k <= $days; $k++) {
            $att='';
            $att_type='';
            $nh='';
            $fh='';
            $nhfh_type='';
            $ot='';
            $single_date='';
            $ot_type='';
            $day_format= $k.$date;
            $status = '';
            $day_name= (date('Y-m-d', strtotime($day_format)));
            foreach ($value as $value1) {
              // echo "<pre>";print_r($value1);die();
              if($value1['date']==$day_name){
                 $att = $value1['att'];
                 $att_type = $value1['att_type'];
                 $nh = $value1['nh'];
                 $fh = $value1['fh'];
                 $nhfh_type = $value1['nhfh_type'];
                 $ot = $value1['ot'];
                 $ot_type = $value1['ot_type'];
                  $single_date = $value1['date'];
                  $status = $value1['status'];

              }
            }
            
             ?>
          
          <td  class="<?php 
          if ($status == 1) {
            echo 'approve-color';
          }else{
            echo '';
          }

        ?>">
            <div class="col-md-4">
              <select class="form-fixer" id="attendance-0-att" name="Att[]" >
                <option value=""></option>
                <option value="A" <?php echo ($att == 'A') ? 'selected' : '';?> data-stats="<?=$status?>"  data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='att'>A</option>
                  <option value="B" <?php echo ($att == 'B') ? 'selected' : '';?> data-stats="<?=$status?>"   data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='att'>B</option>
                  <option value="C" <?php echo ($att == 'C') ? 'selected' : '';?> data-stats="<?=$status?>"  data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='att'>C</option>
                  <option value="G" <?php echo ($att == 'G') ? 'selected' : '';?> data-stats="<?=$status?>"  data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='att'>G</option>
                  <option value="O" <?php echo ($att == 'O') ? 'selected' : '';?> data-stats="<?=$status?>"   data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='att'>O</option>
                  <option value="COFF" <?php echo ($att == 'COFF') ? 'selected' : '';?> data-stats="<?=$status?>"   data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='att'>COFF</option>
                  <option value="AB" <?php echo ($att == 'AB') ? 'selected' : '';?> data-stats="<?=$status?>"  data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='att'>AB</option>
                  <option value="L" <?php echo ($att == 'L') ? 'selected' : '';?> data-stats="<?=$status?>"   data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='att'>L</option>
                  <option value="P" <?php echo ($att == 'P') ? 'selected' : '';?> data-stats="<?=$status?>"   data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='att'>P</option>
                  <option value="HP" <?php echo ($att == 'HP') ? 'selected' : '';?> data-stats="<?=$status?>"   data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='att'>HP</option>
              </select>
               
                  </div>
          </td>
          <td class="<?php 
          if ($status == 1) {
            echo 'approve-color';
          }else{
            echo '';
          }

        ?>">
            <div class="col-md-3">
              <select class="form-fixer" id="attendance-0-att_type" name="Att_Ty[]" data-status="<?=$status?>">
                              <option value=""></option>
                              <option value="Basic" <?php echo ($att_type == 'Basic') ? 'selected' : '';?>  data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?> data-stats="<?=$status?>"  data-type='att_type'>Basic</option>
                              <option value="Gross" <?php echo ($att_type == 'Gross') ? 'selected' : '';?> data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='att_type' data-stats="<?=$status?>">Gross</option>
              </select>
              <!-- <input type="text" id="attendance-0-att_type" class="form-control form-fixer" name="Attendance[att_type][]"  maxlength="255" aria-invalid="false" value="<?php echo $att_type;?>" data-empid= <?= $enrol->papl_id;?> data-date=<?= $single_date;?>  > -->
            </div>
          </td>
          <td class="<?php 
          if ($status == 1) {
            echo 'approve-color';
          }else{
            echo '';
          }

        ?>">
            <select class="form-fixer" id="attendance-0-nh" name="NH[]" data-status="<?=$status?>">
              <option value=""></option>
              <option value="P" <?php echo ($nh == 'P') ? 'selected' : '';?> data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='nh' data-stats="<?=$status?>" >P</option>
              <option value="H" <?php echo ($nh == 'H') ? 'selected' : '';?> data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='nh' data-stats="<?=$status?>" >H</option>
            </select>
          </td>
          <td class="<?php 
          if ($status == 1) {
            echo 'approve-color';
          }else{
            echo '';
          }

        ?>">
            <select class="form-fixer" id="attendance-0-fh"  name="FH[]" data-status="<?=$status?>">
              <option value=""></option>
              <option value="P" <?php echo ($fh == 'P') ? 'selected' : '';?> data-stats="<?=$status?>"  data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='fh'>P</option>
              <option value="H" <?php echo ($fh == 'H') ? 'selected' : '';?> data-stats="<?=$status?>"  data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='fh'>H</option>
            </select>
                
          </td>
          <td class="<?php 
          if ($status == 1) {
            echo 'approve-color';
          }else{
            echo '';
          }

        ?>">
            <div class="col-md-3">
              <select class="form-fixer" id="attendance-0-nhfh_type" name="NH_FH_Ty[]" data-status="<?=$status?>">
                              <option value=""></option>
                              <option value="Basic" data-stats="<?=$status?>"  <?php echo ($nhfh_type == 'Basic') ? 'selected' : '';?> data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='nhfh_type'>Basic</option>
                              <option value="Gross" <?php echo ($nhfh_type == 'Gross') ? 'selected' : '';?> data-stats="<?=$status?>"  data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='nhfh_type'>Gross</option>
              </select>
             </div>
          </td>
          <td class="<?php 
          if ($status == 1) {
            echo 'approve-color';
          }else{
            echo '';
          }

        ?>">
            <div class="col-md-3">
              <input type="text" id="attendance-0-ot" class="form-fixer-text" name="Attendance[ot][]" maxlength="255" value="<?= $ot;?>" data-empid= <?= $enrol->papl_id;?> data-date=<?= $day_name;?> data-stats="<?=$status?>"   data-type='ot' data-status="<?=$status?>">
            </div>
          </td>
          <td class="<?php 
          if ($status == 1) {
            echo 'approve-color';
          }else{
            echo '';
          }

        ?>">
            <select class="form-fixer" id="attendance-0-fh" name="OT_Ty[]" data-status="<?=$status?>">
              <option value=""></option>
              <option value="BS" <?php echo ($ot_type == 'BS') ? 'selected' : '';?>  data-empid=<?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='ot_type' data-stats="<?=$status?>" >BS</option>
              <option value="BD" <?php echo ($ot_type == 'BD') ? 'selected' : '';?>  data-empid=<?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='ot_type' data-stats="<?=$status?>" >BD</option>
              <option value="GS" <?php echo ($ot_type == 'GS') ? 'selected' : '';?> data-empid=<?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='ot_type' data-stats="<?=$status?>" >GS</option>
              <option value="GD" <?php echo ($ot_type == 'GD') ? 'selected' : '';?> data-empid=<?= $enrol->papl_id;?> data-date=<?= $day_name;?>  data-type='ot_type' data-stats="<?=$status?>" >GD</option>
            </select>
            
          </td>
          <?php 
            
            } ?>
        </tr>


                <?php 

        
               }
        

        ?>


      
        

      </tbody>
    </table>
  </div>
<?php $form = ActiveForm::begin(['action' => ['attendance/approve'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>
  
  <input type="hidden" name="att_date" value="<?= $date ?>">
   <?php
       
        if ($attendance_lists) {
          // code...
       
         foreach ($attendance_lists as $key => $value) {
          
        ?>
  <input type="hidden" name="emp_id[]" value="<?= $key ?>">
<?php 
  }
  }
?>
  <div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Approve'), ['class' => 'btn btn-success', 'name' => 'approve', 'value' => 'approve']) ?>  
  </div>

  <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<<JS
 
     $( "#attendance-start_date" ).datepicker({
        dateFormat: 'MM yy',
        changeMonth: true,
        changeYear: true,
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
<script type="text/javascript">
  function checkPlant(id) {
    var id = id;
    $.ajax({
      type: 'post',
      url: "<?= Yii::$app->urlManager->createUrl('plant/getplant'); ?>",
      data: {
        id: id
      },
      success: function(data) {
        $('.plant').html(data);

      }
    });
  }

  function getPurchaseorder(id) {
    var id = id;
    $.ajax({
      type: 'post',
      url: "<?= Yii::$app->urlManager->createUrl('purchaseorder/getpurchaseorder'); ?>",
      data: {
        id: id
      },
      success: function(data) {
        $('.purchaseorder').html(data);

      }
    });
  }

  function getSection(id) {
    var id = id;
    $.ajax({
      type: 'post',
      url: "<?= Yii::$app->urlManager->createUrl('section/getsection'); ?>",
      data: {
        id: id
      },
      success: function(data) {
        $('.section').html(data);

      }
    });
  }
</script>

<script type="text/javascript">
   
    $('.form-fixer').on('change', function() {
      empid =  $(this).find(':selected').data('empid'); 
        type =  $(this).find(':selected').data('type'); 
        date =  $(this).find(':selected').data('date');
        status = $(this).find(':selected').data('stats');
        data =$(this).val();
        if (status == 1) {
        var retVal = confirm("Do you want to edit ?");
        if (retVal == true)
        {
            $.ajax({
            type:'post',
            url: "<?= Yii::$app->urlManager->createUrl('attendance/getattendanceedit'); ?>",
            data:{empid:empid,type:type,date:date,data:data,},
            success:function(data){                
                  // alert(data.status);
                  var obj = data;
                  // alert(obj);
                 if(JSON.parse(obj).status == 'Successfull'){
                   alert('data edited');
                  
                 }
                
            }
        });
        } 
        else
        {
            alert("User does not want to continue!");
            return false;
        }
      }else{
         $.ajax({
            type:'post',
            url: "<?= Yii::$app->urlManager->createUrl('attendance/getattendanceedit'); ?>",
            data:{empid:empid,type:type,date:date,data:data,},
            success:function(data){                
                  // alert(data.status);
                  var obj = data;
                  // alert(obj);
                 if(JSON.parse(obj).status == 'Successfull'){
                   alert('data saved');
                  
                 }
                
            }
        });

      }
        
    });
     $('.form-fixer-text').on('blur', function() {
        // on blur, if there is no value, set the defaultText
        empid =  $(this).data('empid'); 
        type =  $(this).data('type'); 
        date =  $(this).data('date'); 
        status =  $(this).data('stats'); 
        data =$(this).val();
        if (status == 1) {
          var retVal = confirm("Do you want to edit ?");
        if (retVal == true)
        {
            $.ajax({
            type:'post',
            url: "<?= Yii::$app->urlManager->createUrl('attendance/getattendanceedit'); ?>",
            data:{empid:empid,type:type,date:date,data:data,},
            success:function(data){                
                var obj = data;
                 if(JSON.parse(obj).status == 'Successfull'){
                   alert('data edited');
                  
                 }
              
            }
        });
        } 
        else
        {
            alert("User does not want to edit!");
            $('.form-fixer-text').val();
            return false;
        }
      }else{
        $.ajax({
            type:'post',
            url: "<?= Yii::$app->urlManager->createUrl('attendance/getattendanceedit'); ?>",
            data:{empid:empid,type:type,date:date,data:data,},
            success:function(data){                
                var obj = data;
                 if(JSON.parse(obj).status == 'Successfull'){
                   alert('data saved');
                  
                 }
              
            }
        });

      }
        
       
        
    });
  </script>
  <script>
     $(document).ready(function(){
      $( "#loadingDiv" ).show();
        setTimeout(removeLoader, 1000);

       <?php 
        if ($role != 1 && $role != 5) {?>

       setTimeout(function() {
        $(".location").val(<?=$user_plant_id->location_id?>);
        
        getlocation(<?=$user_plant_id->state_id;?>);
      }, 300);
    
   <?php
    }elseif($location !=''){
      
      ?>
        
        setTimeout(function() {
          $(".location").val(<?=$location?>);
         
          getlocation(<?=$state_id;?>);
        }, 1000);
       
      <?php
    }
    ?>
     });
    
  function getlocation(id) {
       
        $.ajax({
                  type: 'post',
                  url: "<?=Url::to(['location/getlocationdetails'])?>?id="+id,
                  data: {
                    id: id,
                  },
                  success: function(data) { 
                 
                    if (data) {
                 
                    $('.location').html('<option value="">Select Location</option>');                  
                    var result = JSON.parse(data);
                    $.each(result, function (index, value) {
                        $(".location").append('<option value="' + index +'" dataid="' + index + '">' + value + '</option>');
                        
                                       
                   }); 

                  <?php 
                    if ($role != 1 && $role != 5) {?>

                   setTimeout(function() {
                    $(".location").val(<?=$user_plant_id->location_id?>);
                    var key = $('.location option:selected').val();
                    
                    checkPlantdetails(key);
                  }, 301);
                
                   <?php
                    }elseif($location !=''){
                      ?>
                        $(".location").val(<?=$location?>);
                        var key = $('.location option:selected').val();
                        checkPlantdetails(key);

                      <?php
                      }
                      ?> 
                    }

                  }//success
              }); 
       
    }
    function checkPlantdetails(id) {
        
        $.ajax({
                  type: 'post',
                  url: "<?=Url::to(['plant/getplantdetails'])?>?id="+id,
                  data: {
                    id: id,
                  },
                  success: function(data) { 
                 
                    if (data) {
                 
                    $('.plant').html('<option value="">Select Plant</option>');                  
                    var result = JSON.parse(data);
                    $.each(result, function (index, value) {
                        $(".plant").append('<option value="' + index +'" dataid="' + index + '">' + value + '</option>');
                        // $(".plant").append('<option value="' + value.plant_name + ','+value.plant_id +'" dataid="' + value.plant_id + '">' + value.plant_name + '</option>');
                                       
                   }); 

                   <?php 
                    if ($role != 1 && $role != 5) {?>

                   setTimeout(function() {
                    $(".plant").val(<?=$user_plant_id->plant_id?>);
                    var plant_id = $('.plant option:selected').val();
                        getPurchaseorderdetails(plant_id);
                  }, 302);
                
                   <?php
                    }elseif($plant !=''){
                      ?>
                        $(".plant").val(<?=$plant?>);
                        var key = $('.plant option:selected').val();
                        getPurchaseorderdetails(key);

                      <?php
                      }
                      ?> 
                    }

                  }//success
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
                    }elseif($plant !=''){
                
                ?>
                 $(".purchaseorder").val(<?=$purchase_order?>);
                   
                var po_id = $('.purchaseorder option:selected').val();
                getSectiondetails(po_id);
              <?php }?>
            }
            

          }//success
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
                    }elseif($plant !=''){
                ?>
                 $(".section").val(<?=$section?>);
                   
                
              <?php }?>
            }
          }
        });
    }
  
</script>
<script type="text/javascript"></script>
<script type="text/javascript">
  
// $(document).ready( function() {
//   $( "#loadingDiv" ).show();
//   setTimeout(removeLoader, 1000); //wait for page load PLUS two seconds.
// });
function removeLoader(){
    $( "#loadingDiv" ).fadeOut(500, function() {
      // fadeOut complete. Remove the loading div
      // $( "#loadingDiv" ).remove(); //makes page more lightweight 
      $( "#loadingDiv" ).hide();
  });  
}
</script>