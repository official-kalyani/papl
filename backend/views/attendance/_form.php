<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Attendance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="attendance-form">
  
  <?php $form = ActiveForm::begin(['action' => ['attendance/create'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'searchform','id'=>'searchform']]); ?>
<!-- <?= $form->field($model, 'nhfh_type')->textInput(['maxlength' => true])->label(false) ?> -->


<div class="row">
    <div class="col-md-2">
      <div class="form-group field-client">
        <div class="form-group field-attendance-state_id">
        <label class="control-label" for="attendance-state_id">State</label>
          <select required="required" id="attendance-state_id" class="form-control state" name="Attendance[state_id]" onchange="return getlocation(this.value)">
            <option value="">State</option>
            <?php
                foreach ($states as $key => $value) {

                  ?>
                  
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
          <select required="required" id="attendance-location_id" class="form-control location" name="Attendance[location_id]" onchange="return checkPlantdetails(this.value)">
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
        <?= $form->field($model, 'purchase_orderid')->dropDownList([], ['class' => 'form-control purchaseorder', 'onchange' => 'return getSectiondetails(this.value)']) ?>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'section_id')->dropDownList([], ['class' => 'form-control section', 'value' => $date]) ?>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
        <div class="form-group field-attendance-start_date">
          <label class="control-label" for="attendance-start_date">Date</label>
          <input type="text" id="attendance-start_date" class="form-control" name="Attendance[start_date]" value="<?php echo isset($date)?$date:''; ?>">
          <div class="help-block"></div>
        </div>       
      </div>
    </div>
    
    
    
  </div>
  <div class="row">
    <div class="col-md-2">
      <div class="form-group field-client">
        <label></label>
        <?= Html::submitButton("Search", ['class' => "btn btn-info", 'name' => 'search', 'value' => 'search','id'=>'searchid']); ?>

      </div>
    </div>
    <div class="form-group field-client">
        <button type="button"  data-toggle="modal"  class="btn btn-success" id="import_modal">Import</button>
      </div>
  </div>
  <?php ActiveForm::end(); ?>
  
  <?php $form = ActiveForm::begin(['action' => ['attendance/create'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>
  <?php
  // echo '<pre>';print_r($posting_history->enrolement);exit();

  ?>
  <input type="hidden" name="att_date" value="<?= $date ?>" id="att_date">
  <input type="hidden" name="location" value="<?= $location ?>" id="location">
  <input type="hidden" name="state_id" value="<?= $state_id ?>" id="state_id">
  <input type="hidden" name="section" value="<?= $section ?>" id="section">
  <input type="hidden" name="purchase_order" value="<?= $purchase_order ?>" id="purchase_order">
  <input type="hidden" name="plant" value="<?= $plant ?>" id="plant">
  <div>
    <?php 
      if ($search_result == 1) {
        ?>
        <p class="h6">Attendance for <?php echo $state_short_code->short_code.'/'.$location_short_code->short_code.'/'.$plant_short_code->short_code;?></p>
     <?php  }
    ?>
    
    <h2>Attendance for <?= $date ?></h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">Employee ID</th>
          <th scope="col">Employee Name</th>
          <th scope="col">Designation</th>
          <th scope="col">ATT</th>
          <!-- <th scope="col">att_type</th> -->
          <th scope="col">NH</th>
          <th scope="col">FH</th>
          <!-- <th scope="col">nhfh_type</th> -->
          <th scope="col">OT</th>
          <th scope="col">OT_TYPE</th>
          <th scope="col">remark</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 0;
        foreach ($posting_historys as $posting_history) {
          if ($posting_history) {
           

           if (isset($attendance_history)) {
             $data_by_date = $attendance_history->getAttendanceDetailByDate($posting_history->papl_id,date('Y-m-d', strtotime($date)));
            $status_by_date = $attendance_history->getAttendanceStatusByDate($posting_history->papl_id,date('Y-m-d', strtotime($date)));
           }

            

             
              
        ?>
            <tr style="<?php 
                  if(isset($status_by_date) && $status_by_date->status == 1){echo 'display:none';}else{echo '';};
                ?>">
              <th scope="row">
                  <?= $form->field($model, '[' . $i . ']papl_id')->textInput(['maxlength' => true, 'value' => isset($posting_history->papl_id) ? $posting_history->papl_id : '','readonly' => true])->label(false) ?>
              </th>
              <td>
                  <?= $form->field($model, '[' . $i . ']employee_name')->textInput(['maxlength' => true, 'value' => isset($posting_history->enrolement->adhar_name) ? $posting_history->enrolement->adhar_name : '','readonly' => true])->label(false) ?>
              </td>
              <td>
                  <?= $form->field($model, '[' . $i . ']PAPLdesignation')->textInput(['maxlength' => true, 'value' => isset($posting_history->enrolement->PAPLdesignation) ? $posting_history->enrolement->PAPLdesignation : '','readonly' => true])->label(false) ?>
              </td>
              <td>
                
                 
                  <select class="form-fixer" id="attendance-<?= $i ?>-att" name="Attendance[<?= $i ?>][att]" >
                    <option value=""></option>
                    <option value="A" <?php if(isset($data_by_date['att'])){ echo ($data_by_date['att'] == 'A') ? 'selected' : '';}?> >A</option>
                      <option value="B" <?php if(isset($data_by_date['att'])){ echo ($data_by_date['att'] == 'B') ? 'selected' : '';}?>  >B</option>
                      <option value="C" <?php if(isset($data_by_date['att'])){ echo ($data_by_date['att'] == 'C') ? 'selected' : '';}?> >C</option>
                      <option value="G" <?php if(isset($data_by_date['att'])){ echo ($data_by_date['att'] == 'G') ? 'selected' : '';}?> >G</option>
                      <option value="O" <?php if(isset($data_by_date['att'])){ echo ($data_by_date['att'] == 'O') ? 'selected' : '';}?>  >O</option>
                      <option value="COFF" <?php if(isset($data_by_date['att'])){ echo ($data_by_date['att'] == 'COFF') ? 'selected' : '';}?>  >COFF</option>
                      <option value="AB" <?php if(isset($data_by_date['att'])){ echo ($data_by_date['att'] == 'AB') ? 'selected' : '';}?> >AB</option>
                      <option value="L" <?php if(isset($data_by_date['att'])){ echo ($data_by_date['att'] == 'L') ? 'selected' : '';}?>  >L</option>
                      <option value="P" <?php if(isset($data_by_date['att'])){ echo ($data_by_date['att'] == 'P') ? 'selected' : '';}?>  >P</option>
                      <option value="HP" <?php if(isset($data_by_date['att'])){ echo ($data_by_date['att'] == 'HP') ? 'selected' : '';}?>  >HP</option>
                  </select>
               
              </td>

              <td>

                <!-- <?= $form->field($model, '[' . $i . ']nh')->textInput(['maxlength' => true,'value'=>isset($data_by_date['nh']) ? $data_by_date['nh'] : ''])->label(false) ?> -->
                <select class="form-fixer" id="attendance-$i-nh" name="Attendance[<?= $i ?>][nh]" >
                    <option value=""></option>
                    <option value="P" <?php if(isset($data_by_date['nh'])){ echo ($data_by_date['nh'] == 'P') ? 'selected' : '';}?> >P</option>
                    <option value="H" <?php if(isset($data_by_date['nh'])){ echo ($data_by_date['nh'] == 'H') ? 'selected' : '';}?> >H</option>
                  </select>
              </td>
              <td>

                <!-- <?= $form->field($model, '[' . $i . ']fh')->textInput(['maxlength' => true,'value'=>isset($data_by_date['fh']) ? $data_by_date['fh'] : ''])->label(false) ?> -->
                <select class="form-fixer" id="attendance-$i-fh" name="Attendance[<?= $i ?>][fh]" >
                    <option value=""></option>
                    <option value="P" <?php if(isset($data_by_date['fh'])){ echo ($data_by_date['fh'] == 'P') ? 'selected' : '';}?> >P</option>
                    <option value="H" <?php if(isset($data_by_date['fh'])){ echo ($data_by_date['fh'] == 'H') ? 'selected' : '';}?> >H</option>
                  </select>
              </td>
              <td>
              
                <?= $form->field($model, '[' . $i . ']ot')->textInput(['maxlength' => true,'value'=>isset($data_by_date['ot']) ? $data_by_date['ot'] : ''])->label(false) ?>
            
              </td>
              <td>

                <!-- <?= $form->field($model, '[' . $i . ']ot_type')->textInput(['maxlength' => true,'value'=>isset($data_by_date['ot_type']) ? $data_by_date['ot_type'] : ''])->label(false) ?> -->
                <select class="form-fixer" id="attendance-$i-ot_type" name="Attendance[<?= $i ?>][ot_type]" >
                    <option value=""></option>
                    <option value="BS" <?php if(isset($data_by_date['ot_type'])){ echo ($data_by_date['ot_type'] == 'BS') ? 'selected' : '';}?>>BS</option>
                    <option value="BD" <?php if(isset($data_by_date['ot_type'])){ echo ($data_by_date['ot_type'] == 'BD') ? 'selected' : '';}?>>BD</option>
                    <option value="GS" <?php if(isset($data_by_date['ot_type'])){ echo ($data_by_date['ot_type'] == 'GS') ? 'selected' : '';}?>>GS</option>
                    <option value="GD" <?php if(isset($data_by_date['ot_type'])){ echo ($data_by_date['ot_type'] == 'GD') ? 'selected' : '';}?>>GD</option>
                  </select>
              </td>
              <td>
                
                <?= $form->field($model, '[' . $i . ']remark')->textInput(['maxlength' => true,'value'=>isset($data_by_date['remark']) ? $data_by_date['remark'] : ''])->label(false) ?>
               
              </td>

            </tr>
        <?php
          }
          $i++;
        }
        ?>


      </tbody>
    </table>
  </div>

  <div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'name' => 'save', 'value' => 'submit']) ?>
  </div>

  <?php ActiveForm::end(); ?>

</div>
<button type="button" id="attendanceSample" class="float-right btn btn-success"><span class='fa fa-download' aria-hidden='true'>Download Samples</span></button>
<!-- Attendance Import Modal -->
<div id="attendance_import_Modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				
				<h4 class="modal-title">Upload Attendance</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php $attendanceimportform = ActiveForm::begin(['id' => "attendance_import_form",'action' => 'javascript:void(0)','options' => ['enctype' => 'multipart/form-data']]); ?>
					<div class="col-6">
						<?= $attendanceimportform->field($model, 'import_file' )->fileInput(['maxlength' => true,'required'=>true,'accept'=>'.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'])->label('Attendance Data </br>')  ?>
					</div>
					<div class="col-2">
						<?= Html::submitButton('Submit', ['id'=>'attendance_import','class' => 'btn btn-success']) ?>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
		</div>
		</div>
	</div>
</div>

<div id="attendance_import_msg_Modal" class="modal fade" >
	<div class="modal-dialog">
		<div class="modal-content" style='height: 400px;width: 600px;overflow-y:auto;'>
			<div class="modal-header">
				
				<h4 class="modal-title">Upload Attendance</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" >
				<div id='msg' ></div>
			</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
		</div>
		</div>
	</div>
</div>
<iframe id="iframeID" style="position: absolute;width:0;height:0;border:0;"></iframe>
<?php
$js = <<<JS
 
     $( "#attendance-start_date" ).datepicker({
     showButtonPanel: true,
        dateFormat: "dd-mm-yy",
        changeMonth:true,
        changeYear:true,        
    }).val();
   

JS;


$this->registerJs($js);
?>
<script type="text/javascript">
   $(document).ready(function(){
    // var params = new URLSearchParams(document.location.search.substring(1));
    // let name = params.get("name"); // is the string "Jonathan"
    // let age = parseInt(params.get("age"), 10); // is the number 18
    var searchParams = new URLSearchParams(document.location.search.substring(1));
   
    let location_url = parseInt(searchParams.get('location'));
    let plant_url = parseInt(searchParams.get('plant'));
    let state_url = parseInt(searchParams.get('state_id'));
    let purchase_order_url = parseInt(searchParams.get('purchase_order'));
    let section_url = parseInt(searchParams.get('section'));
    let date_url = searchParams.get('date');    
    if (location_url) {
       setTimeout(function() {
        $("#attendance-state_id").val(state_url);
        $("#attendance-location_id").val(location_url);
        getlocation(state_url);
      }, 300);
    }
  
    
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
      }, 300);
       
      <?php
    }
    
    ?>
    
    $("#import_modal").click(function(){
        var loc_id=$("#attendance-location_id").val();
        var plant_id=$("#attendance-plant_id").val();
        if(!loc_id){
          alert("Choose Location !");
          return false;
        }
        if(plant_id==0){
          alert("Choose Plant !");
          return false;
        }
        $('#attendance_import_Modal').modal('show');
    });
        //Attendance data Sample Download
      $("#attendanceSample").click(function(){
        
        var loc_id=$("#attendance-location_id").val();
        var plant_id=$("#attendance-plant_id").val();
        var po_id=$("#attendance-purchase_orderid").val();
        var section_id=$("#attendance-section_id").val();
        var month_year=$("#attendance-start_date").val();
        if(!loc_id){
          alert("Choose Location !");
          return false;
        }
        if(plant_id==0){
          alert("Choose Plant !");
          return false;
        }
        if(month_year==0){
          alert("Choose Date !");
          return false;
        }
        $.ajax({url:"attendance_sample?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year,
                success:function(results)
                { 
                  $("#iframeID").attr('src', results);
                          console.log(results);
                                
                }
        });
        return false;  
      });

        //Attendance Data Import 
      $('#attendance_import_form').submit(function(e){
        //alert('Hello');
        e.preventDefault(); 
        e.stopImmediatePropagation();
      
      var plant_id=$("#attendance-plant_id").val();
      
      var formData = new FormData($('#attendance_import_form')[0]);
      $.ajax({  
        url:"attendance_import?plant_id="+plant_id,  
        method:"POST",  
        data:formData,  
        contentType: false,
        processData: false,
        cache: false,
        beforeSend:function(data){  
        $('#import').val("Importing"); 
        console.log(data);	 
        },  
        success:function(results){ 
            //console.log(results);
        $('#attendance_import_form')[0].reset();  
        $('#attendance_import_Modal').modal('hide');  
        $('#attendance_import_msg_Modal').modal('show'); 
        $('#msg').html(results);
        //$('#msg').appendChild(document.createElement("br"));
        //alert(results);
        
        }  
      });
        return false;   
      }); 

   });
  

  

  
</script>


<!-- Search location,plant,purchaseorderid,section -->
<script>
  function getlocation(id) {
        var searchParams = new URLSearchParams(document.location.search.substring(1));
        let location_url = parseInt(searchParams.get('location'));
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
                        setTimeout(function() {
                        $(".location").val(<?=$location?>);
                        var key = $('.location option:selected').val();
                        checkPlantdetails(key);
                      },301);

                      <?php
                      }
                      ?> 
                      if (location_url) {
                         setTimeout(function() {
                         $("#attendance-location_id").val(location_url);
                         var key = $('#attendance-location_id option:selected').val();
                        checkPlantdetails(key);
                        }, 301);
                      }
                    }

                  }//success
              }); 
       
    }
  function checkPlantdetails(id) {
        var key = $('.location option:selected').attr('dataid');
        var searchParams = new URLSearchParams(document.location.search.substring(1));
        let plant_url = parseInt(searchParams.get('plant'));
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
                        setTimeout(function() {
                        $(".plant").val(<?=$plant?>);
                        var plant_id = $('.plant option:selected').val();
                        getPurchaseorderdetails(plant_id);
                        }, 302);
                      <?php
                      }
                      ?> 
                      if (plant_url) {
                          
                         setTimeout(function() {
                         $("#attendance-plant_id").val(plant_url);
                         var key = $('#attendance-plant_id option:selected').val();
                        
                        getPurchaseorderdetails(key);
                        }, 302);
                      }
                    }

                  }//success
              }); 
       
    }
   function getPurchaseorderdetails(id) {
        var key = $('.plant option:selected').attr('dataid');
        var searchParams = new URLSearchParams(document.location.search.substring(1));
        let purchase_order_url = parseInt(searchParams.get('purchase_order'));
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
                    }elseif($plant !=''){
                      
                
                ?>
                 setTimeout(function() {
                 $(".purchaseorder").val(<?=$purchase_order?>);
                           
                var po_id = $('.purchaseorder option:selected').val();
                
                getSectiondetails(po_id);
              },303);
              <?php }?>
              if (purchase_order_url) {                        
               setTimeout(function() {
               $("#attendance-purchase_orderid").val(purchase_order_url);
               var key = $('#attendance-purchase_orderid option:selected').val();
              getSectiondetails(key);
              }, 302);
            }
            }
            

          }//success
        });
    }
  function getSectiondetails(id) {
        var searchParams = new URLSearchParams(document.location.search.substring(1));
        let section_url = parseInt(searchParams.get('section'));
        let savestatus = parseInt(searchParams.get('savestatus'));
        let startdate = searchParams.get('date');
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
                setTimeout(function() {
                 $(".section").val(<?=$section?>);
               },304);
                // var key = $('.section option:selected').val();
                // getSectiondetails(key);
              <?php }?>
              if (section_url) {
                if (savestatus == 1) {
                setTimeout(function(){
                  $("#attendance-section_id").val(section_url);
                  $("#attendance-start_date").val(startdate);                
                  setTimeout(function () {
                    $("#searchform").submit();
                    alert('Data saved');
                }, 306);
                },305);
                
              }
            }
            }
          }
        });
    }
  
</script>
<script type="text/javascript"></script>
