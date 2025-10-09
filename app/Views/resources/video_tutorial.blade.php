<?php 
if(isset($_SESSION['login'])) {
    $ex = 'layout.advocateApp';
} else{
    $ex = 'layout.frontApp';
}
?>
@extends($ex)
@section('content')
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
                                <?php 
                                // $video_details = array(
                                //     array("videoURL"=>"uploaded_docs/video_tutorial/Accessing-e-Filing-Portal.mp4","videoTitle"=>"How to access e-Filing Module/Portal","posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                //     array("videoURL"=>"uploaded_docs/video_tutorial/Registration-of-Advocate-on-Record.mp4","videoTitle"=>"Registration of AoR" ,"posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                //     array("videoURL"=>"uploaded_docs/video_tutorial/Registration-of-PIP.mp4" ,"videoTitle"=>"Registration of Party-in-Person" ,"posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                //     array("videoURL"=>"uploaded_docs/video_tutorial/New-Case-Filing.mp4","videoTitle"=>"How to e-File a New Case","posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                //     array("videoURL"=>"uploaded_docs/video_tutorial/IA.mp4","videoTitle"=>"How to e-File an I.A.","posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                //     array("videoURL"=>"uploaded_docs/video_tutorial/Filing-Misc-Docs.mp4","videoTitle"=>"How to e-file a Miscellaneous Document","posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                //     array("videoURL"=>"uploaded_docs/video_tutorial/Filing-Caveat.mp4","videoTitle"=>"How to e-File Caveat","posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                //     array("videoURL"=>"uploaded_docs/video_tutorial/Check-defects-marked-and-refile.mp4","videoTitle"=>"How to Check Defects and Re-File","posterURL"=>"uploaded_docs/video_tutorial/poster.jpg")
                                // );
                                $video_details = array(
                                    array("videoURL"=>"uploaded_docs/video_tutorial/AOR-Sign-In-Video.mp4","videoTitle"=>"How to access e-Filing Module/Portal","posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                    array("videoURL"=>"uploaded_docs/video_tutorial/AOR-Sign-In-Video.mp4","videoTitle"=>"Registration of AoR" ,"posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                    array("videoURL"=>"uploaded_docs/video_tutorial/AOR-Sign-In-Video.mp4" ,"videoTitle"=>"Registration of Party-in-Person" ,"posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                    array("videoURL"=>"uploaded_docs/video_tutorial/File-A-New-Case.mp4","videoTitle"=>"How to e-File a New Case","posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                    array("videoURL"=>"uploaded_docs/video_tutorial/File-IA.mp4","videoTitle"=>"How to e-File an I.A.","posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                    array("videoURL"=>"uploaded_docs/video_tutorial/File-Misc-Docs.mp4","videoTitle"=>"How to e-file a Miscellaneous Document","posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                    array("videoURL"=>"uploaded_docs/video_tutorial/File-Caveat.mp4","videoTitle"=>"How to e-File Caveat","posterURL"=>"uploaded_docs/video_tutorial/poster.jpg"),
                                    array("videoURL"=>"uploaded_docs/video_tutorial/File-A-New-Case.mp4","videoTitle"=>"How to Check Defects and Re-File","posterURL"=>"uploaded_docs/video_tutorial/poster.jpg")
                                );
                                ?>
                                <div class="main-inner-area">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 ">
                                                <div class="main-inner-bg">
                                                    <div class="right_col" role="main">
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                <div id="msg">
                                                                    <?php echo getSessionData('MSG'); ?>
                                                                </div> 
                                                            </div>
                                                        </div>    
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12 col-xs-12">            
                                                                <div class="inner-page-title">              
                                                                    <?php if (getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN) { ?>
                                                                        <div class="x_title">
                                                                            <h2><i class="fa  fa-newspaper-o"></i> Resources Video Tutorial</h2>
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <!-- <h2><i class="fa  fa-newspaper-o"></i> Resources Video Tutorial</h2>
                                                                        <div class="clearfix"></div> -->
                                                                        <h5>Resources Video Tutorial</h5>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>        
                                                        <!------------Table--------------------->
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                <div class="x_panel">
                                                                    <div class="x_content">
                                                                        <div class="row">
                                                                            <?php foreach($video_details as $key=>$value) { ?>
                                                                                <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                                                                                    <div class="card video-crd">
                                                                                        <!-- <div class="bg-image hover-overlay ripple shadow-1-strong rounded" data-ripple-color="light" > -->
                                                                                            <a href="#" onclick="playVideo('<?=base_url();?><?=$value['videoURL'];?>','<?=$value['videoTitle'];?>')">
                                                                                                <div class="img-sec">
                                                                                                    <img class="" src="<?=base_url();?><?=$value['posterURL'];?>" alt="Introduction to eFiling">
                                                                                                </div>
                                                                                                <h5 class="card-title"><?=$value['videoTitle'];?></h5>
                                                                                            </a>
                                                                                        <!-- </div> -->
                                                                                    </div>                                                                
                                                                                </div>
                                                                            <?php } ?>
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
                                <!-- start modal -->
                                <div class="modal" id="vd_screen">
                                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document" id="vd_dialog">
                                        <div class="modal-content  ">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="vd_title">Resources Video Tutorial</h5>
                                            </div>
                                            <div class="modal-body text-center" id="vd_body"></div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end modal -->
                                {{-- Main End --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script>
    function playVideo(path,videoTitle) {
        $('#vd_body').html('');
        $('#vd_title').html('');
        // var str="<object data="+path +"></object>";
        // alert('path='+path+'flag='+flag);
        var str='<div class="videoWrapper"><iframe width="100%" height="450" src="'+path+'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen ></iframe></div>';
        // var str='<video autoplay="" controls="" style="width:100%"> <source src='+path+' type="video/mp4"></video>';
        $('#vd_screen').modal('show');
        $('#vd_body').html(str);
        $('#vd_title').html(videoTitle);
        $('#vd_dialog').removeClass('modal-md');
        $('#vd_dialog').addClass('modal-lg');
        $('#vd_dialog').addClass('modal-dialog-scrollable');
    }
    $('#vd_screen .close').click(function() {
        // alert('aaa')
        $('#vd_body').html('');
        $('#vd_screen').modal('hide');
    });
</script>