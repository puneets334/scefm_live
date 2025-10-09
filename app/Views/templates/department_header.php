<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#343a40">
    <title>Add/Approve Advocate(s) | SC-eFM @ Supreme Court of India</title>
    <base ref="http://10.40.186.14:91/">
    <link rel="stylesheet" href="http://10.40.186.14:91/assets/css/case_status/bootstrap.min.css">
    <link rel="stylesheet" href="http://10.40.186.14:91/assets/responsive_variant/templates/uikit_scutum_2/assets/css/main.min.css" />
    <link type="text/css" rel="stylesheet" href="http://10.40.186.14:91/assets/responsive_variant/frameworks/uikit_3-4-1/css/uikit.min.css" />
    <link rel="stylesheet" href="http://10.40.186.14:91/assets/responsive_variant/templates/uikit_scutum_2/assets/css/fonts/mdi_fonts.css" />
    <link rel="stylesheet" href="http://10.40.186.14:91/assets/responsive_variant/templates/uikit_scutum_2/assets/css/materialdesignicons.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,100;0,300;0,400;0,500;0,600;0,700;1,100;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
    <script type="text/javascript" src="http://10.40.186.14:91/assets/responsive_variant/js/jquery/jquery351.min.js"></script>
    <script type="text/javascript" src="http://10.40.186.14:91/assets/responsive_variant/js/angularjs/angular.min.js"></script>
    <style type="text/css">
        * {
            font-family: 'Barlow', sans-serif !important;
            /*font-family: 'Playfair Display', serif;*/
        }

        .text-saffron {
            color: #d94d21;
        }

        .text-white {
            color: #ffffff !important;
        }

        .text-pitch-black {
            color: #000000 !important;
        }

        .bg-white {
            background-color: #ffffff;
        }

        .bg-saffron {
            background-color: #d94d21;
        }

        .bg-black {
            background-color: #222222;
        }

        .bg-pitch-black {
            background-color: #000000 !important;
        }

        .bg-dark {
            background-color: #343a40 !important;
        }

        .bg-danger {
            background-color: #f0506e;
        }

        .bg-success {
            background-color: #32D296;
        }

        .bg-warning {
            background-color: #FAA05A;
        }

        .bg-danger-combo {
            background-color: #f0506e;
            color: #ffffff;
        }

        .bg-success-combo {
            background-color: #32D296;
            color: #ffffff;
        }

        .border-white {
            border-color: #ffffff;
        }

        .border-black {
            border-color: #222222;
        }

        .border {
            border: 1px solid;
        }

        .border-primary {
            border-color: #1e87f0;
        }

        .border-warning {
            border-color: #FAA05A;
        }

        .border-danger {
            border-color: #f0506e;
        }

        .border-dark {
            border-color: #343a40;
        }

        .border-pitch-black {
            border-color: #000000;
        }

        .border-muted {
            border-color: #999999;
        }

        .bg-saffron-light {
            background-color: #f7dbd2;
            /*#fbede8;*/
        }

        .bg-gray {
            background-color: #C0C0C0;
        }

        .form-blank {
            background-color: #ffffff !important;
            border-color: #ffffff !important;
        }

        #loading-overlay {
            position: fixed;
            /* Sit on top of the page content */
            display: block;
            /* Hidden by default */
            width: 100%;
            /* Full width (cover the whole page) */
            height: 100%;
            /* Full height (cover the whole page) */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1051;
            /* Specify a stack order in case you're using a different order for other elements */
        }

        .full-overlay {
            position: fixed;
            /* Sit on top of the page content */
            display: block;
            /* Hidden by default */
            width: 100%;
            /* Full width (cover the whole page) */
            height: 100%;
            /* Full height (cover the whole page) */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1051;
            /* Specify a stack order in case you're using a different order for other elements */
        }

        /***start-from bootstrap***/
        @media print {
            .d-print-none {
                display: none !important;
            }

            .d-print-inline {
                display: inline !important;
            }

            .d-print-inline-block {
                display: inline-block !important;
            }

            .d-print-block {
                display: block !important;
            }

            .d-print-table {
                display: table !important;
            }

            .d-print-table-row {
                display: table-row !important;
            }

            .d-print-table-cell {
                display: table-cell !important;
            }

            .d-print-flex {
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important;
            }

            .d-print-inline-flex {
                display: -webkit-inline-box !important;
                display: -ms-inline-flexbox !important;
                display: inline-flex !important;
            }
        }

        /***end-from bootstrap***/

        /***start-modifications in scutum***/
        .uk-form-icon {
            transform: none;
            -webkit-transform: none;
        }

        .uk-notification .uk-notification-message {
            font-size: 1.30rem;
        }

        /***end-modifications in scutum***/
    </style>
</head>

<body ngapp="efilingNearApp" ngcontroller="efilingNearController"><!--todo:find a solution to enable this....coz earlier this gave an issue wherein secure gate had initialized its own app & controller.-->
    <header class="efiling_search">
        <nav id="main-nav-dark" class="bgdark md-bg-grey-200" style="padding-top:0.5rem;padding-bottom:0.5rem;z-index:970 !important;height::51px;" uksticky="show-on-up: true; animation: uk-animation-slide-top; seltarget: [uk-navbar]; clsactive: uk-navbar-sticky; target-offset:true;" uk-height-match uk-navbar>
            <div class="uk-navbar-left d-print-none">

            </div>

            <div class="uk-width-expand uk-flex-center uk-flex-left@s uk-flex bgdark">
                <a href="http://10.40.186.14:91/" class="uk-icon-button" uk-icon="home"></a>

                <a class="uk-navbar-item" href="http://10.40.186.14:91/" style="min-height: unset;padding-left:0.4rem;margin-top:-0.1rem;text-decoration: none;">
                    <img src="http://10.40.186.14:91/assets/responsive_variant/images/flags/sci_logo.png" alt="Supreme Court Emblem" style="height:2.9rem;">
                    <span class="uk-margin-remove-vertical textwhite uk-text-muted uktext-bold uk-text-lead uk-margin-small-left" style="font-weight:500;">Supreme Court of India</span>
                </a>
            </div>

            <?php if (is_logged_in()) { ?>
                <div class="uk-navbar-right d-print-none">
                    <ul class="uk-navbar-nav">
                        <li>
                            <a href="<?= base_url() . 'department/DefaultController/engage_aor' ?>" style="min-height: unset;">Engage AOR</a>
                        </li>
                        <li>
                            <a href="#" data-uk-icon="icon:user; ratio:1.2;" style="min-height: unset;"></a>
                            <div class="uk-navbar-dropdown uk-navbar-dropdown-bottom-left uk-padding-small" style="z-index:971 !important;">
                                <ul class="uk-nav uk-navbar-dropdown-nav">
                                    <li class="uk-nav-header uk-text-medium uk-text-primary uk-text-capitalize">
                                        <b><?php echo trim(strtolower($this->session->userdata['login']['first_name'] . ' ' . $this->session->userdata['login']['last_name'])) ?>'s</b> ACCOUNT
                                    </li>
                                    <li><a href="<?= base_url('my/profile') ?>"><span data-uk-icon="icon: settings"></span> Profile</a></li>
                                    <!--<li><a href="{{base_url('clerk/crud')}}"><span data-uk-icon="icon: users"></span> Add Clerk(s)</a></li>-->
                                    <li class="uk-nav-divider"></li>
                                    <li><a href="<?= base_url('login/logout') ?>"><span data-uk-icon="icon: sign-out"></span> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <!--<a href="#main-offcanvas" class="uk-navbar-toggle uk-hidden@l" uk-navbar-toggle-icon></a>-->
                </div>
            <?php } ?>
        </nav>

    </header>

    <main id="content-container" role="main" class="uk-container uk-container-expand">

        <div class="ukgrid-collapse uk-position-relative uk-margin-small-top" style="z-index:2;" uk-grid>
            <div id="content" class="uk-width-expand">
                <div id="heading-container" class="bgdark textwhite uk-margin-small-bottom">
                    <div class="uk-flex uk-flex-between@m uk-flex-right" ukgrid>
                        <h2 class="textwhite uk-margin-remove uk-visible@m ukwidth-expand"><a href="http://10.40.186.14:91/register/arguingCounsel" class="uk-button-text uk-text-capitalize">Add/Approve Advocate(s)</a></h2>
                    </div>

                </div>
                <!--<hr>-->



            </div>




        </div>
        </div>
    </main>


    <footer class="uk-margin-medium-top d-print-none">

    </footer>


    <script src="http://10.40.186.14:91/assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit.min.js"></script>
    <script src="http://10.40.186.14:91/assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit-icons.min.js"></script>

    <!-- scutum JS -->
    <script type="text/javascript" src="http://10.40.186.14:91/assets/responsive_variant/templates/uikit_scutum_2/assets/js/vendor.min.js"></script>
    <script type="text/javascript" src="http://10.40.186.14:91/assets/responsive_variant/templates/uikit_scutum_2/assets/js/vendor/loadjs.js"></script>
    <script type="text/javascript" src="http://10.40.186.14:91/assets/responsive_variant/templates/uikit_scutum_2/assets/js/scutum_common.js"></script>

    <script type="text/javascript" src="http://10.40.186.14:91/assets/js/case_status/common.js"></script>

    <script type="text/javascript" src="http://10.40.186.14:91/assets/js/case_status/bootstrap.min.js"></script>

    <script type="text/javascript" src="http://10.40.186.14:91/assets/js/aes.js"></script>
    <script type="text/javascript" src="http://10.40.186.14:91/assets/js/aes-json-format.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#loading-overlay').hide();
            /*****start-code to adjust height of iframes*****/
            try {
                if ($.browser.safari || $.browser.opera) {
                    $('.internal-content-iframe').on('load', function() {
                        setTimeout(function() {
                            $('.internal-content-iframe')[0].style.height = ($('.internal-content-iframe')[0].contentWindow.document.body.offsetHeight + 100) + 'px';
                        }, 100);
                    });

                    var iSource = $('.internal-content-iframe')[0].src;
                    $('.internal-content-iframe')[0].src = '';
                    $('.internal-content-iframe')[0].src = iSource;
                } else {
                    $('.internal-content-iframe').on('load', function() {
                        setTimeout(function() {
                            $('.internal-content-iframe')[0].style.height = ($('.internal-content-iframe')[0].contentWindow.document.body.offsetHeight + 100) + 'px';
                        }, 500);
                    });
                }
                setInterval(function() {
                    try {
                        $('.internal-content-iframe')[0].style.height = $('.internal-content-iframe')[0].contentWindow.document.body.offsetHeight + 'px';
                    } catch (e) {}
                }, 3000);
            } catch (e) {}
            /*****start-code to adjust height of iframes*****/
        });

        /*****start-$.browser feature extract, which has been removed in new jQuery version*****/
        var matched, browser;

        jQuery.uaMatch = function(ua) {
            ua = ua.toLowerCase();

            var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
                /(webkit)[ \/]([\w.]+)/.exec(ua) ||
                /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
                /(msie) ([\w.]+)/.exec(ua) ||
                ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) || [];

            return {
                browser: match[1] || "",
                version: match[2] || "0"
            };
        };

        matched = jQuery.uaMatch(navigator.userAgent);
        browser = {};

        if (matched.browser) {
            browser[matched.browser] = true;
            browser.version = matched.version;
        }

        // Chrome is Webkit, but Webkit is also Safari.
        if (browser.chrome) {
            browser.webkit = true;
        } else if (browser.webkit) {
            browser.safari = true;
        }

        jQuery.browser = browser;
        /*****end-$.browser feature extract, which has been removed in new jQuery version*****/
    </script>



</body>

</html>