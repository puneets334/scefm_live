@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 sm-12 col-md-12 col-lg-12  ">
            <div class="center-content-inner comn-innercontent">
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                                <div class="row">
                                    <div class="col-12">
                                        <?php if (session()->getFlashdata('msg')) : ?>
                                            <div class="alert alert-dismissible text-center flashmessage">
                                                <div class="flas-msg-inner">
                                                    <?= session()->getFlashdata('msg') ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (session()->getFlashdata('deactivate_msg')) : ?>
                                            <div class="alert alert-danger text-center flashmessage">
                                                <div class="flas-msg-inner">
                                                    <?= session()->getFlashdata('deactivate_msg') ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="title-sec">
                                    <h5 class="unerline-title">
                                        <?= 'Profile' ?>
                                        <?php
                                      
                                        if (!empty($profile[0])) {
                                            $user_type = '';
                                            if ($profile[0]['ref_m_usertype_id'] == USER_ADVOCATE) {
                                                $user_type = '(Advocate)';
                                            } elseif ($profile[0]['ref_m_usertype_id'] == USER_IN_PERSON) {
                                                $user_type = '(Party-in-Person)';
                                            }
                                            echo htmlentities($user_type, ENT_QUOTES);
                                        }
                                        ?>
                                    </h5>
                                </div>
                                <div class="profile-details">
                                    <div class="row mt-4">
                                        <div class="col-12 col-sm-12 col-md-2 col-lg-1">
                                            <div class="usrprofile-img">
                                                <!-- <img src="http://localhost/supremeCourt/assets/images/user.png" alt=""> -->
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-10 col-lg-11">
                                            <div class="usrprofile-details">
                                                <div class="row">
                                                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="row">
                                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                                <label class="prof-label">Name :</label>
                                                            </div>
                                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                                <div class="prof-outp">
                                                                    <?= strtoupper($profile[0]['first_name']); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="row">
                                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                                <label class="prof-label">Bar Registration No :</label>
                                                            </div>
                                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                                <div class="prof-outp">
                                                                    <?php echo strtoupper(htmlentities($profile[0]['bar_reg_no'], ENT_QUOTES)); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="row">
                                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                                <label class="prof-label">Gender :</label>
                                                            </div>
                                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                                <div class="prof-outp">
                                                                    <?= $profile[0]['gender']; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="row">
                                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                                <label class="prof-label">Office Address :</label>
                                                            </div>
                                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                                <div class="prof-outp">
                                                                    <?php
                                                                    if ($profile[0]['m_address1'] != '') {
                                                                        $address1 = ', ' . $profile[0]['m_address1'];
                                                                    }


                                                                    if ($profile[0]['m_pincode'] != '') {
                                                                        $pincode = '- ' . $profile[0]['m_pincode'];
                                                                    } else {
                                                                        $pincode = ', N/A';
                                                                    }

                                                                    echo strtoupper(htmlentities(ucwords($profile[0]['m_address1'] . ' , ' . $profile[0]['m_city'] . $pincode), ENT_QUOTES));
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="row">
                                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                                <label class="prof-label">Date of Birth :</label>
                                                            </div>
                                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                                <div class="prof-outp">
                                                                    <?= !empty($profile[0]['dob']) ? ucwords(htmlentities(date("d-m-Y", strtotime($profile[0]['dob'])), ENT_QUOTES)) : 'N/A'; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="row">
                                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                                <label class="prof-label">Requested on :</label>
                                                            </div>
                                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                                <div class="prof-outp">
                                                                    <?php
                                                                    if ($profile[0]['created_on']) {
                                                                        echo ucwords(htmlentities(date("d-m-Y h:i:s A", strtotime($profile[0]['created_on'])), ENT_QUOTES));
                                                                    } else {
                                                                        echo 'N/A';
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="row">
                                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                                <label class="prof-label">Mobile :</label>
                                                            </div>
                                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                                <div class="prof-outp">
                                                                    <?php
                                                                    if ($profile[0]['moblie_number'] == '') {
                                                                        $mobile = '<span style="color:red;">' . htmlentities('Update Your Mobile No.', ENT_QUOTES) . '</span>';
                                                                    } else {
                                                                        $mobile = htmlentities($profile[0]['moblie_number'], ENT_QUOTES);
                                                                    }
                                                                    echo $mobile;
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="row">
                                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                                <label class="prof-label">Email :</label>
                                                            </div>
                                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                                <div class="prof-outp">
                                                                    <?php
                                                                    if (strpos($profile[0]['emailid'], '@')) {
                                                                        $email = htmlentities($profile[0]['emailid'], ENT_QUOTES);
                                                                    } else {
                                                                        $email = '<span style="color:red;">' . htmlentities('Update Your Email ID ', ENT_QUOTES) . '</span>';
                                                                    }
                                                                    echo $email;
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="profile-status-div">
                                                <?php
                                                if ($profile[0]['is_active'] == 0) { ?>
                                                    <div class="alert alert-danger">DEACTIVATED</div>
                                                    <?php
                                                }
                                                if ($profile[0]['is_active'] == 1) {
                                                    ?>
                                                    <div class="alert alert-success">ACTIVATED</div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $uri = service('uri');
                                    $uri_segment = htmlentities(trim($uri->getSegment(4)), ENT_QUOTES);
                                    ?>
                                    <div class="center-buttons">
                                        <a href="<?= base_url('NewRegister/Advocate/activate/') . $uri_segment ?>" class="quick-btn">APPROVE</a>
                                        <a href="<?= base_url('NewRegister/Advocate/deactivate/') . $uri_segment ?>" class="quick-btn gray-btn">DEACTIVATE</a>
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

@endsection