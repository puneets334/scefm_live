@extends('layout.frontApp')
@section('content')

<style type="text/css">
    @media only screen and (min-width: 960px) {
        body{
            overflow-x:hidden;
        }
    }

</style>

<?php
//var_dump($_SESSION);
//echo $_SESSION['register_data']['password'];
$star_requered = '<span style="color: red">*</span>'; ?>
<?php $session = session(); ?>
<div class="login-area full-loginarea-pg">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 login-banner d-none">
                <div class="login-banner-inner">
                    <div class="banimg-sec">
                        <!-- <img src="<?= base_url() . 'assets/newDesign/' ?>images/SCI-banner.png" alt="Banner Image" title="Supreme Court of India" class="img-fluid"> -->
                        <img src="<?= base_url() . 'assets/newDesign/' ?>images/logo-full.png" alt="" class="img-fluid  logo-at-banner">

                    </div>
                    <div class="banner-txts">
                        <h5>SC-EFM </h5>
                        <h6>E-Filing Module</h6>
                        <h6>Supreme Court Of India</h6>
                    </div>
                </div>
            </div>
<?php $session = session(); ?>
                    <?php if($session->getFlashData('msg') == 'Successful.'){ ?>
                        <div class="alert alert-success text-center" role="alert" id="" data-auto-dismiss="5000">
                        ID Proof Uploaded Successfully.</div>
                    </div>
                    <?php } else { ?>
                        <div class="text-danger">
                            <b><?php echo $session->getFlashData('msg'); ?></b>
                        </div>
                    <?php } ?>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 login-section">
                <div class="login-s-inner">
                    
                    <div class="httxt">
                        <h4>Verify Your Self</h4>
                    </div>
                    <div class="loin-form">
                        <?php
                        $attribute = array('class' => 'uk-form-horizontal uk-margin-large', 'name' => 'advocate_reg_info', 'id' => 'advocate_reg_info', 'autocomplete' => 'off', 'enctype' => "multipart/form-data");
                        echo form_open('register/AdvSignUp/upload_id_proof', $attribute);
                        ?>
                            <input type="text" style="display: none;" name="_token" value="{{ csrf_token() }}">
                            <div class="row">
                                
                                <div class="col-12 col-md-6">
                                    
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
                                <div class="col-12 col-md-6">
                                    <!-- <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label class="radio-inline mb-2"><input type="checkbox" name="ecopyingCheck" id="ecopyingCheck" value="1">&nbsp;&nbsp;Applying for eCopying</label>
                                        </div>
                                    </div> -->
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
                                                <div class="left" >
                                                    After you click start recording speak following things and record :<br>
                                                    1. Speak your full name<br>
                                                    2. Speak date of birth<br>
                                                    3. Speak this Code : <span class="font-weight-bolder text-danger"><?php echo $_SESSION['text_speak'] = rand_string(5); ?></span>
                                                    <br>
                                                    <div id="startButton" class="btn btn-primary btn-sm col-3 mt-2 text-nowrap w-auto">
                                                        Start Recording
                                                    </div>
                                                </div>

                                                <div class="center">
                                                    <div id="stopButton" class="button" style="display:none;">
                                                        
                                                    </div>
                                                    <div id="cam_preview" style="display:none;">
                                                        <h6 class="font-weight-bolder"> </h6>
                                                        <video  id="preview" width="340px" autoplay muted></video>
                                                    </div>
                                                    <div id="view_recording" style="display:none;">
                                                        <h6 class="font-weight-bolder"></h6>
                                                        <video id="recording" width="340px" controls></video>
                                                    </div>
                                                </div>
                                                <div class="bottom">
                                                    <pre id="log"></pre>
                                                </div>
                                                 <form  method="post" enctype="multipart/form-data">
                                                    <a id="downloadButton" class="button" style="display:none;">
                                                        Download
                                                    </a>
                                                    <div id="video_action" class="left1 col-2 text-nowrap w-auto" style="display:none;">
                                                        <div id="tryAgain" class="btn btn-sm btn-primary mt-2">
                                                            Try Again!
                                                        </div>
                                                        
                                                    </div>
                                                </form> 
                                                <div class="row show_msg col-12 mb-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                    <!-- <div class="center-buttons mb-0"> -->
                                        <?php if (!empty($_SESSION['image_and_id_view']['profile_photo'])) { ?>
                                            <!-- <a href="<?php echo base_url('register/AdvSignUp/final_submit'); ?>" class="quick-btn" id="finalBtn"> FINAL SUBMIT </a> -->
                                            <a id="saveButton" class="btn btn-primary" style="display: none;">
                                            FINAL SUBMIT
                                            </a>
                                        <?php } ?>
                                    <!-- </div> -->
                                </div>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@push('script')

<script>
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

    let preview_display = document.getElementById("cam_preview");
    let recording_display = document.getElementById("view_recording");
    let preview = document.getElementById("preview");
    let recording = document.getElementById("recording");
    let startButton = document.getElementById("startButton");
    let tryAgain = document.getElementById("tryAgain");
    let stopButton = document.getElementById("stopButton");
    let downloadButton = document.getElementById("downloadButton");
    let logElement = document.getElementById("log");

    let myfile = document.getElementById("myfile");
    let recordedBlob = '';
    recording_display.style.visibility = "hidden";
    let recordingTimeMS = 10000;

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
                // preview_display.style.visibility = "hidden";
                // recording_display.style.visibility = "visible";
                preview_display.style.visibility = "hidden";
                preview_display.style.display = "none";
                recording_display.style.visibility = "visible";
                recording_display.style.display = "block";
                stopButton.style.display = "block";
                tryAgain.style.display = "block";
                $("#cam_preview").hide();
                $("#view_recording").show();
                $("#video_action").show();
                recordedBlob = new Blob(recordedChunks, {
                    type: "video/webm"
                });

                recording.src = URL.createObjectURL(recordedBlob);
                recording.autoplay = true;
                downloadButton.href = recording.src;
                downloadButton.download = "RecordedVideo.webm";
                log("Successfully recorded " + recordedBlob.size + " bytes of " + recordedBlob.type + " media.");
                //log("Successfully recorded");
                $('#saveButton').show();
                // sendBlobToServer(recordedBlob);
            })
            .catch(err => {
                log("Error: " + err.message);
                // Reset UI on error
                preview_display.style.visibility = "visible";
                preview_display.style.display = "block";
                recording_display.style.visibility = "hidden";
                recording_display.style.display = "none";
                startButton.style.display = "block";
            });
    }, false);

    tryAgain.addEventListener("click", function() {
        // Reset UI elements
        preview_display.style.visibility = "visible";
        preview_display.style.display = "block";
        recording_display.style.visibility = "hidden";
        recording_display.style.display = "none";
        startButton.style.display = "block";
        stopButton.style.display = "none";
        tryAgain.style.display = "none";
$('#saveButton').hide();
        // Clear existing video source
        recording.src = "";
        if (preview.srcObject) {
            stop(preview.srcObject);
            preview.srcObject = null;
        }

        // Clear recorded blob and log
        recordedBlob = '';
        logElement.innerHTML = '';
    }, false);

    stopButton.addEventListener("click", function() {
        stop(preview.srcObject);
        $('#saveButton').show();
        $('#finalBtn').hide();
    }, false);



    $(document).on("click", "#saveButton", function () {
    var filename = "RecordedVideo.webm";
    var fd1 = new FormData();
    
    fd1.append("file", recordedBlob,filename);
    
    // Include CSRF token if required
    var csrfToken ="<?php echo csrf_token()?>"; // Adjust according to your setup
    //fd1.append('csrf_token_name', csrfToken); // Replace 'csrf_token_name' with the actual name used in your application
    $.ajax({
        url: "<?php echo base_url('register/AdvSignUp/final_submit_ecopying'); ?>",
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        data: fd1,
        type: 'post',
        beforeSend: function () {
            $('.show_msg').html('<table width="100%" align="center"><tr><td>Loading...</td></tr></table>');
        },
        success: function(data) {
            
            if(data.status == 'success'){
                window.location.href="<?=base_url()?>";
            } else {
                $('.show_msg').html('<div class="alert alert-danger alert-dismissible"><strong>'+data.status+'</strong></div>');
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
            $('.show_msg').html('<div class="alert alert-danger alert-dismissible"><strong>Error: ' + xhr.status + ' - ' + xhr.statusText + '</strong></div>');
        }
    });
});

    // $(document).on('click', '#ecopyingCheck', function(){
    //     if($(this).is(":checked")){
    //         $('.left').show();
    //         $('#saveButton').show();
    //         $('#finalBtn').hide();
    //     }else{
    //         $('.left').hide();
    //         $('#saveButton').hide();
    //         $('#finalBtn').show();  
    //     }
    // })
</script>
@endpush