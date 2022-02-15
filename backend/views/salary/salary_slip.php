<?php
use backend\controllers\CalculationController;
use common\models\SalaryMapping;
use common\models\Salary;
use common\models\MonthlySalary;
use common\models\Deduction;
use yii\helpers\ArrayHelper;
?>
<html><head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style type="text/css">
	html,body{font-size:8px;}
	table, th, td{
		/*border: 1px solid black !important;*/
		/* border-collapse: collapse;*/
		font-size:10px;
		line-height: 10px;
	}
	tr {
		/*border: 1px solid black !important;*/
		/* border-collapse: collapse;*/
	}
	.center{
		text-align: center;
	}
	.boxtd{
		border-bottom: 1px solid #000;
		border-collapse: initial;
	} 
	/*h3{text-align:center;}
	.textbox{border:none; width:200px;}
	th{text-align:left;}
	td{height:25px;}
	 table .table{ border-collapse:collapse;}
	.table tr th{ border-collapse:collapse;  padding:3px;}
	.table tr td{  border-collapse:collapse;  padding:3px;}*/
/*.excelbtn{ position: absolute;z-index:999;top: 215px;right: 42px;*/}

</style>
</head>
<body >
<!-- 	<form id="w0" action="/papl/backend/web/index.php/salary-attribute/pdfdownload" method="post">
    <input type="hidden" name="_csrf" value="aUC7CTeZwb4PB4KBC7o8ufNwbQOw-pYRAdo5jnTDODgkK-5OAPLxintotsh6yWrQlyU5ROKSxENDvEq-K4h5UA==">
		<input type="hidden" name="LocationId" value="3">

		<input type="hidden" name="ClientId" value="13">

		<input type="hidden" name="month" value="05-2021">


		<button type="submit" class="btn btn-info" name="pdfdownload">Generate PDF</button>
	</form>	 -->		
	<div style="width:100%;">
		<div class="slip" style="width:100%; background:#fff;">
			<?php 
			$i=0;
			$sl=1;
			foreach($posting_history as $emp){ 
				$i++; ?>

				<div style="width:100%; page-break-before: always; page-break-after: always; clear: both;"> 
					<?php
				for($slip=1;$slip<=2;$slip++){

					?>
					<table style="width:100%;border: 1px solid #000;">
						<tbody>
							<tr>
								<td colspan="15" class="center"><img src="<?= Yii::getAlias('@backendUrl')."/images/lg.png";?>" style="float: left;
								width: 35px;">
								<p style="margin: 0px;padding: 0px;font-weight: 600;">FORM NO. XV <span> </span> [See Rules 77 (2)(b)]</p>
								<h4 style="font-size:10px; margin-top: 0px; margin-bottom: 0px;font-weight: normal;">Wages-SLIP</h4>
							</td>
						</tr>

						<tr>
							<td colspan="15" class="center"><img src="<?= Yii::getAlias('@backendUrl')."/images/black_logo.png";?>" style="float: left;
							height: 35px;
							width: 35px;">
							<h1 style="font-size:11px; margin-top: 0px; margin-bottom: 0px;">M/s. PRADHAN ASSOCIATES PVT. LTD. </h1>
							<h4 style="font-size:11px; margin-top: 0px; margin-bottom: 0px;">PLOT NO: 126/2262, LANE-1, KHANDAGIRI VIHAR, KHANDAGIRI, BHUBANESWAR-751030, ODISHA, INDIA </h4></td>
						</tr>
						<tr> 
							<td colspan="15" style="line-height:0px;border-top: 1px solid #000;"> &nbsp;</td>
						</tr>
						<tr>

							<td colspan="2"><b>MONTH/YEAR</b></td>
							<td colspan="5"><b><?=date('F - Y', strtotime($date));?></b></td>
							<td colspan="6">&nbsp;</td>
							<td colspan="1">SL NO.</td>
							<td colspan="1"><?=$sl?></td>
						</tr>
						<tr>
							<td colspan="2">PRINCIPAL EMPLOYER</td>
							<td colspan="4"><?=$emp->plant->plant_name;?></td>
							<td colspan="2">&nbsp;</td>
							<td colspan="5">WORKMEN REGISTER SL NO</td>
							<td colspan="2"><?=$emp->employee->workman_sl_no;?></td>
						</tr>
						<tr>
							<td colspan="2">NAME OF WORKMAN</td>
							<td colspan="3"><?=$emp->enrolement->adhar_name;?></td>
							<td colspan="3">FATHER'S/HUSBAND'S NAME</td>
							<td colspan="4"><?=$emp->enrolement->father_husband_name;?>							&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
							</td>
							<td colspan="1">SEX</td>
							<td colspan="2"><?=$emp->enrolement->gender;?></td>
						</tr>
						<tr>
							<td colspan="2">EMPLOYEE ID</td>
							<td colspan="3"><?=$emp->papl_id;?></td>
							<td colspan="3">DESIGNATION</td>
							<td colspan="4"><?=$emp->enrolement->designation;?></td>
							<td colspan="1">DEPARTMENT</td>
							<td colspan="2"><?=$emp->section->section_name; ?></td>
						</tr>
						<tr>
							<td colspan="2">AADHAR NUMBER</td>
							<td colspan="3"><?=$emp->enrolement->adhar_number;?></td>
							<td colspan="3">CATEGORY</td>
							<td colspan="4"><?=$emp->enrolement->category;?></td>
							<td colspan="1">PAN</td>
							<td colspan="2"><?php if($emp->enrolement->document)
                                echo $emp->enrolement->document->pannumber;?></td>
						</tr>
						<tr>
							<td colspan="2">UAN</td>
							<td colspan="3"><?=$emp->enrolement->uan;?></td>
							<td colspan="3">BANK ACCOUNT NO</td>
							<td colspan="4"><?php if($emp->enrolement->bankdetail)
                                echo $emp->enrolement->bankdetail->bank_account_number;?></td>
							<td colspan="1">IFSC CODE </td>
							<td colspan="2"><?php if($emp->enrolement->bankdetail)
                                echo $emp->enrolement->bankdetail->IFSC;?></td>
						</tr>
						<tr>
							<td colspan="2">HEALTH INSURANCE NUMBER</td>
							<td colspan="3">Not found</td>
							<td colspan="3">RATE OF WORKER BASIC SALARY/PIECE-RATE</td>
							<td colspan="4"><?=($emp->basic)? $emp->basic->amount:'N/A';?></td>
							<td colspan="1">ESI NUMBER</td>
							<td colspan="2"><?=$emp->enrolement->esic_ip_number;?></td>
						</tr>
						<tr> 
							<td colspan="15" style="line-height:0px;border-top: 1px solid #000;"> &nbsp;</td>
						</tr>
						<tr>
							<?php $work_done=CalculationController::actionWorkdays($emp->papl_id,$date);  ?>
							<td colspan="2">PRESENT DAYS</td>
							<td colspan="1"><?= $work_done['working_days'];?></td>
							<td colspan="1">LEAVE</td>
							<td colspan="1"><?= $work_done['leave'];?></td>
							<td colspan="1">COFF </td>
							<td colspan="1"><?= $work_done['coff'];?></td>
							<td colspan="1">NH/FH </td>
							<td colspan="1"><?= $work_done['nh_fh'];?></td>
							<td colspan="1">EL</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="1"><?= $work_done['el'];?></td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">TOTAL PAYABLE DAYS</td>
							<td colspan="1"><?= $work_done['tot_paydays'];?></td>
							<td colspan="1">OFF</td>
							<td colspan="1"><?= $work_done['weekly_off'];?></td>
							<td colspan="1">OT HOURS</td>
							<td colspan="1"><?= $work_done['tot_ot_hours'];?></td>
							<td colspan="1">OT DATES</td>
							<td colspan="2">0</td>
							<td colspan="5">&nbsp;</td>
						</tr>
						<tr> 
							<td colspan="15" style="line-height:0px;border-top: 1px solid #000;"> &nbsp;</td>
						</tr>
						<tr>
							<td class="center" colspan="15"><b>EARNINGS</b></td>
						</tr>
						<tr>
							<?php 
							$i=1;
							$salary_master=ArrayHelper::map(Salary::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all(), 'id','attribute_name');
							$total_earning= MonthlySalary::find()->where(['papl_id'=>$emp->papl_id,'plant_id'=>$emp->plant_id,'month_year'=>date('F Y', strtotime($date))])->one();
							if($total_earning){ 
								?>
								<td colspan="2">BASIC AMOUNT</td>
								<td colspan="3"><?=$total_earning['total_basic'];?></td>
								<?php $i++;
								$total_earning['earning_detail']=json_decode($total_earning['earning_detail'],true);
								$total_earning['deduction_detail']=json_decode($total_earning['deduction_detail'],true);
								$i=2;
								foreach($salary_master as $k1=>$v1){
									if($v1!="Basic"){
										?>
										<td colspan="2"><?=strtoupper($v1);?></td>
										<?php
                                        //print_r($total_earning['earning_detail']);
										if(array_key_exists($k1,$total_earning['earning_detail'])){
											?>

											<td colspan="3"><?=round($total_earning['earning_detail'][$k1]);?></td>

											<?php
										}else{
											?>
											<td colspan="3">0</td>
											<?php
										}
										$i++;
										if($i==4){
											$i=1;
											?>
										</tr>
										<tr>
											<?php
										}
									}
								}

							 ?>
							<td colspan="2">OTHER OT AMOUNT</td>
							<td colspan="3"><?=round($total_earning['other_ot_earning'])?></td>
						</tr>
						
						<tr>
							<td colspan="2">OVERTIME AMOUNT</td>
							<td colspan="3"><?php
							$ot_amount=0;
							if($total_earning){ 
								if(array_key_exists('ot_bd',$total_earning['earning_detail'])){
									$ot_amount+=$total_earning['earning_detail']['ot_bd'];
								}
								if(array_key_exists('ot_gs',$total_earning['earning_detail'])){
									$ot_amount+=$total_earning['earning_detail']['ot_gs'];
								}
								if(array_key_exists('ot_gd',$total_earning['earning_detail'])){
									$ot_amount+=$total_earning['earning_detail']['ot_gd'];
								}
								if(array_key_exists('ot_bs',$total_earning['earning_detail'])){
									$ot_amount+=$total_earning['earning_detail']['ot_bs'];
								}
							}
							echo $ot_amount;
						?></td>
						<td colspan="2">MISCELLANEOUS EARNING</td>
						<td colspan="3"><?=round($total_earning['misc_earning'])?></td>
						<td colspan="2">OTHER ALLOWANCE</td>
						<td colspan="3">0.00</td>
					</tr>
						<!-- <tr>
							<td colspan="2">EL AMOUNT</td>
							<td colspan="3">0.00</td>
							
						</tr> -->
						<tr>
							<td colspan="12">&nbsp;</td>
							<td colspan="2"><b>TOTAL EARNING</b></td>
							<td colspan="2"><b><?=round($total_earning['total_salary']);?></b></td>
						</tr>
						<tr> 
							<td colspan="15" style="line-height:0px;border-top: 1px solid #000;"> &nbsp;</td>
						</tr>
						<tr>
							<td class="center" colspan="15"><b>DEDUCTIONS</b></td>
						</tr>
						<tr>
							<?php 
							$deduction_master=ArrayHelper::map(Deduction::find()->where(['is_delete'=>0])->orderBy(['id' => SORT_ASC])->all(), 'id','attribute_name');
							$j=1;
							foreach($deduction_master as $k1=>$v1){
								?>
								<td colspan="2"><?=strtoupper($v1);?></td>
								<?php
								if(array_key_exists($k1,$total_earning['deduction_detail'])){
									?>
									<td colspan="3"><?=round($total_earning['deduction_detail'][$k1]);?></td>
									<?php
								}else{
									?>
									<td colspan="3">0</td>
									<?php
								}
								$j++;
								if($j==4){
									$j=1;
									?>
								</tr>
								<tr>
									<?php
								}
							}
							?>
							
						</tr>
						<tr>
							<td colspan="12">&nbsp;</td>
							<td colspan="2"><b>TOTAL DEDUCTION</b></td>
							<td colspan="1"><b><?=round($total_earning['total_deduction']);?></b></td>
						</tr>
						<tr> 
							<td colspan="15" style="line-height:0px;border-top: 1px solid #000;"> &nbsp;</td>
						</tr>
						<tr>
							<td colspan="12">&nbsp;</td>
							<td colspan="2"><b>NET PAY AMOUNT</b></td>
							<td colspan="1"><b><?=round($total_earning['net_payble']);?></b></td>
						</tr>
						<?php }else{
						?>
							<tr>
							<th colspan="15" style="color: red">
							<b>Salary not evaluated for this month</b>
							</th>
						</tr>
						<?php
						} ?>

						<tr>
							<td colspan="6">This is a computer-generated Payslip. No signature is required.</td>
							<td colspan="3">&nbsp;</td>
							<td colspan="6" style="text-align: right;">Signature of the Employee's/Thumb impression</td>
						</tr>
					</tbody>
				</table>
				<!-- <div style="float:right;width: 100%; text-align: right; font-size:11px; margin-top:5px;">This is a computer-generated Payslip. No signature is required.</div> -->
				<?php 
				if($slip==1){
					
					?>
					<div style="width: 100%;border-top: 1px dotted #000;float: left;margin-top: 20px;margin-bottom: 10px;"> &nbsp; </div>
					<!-----------------------copy--------------->

					<?php
				}elseif($slip==2){
					$sl++;
				}

			}
			?>
				</div>
				<?php
		} ?>
	</div>
</div>

</div>


</body></html>