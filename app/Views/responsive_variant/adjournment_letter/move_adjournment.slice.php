@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Adjournment Letter')
@section('heading', 'Adjournment Letter')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')
<!--action="{{base_url('adjournment_letter/DefaultController/saveAdjournmentRequest')}}-->
<form id="frmSaveAdjournmentLetter" method="post" enctype='multipart/form-data' ">
<div class="form-response" id="msg" >
    <?php
/*    if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
        echo $_SESSION['MSG'];
    } unset($_SESSION['MSG']);
    */?>
</div>
<div class="uk-margin-small-top uk-border-rounded">
    <input type="hidden" name="data_array" id="data-array" value="{{json_encode($data_array)}}">
    <div class="uk-width-expand uk-margin-medium-top uk-overflow-auto uk-grid uk-text-large uk-margin-1">
        <div class="uk-width-1-1">
            <span class="uk-text-bold">Case Number:</span>
            {{!empty($data_array['case_number'])?$data_array['case_number'].' @ ':''}}
            D. No.{{$data_array['diary_number']}}
        </div>
        <div class="uk-width-1-1 uk-margin-small-top">
            <span class="uk-text-bold">Listing Details:</span>
            Item No. {{$data_array[item_number]}} @ {{$data_array[court_number]}}
            on {{date_format(date_create($data_array['listed_date']), 'D, jS M Y')}}
        </div>
       <!-- <div class="uk-width-1-3">
            <span class="uk-text-bold">Court Details:</span>
            Item No. {{$data_array[item_number]}} @ {{$data_array[court_number]}}
        </div>-->
        <div class="uk-width-1-1 uk-margin-small-top">
            <span class="uk-text-bold uk-text-normal">Select Letter<span style="color: red">*</span> </span><span class="uk-text-danger">(pdf format only):</span>
            <input name="pdfDocFile" id="browser" tabindex="3" required="required" type="file">

        </div>{{$data_array[already_moved]}}
        @if($data_array[already_moved]==1)
        <div class="uk-width-1-1 uk-margin-medium-top" id="divAction">
            <span class="uk-text-success">Adjournment Request already moved in this case</span>
        </div>
        @else
        <div class="uk-width-1-1 uk-margin-medium-top" id="divAction">
            <input type="submit" value="Upload & Move Adjournment Letter" id="btnSubmit" class="sc-button sc-button-primary sc-js-button-wave-light uk-width-1-2 waves-effect waves-button waves-light"></input>
        </div>
        @endif
    </div>

</div>
    <!--<div class="uk-width-expand">
        <div class="progress" style="display: none">
            <div class="uk-progress" role="progressbar"  value="0" max="100" style="width:0%">0%</div>
        </div>
    </div>-->
</form>

<script>
    $('#frmSaveAdjournmentLetter').on('submit', function () {

            // var title = $("#doc_title").val();
            // var file_post = $('#uploadDocument')[0];
            // var file_data = new FormData(file_post);
            // file_data.append('doc_title', title);
            var  file_data=  updateUploadFileName('browser');
            if(file_data){
                $('.progress').show();
                $.ajax({
                    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: "<?php echo base_url('adjournment_letter/DefaultController/saveAdjournmentRequest'); ?>",
                    data: file_data,
                    contentType: false,
                    processData: false,
                    /*xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                $('.myprogress').html(percentComplete + '%');
                                $('.myprogress').css('width', percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    },*/
                    success: function (data) {
                        //$("#upload_doc").prop("disabled", true);
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='uk-alert-danger uk-alert' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $(".form-response").html("<p class='uk-alert-success uk-alert' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            $('#msg').show();
                            $("#btnSubmit").hide();
                            $("#divAction").html("<span class='uk-success'>Adjournment letter Saved!</span>");
                            //$('.progress').hide();

                            //location.reload();
                        } else if (resArr[0] == 3) {
                            $('#msg').show();
                            $(".form-response").html("<p class='uk-alert-danger uk-alert' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        }
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function (e) {
                        $("#upload").prop("disabled", false);
                    }
                });
            }
            return false;

    });
    var file_data;
    function updateUploadFileName(upload_field_Id){
        var max_file_size = "<?php echo UPLOADED_FILE_SIZE ;?>";
        var upload_file = $('#'+upload_field_Id).get(0).files[0];
        var file_type = upload_file.type;
        var file_size = upload_file.size;
        if(file_type == 'application/pdf' && file_size <= max_file_size){
            var file_data = new FormData();
            var newFile_Name = new Date().getTime()+'.pdf';
            file_data.append('pdfDocFile', upload_file, newFile_Name);
            //file_data.append('doc_title', title);
            return file_data;
        }
        else{
            var max_file_size_mb = (max_file_size / 1024)/1024;
            alert('Please select PDF type and size less than '+max_file_size_mb+'MB.');
            $("#pdf_error span").remove('');
            $("#pdf_error").append('<span>PDF type and size less than '+max_file_size_mb+'MB.</span>');
            $("#pdf_error span").css('color','red');
            $("#browser").val('');
            return false;
        }

    }
    function hideMessageDiv() {
        document.getElementById('msg').style.display = "none";
    }
    setTimeout("hideMessageDiv()", 5000);
</script>

@endsection