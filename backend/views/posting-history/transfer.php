<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\PostingHistorySearch */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
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
            background-color:#b8bfce;
        }
        button#master-import {
    font-size: 10px;
}
</style>
<div  id="loadingDiv"><div class="loader">Loading...</div></div>
<div class="posting-history-search">

    <div class="container-fluid">
  <h2>Employee list</h2>
       
  <table class="emp-table table table-striped table-bordered" style="width:100%">
    <thead>
      <tr>
        <th>Employee Id</th>
        <th>Employee Name</th>
        <th>Designation</th>
        <th>Plant Name</th>
        <th>PO Name</th>
        <th>Section Name</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    	<?php 
      foreach ($employee_lists as $employee_list) {
         $papl_id = '';
    		 // $plant_name =$employee_list->getPlantname($employee_list->papl->plant_id ?? 0);
         // $details =$employee_list->getAlldata($employee_list->papl->plant_id ?? 0);
         // $details =$employee_list->getPOname($details->po_id ?? 0);
         // echo '<pre>';print_r($details);die();
         //VLP2_UTILITY_O_M
    		 // if ($plant_name) {
           
        
    		?>
      <tr>
        <td><?= $employee_list->papl_id?></td>
        <?php 
          if ($role != 1 && $role != 5) {
            $papl_id=$employee_list->enrolement->enrolement_id;

            ?>
            <td><?= $employee_list->enrolement->adhar_name ?? '' ?></td>
        <td><?= $employee_list->enrolement->designation ?? '' ?></td>
        <td><?= $employee_list->plant->plant_name ?? ''?></td>
        <td><?= $employee_list->purchaseorder->purchase_order_name ?? ''?></td>
        <td><?= $employee_list->section->section_name ?? ''?></td>
            
         <?php }else{
          $papl_id=$employee_list->papl->enrolement_id;
        ?>
        <td><?= $employee_list->papl->adhar_name ?? '' ?></td>
        <td><?= $employee_list->papl->designation ?? '' ?></td>
        <td><?= $employee_list->currentposting->plant->plant_name ?? ''?></td>
        <td><?= $employee_list->currentposting->purchaseorder->purchase_order_name ?? ''?></td>
        <td><?= $employee_list->currentposting->section->section_name ?? ''?></td>
      <?php }?>
        <td >
          <a  href="<?= Url::to('@web/posting-history/create?papl_id='.$employee_list->papl_id) ?>"   data-toggle="tooltip" data-placement="bottom" title="Transfer"><i class="fas fa-exchange-alt"></i></a>
          
          <a  href="<?= Url::to('@web/posting-history/download?papl_id='.$papl_id) ?>" data-toggle="tooltip" data-placement="bottom" title="Download"><i class="fas fa-download"></i></a>

          <a  href="<?= Url::to('@web/enrollment/enrole?papl_id='.$employee_list->papl->enrolement_id) ?>"  data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fas fa-edit" ></i></a>
          
          <button type="button" data-toggle="modal" data-target="#<?php echo $employee_list->papl_id;?>"
                class="fas fa-sign-out-alt" id="master-import"  data-toggle="tooltip" data-placement="bottom" title="Exit"></button>

        </td>
      </tr>
     <?php 
            // }
   }
   ?>
    </tbody>
  </table>
  <?php foreach ($employee_lists as $employee_list) {?>
  <div id="<?php echo $employee_list->papl_id;?>" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Upload Employee Exit Data</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php $masterimportform = ActiveForm::begin(['id' => "exit_employee",'action' => ['posting-history/exit_employee'],'options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="row">
          <div class="col-6">
            <label for="">Employee id</label>
          </div>
          <div class="col-6">
            <input type="text" id="emp_id" name="emp_id" value="<?=$employee_list->papl_id?>">
          </div>
          
        </div>
        <div class="row">
          <div class="col-6">
            <label for="">Exit date</label>
          </div>
          <div class="col-6">
            <input type="date" id="exit_date" name="exit_date" value="" required=true>
          </div>
          
        </div>
        <div class="row">
          <div class="col-6">
            <label for="">Comment</label>
          </div>
          <div class="col-6">
            <textarea  id="comment" name="comment" value="" required=true></textarea>
          </div>
          
        </div>
          <div class="row">
            <div class="col-2">
            <?= Html::submitButton('Submit', ['id'=>'exit','class' => 'btn btn-success']) ?>
          </div>
            
          </div>
          
        <?php ActiveForm::end(); ?>
      </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
    </div>
    </div>
  </div>
</div>
<?php }?>
</div>

</div>
<script type="text/javascript">
  // $('.posting-history-search').append('<div style="" id="loadingDiv"><div class="loader">Loading...</div></div>');
$(document).ready( function() {
  $( "#loadingDiv" ).show();
  setTimeout(removeLoader, 1000); //wait for page load PLUS two seconds.
});
function removeLoader(){
    $( "#loadingDiv" ).fadeOut(500, function() {
      // fadeOut complete. Remove the loading div
      // $( "#loadingDiv" ).remove(); //makes page more lightweight 
      $( "#loadingDiv" ).hide();
  });  
}
</script>
