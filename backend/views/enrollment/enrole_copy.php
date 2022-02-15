<?php
/* @var $this yii\web\View */
?>
<?php
// use yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\Enrollment */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
nav>.nav.nav-tabs {

    border: none;
    color: #fff;
    background: #272e38;
    border-radius: 0;

}

nav>div a.nav-item.nav-link,
nav>div a.nav-item.nav-link.active {
    border: none;
    padding: 18px 25px;
    color: #fff;
    background: #125e76;
    border-radius: 0;
}

nav>div a.nav-item.nav-link.active:after {
    content: "";
    position: relative;
    bottom: -60px;
    left: -10%;
    border: 15px solid transparent;
    border-top-color: #0e7c9e;
}

.tab-content {
    background: #fdfdfd;
    line-height: 25px;
    border-left: 0px solid #0e7c9e;
    border-right: 0px solid #0e7c9e;
    border-top: 5px solid #0e7c9e;
    border-bottom: 0px solid #0e7c9e;
    padding: 30px 25px;
}

nav>div a.nav-item.nav-link:hover,
nav>div a.nav-item.nav-link:focus {
    border: none;
    background: #0e7c9e;
    color: #fff;
    border-radius: 0;
    transition: background 0.20s linear;
}

h1.heading {
    font-size: 25px;
    line-height: 20px;
    color: #125e76;
    font-weight: 600;
    width: 100%;
    border-bottom: 1px solid #125e76;
    padding-bottom: 10px;
}

.show {
    display: unset !important;
}
</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <nav>
        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
          <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab"
            aria-controls="nav-home" aria-selected="true">Master</a>
          <a class="nav-item nav-link" id="nav-qualification-tab" data-toggle="tab" href="#nav-qualification"
            role="tab" aria-controls="nav-qualification" aria-selected="false">Qualification</a>
          <a class="nav-item nav-link" id="nav-internal-tab" data-toggle="tab" href="#nav-internal" role="tab"
            aria-controls="nav-internal" aria-selected="false">PAPL Internal </a>
          <a class="nav-item nav-link" id="nav-epf-tab" data-toggle="tab" href="#nav-epf" role="tab"
            aria-controls="nav-epf" aria-selected="false">EPF/ESIC</a>
          <a class="nav-item nav-link" id="nav-bank-tab" data-toggle="tab" href="#nav-bank" role="tab"
            aria-controls="nav-bank" aria-selected="false">Bank Details</a>
          <a class="nav-item nav-link" id="nav-nominee-tab" data-toggle="tab" href="#nav-nominee" role="tab"
            aria-controls="nav-nominee" aria-selected="false">Nominee Details</a>
          <a class="nav-item nav-link" id="nav-family-tab" data-toggle="tab" href="#nav-family" role="tab"
            aria-controls="nav-family" aria-selected="false">Family Details</a>
        </div>
      </nav>
      <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
          <div class="row">
            <div class="col-11" style="float:right;padding-right: 50px;">
              <?php
                echo "<a class='float-right btn btn-success'  download href='".Yii::getAlias('@storageUrl')."/sample/Master.csv'  > Download Samples <span class='fa fa-download' aria-hidden='true'></span></a>";
                 ?>
            </div>
            <div class="col-1" style="float:right;padding-right: 50px;">
              <button type="button" data-toggle="modal" data-target="#master_import_Modal"
                class="btn btn-success" id="master-import" disabled>Import</button>
            </div>
          </div>
          <?php $form = ActiveForm::begin(['action' => ['enrollment/enrole'],'options' => ['enctype' => 'multipart/form-data','class'=> 'homeform']]); ?>
          <?php if(!empty(Yii::$app->request->get('enrolementid')))
            {
              ?>
          <div class="row">
            <div class="col-2">
              <?= $form->field($model, 'enrolement_id')->textInput(['maxlength' => true,'readonly' => 'true']) ?>
            </div>
          </div>
          <?php
            }
            ?>
          <div class="row">
            <div class="col-2">
              <?= $form->field($model, 'plant_id')->dropDownList($plants,['prompt' => 'Choose Plant...','required'=>true]);?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'adhar_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <div class="form-group">
                <?= $form->field($model, 'father_husband_name')->textInput(['maxlength' => true]) ?>
              </div>
            </div>
            <div class="col-2">
               <?= $form->field($model, 'relation_with_employee')->dropdownList([
                'Father' => 'Father', 
                'Husband' => 'Husband'
                ],
                ['prompt'=>'Select relation']
                );?>
              <!-- <?= $form->field($model, 'relation_with_employee')->textInput(['maxlength' => true]) ?> -->
            </div>
            <div class="col-2">
              <?= $form->field($model, 'adhar_number')->textInput(['class' => 'form-control adhar_number','type' => "number"]) ?>
            </div>
            <div class="col-2">
              <?php if ($model->browse_adhar) {?>
              <img src="<?= Yii::getAlias('@storageUrl').$model->browse_adhar?>" height="50px"
                width="60px" alt="<?=$model->browse_adhar?>">
              <?php }else{?>
              <?= $form->field($model, 'browse_adhar')->fileInput(['maxlength' => true,'accept'=>".jpg,.jpeg,.png,.pdf", 'onchange' =>"readURL(this);",]) ?>
              <?php }?>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <?php if ($model->browse_pp_photo) {?>
              <img src="<?= Yii::getAlias('@storageUrl').$model->browse_pp_photo?>" height="50px"
                width="60px" alt="<?=$model->browse_pp_photo?>">
              <?php }else{?>
              <?= $form->field($model, 'browse_pp_photo')->fileInput(['maxlength' => true, 'accept'=>".jpg,.jpeg,.png,.pdf",'onchange' =>"readURL(this);",]) ?>
              <?php }?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'gender')->dropdownList([
                'Male' => 'Male', 
                'Female' => 'Female',
                'Transgender' => 'Transgender'
                ],
                ['prompt'=>'Select gender']
                );?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'marital_status')->dropdownList([
                'Married' => 'Married', 
                'Unmarried' => 'Unmarried',
                'Divorcee' => 'Divorcee'
                ],
                ['prompt'=>'Select Marital Status']
                );?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'mobile_with_adhar')->textInput(['maxlength' => true,'class'=>"form-control mobile_with_adhar"]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'dob')->textInput(['class'=>"form-control dob",'readonly'=>'true']) ?>
              <i class="fas fa-calendar input-prefix" tabindex=0></i>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <h1 class="heading"> Permanent </h1>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <?= $form->field($model, 'permanent_addrs')->textarea(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'permanent_state')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'permanent_district')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'permanent_ps')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'permanent_po')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'permanent_village')->textInput(['maxlength' => true]) ?>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <?= $form->field($model, 'permanent_block')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'permanent_tehsil')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'permanent_GP')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'permanent_pincode')->textInput(['class' => 'form-control permanent_pincode']) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'permanent_mobile_number')->textInput(['maxlength' => true,'class' => 'form-control permanent_mobile_number']) ?>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <h1 class="heading"> Present </h1>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <?= $form->field($model, 'present_address')->textarea(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'present_state')->textInput(['maxlength' => true]);?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'present_district')->textInput(['maxlength' => true]);?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'present_ps')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'present_po')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'present_village')->textInput(['maxlength' => true]) ?>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <?= $form->field($model, 'present_block')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'present_tehsil')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'present_gp')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'present_pincode')->textInput() ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'present_mobile_number')->textInput() ?>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <h1 class="heading"> Employee Information </h1>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <?= $form->field($model, 'blood_group')->textInput(['maxlength' => true]);?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'ID_mark_employee')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'nationality')->textInput(['maxlength' => true,'value' =>'Indian','readonly' => 'true'])
                ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'religion')->textInput();?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'caste')->textInput();?>
            </div>
          </div>
          <div class="row">
            <div class="col-10">
              <?= Html::submitButton("Submit", ['class' => "nav-item nav-link sub", 'name'=>'master', 'value'=>'master']); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-1">
              <!-- <button type="button" class="nav-item nav-link sub qualification" id="qualification">Submit</button> -->
              <!-- <a class="nav-item nav-link sub" id="nav-qualification-tab" data-toggle="tab" href="#nav-qualification" role="tab" aria-controls="nav-qualification" aria-selected="false">Submit</a> -->
            </div>
          </div>
          <?php ActiveForm::end(); ?>
        </div>
        <div class="tab-pane fade" id="nav-qualification" role="tabpanel"
          aria-labelledby="nav-qualification-tab">
          <div class="row">
          <div class="col-11" style="float:right;padding-right: 50px;">
            <button type="button" id="qualificationSample" class="float-right btn btn-success"><span class='fa fa-download' aria-hidden='true'>Download Sample</span></button>
          </div>
			 
          <div class="col-1" style="float:right;padding-right: 50px;" >
            <button type="button"  data-toggle="modal" data-target="#qualification_import_Modal" class="btn btn-success">Import</button>
          </div>
        </div>
          <?php $form = ActiveForm::begin(['action' => ['enrollment/qualification'],'options' => ['enctype' => 'multipart/form-data']]); ?>
          <div id="dynamic_field">
            <input type="hidden" name="enrolementid" value="<?=$enrolmentid?>">
            <?php
              foreach($model->qualifications as $qualification)
              {
                ?>
            <div class="row">
              <div class="col-2">
                <div class="form-group field-qualification-university_name">
                  <label class="control-label"
                    for="qualification-university_name">University/Board</label>
                  <input type="text" id="qualification-university_name" class="form-control"
                    name="Qualification[university_name][]"
                    value="<?=$qualification->university_name?>">
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group field-qualification-college_name">
                  <label class="control-label" for="qualification-college_name">College/Institute
                  Name</label>
                  <input type="text" id="qualification-college_name" class="form-control"
                    name="Qualification[college_name][]" value="<?=$qualification->college_name?>">
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group field-qualification-year_of_passout">
                  <label class="control-label" for="qualification-year_of_passout">Year Of
                  Passout</label>
                  <input type="text" id="qualification-year_of_passout" class="form-control"
                    name="Qualification[year_of_passout][]"
                    value="<?=$qualification->year_of_passout?>">
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group field-qualification-division_percent">
                  <label class="control-label"
                    for="qualification-division_percent">Division/Percent</label>
                  <input type="text" id="qualification-division_percent" class="form-control"
                    name="Qualification[division_percent][]"
                    value="<?=$qualification->division_percent?>">
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group field-qualification-highest_qualification">
                  <label class="control-label"
                    for="qualification-highest_qualification">Qualification</label>
                  <input type="text" id="qualification-highest_qualification" class="form-control"
                    name="Qualification[highest_qualification][]"
                    value="<?=$qualification->highest_qualification?>">
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="col-2 removeParent">
                <button type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <?php
              }
              ?>
            <!--sample data start for qualification -->
            <div class="row" id="sampleQualification">
              <div class="col-2">
                <div class="form-group field-qualification-university_name">
                  <label class="control-label"
                    for="qualification-university_name">University/Board</label>
                  <input type="text" id="qualification-university_name" class="form-control"
                    name="Qualification[university_name][]">
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group field-qualification-college_name">
                  <label class="control-label" for="qualification-college_name">College/Institute
                  Name</label>
                  <input type="text" id="qualification-college_name" class="form-control"
                    name="Qualification[college_name][]">
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group field-qualification-year_of_passout">
                  <label class="control-label" for="qualification-year_of_passout">Year Of
                  Passout</label>
                  <input type="text" id="qualification-year_of_passout" class="form-control"
                    name="Qualification[year_of_passout][]">
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group field-qualification-division_percent">
                  <label class="control-label"
                    for="qualification-division_percent">Division/Percent</label>
                  <input type="text" id="qualification-division_percent" class="form-control"
                    name="Qualification[division_percent][]">
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group field-qualification-highest_qualification">
                  <label class="control-label"
                    for="qualification-highest_qualification">Qualification</label>
                  <input type="text" id="qualification-highest_qualification" class="form-control"
                    name="Qualification[highest_qualification][]">
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="col-2 removeParent">
                <button type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <!--sample data end for qualification -->
          </div>
          <div class="row">
            <div class="col-2">
              <div class="form-group">
                <label for="exampleInputEmail1"> </label>
                <button type="button" name="add" id="add" class="btn btn-primary">Add More</button>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-1">
              <?= Html::submitButton("Submit", ['class' => "nav-item nav-link sub", 'name'=>'master', 'value'=>'master']); ?>
              <?php ActiveForm::end(); ?>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="nav-internal" role="tabpanel" aria-labelledby="nav-internal-tab">
        <div class="row">
          <div class="col-11" style="float:right;padding-right: 50px;">
            <button type="button" id="internalSample" class="float-right btn btn-success"><span class='fa fa-download' aria-hidden='true'>Download Sample</span></button>
          </div>
			 
          <div class="col-1" style="float:right;padding-right: 50px;" >
            <button type="button"  data-toggle="modal" data-target="#internal_import_Modal" class="btn btn-success">Import</button>
          </div>
        </div>
          <?php $form = ActiveForm::begin(['action' => ['enrollment/internal'],'options' => ['enctype' => 'multipart/form-data']]); ?>
          <input type="hidden" name="enrolementid" value="<?=$enrolmentid?>">
          <div class="row">
            <div class="col-2">
              <?= $form->field($model, 'designation')->textInput() ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'category')->textInput() ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'PAPLdesignation')->textInput() ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'experience')->textInput() ?>
            </div>
            <div class="col-2">
              <?php if ($model->browse_experience) {
              $ext = pathinfo($model->browse_experience, PATHINFO_EXTENSION);
                if ($ext == 'pdf') {?>

               <iframe src="<?= Yii::getAlias('@storageUrl').$model->browse_experience?>" height="50px"
                width="60px"></iframe>
                <?php
                }else{?>
                  <img src="<?= Yii::getAlias('@storageUrl').$model->browse_experience?>" height="50px"
                width="60px" alt="<?=$model->browse_experience?>">
                <?php 
              }
                ?>
                
              
              <?php }else{?>
              <?= $form->field($model, 'browse_experience')->fileInput(['maxlength' => true, 'accept'=>".jpg,.jpeg,.png,.pdf",'onchange' =>"readURL(this);",]) ?>
              <?php }?>
              
              
            </div>
          </div>
          <div class="row">
            <?= Html::submitButton("Submit", ['class' => "nav-item nav-link sub", 'name'=>'internal', 'value'=>'internal']); ?>
            <!-- <div class="col-1"> <a class="nav-item nav-link sub" id="nav-epf-tab" data-toggle="tab" href="#nav-epf" role="tab" aria-controls="nav-epf" aria-selected="false">Submit</a> </div> -->
            <div class="col-11" style="float:right;padding-right: 50px;">
            </div>
          </div>
          <?php ActiveForm::end(); ?>
        </div>
        <div class="tab-pane fade" id="nav-epf" role="tabpanel" aria-labelledby="nav-epf-tab">
        <div class="row">
          <div class="col-11" style="float:right;padding-right: 50px;">
            <button type="button" id="epfSample" class="float-right btn btn-success"><span class='fa fa-download' aria-hidden='true'>Download Sample</span></button>
          </div>
			 
          <div class="col-1" style="float:right;padding-right: 50px;" >
            <button type="button"  data-toggle="modal" data-target="#epf_import_Modal" class="btn btn-success">Import</button>
          </div>
        </div>
		  <?php $form = ActiveForm::begin(['action' => ['enrollment/epf'],'options' => ['enctype' => 'multipart/form-data']]); ?>
          <input type="hidden" name="enrolementid" value="<?=$enrolmentid?>">
          <div class="row">
            <div class="col-2">
              <?= $form->field($model, 'esic_code')->textInput() ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'esic_ip_number')->textInput() ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'wca_gpa')->textInput() ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'wca_gpa_expire_date')->textInput(['required' => 'true','readonly' => 'true']) ?>
              <i class="fas fa-calendar input-prefix" tabindex=0></i>
            </div>
            <div class="col-2">
               <?php if ($model->esic_sheet) {
              $ext = pathinfo($model->esic_sheet, PATHINFO_EXTENSION);
                if ($ext == 'pdf') {?>

               <iframe src="<?= Yii::getAlias('@storageUrl').$model->esic_sheet?>" height="50px"
                width="60px"></iframe>
                <?php
                }else{?>
                  <img src="<?= Yii::getAlias('@storageUrl').$model->esic_sheet?>" height="50px"
                width="60px" alt="<?=$model->esic_sheet?>">
                <?php 
              }
                ?>
                
              
              <?php }else{?>
              <?= $form->field($model, 'esic_sheet')->fileInput(['maxlength' => true, 'accept'=>".jpg,.jpeg,.png,.pdf",'onchange' =>"readURL(this);",]) ?>
              <?php }?>
            </div>
            <!-- <div class="col-2">
              <?= $form->field($model, 'esic_sheet')->fileInput(['maxlength' => true,'accept'=>".jpg,.jpeg,.png,.pdf", 'onchange' =>"readURL(this);",]) ?>
            </div> -->
          </div>
          <div class="row">
            <div class="col-12">
              <h1 class="heading"> PF Account </h1>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <?= $form->field($model, 'pf_code')->textInput() ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'uan')->textInput() ?>
            </div>
            <div class="col-2">
              <?= $form->field($model, 'pf_account_number')->textInput() ?>
            </div>
            <div class="col-2">
               <?php if ($model->uan_sheet) {
              $ext = pathinfo($model->uan_sheet, PATHINFO_EXTENSION);
                if ($ext == 'pdf') {?>

               <iframe src="<?= Yii::getAlias('@storageUrl').$model->uan_sheet?>" height="150px"
                width="160px"></iframe>
                <?php
                }else{?>
                  <img src="<?= Yii::getAlias('@storageUrl').$model->uan_sheet?>" height="50px"
                width="60px" alt="<?=$model->uan_sheet?>">
                <?php 
              }
                ?>
                
              
              <?php }else{?>
              <?= $form->field($model, 'uan_sheet')->fileInput(['maxlength' => true, 'accept'=>".jpg,.jpeg,.png,.pdf",'onchange' =>"readURL(this);",]) ?>
              <?php }?>
              
            </div>
            <!-- <div class="col-2">
              <?= $form->field($model, 'uan_sheet')->fileInput(['maxlength' => true,'accept'=>".jpg,.jpeg,.png,.pdf", 'onchange' =>"readURL(this);",]) ?>
            </div> -->
          </div>
          <div class="row">
            <div class="col-1">
              <?= Html::submitButton("Submit", ['class' => "nav-item nav-link sub", 'name'=>'epf', 'value'=>'epf']); ?>
              <!--  <a class="nav-item nav-link sub" id="nav-bank-tab" data-toggle="tab" href="#nav-bank" role="tab" aria-controls="nav-bank" aria-selected="false">Submit</a>  -->
            </div>
            <div class="col-11" style="float:right;padding-right: 50px;">
            </div>
          </div>
          <?php $form = ActiveForm::end(); ?>
        </div>
        <div class="tab-pane fade" id="nav-bank" role="tabpanel" aria-labelledby="nav-bank-tab">
        <div class="row">
              <div class="col-11" style="float:right;padding-right: 50px;">
                <button type="button" id="bankSample" class="float-right btn btn-success"><span class='fa fa-download' aria-hidden='true'>Download Samples</span></button>
				  
              </div>
			 
          <div class="col-1" style="float:right;padding-right: 50px;" >
            <button type="button"  data-toggle="modal" data-target="#bank_import_Modal" class="btn btn-success">Import</button>
            
                </div>
        </div>
          <?php $form = ActiveForm::begin(['action' => ['enrollment/bank'],'options' => ['enctype' => 'multipart/form-data']]); ?>
          <input type="hidden" name="enrolementid" value="<?=$enrolmentid?>">
          <div class="row">
            <div class="col-2">
              <?= $form->field($bank_model, 'name_bank')->textInput(['maxlength' => true,'value' => isset($model->bankdetail->name_bank) ? $model->bankdetail->name_bank : '']) ?>
            </div>
            <div class="col-2">
              <?= $form->field($bank_model, 'name_passbook')->textInput(['maxlength' => true,'value' => isset($model->bankdetail->name_passbook) ? $model->bankdetail->name_passbook : '']) ?>
            </div>
            <div class="col-2">
              <?= $form->field($bank_model, 'IFSC')->textInput(['maxlength' => true,'value' =>isset($model->bankdetail->IFSC) ? $model->bankdetail->IFSC : '' ]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($bank_model, 'bank_account_number')->textInput(['maxlength' => true,'value' =>isset($model->bankdetail->bank_account_number) ? $model->bankdetail->bank_account_number : '' ]) ?>
            </div>
            <div class="col-2">
              <?= $form->field($bank_model, 'name_branch')->textInput(['maxlength' => true,'value' =>isset($model->bankdetail->name_branch) ? $model->bankdetail->name_branch : '']) ?>
            </div>
            <div class="col-2">
               <?php 
              // echo '<pre>';print_r($model->bankdetail->pass_book_photo);
               if ($model->bankdetail->pass_book_photo) {
              $ext = pathinfo($model->bankdetail->pass_book_photo, PATHINFO_EXTENSION);
                if ($ext == 'pdf') {?>

               <iframe src="<?= Yii::getAlias('@storageUrl').$model->bankdetail->pass_book_photo?>" height="50px"
                width="60px"></iframe>
                <?php
                }else{?>
                  <img src="<?= Yii::getAlias('@storageUrl').$model->bankdetail->pass_book_photo?>" height="50px"
                width="60px" alt="<?=$model->bankdetail->pass_book_photo?>">
                <?php 
              }
                ?>
                
              
              <?php }else{?>
              <?= $form->field($bank_model, 'pass_book_photo')->fileInput(['maxlength' => true, 'accept'=>".jpg,.jpeg,.png,.pdf",'onchange' =>"readURL(this);",]) ?>
              <?php }?>
            </div>

            <!-- <div class="col-2">
              <div class="form-group">
                <?= $form->field($bank_model, 'pass_book_photo')->fileInput(['maxlength' => true,'accept'=>".jpg,.jpeg,.png,.pdf",'onchange' =>"readURL(this);",'value' =>isset($model->bankdetail->pass_book_photo) ? $model->bankdetail->pass_book_photo : '' ]) ?>
              </div>
            </div> -->
            <div class="col-2">
              <?= $form->field($bank_model, 'transaction_id')->textInput(['maxlength' => true,'value' =>isset($model->bankdetail->transaction_id) ? $model->bankdetail->transaction_id : '' ]) ?>
            </div>
          </div>
          <div class="row">
            <div class="col-1">
              <?= Html::submitButton("Submit", ['class' => "nav-item nav-link sub", 'name'=>'bank', 'value'=>'bank']); ?>
            </div>
            <div class="col-11" style="float:right;padding-right: 50px;">
            </div>
          </div>
          <?php $form = ActiveForm::end(); ?>
          <?php $form = ActiveForm::begin(['action' => ['enrollment/document'],'options' => ['enctype' => 'multipart/form-data']]); ?>
          <input type="hidden" name="enrolementid" value="<?=$enrolmentid?>">
          <div class="row">
            <div class="col-12">
              <h1 class="heading"> Document </h1>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <?= $form->field($document_model, 'voter_id_number')->textInput(['maxlength' => true,'value' => isset($model->document->voter_id_number) ? $model->document->voter_id_number : '']) ?>
            </div>
            <div class="col-2">
               <?php if ($model->document->voter_copy_photo) {
              $ext = pathinfo($model->document->voter_copy_photo, PATHINFO_EXTENSION);
                if ($ext == 'pdf') {?>

               <iframe src="<?= Yii::getAlias('@storageUrl').$model->document->voter_copy_photo?>" height="50px"
                width="60px"></iframe>
                <?php
                }else{?>
                  <img src="<?= Yii::getAlias('@storageUrl').$model->document->voter_copy_photo?>" height="50px"
                width="60px" alt="<?=$model->document->voter_copy_photo?>">
                <?php 
              }
                ?>
                
              
              <?php }else{?>
              <?= $form->field($document_model, 'voter_copy_photo')->fileInput(['maxlength' => true, 'accept'=>".jpg,.jpeg,.png,.pdf",'onchange' =>"readURL(this);",]) ?>
              <?php }?>
            </div>

            <!-- <div class="col-2">
              <?= $form->field($document_model, 'voter_copy_photo')->fileInput(['maxlength' => true,'accept'=>".jpg,.jpeg,.png,.pdf",'onchange' =>"readURL(this);",'value' => isset($model->document->voter_copy_photo) ? $model->document->voter_copy_photo : '']) ?>
            </div> -->
            <div class="col-2">
              <?= $form->field($document_model, 'dl_number')->textInput(['maxlength' => true,'value' =>  isset($model->document->dl_number) ? $model->document->dl_number : '']) ?>
            </div>
            <div class="col-2">
              <div class="form-group">
                <?= $form->field($document_model, 'dl_expiry_date')->textInput(['readonly'=>'true','value' => isset($model->document->dl_expiry_date) ? $model->document->dl_expiry_date : '']) ?>
              </div>
            </div>
            <div class="col-2">
               <?php if ($model->document->drivinglicense_photo) {
              $ext = pathinfo($model->document->drivinglicense_photo, PATHINFO_EXTENSION);
                if ($ext == 'pdf') {?>

               <iframe src="<?= Yii::getAlias('@storageUrl').$model->document->drivinglicense_photo?>" height="50px"
                width="60px"></iframe>
                <?php
                }else{?>
                  <img src="<?= Yii::getAlias('@storageUrl').$model->document->drivinglicense_photo?>" height="50px"
                width="60px" alt="<?=$model->document->drivinglicense_photo?>">
                <?php 
              }
                ?>
                
              
              <?php }else{?>
              <?= $form->field($document_model, 'drivinglicense_photo')->fileInput(['maxlength' => true, 'accept'=>".jpg,.jpeg,.png,.pdf",'onchange' =>"readURL(this);",]) ?>
              <?php }?>
            </div>
            <!-- <div class="col-2">
              <?= $form->field($document_model, 'drivinglicense_photo')->fileInput(['maxlength' => true,'accept'=>".jpg,.jpeg,.png,.pdf", 'onchange' =>"readURL(this);",]) ?>
            </div> -->
            <div class="col-2">
              <?= $form->field($document_model, 'pannumber')->textInput(['maxlength' => true,'value' => isset($model->document->pannumber) ? $model->document->pannumber : '']) ?>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
               <?php if ($model->document->pan_photo) {
              $ext = pathinfo($model->document->pan_photo, PATHINFO_EXTENSION);
                if ($ext == 'pdf') {?>

               <iframe src="<?= Yii::getAlias('@storageUrl').$model->document->pan_photo?>" height="50px"
                width="60px"></iframe>
                <?php
                }else{?>
                  <img src="<?= Yii::getAlias('@storageUrl').$model->document->pan_photo?>" height="50px"
                width="60px" alt="<?=$model->document->pan_photo?>">
                <?php 
              }
                ?>
                
              
              <?php }else{?>
              <?= $form->field($document_model, 'pan_photo')->fileInput(['maxlength' => true, 'accept'=>".jpg,.jpeg,.png,.pdf",'onchange' =>"readURL(this);",]) ?>
              <?php }?>
            </div>
          
            <div class="col-2">
              <?= $form->field($document_model, 'passportnumber')->textInput(['maxlength' => true,'value' => isset($model->document->passportnumber) ? $model->document->passportnumber : '','placeholder' => 'e.g. J1234567']) ?>
            </div>
            <div class="col-2">
              <div class="form-group">
                <?= $form->field($document_model, 'passport_expirydate')->textInput(['value' => isset($model->document->passport_expirydate) ? $model->document->passport_expirydate : '','readonly'=>'true']) ?>
                <i class="fas fa-calendar input-prefix" tabindex=0></i>
              </div>
            </div>
            <div class="col-2">
               <?php if ($model->document->passport_photo) {
              $ext = pathinfo($model->document->passport_photo, PATHINFO_EXTENSION);
                if ($ext == 'pdf') {?>

               <iframe src="<?= Yii::getAlias('@storageUrl').$model->document->passport_photo?>" height="50px"
                width="60px"></iframe>
                <?php
                }else{?>
                  <img src="<?= Yii::getAlias('@storageUrl').$model->document->passport_photo?>" height="50px"
                width="60px" alt="<?=$model->document->passport_photo?>">
                <?php 
              }
                ?>
                
              
              <?php }else{?>
              <?= $form->field($document_model, 'passport_photo')->fileInput(['maxlength' => true, 'accept'=>".jpg,.jpeg,.png,.pdf",'onchange' =>"readURL(this);",]) ?>
              <?php }?>
            </div>
           
          </div>
          <div class="row">
            <div class="col-1">
              <?= Html::submitButton("Submit", ['class' => "nav-item nav-link sub", 'name'=>'document', 'value'=>'document']); ?>
            </div>
            <div class="col-11" style="float:right;padding-right: 50px;">
            </div>
          </div>
          <?php $form = ActiveForm::end(); ?>
        </div>
        <div class="tab-pane fade" id="nav-nominee" role="tabpanel" aria-labelledby="nav-nominee-tab">
        <div class="row">
          <div class="col-11" style="float:right;padding-right: 50px;">
            <button type="button" id="nomineeSample" class="float-right btn btn-success"><span class='fa fa-download' aria-hidden='true'>Download Sample</span></button>
          </div>
			 
          <div class="col-1" style="float:right;padding-right: 50px;" >
            <button type="button"  data-toggle="modal" data-target="#nominee_import_Modal" class="btn btn-success">Import</button>
          </div>
        </div>
          <?php $form = ActiveForm::begin(['action' => ['enrollment/nominee'],'options' => ['enctype' => 'multipart/form-data']]); ?>
          <input type="hidden" name="enrolementid" value="<?=$enrolmentid?>">
          <div class="row">
            <div class="col-12">
              <h1 class="heading"> Nominee </h1>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <?= $form->field($nominee_model, 'nominee_name')->textInput(['maxlength' => true,'value' => isset($model->nominee->nominee_name) ? $model->nominee->nominee_name : '']) ?>
            </div>
            <div class="col-2">
              <?= $form->field($nominee_model, 'nominee_relation')->textInput(['maxlength' => true,'value' => isset($model->nominee->nominee_relation) ? $model->nominee->nominee_relation : '']) ?>
            </div>
            <div class="col-2">
              <div class="form-group">
                <?= $form->field($nominee_model, 'nominee_dob')->textInput(['readonly'=>'true','value' => isset($model->nominee->nominee_dob) ? $model->nominee->nominee_dob : '']) ?>
                <i class="fas fa-calendar input-prefix" tabindex=0></i>
              </div>
            </div>
            <div class="col-2">
              <?= $form->field($nominee_model, 'nominee_adhar_number')->textInput(['maxlength' => true,'value' => isset($model->nominee->nominee_adhar_number) ? $model->nominee->nominee_adhar_number : '','class' => 'form-control nominee_adhar_number','type' => 'number']) ?>
            </div>
            <div class="col-2">
               <?php if ($model->nominee->nominee_adhar_photo) {
              $ext = pathinfo($model->nominee->nominee_adhar_photo, PATHINFO_EXTENSION);
                if ($ext == 'pdf') {?>

               <iframe src="<?= Yii::getAlias('@storageUrl').$model->nominee->nominee_adhar_photo?>" height="50px"
                width="60px"></iframe>
                <?php
                }else{?>
                  <img src="<?= Yii::getAlias('@storageUrl').$model->nominee->nominee_adhar_photo?>" height="50px"
                width="60px" alt="<?=$model->nominee->nominee_adhar_photo?>">
                <?php 
              }
                ?>
                
              
              <?php }else{?>
              <?= $form->field($nominee_model, 'nominee_adhar_photo')->fileInput(['maxlength' => true, 'accept'=>".jpg,.jpeg,.png,.pdf",'onchange' =>"readURL(this);",]) ?>
              <?php }?>
            </div>
           
           
            <div class="col-2">
              <?= $form->field($nominee_model, 'nominee_address')->textArea(['maxlength' => true,'value' => isset($model->nominee->nominee_address) ? $model->nominee->nominee_address : '']) ?>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <?= Html::submitButton("Submit", ['class' => "nav-item nav-link sub", 'name'=>'nominee', 'value'=>'nominee']); ?>
            </div>
          </div>
          <?php $form = ActiveForm::end(); ?>
        </div>
        <div class="tab-pane fade" id="nav-family" role="tabpanel" aria-labelledby="nav-family-tab">
        <div class="row">
          <div class="col-11" style="float:right;padding-right: 50px;">
            <button type="button" id="familySample" class="float-right btn btn-success"><span class='fa fa-download' aria-hidden='true'>Download Sample</span></button>
          </div>
			 
          <div class="col-1" style="float:right;padding-right: 50px;" >
            <button type="button"  data-toggle="modal" data-target="#family_import_Modal" class="btn btn-success">Import</button>
          </div>
        </div>
          <?php $form = ActiveForm::begin(['action' => ['enrollment/family'],'options' => ['enctype' => 'multipart/form-data']]); ?>
          
          <div class="row">
            <div class="col-12">
              <h1 class="heading"> Family Member </h1>
            </div>
          </div>
           
           <div id="dynamic_fieldf">
            <input type="hidden" name="enrolementid" value="<?=$enrolmentid?>">
            <?php
              foreach($model->families as $family)
              {
                ?>
              <div class="row">
                <div class="col-2">
                  <div class="form-group field-family-family_member">
                      <label class="control-label" for="family-family_member">Family Member</label>
                      <input type="text" id="family-family_member" class="form-control" name="Family[family_member][]" value="<?=$family->family_member?>" maxlength="255">

                      <div class="help-block"></div>
                    </div>    
                </div>
                <div class="col-2">
                    <div class="form-group field-family-family_member_dob has-success">
                    <label class="control-label" for="family-family_member_dob">Family Member Dob</label>
                    <input type="text"  class="form-control family-family_member_dob" name="Family[family_member_dob][]" value="<?=$family->family_member_dob?>" aria-invalid="false">
                    <div class="help-block"></div>
                  </div> 
                </div>
                <div class="col-2">
                    <div class="form-group field-family-family_member_relation">
                        <label class="control-label" for="family-family_member_relation">Family Member Relation</label>
                        <input type="text" id="family-family_member_relation" class="form-control" name="Family[family_member_relation][]" value="<?=$family->family_member_relation?>" maxlength="255" aria-invalid="false">
                      <div class="help-block"></div>
                    </div>                
                </div>
                <div class="col-2">
                  <div class="form-group field-family-family_member_residence">
                      <label class="control-label" for="family-family_member_residence">Family Member Residence</label>
                      <input type="text" id="family-family_member_residence" class="form-control" name="Family[family_member_residence][]" value="<?=$family->family_member_residence?>" maxlength="255">
                      <div class="help-block"></div>
                  </div>                
                </div>
                <div class="col-2">
                  <div class="form-group field-family-family_nominee_adhar_photo">
                     <?php if ($family->family_nominee_adhar_photo) {
                      $ext = pathinfo($family->family_nominee_adhar_photo, PATHINFO_EXTENSION);
                        if ($ext == 'pdf') {?>

                       <iframe src="<?= Yii::getAlias('@storageUrl').$family->family_nominee_adhar_photo?>" height="50px"
                        width="60px"></iframe>
                        <?php
                        }else{?>
                          <img src="<?= Yii::getAlias('@storageUrl').$family->family_nominee_adhar_photo?>" height="50px"
                        width="60px" alt="<?=$family->family_nominee_adhar_photo?>">
                        <?php 
                      }
                        ?>
                
              
                      <?php }else{?>
                      <label class="control-label" for="family-family_nominee_adhar_photo">Family Nominee Adhar Photo</label>
                      <input type="hidden" name="Family[family_nominee_adhar_photo][]" value="">
                      <input type="file" id="family-family_nominee_adhar_photo" name="Family[family_nominee_adhar_photo][]" value="" maxlength="255" onchange="readURL(this);" accept=".jpg,.jpeg,.png,.pdf">
                        <div class="help-block"></div>
                      <?php }?>
                  </div>
                </div>
                <!--  -->
                <div class="col-2">
                  <div class="form-group field-family-family_nominee_adhar_photo">
                    <label class="control-label" for="family-family_nominee_adhar_photo">Family Nominee Adhar Photo</label>
                    <input type="hidden" name="Family[family_nominee_adhar_photo][]" value="">
                    <input type="file" id="family-family_nominee_adhar_photo" name="Family[family_nominee_adhar_photo][]" value="" maxlength="255" onchange="readURL(this);" accept=".jpg,.jpeg,.png,.pdf">
                      <div class="help-block"></div>
                  </div>                
                </div>
                
                <div class="col-2 removeParentfamily">
                  <button type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              </div>
            <?php }?>
          <!--sample data start for Family -->
            <div class="row" id="sampleFamily">
              <div class="col-2">
                  <div class="form-group field-family-family_member">
                      <label class="control-label" for="family-family_member">Family Member</label>
                      <input type="text" id="family-family_member" class="form-control" name="Family[family_member][]" value="" maxlength="255">

                      <div class="help-block"></div>
                  </div>    
              </div>
              <div class="col-2">
                  <div class="form-group field-family-family_member_dob ">
                    <label class="control-label" for="family-family_member_dob">Family Member Dob</label>
                    <input type="text"  class="form-control family-family_member_dob" name="Family[family_member_dob][]"  aria-invalid="false" readonly="true">
                    <div class="help-block"></div>
                  </div> 
              </div>
              <div class="col-2">
                <div class="form-group field-family-family_member_relation">
                  <label class="control-label" for="family-family_member_relation">Family Member Relation</label>
                  <input type="text" id="family-family_member_relation" class="form-control" name="Family[family_member_relation][]" value="" maxlength="255">
                  <div class="help-block"></div>
                </div>            
              </div>
              <div class="col-2">
                <div class="form-group field-family-family_member_residence has-success">
                  <label class="control-label" for="family-family_member_residence">Family Member Residence</label>
                  <input type="text" id="family-family_member_residence" class="form-control" name="Family[family_member_residence][]" value="" maxlength="255" aria-invalid="false">
                  <div class="help-block"></div>
                </div>            
              </div>
              <div class="col-2">
                <div class="form-group field-family-family_nominee_adhar_photo">
                    <label class="control-label" for="family-family_nominee_adhar_photo">Family Nominee Adhar Photo</label>
                    <input type="hidden" name="Family[family_nominee_adhar_photo]" value=""><input type="file" id="family-family_nominee_adhar_photo" name="Family[family_nominee_adhar_photo][]" value="" maxlength="255" onchange="readURL(this);" accept=".jpg,.jpeg,.png,.pdf" multiple>
                    <div class="help-block"></div>
                </div>            
              </div>
              <div class="col-2 removeParentfamily">
                <button type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <!--sample data end for qualification -->
            </div>
            <!-- Dynamic id -->
            <div class="row">
            <div class="col-2">
              <div class="form-group">
                <label for="exampleInputEmail1"> </label>
                <button type="button" name="add" id="addf" class="btn btn-primary">Add More</button>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <?= Html::submitButton("Submit", ['class' => "nav-item nav-link sub", 'name'=>'family', 'value'=>'family']); ?>
            </div>
          </div>
          <?php $form = ActiveForm::end(['options' => ['enctype' => 'multipart/form-data']]); ?>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>

<!-- Master Data Import Modal -->
<div id="master_import_Modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				
				<h4 class="modal-title">Upload Master Data</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php $masterimportform = ActiveForm::begin(['id' => "master_import_form",'action' => 'javascript:void(0)','options' => ['enctype' => 'multipart/form-data']]); ?>
					<div class="col-6">
						<?= $masterimportform->field($model, 'import_file' )->fileInput(['maxlength' => true,'required'=>true,'accept'=>'.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'])->label('Master Data </br>')  ?>
					</div>
					<div class="col-2">
						<?= Html::submitButton('Submit', ['id'=>'master_import','class' => 'btn btn-success']) ?>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
		</div>
		</div>
	</div>
</div>
<!-- Qualification Data Import Modal -->
<div id="qualification_import_Modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				
				<h4 class="modal-title">Upload Qualification Data</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="col-6">
				<?php $qualificationimportform = ActiveForm::begin(['id' => "qualification_import_form",'action' => 'javascript:void(0)','options' => ['enctype' => 'multipart/form-data']]); ?>
					<?= $qualificationimportform->field($model, 'import_file' )->fileInput(['maxlength' => true,'required'=>true,'accept'=>'.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'])->label('Qualification Data </br>')  ?>
				</div>
				<div class="col-2">
					<?= Html::submitButton('Submit', ['id'=>'qual_import','class' => 'btn btn-success']) ?>
				</div>	
				<?php ActiveForm::end(); ?>
			</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
		</div>
		</div>
	</div>
</div>
<!-- PAPL Internal Data Import Modal -->
<div id="internal_import_Modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				
				<h4 class="modal-title">Upload PAPL Internal Data</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php $internalimportform = ActiveForm::begin(['id' => "internal_import_form",'action' => 'javascript:void(0)','options' => ['enctype' => 'multipart/form-data']]); ?>
				<div class="col-6">
					<?= $internalimportform->field($model, 'import_file' )->fileInput(['maxlength' => true,'required'=>true,'accept'=>'.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'])->label('PAPL Internal Data </br>')  ?>
				</div>
				<div class="col-2">
					<?= Html::submitButton('Submit', ['id'=>'internal_import','class' => 'btn btn-success']) ?>
				</div>	
				<?php ActiveForm::end(); ?>
			</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
		</div>
		</div>
	</div>
</div>

<!-- Bank Data Import Modal -->
<div id="bank_import_Modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				
				<h4 class="modal-title">Upload Bank Details Data</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php $bankimportform = ActiveForm::begin(['id' => "bank_import_form",'action' => 'javascript:void(0)','options' => ['enctype' => 'multipart/form-data']]); ?>
				<div class="col-6">
					<?= $bankimportform->field($model, 'import_file' )->fileInput(['maxlength' => true,'required'=>true,'accept'=>'.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'])->label('Bank Details Data </br>')  ?>
				</div>
				<div class="col-2">
					<?= Html::submitButton('Submit', ['id'=>'bank_import','class' => 'btn btn-success']) ?>
				</div>	
				<?php ActiveForm::end(); ?>
			</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
		</div>
		</div>
	</div>
</div>
<!-- Nominee Data Import Modal -->
<div id="nominee_import_Modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				
				<h4 class="modal-title">Upload Nominee Details Data</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php $nomineeimportform = ActiveForm::begin(['id' => "nominee_import_form",'action' => 'javascript:void(0)','options' => ['enctype' => 'multipart/form-data']]); ?>
				<div class="col-6">
					<?= $nomineeimportform->field($model, 'import_file' )->fileInput(['maxlength' => true,'required'=>true,'accept'=>'.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'])->label('Nominee Details Data </br>')  ?>
				</div>
				<div class="col-2">
					<?= Html::submitButton('Submit', ['id'=>'nominee_import','class' => 'btn btn-success']) ?>
				</div>	
				<?php ActiveForm::end(); ?>
			</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
		</div>
		</div>
	</div>
</div>

<!-- Family Data Import Modal -->
<div id="family_import_Modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				
				<h4 class="modal-title">Upload Family Details Data</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php $familyimportform = ActiveForm::begin(['id' => "family_import_form",'action' => 'javascript:void(0)','options' => ['enctype' => 'multipart/form-data']]); ?>
				<div class="col-6">
					<?= $familyimportform->field($model, 'import_file' )->fileInput(['maxlength' => true,'required'=>true,'accept'=>'.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'])->label('Family Details Data </br>')  ?>
				</div>
				<div class="col-2">
					<?= Html::submitButton('Submit', ['id'=>'family_import','class' => 'btn btn-success']) ?>
				</div>	
				<?php ActiveForm::end(); ?>
			</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
		</div>
		</div>
	</div>
</div>
<!-- EPF/ESIC Data Import Modal -->
<div id="epf_import_Modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				
				<h4 class="modal-title">Upload EPF/ESIC Details Data</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php $epfimportform = ActiveForm::begin(['id' => "epf_import_form",'action' => 'javascript:void(0)','options' => ['enctype' => 'multipart/form-data']]); ?>
				<div class="col-6">
					<?= $epfimportform->field($model, 'import_file' )->fileInput(['maxlength' => true,'required'=>true,'accept'=>'.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'])->label('EPF/ESIC Details Data </br>')  ?>
				</div>
				<div class="col-2">
					<?= Html::submitButton('Submit', ['id'=>'epf_import','class' => 'btn btn-success']) ?>
				</div>	
				<?php ActiveForm::end(); ?>
			</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
		</div>
		</div>
	</div>
</div>
<iframe id="iframeID" style="position: absolute;width:0;height:0;border:0;"></iframe>
<script>

</script>
<?php
  $js = <<<JS
 
     $( "#wca_gpa_expire_date" ).datepicker({
     showButtonPanel: true,
        dateFormat: "dd-mm-yy",
        changeMonth:true,
        changeYear:true,        
    }).val();

    $( "#bankdetails-family_member_dob" ).datepicker({
        showButtonPanel: true,
        dateFormat: "dd-mm-yy",
        changeMonth:true,
        changeYear:true,
        yearRange: "-100:+0",
        maxDate: 0
    }).val();
    $( "#document-dl_expiry_date" ).datepicker({
        showButtonPanel: true,
        dateFormat: "dd-mm-yy",
        changeMonth:true,
        changeYear:true,
        
    }).val();
    $( "#document-passport_expirydate" ).datepicker({
        showButtonPanel: true,
        dateFormat: "dd-mm-yy",
        changeMonth:true,
        changeYear:true,
    }).val();
    $( "#enrollment-wca_gpa_expire_date" ).datepicker({
       showButtonPanel: true,
        dateFormat: "dd-mm-yy",
        changeMonth:true,
        changeYear:true,
    }).val();
    $( "#passport_expirydate" ).datepicker({
     showButtonPanel: true,
        dateFormat: "dd-mm-yy",
        changeMonth:true,
        changeYear:true,
    }).val();
    $( "#nominee-nominee_dob" ).datepicker({
       showButtonPanel: true,
        dateFormat: "dd-mm-yy",
        changeMonth:true,
        changeYear:true,
        yearRange: "-100:+0",
        maxDate: 0
    }).val();
    $('body').on('focus', '.family-family_member_dob', function() {
      $(this).removeClass('hasDatepicker').datepicker({
       showButtonPanel: true,
        dateFormat: "dd-mm-yy",
        changeMonth:true,
        changeYear:true,
        yearRange: "-130:+0",
        maxDate: 0
    }).val();
    });

    $( "#passport_expirydate" ).datepicker({
       showButtonPanel: true,
        dateFormat: "dd-mm-yy",
        changeMonth:true,
        changeYear:true,
    }).val();
   
   
    $("#enrollment-dob").datepicker({
        showButtonPanel: true,
        dateFormat: "dd-mm-yy",
        changeMonth:true,
        changeYear:true,
        yearRange: "-100:+0",
        maxDate: 0
    }).val();
       
    
   
  JS;
  
    
  $this->registerJs($js);
  $jstag = <<<JS
  
  JS;
  if($tag=='qualification')
    $jstag =  <<<JS
      
      $('#nav-qualification-tab').tab('show');
      JS;
  $this->registerJs($jstag);
  $addmore = <<<JS

  JS;
  $addmore = <<<JS
  var i = 1;
  $('.removeParent').click(function(){
          $(this).parent().remove();

        })
    $("#add").click(function(){
      i++;
      $('#dynamic_field').append('<div class="row">'+
        $('#sampleQualification').html()
      +'</div>');  
      $('.removeParent').click(function(){
          $(this).parent().remove();

        })
    });
   
JS;
$this->registerJs($addmore);
$addmorefamily = <<<JS

  JS;
  $addmorefamily = <<<JS
  var template = $('#dynamic_fieldf #sampleFamily:first').clone();
  var sectionsCount = 1;
  $('body').on('click', '#addf', function() {    
    sectionsCount++;
    var section = template.clone().find(':input').each(function(){
      var newId = this.id + sectionsCount;        
      $(this).prev().attr('for', newId);
      }).end().appendTo('#dynamic_fieldf');
    
    $("input[name='family_member_dob[]']").last().datepicker();
      $('#dynamic_fieldf').on('click', '.removeParentfamily', function() {
    $(this).parent().fadeOut(300, function(){
        
        return false;
    });
    return false;
});
    return false;
});

$("input[name='family_member_dob[]']").last().datepicker();
  
JS;
$this->registerJs($addmorefamily);

$epftag = <<<JS

  JS;
if($tag=='epf')
    $epftag =  <<<JS
      
      $('#nav-epf-tab').tab('show');
      JS;
  $this->registerJs($epftag);

  $epftag = <<<JS

  JS;
if($tag=='internal')
    $epftag =  <<<JS
      $('#nav-internal-tab').tab('show');
      JS;
  $this->registerJs($epftag);
  $banktag = <<<JS

  JS;
   if($tag=='bank')
    $banktag =  <<<JS
      
      $('#nav-bank-tab').tab('show');
      JS;
  $this->registerJs($banktag);
    $nomineetag = <<<JS

  JS;
   if($tag=='nominee')
    $nomineetag =  <<<JS
      
      $('#nav-nominee-tab').tab('show');
      JS;
  $this->registerJs($nomineetag);
    $familytag = <<<JS

  JS;
   if($tag=='family')
    $familytag =  <<<JS
      
      $('#nav-family-tab').tab('show');
      JS;
  $this->registerJs($familytag);
    $mastertag = <<<JS

  JS;
   if($tag=='master')
    $mastertag =  <<<JS
      
      $('#nav-family-tab').tab('show');
      JS;
  $this->registerJs($mastertag);
    
  ?>
  <script type="text/javascript">
    function readURL(input){
      
          var ext = input.files[0]['name'].substring(input.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();
          if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg" || ext == "pdf")) {

          }else{
            alert("Only jpg/jpeg/png and pdf files are allowed!");
            $('input[type=file]').val(null) ;
          }
    
    }
</script>