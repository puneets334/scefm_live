<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php csrf_hash() ?>" />
    <title>SCeFM</title>
    <link rel="icon" href="<?= base_url() . 'assets/newDesign/' ?>images/logo.png" sizes="32x32">
	<link rel="icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
    @stack('style')
    <style>
		#loader-wrapper {
			display: flex;
			justify-content: center;
			align-items: center;
			z-index: 1000; /* Ensure it's on top of other content */
		}
	</style>
</head>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div id="loader-text">Loading ...</div>
    </div>
    <div class="wrapper">
        <!--header section-->
        @include('layout.header')
        <!--header sec   end-->
        <div class="content">
            <!-- Side Panel Starts -->
            @if(isset($_SESSION['login']))
                @include('layout.sidebar')
            @endif            
            <!-- Side Panel Ends -->
            <!-- Main Panel Starts -->
            <div class="mainPanel ">
                <div class="panelInner">
                    <div class="middleContent">
                        @yield('content')
                    </div>
                </div>
            </div>
            <!-- Main Panel Ends -->
        </div>
        <footer class="footer-sec">Content Owned by Supreme Court of India</footer>
    </div>
    <?php // pr($_SESSION['login']['ref_m_usertype_id']); ?>
    @if(isset($_SESSION['login']) && in_array($_SESSION['login']['ref_m_usertype_id'],array(USER_ADVOCATE, USER_IN_PERSON, AMICUS_CURIAE_USER)))
        
        <div class="add-new-area" tabindex="0" role="button">
            <a href="javascript:void(0)" class="add-btn"><span class="mdi mdi-plus-circle-outline"></span> NEW</a>
            <div class="add-new-options">
                <ul>
                    <?php if (getSessionData('login')['ref_m_usertype_id'] != AMICUS_CURIAE_USER) { ?>
                        <?php if(isset($_SESSION['login']['id']) && in_array($_SESSION['login']['id'], AIASSISTED_USER_IN_LIST)) { ?>
                            <li>
                                <a style="color: #fff; width: 100%; display: inline-block; padding: 9px 12px; font-size: 14px;" href="{{base_url('casewithAI')}}" class="add-link">AI Assisted Case Filing</a>
                            </li>
                        <?php } ?>
                        <li>
                            <a href="{{base_url('case/crud')}}" class="add-lnk">Case</a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="{{base_url('case/interim_application/crud')}}" class="add-lnk">IA</a>
                    </li>
                    <li>
                        <a href="{{base_url('case/document/crud')}}" class="add-lnk">Misc. Docs</a>
                    </li>
                    <?php if (getSessionData('login')['ref_m_usertype_id'] != AMICUS_CURIAE_USER) { ?>
                        <li>
                            <a href="{{base_url('case/caveat/crud')}}" class="add-lnk">Caveat</a>
                        </li>
                        <!-- <li>
                            <a href="{{base_url('case/refile_old_efiling_cases/crud')}}" class="add-lnk">Refile old e-filing cases</a>
                        </li> -->
                    <?php } ?>
                </ul>
            </div>
        </div>
    @endif
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
    <script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>  
    <script src="<?= base_url() ?>assets/js/sha256.js"></script>
    <script src="<?= base_url() ?>assets/newAdmin/js/angular.min.js"></script>
    <!-- scutum JS -->
    <script type="text/javascript" src="{{base_url('assets/responsive_variant/templates/uikit_scutum_2/assets/js/vendor.min.js')}}"></script>
    <script type="text/javascript" src="{{base_url('assets/responsive_variant/templates/uikit_scutum_2/assets/js/vendor/loadjs.js')}}"></script>
    <script type="text/javascript" src="{{base_url('/assets/responsive_variant/templates/uikit_scutum_2/assets/js/scutum_common.js')}}"></script>
    @stack('script')
    <script>
        $(document).ready(function() {
            $('#datatable-responsive').DataTable();
        });
    </script>
    <script src="<?= base_url() ?>assets/newAdmin/js/moment.min.js"></script>
    <script src="<?= base_url() ?>assets/newAdmin/js/fullcalendar.min.js"></script>
    <script src="<?= base_url() ?>assets/newAdmin/js/jquery.validate.min.js"></script>
    <script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
    <script>
        // $(document).ready(function() {
        //     var calendar = $('#calendar').fullCalendar({
        //         editable: true,
        //         header: {
        //             // left:'prev,next today',
        //             // center:'title',
        //             //right:'month,agendaWeek,agendaDay'
        //         },
        //         events: "<?php // echo base_url(); ?>fullcalendar/load",
        //         selectable: true,
        //         selectHelper: true,
        //         select: function(start, end, allDay) {
        //             var title = prompt("Enter Event Title");
        //             if (title) {
        //                 var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
        //                 var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
        //                 $.ajax({
        //                     url: "<?php // echo base_url(); ?>fullcalendar/insert",
        //                     type: "POST",
        //                     data: {
        //                         title: title,
        //                         start: start,
        //                         end: end
        //                     },
        //                     success: function() {
        //                         calendar.fullCalendar('refetchEvents');
        //                         alert("Added Successfully");
        //                     }
        //                 })
        //             }
        //         },
        //         editable: true,
        //         eventResize: function(event) {
        //             var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
        //             var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
        //             var title = event.title;
        //             var id = event.id;
        //             $.ajax({
        //                 url: "<?php // echo base_url(); ?>fullcalendar/update",
        //                 type: "POST",
        //                 data: {
        //                     title: title,
        //                     start: start,
        //                     end: end,
        //                     id: id
        //                 },
        //                 success: function() {
        //                     calendar.fullCalendar('refetchEvents');
        //                     alert("Event Update");
        //                 }
        //             })
        //         },
        //         eventDrop: function(event) {
        //             var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
        //             //alert(start);
        //             var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
        //             //alert(end);
        //             var title = event.title;
        //             var id = event.id;
        //             $.ajax({
        //                 url: "<?php // echo base_url(); ?>fullcalendar/update",
        //                 type: "POST",
        //                 data: {
        //                     title: title,
        //                     start: start,
        //                     end: end,
        //                     id: id
        //                 },
        //                 success: function() {
        //                     calendar.fullCalendar('refetchEvents');
        //                     alert("Event Updated");
        //                 }
        //             })
        //         },
        //         eventClick: function(event) {
        //             if (confirm("Are you sure you want to remove it?")) {
        //                 var id = event.id;
        //                 $.ajax({
        //                     url: "<?php // echo base_url(); ?>fullcalendar/delete",
        //                     type: "POST",
        //                     data: {
        //                         id: id
        //                     },
        //                     success: function() {
        //                         calendar.fullCalendar('refetchEvents');
        //                         alert('Event Removed');
        //                     }
        //                 })
        //             }
        //         }
        //     });
        // });
        function isNumber(value) {
            return typeof value === 'number';
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

        $.ajaxSetup({
            headers: {
                'CSRF_TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
	</script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    let inactivityTime = function () {
        let time;
        // Reset timer on user activity
        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
        document.onclick = resetTimer;
        document.onscroll = resetTimer;

        function logout() {
            alert('You have been logged out due to inactivity.');
            window.location.href = '<?php echo base_url('logout'); ?>';
        }

        function resetTimer() {
            clearTimeout(time);
            // Set timeout to 30 minutes (1800000 ms)
            time = setTimeout(logout, 900000);
        }
    };

    inactivityTime();
});
</script>
<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    function checkSession() {
        fetch('<?php echo base_url("is_user_status"); ?>', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'expired') {
                alert('Another login detected. You will be redirected to the login page.');
           		window.location.href = '<?php echo base_url('logout'); ?>';
            }
        })
        .catch(error => {
            // console.error('Error checking session:', error);
        });
    }

    // Check session every 60 seconds
    setInterval(checkSession, 60000);
});
</script> -->
</body>
</html>