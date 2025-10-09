<?php 
if(isset($_SESSION['login'])){
    $ex = 'layout.advocateApp';
}else{
    $ex = 'layout.frontApp';
}
?>
@extends($ex)
@section('content')
    <style>
        #accordion {
            font-size: 17px;
        }
        #accordion h3 {
            font-weight: bolder;
            background: lightgoldenrodyellow;
        }
        #accordion div div h3 {
            color: #0d47a1;
            background: whitesmoke;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-section dashboard-tiles-area"></div>
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                                {{-- Page Title Start --}}
                                <?php if(isset($_SESSION['login'])) { ?>
                                    <div class="title-sec">
                                        <h5 class="unerline-title">Resources</h5>
                                        <!-- <a href="javascript:void(0)" class="quick-btn pull-right" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                        <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a href="{{base_url('resources/hand_book')}}" aria-current="page" class="nav-link {{(current_url() == base_url('support') || current_url() == base_url('resources/hand_book')) ? 'active' : ''}}">Hand Book</a>
                                        </li>
                                        <!-- <li class="nav-item">
                                            <a href="{{base_url('resources/video_tutorial/view')}}" aria-current="page" class="nav-link {{current_url() == base_url('resources/video_tutorial/view') ? 'active' : ''}}">Video Tutorial</a>
                                        </li> -->
                                        <li class="nav-item">
                                            <a href="{{base_url('resources/FAQ')}}" aria-current="page" class="nav-link {{current_url() == base_url('resources/FAQ') ? 'active' : ''}}">FAQ</a>
                                        </li>
                                        <!-- <li class="nav-item">
                                            <a href="{{base_url('resources/hand_book_old_efiling')}}" aria-current="page" class="nav-link {{current_url() == base_url('resources/hand_book_old_efiling') ? 'active' : ''}}">Refile Old Efiling Cases</a>
                                        </li> -->
                                        <li class="nav-item">
                                            <a href="{{base_url('resources/Three_PDF_user_manual')}}" aria-current="page" class="nav-link {{current_url() == base_url('resources/Three_PDF_user_manual') ? 'active' : ''}}">3PDF User Manual</a>
                                        </li>
                                    </ul>
                                <?php } ?>
                                {{-- Page Title End --}}
                                {{-- Main Start --}}
                                <div class="right_col" role="main">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div id="msg">
                                                <?php echo getSessionData('MSG'); ?>
                                            </div> 
                                        </div>
                                    </div>
                                    <!-- Breadcrumb area End  -->

                                    <!-- MAIN Inner Area Start  -->
                                    <div class="main-inner-area">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 ">
                                                    <div class="main-inner-bg">
                                                        <div class="inner-page-title">                
                                                            <?php if (getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN) { ?>
                                                                <h2><i class="fa  fa-newspaper-o"></i> Resources FAQ </h2>
                                                            <?php } else { ?>
                                                                <h5>Resources FAQ</h5>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="inner-pg-cont-area">
                                                            <div class="faq-sec">
                                                                <div class="accordion" id="accordion-1">
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="headingOne">
                                                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">REGISTRATION</button>
                                                                        </h2>
                                                                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordion-1">
                                                                            <div class="accordion-body">
                                                                                <div class="accordion" id="sub-accordionExample">
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-headingOne">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapseOne" aria-expanded="false" aria-controls="collapseOne">01. I am an Advocate on Record. how should I register myself?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapseOne" class="accordion-collapse collapse" aria-labelledby="sub-headingOne" data-bs-parent="#sub-accordionExample">
                                                                                            <div class="accordion-body">
                                                                                                <p>If the details of the AOR are already available with the Registry then such AOR is only required to create a new password in the new efiling Portal by taking the following steps:</p>
                                                                                                    <ol>
                                                                                                        <li>On the login page, click on forgot password option;</li>
                                                                                                        <li>Enter your registered mobile number, enter captcha and then click on send OTP button.</li>
                                                                                                        <li>OTP will be sent on your registered mobile number;</li>
                                                                                                        <li>Enter the OTP and click ‘verify’ button</li>
                                                                                                        <li>Create new password</li>
                                                                                                    </ol>
                                                                                                  <p> Once new password is set, the AOR can login by using his AOR code or registered mobile number or registered email id along with the new password.
                                                                                                  </p> 
                                                                                                <div class="faq-img-sec">
                                                                                                    <img src="<?php echo base_url('/uploaded_docs/faq_images/1.png'); ?>" alt="Registration" class="img-fluid">
                                                                                                    <!-- <h6 class="faq-img-title">Screenshot of Welcome Page of SC-EFM</h6> -->
                                                                                                </div>
                                                                                                <p>Please note: The password must be at least 8 characters long. It must also contain at least 1 Uppercase character, 1 lowercase character, 1 number, 1 special character each.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -2 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-headingTwo">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapseTwo" aria-expanded="false" aria-controls="sub-collapseTwo">02. I am an AOR and the portal is not accepting my mobile number for registration, what should I do?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapseTwo" class="accordion-collapse collapse" aria-labelledby="sub-headingTwo" data-bs-parent="#sub-accordionExample">
                                                                                            <div class="accordion-body">
                                                                                                <p>
                                                                                                    If your mobile number is not updated in records, it is requested to update your record by placing Specimen signature form available on www.sci.gov.in visit the ‘Record Room’ and get your mobile number updated in Records. Further, in case if your mobile number is already registered with a firm having another AOR code, it is requested to use a different mobile number to register yourself.
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -3 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-headingthree">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapsethree" aria-expanded="false" aria-controls="sub-collapsethree">03. I have completed registration and completed the profile; still I am not able to access the services. Why?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapsethree" class="accordion-collapse collapse"
                                                                                            aria-labelledby="sub-headingthree"
                                                                                            data-bs-parent="#sub-accordionExample">
                                                                                            <div class="accordion-body">
                                                                                                <p>
                                                                                                    Post registration, an AOR can directly access the e-Filing portal to file petition, documents, Caveat etc… However in the case of Advocate their profile needs to be approved and activated.
                                                                                                </p>
                                                                                                <p>In case of registration as an Advocate, their profile needs to be approved by an AOR by going on the three lines menu and then clicking on the ‘Add Advocate’ image shown in the below screen. </p>
                                                                                                <div class="faq-img-sec"><img src="<?php echo base_url('/uploaded_docs/faq_images/3.png'); ?>" alt="Registration" class="img-fluid"></div>
                                                                                                <p>The concerned AOR will receive a notification with an OTP/ Code inside their eFiling profile. If the concerned AOR approves the profile of the concerned Advocate, login will be activated.</p>
                                                                                                <p>In the case of registration as Petitioner-in-Person (PIP), the account is automatically approved and activated.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- -----  -->
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="heading-2">
                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-expanded="false" aria-controls="collapse-2">DASHBOARD</button>
                                                                        </h2>
                                                                        <div id="collapse-2" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordion-1" >
                                                                            <div class="accordion-body">
                                                                                <div class="accordion" id="sub-accordionExample-2">
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-1">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-1" aria-expanded="false" aria-controls="collapseOne">04. What does the dashboard display in SC-EFM ?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-1" class="accordion-collapse collapse" aria-labelledby="sub-heading-1" data-bs-parent="#sub-accordionExample-2">
                                                                                            <div class="accordion-body">
                                                                                                <p>Dashboard gives at-a-glance status of your cases which are soon to be listed under ‘My Cases’ head and your e-Filed Cases under the ‘eFiled Cases’ head. Further, you can view ‘Recent documents’ e-filed by you, ‘Incomplete filings’ and ‘Scrutiny Status’ of your cases i.e. whether at Defective Stage or Pending for Scrutiny. Screenshot attached as below:</p>
                                                                                                <div class="faq-img-sec"><img src="<?php echo base_url('/uploaded_docs/faq_images/4.png'); ?>" alt="Registration" class="img-fluid"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-2">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-2" aria-expanded="false" aria-controls="sub-collapse-2">05. Does the dashboard provide the list of my cases which are listed in near future?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-2" class="accordion-collapse collapse" aria-labelledby="sub-heading-2" data-bs-parent="#sub-accordionExample-2">
                                                                                            <div class="accordion-body">
                                                                                                <p>Dashboard provides details of cases which are to be listed cases in near future under the head “Soon to be listed cases" </p>
                                                                                                <div class="faq-img-sec"><img src="<?php echo base_url('/uploaded_docs/faq_images/5.png'); ?>" alt="Registration" class="img-fluid"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- -----  -->
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="heading-3">
                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3" aria-expanded="false" aria-controls="collapse-3">PROFILE UPDATION</button>
                                                                        </h2>
                                                                        <div id="collapse-3" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordion-1" >
                                                                            <div class="accordion-body">
                                                                                <div class="accordion" id="sub-accordionExample-3">
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-1">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-1" aria-expanded="false" aria-controls="collapseOne">06.  How can I view and update my profile?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-1" class="accordion-collapse collapse" aria-labelledby="sub-heading-1" data-bs-parent="#sub-accordionExample-3">
                                                                                            <div class="accordion-body">
                                                                                                <p>The profile can be viewed by clicking the user icon at the top right corner of the screen.The details of an AOR can be updated through Record Room Section of Supreme Court of India</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-2">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-2" aria-expanded="false" aria-controls="sub-collapse-2">07. Can I update my registered mobile number and registered email id?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-2" class="accordion-collapse collapse" aria-labelledby="sub-heading-2" data-bs-parent="#sub-accordionExample-3">
                                                                                            <div class="accordion-body">
                                                                                                <p>The details of an AOR can be updated through Record Room Section of Supreme Court of India</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-3">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-3" aria-expanded="false" aria-controls="sub-collapse-3">08. How can I change my password?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-3" class="accordion-collapse collapse" aria-labelledby="sub-heading-3" data-bs-parent="#sub-accordionExample-3">
                                                                                            <div class="accordion-body">
                                                                                                <p>You can change the password by clicking on “Change Password” after logging in below the user profile photo.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- -----  -->
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="heading-4">
                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4" aria-expanded="false" aria-controls="collapse-4">NEW CASE FILING / FINAL CASE SUBMISSION</button>
                                                                        </h2>
                                                                        <div id="collapse-4" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordion-1" >
                                                                            <div class="accordion-body">
                                                                                <div class="accordion" id="sub-accordionExample-4">
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-1">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-1" aria-expanded="false" aria-controls="collapseOne">09. I have filed a case, but I am not able to do any editing in the case?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-1" class="accordion-collapse collapse" aria-labelledby="sub-heading-1" data-bs-parent="#sub-accordionExample-4">
                                                                                            <div class="accordion-body">
                                                                                                <p>Any filed case is available for editing only before final submission. Once submitted, the case will not be available for editing. Please check whether you have already submitted the case.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-2">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-2" aria-expanded="false" aria-controls="sub-collapse-2">10.  For new case filing, do I need to complete all the 7 steps in one go?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-2" class="accordion-collapse collapse" aria-labelledby="sub-heading-2" data-bs-parent="#sub-accordionExample-4">
                                                                                            <div class="accordion-body">
                                                                                                <p>No, you can fill in the details or complete the 7 steps according to your convenience. Also the details can be edited multiple times. The draft case gets saved in your cases list which can be further processed later. Please ensure that all the details are correct before final submission. After Final Submission, the case details cannot be modified.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-3">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-3" aria-expanded="false" aria-controls="sub-collapse-3">11. I am an advocate; can I refile the matter of my AOR?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-3" class="accordion-collapse collapse" aria-labelledby="sub-heading-3" data-bs-parent="#sub-accordionExample-4">
                                                                                            <div class="accordion-body">
                                                                                                <p>No. Advocates login provides facility to only view the case details or the cases of his AOR. The case can be filed or refiled only through the login of the AOR.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- -----  -->
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="heading-5">
                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-5" aria-expanded="false" aria-controls="collapse-5">UPLOAD PETITION</button>
                                                                        </h2>
                                                                        <div id="collapse-5" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordion-1" >
                                                                            <div class="accordion-body">
                                                                                <div class="accordion" id="sub-accordionExample-5">
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-1">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-1" aria-expanded="false" aria-controls="collapseOne">12. Which file formats are allowed for uploading a petition? What is the maximum limit of the file size which can be uploaded?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-1" class="accordion-collapse collapse" aria-labelledby="sub-heading-1" data-bs-parent="#sub-accordionExample-5">
                                                                                            <div class="accordion-body">
                                                                                                <p>Only .pdf file format is allowed for uploading a petition through e-Filing Portal. The maximum file size that can be uploaded is 50 MB. If pdf file size is more than 50MB, file may split into parts for meeting the requirement.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-2">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-2" aria-expanded="false" aria-controls="sub-collapse-2">13. If an already signed physical document is scanned for uploading, do we need to re-authenticate it digitally?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-2" class="accordion-collapse collapse" aria-labelledby="sub-heading-2" data-bs-parent="#sub-accordionExample-5">
                                                                                            <div class="accordion-body">
                                                                                                <p>As of now it is not mandatory to sign the petition digitally.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-3">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-3" aria-expanded="false" aria-controls="sub-collapse-3">14. Are there any templates for petitions?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-3" class="accordion-collapse collapse" aria-labelledby="sub-heading-3" data-bs-parent="#sub-accordionExample-5">
                                                                                            <div class="accordion-body">
                                                                                                <p>It is requested to refer to the Supreme Court Rules, 2013 for the same which are available on the website under the bottom panel of the home page of Supreme Court website.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- -----  -->
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="heading-6">
                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-6" aria-expanded="false" aria-controls="collapse-6">SUBMISSION OF PETITION</button>
                                                                        </h2>
                                                                        <div id="collapse-6" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordion-1" >
                                                                            <div class="accordion-body">
                                                                                <div class="accordion" id="sub-accordionExample-6">
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-1">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-1" aria-expanded="false" aria-controls="collapseOne">15. I have completed all the formalities such as entering case details, uploading document(s) and court fee payment etc. How to submit the case to the court?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-1" class="accordion-collapse collapse" aria-labelledby="sub-heading-1" data-bs-parent="#sub-accordionExample-6">
                                                                                            <div class="accordion-body">
                                                                                                <p>To finally submit a case to the court, click on the ‘Submit’ button. History of all the activities that have been completed through the e-filing system for all the cases will be displayed under the ‘e-Filing History’ tab. The facility to view the uploaded petitions, court fee payment, in respect of the respective case is available under the e-Filed Cases report. Advocate can verify that all the details, court fee if any is in order under the view tab.
                                                                                                </p>
                                                                                                <P>If everything is in order and completed, the case can be finally submitted. Once a case is submitted, the diary number is automatically generated and the case gets transferred to the Registry for further action required.</P>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-2">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-2" aria-expanded="false" aria-controls="sub-collapse-2">16. Is there any facility to review case status after the case is submitted to the court?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-2" class="accordion-collapse collapse" aria-labelledby="sub-heading-2" data-bs-parent="#sub-accordionExample-6">
                                                                                            <div class="accordion-body">
                                                                                                <p>After submission, the court may take some time to verify the case. You can view the status by clicking on ‘Scrutiny status’ button in the dashboard. If the Scrutiny process is complete, your case will be shown under the defects notified button and you may cure your defects in the case using the ‘Cure defects’ option. You can also view the name of the Dealing Assistant to whom your matter has been marked for scrutiny.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- -----  -->
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="heading-7">
                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-7" aria-expanded="false" aria-controls="collapse-7">e-PAYMENT / COURT FEE SUBMISSION</button>
                                                                        </h2>
                                                                        <div id="collapse-7" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordion-1" >
                                                                            <div class="accordion-body">
                                                                                <div class="accordion" id="sub-accordionExample-7">
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-1">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-1" aria-expanded="false" aria-controls="collapseOne">17. Which types of payments can be done through the payment facility?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-1" class="accordion-collapse collapse" aria-labelledby="sub-heading-1" data-bs-parent="#sub-accordionExample-7">
                                                                                            <div class="accordion-body">
                                                                                                <p>Provision is made to make online payment towards Court Fees and allied charges through Stock Holding Corporation. The facility to pay the Court fees by debit card, credit card or UPI or Net Banking etc. has been incorporated.
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-2">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-2" aria-expanded="false" aria-controls="sub-collapse-2">18. I paid the court fees more than what was required to. How can I get it refunded in my account?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-2" class="accordion-collapse collapse" aria-labelledby="sub-heading-2" data-bs-parent="#sub-accordionExample-7">
                                                                                            <div class="accordion-body">
                                                                                                <p>Court fees once submitted successfully gets automatically locked and is transferred to the Consolidated fund of India. Hence, the amount once paid successfully cannot be refunded.
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-3">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-3" aria-expanded="false" aria-controls="collapseOne">19. Can I use the court fee deposited by mistake in a matter for another case to be e-filed?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-3" class="accordion-collapse collapse" aria-labelledby="sub-heading-3" data-bs-parent="#sub-accordionExample-7">
                                                                                            <div class="accordion-body">
                                                                                                <p>No. Court fees gets locked automatically once submitted and cannot be used for another matter.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-4">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-4" aria-expanded="false" aria-controls="sub-collapse-4">20. There was some problem during the online payment transaction of court fee. How can I check whether the payment was successful?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-4" class="accordion-collapse collapse" aria-labelledby="sub-heading-4" data-bs-parent="#sub-accordionExample-7">
                                                                                            <div class="accordion-body">
                                                                                                <p>If the transaction was done through the ‘payment’ menu, click on ‘Fee Paid’ tab, details of all the transactions will be provided here.  If there was some issue during the payment, you will see the payment status will be ‘pending’. You need to wait for atleat 30 minutes before making another attempt of payment. If the status is still pending after 30 minutes, you need to repeat the payment in such cases after confirming from your bank that the amount so was not deducted from your account. If the payment was successful, ‘Success’ status  is displayed.
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- -----  -->
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="heading-8">
                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-8" aria-expanded="false" aria-controls="collapse-8">INTERLOCUTORY APPLICATION FILING</button>
                                                                        </h2>
                                                                        <div id="collapse-8" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordion-1" >
                                                                            <div class="accordion-body">
                                                                                <div class="accordion" id="sub-accordionExample-8">
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-1">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-1" aria-expanded="false" aria-controls="collapseOne">21. Can I modify an application after I click on Submit?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-1" class="accordion-collapse collapse" aria-labelledby="sub-heading-1" data-bs-parent="#sub-accordionExample-8">
                                                                                            <div class="accordion-body">
                                                                                                <p>Any submitted application can be modified before final submission. Once the application is submitted, it cannot be modified.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-2">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-2" aria-expanded="false" aria-controls="sub-collapse-2">22. Can I file IAs for the cases which are not filed through e-Filing services?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-2" class="accordion-collapse collapse" aria-labelledby="sub-heading-2" data-bs-parent="#sub-accordionExample-8">
                                                                                            <div class="accordion-body">
                                                                                                <p>Yes, the AOR can file an IA for any case. The prerequisite is that the case must be pending in the account of the cases of that AOR.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- -----  -->
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="heading-9">
                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-9" aria-expanded="false" aria-controls="collapse-9">PORTFOLIO MANAGEMENT</button>
                                                                        </h2>
                                                                        <div id="collapse-9" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordion-1" >
                                                                            <div class="accordion-body">
                                                                                <div class="accordion" id="sub-accordionExample-9">
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-1">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-1" aria-expanded="false" aria-controls="collapseOne">23. Can I add my existing cases in the portfolio of eFiling services?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-1" class="accordion-collapse collapse" aria-labelledby="sub-heading-1" data-bs-parent="#sub-accordionExample-9">
                                                                                            <div class="accordion-body">
                                                                                                <p>No, existing cases can not be added in the account of SCeFM but advocates can file Miscellaneous documents/ Interlocutory applications in physically filed cases through SCeFM.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-2">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-2" aria-expanded="false" aria-controls="sub-collapse-2">24. I have already created a portfolio in my mobile app. Can I import those cases in the eFiling Services portfolio?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-2" class="accordion-collapse collapse" aria-labelledby="sub-heading-2" data-bs-parent="#sub-accordionExample-9">
                                                                                            <div class="accordion-body">
                                                                                                <p>No, cases in the portfolios of mobile apps cannot be imported in SCeFM.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-3">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-3" aria-expanded="false" aria-controls="collapseOne">25. How can case related updates be reflected in my account?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-3" class="accordion-collapse collapse" aria-labelledby="sub-heading-3" data-bs-parent="#sub-accordionExample-9">
                                                                                            <div class="accordion-body">
                                                                                                <p>Defects notified in the matters or defects cured in ICMIS are reflected automatically in every 15 minutes through CRON service in SCeFM.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-4">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-4" aria-expanded="false" aria-controls="sub-collapse-4">26. How can I search my cases?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-4" class="accordion-collapse collapse" aria-labelledby="sub-heading-4" data-bs-parent="#sub-accordionExample-9">
                                                                                            <div class="accordion-body">
                                                                                                <p>You can view your cases under ‘My Cases’ head and your eFiled Cases under the ‘eFiled Cases’ head. Multiple filter options are provided for searching cases. You may search the case using Case number, eFiling number, Cause title, filing stage of the case or other options under ‘Search’ box. All the cases matching the search criteria will be displayed. You may click on the efiling case number to view further details of the case.</p>
                                                                                                <div class="faq-img-sec"><img src="<?php echo base_url('/uploaded_docs/faq_images/26.png'); ?>" alt="Registration" class="img-fluid"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-5">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-5" aria-expanded="false" aria-controls="collapseOne">27. How can I remove a case from ‘My Cases’?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-5" class="accordion-collapse collapse" aria-labelledby="sub-heading-5" data-bs-parent="#sub-accordionExample-9">
                                                                                            <div class="accordion-body">
                                                                                                <p>Cases cannot be removed from the ‘My Cases’ list.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-6">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-6" aria-expanded="false" aria-controls="sub-collapse-6">28. Which other facilities are provided in the eFiling Services for managing the portfolio?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-6" class="accordion-collapse collapse" aria-labelledby="sub-heading-6" data-bs-parent="#sub-accordionExample-9">
                                                                                            <div class="accordion-body">
                                                                                                <ol type="i">
                                                                                                    <li> Change Password</li>
                                                                                                    <li>
                                                                                                        My Cases: Cases can be searched by using different filters like Pending matters, Disposed matters, Registered Matters, Unregistered Matters
                                                                                                        <div class="faq-img-sec"><img src="<?php echo base_url('/uploaded_docs/faq_images/29.png'); ?>" alt="Registration" class="img-fluid"></div>
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        To add and approve an advocate to view the cases filed on behalf of the AOR
                                                                                                        <div class="faq-img-sec"><img src="<?php echo base_url('/uploaded_docs/faq_images/29-2.png'); ?>" alt="Registration" class="img-fluid"></div>
                                                                                                    </li>
                                                                                                </ol>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-7">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-7" aria-expanded="false" aria-controls="collapseOne">29. How do I file Interlocutory Application or Misc. Document?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-7" class="accordion-collapse collapse" aria-labelledby="sub-heading-7" data-bs-parent="#sub-accordionExample-9">
                                                                                            <div class="accordion-body">
                                                                                                <p>The initial process for filing IA and filing Misc. documents is exactly same. Therefore, user can follow exact steps for both. The user needs to enter the Diary number in which the IA or document is to be efiled as in the screenshot below:
                                                                                                </p>
                                                                                                <div class="faq-img-sec"><img src="<?php echo base_url('/uploaded_docs/faq_images/30.png'); ?>" alt="Registration" class="img-fluid"></div>
                                                                                                <p>The portal then checks the status of the matter whether pending or disposed. If disposed, the portal will not allow the user to upload documents in the matter. If the matter is pending, then under the index tab, select the particular document type and then upload the relevant document required to be uploaded. The Index tab  is also to be filled in so that all the documents contained in the pdf so uploaded are updated in the Index. After submission of the Court fees, the IA or the Misc. Document will reach the Miscellaneous Dak Counter for further processing the same. </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-8">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-8" aria-expanded="false" aria-controls="sub-collapse-8">30. How do I file Caveat Application?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-8" class="accordion-collapse collapse" aria-labelledby="sub-heading-8" data-bs-parent="#sub-accordionExample-9">
                                                                                            <div class="accordion-body">
                                                                                                <p>By default ‘Caveator is - an Individual’ is kept selected. A user is required to fill the requisite details about the caveator whether it is an Individual, Central Government, State Government or other organization.  The field marked with red asterisk (*) are mandatory.
                                                                                                </p>
                                                                                                <p>There are six steps which are to be completed for efiling a Caveat as per the screenshot below:</p>
                                                                                                <div class="faq-img-sec"><img src="<?php echo base_url('/uploaded_docs/faq_images/31.png'); ?>" alt="Registration" class="img-fluid"></div>
                                                                                                <p>After completing the steps, click on the Submit button at the top and your Caveat will be submitted. In case of any defect raised by the Registry, you can view the status of the same under the ‘For Compliance’ menu. The defects are then to be removed and then to be resubmitted by uploading the defect free Caveat in the Upload Documents/ Index tab and then click on ‘Final Submit’ button.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-9">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-9" aria-expanded="false" aria-controls="collapseOne">31. I am an Intervenor, am I allowed to file an application using the SC-eFM?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-9" class="accordion-collapse collapse" aria-labelledby="sub-heading-9" data-bs-parent="#sub-accordionExample-9">
                                                                                            <div class="accordion-body">
                                                                                                <p>Yes, an Intervenor is allowed to file an application of intervention using the ‘IA’ option. The name of the Intervenor is to be entered and then may upload the relevant documents along with the court fee payment. The application then gets submitted and sent to the Miscellaneous Dak Counter for further processing.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- inner item -1 start  -->
                                                                                    <div class="accordion-item">
                                                                                        <h2 class="accordion-header" id="sub-heading-10">
                                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-collapse-10" aria-expanded="false" aria-controls="sub-collapse-10">32. How to cure defect of deficit court fees?</button>
                                                                                        </h2>
                                                                                        <div id="sub-collapse-10" class="accordion-collapse collapse" aria-labelledby="sub-heading-10" data-bs-parent="#sub-accordionExample-9">
                                                                                            <div class="accordion-body">
                                                                                                <p>For the payment of deficit Court fees in unregistered matters, the AOR/PIP can enter the amount under ‘WANT TO PAY MORE COURT FEE’ option and submit deficit Court fees.</p>
                                                                                                <div class="faq-img-sec"><img src="<?php echo base_url('/uploaded_docs/faq_images/33-1.png'); ?>" alt="Registration" class="img-fluid"></div>
                                                                                                <p>Further, for registered matters, the user should select ‘Misc. Doc’ option under NEW tab to pay deficit Court fees. Then the following steps have to be followed:-</p>
                                                                                                <p><u>Step 1:</u>  Enter Diary Number / Year after Selecting ‘New’ option. Thereafter, relevant information have to be provided in appropriate fields on the page. After filling up the information, submit the page.</p>
                                                                                                <div class="faq-img-sec"><img src="<?php echo base_url('/uploaded_docs/faq_images/33.png'); ?>" alt="Registration" class="img-fluid"></div>
                                                                                                <p><u>Step 2:</u> Select the party name by clicking on the checkbox.</p>
                                                                                                <p><u>Step 3:</u>  Now, upload the document by mentioning details of deficit Court fees to be paid and select ‘Court Fee’ in the ‘Index Item’ drop down. Now click on ‘Add’, then click ‘Next’ as per image below.</p>
                                                                                                <div class="faq-img-sec"><img src="<?php echo base_url('/uploaded_docs/faq_images/33-2.png'); ?>" alt="Registration" class="img-fluid"></div>
                                                                                                <p><u>Step 4:</u>  Enter the deficit Court fee amount in the box besides ‘want to pay more court fee’ option and submit </p>
                                                                                                <div class="faq-img-sec"><img src="<?php echo base_url('/uploaded_docs/faq_images/33-3.png'); ?>" alt="Registration" class="img-fluid"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                {{-- Main End --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script src="<?= base_url() . 'assets/' ?>js/jquery-ui.js"></script>
<script>
    $(document).ready(function(){
        $( function() {
            $( "#accordion" ).accordion({heightStyle: "content",collapsible: true}).on("click", "h3.ui-accordion-header", function(e) {
                $("h3.ui-accordion-header").each(function(i, el) {
                    $(this).find(".panel-icon").text($(el).is(".ui-state-active") ? "-" : "+")
                })
            });
            $( "#accordion1" ).accordion({heightStyle: "content",collapsible: true}).on("click", "h3.ui-accordion-header", function(e) {
                $("h3.ui-accordion-header").each(function(i, el) {
                    $(this).find(".panel-icon").text($(el).is(".ui-state-active") ? "-" : "+")
                })
            });
            $( "#accordion2" ).accordion({heightStyle: "content",collapsible: true}).on("click", "h3.ui-accordion-header", function(e) {
                $("h3.ui-accordion-header").each(function(i, el) {
                    $(this).find(".panel-icon").text($(el).is(".ui-state-active") ? "-" : "+")
                })
            });
            $( "#accordion3" ).accordion({heightStyle: "content",collapsible: true}).on("click", "h3.ui-accordion-header", function(e) {
                $("h3.ui-accordion-header").each(function(i, el) {
                    $(this).find(".panel-icon").text($(el).is(".ui-state-active") ? "-" : "+")
                })
            });
            $( "#accordion4" ).accordion({heightStyle: "content",collapsible: true}).on("click", "h3.ui-accordion-header", function(e) {
                $("h3.ui-accordion-header").each(function(i, el) {
                    $(this).find(".panel-icon").text($(el).is(".ui-state-active") ? "-" : "+")
                })
            });
            $( "#accordion5" ).accordion({heightStyle: "content",collapsible: true}).on("click", "h3.ui-accordion-header", function(e) {
                $("h3.ui-accordion-header").each(function(i, el) {
                    $(this).find(".panel-icon").text($(el).is(".ui-state-active") ? "-" : "+")
                })
            });
            $( "#accordion6" ).accordion({heightStyle: "content",collapsible: true}).on("click", "h3.ui-accordion-header", function(e) {
                $("h3.ui-accordion-header").each(function(i, el) {
                    $(this).find(".panel-icon").text($(el).is(".ui-state-active") ? "-" : "+")
                })
            });
            $( "#accordion7" ).accordion({heightStyle: "content",collapsible: true}).on("click", "h3.ui-accordion-header", function(e) {
                $("h3.ui-accordion-header").each(function(i, el) {
                    $(this).find(".panel-icon").text($(el).is(".ui-state-active") ? "-" : "+")
                })
            });
            $( "#accordion8" ).accordion({heightStyle: "content",collapsible: true}).on("click", "h3.ui-accordion-header", function(e) {
                $("h3.ui-accordion-header").each(function(i, el) {
                    $(this).find(".panel-icon").text($(el).is(".ui-state-active") ? "-" : "+")
                })
            });
            $( "#accordion9" ).accordion({heightStyle: "content",collapsible: true}).on("click", "h3.ui-accordion-header", function(e) {
                $("h3.ui-accordion-header").each(function(i, el) {
                    $(this).find(".panel-icon").text($(el).is(".ui-state-active") ? "-" : "+")
                })
            });
        });
    });
</script>
@endpush