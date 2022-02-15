<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\controllers\CalculationController;
use yii\helpers\ArrayHelper;
use common\models\SalaryMapping;
use common\models\Deduction;
use common\models\MonthlySalary;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $model common\models\Salary */
?>
<style>
    .form-control{
        font-size: 12px !important;  
    }

    .tableFixHead          { overflow: auto; height: 400px; width: 100%; }
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
<?php
$this->title = Yii::t('app', 'Update Monthly Salary ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Salaries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$form = ActiveForm::begin(['action' => ['salary/salary_sheet/?plant_id='.$plant->plant_id.'&po_id='.$po.'&section_id='.$section.'&date='.$date], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'homeform','onSubmit'=>"return confirm('Do you really want to update the changes in monthly salary?')"]]);

?>
<?php 
    if (isset($updated_details_name->username)) {       
   
?>
<span class="badge-pill badge-info float-right">Last updated by : <?= $updated_details_name->username ?? '';?></span>
<?php  } ?>

    <h2><?= Html::encode($this->title) ?></h2>
<table >
    <tr>
        <th>Location:</th>
        <td><?=$plant->location->location_name;?></td>
    </tr>
    <tr>
        <th>Plant:</th>
        <td><?=$plant->plant_name;?></td>
    </tr>
    <tr>
        <th>Purchase Order:</th>
        <td><?php
            if($pos){
                echo $pos->purchase_order_name;
            }else{
                echo "All";
            }
        ?></td>
    </tr>
    <tr>
        <th>Section Id:</th>
        <td><?php
            if($sections){
                echo $sections->section_name;
            }
            else{
                echo "All";
            }
        ?></td>
    </tr>
    <tr>
        <th>Month Year:</th>
        <td><?=$date;?></td>
    </tr>
</table>
<br/>

<div class="row">
    <div class="col-md-8">
      <div class="form-group field-client">
          <?= Html::submitButton("Update", ['class' => "btn btn-info", 'name' => 'update', 'value' => 'update']); ?>
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
<div style="font-size: 12px;" class="tableFixHead">
<table class="table table-bordered" >
    <tr>
        <thead>
        <th>Emoloyee ID</th>
        <th>Emoloyee Name</th>
        <th>Designation</th>
        <th>Attendance</th>
        <th>EXTRA</th>
        <th>Leaves</th>
        <th>COFF</th>
        <th>NH/FH</th>
        <th>OT-BD</th>
        <th>OT-GS</th>
        <th>OT-GD</th>
        <th>OT-BS</th>
        <th>WO</th>
        <th>Misc Att</th>
        <th>Other OT</th>
        <th>Total Payble Days</th>
        <th>Tot OT</th>
        <th>EL Days</th>
        <th>Basic</th>
        <?php foreach($salary_master as $sal){
                    if($sal->attribute_name!= "Basic"){ 
            ?>
            <th><?php echo $sal->attribute_name;?></th>
        <?php
            }
        }
        ?>
        <th>Daily Rate of Wages/Piece Rate (Gross)</th>
        <th>Tot. Basic</th>
        
        <?php foreach($salary_master as $sal){
                    if($sal->attribute_name!= "Basic"){     
        ?>
            <th><?php echo "Net  ".$sal->attribute_name;?></th>
        <?php
            }
        }
        ?>
        <th>Net OT-BD</th>
        <th>Net OT-GS</th>
        <th>Net OT-GD</th>
        <th>Net OT-BS</th>
        <th>Miscelleneous Earnings</th>
        <th>Other OT Earnings</th>
        <th>Total Amount</th>
        <?php $deduction_master=ArrayHelper::map(Deduction::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all(), 'id','attribute_name');
                foreach($deduction_master as $k1=>$v1){
                ?>
                <th><?= $v1;?></th>
                <?php
                   
                }
        ?>
        <th>Total Deduction</th>
        <th>Net Payble</th>
        <th>LWF Refund</th>
        <th>ESI Refund</th>
        <th>Total Payable</th>
            </thead>   
</tr>
<?php 
   
foreach($posting_history as $emp){ ?>
<tr>
<tbody>
    <th><?php echo $emp->enrolement->papl_id; ?></th>
    <th><?php echo $emp->enrolement->adhar_name; ?></th>
    <th><?php echo $emp->enrolement->designation; ?></th>
    <?php $work_done=CalculationController::actionWorkdays($emp->papl_id,$date); 
        foreach($work_done as $k=>$v){
            
            if($k=="misc_att"){
                $msalary=$emp->getMonthlySalary($plant->plant_id,$emp->papl_id,$date);
                if($msalary)
                    $misc_att=$msalary->misc_att;
                else
                    $misc_att=0;
        ?>
            <td><input type="number" style="width: 70px;"  class="form-control" name="att[<?=$emp->papl_id;?>][<?=$k;?>]" value="<?php echo $misc_att; ?>" min=0></td>
        <?php }elseif($k=="other_ot_hour"){
                $msalary=$emp->getMonthlySalary($plant->plant_id,$emp->papl_id,$date);
                if($msalary)
                    $other_ot_hour=$msalary->other_ot_hour;
                else
                    $other_ot_hour=0;
                    
        ?>
            <td><input type="number" step="0.1" style="width: 70px;"  class="form-control" name="att[<?=$emp->papl_id;?>][<?=$k;?>]" value="<?php echo $other_ot_hour; ?>" min=0></td>
        <?php
            }elseif($k=="el"){
                $el=0;
                ?>
                <td><input type="number" style="width: 70px;"  class="form-control"  value="<?php echo $el; ?>" readonly min=0></td>
        <?php }
            else{
        ?>
            <td><input type="number" style="width: 70px;"  class="form-control"  value="<?php echo $v; ?>" min=0 readonly></td>
        <?php       
            }
        ?>
        
    <?php
        }
        $set_salary= ArrayHelper::map(SalaryMapping::find()->where(['papl_id'=>$emp->papl_id])->all(), 'salary_id','amount');
            foreach($salary_master as $sal){
              if($sal->attribute_name=="Basic"){
                if(isset($set_salary[$sal->id])){
                    $base_salary=$set_salary[$sal->id];
                ?>
                    <td><input type="number" style="width: 85px;"  class="form-control" name="" value="<?php echo $set_salary[$sal->id]; ?>" min=0 readonly></td>
                <?php         
                    break;
                }else{
                    $base_salary=0;
                ?>
                    <td><input type="number" style="width: 85px;"  class="form-control" name="" value="0" min=0 readonly></td>
         
                <?php
                    break;
                }
              }
            }
          
            $daily_gross=0;
            foreach($salary_master as $sal){
              if($sal->attribute_name!="Basic"){
                if(isset($set_salary[$sal->id])){
                    if($sal->type=="amount"){
                    ?>
                        <td><input type="number" style="width: 85px;"  class="form-control" name="" value="<?=$set_salary[$sal->id];?>" min=0 readonly></td>
                        <?php
                        $daily_gross+=$set_salary[$sal->id];
                       
                    }elseif($sal->type=="percentage"){
                    ?>
                        <td><input type="number" style="width: 85px;"  class="form-control" name="" value="<?=($set_salary[$sal->id] / 100) * $base_salary?>" min=0 readonly></td>
             
                    <?php
                       
                        $daily_gross+=($set_salary[$sal->id] / 100) * $base_salary;
                        
                    }
                }else{
                ?>
                    <td><input type="number" style="width: 85px;"  class="form-control" name="" value="0" min=0 readonly></td>
                <?php
                    $daily_gross+=0;
                }
                     
              }
            }
            ?>
        <td><input type="text" style="width: 85px;"  class="form-control" name="" value="<?=$daily_gross+$base_salary;?>" min=0 readonly></td>
        <?php
        $total_earning= MonthlySalary::find()->where(['papl_id'=>$emp->papl_id,'plant_id'=>$emp->plant_id,'month_year'=>date('F Y', strtotime($date))])->one();
        if($total_earning){
            $total_earning['earning_detail']=json_decode($total_earning['earning_detail'],true);
            $total_earning['deduction_detail']=json_decode($total_earning['deduction_detail'],true);
            //print_r($total_earning);
            //echo $total_earning['total_basic'];exit;
        ?>
            <td><input type="number" style="width: 85px;"  class="form-control" name="salary[<?=$emp->papl_id;?>][total_basic]" value="<?=round($total_earning['total_basic'])?>" min=0></td>
        <?php
            
            foreach($salary_master as $sal){
                if($sal->attribute_name!="Basic"){
                    //print_r($total_earning['earning_detail']);
                    if(array_key_exists($sal->id,$total_earning['earning_detail'])){
                    ?>
                    <td><input type="number" style="width: 85px;"  class="form-control" name="salary[<?=$emp->papl_id;?>][<?=$sal->id;?>]"  value="<?=round($total_earning['earning_detail'][$sal->id]);?>" min=0></td>
                        
                    <?php
                        
                    }else{
                    ?>
                    <td><input type="number" style="width: 85px;"  class="form-control" name="salary[<?=$emp->papl_id;?>][<?=$sal->id;?>]" value="0" min=0></td>
                    <?php
                    }
                    
                }
            }
            if(array_key_exists('ot_bd',$total_earning['earning_detail'])){
            ?>
                <td><input type="number" style="width: 85px;"  class="form-control" name="salary[<?=$emp->papl_id;?>][ot_bd]" value="<?=round($total_earning['earning_detail']['ot_bd']);?>" min=0 readonly></td>
            <?php 
            }else{
            ?>
                <td><input type="number" style="width: 85px;"  class="form-control" name="salary[<?=$emp->papl_id;?>][ot_bd]" value="0" min=0 readonly></td>
            <?php
            }

            if(array_key_exists('ot_gs',$total_earning['earning_detail'])){
             ?>
                <td><input type="number" style="width: 85px;"  class="form-control" name="salary[<?=$emp->papl_id;?>][ot_gs]" value="<?=round($total_earning['earning_detail']['ot_gs']);?>" min=0 readonly></td>
            <?php
            }else{
            ?>
                <td><input type="number" style="width: 85px;"  class="form-control" name="salary[<?=$emp->papl_id;?>][ot_gs]" value="0" min=0 readonly></td>
            <?php
            }

            if(array_key_exists('ot_gd',$total_earning['earning_detail'])){
            ?>
                <td><input type="number" style="width: 85px;"  class="form-control" name="salary[<?=$emp->papl_id;?>][ot_gd]" value="<?=round($total_earning['earning_detail']['ot_gd']);?>" min=0 readonly></td>
            <?php 
            }else{
            ?>
                <td><input type="number" style="width: 85px;"  class="form-control" name="salary[<?=$emp->papl_id;?>][ot_gd]" value="0" min=0 readonly></td>
            <?php
            }
            
            if(array_key_exists('ot_bs',$total_earning['earning_detail'])){
            ?>
                <td><input type="number" style="width: 85px;"  class="form-control" name="salary[<?=$emp->papl_id;?>][ot_bs]" value="<?=round($total_earning['earning_detail']['ot_bs']);?>" min=0 readonly></td>
            <?php 
            }else{
            ?>
                <td><input type="number" style="width: 85px;"  class="form-control" name="salary[<?=$emp->papl_id;?>][ot_bs]" value="0" min=0 readonly></td>
            <?php
            }
            ?>
                <td><input type="number" style="width: 85px;"  class="form-control" name="" value="<?=round($total_earning['misc_earning']);?>" min=0 readonly></td>
                <td><input type="number" style="width: 85px;"  class="form-control" name="" value="<?=round($total_earning['other_ot_earning']);?>" min=0 readonly></td>
                <td><input type="number" style="width: 85px;"  class="form-control" name="" value="<?=round($total_earning['total_salary']);?>" min=0 readonly></td>
            <?php
            

            foreach($deduction_master as $k1=>$v1){
                if(array_key_exists($k1,$total_earning['deduction_detail'])){
                ?>
                    <td><input type="text" style="width: 85px;"  class="form-control" name="" value="<?=round($total_earning['deduction_detail'][$k1]);?>" min=0 readonly></td>
                <?php
                }else{
                ?>
                    <td><input type="text" style="width: 85px;"  class="form-control" name="" value="0.00" min=0 readonly></td>
                <?php
                }
                
            }
            ?>
                <td><input type="text" style="width: 85px;"  class="form-control" name="" value="<?=round($total_earning['total_deduction']);?>" min=0 readonly></td>
                <td><input type="text" style="width: 85px;"  class="form-control" name="" value="<?=round($total_earning['net_payble']);?>" min=0 readonly></td>
                <td><input type="text" style="width: 85px;"  class="form-control" name="" value="<?=round($total_earning['lwf_refund']);?>" min=0 readonly></td>
                <td><input type="text" style="width: 85px;"  class="form-control" name="" value="<?=round($total_earning['esi_refund']);?>" min=0 readonly></td>
                <td><input type="text" style="width: 85px;"  class="form-control" name="" value="<?=round($total_earning['total_payble']);?>" min=0 readonly></td>
            <?php
            
        }
        ?>
    </tbody>
</tr>
<?php }?>
</table>
<?php ActiveForm::end(); ?>
  </div>

  <div class="row">
    <div class="col-md-4" style='margin-left: auto;margin-right: 0;text-align: right;'>
        <?php echo LinkPager::widget([
            'pagination' => $pages,
        ]); ?>
    </div>
</div>

  <iframe id="iframeID" style="position: absolute;width:0;height:0;border:0;"></iframe>
<script>
    //Salary slip generate
$("#generate_salary_slip").click(function(){
        
        
        var plant_id="<?=$plant->plant_id?>";
        var po_id="<?=$po?>";
        var section_id="<?=$section?>";
        var month_year="<?=date('F Y', strtotime($date))?>";
      
        window.open("<?=Url::to(['salary/salary_slip'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year, '_blank');
           
        
        return false;  
      });
//Salary slip generate
$("#generate_salary_sheet").click(function(){
        
        
        var plant_id="<?=$plant->plant_id?>";
        var po_id="<?=$po?>";
        var section_id="<?=$section?>";
        var month_year="<?=date('F Y', strtotime($date))?>";
      
        $.ajax({
            url: "<?=Url::to(['calculation/salary_sheet'])?>?plant_id="+plant_id+"&po_id="+po_id+"&section_id="+section_id+"&date="+month_year,
            
            success:function(results)
            { 
                $("#iframeID").attr('src', results);
                //alert(results);
                alert('Salary Sheet Generated for '+month_year );
            }
        });
        return false; 
      });
</script>
