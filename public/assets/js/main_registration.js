$(document).ready(function (e) {
    $.validator.addMethod("valid_email", function (value, element) {
        return this.optional(element) || /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/.test($.trim(value));
    }, "Enter valid email id");
    $.validator.addMethod("valid_address", function (value, element) {
        return this.optional(element) || /^([0-9a-zA-Z\s\r\n, /@\\- ._])+$/.test($.trim(value));
    }, "Only alphnumeric and special characters  /@\\, - ._ are allowed");

    $('.advocate_reg_searchss').validate({ 
        ignore: ":hidden",
        rules: {
            "user_mobile1": {
                digits: true,
                minlength: 10,
                maxlength: 10,
                required: function (element) {
                    if ($("#reg_bar_code_1").val().length > 0) {
                        return false;
                    } else if ($("#user_email1").val().length > 0) {
                        return false;
                    } else {
                        return true;
                    }
                },
                minlength: 10,
                maxlength: 10,
                digits: true
            }, "user_email1": {
                required: function (element) {
                    if ($("#user_mobile1").val().length > 0) {
                        return false;
                    } else if ($("#reg_bar_code_1").val().length > 0) {
                        return false;
                    } else {
                        return true;
                    }
                },
                email: true,
                valid_email: true,
            },
            "reg_bar_codes": {
                digits: true,
                minlength: 2,
                maxlength: 6,
                required: function (element) {
                    if ($("#user_email1").val().length > 0) {
                        return false;
                    } else if ($("#user_mobile1").val().length > 0) {
                        return false;
                    } else {
                        return true;
                    }
                },
            }, "adv_name": {
                minlength: 3,
                maxlength: 100,
                required: function (element) {
                    if ($("#reg_bar_code_1").val().length > 0) {
                        return true;
                    } else if ($("#user_mobile1").val().length > 0) {
                        return false;
                    } else if ($("#user_email1").val().length > 0) {
                        return false;
                    }
                },
            }, captcha: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6
            }
        },
        messages: {
            "user_mobile1": {
                required: 'Enter valid mobile number.'
            }, "user_email1": {
                required: 'Enter valid email ID.'
            }, "reg_bar_codes": {
                required: '<p style="position: absolute;margin: 54px; margin-left:-2px;">Enter Bar code e.g (DL-05-2004).</p>'
            }, "adv_name": {
                required: 'Requerd - Enter valid Advocate Name.'
            }, captcha: {
                required: 'Enter captcha text.'
            }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
            $("panel-info").addClass("red");
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'error-tip',

        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $('#otp_verify').validate({
        ignore: ":hidden",
        rules: {
            "mobile_verify_code[]": {
                required: true,
                digits: true,
                maxlength: 6
            }, "email_verify[]": {
                required: true,
                digits: true,
                minlength: 6
            }, captcha: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6
            }
        },
        messages: {
            "mobile_verify_code[]": {
                required: 'Enter Mobile Verification Code'
            }, "email_verify[]": {
                required: 'Enter Email Verification Code'
            }, captcha: {
                required: 'Enter Captcha Code'
            }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'error-tip',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

//    $('#adv_establishment').validate({
//        ignore: ":hidden",
//        rules: {
//            a_state_id: {
//                required: true
//            }, a_district: {
//                required: true
//            }, mySelect1: {
//                required: true
//            }, mySelect2: {
//                required: true
//            }
//        },
//        messages: {
//            a_state_id: {
//                required: 'Select State'
//            }, a_district: {
//                required: 'Select District'
//            }, mySelect1: {
//                required: 'Select Establishment'
//            }, mySelect2: {
//                required: 'Select Establishment'
//            }
//        },
//        highlight: function (element) {
//            $(element).closest('.form-group').addClass('has-error');
//        },
//        unhighlight: function (element) {
//            $(element).closest('.form-group').removeClass('has-error');
//        },
//        errorClass: 'error-tip',
//        errorElement: 'div',
//
//        errorPlacement: function (error, element) {
//            if (element.parent('.div').length) {
//                error.insertAfter(element.parent());
//            } else {
//                error.insertAfter(element);
//            }
//        }
//    });


    $('#advocate_reg_info').validate({
        ignore: ":hidden",
        rules: {
            name: {
                minlength: 3,
                required: true
            }, date_of_birth: {
                required: true
            }, gender: {
                required: true
            }, state_id: {
                required: true
            }, district_list: {
                required: true
            }, address: {
                valid_address: true,
                required: true,
                minlength: 4
            }, pincode: {
                required: true,
                minlength: 6,
                maxlength: 6,
                digits: true
            }
        },
        messages: {
            "legacy_advocate_id[]": {
                //required: 'Select <script type="text/javascript"> $(document).ready(function () { $("#myModal").modal("show"); }); </script>'
            }, name: {
                required: 'Enter Name'
            }, date_of_birth: {
                required: 'Enter Date of Birth'
            }, gender: {
                required: '<span style="margin-top: 23px; position: absolute; width: 200px;">Select Male Or Female Or Other</span>'
            }, state_id: {
                required: '<div>Select State</div>'
            }, district_list: {
                required: 'Select District'
            }, address: {
                required: 'Enter Office Address'
            }, pincode: {
                required: 'Enter Pin Code'
            }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'error-tip',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $('#new_adv_register').validate({
        ignore: ":hidden",
        rules: {
            adv_type: {
                //required: true
            }, dep_add: {
                //required: true
            }, captcha: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6
            }
        },
        messages: {
            adv_type: {
                //required: 'Please Select One'
            }, dep_add: {
                //required: 'Please Select One'
            }, captcha: {
                required: 'Enter Captcha Code'
            }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },

        errorClass: 'error-tip',
        errorElement: 'div',

        errorPlacement: function (error, element) {
            if (element.parent('.div').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $('#advocate_reg_info_user').validate({
        ignore: ":hidden",
        rules: {
            name: {
                minlength: 3,
                required: true,
                valid_address: true,
                maxlength: 50
            }, date_of_birth: {
                required: true
            },
            gender: {
                required: true
            },
            bar_reg_no: {
                required: true,
                minlength: 2,
                maxlength: 15
            }, phone_no: {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true
            }, email: {
                required: true,
                email: true,
                valid_email: true,
                minlength: 5,
                maxlength: 60
            }, a_state_id: {
                required: true
            }, a_district: {
                required: true
            },
            office_address: {
                valid_address: true,
                required: true,
                minlength: 4
            },
            office_pin_code: {
                minlength: 6,
                maxlength: 6,
                digits: true
            }, resident_address: {
                valid_address: true,
                minlength: 4
            }, resident_pin_code: {
                minlength: 6,
                maxlength: 6,
                digits: true
            }, user_id: {
                required: true
            }, court: {
                required: true
            }, high_court_id: {
                required: true
            }

        },
        messages: {
            name: {
                required: 'Enter Name'
            }, date_of_birth: {
                required: 'Enter Date of Birth'
            }, gender: {
                required: ''
            }, bar_reg_no: {
                required: 'Enter Register Bar Code'
            }, phone_no: {
                required: 'Enter Mobile Number'
            }, email: {
                required: 'Enter Email'
            }, state: {
                required: 'Select State'
            }, district: {
                required: 'Select District'
            }, establishment: {
                required: 'Select Establishment'
            }, a_state_id: {
                required: 'Select State'
            }, a_district: {
                required: 'Select District'
            }, a_nationality: {
                required: 'Enter nationnaltiy'
            }, office_address: {
                required: 'Enter Office Address'
            }, office_pin_code: {
                required: 'Enter Pin Code'
            }, resident_address: {
                required: 'Enter Residential Address'
            }, resident_pin_code: {
                required: 'Enter Pin Code'
            }, user_id: {
                required: 'Enter User Id'
            }, court: {
                required: 'Hight court'
            }, high_court_id: {
                required: 'Select Hight court '
            }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'error-tip',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $('#advocate_reg_info_user_party').validate({
        ignore: ":hidden",
        rules: {
            name: {
                minlength: 3,
                required: true,
                valid_address: true,
                maxlength: 50
            }, date_of_birth: {
                required: true
            },
            gender: {
                required: true
            },
            bar_reg_no: {
                required: true,
                minlength: 15,
                maxlength: 15
            }, phone_no: {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true
            }, email: {
                required: true,
                email: true,
                valid_email: true,
                minlength: 5,
                maxlength: 60
            }, resident_address: {
                required: true,
                valid_address: true,
                minlength: 4
            }, resident_pin_code: {
                minlength: 6,
                maxlength: 6,
                digits: true
            }, user_id: {
                required: true
            }, court: {
                required: true
            }, high_court_id: {
                required: true
            }
        },
        messages: {
            name: {
                required: 'Enter Name'
            }, date_of_birth: {
                required: 'Enter Date of Birth'
            }, gender: {
                required: ''
            }, bar_reg_no: {
                required: 'Enter Register Bar Code'
            }, phone_no: {
                required: 'Enter Mobile Number'
            }, email: {
                required: 'Enter Email'
            }, state: {
                required: 'Select State'
            }, district: {
                required: 'Select District'
            }, establishment: {
                required: 'Select Establishment'
            }, resident_address: {
                required: 'Enter Residential Address'
            }, resident_pin_code: {
                required: 'Enter Pin Code'
            }, user_id: {
                required: 'Enter User Id'
            }, court: {
                required: 'Hight court'
            }, high_court_id: {
                required: 'Select Hight court '
            }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'error-tip',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $('#advocate_reg_verifiy').validate({
        ignore: ":hidden",
        rules: {
            mobilie_verify_code: {
                required: true,
                minlength: 4

            }, email_verify: {
                required: true,
                minlength: 4
            }, captcha: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6

            }
        },
        messages: {
            mobilie_verify_code: {
                required: 'Enter Mobile Verification Code'
            }, email_verify: {
                required: 'Enter Email Verification Code'
            }, captcha: {
                required: 'Enter Captcha Code'
            }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'error-tip',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $('#advocate_reg_uploads').validate({
        ignore: ":hidden",
        rules: {
            advocate_image: {
                required: true
            }, advocate_id_prof: {
                required: true
            }, bar_reg_certificate: {
                required: true
            }, captcha: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6
            }
        },
        messages: {
            advocate_image: {
                required: 'Please Upload Your Image'
            }, advocate_id_prof: {
                required: 'Please Upload Your Id Prof'
            }, bar_reg_certificate: {
                required: 'Please Upload Your Bar Reg Certificate'
            }, captcha: {
                required: 'Enter captcha text.'
            }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'error-tip',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $(document).on('click', '.refresh_cap_adv_reg', function () {
        $.ajax({
            url: base_url + 'captcha_refresh',
            success: function (data) {
                $('.userCaptcha').val('');
                $('.captcha-img').replaceWith('<span class="captcha-img">' + data + '</span>');
            }
        });

    });

    $(document).on('click', '.refresh_cap_adv_reg_user', function () {
        $.ajax({
            url: base_url + 'captcha_refresh2',
            success: function (data) {
                $('.userCaptcha2').val('');
                $('.captcha-img-user').replaceWith('<span class="captcha-img-user">' + data + '</span>');
            }
        });

    });

    $(function () {
        $(".pop-me-over").popover();
    });
    $('[data-toggle="popover"]').popover({
        container: "body"
    });
    $('body').on('click', function (e) {
        if ($(e.target).data('toggle') !== 'popover'
                && $(e.target).parents('[data-toggle="popover"]').length === 0
                && $(e.target).parents('.popover.in').length === 0) {
            $('[data-toggle="popover"]').popover('hide');
        }
    });
});



/* start submit from ->advocate */

/* end submit from ->advocate */