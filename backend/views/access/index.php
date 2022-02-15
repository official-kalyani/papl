<?php
/* @var $this yii\web\View */
?>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Enrollment */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
.divider {
  position: relative;
  border-bottom: 1px solid #2ca5b9;
  margin-bottom: 30px;
  margin-top: 30px;
  width: 100%;
}
.divider:before {
  position: absolute;
  content: '';
  width: 30px;
  height: 30px;
  border: 1px solid #2ca5b9;
  left: 50%;
  margin-left: -15px;
  top: 50%;
  background: #fff;
  margin-top: -15px;
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
.divider:after {
  position: absolute;
  content: '';
  width: 20px;
  height: 20px;
  border: 1px solid #2ca5b9;
  left: 50%;
  margin-left: -10px;
  top: 50%;
  background: #2ca5b9;
  margin-top: -10px;
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
  .accordion .item {
    border: none;
    margin-bottom: 50px;
    background: none;
}
.t-p{
  color: #333;
  padding: 40px 30px 0px 30px;
}
.accordion .item .item-header h2 button.btn.btn-link {
    background: #333435;
    color: white;
    border-radius: 0px;
    font-family: 'Poppins';
    font-size: 16px;
    font-weight: 400;
    line-height: 2.5;
    text-decoration: none;
}
.accordion .item .item-header {
    border-bottom: none;
    background: transparent;
    padding: 0px;
    margin: 2px;
}

.accordion .item .item-header h2 button {
    color: white;
    font-size: 20px;
    padding: 15px;
    display: block;
    width: 100%;
    text-align: left;
}

.accordion .item .item-header h2 i {
    float: right;
    font-size: 30px;
    color: #fff;
    background-color: #17a2b8;
    width: 60px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 5px;
}
.btn:focus, .btn.focus {
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 0%);
}
button.btn.btn-link.collapsed i {
    transform: rotate(0deg);
}

button.btn.btn-link i {
    transform: rotate(180deg);
    transition: 0.5s;
}
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <?php 
            if (isset($updated_details_name->username)) {    
   
        ?>
        <span class="badge badge-pill badge-info float-right">Last updated by : <?= $updated_details_name->username ?? '';?></span>
        <?php  } ?>
    </div>
    
  </div>
  <div class="row">
    <div class="col-12">
    <div class="form-group">

    <label for="exampleFormControlSelect1">Users</label>
    <?php
    $form = ActiveForm::begin(['action' => ['access/update'], 'options' => [ 'class' => 'homeform']]);

    echo $form->field($model, 'user_id')->dropDownList($user,['prompt' => 'Choose User...','required'=>true,'class' => 'form-control plant','onchange'=>"userDetails(this.value)"])->label(false);

?>
      
  </div>
    </div>
</div>
<div class="row">
  <div class="col-6">
    <table>
      <tr><th>Plant : </th><td id="plant_name" ></td></tr>
      <tr><th>Purchase Order : </th><td id="po_name"></td></tr>
    </table>
  </div>
</div>

<div class="row">
  <div class="col-12">  <label  style="width: 100%;height: auto;float: left; font-weight: bold;" for="exampleFormControlSelect1">Acess List</label> </div>

<div class="col-4">
<div class="accordion" id="accordionExample">
  <div class="item">
     <div class="item-header" id="headingOne">
        <h2 class="mb-0">
           <button class="btn btn-link" type="button" data-toggle="collapse"
              data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
           Access Control
           <i class="fa fa-angle-down"></i>
           </button>
        </h2>
     </div>
     <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
        data-parent="#accordionExample">
        <div class="t-p">
It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters,
        </div>
     </div>
  </div>
 
   
   
</div>
</div>
<div class="divider"></div>
  <div class="row">
      <?php $i=1;$j=1; 
        foreach($acl_master as $k=>$v){ ?>
        <div class="col-4">
          <div class="scrl">
      <ul style="list-style-type:none;">
        <li>
        <input class="form-check-input" type="checkbox" value="<?=$k?>" id="checkall_<?=$j?>">
          <label class="form-check-label" for="defaultCheck1">
              <b><?=$k?></b>
          </label> 
          
          <?php 
              $n=1;
              foreach($v as $lvl2k=>$lvl2v){ 
          ?>
            <ul style="list-style-type:none;">
              <li>
              
              <?php if(is_array($lvl2v)&&!empty($lvl2v)){
                
              ?>
                <input class="form-check-input checkall_<?=$j?>" type="checkbox" value="<?=$lvl2k?>" id="checkall_<?=$j?>_<?=$n?>">
                  <label class="form-check-label" for="defaultCheck1">
                    <b><?=$lvl2k?></b>
                  </label>
                  <ul style="list-style-type:none;">
              <?php
                  
                  foreach($lvl2v[0] as $lvl3k=>$lvl3v){ 
              ?>
                  
                    <li>
                    <input class="form-check-input acl_<?=$j?>_<?=$n?>" type="checkbox" name="acl[<?= $lvl3k?>]" value="<?=$lvl3k?>" id="acl<?=$lvl3k?>">
                    <label class="form-check-label" for="defaultCheck1">
                        <?=$lvl3v?>
                    </label> 
                    
                    </li>
                  
              <?php  }
              ?>
                </ul>
              <?php
                }else{
              ?>
                  <input class="form-check-input acl_<?=$j?>" type="checkbox" name="acl[<?= $lvl2v?>]" value="<?=$lvl2v?>" id="acl<?=$lvl2v?>">
                  <label class="form-check-label" for="defaultCheck1">
                      <?=$lvl2k?>
                  </label>
              <?php
                }
              ?>
              </li>
            </ul>
          <?php $n++; }
          
          ?>
            
        </li>
      </ul>
      </div>
      </div>
      <?php $j++; } ?>
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
   setTimeout(function() {
      $('#aclmapping-user_id').select2();
    
  }, 1000);
  <?php
    if ($uid != '') {?>
        setTimeout(function() {
          $("#aclmapping-user_id").val(<?=$uid?>);
          //$("#select2-aclmapping-user_id-container").val(<?=$uid?>);
          userDetails(<?=$uid?>);
    }, 1000);
<?php }
?>
  function userDetails(uid) {
       //alert(uid);
       $.ajax({url:"<?=Url::toRoute(['access/getuserdetails'])?>?uid="+uid,
            success:function(results)
            {
                if(results)
                { 
                    var useracls=JSON.parse(results);console.log(useracls.user_access_detail);
                    $('input:checkbox').removeAttr('checked');
                    $.each(useracls.user_access_detail, function (index, value) {
                      $('#acl'+value).attr('checked', true);
                    });
                    // var plant=useracls.plant;
                    // var po=useracls.po;
                    // $('#plant_name').html(plant);
                    // $('#po_name').html(po);
                  //console.log(userdetail);
                }
            }
        });
  }

  $('[id^=checkall]').click(function() {
    var acls=$(this).attr('id').substring(9);
    //alert(acls);
    if($(this).prop('checked')){
      $('[class*=checkall_'+acls+']').attr('checked',true);
      $('[class*=acl_'+acls+']').attr('checked',true);
    }else{
      $('[class*=checkall_'+acls+']').attr('checked',false);
      $('[class*=acl_'+acls+']').attr('checked',false);
    }
    
});
</script>
