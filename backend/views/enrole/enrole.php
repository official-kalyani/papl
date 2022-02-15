<?php
/* @var $this yii\web\View */
?>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Enrollment */
/* @var $form yii\widgets\ActiveForm */
?>
<style> 

nav > .nav.nav-tabs{

  border: none;
  color:#fff;
  background:#272e38;
  border-radius:0;

}
nav > div a.nav-item.nav-link,
nav > div a.nav-item.nav-link.active
{
  border: none;
  padding: 18px 25px;
  color:#fff;
  background:#125e76;
  border-radius:0;
}

nav > div a.nav-item.nav-link.active:after
{
  content: "";
  position: relative;
  bottom: -60px;
  left: -10%;
  border: 15px solid transparent;
  border-top-color: #0e7c9e ;
}
.tab-content{
  background: #fdfdfd;
  line-height: 25px;
  border-left:0px solid #0e7c9e;
  border-right:0px solid #0e7c9e;
  border-top:5px solid #0e7c9e;
  border-bottom:0px solid #0e7c9e;
  padding:30px 25px;
}

nav > div a.nav-item.nav-link:hover,
nav > div a.nav-item.nav-link:focus
{
  border: none;
  background: #0e7c9e;
  color:#fff;
  border-radius:0;
  transition:background 0.20s linear;
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
</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <nav>
        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
          <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Master</a>
          <a class="nav-item nav-link" id="nav-qualification-tab" data-toggle="tab" href="#nav-qualification" role="tab" aria-controls="nav-qualification" aria-selected="false">Qualification</a>
          <a class="nav-item nav-link" id="nav-internal-tab" data-toggle="tab" href="#nav-internal" role="tab" aria-controls="nav-internal" aria-selected="false">PAPL Internal </a> <a class="nav-item nav-link" id="nav-epf-tab" data-toggle="tab" href="#nav-epf" role="tab" aria-controls="nav-epf" aria-selected="false">EPF/ESIC</a> <a class="nav-item nav-link" id="nav-bank-tab" data-toggle="tab" href="#nav-bank" role="tab" aria-controls="nav-bank" aria-selected="false">Bank Details</a> </div>
      </nav>
      <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
         <?php $form = ActiveForm::begin(); ?>
            <div class="row">
              <div class="col-2">
                <?= $form->field($model, 'adhar_name')->textInput(['maxlength' => true]) ?>
              </div>
              <div class="col-2">
                <div class="form-group">
                 <?= $form->field($model, 'father_husband_name')->textInput(['maxlength' => true]) ?>
                </div>
              </div>
              <div class="col-2">
                 <?= $form->field($model, 'relation_with_employee')->textInput(['maxlength' => true]) ?>
              </div>
              <div class="col-2">
                <?= $form->field($model, 'adhar_number')->textInput() ?>
              </div>
              <div class="col-2">
                 <?= $form->field($model, 'browse_adhar')->fileInput(['maxlength' => true]) ?>
              </div>
              <div class="col-2">
                <?= $form->field($model, 'browse_pp_photo')->fileInput(['maxlength' => true]) ?>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
               <?= $form->field($model, 'gender')->dropdownList([
                      1 => 'Male', 
                      2 => 'Female'
                  ],
                  ['prompt'=>'Select gender']
              );?>
              </div>
              <div class="col-2">
                <?= $form->field($model, 'marital_status')->dropdownList([
                      1 => 'Married', 
                      2 => 'Unmarried'
                  ],
                  ['prompt'=>'Select Marital Status']
              );?>
               
              </div>
              <div class="col-2">
               <?= $form->field($model, 'mobile_with_adhar')->textInput(['maxlength' => true]) ?>
              </div>
              <div class="col-2">
                <?= $form->field($model, 'dob')->textInput() ?>
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
                <?= $form->field($model, 'permanent_state')->dropdownList([
                      1 => 'Odisha', 
                      2 => 'MP'
                  ],
                  ['prompt'=>'Select State']
              );?>
              </div>
              <div class="col-2">
                <?= $form->field($model, 'permanent_district')->dropdownList([
                      1 => 'Baleswar', 
                      2 => 'Nayagad'
                  ],
                  ['prompt'=>'Select District']
              );?>
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
                <?= $form->field($model, 'permanent_pincode')->textInput() ?>
              </div>
              <div class="col-2">
                <?= $form->field($model, 'permanent_mobile_number')->textInput(['maxlength' => true]) ?>

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
                <?= $form->field($model, 'present_state')->dropdownList([
                      1 => 'Odisha', 
                      2 => 'MP'
                  ],
                  ['prompt'=>'Select State']
              );?>
              </div>
              <div class="col-2">
                <?= $form->field($model, 'present_district')->dropdownList([
                      1 => 'Odisha', 
                      2 => 'MP'
                  ],
                  ['prompt'=>'Select District']
              );?>
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
              <div class="col-2">
                 <?= $form->field($model, 'blood_group')->dropdownList([
                      1 => 'A+', 
                      2 => 'O'
                  ],
                  ['prompt'=>'Select Blood group']
              );?>
              </div>
              <div class="col-2">
                <?= $form->field($model, 'ID_mark_employee')->textInput(['maxlength' => true]) ?>
              </div>
              <div class="col-2">
                 <?= $form->field($model, 'nationality')->dropdownList([
                      1 => 'Indian', 
                      2 => 'Muslim'
                  ],
                  ['prompt'=>'Select nationality']
              );?>
              </div>
              <div class="col-2">
                 <?= $form->field($model, 'religion')->dropdownList([
                      1 => 'Hindu', 
                      2 => 'Muslim'
                  ],
                  ['prompt'=>'Select nationality']
              );?>
              </div>
              <div class="col-2">
                <?= $form->field($model, 'caste')->dropdownList([
                      1 => 'Hindu', 
                      2 => 'Muslim'
                  ],
                  ['prompt'=>'Select caste']
              );?>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h1 class="heading"> Employee Information </h1>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <?= $form->field($model, 'designation')->dropdownList([
                      1 => 'KHALSI', 
                      2 => 'MAZDOOR'
                  ],
                  ['prompt'=>'Select Designation']
              );?>
              </div>
              <div class="col-2">
                <?= $form->field($model, 'category')->dropdownList([
                      1 => 'UN SKILLED', 
                      2 => 'SEMI SKILLED',
                      3 => 'SKILLED',
                      4 => 'HIGH SKILLED',
                      5 => 'MANAGEMENT STAFF'
                  ],
                  ['prompt'=>'Select Category']
              );?>
              </div>
              <div class="col-2">
                <?= $form->field($model, 'PAPLdesignation')->textInput(['maxlength' => true]) ?>
              </div>
              <div class="col-2">
                <?= $form->field($model, 'experience')->textInput(['maxlength' => true]) ?>

              </div>    
            </div>
            <div class="row"> 
              <div class="col-1">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'nav-item nav-link sub']) ?>
              </div> 
            </div>  
          <!--   <div class="row">
              <div class="col-1"> <a class="nav-item nav-link sub" id="nav-qualification-tab" data-toggle="tab" href="#nav-qualification" role="tab" aria-controls="nav-qualification" aria-selected="false">Submit</a> </div>
            </div> -->
           <?php ActiveForm::end(); ?>
        </div>
        <div class="tab-pane fade" id="nav-qualification" role="tabpanel" aria-labelledby="nav-qualification-tab">
          <form>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect2">Qualification if any</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">University/Board</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">College/Institute Name</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect2"> Year of Passout</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Division/Percent</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Qualification</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-1"> <a class="nav-item nav-link sub" id="nav-internal-tab" data-toggle="tab" href="#nav-internal" role="tab" aria-controls="nav-internal" aria-selected="false">Submit</a> </div>
            </div>
          </form>
        </div>
        <div class="tab-pane fade" id="nav-internal" role="tabpanel" aria-labelledby="nav-internal-tab">
          <form>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect2">Designation</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect2"> Category</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">PAPL Designation</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect2"> Experience in Year</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Experience</label>
                  <input type="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-1"> <a class="nav-item nav-link sub" id="nav-epf-tab" data-toggle="tab" href="#nav-epf" role="tab" aria-controls="nav-epf" aria-selected="false">Submit</a> </div>
            </div>
          </form>
        </div>
        <div class="tab-pane fade" id="nav-epf" role="tabpanel" aria-labelledby="nav-epf-tab">
          <form>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect2">ESIC Code</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect2"> ESIC IP Number</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">WCA/GPA</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect2"> WCA/GPA Expire Date</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Browse ESIC Sheet</label>
                  <input type="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h1 class="heading"> PF Account </h1>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">PF Code</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">UAN</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">PF Account Number</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Browse UAN Sheet</label>
                  <input type="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-1"> <a class="nav-item nav-link sub" id="nav-bank-tab" data-toggle="tab" href="#nav-bank" role="tab" aria-controls="nav-bank" aria-selected="false">Submit</a> </div>
            </div>
          </form>
        </div>
        <div class="tab-pane fade" id="nav-bank" role="tabpanel" aria-labelledby="nav-bank-tab">
          <form>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect2">Bank Transaction ID</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect2"> IFSC</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Name as per Bank Pass Book</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect2">Name of Bank</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Branch with (Distrct and State)</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Bank Pass Book/Cheque</label>
                  <input type="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h1 class="heading"> Document </h1>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Voter Id Number</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Voter Copy</label>
                  <input type="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">DL Number</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>Transport</option>
                    <option>Non Transport</option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">DL Expire Date</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Driving Licence</label>
                  <input type="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Pan Number</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Pan</label>
                  <input type="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Passport Number</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Passport Expire Date</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Passport</label>
                  <input type="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h1 class="heading"> Nominee </h1>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Nominee Name</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Nominee Relationship</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Nominee Date of Birth</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Nominee Aadhar Number</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Browse Nominee Aadhar Copy</label>
                  <input type="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Nominee Adress</label>
                  <textarea class="form-control" id="exampleFormControlTextarea1" rows="1"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h1 class="heading"> Family Member </h1>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Family Member</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Date of Birth</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Relation</label>
                  <input type="text" class="form-control"  aria-describedby="name"  >
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Residence with (Yes/No)</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>Yes</option>
                    <option>No</option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">Browse Nominee Aadhar Copy</label>
                  <input type="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-1"> <a class="nav-item nav-link sub" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Submit</a> </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
