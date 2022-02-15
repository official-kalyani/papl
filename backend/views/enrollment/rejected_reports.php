<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Enrollment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="enrollment-form">
<?php $form = ActiveForm::begin(['action' => 'javascript:void(0)','options' => ['enctype' => 'multipart/form-data']]); ?>
	<div class="row">
     <div class="col-4">
        <div class="form-group field-enrollment-plant_id required has-error">
            <label class="control-label" for="enrollment-plant_id">Plant</label>
            <select id="enrollment-plant_id" class="form-control" name="Enrollment[plant_id]" required="" aria-required="true" aria-invalid="true">
            <option value="">Choose Plant...</option>            
            <option value="0">All</option>
             <?php
                foreach ($plants as $key => $value) {?>
                  
                  <option value="<?=$key?>" >
                    <?= $value?>
                      
                    </option>
               <?php  }
            ?>
            
            </select>

            
        </div>
       
    </div>   
    </div>
    <div class="row">
        <div class="col-2">
        <?= Html::submitButton('Download', ['id'=>'exportAll','class' => 'btn btn-success']) ?>
    </div>
    <div class="col-2">
        <?= Html::submitButton('View', ['id'=>'exportAll','class' => 'btn btn-success']) ?>
    </div>
    </div>
    
    

    <?php ActiveForm::end(); ?>

</div>
<iframe id="iframeExport" style="position: absolute;width:0;height:0;border:0;"></iframe>
<script>
$(document).ready(function(){
$("#exportAll").click(function(){
   
    var plant_id=$("#enrollment-plant_id").val();
    if(!plant_id){
      alert("Choose Plant !");
      return false;
    }
       $.ajax({url:"download_rejected_report?plant_id="+plant_id,
            success:function(results)
            { 
              $("#iframeExport").attr('src', results);
                      console.log(results);exit;
                    
            }
            
        }); 

      
    
  });
});
</script>
