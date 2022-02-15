<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Plant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="role-form">
    <div class="row">
        
        <div class="col-md-4">
           <?php
           $form = ActiveForm::begin(['action' => ['access/rolebasedaccess'], 'options' => [ 'class' => 'homeform']]);
           ?>
           <select id="user-id" class="form-control basicAutoSelect"  name="User[id]"  onchange="return getroles(this.value)">
                <option value="">Choose User...</option>
                <?php
                foreach ($user as $key => $value) {?>
                  
                  <option value="<?=$key?>" >
                    <?= $value?>
                      
                    </option>
               <?php  }
            ?>
            </select>
       </div>
       <div class="col-md-4">
           <select id="role-id" class="form-control" name="User[role_id]"  >
                <option value="">Choose Roles...</option>
                 <?php
                foreach ($roles as $key => $value) {?>
                  
                  <option value="<?=$key?>" >
                    <?= $value?>
                      
                    </option>
               <?php  }
            ?>
            </select>
       </div>
       <div class="col-md-4">
       <?php 
            if (isset($updated_details_name->username)) {    
   
        ?>
        <span class="badge badge-pill badge-info float-right">Last updated by : <?= $updated_details_name->username ?? '';?></span>
        <?php  } ?>
       </div>
   </div>
   
   <div class="row">
    <div class="col-md-8">
      <div class="form-group field-client">
          <?= Html::submitButton("Update", ['class' => "btn btn-info", 'name' => 'update', 'value' => 'update']); ?>
          <?php  ActiveForm::end();?>
      </div>
  </div>
</div>



</div>
<script>
    $('.basicAutoSelect').select2();
    
</script>
<script type="text/javascript">
   
    function getroles(user_id) {
       
        $.ajax({
          type: 'post',
          url: "<?=Url::to(['access/getroles'])?>",
          data: {
            user_id: user_id
          },
          success: function(data) {
            if (data) {
            var result = JSON.parse(data);
            // $.each(result, function (index, value) {
                // alert(result.id);
                $("#role-id").val(result.id);
                
                               
           // }); 
           
            }
          }
        });
    }
  
</script>

