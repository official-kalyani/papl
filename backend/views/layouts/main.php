<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
use common\helpers\acl;
AppAsset::register($this);
$role=Yii::$app->user->identity->role_id;
//$role_name=Yii::$app->user->identity->role->name;
$user_name=Yii::$app->user->identity->username;
$uid=Yii::$app->user->identity->id;;
//print_r($role_name);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <style> 

.navbar .dropdown-toggle, .navbar .dropdown-menu a {
    cursor: pointer;
}

.navbar .dropdown-item.active, .navbar .dropdown-item:active {
    color: inherit;
    text-decoration: none;
    background-color: inherit;
}

.navbar .dropdown-item:focus, .navbar .dropdown-item:hover {
    color: #16181b;
    text-decoration: none;
    background-color: #f8f9fa;
}

@media (min-width: 767px) {
    .navbar .dropdown-toggle:not(.nav-link)::after {
        display: inline-block;
        width: 0;
        height: 0;
        margin-left: .5em;
        vertical-align: 0;
        border-bottom: .3em solid transparent;
        border-top: .3em solid transparent;
        border-left: .3em solid;
    }
}
.breadcrumb > li + li:before {
    color: #ccc;
    content: "/ ";
    padding: 0 5px;
}
</style>
<?php $this->beginBody() ?>
<div class="navbar navbar-expand-md navbar-dark bg-dark" role="navigation">
     <a class="navbar-brand" href="#"> 
<img src="<?= Yii::getAlias('@backendUrl')."/images/logo.png";?>" height="60"/>
     </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
        <?php if(acl::checkAcess($uid,"Enrollment")){?>
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo Url::to('@web/enrollment/enrole', '');?>">Home <span class="sr-only">(current)</span></a>
            </li>
         <?php } ?>   
            <?php 
            $backendUrl=Yii::getAlias('@backendUrl');
            ?>
            <?php 
            if(acl::checkAcess($uid,"Create State")  || acl::checkAcess($uid,"Edit State") || acl::checkAcess($uid,"View State")
                || acl::checkAcess($uid,"Create Location")  || acl::checkAcess($uid,"Edit Location") || acl::checkAcess($uid,"View Location")
                || acl::checkAcess($uid,"Create Plant")  || acl::checkAcess($uid,"Edit Plant") || acl::checkAcess($uid,"View Plant") 
                || acl::checkAcess($uid,"Create Purchase Order")  || acl::checkAcess($uid,"Edit Purchase Order") || acl::checkAcess($uid,"View Purchase Order")
                || acl::checkAcess($uid,"Create Section")  || acl::checkAcess($uid,"Edit Section") || acl::checkAcess($uid,"View Section")){?>
           <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Master</a>
                <ul class="dropdown-menu" aria-labelledby="dropdown1">
                    <!-- <li class="dropdown-item" href="#"><a>Action 1</a></li> -->
                    
                    <?php if(acl::checkAcess($uid,"Create State")  || acl::checkAcess($uid,"Edit State") || acl::checkAcess($uid,"View State")){?>
                    <li class="dropdown-item dropdown">
                        <a class="dropdown-toggle" id="dropdown3-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">State</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown3-3">
                            <?php if(acl::checkAcess($uid,"Create State")){?>
                                <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/state/create', '');?>">Create</a></li>
                            <?php } ?>
                            <?php if(acl::checkAcess($uid,"Edit State")){?>
                                <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/state', '');?>">Edit</a></li>
                            <?php } ?>
                            <?php if(acl::checkAcess($uid,"View State")){?>
                                <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/state', '');?>">View</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if(acl::checkAcess($uid,"Create Location")  || acl::checkAcess($uid,"Edit Location") || acl::checkAcess($uid,"View Location")){?>
                     <li class="dropdown-item dropdown">
                        <a class="dropdown-toggle" id="dropdown4-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Location</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown4-4">
                            <?php if(acl::checkAcess($uid,"Create Location")){?>
                                <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/location/create', '');?>">Create</a></li>
                            <?php } ?>
                            <?php if(acl::checkAcess($uid,"Edit Location")){?>
                                <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/location', '');?>">Edit</a></li>
                            <?php } ?>
                            <?php if(acl::checkAcess($uid,"View Location")){?>
                                <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/location', '');?>">View</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if(acl::checkAcess($uid,"Create Plant")  || acl::checkAcess($uid,"Edit Plant") || acl::checkAcess($uid,"View Plant")){?>
                    <li class="dropdown-item dropdown">
                        <a class="dropdown-toggle" id="dropdown1-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Plant</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown1-1">
                        <?php if(acl::checkAcess($uid,"Create Plant")){?>
                            <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/plant/create', '');?>">Create</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"Edit Plant")){?>
                            <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/plant', '');?>">Edit</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"View Plant")){?>
                            <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/plant', '');?>">View</a></li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if(acl::checkAcess($uid,"Create Purchase Order")  || acl::checkAcess($uid,"Edit Purchase Order") || acl::checkAcess($uid,"View Purchase Order")){?>
                     <li class="dropdown-item dropdown">
                        <a class="dropdown-toggle" id="dropdown4-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Purchase Order</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown4-4">
                        <?php if(acl::checkAcess($uid,"Create Purchase Order")){?>
                            <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/purchaseorder/create', '');?>">Create</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"Edit Purchase Order")){?>
                            <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/purchaseorder', '');?>">Edit</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"View Purchase Order")){?>
                            <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/purchaseorder', '');?>">View</a></li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if(acl::checkAcess($uid,"Create Section")  || acl::checkAcess($uid,"Edit Section") || acl::checkAcess($uid,"View Section")){?>
                     <li class="dropdown-item dropdown">
                        <a class="dropdown-toggle" id="dropdown2-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Section</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown2-2">
                        <?php if(acl::checkAcess($uid,"Create Section")){?>
                            <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/section/create', '');?>">Create</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"Edit Section")){?>
                            <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/section', '');?>">Edit</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"View Section")){?>
                            <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/section', '');?>">View</a></li>
                        <?php } ?>
                           
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if(acl::checkAcess($uid,"Create PAPL Designation")  || acl::checkAcess($uid,"Edit PAPL Designation") || acl::checkAcess($uid,"View PAPL Designation")){?>
                    <li class="dropdown-item dropdown">
                        <a class="dropdown-toggle" id="dropdown3-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">PAPL Designation</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown3-3">
                            <?php if(acl::checkAcess($uid,"Create PAPL Designation")){?>
                                <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/papldesignation/create', '');?>">Create</a></li>
                            <?php } ?>
                            <?php if(acl::checkAcess($uid,"Edit PAPL Designation")){?>
                                <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/papldesignation', '');?>">Edit</a></li>
                            <?php } ?>
                            <?php if(acl::checkAcess($uid,"View PAPL Designation")){?>
                                <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/papldesignation', '');?>">View</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Enrollment</a>
                <ul class="dropdown-menu" aria-labelledby="dropdown1">
                <?php if(acl::checkAcess($uid,"Create Enrollment")){?>
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/enrollment/enrole') ?>">Create enrollment</a></li>
                <?php } ?>
                    <?php if(acl::checkAcess($uid,"Enrollment")  || acl::checkAcess($uid,"Plant Head Approve") || acl::checkAcess($uid,"EPF/ESIC") || acl::checkAcess($uid,"Appointment") ||acl::checkAcess($uid,"Rejected")){?>
                    <li class="dropdown-item dropdown">
                        <a class="dropdown-toggle" id="dropdown1-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Approve enrollment</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown1-1">
                        <?php if(acl::checkAcess($uid,"Enrollment")){?>
                            <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/enrollment?type=0', '');?>">Enrollment</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"Plant Head Approve")){?>
                            <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/enrollment?type=1', '');?>">Plant Head Approve</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"EPF/ESIC")){?>
                            <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/enrollment?type=2', '');?>">EPF/ESIC</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"Appointment")){?>
                            <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/enrollment?type=3', '');?>">Appointment</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"Rejected")){?>
                             <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/enrollment?type=4', '');?>">Rejected</a></li>
                        <?php } ?>
                            <!-- <li class="dropdown-item" href="#"><a href="<?php echo Url::to('@web/enrollment?type=4', '');?>">Salary</a></li> -->
                           <!--  <li class="dropdown-item dropdown">
                                <a class="dropdown-toggle" id="dropdown1-1-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Salary</a>
                                <ul class="dropdown-menu" aria-labelledby="dropdown1-1-1">
                                    <li class="dropdown-item" ><a href="<?php echo Url::to('@web/salary', '');?>">Create Salary</a></li>
                                    <li class="dropdown-item" ><a href="<?php echo Url::to('@web/deduction', '');?>">Create Deduction</a></li>
                                </ul>
                            </li> -->
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if(acl::checkAcess($uid,"Employee")){?>
                        <!-- <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/transfer') ?>">Transfer</a></li> -->
                        <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/transfer') ?>">Employee</a></li>
                
                    <?php } ?>
                    
                </ul>

            </li>
            <?php if(acl::checkAcess($uid,"Create Salary")  || acl::checkAcess($uid,"Salary Update") || acl::checkAcess($uid,"Create Deduction") || acl::checkAcess($uid,"Salary Sheet")){?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Salary</a>
                <ul class="dropdown-menu" aria-labelledby="dropdown1">
                <?php if(acl::checkAcess($uid,"Create Salary")){?>
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/salary/create') ?>">Create Salary</a></li>
                <?php } ?>
                <?php if(acl::checkAcess($uid,"Salary Update")){?>
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/salary/salary_update') ?>">Salary Update</a></li>
                <?php } ?>
                <?php if(acl::checkAcess($uid,"Create Deduction")){?>
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/deduction/create');?>">Create Deduction</a></li>
                <?php } ?>
                <?php if(acl::checkAcess($uid,"Salary Sheet")){?>
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/salary/sheet');?>">Salary Sheet</a></li>
                <?php } ?>
                </ul>

            </li>
            <?php } ?>
            <?php if(acl::checkAcess($uid,"Daily Attendance")  || acl::checkAcess($uid,"Approve Attendance")){?>
             <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Attendance</a>
                <ul class="dropdown-menu" aria-labelledby="dropdown1">
                <?php if(acl::checkAcess($uid,"Daily Attendance")){?>
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/attendance/create') ?>">Daily Attendance</a></li>
                <?php } ?>
                <?php if(acl::checkAcess($uid,"Approve Attendance")){?>
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/attendance/approve') ?>">Approve Attendance</a></li>
                <?php } ?>  
                </ul>

            </li>
            <?php } ?>
            <!-- <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Appointment</a>
                <ul class="dropdown-menu" aria-labelledby="dropdown1">
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/create') ?>">Create Appointment</a></li>
                   
                
                </ul>

            </li> -->
            
            <!-- <?php if(acl::checkAcess($uid,"Employee Card")){?>  
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Compliance Reports</a>
                <ul class="dropdown-menu" aria-labelledby="dropdown1">
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/employee_card') ?>">Employee Card</a></li>
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/nomineedeclaration') ?>">Nominee Declaration Form 1</a></li>
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/nomineedeclarationtwo') ?>">Nominee Declaration Form 2</a></li>
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/servicecertificate') ?>">SERVICE CERTIFICATE</a></li>
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/newregisterformb') ?>">NEW REGISTER FORM-B</a></li>
                    <li class="dropdown-item" href="#">
                        <a href="<?= Url::to('@web/posting-history/newregisterform_xii') ?>">
                        NEW REGISTER FORM-XII
                        </a>
                    </li>
                   <li class="dropdown-item" href="#">
                        <a href="<?= Url::to('@web/posting-history/newregisterform_xvi') ?>">
                        NEW REGISTER FORM-XVI
                        </a>
                    </li>
                   <li class="dropdown-item" href="#">
                        <a href="<?= Url::to('@web/posting-history/newregisterform_xix') ?>">
                        NEW REGISTER FORM-XIX
                        </a>
                    </li>
                   <li class="dropdown-item" href="#">
                        <a href="<?= Url::to('@web/posting-history/adultregister') ?>">
                        ADULT REGISTER
                        </a>
                    </li>
                   <li class="dropdown-item" href="#">
                        <a href="<?= Url::to('@web/posting-history/finalsettlement') ?>">
                        FULLFINAL SETTLEMENT
                        </a>
                    </li>
                   
                
                </ul>

            </li>
            <?php } ?> -->
            <?php if(acl::checkAcess($uid,"User ACL") || Yii::$app->user->identity->role_id==1){?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Access Control</a>
                <ul class="dropdown-menu" aria-labelledby="dropdown1">
                <?php if(acl::checkAcess($uid,"User ACL") || Yii::$app->user->identity->role_id==1){?> 
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/access/index') ?>">User ACL</a></li>
                <?php } ?>   
                <?php if(acl::checkAcess($uid,"User ACL") && Yii::$app->user->identity->role_id!=5){?> 
                    <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/access/rolebasedaccess') ?>">Role Change</a></li>
                <?php } ?>
                </ul>

            </li>
            <?php } ?> 
            <?php if(acl::checkAcess($uid,"Changepassword") || Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 2 || Yii::$app->user->identity->role_id == 3 || Yii::$app->user->identity->role_id == 4 || Yii::$app->user->identity->role_id == 5 || Yii::$app->user->identity->role_id == 7){?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Url::to('@web/site/reset-password');?>">Changepassword <span class="sr-only">(current)</span></a>
            </li>
            <?php } ?>

            <!-- Report -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reports</a>
                <ul class="dropdown-menu" aria-labelledby="dropdown1">
                    <?php if(acl::checkAcess($uid,"Employee Master Data Report")){?>
                        <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/enrollment/employee_all_report') ?>">Employee Master Report</a></li>
                    <?php } ?>    
                   <?php if(acl::checkAcess($uid,"ESI - MONTHLY MC FILE")){?>
                        <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/salary/esi_monthly_mc_file') ?>">ESI - MONTHLY MC FILE</a></li>
                    <?php } ?>  
                    <?php if(acl::checkAcess($uid,"PF - MONTHLY PF STATEMENT")){?>
                        <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/salary/pt_monthly_pt_satement') ?>">PF - MONTHLY PF STATEMENT</a></li>
                    <?php } ?>    
                    <?php if(acl::checkAcess($uid,"Exit Reports")){?>
                        <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/enrollment/export_exit_report') ?>">Exit Reports</a></li>
                    <?php } ?>
                    <?php if(acl::checkAcess($uid,"Employee Card")){?>
                        <li class="dropdown-item dropdown">
                        <a class="dropdown-toggle" id="dropdown1-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Compliance Records</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown1-1">
                        <?php if(acl::checkAcess($uid,"Employee Card")){?>
                            <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/employee_card') ?>">Employee Card</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"Nominee Declaration Form 1")){?>
                            <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/nomineedeclaration') ?>">Nominee Declaration Form 1</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"Nominee Declaration Form 2")){?>
                           <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/nomineedeclarationtwo') ?>">Nominee Declaration Form 2</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"SERVICE CERTIFICATE")){?>
                           <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/servicecertificate') ?>">SERVICE CERTIFICATE</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"NEW REGISTER FORM-B")){?>
                            <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/newregisterformb') ?>">NEW REGISTER FORM-B</a></li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"NEW REGISTER FORM-XII")){?>
                            <li class="dropdown-item" href="#">
                                <a href="<?= Url::to('@web/posting-history/newregisterform_xii') ?>">
                                NEW REGISTER FORM-XII
                                </a>
                            </li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"NEW REGISTER FORM-XVI")){?>
                             <li class="dropdown-item" href="#">
                                <a href="<?= Url::to('@web/posting-history/newregisterform_xvi') ?>">
                                NEW REGISTER FORM-XVI
                                </a>
                            </li>
                        <?php } ?>
                           <?php if(acl::checkAcess($uid,"NEW REGISTER FORM-XIX")){?>
                            <li class="dropdown-item" href="#">
                                <a href="<?= Url::to('@web/posting-history/newregisterform_xix') ?>">
                                    NEW REGISTER FORM-XIX
                                </a>
                            </li>
                        <?php } ?>
                        <?php if(acl::checkAcess($uid,"ADULT REGISTER")){?>
                        <li class="dropdown-item" href="#">
                            <a href="<?= Url::to('@web/posting-history/adultregister') ?>">
                            ADULT REGISTER
                            </a>
                        </li>
                        <?php } ?>
                         <?php if(acl::checkAcess($uid,"FULLFINAL SETTLEMENT")){?>
                            <li class="dropdown-item" href="#">
                                <a href="<?= Url::to('@web/posting-history/finalsettlement') ?>">
                                FULLFINAL SETTLEMENT
                                </a>
                            </li>
                        <?php } ?>
                           
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if(acl::checkAcess($uid,"Rejected Reports")){?>
                        <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/enrollment/rejected_reports') ?>">Rejected Reports</a></li>
                
                    <?php } ?>
                    <?php if(acl::checkAcess($uid,"Approved Attendance")){?>
                        <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/attendance/approved_attendance') ?>">Attendance Report</a></li>
                
                    <?php } ?>
                    <?php if(acl::checkAcess($uid,"Posting Report")){?>
                        <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/posting-history/report') ?>">Posting Report</a></li>
                
                    <?php } ?>
                    <?php if(acl::checkAcess($uid,"Increament Report")){?>
                        <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/salary/increament_report') ?>">Increament Report</a></li>
                
                    <?php } ?>
                    <?php if(acl::checkAcess($uid,"Professional Tax Statement")){?>
                        <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/report/professional_tax_statement') ?>">Professional Tax Statement</a></li>
                
                    <?php } ?>
                    <?php if(acl::checkAcess($uid,"PF Monthly Text File")){?>
                        <li class="dropdown-item" href="#"><a href="<?= Url::to('@web/report/pf_monthly_text_file') ?>">PF Monthly Text File</a></li>
                
                    <?php } ?>
                    
                </ul>

            </li>
            <!-- Report -->
           
        </ul>
        <span style="color:white;margin-right:20px;">
            <?php echo $user_name;?>
        </span>
        <a href="<?= Url::toRoute(['site/logout']); ?>" data-method="post">
            <button class="btn logout my-2 my-sm-0">Logout</button>
        </a>
        
    </div>
</div>
<div class="wrap">
    
    

    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>
    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
<!-- JS script added by kalyani -->

<!-- JS script added by kalyani -->
    
    <script type="text/javascript">
    $('.mobile_with_adhar').keypress(function(e){ 
        if (this.value.length == 0 && e.which == 48 || this.value.length >= 10){
          return false;
        }
    });
    $('#bankdetails-transaction_id').keypress(function(e){ 
        if (this.value.length == 0 && e.which == 48 ){
          return false;
        }
    });
    $('#document-voter_id_number').keypress(function(e){ 
        if (this.value.length == 0 && e.which == 48 ){
          return false;
        }
    });
    $('.permanent_pincode').keypress(function(e){ 
        if (this.value.length == 0 && e.which == 48 || this.value.length >= 6){
           

          return false;
        }
    });
    
    $('.permanent_mobile_number').keypress(function(e){ 
        if (this.value.length == 0 && e.which == 48 || this.value.length >= 10){
          return false;
        }
    });
    $('.adhar_number').keypress(function(e) {
          if (this.value.length == 12  ){
          return false;
        }
        });
    $('.nominee_adhar_number').keypress(function(e) {
          if (this.value.length == 12  ){
          return false;
        }
        });
    </script>

    <script>
    $(document).ready( function () {
    $('.emp-table').DataTable();
} );
</script>
<!-- JS script added by kalyani -->
</body>

</html>
<?php $this->endPage() ?>