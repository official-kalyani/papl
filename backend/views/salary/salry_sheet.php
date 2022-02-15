<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Salary */

$this->title = Yii::t('app', 'Generate Salary Sheet');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Salaries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salary-create">

    <h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(['action' => ['salary/sheet'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform']]); ?>
<div class="row">
  <div class="col-md-2">
    <div class="form-group field-client">
      <?= $form->field($model, 'state')->dropDownList($states, ['class' => 'form-control state','prompt' => 'Choose State...', 'onchange' => 'return getlocation(this.value)']) ?>
    </div>
  </div>

    <div class="col-md-2">
      <div class="form-group field-client">
        <?= $form->field($model, 'location')->dropDownList([], ['class' => 'form-control location', 'onchange' => 'return checkPlantdetails(this.value)']) ?>
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
        <div class="form-group field-salary-start_date">
          <label class="control-label" for="salary-start_date">Date</label>
          <input type="text" id="salary-start_date" class="form-control" name="Salary[start_date]" value="<?php echo date('F Y',strtotime("first day of previous month")); ?>">
          <div class="help-block"></div>
        </div>       
      </div>
    </div>
    
    
    </div>
    <div class="row">
    <div class="col-md-2">
      <div class="form-group field-client">
      <button type="button" id="calculate_daily_salary" class="float-left btn btn-success"><span class='fa fa-download' aria-hidden='true'>Calculate Daily Salary</span></button>
      
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
      <button type="button" id="calculate_monthly_salary" class="float-left btn btn-info"><span class='fa fa-download' aria-hidden='true'>Clear Cache & Calculate Monthly Salary</span></button>
       
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
      <button type="button" id="update_salary_sheet" class="float-left btn btn-info"><span class='fa fa-download' aria-hidden='true'>Update Monthly Salary</span></button>

      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
      <button type="button" id="generate_salary_sheet" class="float-left btn btn-info"><span class='fa fa-download' aria-hidden='true'>Generate Salary Sheet</span></button>

      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
      <button type="button" id="generate_salary_slip" class="float-left btn btn-success"><span class='fa fa-download' aria-hidden='true'>Generate Salary Slip</span></button>
       
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-2">
      <div class="form-group field-client">
      <button type="button" id="generate_posting_salary" class="float-left btn btn-success"><span class='fa fa-download' aria-hidden='true'>Generate Posting Salary (Excel )</span></button>
       
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
      <button type="button" id="generate_posting_salary_xml" class="float-left btn btn-success"><span class='fa fa-download' aria-hidden='true'>Generate Posting Salary (XML )</span></button>
       
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group field-client">
      <button type="button" id="master_bank_report" class="float-left btn btn-success"><span class='fa fa-download' aria-hidden='true'>MASTER BANK DEBIT REPORT</span></button>
       
      </div>
    </div>
  </div>
        </div>
  <?php ActiveForm::end(); ?>
  
  
  </div>
  <div id="msg_Modal" class="modal fade" >
	<div class="modal-dialog">
		<div class="modal-content" style='height: 400px;width: 600px;overflow-y:auto;'>
			<div class="modal-header">
				
				<h4 class="modal-title">Salary Calculation</h4>
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
  <script type="text/javascript">
    
    $(document).ready(function(){
      <?php 
        if ($model->state) {?>

          setTimeout(function() {
            $("#salary-state").val(<?=$model->state?>);
            getlocation(<?=$model->state?>);
          }, 300);
     <?php 
       }?>
    })
  </script>
<?php
$js = <<<JS
 
     $( "#salary-start_date" ).datepicker({
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
    $("#salary-start_date").focus(function () {
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
                        $('#salary-location').html('<option value="">Choose Location...</option>');
                        var locations=JSON.parse(results);
                        $.each(locations,function(key,value){
                            $('#salary-location').append('<option value="'+key+'">'+value+'</option>');
                        });
                        <?php
                          if ($model->location != '') {
                          ?>
                            $("#salary-location").val(<?=$model->location?>);
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
                        $('#salary-plant_id').html('<option value="">Choose Plant...</option>');
                        var plants=JSON.parse(results);
                        $.each(plants,function(key,value){
                            $('#salary-plant_id').append('<option value="'+key+'">'+value+'</option>');
                        });
                        <?php
                          if ($model->plant_id != '') {
                          ?>
                            $("#salary-plant_id").val(<?=$model->plant_id?>);
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
                        $('#salary-purchase_orderid').html('<option value="">Choose PO...</option>');
                        var pos=JSON.parse(results);
                        $.each(pos,function(key,value){
                            //$('#salary-purchase_orderid').append('<option value="'+key+'">'+value+'</option>');
                            $("#salary-purchase_orderid").append('<option value="' +value.po_id + '" dataid="' + value.po_id + '">' + value.purchase_order_name + '</option>');
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
                        $('#salary-section_id').html('<option value="">Choose Section...</option>');
                        var sections=JSON.parse(results);
                        $.each(sections,function(key,value){
                            $("#salary-section_id").append('<option value="' +value.section_id +  '" dataid="' + value.section_id + '">' + value.section_name + '</option>');
                        });
                        
                    }
                }
        });
  }
  

    //Daily Salary Calculation
  $("#calculate_daily_salary").click(function(){
        var state_id=$("#salary-state").val();
        var loc_id=$("#salary-location").val();
        var plant_id=$("#salary-plant_id").val();
        var po_id=$("#salary-purchase_orderid").val();
        var section_id=$("#salary-section_id").val();
        var month_year=$("#salary-start_date").val();
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
        if(confirm("Do you want to calculate daily salary!")){
          $.ajax({
            url: "<?=Url::to(['attendance/daily_salary_calculation'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year,
            
            success:function(results)
            { 
                //console.log(results);
                //alert(results);
                $('#msg_Modal').modal('show'); 
                $('#msg').html(results);
                            
            }
          });
        }else{
          return false;
        }
        
        return false;  
      });
  //Monthly Salary Calculation
  $("#calculate_monthly_salary").click(function(){
        
    var state_id=$("#salary-state").val();
        var loc_id=$("#salary-location").val();
        var plant_id=$("#salary-plant_id").val();
        var po_id=$("#salary-purchase_orderid").val();
        var section_id=$("#salary-section_id").val();
        var month_year=$("#salary-start_date").val();
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
        if(confirm("Do you want to calculate monthly salary!")){
          $.ajax({
            url: "<?=Url::to(['calculation/total_salary_calculation'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year,
            
            success:function(results)
            { 
              $('#msg_Modal').modal('show'); 
              $('#msg').html(results);
              window.open("<?=Url::to(['salary/salary_sheet'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year);
                            
            }
          });
        }else{
          return false;
        }
        
        return false;  
      });
  //Update Monthly Salary
  $("#update_salary_sheet").click(function(){
        
        var state_id=$("#salary-state").val();
        var loc_id=$("#salary-location").val();
        var plant_id=$("#salary-plant_id").val();
        var po_id=$("#salary-purchase_orderid").val();
        var section_id=$("#salary-section_id").val();
        var month_year=$("#salary-start_date").val();
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
        
        window.open("<?=Url::to(['salary/salary_sheet'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year);
        
        return false;  
      });
    //Salary Sheet Download
  $("#generate_salary_sheet").click(function(){
        
        var state_id=$("#salary-state").val();
        var loc_id=$("#salary-location").val();
        var plant_id=$("#salary-plant_id").val();
        var po_id=$("#salary-purchase_orderid").val();
        var section_id=$("#salary-section_id").val();
        var month_year=$("#salary-start_date").val();
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
        
        $.ajax({
            url: "<?=Url::to(['calculation/salary_sheet'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year,
            
            success:function(results)
            { 
                $("#iframeID").attr('src', results);
                //alert(results);
                alert('Salary Sheet Generated for '+month_year );
                window.open("<?=Url::to(['salary/salary_sheet'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year);
                            
            }
        });
        return false;  
      });

//Salary slip generate
$("#generate_salary_slip").click(function(){
        
        var state_id=$("#salary-state").val();
        var loc_id=$("#salary-location").val();
        var plant_id=$("#salary-plant_id").val();
        var po_id=$("#salary-purchase_orderid").val();
        var section_id=$("#salary-section_id").val();
        var month_year=$("#salary-start_date").val();
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
        
      
        window.open("<?=Url::to(['salary/salary_slip'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year, '_blank');
           
        
        return false;  
      });
   //Posting Salary Sheet Download
   $("#generate_posting_salary").click(function(){
        
        var state_id=$("#salary-state").val();
        var loc_id=$("#salary-location").val();
        var plant_id=$("#salary-plant_id").val();
        var po_id=$("#salary-purchase_orderid").val();
        var section_id=$("#salary-section_id").val();
        var month_year=$("#salary-start_date").val();
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
        if(po_id==0){
          alert("Choose Purchase Order !");
          return false;
        }
        
        $.ajax({
            url: "<?=Url::to(['calculation/salary_postingsheet'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year,
            
            success:function(results)
            { 
                $("#iframeID").attr('src', results);
                //alert(results);
                alert('Posting Salary Sheet Generated for '+month_year );
                //window.open("<?=Url::to(['salary/salary_sheet'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year);
                            
            }
        });
        return false;  
      });
   //Posting Salary Sheet XML  Download
   $("#generate_posting_salary_xml").click(function(){
        
        var state_id=$("#salary-state").val();
        var loc_id=$("#salary-location").val();
        var plant_id=$("#salary-plant_id").val();
        var po_id=$("#salary-purchase_orderid").val();
        var section_id=$("#salary-section_id").val();
        var month_year=$("#salary-start_date").val();
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
        if(po_id==0){
          alert("Choose Purchase Order !");
          return false;
        }
        
        $.ajax({
            url: "<?=Url::to(['salary/salary_postingxml'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year,
            
            success:function(results)
            { 
                $("#iframeID").attr('src', results);
                //alert(results);
                alert('Posting Salary XML Generated for '+month_year );
                window.open("<?=Url::to(['salary/salary_postingxml'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year);
                            
            }
        });
        return false;  
      });
   //Master bank report Download
   $("#master_bank_report").click(function(){
        
        var state_id=$("#salary-state").val();
        var loc_id=$("#salary-location").val();
        var plant_id=$("#salary-plant_id").val();
        var po_id=$("#salary-purchase_orderid").val();
        var section_id=$("#salary-section_id").val();
        var month_year=$("#salary-start_date").val();
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
        if(po_id==0){
          alert("Choose Purchase Order !");
          return false;
        }
        
        $.ajax({
            url: "<?=Url::to(['salary/bank_debit_report'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year,
            
            success:function(results)
            { 
                $("#iframeID").attr('src', results);
                //alert(results);
                alert('Master bank debit report Generated for '+month_year );
                //window.open("<?=Url::to(['salary/salary_sheet'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year);
                            
            }
        });
        return false;  
      });
</script>