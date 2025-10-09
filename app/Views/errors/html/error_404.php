
<div id="notfound" style="height: 70vh;">
    <div class="notfound">
        <div class="notfound-404"> 
            <h1 >Oops!</h1>
            <h2 >404 - The Page can't be found</h2>
        </div>
        <a class="" href="<?=base_url('/'); ?>"><i class="fa fa-home blinking" style="font-size:25px;"></i> Go TO Homepage</a>
    </div>
</div> 
<style>
    .blinking{
        color:white !important;
    }
    * {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    body {
        padding: 0;
        margin: 0;
    }

    #notfound {
        position: relative;
        height: 100vh;
    }

    #notfound .notfound {
        position: absolute;
        left: 50%;
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    .notfound {
        max-width: 520px;
        width: 100%;
        line-height: 1.4;
        text-align: center;
    }

    .notfound .notfound-404 {
        position: relative;
        height: 200px;
        margin: 0px auto 20px;
        z-index: -1;
    }

    .notfound .notfound-404 h1 {
        font-family: 'Montserrat', sans-serif;
        font-size: 236px;
        font-weight: 200;
        margin: 0px;
        color: #ff5656;
        text-transform: uppercase;
        position: absolute;
        left: 50%;
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    .notfound .notfound-404 h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: 28px;
        font-weight: 400;
        text-transform: uppercase;
        color: #211b19;
        background: #fff;
        padding: 10px 5px;
        margin: auto;
        display: inline-block;
        position: absolute;
        bottom: 0px;
        left: 0;
        right: 0;
    }

    .notfound a {
        font-family: 'Montserrat', sans-serif;
        display: inline-block;
        font-weight: 700;
        text-decoration: none;
        color: #fff;
        text-transform: uppercase;
        padding: 13px 23px;
        background: #cccccc;
        font-size: 18px;
        -webkit-transition: 0.2s all;
        transition: 0.2s all;
    }

    .notfound a:hover {
        color: #fff;
        background: gray;
    }

    @media only screen and (max-width: 767px) {
        .notfound .notfound-404 h1 {
            font-size: 148px;
        }
    }

    @media only screen and (max-width: 480px) {
        .notfound .notfound-404 {
            height: 148px;
            margin: 0px auto 10px;
        }
        .notfound .notfound-404 h1 {
            font-size: 86px;
        }
        .notfound .notfound-404 h2 {
            font-size: 16px;
        }
        .notfound a {
            padding: 7px 15px;
            font-size: 14px;
        }
    }

</style>

<script src="https://efiling.sci.gov.in/assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="https://efiling.sci.gov.in/assets/vendors/moment/min/moment.min.js"></script>

<!--start required for library by akg-->
<!--<link href="dataTables/jquery.dataTables.css" rel="stylesheet" />-->
<link href="https://efiling.sci.gov.in/dataTables/buttons.dataTables.css" rel="stylesheet" />
<script src="https://efiling.sci.gov.in/assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="https://efiling.sci.gov.in/dataTables/jszip.min.js"></script>
<script src="https://efiling.sci.gov.in/assets/responsive_variant/templates/uikit_scutum_2/node_modules/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="https://efiling.sci.gov.in/dataTables/pdfmake.min.js"></script>
<script src="https://efiling.sci.gov.in/dataTables/vfs_fonts.js"></script>
<script src="https://efiling.sci.gov.in/assets/responsive_variant/templates/uikit_scutum_2/node_modules/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="https://efiling.sci.gov.in/dataTables/buttons.print.min.js"></script>
<!--end required for library by akg-->

<script src="https://efiling.sci.gov.in/assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="https://efiling.sci.gov.in/assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="https://efiling.sci.gov.in/assets/js/sha256.js"></script>
<script src="https://efiling.sci.gov.in/assets/build/js/custom.min.js"></script>
<script src="https://efiling.sci.gov.in/assets/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
<script src="https://efiling.sci.gov.in/assets/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
<script src="https://efiling.sci.gov.in/assets/vendors/google-code-prettify/src/prettify.js"></script>
<script src="https://efiling.sci.gov.in/assets/js/jquery.smartWizard.js"></script>
<script type="text/javascript" src="https://efiling.sci.gov.in/assets/js/jquery.validate.js"></script>
<script src="https://efiling.sci.gov.in/assets/js/select2.min.js"></script>
<script src="https://efiling.sci.gov.in/assets/js/select2-tab-fix.min.js"></script>
<script src="https://efiling.sci.gov.in/assets/js/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://efiling.sci.gov.in/assets/css/sweetalert.css">
<script type="text/javascript">
    $('.filter_select_dropdown').select2();
    $(".flashmessage").fadeTo(6000, 100).slideUp(10, function () {
        $(".flashmessage").slideUp(6000);
    });
    $('.scr_date').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        maxDate: new Date(),
        dateFormat: "dd-mm-yy",
        yearRange: '2000:2099'
    });
    $(".pending_scrutiny_status").submit(function (e) {
        e.preventDefault();
        var frm_value = $(this).find('.scrutiny_status').val();
        var scr_date = $(this).find('.scr_date').val();
        var y = confirm("Are you sure to know Scrutiny Status ?");
        if (y == false)
        {
            return false;
        }
        var efiling_for_type_id = '';
        if (efiling_for_type_id == 1) {
            var status_url = 'https://efiling.sci.gov.in/Efiled_cases/check_pending_scrutiny_status/';
            var redirect_url = 'https://efiling.sci.gov.in/new_case/view';
        } else if (efiling_for_type_id == 4) {
            status_url = 'https://efiling.sci.gov.in/Efiled_cases/check_pending_scrutiny_status_ia/';
            redirect_url = 'https://efiling.sci.gov.in/IA/view';
        } else {

        }

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: status_url,
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, form_submit: frm_value, scr_date: scr_date},
            beforeSend: function () {
                $(".scrutiny_status").text('Get Scrutiny Status').button("refresh");
            },
            success: function (result) {
                if (result) {

                    if (result == 'efiled') {
                        alert('Transferred to Efiled Stage Successfully.');
                    }
                    if (result == 'defects') {
                        alert('Transferred to Defective Stage Successfully.');
                    }
                    if (result == 'rejects') {
                        alert('Transferred to Rejected Stage Successfully.');
                    }
                    if (result == 'scrutiny') {
                        alert('Efiling No. Checked and No status Change.');
                    }
                    if (result == 'no_date_posted') {
                        alert('No Scrutiny Date posted. Please Check!');
                    }
                    if (result == 'date_format_not_correct') {
                        alert('Scrutiny date format not correct. Please Check!');
                    }
                } else {
                    alert('Error !!');
                }
                window.location.href = redirect_url;
                $.getJSON("https://efiling.sci.gov.in/Login/get_csrf_new", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function (result) {
                $.getJSON("https://efiling.sci.gov.in/Login/get_csrf_new", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
        return false;
    });
    $("#pass_update_formwww").submit(function () {

        var value1 = $(this).find('#newpass').val();
        if (/^[A-Za-z0-9\d=!\-@._*#$%]*$/.test(value1) && /[a-z]/.test(value1) && /\d/.test(value1) && /[A-Z]/.test(value1) && (value1.length >= 8))
        {
            $(this).find('#currentpass').val(sha256(sha256($(this).find('#currentpass').val()) + 'BgaD3KAYu8'));
            $(this).find('#newpass').val(sha256($(this).find('#newpass').val()));
            $(this).find('#confirmpass').val(sha256($(this).find('#confirmpass').val()));
        } else
        {
           // alert("The New Password must contain atleast 1 Special Character, 1 Digit, 1 lower case Character, 1 Upper Case Character and atleast 8 digit length");
            return false;
        }

    });</script>

<script type="text/javascript">
    $(document).ready(function () {

        var idleTimer = null;
        $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
            clearTimeout(idleTimer);
            idleTimer = setTimeout(function () {
                window.location.href = 'https://efiling.sci.gov.in/login/logout';
            }, 540000); //(24*60*1000)600000
        });
        $('.filter_select_dropdown').select2();
        $(".collapse").on("hide.bs.collapse", function () {
            var id = $(this).attr('id');
            var title = $('[href="#' + id + '"] b').html();
            $('[href="#' + id + '"]').html('<i class="fa fa-plus" style="float: right;"></i> <b>' + title + '</b>');
        });
        $(".collapse").on("show.bs.collapse", function () {
            var id = $(this).attr('id');
            var title = $('[href="#' + id + '"] b').html();
            $('[href="#' + id + '"]').html('<i class="fa fa-minus" style="float: right;"></i> <b>' + title + '</b>');
        });
    });</script>

<script>
    function hideMessageDiv() {
        if (document.getElementById("msgdiv") != null) {
            document.getElementById('msgdiv').style.display = "none";
            $(".msgdiv").hide();
        }
    }
    function HideMsg() {
        if (document.getElementById("msg") != null) {
            document.getElementById("msg").style.display = "none";
        }
    }
    setTimeout("hideMessageDiv()", 5000); // after 5 sec
    setTimeout("HideMsg()", 5000); // after 5 sec
    $(document).ready(function () {
        $('.get_datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd-mm-yy",
            maxDate: new Date,
            yearRange: '2000:2099'
        });
        $('[name="cino"],.cnr_number').on('keyup blur', function (e) {
            var number = $(this).val();
            var number = number.replace(/-/gi, "");
            if (e.keyCode != 8) {
                if (number.length == 6) {
                    $(this).val($(this).val() + '-');
                } else if (number.length == 12) {
                    $(this).val($(this).val() + '-');
                } else if (number.length == 16) {
                    $(this).val(number.substr(0, 6) + '-' + number.substr(6, 6) + '-' + number.substr(12, 4));
                } else if (number.length > 16)
                {
                    $(this).val(number.substr(0, 6) + '-' + number.substr(6, 6) + '-' + number.substr(12, 4));
                }
            }
        });
    });
    $(document).ready(function () {
        $(document).on('click', '.refresh_cap', function () {
            $.ajax({
                url: base_url + 'captcha_refresh',
                success: function (data) {
                    $('.userCaptcha').val('');
                    $('.captcha-img').replaceWith(data);
                }
            });
        });
        $(document).on('click', '#menu_toggle', function () {
            var element_get = $('body').attr('class');
            if (element_get == 'nav-sm') {
                $('.show_sm_div').removeClass("visible-xs visible-sm");
                $('.show_sm_div').show();
                $('.show_md_div').removeClass("visible-lg visible-md");
                $('.show_md_div').hide();

            } else {
                $('.show_md_div').show();
                $('.show_sm_div').addClass('visible-sm visible-xs');
                $('.show_sm_div').hide();
            }

        });
        $(document).ready(function () {

            $('.show_sm_div').removeClass("visible-xs visible-sm");
            $('.show_sm_div').show();
            $('.show_md_div').removeClass("visible-lg visible-md");
            $('.show_md_div').hide();
            $('.show_md_div').show();
            $('.show_sm_div').addClass('visible-sm visible-xs');
            $('.show_sm_div').hide();

            var element_get = $('body').attr('class');
            if (element_get == 'nav-sm') {
                $('.show_sm_div').removeClass("visible-xs visible-sm");
                $('.show_sm_div').show();
                $('.show_md_div').removeClass("visible-lg visible-md");
                $('.show_md_div').hide();

            } else {
                $('.show_md_div').show();
                $('.show_sm_div').addClass('visible-sm visible-xs');
                $('.show_sm_div').hide();
            }

        });
    });
</script>
<style>
    .blink{
        color: rgb (0, 137, 226);
        animation: blink 1s infinite;
    }

    @keyframes blink{
        0%{opacity: 1;}
        75%{opacity: 1;}
        76%{ opacity: 0;}
        100%{opacity: 0;}
    }
    .footer {
        /*        position: fixed;*/
        bottom: 0;
        width:100%;

    }

    @media screen and (max-width: 700px) {
        .top_nav .navbar-right   { 
            margin-top: -15px !important;
            margin-left: 20px !important;
        }.search_align{
            margin-top: -10px !important;
        }
        .footer {
            position: inherit;
            width:100%;

        }
        .profile_align{
            margin-top: -30px;
        }
        .text_length{
            word-wrap: break-word;
        }
    }
    .my-custom-scrollbar1{
        position: relative;
        height: 100vh;
        overflow: auto;
    }
    .table-wrapper-scroll-y {
        display: block;
    }div.view_data {
        position: relative;


    }
    .topnav {
        overflow: hidden;
    }
    .topnav a {
        float: left;
        text-align: center;
        padding: 3px 12px ;
        text-decoration: none;
    }

    .highlight_tab:hover{
        background: #745533;
        color: #fff;
    }
    .nav.side-menu > li.active > a {
        background: linear-gradient( #737373,  #737373) repeat scroll 0 0%, none repeat scroll 0 0  #737373;
    }
    .active_tab{
        background: linear-gradient( #737373,  #737373) repeat scroll 0 0%, none repeat scroll 0 0  #737373 ;
    }
    .nav.child_menu > li > a, .nav.side-menu > li > a {
        font-weight: 500;
        font-weight: bold;
    }
    .nav-md ul.nav.child_menu li:before {
        background: none repeat scroll 0 0 #EDEDED;
        border-radius: 50%;
        bottom: auto;
        content: "";
        height: 8px;
        left: 23px;
        margin-top: 15px;
        position: absolute;
        right: auto;
        width: 8px;
        z-index: 1;

    }
    .nav-md ul.nav.child_menu li:after {
        border-left: 1px solid #EDEDED;
        bottom: 0;
        content: "";
        left: 27px;
        position: absolute;
        top: 0;
    }
    .nav-md{
        overflow-x: hidden;
    }
    .nav li.current-page {
        background:  rgba(0, 0, 0, 0.25);
    }
    .hr_class{
        border:rgba(92, 80, 80, 0.3) 0.1px solid !important;
    }
    .text_color{
        color: #334556!important;
    }
    .nav.side-menu > li > a {
        margin-bottom: -3px;
    }
    .highlight:hover {
        background-color: #BAB7AE !important;
    }
    .number_css{
        float: right;
    }
    .site_title i {
        border: 1px solid #334556;
        padding: 5px 6px;
        border-radius: 50%;
    }
    .font_css{
        font-weight: bolder;
        padding:0px 0px 0px 30px!important;
    }
    .top_nav .navbar-right {
        margin: 0;
        width: 94%;
        float: left;
    }
    .nav.side-menu > li.active, .nav.side-menu > li.current-page {
        border-right: 3px solid rgba(92, 80, 80, 0.3) !important;
    }
    .navbar-nav li:hover > ul.dropdown-menu {
        display: block;
    }
    .dropdown-submenu {
        position:relative;
    }
    .dropdown-submenu>.dropdown-menu {
        top:0;
        left:100% !important;
        margin-top:-6px;
    }
    .nav-sm ul.nav.child_menu {

        left: 100%;
        position: absolute;
        top: 0;
        width: 210px;
        z-index: 4000;
        background: #EDEDED;
        display: none;
    }
    .text_cursor { cursor: text; }
    .topnav {
        overflow: hidden;
    }
    .topnav a {
        float: left;
        text-align: center;
        padding: 3px 12px ;
        text-decoration: none;
    }

    .leftCol {
        position: relative;
        overflow-y: scroll;
        top: 0;
        bottom: 0;
    }
    .topalign{
        margin-top: 13px; margin-left: -110px;
    }
    .nav-sm .navbar.nav_title a span {
        display: block !important;
    }
    .x-panel_height{
        padding:3px 3px 0 ;

    }
    .select2-results__options {

        position: absolute;
        background-color: #EDEDED;

        width: 100%;
        overflow: auto;
        border: 1px solid #ddd;
        z-index: 1;
    }
    .select2-results__options a:hover {background-color: #ddd !important;}
    select option {
        background-color: #EDEDED !important;

    }
    table.dataTable thead th.sorting:after {
        content: "\f0dc";
        color: #29527C;
        font-size: 0.8em;

    }
    table.dataTable thead th.sorting_asc:after {
        content: "\f0de";
    }
    table.dataTable thead th.sorting_desc:after {
        content: "\f0dd";
    }


    table.dataTable thead th.sorting:after,
    table.dataTable thead th.sorting_asc:after,
    table.dataTable thead th.sorting_desc:after {
        float:right;
        display: block;
        font-family: FontAwesome;
    }



    @media screen and  (min-width: 200px)and  (max-width: 400px){
        a.user-profile{

        }
        .dropdown-menu {
            box-shadow: none;
            display: none;
            float: left;
            font-size: 12px;
            left: 15% !important;
            list-style: none;
            padding: 0;
            position: absolute;
            text-shadow: none;
            top: 18% !important;
            z-index: 9998;
            border: 1px solid #D9DEE4;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }.nav > li > a {
            padding: 29px 15px 12px;
        }
    }

    @media screen and  (min-width: 401px)and  (max-width: 480px){
        .dropdown-menu {
            box-shadow: none;
            display: none;
            float: left;
            font-size: 12px;
            left: 30% !important;
            list-style: none;
            padding: 0;
            position: absolute;
            text-shadow: none;
            top: 17% !important;
            z-index: 9998;
            border: 1px solid #D9DEE4;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
        .nav > li > a {
            padding: 20px 15px 12px;
        }

    }
    .sweet-alert{ top: 25% !important;}

</style>

<script type="text/javascript">
    $(document).ready(function () {

        $('#advocate_list').change(function () {
            $('#select2-advocate_list1-container').text('Select Panel Advocate');
            $('#select2-advocate_list1-container').attr('title', 'Select Panel Advocate');

            $('#advocate_list1').prop('selectedIndex', 0);
        });


        $('#advocate_list1').change(function () {
            $('#select2-advocate_list-container').text('Practicing On this establishment');
            $('#select2-advocate_list-container').attr('title', 'Practicing On this establishment');
            $('#advocate_list').prop('selectedIndex', 0);
        });
    });
    $(".main-menu").mouseover(function () {
        var title_text = $.trim($(this).text());
        var uniqueList = title_text.split(' ')
        $('span.' + uniqueList[0] + '_title').hide();



    });
    $('.main-menu').mouseout(function () {
        var title_text = $.trim($(this).text());
        var uniqueList = title_text.split(' ')
        $('span.' + uniqueList[0] + '_title').show();


    });
    $(window).bind("resize", function () {
        console.log($(this).width())
        if ($(this).width() < 400) {
            $('body').removeClass('nav-sm').addClass('nav-md')
            $('.show_sm_div').removeClass("visible-xs visible-sm");
            $('.show_sm_div').show();
            $('.show_md_div').removeClass("visible-lg visible-md");
            $('.show_md_div').hide();
        }
    }).trigger('resize');

    $(document).ready(function(){
        $('#pet_dob').datepicker({
            onSelect: function (value) {
                var parts = value.split("/");
                var day = parts[0] && parseInt(parts[0], 10);
                var month = parts[1] && parseInt(parts[1], 10);
                var year = parts[2] && parseInt(parts[2], 10);
                var str = month + '/' + day + '/' + year;
                var today = new Date(),
                    dob = new Date(str),
                    age = new Date(today - dob).getFullYear() - 1970;
                $('#pet_age').val(age);
            },
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:-1",
            dateFormat: "dd/mm/yy",
            defaultDate: '-40y'
        });
        //----------Get District List----------------------//
        $('#party_state').change(function () {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#party_district').val('');

            var get_state_id = $(this).val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
                url: "https://efiling.sci.gov.in/newcase/Ajaxcalls/get_districts",
                success: function (data)
                {
                    $('#party_district').html(data);
                    $.getJSON("https://efiling.sci.gov.in/csrftoken", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("https://efiling.sci.gov.in/csrftoken", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });

        });


    $('#party_pincode').blur(function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var pincode = $("#party_pincode").val();
            if(pincode){
                var stateObj = JSON.parse(state_Arr);
                var options = '';
                options +='<option value="">Select State</option>';
                stateObj.forEach((response)=>
                options +='<option value="'+response.id+'">'+response.state_name+'</option>');
                $('#party_state').html(options).select2().trigger("change");
                $.ajax({
                    type: "POST",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, pincode : pincode},
                    url: "https://efiling.sci.gov.in/newcase/Ajaxcalls/getAddressByPincode",
                    success: function (response)
                    {
                        var taluk_name;
                        var district_name;
                        var state;
                        if(response){
                            var resData = JSON.parse(response);
                            if(resData){
                                taluk_name = resData[0]['taluk_name'].trim().toUpperCase();
                                district_name = resData[0]['district_name'].trim().toUpperCase();
                                state = resData[0]['state'].trim().toUpperCase();
                            }
                            if(taluk_name){
                                $("#party_city").val('');
                                $("#party_city").val(taluk_name);
                            }
                            else{
                                $("#party_city").val('');
                            }
                            if(state){
                                var stateObj = JSON.parse(state_Arr);
                                if(stateObj){
                                    var singleObj = stateObj.find(
                                        item => item['state_name'] === state
                                    );
                                }
                                if(singleObj){
                                    $('#party_state').val('');
                                    $('#party_state').val(singleObj.id).select2().trigger("change");
                                }
                                else{
                                    $('#party_state').val('');
                                }
                                if(district_name){
                                    var stateId = $('#party_state').val();
                                    setSelectedDistrict(stateId,district_name);
                                }
                            }
                            else{
                                $('#party_state').val('');
                            }
                        }
                        $.getJSON("https://efiling.sci.gov.in/csrftoken", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("https://efiling.sci.gov.in/csrftoken", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            }
        });
        $(document).on("change","#country_id",function(){
            var country_id = $(this).val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var is_dead_minor =  $('input[name="is_dead_minor"]:checked').val();
            if(country_id){
                $.ajax({
                    type: "POST",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, doc_type_code: country_id},
                    url: "https://efiling.sci.gov.in/documentIndex/Ajaxcalls/get_index_type",
                    success: function (res)
                    {
                        if(res == 96){
                            var stateObj = JSON.parse(state_Arr);
                            var options = '';
                            options +='<option value="">Select State</option>';
                            stateObj.forEach((response)=>
                            options +='<option value="'+response.id+'">'+response.state_name+'</option>');
                            $('#party_state').html(options).select2().trigger("change");
                            if(is_dead_minor == 1){
                                $("#address_label_name span#city_span").html('');
                                $("span#state_span").html('');
                                $("span#district_span").html('');
                                $("#party_pincode").val('');
                                $("#party_city").val('');
                            }
                            else{
                                $("#address_label_name span#city_span").html('*');
                                $("span#state_span").html('*');
                                $("span#district_span").html('*');
                                $("#party_pincode").val('');
                                $("#party_city").val('');
                            }
                        }
                        else{
                            $("#address_label_name span#city_span").html('');
                            $("span#state_span").html('');
                            $("span#district_span").html('');
                            $("#party_pincode").val('');
                            $("#party_city").val('');
                            $("#party_state").html('<option value="">Select State</option>');
                            $("#party_district").html('<option value="">Select District</option>');
                            $("#party_city").attr('required',false);
                            $("#state_span").html('');
                            $("#state_span").css({'color':''});
                            $("#party_state").attr('required',false);
                            $("#district_span").html('');
                            $("#district_span").css({'color':''});
                            $("#party_district").attr('required',false);
                        }
                        $.getJSON("https://efiling.sci.gov.in/csrftoken", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("https://efiling.sci.gov.in/csrftoken", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            }
        });


    });
    function setSelectedDistrict(stateId,district_name){
        if(stateId && district_name){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: stateId},
                url: "https://efiling.sci.gov.in/newcase/Ajaxcalls/getSelectedDistricts",
                success: function (resData)
                {
                    if(resData){
                        var districtObj = JSON.parse(resData);
                        var singleObj = districtObj.find(
                            item => item['district_name'] === district_name
                        );
                        if(singleObj){
                            $('#party_district').val('');
                            $('#party_district').val(singleObj.id).select2().trigger("change");
                        }
                        else{
                            $('#party_district').val('');
                        }
                    }
                    else{
                        $('#party_district').val('');
                    }
                    $.getJSON("https://efiling.sci.gov.in/csrftoken", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("https://efiling.sci.gov.in/csrftoken", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        }
    }


    $( function() {
        $( "#tabs" ).tabs();
    } );
</script>

<!--for responsive_variant-->
    <!--<style type="text/css">
        .nav-sm .container.body .right_col {
            padding: 300px;
            margin-left: 100px;
            /*overflow-x:hidden !important;*/
        }
        #page-wrapper > .row{
            margin-right: 0;
        }
    </style>-->
<script>
    document.getElementById("copyButton").addEventListener("click", function() {
        copyToClipboardMsg(document.getElementById("copyTarget_EfilingNumber"), "copyButton");
    });

    document.getElementById("copyButton2").addEventListener("click", function() {
        copyToClipboardMsg(document.getElementById("copyTarget2"), "copyButton");
    });

    document.getElementById("pasteTarget").addEventListener("mousedown", function() {
        this.value = "";
    });


    function copyToClipboardMsg(elem, msgElem) {
        var EfilingNumber=document.getElementById('copyTarget_EfilingNumber').innerHTML;
        var succeed = copyToClipboard(elem);
        var msg;
        if (!succeed) {
            msg = "Copy not supported or blocked.  Press Ctrl+c to copy."
        } else {
            //msg = 'E-Filing Number: ' +EfilingNumber+' Copied.';
            msg = 'Copied';
        }
        if (typeof msgElem === "string") {
            msgElem = document.getElementById(msgElem);
        }
        msgElem.innerHTML = msg;
       /* setTimeout(function() {
            msgElem.innerHTML = "";
        }, 5000);*/
    }

    function copyToClipboard(elem) {
        // create hidden text element, if it doesn't already exist
        var targetId = "_hiddenCopyText_";
        var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA" || elem.tagName === "INPUT";
        var origSelectionStart, origSelectionEnd;
        if (isInput) {
            // can just use the original source element for the selection and copy
            target = elem;
            origSelectionStart = elem.selectionStart;
            origSelectionEnd = elem.selectionEnd;
        } else {
            // must use a temporary form element for the selection and copy
            target = document.getElementById(targetId);
            if (!target) {
                var target = document.createElement("textarea");
                target.style.position = "absolute";
                target.style.left = "-9999px";
                target.style.top = "0";
                target.id = targetId;
                document.body.appendChild(target);
            }
            target.textContent = elem.textContent;
        }
        // select the content
        var currentFocus = document.activeElement;
        target.focus();
        target.setSelectionRange(0, target.value.length);

        // copy the selection
        var succeed;
        try {
            succeed = document.execCommand("copy");
        } catch(e) {
            succeed = false;
        }
        // restore original focus
        if (currentFocus && typeof currentFocus.focus === "function") {
            currentFocus.focus();
        }

        if (isInput) {
            // restore prior selection
            elem.setSelectionRange(origSelectionStart, origSelectionEnd);
        } else {
            // clear temporary content
            target.textContent = "";
        }
        return succeed;
    }


</script>

<!--start ActionToTrash with sweetAlert by ANshu 22-05-2023-->
<style>.sweet-alert{ top: 28% !important;}</style>
<script>
    function ActionToTrash(trash_type) {
        event.preventDefault();
        var trash_type =trash_type;
        var url="";
        if (trash_type==''){
            swal("Cancelled", "Your imaginary file is safe :)", "error");
            return false;
        }else if (trash_type=='UAT'){
            url="https://efiling.sci.gov.in/userActions/trash";
        }else if (trash_type=='SLT'){
            url="https://efiling.sci.gov.in/stage_list/trash";
        }else if (trash_type=='EAT'){
            url="https://efiling.sci.gov.in/userActions/trash";
        }else{
            swal("Cancelled", "Your imaginary file is safe :)", "error");
            return false;
        }
       // alert('trash_type'+trash_type+'url='+url);//return false;
        swal({
                title: "Do you really want to trash this E-Filing,",
                text: "once it will be trashed you can't restore the same.",
                type: "warning",
                position: "top",
                showCancelButton: true,
                confirmButtonColor: "green",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                buttons: ["Make Changes", "Yes!"],
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm){
                if (isConfirm) {  // submitting the form when user press yes
                    var link = document.createElement("a");
                    link.href = url;
                    link.target = "_self";
                    link.click();
                    swal({ title: "Deleted!",text: "E-Filing has been deleted.",type: "success",timer: 2000 });

                } else {
                    //swal({title: "Cancelled",text: "Your imaginary file is safe.",type: "error",timer: 1300});
                }

            });
    }
</script>
<!--end ActionToTrash with sweetAlert-->
</body>
</html>
