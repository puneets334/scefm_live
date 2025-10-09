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
                                        <h5><i class="fa fa-file"></i> Screen Reader </h5>
                                    </div>
                                    <div class="form-response" id="msg" role="alert" data-auto-dismiss="5000"></div>
                                </div>
                                <div class="ryt-dash-breadcrumb">
                                    <div class="btns-sec">
                                        <!-- <a href="javascript:void(0)" class="quick-btn pull-right mb-2" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
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
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <p><a href="<?=ANU_GOV_IN?>e_copying">eCopying Software</a> is accessible with different screen readers, Following are the information about different screen readers :
                                    </p>
                                    <div class="table-sec">
                                        <div class="table-responsive">
                                            <table id="datatable-responsive" class="table table-striped custom-table">
                                        <thead>

                                        <tr>
                                            <th>Screen Reader</th>
                                            <th>Free / Commercial</th>
                                            <th>URL</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td data-key="Screen Reader">NVDA</td>
                                            <td data-key="Free / Commercial">Free</td>
                                            <td data-key="URL"><a href="http://www.nvda-project.org/" target="_blank" title="External website that opens in a new window :- Non Visual Desktop Access (NVDA)">http://www.nvda-project.org/</a><br />(External website, you are going to open a new window)</td>
                                        </tr>
                                        <tr>
                                            <td data-key="Screen Reader">SAFA</td>
                                            <td data-key="Free / Commercial">Free</td>
                                            <td data-key="URL"><a href="http://safa-reader.software.informer.com/download/" target="_blank" title="External website that opens in a new window :- Screen Access For All (SAFA)">http://safa-reader.software.informer.com/download/</a><br />(External website, you are going to open a new window)</td>
                                        </tr>
                                        <tr>
                                            <td data-key="Screen Reader">System Access To Go</td>
                                            <td data-key="Free / Commercial">Free</td>
                                            <td data-key="URL"><a href="http://www.satogo.com/" target="_blank" title="External website that opens in a new window :- System Access To Go">http://www.satogo.com/</a><br />(External website, you are going to open a new window)</td>

                                        </tr>
                                        <tr>
                                            <td data-key="Screen Reader">Thunder</td>
                                            <td data-key="Free / Commercial">Free</td>
                                            <td data-key="URL"><a href="http://www.screenreader.net/index.php?pageid=11" target="_blank" title="External website that opens in a new window :- Thunder">http://www.screenreader.net/index.php?pageid=11</a><br />(External website, you are going to open a new window)</td>
                                        </tr>
                                        <tr>
                                            <td data-key="Screen Reader">Web Anywhere</td>
                                            <td data-key="Free / Commercial">Free</td>
                                            <td data-key="URL"><a href="http://webanywhere.cs.washington.edu/wa.php" target="_blank" title="External website that opens in a new window :- Web Anywhere">http://webanywhere.cs.washington.edu/wa.php</a><br />(External website, you are going to open a new window)</td>
                                        </tr>
                                        <tr>
                                            <td data-key="Screen Reader">Hal</td>
                                            <td data-key="Free / Commercial">Commercial</td>
                                            <td data-key="URL"><a href="http://www.yourdolphin.co.uk/productdetail.asp?id=5" target="_blank" title="External website that opens in a new window :- Hal">http://www.yourdolphin.co.uk/productdetail.asp?id=5</a><br />(External website, you are going to open a new window)</td>
                                        </tr>
                                        <tr>
                                            <td data-key="Screen Reader">JAWS</td>
                                            <td data-key="Free / Commercial">Commercial</td>
                                            <td data-key="URL"><a href="http://www.freedomscientific.com/jaws-hq.asp" target="_blank" title="External website that opens in a new window :- JAWS">http://www.freedomscientific.com/jaws-hq.asp</a><br />(External website, you are going to open a new window)</td>
                                        </tr>
                                        <tr>
                                            <td data-key="Screen Reader">Supernova</td>
                                            <td data-key="Free / Commercial">Commercial</td>
                                            <td data-key="URL"><a href="http://www.yourdolphin.co.uk/productdetail.asp?id=1" target="_blank" title="External website that opens in a new window :- Supernova">http://www.yourdolphin.co.uk/productdetail.asp?id=1</a><br />         (External website that opens in a new window)</td>
                                        </tr>
                                        <tr>
                                            <td data-key="Screen Reader">Window-Eyes</td>
                                            <td data-key="Free / Commercial">Commercial</td>
                                            <td data-key="URL"><a href="http://www.gwmicro.com/Window-Eyes/" target="_blank" title="External website that opens in a new window :- Window-Eyes">http://www.gwmicro.com/Window-Eyes/</a><br />(External website, you are going to open a new window)</td>
                                        </tr>
                                        </tbody>
                                    </table>
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
@push('script')
<script type="text/javascript">
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    $(document).ready(function(){
        $("#search_crn").hide();
    });
    $(document).on('click', '#radio_crn', function () {
        $("#search_crn").show();
        $("#search_application_no").hide();
        $('#result').html('');
    });
    $(document).on('click', '#radio_ano', function () {
        $("#search_application_no").show();
        $("#search_crn").hide();
        $('#result').html('');
    });
    $(document).on('click','#sub',function(){
        var application_type= $("#application_type").val();
        var application_no= $("#application_no").val();
        var application_year= $("#application_year").val();
        var crn = $("#crn").val();
        var flag = '';
        var regNum = new RegExp('^[0-9]+$');
        if($("#radio_ano").is(':checked')){
            flag = 'ano';
            if(!regNum.test(application_type)){
                alert("Please Select Type");
                $("#application_type").focus();
                return false;
            }
            if(!regNum.test(application_no)){
                alert("Please Fill Application No. in Numeric");
                $("#application_no").focus();
                return false;
            }
            if(!regNum.test(application_year)){
                alert("Please Fill Application Year in Numeric");
                $("#application_year").focus();
                return false;
            }
            if(application_no == 0){
                alert("Application No. Can't be Zero");
                $("#case_no").focus();
                return false;
            }
            if(application_year == 0){
                alert("Application Year Can't be Zero");
                $("#case_yr").focus();
                return false;
            }
        } else{
            flag = 'crn';
            if(crn.length !=15){
                alert("Please enter CRN");
                $('#crn').focus();
                return false;
            }
        }        
        $.ajax({
            url:'<?php echo base_url("online_copying/get_copy_search"); ?>',
            cache: false,
            async: true,
            beforeSend: function () {
                $('#result').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
            },
            data: {flag:flag, crn:crn, application_type:application_type,application_no:application_no,application_year:application_year},
            type: 'GET',
            success: function(data, status) {
                $('#result').html(data);
               // $('#user_input_captcha').val("");
                //reload_captcha();
                //load_re_captcha();
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
    function onloadCallback() {
        var textarea = document.getElementById("g-recaptcha-response-100000");
        textarea.setAttribute("aria-hidden", "true");
        textarea.setAttribute("aria-label", "do not use");
        textarea.setAttribute("aria-readonly", "true");
    }
    //XXXXXXXXX TRACK MODAL FUNCTION START XXXXXXXX
    function mytrack_record(){
        //alert("Rounak Mishra");
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("myBtn");
        var span = document.getElementsByClassName("close")[0];
        modal.style.display = "block";
        span.onclick = function() {
            modal.style.display = "none";
        };
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
    //End of function mytrack_record..
    //XXXXXXXXXX TRACK MODAL FUNCTION END  XXXXXXX
</script>
@endpush