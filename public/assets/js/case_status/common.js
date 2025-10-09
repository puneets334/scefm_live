(function ($) {
    $(document).ready(function () {
        function get_benches(str)
        {
            var ddl_st_agncy = $('#ddl_st_agncy').val();
            var ddl_court = $('#ddl_court').val();
            if (ddl_st_agncy != '' && ddl_court != '')

            {

                $.ajax({
                    url: '../kiosk/addon_pages/get_bench.php',
                    cache: false,
                    async: true,
                    data: {ddl_st_agncy: ddl_st_agncy, ddl_court: ddl_court},
//            beforeSend: function () {
//                $('#dv_ent_z').html('<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>');
//            },
                    type: 'POST',
                    success: function (data, status) {

                        $('#ddl_bench').html(data);
                        if (str == 1)
                        {
                            $('#ddl_bench').val('10000');
                            $('#ddl_st_agncy').attr('disabled', true);
                        } else
                        {
                            $('#ddl_bench').val('');
//                               $('#ddl_st_agncy').val('')
                            $('#ddl_st_agncy').attr('disabled', false);
                        }

                    },
                    error: function (xhr) {
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                    }

                });
            }
        }

        function get_lc_casetype(z, idd) {
            //alert("sssss:"+z);
            var ddl_st_agncy = '';
            var m_ldist = '';
            var cl_hc_dc = '';
            if (idd == 'ddl_bench')
            {
                ddl_st_agncy = $('#ddl_st_agncy').val();
                cl_hc_dc = $('#ddl_court').val();
            }


            var corttyp = z;

            var ajaxRequest2;  // The variable that makes Ajax possible!
            try {
                // Opera 8.0+, Firefox, Safari
                ajaxRequest2 = new XMLHttpRequest();
            } catch (e) {
                // Internet Explorer Browsers
                try {
                    ajaxRequest2 = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                    try {
                        ajaxRequest2 = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {
                        // Something went wrong
                        alert("Your browser broke!");
                        return false;
                    }
                }
            }
            // Create a function that will receive data sent from the server
            ajaxRequest2.onreadystatechange = function () {
                if (ajaxRequest2.readyState == 4) {
                    var iop2 = ajaxRequest2.responseText;

                    if (idd == 'ddl_bench')
                    {
                        $('#ddl_ref_case_type').html(ajaxRequest2.responseText);
                    }

                }
            }

            var url = "../kiosk/addon_pages/get_lc_casetype.php?corttyp=" + corttyp + '&ddl_st_agncy=' + ddl_st_agncy + '&m_ldist=' + m_ldist + "&cl_hc_dc=" + cl_hc_dc;
            //alert(url);
//            document.getElementById("m_lct_type").innerHTML='<blink><b> Please wait...</blink>';
            ajaxRequest2.open("GET", url, true);
            ajaxRequest2.send(null);

//        }
        }

        $(document).on('change', '#ddl_st_agncy,#ddl_court', function () {
            get_benches('0');
        });


        $(document).on('change', '#ddl_bench', function () {
//           var org_id=$(this).attr('id');
            var idd = $(this).attr('id');

            var ddl_ref_court = $('#ddl_court').val();
//     alert(ddl_ref_court);

            if (ddl_ref_court == '1' || ddl_ref_court == '3')
            {
                if (ddl_ref_court == '1')
                    var chk_status = 'H';
                else
                    var chk_status = 'L';
                get_lc_casetype(chk_status, idd);
            } else if (ddl_ref_court == '4' || ddl_ref_court == '5')
            {
                get_lc_casetype(chk_status, idd);
            }
        });

        $(document).on('change', '#ddl_court', function () {


            var idd = $('#ddl_court').val();
            var ddl_bench = $('#ddl_bench').attr('id');

            if (idd == '4')
            {
                $('#ddl_st_agncy').val('490506');

                get_benches('1');
                var ddl_ref_court = $('#ddl_court').val();
//     alert(ddl_ref_court);

                if (ddl_ref_court == '1' || ddl_ref_court == '3')
                {
                    if (ddl_ref_court == '1')
                        var chk_status = 'H';
                    else
                        var chk_status = 'L';
                    get_lc_casetype(chk_status, ddl_bench);
                } else if (ddl_ref_court == '4' || ddl_ref_court == '5')
                {
                    get_lc_casetype(chk_status, ddl_bench);
                }

            }
        });

        $(document).on('click', '#btn_left', function () {
            $('#btn_left').attr('disabled', true);
            var ddl_court = $('#ddl_court').val();
            var ddl_st_agncy = $('#ddl_st_agncy').val();
            var ddl_bench = $('#ddl_bench').val();
            var ddl_ref_case_type = $('#ddl_ref_case_type').val();
            var txt_ref_caseno = $('#txt_ref_caseno').val();
            var ddl_ref_caseyr = $('#ddl_ref_caseyr').val();
            var txt_order_date = $('#txt_order_date').val();


            var ct_count = parseInt($('#inc_count').val());
            var hd_fst = parseInt($('#hd_fst').val());
//                alert($('#inc_count').val());
            var inc_val = parseInt($('#inc_val').val());
            var inc_tot = parseInt($('#inc_tot').val());

            var sp_frst = parseInt($('#sp_frst').html()) - inc_val;
            var inc_tot_pg = sp_frst;
//               alert(inc_tot_pg);
            if ($('#btn_right').is(':disabled'))
            {
                $('#btn_right').attr('disabled', false);
            }
//                if(hd_fst==0)
//                    {
//                     $('#btn_left').attr('disabled',false);
//
//                    }
            var nw_hd_fst = hd_fst - inc_val;
            $('#inc_count').val(ct_count - 1);
            if ($('#inc_count').val() == 1)
            {
                $('#btn_left').attr('disabled', true);
            }
            $.ajax({
                url: 'php/include_caveat_caveat.php',
                type: "GET",
                cache: false,
                async: true,
                beforeSend: function () {

                    $('#dv_include').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                data: {nw_hd_fst: nw_hd_fst, inc_val: inc_val, u_t: 1, inc_tot_pg: inc_tot_pg,
                    ddl_st_agncy: ddl_st_agncy, ddl_court: ddl_court, ddl_bench: ddl_bench,
                    ddl_ref_case_type: ddl_ref_case_type, txt_ref_caseno: txt_ref_caseno, ddl_ref_caseyr: ddl_ref_caseyr,
                    txt_order_date: txt_order_date},
                success: function (data, status) {


                    $('#dv_include').html(data);

//                           alert( $('#inc_count').val());
                    $('#hd_fst').val(nw_hd_fst);
                    $('#sp_last').html(parseInt($('#sp_frst').html()) - 1);
                    $('#sp_frst').html(parseInt($('#sp_frst').html()) - inc_val);

                    if (sp_frst == 1)
                        $('#btn_left').attr('disabled', true);
                    else
                        $('#btn_left').attr('disabled', false);
                },
                error: function (xhr) {
                    alert("Error: " + xhr.status + ' ' + xhr.statusText);
                }
            });
        });

        $(document).on('click', '#btn_right', function () {
//    $('#btn_right').click(function(){

            $('#btn_right').attr('disabled', true);
            var ddl_court = $('#ddl_court').val();
            var ddl_st_agncy = $('#ddl_st_agncy').val();
            var ddl_bench = $('#ddl_bench').val();
            var ddl_ref_case_type = $('#ddl_ref_case_type').val();
            var txt_ref_caseno = $('#txt_ref_caseno').val();
            var ddl_ref_caseyr = $('#ddl_ref_caseyr').val();
            var txt_order_date = $('#txt_order_date').val();
            var ct_count = parseInt($('#inc_count').val());
            var hd_fst = parseInt($('#hd_fst').val());
            var inc_val = parseInt($('#inc_val').val());

            var inc_tot = parseInt($('#inc_tot').val());
            var inc_tot_pg = parseInt($('#inc_tot_pg').val());
//              alert(inc_tot_pg);
            if (hd_fst == 0)
            {
                $('#btn_left').attr('disabled', false);

            }
            var nw_hd_fst = hd_fst + inc_val;
//                 alert(ct_count);
//                  alert(inc_val);
//   alert(ct_count+'@@'+hd_fst+'@@'+inc_val+'@@'+inc_tot+'@@'+inc_tot_pg+'@@'+nw_hd_fst);
            if (ct_count == inc_tot - 1)
            {
                $('#btn_right').attr('disabled', true);
            }
            $.ajax({
                url: 'php/include_caveat_caveat.php',
                type: "GET",
                cache: false,
                async: true,
                beforeSend: function () {

                    $('#dv_include').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                data: {nw_hd_fst: nw_hd_fst, inc_val: inc_val, u_t: 1, inc_tot_pg: inc_tot_pg,
                    ddl_st_agncy: ddl_st_agncy, ddl_court: ddl_court, ddl_bench: ddl_bench,
                    ddl_ref_case_type: ddl_ref_case_type, txt_ref_caseno: txt_ref_caseno, ddl_ref_caseyr: ddl_ref_caseyr,
                    txt_order_date: txt_order_date},
                success: function (data, status) {

//                             alert(data);

                    $('#dv_include').html(data);
//                                 alert($('dv_include').html());
                    $('#inc_count').val(ct_count + 1);
//                           alert( $('#inc_count').val());
                    $('#hd_fst').val(nw_hd_fst);

                    $('#sp_frst').html(parseInt($('#sp_last').html()) + 1);
                    var sp_last_ck = parseInt($('#sp_last').html()) + inc_val;
                    var sp_nf = parseInt($('#sp_nf').html());
//                         alert(sp_last_ck+'$$'+sp_nf);
                    if (sp_last_ck <= sp_nf)
                    {
                        $('#sp_last').html(parseInt($('#sp_last').html()) + inc_val);
                        $('#btn_right').attr('disabled', false);
                    } else
                    {
//                                  $('#sp_last').html('');
                        $('#sp_last').html(sp_nf);
                        $('#btn_right').attr('disabled', true);
                    }

                },
                error: function (xhr) {
                    alert("Error: " + xhr.status + ' ' + xhr.statusText);
                }
            });


        });
        $(document).on('click', '.cl_off_rop', function () {
            var idd = $(this).attr('id');
            // alert(idd);
            var str_idd = idd.split('.');

//
            if (str_idd[1] == 'html')
            {
                document.getElementById('ggg1').scrollTop = 0;
                document.getElementById('ggg1').style.width = 'auto';
                document.getElementById('ggg1').style.height = ' 550px';
                document.getElementById('ggg1').style.overflow = 'hidden';
                document.getElementById('ggg1').style.marginLeft = '18px';
                document.getElementById('ggg1').style.marginRight = '18px';
                document.getElementById('ggg1').style.marginBottom = '25px';
                document.getElementById('ggg1').style.marginTop = '20px';
                document.getElementById('ggg1').style.overflow = 'scroll';
                document.getElementById('dv_sh_hd1').style.display = 'block';
                document.getElementById('dv_fixedFor_P1').style.display = 'block';
                document.getElementById('dv_fixedFor_P1').style.marginTop = '3px';
                $.ajax({
                    url: 'php/get_text_file.php',
                    cache: false,
                    async: true,
                    data: {idd: idd},
                    beforeSend: function () {
                        $('#ggg1').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                    },
                    type: 'POST',
                    success: function (data, status) {
                        $('#ggg1').html(data);
                    },
                    error: function (xhr) {
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                    }

                });
            } else if (str_idd[1] == 'pdf')
            {
                document.getElementById('ggg').scrollTop = 0;
                document.getElementById('ggg').style.width = 'auto';
                document.getElementById('ggg').style.height = ' 550px';
                document.getElementById('ggg').style.overflow = 'hidden';

                document.getElementById('ggg').style.marginLeft = '18px';
                document.getElementById('ggg').style.marginRight = '18px';
                document.getElementById('ggg').style.marginBottom = '25px';
                document.getElementById('ggg').style.marginTop = '20px';
                document.getElementById('ggg').style.overflow = 'scroll';
                document.getElementById('dv_sh_hd').style.display = 'block';
                document.getElementById('dv_fixedFor_P').style.display = 'block';
                document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
                idd = "../" + idd;
                document.getElementById('ob_shw').setAttribute('data', idd);
            }

        });
        $(document).on('click', '#getCaveatHCN', function () {
            var ddl_court = $('#ddl_court').val();
            var ddl_st_agncy = $('#ddl_st_agncy').val();
            var ddl_bench = $('#ddl_bench').val();
            var ddl_ref_case_type = $('#ddl_ref_case_type').val();
            var txt_ref_caseno = $('#txt_ref_caseno').val();
            var ddl_ref_caseyr = $('#ddl_ref_caseyr').val();
            var txt_order_date = $('#txt_order_date').val();
//          alert(txt_order_date);
            if (ddl_court == '')
            {
                alert("Please select court");
                $('#ddl_court').focus();
                return false;
            }
            if (ddl_ref_caseyr == '')
            {
                alert("Please select year");
                $('#ddl_ref_caseyr').focus();
                return false;
            }
            if (ddl_st_agncy == '')
            {
                alert("Please select state");
                $('#ddl_st_agncy').focus();
                return false;
            }
            $('#getCaveatHCN').attr('disabled', true);
            $.ajax({
                url: 'addon_pages/get_caveat_to_caveat.php',
                cache: false,
                async: true,
                data: {ddl_st_agncy: ddl_st_agncy, ddl_court: ddl_court, ddl_bench: ddl_bench,
                    ddl_ref_case_type: ddl_ref_case_type, txt_ref_caseno: txt_ref_caseno, ddl_ref_caseyr: ddl_ref_caseyr,
                    txt_order_date: txt_order_date},
                beforeSend: function () {
                    $("#CHCNdisplay").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");
                },
                type: 'POST',
                success: function (data, status) {

                    $('#CHCNdisplay').html(data);
                    $('#getCaveatHCN').attr('disabled', false);
                },
                error: function (xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            });
        });

        $(document).on('click', "#PNdisplay .pagination li.active", function () {
            var page = $(this).attr('p');
            loadData(page);

        });

        $(document).on('click', "#go_btn", function () {
            var page = parseInt($('.goto').val());
            var no_of_pages = parseInt($('.total').attr('a'));
            if (page != 0 && page <= no_of_pages) {
                loadData(page);
            } else {
                alert('Enter a PAGE between 1 and ' + no_of_pages);
                $('.goto').val("").focus();
                return false;
            }
        });

        $(document).on('click', "#verifyotp", function () {
            var mb = $("#mob").val();
            var votp = $("#otp").val();
            if (mb == '' || mb.length != 10) {
                alert("ENTER 10 DIGIT MOBILE NO.");
                return;
            }
            if (votp == '' || votp.length != 6) {
                alert("ENTER 6 DIGIT OTP");
                return;
            }
            $.ajax({
                url: 'verifyotp.php',
                type: "POST",
                data: {mob: $("#mob").val(), votp: $("#otp").val()},
                cache: false,
                success: function (r)
                {
                    if (r != '')
                        alert(r);
                    else {
                        document.getElementById('sumitotp').style.display = 'none';
                        document.getElementById('verifyotp').style.display = 'block';
                    }
                    parent.jQuery.fn.colorbox.close();
                    parent.clickonbutton();

                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $(document).on('click', "#sumitotp", function () {
            var mb = $("#mob").val();
            if (mb == '' || mb.length != 10) {
                alert("ENTER 10 DIGIT MOBILE NO.");
                return;
            }
            $.ajax({
                url: 'send_sms.php',
                type: "POST",
                data: {mob: $("#mob").val()},
                cache: false,
                success: function (r)
                {
                    if (r != '')
                        alert(r);
                    else {

                        document.getElementById('votp').style.display = 'block';
                        document.getElementById('sumitotp').style.display = 'none';
                        document.getElementById('verifyotp').style.display = 'block';
                    }
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });


        function loadData(page) {			//alert(opt);
            $("#PNdisplay").html('');
            var PartyType = $('#party_type').find(":selected").val();
            var PartyName = $('#partyname').val();
            var PartyYear = $('#partyyear').find(":selected").val();
            var PartyCaseType = $('#party_case_type').find(":selected").val();
            var PartyCaseType1 = $("#party_case_type option:selected").text();
            var PartyStatus = $('#ppd').val();
            if ($.trim(PartyName) == "" || $.trim(PartyName).length < 3)
            {
                alert("Please Fill Party Name");
                $("#partyname").focus();
                return false;
            }
            if (PartyCaseType == '')
            {
                alert("Please Select Case Type");
                $("#PartyCaseType").focus();
                return false;
            }
            $("#PNdisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");
            $('#getPartyData').hide();
            $.ajax
            ({
                url: 'php/getPartyDetails.php',
                type: "POST",
                data: {PartyType: PartyType, PartyName: PartyName, PartyYear: PartyYear, PartyCaseType: PartyCaseType + '^^' + PartyCaseType1, PartyStatus: PartyStatus, page: page},
                cache: false,
                success: function (r)
                {
                    $('#getPartyData').show();
                    $("#PNdisplay").html(r);
                    var $html = $("#PNdisplay");
                    $html.find('a.colorbox-inline').colorbox({width: '960px', height: '90%', iframe: true});
                },
                error: function () {
                    alert('ERRO');
                }
            });
        }

        $(document).on('click', "#ANdisplay .pagination1 li.active", function () {
            var page = $(this).attr('p');
            loadData1(page);

        });

        $(document).on('click', "#go_btn1", function () {
            var page = parseInt($('.goto1').val());
            var no_of_pages = parseInt($('.total1').attr('a'));
            if (page != 0 && page <= no_of_pages) {
                loadData1(page);
            } else {
                alert('Enter a PAGE between 1 and ' + no_of_pages);
                $('.goto1').val("").focus();
                return false;
            }
        });

        function loadData1(page) {			//alert(opt);
            $("#ANdisplay").html('');
            var ANparty_type = $('#ANparty_type').find(":selected").val();
            var Advocatename = $('#Advocatename').val();
            var AOR = $('#aor').val();
//            alert(AOR);
            var ANyear = $('#ANyear').find(":selected").val();
            var ANcase_type = $('#ANcase_type').find(":selected").val();
            var ANCaseType1 = $("#ANcase_type option:selected").text();
            var ANppd = $('#ANppd').val();
            if (($.trim(Advocatename) == "" || $.trim(Advocatename).length < 3) && AOR=='')
            {
                alert("Please Fill AOR");
                $("#Advocatename").focus();
                return false;
            }
//            if(ANcase_type=='')
//            {
//            alert("Please Select Case Type");
//            $("#ANcase_type").focus();
//            return false;
//            }
            $("#ANdisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/getAdvocateNameDetails.php',
                type: "POST",
                data: {ANparty_type: ANparty_type, Advocatename: Advocatename, ANyear: ANyear, ANcase_type: ANcase_type + '^^' + ANCaseType1, ANppd: ANppd, AOR:AOR, page: page},
                cache: false,
                success: function (r)
                {
                    $("#ANdisplay").html(r);
                    var $html = $("#ANdisplay");
                    $html.find('a.colorbox-inline').colorbox({width: '960px', height: '90%', iframe: true});

                },
                error: function () {
                    alert('ERRO');
                }
            });
        }
        $("#tabbed-nav").zozoTabs({
            theme: "gray",
            orientation: "horizontal",
            position: "top-compact",
            size: "mini",
            rememberState: true,
            animation: {
                easing: "easeInOutExpo",
                duration: 400,
                effects: "fade"
            },
//            autoplay: {
//                interval: 5000,
//                smart: true
//            },
            defaultTab: "tab1"
        });

        $(".nested-tabs").zozoTabs({
            position: "top-left",
            theme: "red",
            style: "underlined",
            rounded: false,
            shadows: false,
            defaultTab: "tab1",
            rememberState: true,
            animation: {
                easing: "easeInOutCirc",
                effects: "fade"
            },
            size: "medium"
        });

        $(document).on('mouseover', "#tabbed-nav", function () {
            $("#tabbed-nav").data('zozoTabs').stop();
        });

        $(document).on('click', "#linktopdf", function () {
            window.open($(this).data('fn'), 'pdf');
        });

        $(document).on('click', "#linktopdf1", function () {
            var fno = $(this).data('fn');
            $.ajax({
                type: 'POST',
                url: "./php/get_office_report_html.php",
                data: {fno: fno}
            })
                .done(function (msg) {
                    $("#newc123").html(msg);
                })
                .fail(function () {
                    alert("ERROR, Please Contact Server Room");
                });
            var divname = "";
            divname = "newc";
            $('#' + divname).width($(window).width() - 150);
            $('#' + divname).height($(window).height() - 120);
            $('#newc123').height($('#newc').height() - $('#newc1').height() - 50);

            var newX = ($('#' + divname).width() / 2);
            var newY = ($('#' + divname).height() / 2);
            document.getElementById(divname).style.marginLeft = "-" + newX + "px";
            document.getElementById(divname).style.marginTop = "-" + newY + "px";
            document.getElementById(divname).style.display = 'block';
            document.getElementById(divname).style.zIndex = 10;
            $('#overlay').height($(window).height());
            document.getElementById('overlay').style.display = 'block';
        });

        $(".numeric").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                $(".errmsg").html("Digits Only").show().fadeOut("slow");
                return false;
            }
        });

        $("#getCaseDiary").click(function ()
        {
            var d_no = $('#CaseDiaryNumber').val();
            var d_yr = $('#CaseDiaryYear').find(":selected").val();


            if ($.trim(d_no) == "")
            {
                alert("Please Fill Diary Number");
                $("#CaseDiaryNumber").focus();
                return false;
            }
            $("#DNdisplay").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                //url: 'php/getCaseDetailsDN.php',
                url: 'case_status_process.php',
                type: "POST",
                data: {d_no: d_no, d_yr: d_yr},
                cache: false,
                success: function (r)
                {
                    if(r=='0')
                    {
                        $("#DNdisplay").html(' ');
                        // location.reload();
                    }
                    else {
                        $("#DNdisplay").html(r);

                    }

                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCaseData").click(function ()
        {
            var CaseType = $('#case_type').find(":selected").val();
            var CaseNumber = $('#CaseNumber').val();
            var CaseYear = $('#CaseYear').find(":selected").val();

            if (CaseType == '')
            {
                alert("Please Select Case Type");
                $("#case_type").focus();
                return false;
            }
            if (CaseNumber == '')
            {
                alert("Please Fill Case Number");
                $("#CaseNumber").focus();
                return false;
            }
            $("#CNdisplay").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                // url: 'php/getCaseDetails.php',
                url: 'case_status_process.php',
                type: "POST",
                //data: {CaseType: CaseType, CaseNumber: CaseNumber, CaseYear: CaseYear},
                data: {ct: CaseType, cn: CaseNumber, cy: CaseYear},
                cache: false,
                success: function (r)
                {
                    if(r=='0')
                    {
                        alert('Invalid Captcha');
                        $("#CNdisplay").html(' ');
                        // location.reload();
                    }
                    else {
                        $("#CNdisplay").html(r);
                    }

                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getPartyData").click(function ()
        {
            var PartyType = $('#party_type').find(":selected").val();
            var PartyName = $('#partyname').val();
            var PartyYear = $('#partyyear').find(":selected").val();


            var PartyStatus = $('#ppd').val();
            if ($.trim(PartyName) == "" || $.trim(PartyName).length < 3)
            {
                alert("Please Fill Party Name");
                $("#partyname").focus();
                return false;
            }

            $("#PNdisplay").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");
            $.ajax
            ({
                url: 'addon_pages/getPartyDetails.php',
                type: "POST",
                data: {PartyType: PartyType, PartyName: PartyName, PartyYear: PartyYear,  PartyStatus: PartyStatus, page: 1},
                cache: false,
                success: function (r)
                {
                    if(r=='0')
                    {
                        alert('Invalid Captcha');
                        $("#PNdisplay").html(' ');
                        // location.reload();
                    }
                    else {
                        $('#getPartyData').show();
                        $("#PNdisplay").html(r);
                        var $html = $("#PNdisplay");
                        $html.find('a.colorbox-inline').colorbox({width: '960px', height: '90%', iframe: true});

                    }
                    $('#ansCaptcha').val('');
                    document.getElementById('captcha').src='php/captcha.php?'+Math.random();

                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getAdvocateData").click(function ()
        {
            var ANparty_type = $('#ANparty_type').find(":selected").val();
            var Advocatename = $('#Advocatename').val();
            var AOR = $('#aor').val();
            var ANyear = $('#ANyear').find(":selected").val();


            var ANppd = $('#ANppd').val();
            if (($.trim(Advocatename) == "" || $.trim(Advocatename).length < 3) && $.trim(AOR) == "")
            {
                alert("Please Fill AOR Number/Name");
                $("#Advocatename").focus();
                return false;
            }

            $("#ANdisplay").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getAdvocateNameDetails.php',
                type: "POST",
                data: {ANparty_type: ANparty_type, AOR: AOR, Advocatename: Advocatename, ANyear: ANyear, ANppd: ANppd, page: 1},
                cache: false,
                success: function (r)
                {
                    if(r=='0')
                    {
                        alert('Invalid Captcha');
                        $("#ANdisplay").html(' ');
                        // location.reload();
                    }
                    else {
                        $("#ANdisplay").html(r);

                    }

                },
                error: function () {
                    alert('ERRO');
                    $("#ANdisplay").html("");
                }
            });
        });
        $("#getLowerCourtData1").click(function ()
        {
            var ddl_court = $('#ddl_court').find(":selected").val();
            var ddl_st_agncy = $('#ddl_st_agncy').find(":selected").val();
            var ddl_bench = $('#ddl_bench').find(":selected").val();
            var ddl_ref_case_type = $('#ddl_ref_case_type').find(":selected").val();
            var txt_ref_caseno = $('#txt_ref_caseno').val();
            var ddl_ref_caseyr = $('#ddl_ref_caseyr').find(":selected").val();
            var txt_order_date = $('#txt_order_date').val();


            if ($.trim(ddl_court) == "")
            {
                alert("Please Select Court");
                $("#ddl_court").focus();
                return false;
            }
            if ($.trim(ddl_st_agncy) == "")
            {
                alert("Please Select State");
                $("#ddl_st_agncy").focus();
                return false;
            }
            if ($.trim(ddl_ref_caseyr) == "")
            {
                alert("Please Select Year");
                $("#ddl_ref_caseyr").focus();
                return false;
            }
            $("#LCdisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/get_diary_diary.php',
                type: "POST",
                data: {ddl_court: ddl_court, ddl_st_agncy: ddl_st_agncy, ddl_bench: ddl_bench, ddl_ref_case_type: ddl_ref_case_type, txt_ref_caseno: txt_ref_caseno, ddl_ref_caseyr: ddl_ref_caseyr, txt_order_date: txt_order_date},
                cache: false,
                success: function (r)
                {
                    if(r=='0')
                    {
                        alert('Invalid Captcha');
                        $("#LCdisplay").html(' ');
                        // location.reload();
                    }
                    else {
                        $("#LCdisplay").html(r);

                    }

                    //$("#LCdisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });
        $(document).on('click','#btn_left_cs',function(){
            $('#btn_left').attr('disabled',true);
            var ddl_court=$('#ddl_court').val();
            var ddl_st_agncy=$('#ddl_st_agncy').val();
            var ddl_bench=$('#ddl_bench').val();
            var ddl_ref_case_type=$('#ddl_ref_case_type').val();
            var txt_ref_caseno=$('#txt_ref_caseno').val();
            var ddl_ref_caseyr=$('#ddl_ref_caseyr').val();
            var txt_order_date=$('#txt_order_date').val();


            var ct_count=parseInt($('#inc_count').val());
            var hd_fst=parseInt($('#hd_fst').val());
//                alert($('#inc_count').val());
            var inc_val=parseInt($('#inc_val').val());
            var inc_tot=parseInt($('#inc_tot').val());

            var sp_frst=parseInt($('#sp_frst_cs').html())-inc_val;
            var inc_tot_pg=sp_frst;
//               alert(inc_tot_pg);
            if($('#btn_right_cs').is(':disabled'))
            {
                $('#btn_right_cs').attr('disabled',false);
            }
//                if(hd_fst==0)
//                    {
//                     $('#btn_left').attr('disabled',false);
//
//                    }
            var nw_hd_fst=hd_fst-inc_val;
            $('#inc_count').val(ct_count-1);
            if($('#inc_count').val()==1)
            {
                $('#btn_left_cs').attr('disabled',true);
            }
            $.ajax({
                url:'php/include_diary_diary.php',
                type:"GET",
                cache:false,
                async:true,
                beforeSend:function(){

                    $('#dv_include_cs').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                data:{nw_hd_fst:nw_hd_fst,inc_val:inc_val,u_t:1,inc_tot_pg:inc_tot_pg,
                    ddl_st_agncy: ddl_st_agncy,ddl_court:ddl_court,ddl_bench:ddl_bench,
                    ddl_ref_case_type:ddl_ref_case_type,txt_ref_caseno:txt_ref_caseno,ddl_ref_caseyr:ddl_ref_caseyr,
                    txt_order_date:txt_order_date},
                success:function(data,status){



                    $('#dv_include_cs').html(data);

//                           alert( $('#inc_count').val());
                    $('#hd_fst').val(nw_hd_fst);
                    $('#sp_last_cs').html(parseInt($('#sp_frst_cs').html())-1);
                    $('#sp_frst_cs').html(parseInt($('#sp_frst_cs').html())-inc_val);

                    if(sp_frst==1)
                        $('#btn_left_cs').attr('disabled',true);
                    else
                        $('#btn_left_cs').attr('disabled',false);
                },
                error:function(xhr){
                    alert("Error: "+xhr.status+' '+xhr.statusText);
                }
            });
        });

        $(document).on('click','#btn_right_cs',function(){
//    $('#btn_right').click(function(){

            $('#btn_right_cs').attr('disabled',true);
            var ddl_court=$('#ddl_court').val();
            var ddl_st_agncy=$('#ddl_st_agncy').val();
            var ddl_bench=$('#ddl_bench').val();
            var ddl_ref_case_type=$('#ddl_ref_case_type').val();
            var txt_ref_caseno=$('#txt_ref_caseno').val();
            var ddl_ref_caseyr=$('#ddl_ref_caseyr').val();
            var txt_order_date=$('#txt_order_date').val();
            var ct_count=parseInt($('#inc_count').val());
            var hd_fst=parseInt($('#hd_fst').val());
            var inc_val=parseInt($('#inc_val').val());

            var inc_tot=parseInt($('#inc_tot').val());
            var inc_tot_pg=parseInt($('#inc_tot_pg').val());
//              alert(inc_tot_pg);
            if(hd_fst==0)
            {
                $('#btn_left_cs').attr('disabled',false);

            }
            var nw_hd_fst=hd_fst+inc_val;
//                 alert(ct_count);
//                  alert(inc_val);
//   alert(ct_count+'@@'+hd_fst+'@@'+inc_val+'@@'+inc_tot+'@@'+inc_tot_pg+'@@'+nw_hd_fst);
            if(ct_count==inc_tot-1)
            {
                $('#btn_right_cs').attr('disabled',true);
            }
            $.ajax({
                url:'php/include_diary_diary.php',
                type:"GET",
                cache:false,
                async:true,
                beforeSend:function(){

                    $('#dv_include_cs').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                data:{nw_hd_fst:nw_hd_fst,inc_val:inc_val,u_t:1,inc_tot_pg:inc_tot_pg,
                    ddl_st_agncy: ddl_st_agncy,ddl_court:ddl_court,ddl_bench:ddl_bench,
                    ddl_ref_case_type:ddl_ref_case_type,txt_ref_caseno:txt_ref_caseno,ddl_ref_caseyr:ddl_ref_caseyr,
                    txt_order_date:txt_order_date},
                success:function(data,status){

//                             alert(data);

                    $('#dv_include_cs').html(data);
//                                 alert($('dv_include').html());
                    $('#inc_count').val(ct_count+1);
//                           alert( $('#inc_count').val());
                    $('#hd_fst').val(nw_hd_fst);

                    $('#sp_frst_cs').html(parseInt($('#sp_last_cs').html())+1);
                    var sp_last_ck= parseInt($('#sp_last_cs').html())+inc_val;
                    var sp_nf = parseInt($('#sp_nf_cs').html());
//                         alert(sp_last_ck+'$$'+sp_nf);
                    if(sp_last_ck<=sp_nf)
                    {
                        $('#sp_last_cs').html(parseInt($('#sp_last_cs').html())+inc_val);
                        $('#btn_right_cs').attr('disabled',false);
                    }
                    else
                    {
//                                  $('#sp_last').html('');
                        $('#sp_last_cs').html(sp_nf);
                        $('#btn_right_cs').attr('disabled',true);
                    }

                },
                error:function(xhr){
                    alert("Error: "+xhr.status+' '+xhr.statusText);
                }
            });


        });

        $("#getLowerCourtData").click(function ()
        {
            var LCstate = $('#LCstate').find(":selected").val();
            var LowerCourtNumberYear = $('#LowerCourtNumberYear').find(":selected").val();
            var LowerCourtNumber = $('#LowerCourtNumber').val();
            var LCJudgement_date = $('#LCJudgement_date').val();
            if ($.trim(LCstate) == "" || $.trim(LCstate).length < 3)
            {
                alert("Please Select State");
                $("#LCstate").focus();
                return false;
            }
            if ($.trim(LowerCourtNumber) == "")
            {
                alert("Please Fill High Court Number");
                $("#LowerCourtNumber").focus();
                return false;
            }
            $("#LCdisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/getLowerCourtDetails.php',
                type: "POST",
                data: {LCstate: LCstate, LowerCourtNumber: LowerCourtNumber, LCJudgement_date: LCJudgement_date, LowerCourtNumberYear: LowerCourtNumberYear},
                cache: false,
                success: function (r)
                {
                    $("#LCdisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getFilingDefects").click(function ()
        {
            var case_DiaryNo_FD = $('#case_DiaryNo_FD').val();
            var case_year_FD = $('#case_year_FD').find(":selected").val();
            if ($.trim(case_DiaryNo_FD) == "")
            {
                alert("Please Fill Diary Number");
                $("#case_DiaryNo_FD").focus();
                return false;
            }
            if ($.trim(case_year_FD) == "" || $.trim(case_year_FD).length < 3)
            {
                alert("Please Select Year");
                $("#case_year_FD").focus();
                return false;
            }
            $("#FDdisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/getFilingDefects.php',
                type: "POST",
                data: {case_DiaryNo_FD: case_DiaryNo_FD, case_year_FD: case_year_FD},
                cache: false,
                success: function (r)
                {
                    $("#FDdisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCopyData").click(function ()
        {
            var copy_type = $('#copy_type').find(":selected").val();
            var copy_number = $('#copy_number').val();
            var copy_year = $('#copy_year').find(":selected").val();
            if ($.trim(copy_number) == "")
            {
                alert("Please Fill Application Number");
                $("#copy_number").focus();
                return false;
            }
            $("#CopyDatadisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getCopyDetails.php',
                type: "POST",
                data: {copy_number: copy_number, copy_year: copy_year, copy_type: copy_type},
                cache: false,
                success: function (r)
                {
                    $("#CopyDatadisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCaveatData").click(function ()
        {
            var caveat_number = $('#caveat_number').val();
            var caveat_case_type = $('#caveat_case_type').find(":selected").val();
            var caveat_year = $('#caveat_year').find(":selected").val();
            if ($.trim(caveat_case_type) == '')
            {
                alert("Please Select Case Type");
                $("#caveat_case_type").focus();
                return false;
            }
            if ($.trim(caveat_number) == "")
            {
                alert("Please Fill Caveat Number");
                $("#caveat_number").focus();
                return false;
            }
            $("#CDdisplay").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getCaveatCaseNo.php',
                type: "POST",
                data: {caveat_number: caveat_number, caveat_case_type: caveat_case_type, caveat_year: caveat_year},
                cache: false,
                success: function (r)
                {
                    $("#CDdisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCaveatNumYr").click(function ()
        {
            var caveat_num = $('#caveat_num').val();
            var caveat_yr = $('#caveat_yr').find(":selected").val();
            if ($.trim(caveat_num) == "")
            {
                alert("Please Fill Caveat Number");
                $("#caveat_num").focus();
                return false;
            }
            $("#CNdisplay").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getCaveatNumYr.php',
                type: "POST",
                data: {caveat_num: caveat_num, caveat_yr: caveat_yr},
                cache: false,
                success: function (r)
                {
                    $("#CNdisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCaveatParty").click(function ()
        {
            var caveat_partyName = $('#caveat_partyName').val();

            if ($.trim(caveat_partyName) == "")
            {
                alert("Please Fill Party Name");
                $("#caveat_partyName").focus();
                return false;
            }
            $("#CPdisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getCaveatParty.php',
                type: "POST",
                data: {caveat_partyName: caveat_partyName},
                cache: false,
                success: function (r)
                {
                    $("#CPdisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCaveatDates").click(function ()
        {
            var caveat_from_date = $('#caveat_from_date').val();
            var caveat_to_date = $('#caveat_to_date').val();
            $("#DCdisplay").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getCaveatDates.php',
                type: "POST",
                data: {caveat_from_date: caveat_from_date, caveat_to_date: caveat_to_date},
                cache: false,
                success: function (r)
                {
                    $("#DCdisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCLJData_my").click(function ()
        {
            var judgename = $('#judgename').find(":selected").val();
            var from_date = $('#from_date').val();
            var stage = $("input[name=stage]:checked").val();
            var msbj = $("input[name=msbj]:checked").val();
            var roles = $('#roles').val();
//            if($.trim(judgename) == "")
//            {
//            alert("Please Select Judgename");
//            $("#judgename").focus();
//            return false;
//            }
            $("#getCLJDatadisplay_my").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/getCLJData_my.php',
                type: "POST",
                data: {judgename: judgename, stage: stage, msbj: msbj, from_date: from_date,roles:roles},
                cache: false,
                success: function (r)
                {
                    $("#getCLJDatadisplay_my").html(r);
                    var $html = $("#getCLJDatadisplay_my");
                    $html.find('a.colorbox-inline').colorbox({width: '960px', height: '90%', iframe: true});


                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCLJData").click(function ()
        {
            var judgename = $('#judgename').find(":selected").val();
            var from_date = $('#from_date').val();
            var stage = $("input[name=stage]:checked").val();
            var msbj = $("input[name=msbj]:checked").val();
//            if($.trim(judgename) == "")
//            {
//            alert("Please Select Judgename");
//            $("#judgename").focus();
//            return false;
//            }
            $("#getCLJDatadisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/getCLJData.php',
                type: "POST",
                data: {judgename: judgename, stage: stage, msbj: msbj, from_date: from_date},
                cache: false,
                success: function (r)
                {
                    $("#getCLJDatadisplay").html(r);
                    var $html = $("#getCLJDatadisplay");
                    $html.find('a.colorbox-inline').colorbox({width: '960px', height: '90%', iframe: true});
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCLJData_sclsc").click(function ()
        {
            var judgename = $('#judgename').find(":selected").val();
            var from_date = $('#from_date').val();
            var userID = $('#userID').val();
            var stage = $("input[name=stage]:checked").val();
            var msbj = $("input[name=msbj]:checked").val();

            $("#getCLJDatadisplay_sclsc").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/editor/getCLJData_sclsc.php',
                type: "POST",
                data: {judgename: judgename, stage: stage, msbj: msbj, from_date: from_date,userID:userID},
                cache: false,
                success: function (r)
                {
                    $("#getCLJDatadisplay_sclsc").html(r);
                    var $html = $("#getCLJDatadisplay_sclsc");
                    $html.find('a.colorbox-inline').colorbox({width: '960px', height: '90%', iframe: true});


                },
                error: function () {
                    alert('ERRO');
                }
            });
        });



        $("#getCLWData").click(function ()
        {
            var courtno_week = $('#courtno_week').find(":selected").val();
//            if(courtno_week==''){
//                alert("Select Court No.");
//                return;
//            }
            var weekcl = $('#weekcl').find(":selected").val();
            $("#getCLWDatadisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/getCLWData.php',
                type: "POST",
                data: {weekcl: weekcl, courtno_week: courtno_week},
                cache: false,
                success: function (r)
                {
                    $("#getCLWDatadisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCLTLData").click(function ()
        {

//            var txtcaseno = $('#txtcaseno').val();
//            var txtyear = $('#txtyear').find(":selected").val();
//            var case_type_tl = $('#case_type_tl').find(":selected").val();
//            var CaseYear_tl = $('#CaseYear_tl').find(":selected").val();
//            var CaseNumber_tl = $('#CaseNumber_tl').val();


            {
                //alert(wr_skey);
                var count = 0;
                var ss = "";
                var val = "";
                var subject_count = 0;
                var subject_val = "";
                var cat_count = 0;
                var cat_val = "";
                var subcat_count = 0;
                var subcat_val = "";
                var subcat2_count = 0;
                var subcat2_val = "";

                for (var i = 0; i < document.getElementById('subject').options.length; i++)
                {
                    if (document.getElementById('subject').options[i].selected == 1)
                    {
                        subject_count++;
                        subject_val = subject_val + document.getElementById('subject').options[i].value + ",";
                    }
                }

                for (var i = 0; i < document.getElementById('cat').options.length; i++)
                {
                    if (document.getElementById('cat').options[i].selected == 1)
                    {
                        cat_count++;
                        cat_val = cat_val + document.getElementById('cat').options[i].value + ",";
                    }
                }

                for (var i = 0; i < document.getElementById('subcat').options.length; i++)
                {
                    if (document.getElementById('subcat').options[i].selected == 1)
                    {
                        subcat_count++;
                        subcat_val = subcat_val + document.getElementById('subcat').options[i].value + ",";
                    }
                }

                for (var i = 0; i < document.getElementById('subcat2').options.length; i++)
                {
                    if (document.getElementById('subcat2').options[i].selected == 1)
                    {
                        subcat2_count++;
                        subcat2_val = subcat2_val + document.getElementById('subcat2').options[i].value + ",";
                    }
                }

                if (cat_count > 1 && document.getElementById('cat').value == 'all')
                {
                    alert('ERROR : Either select "ALL CATEGORY" Or other remaining option from SUB CATEGORY1 BOX');
                    document.getElementById('cat').focus();
                    return false;
                }

                if (subcat_count > 1 && document.getElementById('subcat').value == 'all')
                {
                    alert('ERROR : Either select "ALL CATEGORY" Or other remaining option from SUB CATEGORY2 BOX');
                    document.getElementById('subcat').focus();
                    return false;
                }

                if (subcat2_count > 1 && document.getElementById('subcat2').value == 'all')
                {
                    alert('ERROR : Either select "ALL CATEGORY" Or other remaining option from SUB CATEGORY3 BOX');
                    document.getElementById('subcat').focus();
                    return false;
                }

            }// with end

            //|| ($.trim(case_type_tl) == "" && $.trim(CaseYear_tl) == ""  && $.trim(CaseNumber_tl) == "")
//            if(($.trim(txtcaseno) == "" && $.trim(txtyear) == "") )
//            {
//            alert("Please provide Diary No./Case No.");
//            $("#txtcaseno").focus();
//            return false;
//            }
            $("#getCLTLDatadisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/getCLTLData.php',
                type: "POST",
                data: {subject: subject_val, subject_length: subject_count, cat: cat_val, cat_length: cat_count, subcat: subcat_val, subcat_length: subcat_count, subcat2: subcat2_val, subcat2_length: subcat2_count},
                cache: false,
                success: function (r)
                {
                    $("#getCLTLDatadisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCLLNData").click(function ()
        {
            var lawyername = $('#lawyername').val();
            var cl_en_no = $('#cl_en_no').val();
            var LN_from_date = $('#LN_from_date').val();
            if (($.trim(lawyername) == "" || $.trim(lawyername).length < 3) && $.trim(cl_en_no) == "")
            {
                alert("Please Select Lawyer Name/AOR");
                $("#lawyername").focus();
                return false;
            }

            $("#LNgetCLLNDatadisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/getCLLNData.php',
                type: "POST",
                data: {lawyername: lawyername, cl_en_no: cl_en_no, LN_from_date: LN_from_date},
                cache: false,
                success: function (r)
                {
                    $("#LNgetCLLNDatadisplay").html(r);
                    var $html = $("#LNgetCLLNDatadisplay");
                    $html.find('a.colorbox-inline').colorbox({width: '960px', height: '90%', iframe: true});

                },
                error: function () {
                    alert('ERRO');
                }
            });
        });


        $("#getCLENData").click(function ()
        {
            var cl_en_no = $('#cl_en_no').val();
            var cl_en_from_date = $('#cl_en_from_date').val();

            if ($.trim(cl_en_no) == "")
            {
                alert("Please Fill Enrollment Number");
                $("#cl_en_no").focus();
                return false;
            }

            $("#ENgetCLENDatadisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/getCLENData.php',
                type: "POST",
                data: {cl_en_no: cl_en_no, cl_en_from_date: cl_en_from_date},
                cache: false,
                success: function (r)
                {
                    $("#ENgetCLENDatadisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCLPNData").click(function ()
        {
            var cl_prn_name = $('#cl_prn_name').val();
            var party_from_cl_frm = $('#party_from_cl_frm').find(":selected").val();
            var from_date_cl_pn = $('#from_date_cl_pn').val();
            if ($.trim(cl_prn_name) == "" || $.trim(cl_prn_name).length < 3)
            {
                alert("Please Fill Party Name");
                $("#cl_prn_name").focus();
                return false;
            }

            $("#PNgetCLPNDatadisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/getCLPNData.php',
                type: "POST",
                data: {cl_prn_name: cl_prn_name, party_from_cl_frm: party_from_cl_frm, from_date_cl_pn: from_date_cl_pn},
                cache: false,
                success: function (r)
                {
                    $("#PNgetCLPNDatadisplay").html(r);
                    var $html = $("#PNgetCLPNDatadisplay");
                    $html.find('a.colorbox-inline').colorbox({width: '960px', height: '90%', iframe: true});

                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCLPData").click(function ()
        {
            var PCL_from_date = $('#PCL_from_date').val();
            var causelist_type = $('#new_cis_cl').val();
            var msb_cl = $("input[name=msb_cl]:checked").val();
            var stagecl = $("input[name=stagecl]:checked").val();

            var d1 = PCL_from_date.split("-");
            var cl_date = new Date(d1[2], parseInt(d1[1])-1, d1[0]);
            var threshold_date = new Date("2017-05-08");

            $("#PCLgetCLPNDatadisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");
            if(cl_date>threshold_date) {
                $.ajax
                ({
                    url: 'php/getPCLData.php',
                    type: "POST",
                    data: {PCL_from_date: PCL_from_date, msb_cl: msb_cl, stagecl: stagecl, cl_type:causelist_type},
                    cache: false,
                    success: function (r)
                    {
                        $("#PCLgetCLPNDatadisplay").html(r);
                    },
                    error: function () {
                        alert('ERRO');
                    }
                });
            }
            else{
                $("#PCLgetCLPNDatadisplay").html('<iframe src="/php/getOldCLData.php?date='+PCL_from_date+'&cl_type='+causelist_type+'" width="100%" height="500px"></iframe>');

                /*var request = new XMLHttpRequest();
                request.open("POST", "php/getOldCLData.php", true);
                request.responseType = "blob";
                request.onload = function (e) {
                    if (this.status === 200) {
                        // `blob` response
                        console.log(this.response);
                        // create `objectURL` of `this.response` : `.pdf` as `Blob`
                        var file = window.URL.createObjectURL(this.response);
                        var a = document.createElement("a");
                        a.href = file;
                        a.download = this.response.name || "detailPDF";
                        document.body.appendChild(a);
                        a.click();

                        window.onfocus = function () {
                            document.body.removeChild(a)
                        }
                    };
                };
                request.send();*/
            }

        });

        $("#getSpreadOutData").click(function ()
        {
            var PCL_from_date1 = $('#PCL_from_date1').val();
            var PCL_to_date1 = $('#PCL_to_date1').val();
            var mc = $("input[name=mc1]:checked").val();
            $("#getSpreadOutDatadisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/getSpreadOutData.php',
                type: "POST",
                data: {PCL_from_date1: PCL_from_date1, PCL_to_date1: PCL_to_date1, mc:mc},
                cache: false,
                success: function (r)
                {
                    $("#getSpreadOutDatadisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getSpreadOutData1").click(function ()
        {
            var PCL_from_date1 = $('#PCL_from_date2').val();
            var PCL_to_date1 = $('#PCL_to_date2').val();
            var mc = $("input[name=mc2]:checked").val();
            $("#getSpreadOutDatadisplay1").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/displayChamberCauseList.php',
                type: "POST",
                data: {PCL_from_date1: PCL_from_date1, PCL_to_date1: PCL_to_date1,mc:mc},
                cache: false,
                success: function (r)
                {
                    $("#getSpreadOutDatadisplay1").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });
        $("#getJBD").click(function ()
        {
            var judgename = $('#judgename').find(":selected").val();
            var from_date = $('#from_date').val();
            var rpt_type = $('#rpt_type').find(":selected").val();
            var to_date = $('#to_date').val();
            var jorrop = $('#jorrop').val();
            if ($.trim(judgename) == "")
            {
                alert("Please Select Hon'ble Judge Name");
                $("#judgename").focus();
                return false;
            }

            var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
            var d1 = from_date.split("-");
            var d2 = to_date.split("-");
            var fromd = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
            var tod   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);

            if(tod < fromd){
                alert('Second date is less or equal to First date!');
                return;
            }
//var diffDays = Math.round(Math.abs((fromd.getTime() - tod.getTime())/(oneDay)));
//if(diffDays>365){
// alert('Difference of dates must be less than 365 days!');
//return;
//}

            $("#JBD").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getJBD.php',
                type: "POST",
                data: {judgename: judgename, from_date: from_date, rpt_type: rpt_type, to_date: to_date, jorrop:jorrop},
                cache: false,
                success: function (r)
                {
                    $("#JBD").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });


        $("#getJPN").click(function ()
        {
            var Jparty_type = $('#Jparty_type').find(":selected").val();
            var Jparty_name = $('#Jparty_name').val();
            var Jfrom_date = $('#Jfrom_date').val();
            var Jrpt_type = $('#Jrpt_type').find(":selected").val();
            var Jto_date = $('#Jto_date').val();
            var jorrop = $('#jorrop').val();
            if ($.trim(Jparty_name) == "")
            {
                alert("Please Fill Party Name");
                $("#Jparty_name").focus();
                return false;
            }
            var nw_hd_fst=0;
            var inc_val=200;
            var u_t=1;

            var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
            var d1 = Jfrom_date.split("-");
            var d2 = Jto_date.split("-");
            var fromd = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
            var tod   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);

            if(tod < fromd){
                alert('Second date is less or equal to First date!');
                return;
            }
            var diffDays = Math.round(Math.abs((fromd.getTime() - tod.getTime())/(oneDay)));
            /* if(diffDays>365){
                 alert('Difference of dates must be less than 365 days!');
                 return;
             }
 */
            $("#JPN").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");
            if(jorrop=='J')
            {
                $.ajax
                ({
                    url: 'addon_pages/get_JPN_new.php',
                    type: "POST",
                    data: {nw_hd_fst: nw_hd_fst, inc_val: inc_val, u_t: 1,
                        Jparty_type: Jparty_type, Jparty_name: Jparty_name, Jfrom_date: Jfrom_date, Jrpt_type: Jrpt_type, Jto_date: Jto_date,jorrop: jorrop},
                    cache: false,
                    success: function (r)
                    {
                        $("#JPN").html(r);
                    },
                    error: function () {
                        alert('ERRO');
                    }
                });
            }
            if(jorrop=='O')
            {
                $.ajax
                ({
                    url: 'addon_pages/getJPN.php',
                    type: "POST",
                    data: {Jparty_type: Jparty_type, Jparty_name: Jparty_name, Jfrom_date: Jfrom_date, Jrpt_type: Jrpt_type, Jto_date: Jto_date,jorrop: jorrop},
                    cache: false,
                    success: function (r)
                    {
                        $("#JPN").html(r);
                    },
                    error: function () {
                        alert('ERRO');
                    }
                });
            }
        });
        $(document).on('click', '#jpn_btn_right', function ()
        {

            $('#jpn_btn_right').attr('disabled', true);

            var Jparty_type = $('#Jparty_type').val();
            var Jparty_name = $('#Jparty_name').val();
            var Jfrom_date = $('#Jfrom_date').val();
            var Jrpt_type = $('#Jrpt_type').val();
            var Jto_date = $('#Jto_date').val();
            var jorrop = $('#jorrop').val();
            if ($.trim(Jparty_name) == "")
            {
                alert("Please Fill Party Name");
                $("#Jparty_name").focus();
                return false;
            }

            var ct_count = parseInt($('#inc_count').val());
            var hd_fst = parseInt($('#hd_fst').val());
            var inc_val = parseInt($('#inc_val').val());

            var inc_tot = parseInt($('#inc_tot').val());
            var inc_tot_pg = parseInt($('#inc_tot_pg').val());
//              alert(inc_tot_pg);
            if (hd_fst == 0)
            {
                $('#jpn_btn_left').attr('disabled', false);

            }
            var nw_hd_fst = hd_fst + inc_val;

            if (ct_count == inc_tot - 1)
            {
                $('#jpn_btn_right').attr('disabled', true);
            }
            $.ajax({
                url: 'addon_pages/get_JPN_new.php',
                type: "POST",
                cache: false,
                async: true,
                beforeSend: function () {

                    $('#dv_include').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                data: {nw_hd_fst: nw_hd_fst, inc_val: inc_val, u_t: 1, inc_tot_pg: inc_tot_pg,
                    Jparty_type: Jparty_type, Jparty_name: Jparty_name, Jfrom_date: Jfrom_date, Jrpt_type: Jrpt_type, Jto_date: Jto_date,jorrop: jorrop},
                success: function (data, status) {

//                             alert(data);

                    $('#dv_include').html(data);

                    // $('#dv_right').html('');
                    //alert($('dv_include').html());
                    $('#inc_count').val(ct_count + 1);
//                           alert( $('#inc_count').val());
                    $('#hd_fst').val(nw_hd_fst);

                    $('#sp_frst').html(parseInt($('#sp_last').html()) + 1);
                    var sp_last_ck = parseInt($('#sp_last').html()) + inc_val;
                    var sp_nf = parseInt($('#sp_nf').html());
//                         alert(sp_last_ck+'$$'+sp_nf);
                    if (sp_last_ck <= sp_nf)
                    {
                        $('#sp_last').html(parseInt($('#sp_last').html()) + inc_val);
                        $('#jpn_btn_right').attr('disabled', false);
                    } else
                    {
//                                $('#sp_last').html('');
                        $('#sp_last').html(sp_nf);
                        $('#jpn_btn_right').attr('disabled', true);
                    }
                    //$('#dv_le_ri').hide();
                    $("[id=dv_le_ri]:eq(1)").hide();
                },
                error: function (xhr) {
                    alert("Error: " + xhr.status + ' ' + xhr.statusText);
                }
            });


        });

        $(document).on('click', '#jpn_btn_left', function () {
            $('#jpn_btn_left').attr('disabled', true);
            var Jparty_type = $('#Jparty_type').val();
            var Jparty_name = $('#Jparty_name').val();
            var Jfrom_date = $('#Jfrom_date').val();
            var Jrpt_type = $('#Jrpt_type').val();
            var Jto_date = $('#Jto_date').val();
            var jorrop = $('#jorrop').val();
            if ($.trim(Jparty_name) == "")
            {
                alert("Please Fill Party Name");
                $("#Jparty_name").focus();
                return false;
            }


            var ct_count = parseInt($('#inc_count').val());
            var hd_fst = parseInt($('#hd_fst').val());

            var inc_val = parseInt($('#inc_val').val());
            var inc_tot = parseInt($('#inc_tot').val());

            var sp_frst = parseInt($('#sp_frst').html()) - inc_val;
            var inc_tot_pg = sp_frst;

            if ($('#jpn_btn_right').is(':disabled'))
            {
                $('#jpn_btn_right').attr('disabled', false);
            }
//                if(hd_fst==0)
//                    {
//                     $('#btn_left').attr('disabled',false);
//
//                    }
            var nw_hd_fst = hd_fst - inc_val;
            $('#inc_count').val(ct_count - 1);
            if ($('#inc_count').val() == 1)
            {
                $('#jpn_btn_left').attr('disabled', true);
            }
            $.ajax({
                url: 'addon_pages/get_JPN_new.php',
                type: "POST",
                cache: false,
                async: true,
                beforeSend: function () {

                    $('#dv_include').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                data: {nw_hd_fst: nw_hd_fst, inc_val: inc_val, u_t: 1, inc_tot_pg: inc_tot_pg,
                    Jparty_type: Jparty_type, Jparty_name: Jparty_name, Jfrom_date: Jfrom_date, Jrpt_type: Jrpt_type, Jto_date: Jto_date,jorrop: jorrop
                },
                success: function (data, status) {


                    $('#dv_include').html(data);

//                           alert( $('#inc_count').val());
                    $('#hd_fst').val(nw_hd_fst);
                    $('#sp_last').html(parseInt($('#sp_frst').html()) - 1);
                    $('#sp_frst').html(parseInt($('#sp_frst').html()) - inc_val);

                    if (sp_frst == 1)
                        $('#jpn_btn_left').attr('disabled', true);
                    else
                        $('#jpn_btn_left').attr('disabled', false);

                    $("[id=dv_le_ri]:eq(1)").hide();
                },
                error: function (xhr) {
                    alert("Error: " + xhr.status + ' ' + xhr.statusText);
                }
            });
        });


        $("#getORCN").click(function ()
        {
            var CTcase_type = $('#CTcase_type').find(":selected").val();
            var CTNumber = $('#CTNumber').val();
            var CTyear = $('#CTyear').val();
            var CTrpt = $('#CTrpt').find(":selected").val();

            if ($.trim(CTcase_type) == "")
            {
                alert("Please Select Case Type");
                $("#CTcase_type").focus();
                return false;
            }
            if ($.trim(CTNumber) == "")
            {
                alert("Please Fill Case Number");
                $("#CTNumber").focus();
                return false;
            }


            $("#ORCN").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getORCN.php',
                type: "POST",
                data: {CTcase_type: CTcase_type, CTNumber: CTNumber, CTyear: CTyear, CTrpt: CTrpt},
                cache: false,
                success: function (r)
                {
                    $("#ORCN").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });
        $("#getJCN").click(function ()
        {
            var CTcase_type = $('#CTcase_type').find(":selected").val();
            var CTNumber = $('#CTNumber').val();
            var CTyear = $('#CTyear').val();
            var CTrpt = $('#CTrpt').find(":selected").val();
            var jorrop = $('#jorrop').val();

            if ($.trim(CTcase_type) == "")
            {
                alert("Please Select Case Type");
                $("#CTcase_type").focus();
                return false;
            }
            if ($.trim(CTNumber) == "")
            {
                alert("Please Fill Case Number");
                $("#CTNumber").focus();
                return false;
            }


            $("#JCN").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getJCN.php',
                type: "POST",
                data: {CTcase_type: CTcase_type, CTNumber: CTNumber, CTyear: CTyear, CTrpt: CTrpt, jorrop: jorrop},
                cache: false,
                success: function (r)
                {
                    $("#JCN").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getJAW").click(function ()
        {
            var Jact_name = $('#Jact_name').val();
            var Jfrom_date = $('#JBDfrom_date').val();
            var Jto_date = $('#JBDto_date').val();
            var Jrpt_type = $('#JBDrpt').find(":selected").val();

            if ($.trim(Jact_name) == "")
            {
                alert("Please Fill Act Name");
                $("#Jact_name").focus();
                return false;
            }

            var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
            var d1 = Jfrom_date.split("-");
            var d2 = Jto_date.split("-");
            var fromd = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
            var tod   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);

            if(tod < fromd){
                alert('Second date is less or equal to First date!');
                return;
            }
            var diffDays = Math.round(Math.abs((fromd.getTime() - tod.getTime())/(oneDay)));
            if(diffDays>365){
                alert('Difference of dates must be less than 365 days!');
                return;
            }

            $("#JAW").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getJAW.php',
                type: "POST",
                data: {Jact_name: Jact_name, Jfrom_date: Jfrom_date, Jrpt_type: Jrpt_type, Jto_date: Jto_date},
                cache: false,
                success: function (r)
                {
                    $("#JAW").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getJBJ").click(function ()
        {
            var JBJfrom_date = $('#JBJfrom_date').val();
            var JBJto_date = $('#JBJto_date').val();
            var jorrop = $('#jorrop').val();

            var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
            var d1 = JBJfrom_date.split("-");
            var d2 = JBJto_date.split("-");
            var fromd = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
            var tod   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);

            if(tod < fromd){
                alert('Second date is less or equal to First date!');
                return;
            }
            var diffDays = Math.round(Math.abs((fromd.getTime() - tod.getTime())/(oneDay)));
            if(diffDays>365){
                alert('Difference of dates must be less than 365 days!');
                return;
            }
            $("#JBJ").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getJBJ.php',
                type: "POST",
                data: {JBJfrom_date: JBJfrom_date, JBJto_date: JBJto_date, jorrop: jorrop},
                cache: false,
                success: function (r)
                {
                    $("#JBJ").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        /* $("#getJCB").click(function ()
         {
             var JCBfrom_date = $('#JCBfrom_date').val();
             var JCBto_date = $('#JCBto_date').val();
 var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
 var d1 = JCBfrom_date.split("-");
 var d2 = JCBto_date.split("-");
 var fromd = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
 var tod   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);

 if(tod < fromd){
     alert('Second date is less or equal to First date!');
     return;
 }
 var diffDays = Math.round(Math.abs((fromd.getTime() - tod.getTime())/(oneDay)));
 if(diffDays>365){
  alert('Difference of dates must be less than 365 days!');
 return;
 }
             $("#JCB").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

             $.ajax
                     ({
                         url: 'php/getJCB.php',
                         type: "POST",
                         data: {JCBfrom_date: JCBfrom_date, JCBto_date: JCBto_date},
                         cache: false,
                         success: function (r)
                         {
                             $("#JCB").html(r);
                         },
                         error: function () {
                             alert('ERRO');
                         }
                     });
         });
 */


        $("#getJCB").click(function ()
        {
            var JCBfrom_date = $('#JCBfrom_date').val();
            var JCBto_date = $('#JCBto_date').val();
            var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
            var d1 = JCBfrom_date.split("-");
            var d2 = JCBto_date.split("-");
            var fromd = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
            var tod   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);

            var nw_hd_fst=0;
            var inc_val=50;
            var u_t=1;

            if(tod < fromd){
                alert('Second date is less or equal to First date!');
                return;
            }
            var diffDays = Math.round(Math.abs((fromd.getTime() - tod.getTime())/(oneDay)));
            /*
            if(diffDays>365){
             alert('Difference of dates must be less than 365 days!');
            return;
            }*/
            $("#JCB").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getJCB_new.php',
                type: "POST",
                data: {nw_hd_fst: nw_hd_fst, inc_val: inc_val, u_t: 1,JCBfrom_date: JCBfrom_date, JCBto_date: JCBto_date},
                cache: false,
                success: function (r)
                {
                    $("#JCB").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });

        });

        $(document).on('click', '#jcb_btn_right', function ()
        {
            $('#jcb_btn_right').attr('disabled', true);

            var JCBfrom_date = $('#JCBfrom_date').val();
            var JCBto_date = $('#JCBto_date').val();
            var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
            var d1 = JCBfrom_date.split("-");
            var d2 = JCBto_date.split("-");
            var fromd = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
            var tod   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);

            if(tod < fromd){
                alert('Second date is less or equal to First date!');
                return;
            }

            var ct_count = parseInt($('#inc_count').val());
            var hd_fst = parseInt($('#hd_fst').val());
            var inc_val = parseInt($('#inc_val').val());

            var inc_tot = parseInt($('#inc_tot').val());
            var inc_tot_pg = parseInt($('#inc_tot_pg').val());
//              alert(inc_tot_pg);
            if (hd_fst == 0)
            {
                $('#jcb_btn_left').attr('disabled', false);

            }
            var nw_hd_fst = hd_fst + inc_val;

            if (ct_count == inc_tot - 1)
            {
                $('#jcb_btn_right').attr('disabled', true);
            }
            $.ajax({
                url: 'addon_pages/getJCB_new.php',
                type: "POST",
                cache: false,
                async: true,
                beforeSend: function () {
                    $('#dv_include').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                data: {nw_hd_fst: nw_hd_fst, inc_val: inc_val, u_t: 1, inc_tot_pg: inc_tot_pg,
                    JCBfrom_date: JCBfrom_date, JCBto_date: JCBto_date},
                success: function (data, status) {

//                             alert(data);

                    $('#dv_include').html(data);

                    // $('#dv_right').html('');
                    //alert($('dv_include').html());
                    $('#inc_count').val(ct_count + 1);
//                           alert( $('#inc_count').val());
                    $('#hd_fst').val(nw_hd_fst);

                    $('#sp_frst').html(parseInt($('#sp_last').html()) + 1);
                    var sp_last_ck = parseInt($('#sp_last').html()) + inc_val;
                    var sp_nf = parseInt($('#sp_nf').html());
//                         alert(sp_last_ck+'$$'+sp_nf);
                    if (sp_last_ck <= sp_nf)
                    {
                        $('#sp_last').html(parseInt($('#sp_last').html()) + inc_val);
                        $('#jcb_btn_right').attr('disabled', false);
                    } else
                    {
//                                $('#sp_last').html('');
                        $('#sp_last').html(sp_nf);
                        $('#jcb_btn_right').attr('disabled', true);
                    }
                    //$('#dv_le_ri').hide();
                    $("[id=dv_le_ri]:eq(1)").hide();
                },
                error: function (xhr) {
                    alert("Error: " + xhr.status + ' ' + xhr.statusText);
                }
            });



        });

        $(document).on('click', '#jcb_btn_left', function () {

            $('#jcb_btn_left').attr('disabled', true);

            var JCBfrom_date = $('#JCBfrom_date').val();
            var JCBto_date = $('#JCBto_date').val();
            var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
            var d1 = JCBfrom_date.split("-");
            var d2 = JCBto_date.split("-");
            var fromd = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
            var tod   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);

            if(tod < fromd){
                alert('Second date is less or equal to First date!');
                return;
            }


            var ct_count = parseInt($('#inc_count').val());
            var hd_fst = parseInt($('#hd_fst').val());

            var inc_val = parseInt($('#inc_val').val());
            var inc_tot = parseInt($('#inc_tot').val());

            var sp_frst = parseInt($('#sp_frst').html()) - inc_val;
            var inc_tot_pg = sp_frst;

            if ($('#jcb_btn_right').is(':disabled'))
            {
                $('#jcb_btn_right').attr('disabled', false);
            }
//                if(hd_fst==0)
//                    {
//                     $('#btn_left').attr('disabled',false);
//
//                    }
            var nw_hd_fst = hd_fst - inc_val;
            $('#inc_count').val(ct_count - 1);
            if ($('#inc_count').val() == 1)
            {
                $('#jcb_btn_left').attr('disabled', true);
            }
            $.ajax({
                url: 'addon_pages/getJCB_new.php',
                type: "POST",
                cache: false,
                async: true,
                beforeSend: function () {

                    $('#dv_include').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                data: {nw_hd_fst: nw_hd_fst, inc_val: inc_val, u_t: 1, inc_tot_pg: inc_tot_pg,
                    JCBfrom_date: JCBfrom_date, JCBto_date: JCBto_date
                },
                success: function (data, status) {


                    $('#dv_include').html(data);

//                           alert( $('#inc_count').val());
                    $('#hd_fst').val(nw_hd_fst);
                    $('#sp_last').html(parseInt($('#sp_frst').html()) - 1);
                    $('#sp_frst').html(parseInt($('#sp_frst').html()) - inc_val);

                    if (sp_frst == 1)
                        $('#jcb_btn_left').attr('disabled', true);
                    else
                        $('#jcb_btn_left').attr('disabled', false);

                    $("[id=dv_le_ri]:eq(1)").hide();
                },
                error: function (xhr) {
                    alert("Error: " + xhr.status + ' ' + xhr.statusText);
                }
            });
        });









        $("#getORDN").click(function ()
        {
            var ORDNNumber = $('#ORDNNumber').val();
            var ORDNyear = $('#ORDNyear').val();
            if ($.trim(ORDNNumber) == "")
            {
                alert("Please Fill Diary Number");
                $("#ORDNNumber").focus();
                return false;
            }
            $("#ORDN").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getORDN.php',
                type: "POST",
                data: {ORDNNumber: ORDNNumber, ORDNyear: ORDNyear},
                cache: false,
                success: function (r)
                {
                    $("#ORDN").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getJDN").click(function ()
        {
            var JDNNumber = $('#JDNNumber').val();
            var JDNyear = $('#JDNyear').val();
            var jorrop = $('#jorrop').val();
            if ($.trim(JDNNumber) == "")
            {
                alert("Please Fill Diary Number");
                $("#JDNNumber").focus();
                return false;
            }
            $("#JDN").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/getJDN.php',
                type: "POST",
                data: {JDNNumber: JDNNumber, JDNyear: JDNyear, jorrop: jorrop},
                cache: false,
                success: function (r)
                {
                    $("#JDN").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getDOCT").click(function ()
        {
            var DOcase_type = $('#DOcase_type').find(":selected").val();
            var DONumber = $('#DONumber').val();
            var DOyear = $('#DOyear').val();

            if ($.trim(DOcase_type) == "")
            {
                alert("Please Fill Case Number");
                $("#DOcase_type").focus();
                return false;
            }

            $("#DOCT").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");
            $('#getDOCT').hide();
            $.ajax
            ({
                url: 'php/getDOCT.php',
                type: "POST",
                data: {DOcase_type: DOcase_type, DONumber: DONumber, DOyear: DOyear},
                cache: false,
                success: function (r)
                {
                    $('#getDOCT').show();
                    $("#DOCT").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getDODN").click(function ()
        {
            var DODNNumber = $('#DODNNumber').val();
            var DODNyear = $('#DODNyear').find(":selected").val();
            if ($.trim(DODNNumber) == "")
            {
                alert("Please Fill Diary Number");
                $("#DODNNumber").focus();
                return false;
            }
            $("#DODN").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");
            $('#getDODN').hide();

            $.ajax
            ({
                url: 'php/getDODN.php',
                type: "POST",
                data: {DODNNumber: DODNNumber, DODNyear: DODNyear},
                cache: false,
                success: function (r)
                {
                    $('#getDODN').show();
                    $("#DODN").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $.fn.alphanumeric = function (r) {
            var a, e, n = $(this), l = "abcdefghijklmnopqrstuvwxyz", c = $.extend({ichars: "!@#$%^&*()+=[]\\';,/{}|\":<>?~`.- _", nchars: "", allow: ""}, r), i = c.allow.split(""), h = 0;
            for (h; h < i.length; h++)
                -1 != c.ichars.indexOf(i[h]) && (i[h] = "\\" + i[h]);
            return c.nocaps && (c.nchars += l.toUpperCase()), c.allcaps && (c.nchars += l), c.allow = i.join("|"), e = new RegExp(c.allow, "gi"), a = (c.ichars + c.nchars).replace(e, ""), n.keypress(function (r) {
                var e = String.fromCharCode(r.charCode ? r.charCode : r.which);
                -1 == a.indexOf(e) || r.ctrlKey || r.preventDefault()
            }), n.blur(function () {
                var r = n.val(), e = 0;
                for (e; e < r.length; e++)
                    if (-1 != a.indexOf(r[e]))
                        return n.val(""), !1;
                return!1
            }), n
        };
        $(".alphanumeric").alphanumeric({allow: "'., %"});

        $("#getCLJData2").click(function ()
        {
            var judgename = $('#judgename').find(":selected").val();
            var from_date = $('#from_date').val();
            var stage = $("input[name=stage]:checked").val();

            if ($.trim(judgename) == "")
            {
                alert("Please Select Judgename");
                $("#judgename").focus();
                return false;
            }
            $("#getCLJDatadisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/getCLJData2.php',
                type: "POST",
                data: {judgename: judgename, stage: stage, from_date: from_date},
                cache: false,
                success: function (r)
                {
                    $("#getCLJDatadisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $(document).on("input", "#ia", function () {
            var ia_text = document.getElementById('ia').value;
            $.ajax
            ({
                url: 'php/get_ia.php',
                type: "POST",
                data: {ia_text: ia_text},
                cache: false,
                success: function (r)
                {
                    $("#ia_result").html(r);
                },
                error: function () {
                    alert('ERROR');
                }
            });
        });//function getia end

        $(document).on("input", "#cat", function () {
            var cat_search = document.getElementById('cat').value;
            $.ajax
            ({
                url: 'php/search_cat.php',
                type: "POST",
                data: {cat_search: cat_search},
                cache: false,
                success: function (r)
                {
                    $("#cat_result").html(r);
                },
                error: function () {
                    alert('ERROR');
                }
            });
        });//function getcat end

        $(document).on("input", "#default", function () {
            var def_search = document.getElementById('default').value;
            $.ajax
            ({
                url: 'php/search_defaults.php',
                type: "POST",
                data: {def_search: def_search},
                cache: false,
                success: function (r)
                {
                    $("#def_result").html(r);
                },
                error: function () {
                    alert('ERROR');
                }
            });
        });//function getDefaults end

        $("#getCaseDiary1").click(function ()
        {
            var d_no = $('#CaseDiaryNumber').val();
            var d_yr = $('#CaseDiaryYear').find(":selected").val();
            if ($.trim(d_no) == "")
            {
                alert("Please Fill Diary Number");
                $("#CaseDiaryNumber").focus();
                return false;
            }
            $("#DNdisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/case_status/case_status_process.php',
                type: "POST",
                data: {d_no: d_no, d_yr: d_yr},
                cache: false,
                success: function (r)
                {
                    $("#DNdisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCLTLData").prop('disabled', true);
        $(document).on("change", "#subject", function () {
            $("#getCLTLData").prop('disabled', false);
            var subject_count = 0;
            var subject_val = "";
            for (var i = 0; i < document.getElementById('subject').options.length; i++)
            {
                if (document.getElementById('subject').options[i].selected == 1)
                {
                    subject_count++;
                    subject_val = subject_val + document.getElementById('subject').options[i].value + ",";
                }
            }

            if (subject_count > 1 && document.getElementById('subject').value == 'all')
            {
                alert('ERROR : Either select "ALL CATEGORY" Or other remaining option from SUBJECT BOX');
                document.getElementById('subject').focus();
                return false;
            }

            $.ajax
            ({
                url: 'php/getcat_multiple.php',
                type: "POST",
                data: {subject: subject_val, subject_length: subject_count},
                cache: false,
                success: function (r)
                {
                    var t_r = r.split('|:|');
                    $("#catdiv").html(t_r[0]);
                    if (t_r[1] > 0) {
                        $("#ssid").show();
                    } else {
                        $("#ssid").hide();
                    }
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });//function getcat end

        $(document).on("change", "#cat", function () {
            var subject_count = 0;
            var subject_val = "";
            var cat_count = 0;
            var cat_val = "";

            for (var i = 0; i < document.getElementById('subject').options.length; i++)
            {
                if (document.getElementById('subject').options[i].selected == 1)
                {
                    subject_count++;
                    subject_val = subject_val + document.getElementById('subject').options[i].value + ",";
                }
            }


            for (var i = 0; i < document.getElementById('cat').options.length; i++)
            {
                if (document.getElementById('cat').options[i].selected == 1)
                {
                    cat_count++;
                    cat_val = cat_val + document.getElementById('cat').options[i].value + ",";
                }
            }

            if (cat_count > 1 && document.getElementById('cat').value == 'all')
            {
                alert('ERROR : Either select "ALL CATEGORY" Or other remaining option from SUB CATEGORY BOX');
                document.getElementById('cat').focus();
                return false;
            }

            $.ajax
            ({
                url: 'php/getsubcat_mul.php',
                type: "POST",
                data: {subject: subject_val, subject_length: subject_count, cat: cat_val, cat_length: cat_count},
                cache: false,
                success: function (r)
                {

                    var t_r = r.split('|:|');
                    $("#subcatdiv").html(t_r[0]);
                    if (t_r[1] > 0) {
                        $("#ssid1").show();
                    } else {
                        $("#ssid1").hide();
                    }
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });
//function getsubcat end

        $(document).on("change", "#subcat", function () {

            var subject_count = 0;
            var subject_val = "";
            var cat_count = 0;
            var cat_val = "";
            var subcat_count = 0;
            var subcat_val = "";

            for (var i = 0; i < document.getElementById('subject').options.length; i++)
            {
                if (document.getElementById('subject').options[i].selected == 1)
                {
                    subject_count++;
                    subject_val = subject_val + document.getElementById('subject').options[i].value + ",";
                }
            }

            for (var i = 0; i < document.getElementById('cat').options.length; i++)
            {
                if (document.getElementById('cat').options[i].selected == 1)
                {
                    cat_count++;
                    cat_val = cat_val + document.getElementById('cat').options[i].value + ",";
                }
            }

            for (var i = 0; i < document.getElementById('subcat').options.length; i++)
            {
                if (document.getElementById('subcat').options[i].selected == 1)
                {
                    subcat_count++;
                    subcat_val = subcat_val + document.getElementById('subcat').options[i].value + ",";
                }
            }

            if (cat_count > 1 && document.getElementById('cat').value == 'all')
            {
                alert('ERROR : Either select "ALL CATEGORY" Or other remaining option from SUB CATEGORY BOX');
                document.getElementById('cat').focus();
                return false;
            }

            $.ajax
            ({
                url: 'php/getsubcat2_mul.php',
                type: "POST",
                data: {subcat: subcat_val, subcat_length: subcat_count},
                cache: false,
                success: function (r)
                {

                    var t_r = r.split('|:|');
                    $("#subcat2div").html(t_r[0]);
                    if (t_r[1] > 0) {
                        $("#ssid2").show();
                    } else {
                        $("#ssid2").hide();
                    }

                },
                error: function () {
                    alert('ERRO');
                }
            });

        });//function getsubcat end


        $("#getTextFree").click(function ()
        {
            var Free_Text = $('#Free_Text').val();
            var FT_from_date = $('#FT_from_date').val();
            var FT_to_date = $('#FT_to_date').val();
            var judgmentType=$('#orderOrJudgment').val();
            $("#FTdisplay_data").html("");
            if ($.trim(Free_Text) == "")
            {
                alert("Please Fill Text");
                $("#Free_Text").focus();
                return false;
            }
            $("#FTdisplay").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/get_Text_Free.php',
                type: "POST",
                data: {Free_Text: Free_Text, FT_from_date: FT_from_date, FT_to_date: FT_to_date,Judgment_Type:judgmentType},
                cache: false,
                success: function (r)
                {
                    $("#FTdisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#case_getTextFree").click(function ()
        {
            var Free_Text = $('#case_Free_Text').val();
            var FT_from_date = $('#case_FT_from_date').val();
            var FT_to_date = $('#case_FT_to_date').val();

            var capcthaAns = $('#ansCaptcha').val();

            if ($.trim(capcthaAns) == "")
            {
                alert("Please Fill Captcha");
                $("#ansCaptcha").focus();
                return false;
            }

            if ($.trim(Free_Text) == "")
            {
                alert("Please Fill Text");
                $("#case_Free_Text").focus();
                return false;
            }
            $("#case_FTdisplay").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/get_Text_Free.php',
                type: "POST",
                data: {Free_Text: Free_Text, FT_from_date: FT_from_date, FT_to_date: FT_to_date,ansCaptcha:capcthaAns},
                cache: false,
                success: function (r)
                {
                    if(r=='0')
                    {
                        alert('Invalid Captcha');
                        $("#case_FTdisplay").html(' ');
                        // location.reload();
                    }
                    else {
                        $("#case_FTdisplay").html(r);

                    }
                    $('#ansCaptcha').val('');
                    document.getElementById('captcha').src='php/captcha.php?'+Math.random();


                    //$("#case_FTdisplay").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });


        $(document).on("change", "#get_free_text_data", function () {
            var judgmentType=$('#orderOrJudgment').val();

            var diary_no = $('#get_free_text_data').find(":selected").val();
            //alert(diary_no);
            var t_pno = diary_no.split(':');
//            if($.trim(diary_no) == "")
//            {
//            alert("Please Select Record");
//            $("#case_Free_Text").focus();
//            return false;
//            }
            $("#FTdisplay_data").html("<center><img src=\"../images/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'addon_pages/get_Text_Free_data.php',
                type: "POST",
                data: {diary_no: t_pno[0], tbl: t_pno[1],order_date: t_pno[2],judgment_type:judgmentType},
                cache: false,
                success: function (r)
                {
                        $("#FTdisplayPDF").html('<a href="https://www.sci.gov.in/'+t_pno[3]+'" target="_blank">PDF</a>');

                    $("#FTdisplay_data").html(r);
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getCLCNData").click(function ()
        {
            var courtno = $('#courtno').find(":selected").val();
//            var courtcl = $('#courtcl').find(":selected").val();
            var from_date_cn = $('#from_date_cn').val();
            var stagecn = $("input[name=stagecn]:checked").val();
            var msb = $("input[name=msb]:checked").val();

//            if($.trim(courtno) == "")
//            {
//            alert("Please Select Hon'ble Court");
//            $("#courtno").focus();
//            return false;
//            }
            $("#getCLCNDatadisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");
            $('#getCLCNData').hide();
            $.ajax
            ({
                url: 'php/getCLCNData.php',
                type: "POST",
                data: {courtno: courtno, stagecn: stagecn, msb: msb, from_date_cn: from_date_cn},
                cache: false,
                success: function (r)
                {
                    $('#getCLCNData').show();
                    $("#getCLCNDatadisplay").html(r);
                    var $html = $("#getCLCNDatadisplay");
                    $html.find('a.colorbox-inline').colorbox({width: '960px', height: '90%', iframe: true});

                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $(document).on('change', '#ddl_st_agncy,#ddl_court', function() {
            get_benches('0');
        });


        $(document).on('change','#ddl_bench',function(){
//           var org_id=$(this).attr('id');
            var idd=$(this).attr('id');

            var ddl_ref_court=$('#ddl_court').val();
//     alert(ddl_ref_court);

            if(ddl_ref_court=='1' || ddl_ref_court=='3')
            {
                if(ddl_ref_court=='1')
                    var  chk_status='H';
                else
                    var  chk_status='L';
                get_lc_casetype(chk_status,idd);
            }
            else  if(ddl_ref_court=='4' || ddl_ref_court=='5')
            {
                get_lc_casetype(chk_status,idd);
            }
        });

        $(document).on('change', '#ddl_court', function() {

            var idd= $(this).val();
            var ddl_bench=$('#ddl_bench').attr('id');

            if(idd=='4')
            {
                $('#ddl_st_agncy').val('490506');

                get_benches('1');
                var ddl_ref_court=$('#ddl_court').val();
//     alert(ddl_ref_court);

                if(ddl_ref_court=='1' || ddl_ref_court=='3')
                {
                    if(ddl_ref_court=='1')
                        var  chk_status='H';
                    else
                        var  chk_status='L';
                    get_lc_casetype(chk_status,ddl_bench);
                }
                else  if(ddl_ref_court=='4' || ddl_ref_court=='5')
                {
                    get_lc_casetype(chk_status,ddl_bench);
                }

            }
        });

        $("#tab_amt").hide();
        $("#get_case_detail").click(function ()
        {
            var DNNumber = $('#diary_no').val();
            var DNYear = $('#diary_year').val();
            if ($.trim(DNNumber) == "")
            {
                alert("Please Fill Diary Number");
                $("#diary_no").focus();
                return false;
            }
            $("#JDN").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/get_causeTitle.php',
                type: "POST",
                data: {DNNumber: DNNumber, DNYear: DNYear},
                cache: false,
                success: function (r)
                {
                    if(r!=0){
                        $("#tab_amt").show();
                        $("#JDN").html(r);
                    }
                    else{
                        $("#tab_amt").hide();
                        alert("Invalid Diary Number");
                        $("#JDN").html("");
                    }
                },
                error: function () {
                    alert('ERRO');
                }
            });
        });

        $("#getAdvanceListHTML").click(function ()
        {
            var ldate = $("#PCL_from_date1").val();
            var listType = $("#mc1").val();

            var folderLocation='A';

            $("#getAdvanceListHTMLDisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/displayAdvanceAndEliminationAdvanceListHTML.php',
                type: "POST",
                data: {list_dt: ldate,list_type:listType,folderLocation:folderLocation},
                cache: false,
                success: function (r)
                {
                    $("#getAdvanceListHTMLDisplay").html(r);
                },
                error: function () {
                    alert('ERROR');
                }
            });
        });

        $("#getFinalEliminationListHTML").click(function ()
        {
            var ldate = $("#FE_from_date1").val();
            var listType = $("#FE_mc1").val();

            var folderLocation='FE';

            $("#getFinalEliminationListHTMLDisplay").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/displayAdvanceAndEliminationAdvanceListHTML.php',
                type: "POST",
                data: {list_dt: ldate,list_type:listType,folderLocation:folderLocation},
                cache: false,
                success: function (r)
                {
                    $("#getFinalEliminationListHTMLDisplay").html(r);
                },
                error: function () {
                    alert('ERROR');
                }
            });
        });


        $("#getELData").click(function ()
        {

            var ldates = $("#ldates").val();
            //alert(ldates);
            var board_type = $("#board_type").val();
            //var sec_id = $("#sec_id").val();
            var sec_id = 0;


            var checkDate='02-11-2018';
            var checkDate1 = checkDate.split("-");
            var arrDate = ldates.split("-");
            checkDate = new Date(checkDate1[2], checkDate1[1], checkDate1[0]);
            useDate = new Date(arrDate[2], arrDate[1], arrDate[0]);
            var folderLocation='EA';
            if (useDate >= checkDate) {
                //alert('displayHTML');
                $.ajax
                ({
                    url: 'php/displayAdvanceAndEliminationAdvanceListHTML.php',
                    type: "POST",
                    data: {list_dt: ldates,list_type:board_type,folderLocation:folderLocation},
                    cache: false,
                    success: function (r)
                    {
                        $("#dv_res1").html(r);
                    },
                    error: function () {
                        alert('ERROR');
                    }
                });
            } else  {

                $.ajax({
                    url: 'php/elemination_transfer_get.php',
                    cache: false,
                    async: true,
                    data: {list_dt: ldates, board_type: board_type, sec_id: sec_id},
                    beforeSend: function () {
                        $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                    },
                    type: 'POST',
                    success: function (data, status) {
                        $('#dv_res1').html(data);
                    },
                    error: function (xhr) {
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                    }
                });
            }

        });

        $("#getVacationAdvanceList").click(function ()
        {
            $("#vacationAdvanceList").html("<center><img src=\"php/img/load.gif\" alt=\"Loading...\" title=\"Loading...\" /></center>");

            $.ajax
            ({
                url: 'php/getVacationAdvanceListData.php',
                type: "POST",
                data: {},
                cache: false,
                success: function (r)
                {
                    $("#vacationAdvanceList").html(r);
                },
                error: function () {
                    alert('ERROR');
                }
            });
        });

        $(".judgenameSCALL").select2({

        });


    });
})(jQuery);

