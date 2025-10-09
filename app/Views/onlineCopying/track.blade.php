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
                                        <h5><i class="fa fa-file"></i> Track Your Consignment</h5>
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
                    <!-- <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 ">
                            <div class="crnt-page-head">
                                <div class="current-pg-title">
                                    <h6>Case Search </h6>
                                </div>
                                <div class="current-pg-actions"> </div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="dash-card dashboard-section">
                    <div class="row">
                        <div class="panel panel-default">
                            <?php
                            $attribute = array('class' => 'form-horizontal', 'id' => 'search_case_details', 'name' => 'search_case_details', 'autocomplete' => 'off', 'novalidate' => 'novalidate');
                            echo form_open('#', $attribute);
                            ?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div style="text-align: left;">
                                <?php
                                if (!empty(getSessionData('MSG'))) {
                                    echo getSessionData('MSG');
                                }
                                if (!empty(getSessionData('msg'))) {
                                    echo getSessionData('msg');
                                }
                                ?>
                                <br>
                            </div>
                            <br>
                            <div id="">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="row mb-3">
                                            <!-- <div class="row w-100 align-items-center"> -->
                                                <div class="col-12">
                                                    <label for="inputPassword6" class="col-form-label">Consignment No. <span style="color: red">*</span></label>
                                                </div>
                                                <div class="col-12 pe-0">
                                                    <input class="form-control cus-form-ctrl" id="cn" name="cn" minlength="10" maxlength="17" placeholder="Consignment No." type="text">
                                                </div>
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3 col-sm-3 col-xs-12"> -->
                                        <div class="save-form-details">
                                            <div class="save-btns">
                                                <button type="button" id="sub" class="quick-btn gray-btn">Search</button>
                                            </div>
                                        </div>
                                    <!-- </div> -->
                                </div>
                            </div>
                            <?php echo form_close(); ?>

                            <br>
                            <div id="result"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
<style>
   /* #captcha_image {
        border: solid 0px #ccc;
        margin: 0 1em;
    }*/
    .order-card {
        color: #fff;
    }
    .applicant_dashboard{
        cursor: pointer;
    }
    .bg-c-blue {
        background: linear-gradient(45deg,#4099ff,#73b4ff);
    }

    .bg-c-green {
        background: linear-gradient(45deg,#2ed8b6,#59e0c5);
    }

    .bg-c-yellow {
        background: linear-gradient(45deg,#FFB64D,#ffcb80);
    }

    .bg-c-pink {
        background: linear-gradient(45deg,#FF5370,#ff869a);
    }


    .card {
        border-radius: 5px;
        -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
        box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
        border: none;
        margin-bottom: 5px;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }

    .card .card-block {
        padding: 20px;
    }

    .order-card i {
        font-size: 26px;
    }

    .f-left {
        float: left;
    }

    .f-right {
        float: right;
    }

    /*timelime css*/

   ul.timeline {
       list-style-type: none;
       position: relative;
   }
   ul.timeline:before {
       content: ' ';
       background: #d4d9df;
       display: inline-block;
       position: absolute;
       left: 29px;
       width: 2px;
       height: 100%;
       z-index: 400;
   }
   ul.timeline > li {
       margin: 20px 0;
       padding-left: 35px;
   }
   ul.timeline > li:before {
       content: ' ';
       background: white;
       display: inline-block;
       position: absolute;
       border-radius: 50%;
       border: 3px solid #124cbf;
       left: 20px;
       width: 20px;
       height: 20px;
       z-index: 400;
   }

</style>

    <script type="text/javascript">
        $(document).on('click', '#radioBtn a', function () {

            var sel = $(this).data('title');
            var tog = $(this).data('toggle');
            $('#'+tog).prop('value', sel);

            $('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
            $('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');

            if(sel == 'timeline_show'){
                $("#show_tracking").removeClass("d-none");
                $(".show_table_data").addClass("d-none");
            }
            if(sel == 'table_show'){
                $("#show_tracking").addClass("d-none");
                $(".show_table_data").removeClass("d-none");
            }
        });


        $(document).on('click','#sub',function(){
            //load_re_captcha();
            var cn = $("#cn").val();
            // var user_input_captcha = $("#user_input_captcha").val().trim();
            var maxLength = 10;
                if(cn.toString().length < maxLength){
                    $('#result').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please Enter Valid Consignment No.</strong></div>');
                    $('#cn').focus();
                    return false;
                }
            $.ajax({
                url:'<?php echo base_url("online_copying/get_consignment_status"); ?>',
                cache: false,
                async: true,
                beforeSend: function () {
                    $('#result').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
                },
                data: {cn:cn},
                type: 'POST',
                success: function(data, status) {
                    $('#result').html(data);
                    $('#user_input_captcha').val("");
                    $('#cn').val("");
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

        $('input[type=text]').val (function () {
            return this.value.toUpperCase();
        })

        const inputField = document.getElementById('cn');
            inputField.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' || event.keyCode === 13) {
                event.preventDefault(); // Prevents the default action (e.g., form submission)
                // Optional: Add custom behavior here, e.g., alert('Enter key disabled!');
            }
        });
    </script>
        @endpush