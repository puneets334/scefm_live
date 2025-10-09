<?php
if(isset(session()->get('login')['impersonator_user']) && isset(session()->get('login')['impersonator_user']->is_active) && !empty(session()->get('login')['impersonator_user']) && session()->get('login')['impersonator_user']->is_active == 1){
    $extends = 'layout.advocateApp';
}else{
    $extends = 'layout.ecopyApp';
} ?>
@extends($extends)
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="dash-card dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class=" dashboard-bradcrumb">
                                <div class="left-dash-breadcrumb">
                                    <div class="page-title">
                                        <h5><i class="fa fa-file"></i> User Verification</h5>
                                    </div>
                                    <div class="form-response" id="msg" role="alert" data-auto-dismiss="5000"></div>
                                </div>
                                <div class="ryt-dash-breadcrumb">
                                    <div class="btns-sec">
                                        <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dash-card dashboard-section">
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="httxt">
                                <h4>Verify Your Self</h4>
                            </div>
                            <div class="loin-form">
                                <?php
                                
                                $attribute = array('class' => 'uk-form-horizontal uk-margin-large', 'name' => 'advocate_reg_info', 'id' => 'advocate_reg_info', 'autocomplete' => 'off', 'enctype' => "multipart/form-data");
                                // echo form_open('register/AdvSignUp/upload_id_proof', $attribute);
                                echo form_open('#', $attribute);
                                ?>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="mb-3">
                                                    <label class="radio-inline mb-2"><input type="radio" name="optradio" checked> &nbsp;&nbsp;Upload ID Proof </label>
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                                                            <input accept=".jpg, .jpeg" class="form-control cus-form-ctrl"  type="file" required id="advocate_image" name="advocate_image" onchange="validateImage()">
                                                        </div>
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                                                            <input type="submit" value="UPLOAD" id="info_save" class="btnSubmit btn btn-sm quick-btn btn-primary" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (!empty($_SESSION['image_and_id_view']['profile_photo'])) { ?>
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 ">
                                                    <div class="mb-3">
                                                        <div class="veryfy-img-sec">
                                                            <img src="<?php echo $_SESSION['image_and_id_view']['profile_photo']; ?>" width="250" height="250" alt="profile photo" style="border: 1px solid;">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-6">
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="mb-3">
                                                    <label class="radio-inline mb-2"><input type="checkbox" name="ecopyingCheck" id="ecopyingCheck" value="1">&nbsp;&nbsp;Applying for eCopying</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="mb-3">
                                                    <div class="card-body">
                                                        <div class="row m-1" style="display: none;">
                                                            <div class="alert alert-warning alert-dismissible">
                                                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                                <strong>Unless there is request for Certified Copy/Digital Copy, your identity as claimed will not be verified.</strong>
                                                            </div>
                                                        </div>
                                                        <div class="row m-1" style="display: none;">
                                                            <div class="alert alert-warning alert-dismissible">
                                                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                                <strong>Remove mask, spectacles or any other face covering.</strong>
                                                            </div>
                                                        </div>
                                                        <div class="left" style="display: none;">
                                                            After you click start recording speak following things and record :<br>
                                                            1. Speak your full name<br>
                                                            2. Speak date of birth<br>
                                                            3. Speak this Code : <span class="font-weight-bolder text-danger"><?php echo $_SESSION['text_speak'] = rand_string(5); ?></span>
                                                            <br>
                                                            <div id="startButton" class="btn btn-primary mt-2">
                                                                Start Recording
                                                            </div>
                                                        </div>
                                                        <div class="center">
                                                            <div id="stopButton" class="button" style="display:none;">
                                                                Stop
                                                            </div>
                                                            <div id="cam_preview" style="display:none;">
                                                                <h6 class="font-weight-bolder">Recording Started </h6>
                                                                <video  id="preview" width="340px" autoplay muted></video>
                                                            </div>
                                                            <div id="view_recording" style="display:none;">
                                                                <h6 class="font-weight-bolder">Preview</h6>
                                                                <video id="recording" width="340px" controls></video>
                                                            </div>
                                                        </div>
                                                        <div class="bottom">
                                                            <pre id="log"></pre>
                                                        </div>
                                                        <!-- <form  method="post" enctype="multipart/form-data">
                                                            <a id="downloadButton" class="button" style="display:none;">
                                                                Download
                                                            </a>
                                                            <div id="video_action" class="left1" style="display:none;">
                                                                <a href="user_video.php" class="btn btn-primary" >
                                                                    Try Again!
                                                                </a>
                                                                <a id="saveButton" class="btn btn-primary"  style="display:none;">
                                                                    Save & Next
                                                                </a>
                                                            </div>
                                                        </form> -->
                                                        <div class="row show_msg col-12 mb-3"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                            <div class="center-buttons mb-0">
                                                <?php if (!empty($_SESSION['image_and_id_view']['profile_photo'])) {  ?>
                                                    <a href="<?php echo base_url('register/AdvSignUp/final_submit_ecopying'); ?>" class="quick-btn" id="finalBtn"> FINAL SUBMIT </a>
                                                    <!-- <a href="<?php // echo base_url('register/AdvSignUp/final_submit'); ?>" class="quick-btn" id="finalBtn"> FINAL SUBMIT </a> -->
                                                    <a href="javascript:;" class="quick-btn" id="saveButton" style="display:none;"> FINAL SUBMIT </a>
                                                <?php }  ?>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@push('script')

<script>
    $(document).ready(function() {
        $('#loader-wrapper').fadeOut('slow', function() {
            $('#content').fadeIn('slow');
        });
    }
    $(document).ready(function() {
        $('#loader-wrapper').show();
        var loaderTimeout = setTimeout(function() {
            $('#loader-wrapper').fadeOut('slow', function() {
                $('#content').fadeIn('slow');
            });
        }, 1000);
        $(window).on('load', function() {
            clearTimeout(loaderTimeout);
            $('#loader-wrapper').fadeOut('slow', function() {
                $('#content').fadeIn('slow');
            });
        });
    });
    function validateImage() {
        var fileInput = document.getElementById('advocate_image');
        var filePath = fileInput.value;
        var allowedExtensions = /(\.jpg|\.jpeg)$/i;
        var maxSize = 1 * 1024 * 1024; // 2MB in bytes        
        // Check file type
        if (!allowedExtensions.exec(filePath)) {
            alert('Please upload a file with .jpg or .jpeg extension.');
            fileInput.value = ''; // Clear the input
            return false;
        }        
        // Check file size
        if (fileInput.files[0].size > maxSize) {
            alert('The file size should not exceed 1 MB.');
            fileInput.value = ''; // Clear the input
            return false;
        }
    }
    var preview_display = document.getElementById("cam_preview");
    var recording_display = document.getElementById("view_recording");
    var preview = document.getElementById("preview");
    var recording = document.getElementById("recording");
    var startButton = document.getElementById("startButton");
    // let tryAgain = document.getElementById("tryAgain");
    var stopButton = document.getElementById("stopButton");
    var downloadButton = document.getElementById("downloadButton");
    var logElement = document.getElementById("log");

    var myfile = document.getElementById("myfile");
    var recordedBlob = '';
    recording_display.style.visibility = "hidden";
    var recordingTimeMS = 10000;

    function log(msg) {
        logElement.innerHTML += msg + "\n";
    }

    function wait(delayInMS) {
        return new Promise(resolve => setTimeout(resolve, delayInMS));
    }

    function startRecording(stream, lengthInMS) {
        let recorder = new MediaRecorder(stream);
        let data = [];

        recorder.ondataavailable = event => data.push(event.data);
        recorder.start();
        log(recorder.state + " for " + (lengthInMS / 1000) + " seconds...");

        let stopped = new Promise((resolve, reject) => {
            recorder.onstop = resolve;
            recorder.onerror = event => reject(event.name);
        });

        let recorded = wait(lengthInMS).then(
            () => recorder.state == "recording" && recorder.stop()
        );

        return Promise.all([
                stopped,
                recorded
            ])
            .then(() => data);
    }

    function stop(stream) {
        stream.getTracks().forEach(track => track.stop());
    }
    startButton.addEventListener("click", function() {
        $("#cam_preview").show();
        $("#startButton").hide();
        //$("#stopButton").show();

        navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            }).then(stream => {
                preview.srcObject = stream;
                downloadButton.href = stream;
                preview.captureStream = preview.captureStream || preview.mozCaptureStream;
                return new Promise(resolve => preview.onplaying = resolve);
            }).then(() => startRecording(preview.captureStream(), recordingTimeMS))
            .then(recordedChunks => {
                preview_display.style.visibility = "hidden";
                recording_display.style.visibility = "visible";
                $("#cam_preview").hide();
                $("#view_recording").show();
                $("#video_action").show();
                recordedBlob = new Blob(recordedChunks, {
                    type: "video/webm"
                });

                recording.src = URL.createObjectURL(recordedBlob);
                downloadButton.href = recording.src;
                downloadButton.download = "RecordedVideo.webm";
                log("Successfully recorded " + recordedBlob.size + " bytes of " + recordedBlob.type + " media.");
                //log("Successfully recorded");

                // sendBlobToServer(recordedBlob);
            })
            .catch(log);
    }, false);
    stopButton.addEventListener("click", function() {
        stop(preview.srcObject);
        $('#saveButton').show();
        $('#finalBtn').hide();
    }, false);
    $(document).on("click", "#saveButton", function () {
        var filename = "RecordedVideo.webm";
        var fd1 = new FormData();
        fd1.append("file", recordedBlob, filename);
        $.ajax
        ({
            url: "<?php echo base_url('register/AdvSignUp/final_submit_ecopying'); ?>",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data:fd1,
            type: 'post',
            beforeSend: function () {
                $('.show_msg').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
            },
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            success: function(data)
            {
                if(data.status == 'success'){
                    swal({
                        title: "SUCCESS!",
                        text: "Video Uploaded Successfully",
                        icon: "success",
                        button: "Next",
                    }).then(function () {
                        $.redirect('user_image.php');
                    }
                    );
                }else{
                    $('.show_msg').html('<div class="alert alert-danger alert-dismissible"><strong>'+data.status+'</strong></div>');
                }
            }
        });
    });
    $(document).on('click', '#ecopyingCheck', function(){
        if($(this).is(":checked")) {
            $('.left').show();
        } else {
            $('.left').hide();  
        }
    })
</script>
@endpush
