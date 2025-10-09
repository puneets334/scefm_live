$(document).ready(function (e) {
    $.validator.addMethod("textonly", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\s]+$/.test($.trim(value));
    }, "Allowe only text");
    $.validator.addMethod("text_with_dot_space", function (value, element) {
        return this.optional(element) || /^([a-zA-Z])+([a-zA-Z\. ])+$/.test($.trim(value));
    }, "Only dot[.] and space are allowed");
    $.validator.addMethod("text_with_dot_space_hyphen", function (value, element) {
        return this.optional(element) || /^([a-zA-Z])([a-zA-Z. -])+$/.test($.trim(value));
    }, "Only space hyphens and dots  are allowed");
    /*$.validator.addMethod("valid_address", function (value, element) {
     return this.optional(element) || /^([0-9a-zA-Z\s\r\n,\/._\[\])@(;\' -])+$/.test($.trim(value));
     }, "Only alphnumeric and special characters - @ . ( ) [ ] , _ ' ; / are allowed");*/

    $.validator.addMethod("valid_address", function (value, element) {
        return this.optional(element) || /^([0-9a-zA-Z\s\r\n, /@\\ - ._])+$/.test($.trim(value));
    }, "Only alphnumeric and special characters  /@\\, - ._ are allowed");
    $.validator.addMethod("alphanumeric", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9_]+$/i.test($.trim(value));
    }, "Alphanumeric only");
    $.validator.addMethod("passport_no", function (value, element) {
        return this.optional(element) || /^([a-zA-Z]){1}([0-9]){8}?$/.test($.trim(value));
    }, "Enter Passport Number eg:A12345678");
    $.validator.addMethod("pancard_no", function (value, element) {
        return this.optional(element) || /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/.test($.trim(value));
    }, "Enter Pancard Number  eg:ABCDE1234F");
    $.validator.addMethod("fax_number", function (value, element) {
        return this.optional(element) || /^[0-9]{3}-[0-9]{8}$/.test($.trim(value));
    }, "Enter number eg :012-12345678");
    $.validator.addMethod("phone_number", function (value, element) {
        return this.optional(element) || /^[0-9]{3}-[0-9]{7,8}$/.test($.trim(value));
    }, "Enter number eg :012-12345678");
    $.validator.addMethod("text_with_dot_space_numbers", function (value, element) {
        return this.optional(element) || /^([a-zA-Z0-9]+[\s._-]?)+([a-zA-Z0-9])+$/.test($.trim(value));
    }, "Only space hyphens,dots and underscores are allowed");
    $.validator.addMethod("valid_email", function (value, element) {
        return this.optional(element) || /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/.test($.trim(value));
    }, "Enter valid email id");
    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than 50MB');
    $.validator.addMethod("text_with_characters_hypen_numbers", function (value, element) {
        return this.optional(element) || /^([0-9a-zA-Z])([0-9a-zA-Z-])+$/.test($.trim(value));
    }, "Only  hyphens are allowed");
    $.validator.addMethod("text_space_only", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\s]+$/.test($.trim(value));
    }, "Allow only text and space");
    $.validator.addMethod("decimal_value", function (value, element) {
        return this.optional(element) || /^[0-9]*\.?[0-9]*$/.test($.trim(value));
    }, "Allow only decimals and numbers");

    $.validator.addMethod("ip_address", function (value, element) {
        return this.optional(element) || /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test($.trim(value));
    }, "Enter valid IP Address.");

    $.validator.addMethod("multiple_email", function (value, element) {
        return this.optional(element) || /^(([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)(\s*,\s*|\s*$))*$/.test($.trim(value));
    }, "Enter valid Email if more than one use Comma");

    $.validator.addMethod("multiple_mobile", function (value, element) {
        return this.optional(element) || /^((\d{10})[,])*(\d{10})$/.test($.trim(value));
    }, "Enter valid Mobile No. if more than one use Comma");
    $.validator.addMethod("num_hyphen_comma", function (value, element) {
        return this.optional(element) || /^[0-9-,]+$/.test($.trim(value));
    }, "Enter valid contacts.Only number, hyphen and comma allow ");

    //----------------Start Petitioner validation---------------//
    $('#add_petitioner1').validate({
        focusInvalid: true,
        ignore: ":hidden",
        rules: {
            party_name: {
                minlength: 3,
                maxlength: 99,
                required: true,
                valid_address: true
            }, relation: {
                required: true,
            }, relative_name: {
                text_with_dot_space: true
            }, party_gender: {
                required: true,
                digits: true
            }, party_age: {
                required: true,
                digits: true
            }, party_dob: {
                // required: false
            }, party_email: {
                email: true,
                valid_email: true,
                //required: '#pet_email_req[value ="1"]'
            }, party_mobile: {
                //required: '#pet_mobile_req[value="1"]',
                minlength: 10,
                maxlength: 10,
                digits: true
            }, party_address: {
                required: "#legal_heir_no:checked",
                minlength: 4,
                valid_address: true
            }, party_city: {
                required: "#legal_heir_no:checked",
                minlength: 4,
                maxlength: 15,
                valid_address: true
            }, party_state: {
                required: true
            }, party_district: {
                required: true
            }, party_pincode: {
                //required: true,
                digits: true,
                minlength: 6,
                maxlength: 6
            }
        },
        messages: {
            party_name: {
                required: 'Enter Petitioner Name'
            }, relation: {
                required: 'Select Relation',
            }, relative_name: {
                required: 'Enter Relative Name'
            }, party_gender: {
                required: ''
            }, party_age: {
                required: 'Enter Petitioner age'
            }, pet_dob: {
                //required: 'Date of Birth'
            }, party_email: {
                required: 'Enter email id'
            }, party_mobile: {
                required: 'Enter Mobile Number'
            }, party_address: {
                required: 'Enter address',
            }, party_city: {
                required: 'Enter City'
            }, party_pincode: {
                required: 'Enter Pincode'
            }, party_state: {
                required: 'Select state'
            }, party_district: {
                required: 'Select district'
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
    //----------------End Petitioner validation-----------------//

    //----------------Start Respondent validation---------------//
    $('#add_respondent').validate({
        focusInvalid: true,
        ignore: ":hidden",
        rules: {
            res_org_id: {
                required: true,
            }, not_in_list_org: {
                minlength: 3,
                required: "#res_not_in_list:checked",
                valid_address: true
            }, res_complainant: {
                minlength: 3,
                required: true,
                valid_address: true
            }, res_rel_flag: {
                required: '#res_rel_name[value!=""]'
            }, res_rel_name: {
                text_with_dot_space: true
            }, res_age: {
                //required: true,
                digits: true
            }, res_dob: {
                //required: true
            }, res_email: {
                email: true,
                valid_email: true,
                required: '#res_email_req[value="1"]'
            }, res_mobile: {
                required: '#res_mobile_req[value="1"]',
                minlength: 10,
                maxlength: 10,
                digits: true
            }, res_address: {
                required: "#legal_heir_no:checked",
                minlength: 4,
                valid_address: true
            }, res_pincode: {
                // required: true,
                digits: true,
                minlength: 6
            }, res_state_id: {
                required: '#res_state_req[value="1"]'
            }, res_district: {
                required: '#res_dist_req[value="1"]'
            }
        },
        messages: {
            res_org_id: {
                required: 'Select organisation'
            }, not_in_list_org: {
                required: 'Enter Organisation Name'
            }, res_complainant: {
                required: 'Enter Name'
            }, res_rel_flag: {
                required: 'Select Relation'
            }, res_rel_name: {
                required: 'Enter Name'
            }, res_age: {
                // required: 'Enter age'
            }, res_dob: {
                // required: 'Date of Birth'
            }, res_email: {
                required: 'Enter email id'
            }, res_mobile: {
                required: 'Enter Mobile Number'
            }, res_address: {
                required: 'Enter address',
            }, res_pincode: {
                // required: 'Enter Pincode'
            }, res_state_id: {
                required: 'Select state'
            }, res_district: {
                required: 'Select district'
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
    //----------------End Respondent validation-----------------//

    //----------------Start Extra Information validation--------//
    $('#add_extra_info').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            p_passport_no: {
                passport_no: true
            }, p_pancard_no: {
                pancard_no: true
            }, p_fax_no: {
                fax_number: true
            }, p_phone_no: {
                phone_number: true
            }, p_occupation: {
                text_with_dot_space: true
            }, p_country: {
                text_with_dot_space: true
            }, p_nationality: {
                text_with_dot_space: true
            }, p_alt_address: {
                valid_address: true
            }, p_state_id: {
            }, p_district: {
            }, r_passport_no: {
                passport_no: true
            }, r_pancard_no: {
                pancard_no: true
            }, r_fax_no: {
                fax_number: true
            }, r_phone_no: {
                fax_number: true
            }, r_occupation: {
                text_with_dot_space: true
            }, r_country: {
                text_with_dot_space: true
            }, r_nationality: {
                text_with_dot_space: true
            }, r_alt_address: {
                valid_address: true
            }, r_state_id: {
            }, r_district: {
            }
        },
        messages: {
            p_passport_no: {
                required: 'Enter Passport Number eg:A1234567',
            }, p_pancard_no: {
                required: 'Enter Pancard Number eg:ABCDE1234F'
            }, p_fax_no: {
                required: 'Enter number eg :012-12345678'
            }, p_phone_no: {
                required: 'Enter number eg :012-12345678'
            }, p_state_id: {
                required: 'Select State'
            }, p_district: {
                required: 'Select District'
            }, r_passport_no: {
                required: 'Enter Passport Number eg:A1234567'
            }, r_pancard_no: {
                required: 'Enter Pancard Number eg:ABCDE1234F'
            }, r_fax_no: {
                required: 'Enter number eg :012-12345678'
            }, r_phone_no: {
                required: 'Enter number eg :012-12345678'
            }, r_state_id: {
                required: 'Select state'
            }, r_district: {
                required: 'Select district'
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
    //----------------End Extra Information  validation--------//

    //----------------Start Extra Information validation-------//
    $('#subordinate_court').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            subordinate_court: {
                required: true
            },
            cnr_number: {
                //required: true

            }, case_type: {
                required: true
            },
            judge_name: {
                text_with_dot_space: true
            },
            decision_date: {
                required: true
            },
            case_number: {
                required: true,
                digits: true
            }, case_year: {
                required: true,
                digits: true
            }
        },
        messages: {
            subordinate_court: {
                required: 'Select Lower Court'
            },
            cnr_number: {
                //required: 'Enter CNR Number'
            }, case_type: {
                required: 'Select Case Type'
            },
            judge_name: {
                //required: 'Enter Judge Name'
            },
            decision_date: {
                required: 'Enter Decision Date'
            },
            case_number: {
                //required: 'Enter Case Number'
            }, case_year: {
                //required: 'Enter Case Year'
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
    //----------------End Extra Information  validation--------//

    //----------------Start MVC Validation---------------------//
    $('#act_mvc').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            item_number: {
                required: true
            }, distt_code: {
                required: true

            }, taluka_code: {
                required: true
            }, cr_number: {
                required: true,
                digits: true
            }, fir_year: {
                required: true,
                digits: true
            }, accident_place: {
                required: true
            }
        },
        messages: {
            item_number: {
                required: 'Enter Item Number'
            }, distt_code: {
                required: 'Select District'
            }, taluka_code: {
                required: 'Select Taluka'
            }, cr_number: {
                required: 'Enter CNR Number'
            }, fir_year: {
                required: 'Enter FIR Year'
            }, accident_place: {
                required: 'Enter Accident Place'
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
    //----------------End MVC Validation-----------------------//

    //----------------Start Extra Party Validation-------------//
    $('#add_extra_party').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            organisation_name: {
                required: true,
            }, not_in_list_org: {
                minlength: 3,
                required: "#exp_not_in_list_org:checked",
                valid_address: true
            }, compainant_accused: {
                required: true,
                valid_address: true
            }, rel_flag: {
                required: '#rel_father_name[value!=""]'
            }, rel_father_name: {
                text_with_dot_space: true
            }, e_occupation: {
                text_with_dot_space: true
            }, age: {
                required: '#complainant_type[value="1"]:checked',
                digits: true
            }, o_passport_no: {
                passport_no: true
            }, o_pancard_no: {
                pancard_no: true
            }, o_fax_no: {
                fax_number: true
            }, o_phone_no: {
                phone_number: true
            }, o_occupation: {
                text_with_dot_space: true
            }, o_country: {
                text_with_dot_space: true
            }, o_nationality: {
                text_with_dot_space: true
            }, o_alt_address: {
                valid_address: true
            },
            email_id: {
                email: true,
                valid_email: true,
                required: '#email_req[value="1"]'
            }, mobile_no: {
                required: '#mobile_req[value="1"]',
                minlength: 10,
                maxlength: 10,
                digits: true
            }, address: {
                required: true,
                valid_address: true
            }, pincode: {
                // required: true,
                digits: true,
                minlength: 6
            }, state_id: {
                required: '#state_req[value="1"]'
            }, district: {
                required: '#district_req[value="1"]'
            }
        },
        messages: {
            organisation_name: {
                required: 'Select organisation'
            }, not_in_list_org: {
                required: 'Enter Organisation Name'
            }, compainant_accused: {
                required: 'Enter Name'
            }, rel_flag: {
                required: 'Select Relation'
            },
            rel_father_name: {
                required: 'Enter Name'
            }, age: {
                required: 'Enter age'
            }, o_passport_no: {
                required: 'Enter Passport Number eg:A1234567',
            }, o_pancard_no: {
                required: 'Enter Pancard Number eg:ABCDE1234F'
            }, o_fax_no: {
                required: 'Enter number eg :012-12345678'
            }, o_phone_no: {
                required: 'Enter number eg :012-12345678'
            }, ex_religion: {
                // required: 'Select Religion'
            }, mobile_no: {
                required: 'Enter Mobile Number'
            }, email_id: {
                required: 'Enter Email Address'
            }, address: {
                required: 'Enter address'
            }, pincode: {
                // required: 'Enter Pincode'
            }, state_id: {
                required: 'Select state'
            }, district: {
                required: 'Select district'
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
    //----------------End Extra Party Validation---------------//

    //----------------Start LRS Validation-------------//
    $('#add_legal_representive').validate({
        focusInvalid: true,
        ignore: ":hidden",
        rules: {
            compainant_lrs: {
                required: true
            }, accused_lrs: {
                required: true
            }, organisation_name: {
                required: true,
            }, not_in_list_org: {
                minlength: 3,
                required: "#exp_not_in_list_org:checked",
                valid_address: true
            }, compainant_accused: {
                required: true,
                valid_address: true
            }, rel_flag: {
                required: '#rel_father_name[value!=""]'
            }, rel_father_name: {
                text_with_dot_space: true
            }, e_occupation: {
                text_with_dot_space: true
            }, age: {
                required: '#complainant_type[value="1"]:checked',
                digits: true
            }, o_passport_no: {
                passport_no: true
            }, o_pancard_no: {
                pancard_no: true
            }, o_fax_no: {
                fax_number: true
            }, o_phone_no: {
                phone_number: true
            }, o_occupation: {
                text_with_dot_space: true
            }, o_country: {
                text_with_dot_space: true
            }, o_nationality: {
                text_with_dot_space: true
            }, o_alt_address: {
                valid_address: true
            },
            email_id: {
                email: true,
                valid_email: true,
                required: '#email_req[value="1"]'
            }, mobile_no: {
                required: '#mobile_req[value="1"]',
                minlength: 10,
                maxlength: 10,
                digits: true
            }, address: {
                required: true,
                valid_address: true
            }, pincode: {
                digits: true,
                minlength: 6
            }, state_id: {
                required: '#state_req[value="1"]'
            }, district: {
                required: '#district_req[value="1"]'
            }
        },
        messages: {
            compainant_lrs: {
                required: "Select Legal Representative.",
            },
            accused_lrs: {
                required: "Select Legal Representative.",
            },
            organisation_name: {
                required: 'Select organisation'
            }, not_in_list_org: {
                required: 'Enter Organisation Name'
            }, compainant_accused: {
                required: 'Enter Name'
            }, rel_flag: {
                required: 'Select Relation'
            },
            rel_father_name: {
                required: 'Enter Name'
            }, age: {
                required: 'Enter age'
            }, o_passport_no: {
                required: 'Enter Passport Number eg:A1234567',
            }, o_pancard_no: {
                required: 'Enter Pancard Number eg:ABCDE1234F'
            }, o_fax_no: {
                required: 'Enter number eg :012-12345678'
            }, o_phone_no: {
                required: 'Enter number eg :012-12345678'
            }, ex_religion: {
                // required: 'Select Religion'
            }, mobile_no: {
                required: 'Enter Mobile Number'
            }, email_id: {
                required: 'Enter Email Address'
            }, address: {
                required: 'Enter address'
            }, pincode: {
                // required: 'Enter Pincode'
            }, state_id: {
                required: 'Select state'
            }, district: {
                required: 'Select district'
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
    //----------------End LRs Validation---------------//



    //----------------Start HIGH COURT Where Case File Validation--//
    $('#high_court_case_file').validate({
        ignore: ":hidden",
        rules: {
            high_court_id: {
                required: true
            }, bench_court_list: {
                required: true
            }, civil_or_criminal: {
                required: true
            }, matter_type_hc: {
                required: true
            }, hc_case_type: {
                required: true
            }, macp_matter: {
                required: true
            }, urgent_list: {
                required: true
            }
        },
        messages: {
            high_court_id: {
                required: 'Select High Court'
            }, bench_court_list: {
                required: 'Select Bench Court'
            }, civil_or_criminal: {
                required: ''
            }, matter_type_hc: {
                required: 'Select Matter Type'
            }, hc_case_type: {
                required: 'Select Case Type'
            }, macp_matter: {
                required: ''
            }, urgent_list: {
                required: ''
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
    //----------------End Where Case File Validation-----------//
    //----------------Start LOWER COURT Where Case File Validation---------//
    $('#lower_court_case_file').validate({
        ignore: ":hidden",
        rules: {
            high_court_id: {
                required: true
            },
            state_id: {
                required: true
            }, distt_court_list: {
                required: true
            }, establishment_list: {
                required: true
            }, civil_or_criminal: {
                required: true
            }, matter_type_lc: {
                required: true
            }, case_type_id: {
                required: true
            }, macp_matter: {
                required: true
            }, urgent_list: {
                required: true
            }
        },
        messages: {
            high_court_id: {
                required: 'Select High Court'
            },
            state_id: {
                required: 'Select State'
            }, distt_court_list: {
                required: 'Select District'
            }, establishment_list: {
                required: 'Select Establishment'
            }, civil_or_criminal: {
                required: ''
            }, matter_type_lc: {
                required: 'Select Matter Type'
            }, case_type_id: {
                required: 'Select Case Type'
            }, macp_matter: {
                required: ''
            }, urgent_list: {
                required: ''
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
    $('#my_case_file').validate({
        ignore: ":hidden",
        rules: {
            court_id: {
                required: true
            }, bench_id: {
                required: true
            }, ci_cri_court: {
                required: true
            }, matter_type: {
                required: true
            }, case_type: {
                required: true
            }, macp_matter: {
                required: true
            }, urgent_list: {
                required: true
            }
        },
        messages: {
            court_id: {
                required: 'Select  Court'
            }, bench_id: {
                required: 'Select Bench Court'
            }, ci_cri_court: {
                required: ''
            }, matter_type: {
                required: 'Select Matter Type'
            }, case_type: {
                required: 'Select Case Type'
            }, macp_matter: {
                required: ''
            }, urgent_list: {
                required: ''
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
    //----------------End Where Case File Validation-----------//

    $('#searchCNRHC').validate({
        ignore: ":hidden",
        rules: {
            high_court_id: {
                required: true
            }, search_filing_type_hc: {
                required: true
            }, cino: {
                required: true
            }, case_type_id: {
                required: true
            }, case_number_hc: {
                required: true
            }, case_year_hc: {
                required: true
            }
        },
        messages: {
            high_court_id: {
                required: 'Select High Court'
            }, search_filing_type_hc: {
                required: 'Select File In'
            }, cino: {
                required: 'Enter CNR Number'
            }, case_type_id: {
                required: 'Select Case Type'
            }, case_number_hc: {
                required: 'Enter Case Number'
            }, case_year_hc: {
                required: 'Enter Case Year'
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
    $('#searchCNRCOURT').validate({
        ignore: ":hidden",
        rules: {
            court_id: {
                required: true
            }, search_court_filing_type: {
                required: true
            }, cino: {
                required: true
            }, case_type_list_court: {
                required: true
            }, court_case_number: {
                required: true
            }, court_case_year: {
                required: true
            }
        },
        messages: {
            court_id: {
                required: 'Select  Court'
            }, search_court_filing_type: {
                required: 'Select File In'
            }, cino: {
                required: 'Enter CNR Number'
            }, case_type_list_court: {
                required: 'Select Case Type'
            }, court_case_number: {
                required: 'Enter Case Number'
            }, court_case_year: {
                required: 'Enter Case Year'
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
    $('#searchCNREstab').validate({
        ignore: ":hidden",
        rules: {
            state_id: {
                required: true
            }, district_list: {
                required: true
            }, establishment_list: {
                required: true
            }, search_filing_type_lw: {
                required: true
            }, cino: {
                required: true
            }
        },
        messages: {
            state_id: {
                required: 'Select State'
            }, district_list: {
                required: 'Select District'
            }, establishment_list: {
                required: 'Select Establishment '
            }, search_filing_type_lw: {
                required: 'Select File In'
            }, cino: {
                required: 'Enter CNR Number'
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
    //----------------Start MVC Validation---------------------//
    $('#police_station').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            police_challan: {
                required: true
            }, state_code: {
                required: true
            }, distt_code: {
                required: true
            }, police_station_code: {
                required: true
            }, offence_date: {
                // required: true
            }, charge_sheet_date: {
                //required: true
            }, fir_type: {
                //required: true
            }, fir_number: {
                required: true,
                digits: true,
            }, fir_year: {
                required: true,
                digits: true,
            }, investigation_agency: {
                // required: true
            }, fir_file_date: {
                required: true
            }, investigating_off: {
                // required: true
            }, belt_number: {
                // required: true
            }, trials: {
                //required: true
            }
        },
        messages: {
            police_challan: {
                required: 'Police challan or private complaint '
            }, state_code: {
                required: 'Select state'
            }, distt_code: {
                required: 'Select district'
            }, police_station_code: {
                required: 'Police station'
            }, offence_date: {
                // required: 'Date of offence'
            }, charge_sheet_date: {
                // required: 'Charge sheet date'
            }, fir_type: {
                //required: 'FIR type'
            }, fir_number: {
                required: 'FIR Number'
            }, fir_year: {
                required: 'FIR year'
            }, investigation_agency: {
                // required: 'Enter investigation agency'
            }, fir_file_date: {
                required: 'Choose FIR filing date'
            }, investigating_off: {
                //required: 'Enter investigating officer name'
            }, belt_number: {
                //required: 'Enter belt number'
            }, trials: {
                //required: 'Select trials'
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
    //----------------End MVC Validation-----------------------//
//-------------------Court Payment----------------------//
    $('#makePaymentForm1').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            court_fee_type_id: {
                required: true,
            }, court_fee: {
                required: true,
                decimal_value: true,
                minlength: 1,
                maxlength: 17
            }, party_name: {
                required: true
            }, party_name_res: {
                required: true
            }, select_bank: {
                required: true
            }, payment_address: {
                required: true,
                valid_address: true
            }
        },
        messages: {
            court_fee_type_id: {
                required: 'Select fee type'
            }, court_fee: {
                required: 'Enter court fee'
            }, paymode: {
                required: 'Select payment mode'
            }, party_name: {
                required: 'Enter party name'
            }, party_name_res: {
                required: 'Enter party name'
            }, select_bank: {
                required: 'Select bank'
            }, payment_address: {
                required: 'Enter address'
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
//--------------------End Payemnt---------------------//

//----------------Start: HR Payment validation---------------//
    $('#makePaymentForm').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            court_fee_type_id: {
                required: true,
            }, court_fee: {
                required: true,
                decimal_value: true,
                minlength: 1,
                maxlength: 6
            }, paymode: {
                required: true
            }, party_name: {
                required: true
            }, party_name_res: {
                required: true
            }, select_bank: {
                required: true
            }, payment_address: {
                required: true,
                valid_address: true
            }
        },
        messages: {
            court_fee_type_id: {
                required: 'Select fee type'
            }, court_fee: {
                required: 'Enter court fee'
            }, paymode: {
                required: 'Select payment mode'
            }, party_name: {
                required: 'Enter party name'
            }, party_name_res: {
                required: 'Enter party name'
            }, select_bank: {
                required: 'Select bank'
            }, payment_address: {
                required: 'Enter address'
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
    //----------------End HR Payment validation-----------------//
    //-----------Start SEARCH Validation HIGH COURT-----------//
    $('#search_mycases_hc').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            high_court_id: {
                required: true
            }, search_filing_type_hc: {
                required: true
            }, cino: {
                required: true
            }, case_type_id: {
                required: true
            }, case_number_hc: {
                required: true
            }, case_year_hc: {
                required: true,
                minlength: 4,
                maxlength: 4,
                digits: true
            }, userCaptcha: {
                required: true,
                minlength: 6,
                maxlength: 6,
                digits: true
            }
        },
        messages: {
            high_court_id: {
                required: 'Select high court'
            }, search_filing_type_hc: {
                required: 'Select valid file in type'
            }, cino: {
                required: 'Enter CNR Number'
            }, case_type_id: {
                required: 'Select case type station'
            }, case_number_hc: {
                required: 'Enter case number'
            }, case_year_hc: {
                required: 'Enter case year'
            }, userCaptcha: {
                required: 'Enter captcha'
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
    //----------------End : SEARCH Validation HIGH COURT-----------------------//

    //--------------Start : SEARCH Validation LOWER COURT---------------------//
    $('#search_mycases_dc').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            state_id: {
                required: true
            }, district_list: {
                required: true
            }, establishment_list: {
                required: true
            }, search_filing_type_lw: {
                required: true
            }, cino: {
                required: true
            }, case_type_id: {
                required: true
            }, case_number: {
                required: true
            }, case_year: {
                required: true,
                minlength: 4,
                maxlength: 4,
                digits: true
            }, case_type_id_fil: {
                required: true
            }, fil_number: {
                required: true
            }, fil_year: {
                required: true,
                minlength: 4,
                maxlength: 4,
                digits: true
            }, userCaptcha: {
                required: true,
                minlength: 6,
                maxlength: 6,
                digits: true
            }
        },
        messages: {
            state_id: {
                required: 'Select state'
            }, district_list: {
                required: 'Select district'
            }, establishment_list: {
                required: 'Select court establishment'
            }, search_filing_type_lw: {
                required: 'Select valid file in type'
            }, cino: {
                required: 'Enter CNR Number'
            }, case_type_id: {
                required: 'Select case type station'
            }, case_number: {
                required: 'Enter case number'
            }, case_year: {
                required: 'Enter case year'
            }, case_type_id_fil: {
                required: 'Select case type station'
            }, fil_number: {
                required: 'Enter Filing number'
            }, fil_year: {
                required: 'Enter case year'
            }, userCaptcha: {
                required: 'Enter captcha'
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
    //----------End SEARCH Validation LOWER COURT-----------//

    //----START: Subordinate Judicial Info Form----//
    $('#appellate_n_trail_court1').validate({
        ignore: ":hidden",
        rules: {
            //------Appellete Court ---//
            sub_state_id: {
                required: true
            }, sub_district: {
                required: true
            }, subordinate_court: {
                required: true
            }, cin_or_case: {
                required: true
            }, cnr_number: {
                required: '#cin_number[value="2"]'
            }, case_type: {
                required: '#case_or_filing_no[value="1"]'
            }, case_number: {
                required: '#case_or_filing_no[value="1"]'
            }, case_year: {
                required: '#case_or_filing_no[value="1"]'
            }, decision_date: {
                required: true
            },
            //------Trial Court ---//
            trial_state_id: {
                required: true
            }, trial_district: {
                required: true
            }, trial_subordinate_court: {
                required: true
            }, trial_cin_or_case: {
                required: true
            }, trial_cnr_number: {
                required: '#trial_cin_number[value="4"]'
            }, trial_case_type: {
                required: '#trial_case_or_filing_no[value="3"]'
            }, trial_case_number: {
                required: '#trial_case_or_filing_no[value="3"]'
            }, trial_case_year: {
                required: '#trial_case_or_filing_no[value="3"]'
            }, trial_decision_date: {
                required: true
            }
        },
        messages: {
            sub_state_id: {
                required: 'Select State'
            }, sub_district: {
                required: 'Select District'
            }, subordinate_court: {
                required: 'Select subordinate court'
            }, cin_or_case: {
                required: 'Choose valid CNR No OR Case type'
            }, cnr_number: {
                required: 'Enter CNR Number'
            }, case_type: {
                required: 'Select case type'
            }, case_number: {
                required: 'Enter case / filing number'
            }, case_year: {
                required: 'Enter case / filing year'
            },
            decision_date: {
                required: 'Enter Date of decision'
            },
            //------Trial Court ---//
            trial_state_id: {
                required: 'Select State'
            }, trial_district: {
                required: 'Select District'
            }, trial_subordinate_court: {
                required: 'Select subordinate court'
            }, trial_cin_or_case: {
                required: 'Choose valid CNR No OR Case type'
            }, trial_cnr_number: {
                required: 'Enter CNR Number'
            }, trial_case_type: {
                required: 'Select case type'
            }, trial_case_number: {
                required: 'Enter case / filing number'
            }, trial_case_year: {
                required: 'Enter case / filing year'
            }, trial_decision_date: {
                required: 'Enter Date of decision'
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
    //----END: Subordinate Court Info Form----//

    //----START: Quasi Judicial Info Form----//
    $('#quasi_jud_info1').validate({
        ignore: ":hidden",
        rules: {
            app_case_ref_no: {
                required: true,
                maxlength: 50,
            }, app_case_order_dt: {
                required: true
            }, trial_case_ref_no: {
                required: true,
                maxlength: 50,
            }, trial_case_order_dt: {
                required: true
            }
        },
        messages: {
            app_case_ref_no: {
                required: 'Enter Case / Reference No.'
            }, app_case_order_dt: {
                required: 'Select Order Date'
            }, trial_case_ref_no: {
                required: 'Enter Case / Reference No.'
            }, trial_case_order_dt: {
                required: 'Select Order Date'
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
    //----END: Quasi Judicial Info Form----//

    //----START: High Court Judicial Info Form----//
    $('#sub_high_court_info').validate({
        ignore: ":hidden",
        rules: {
            case_type: {
                required: true
            }, case_number: {
                required: true,
                digits: true
            }, case_year: {
                required: true,
                minlength: 4,
                maxlength: 4,
                digits: true
            }, userCaptcha: {
                required: true,
                minlength: 6,
                maxlength: 6,
                digits: true
            }
        },
        messages: {
            case_type: {
                required: 'Select case type'
            }, case_number: {
                required: 'Enter case number'
            }, case_year: {
                required: 'Enter case year'
            }, userCaptcha: {
                required: 'Enter captch'
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
    //----END: High Court Judicial Info Form----//

    //----START: Add Case Type Master Form Validation FOR HC----//
    $('#case_type_hc').validate({
        ignore: ":hidden",
        rules: {
            high_court_id: {
                required: true
            }, civil_or_criminal: {
                required: true
            }, "hc_case_type[]": {
                required: true
            }
        },
        messages: {
            high_court_id: {
                required: 'Select high court'
            }, civil_or_criminal: {
                required: 'Choose civil OR Criminal'
            }, hc_case_type: {
                required: 'Select case type'
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
    //----END:  Add Case Type Master Form Validation For HC----//

    //----START: Add Case Type Master Form Validation FOR DC----//
    $('#add_case_type_dc').validate({
        ignore: ":hidden",
        rules: {
            state_id: {
                required: true
            }, distt_court_list: {
                required: true
            }, establishment_list: {
                required: true
            }, civil_or_criminal: {
                required: true
            }, "case_type_id[]": {
                required: true
            }
        },
        messages: {
            state_id: {
                required: 'Select state'
            }, distt_court_list: {
                required: 'Select district'
            }, establishment_list: {
                required: 'Select establishment'
            }, civil_or_criminal: {
                required: 'Choose civil OR Criminal'
            }, case_type_id: {
                required: 'Select case type'
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
    //----END:  Add Case Type Master Form Validation FOR DC----//

    //----START: Applicant eVerifiction Form Validation----//
    $('#case_send_mobile_otp').validate({
        ignore: ":hidden",
        rules: {
            name: {
                required: true
            }, mobile: {
                required: true
            }
        },
        messages: {
            name: {
                required: 'Enter Applicant Name'
            }, mobile: {
                required: 'Enter Applicant Mobile'
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
    //----END:  Applicant eVerifiction Form Validation----//

    //----------------Start : IA Details Validation--//
    $('#add_ia_details').validate({
        ignore: ":hidden",
        rules: {
            ia_type: {
                required: true
            }, act: {
                required: true
            }, number_of_leaves: {
                digits: true,
                minlength: 1,
                maxlength: 2
            }, ia_purpose: {
                required: '#ia_purpose_of_req[value="1"]'
            }, section: {
                required: true
            }
        },
        messages: {
            ia_type: {
                required: 'Select IA Type'
            }, act: {
                required: 'Select ACT 1'
            }, number_of_leaves: {
                required: 'Enter No. of Leaves'
            }, ia_purpose: {
                required: 'Select Purpose of listing'
            }, section: {
                required: 'Enter Section 1'
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
    //----------------End : IA Details Validation-----------//

    //---- Start Add User Department----------------//
    $('#add_dep_user').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            dep_name: {
                required: true,
                text_space_only: true,
                maxlength: 50
            }, last_name: {
                required: true,
                text_space_only: true,
                maxlength: 50
            }, mobile_no: {
                required: true,
                digits: true,
                maxlength: 10
            }, email_id: {
                required: true,
                valid_email: true,
            }, userid: {
                required: true,
                text_with_characters_hypen_numbers: true,
                maxlength: 20
            }, address: {
                required: true,
                valid_address: true
            }, city: {
                required: true,
                valid_address: true
            }, //states_id: {
            //required: true

            // }, 
            pincode: {
                //required: true,
                digits: true,
                minlength: 6
            },

        },
        messages: {
            dep_name: {
                required: 'Enter Department Name.'
            }, last_name: {
                required: 'Enter Moniter Name.'
            }, mobile_no: {
                required: 'Enter Valid Mobile Number.',
            }, email_id: {
                required: 'Enter Valid Email.',
            }, userid: {
                required: 'Enter Valid User Id.',
            },
            address: {
                required: 'Enter Valid Address.',
            }, city: {
                required: 'Enter Valid City.',
            },
            //states_id: {
            //required: 'Enter Valid State.',
            // }, 
            pincode: {
                required: 'Enter Valid Pincode.',
            },

        },
        highlight: function (element) {
            //$(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            //$(element).closest('.form-group').removeClass('has-error');
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
    //----------End Department User------------------//
    ////---- Start Add Case Contact----------------//
    $('#add_case_contact').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            cnr_no: {
                required: true,

            }, name: {
                required: true,

                maxlength: 249
            }, email_id: {
                required: false,
                valid_email: true,
            }, mobile_no: {
                required: false,
                digits: true,
                maxlength: 10
            },
            o_contact: {
                required: false,
                num_hyphen_comma: true

            },
            contact_type: {
                required: true,

            },

        },
        messages: {
            cnr_no: {
                required: 'Select CNR',

            }, name: {
                required: 'Enter Name',

            }, email_id: {
                required: 'Enter Valid Email id',
            }, mobile_no: {
                required: 'Enter Valid Mobile no.',
            },
            o_contact: {required: ''},
            contact_type: {
                required: 'Enter Contact type.',

            },

        },
        highlight: function (element) {
            //$(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            //$(element).closest('.form-group').removeClass('has-error');
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



    //----------End Case Contact------------------//
//==============================Add Clerk============================//
    $('#add_clerk_user').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            clerk_name: {
                required: true,
                text_space_only: true,
                maxlength: 50
            }, mobile_no: {
                required: true,
                digits: true,
                maxlength: 10
            }, email_id: {
                required: true,
                valid_email: true,
            }, userid: {
                required: true,
                text_with_characters_hypen_numbers: true,
                maxlength: 20
            }, address: {
                required: true,
                maxlength: 150,
                minlength: 4,
                valid_address: true
            }, city: {
                required: true,
                valid_address: true
            }, //states_id: {
            //required: true

            // }, 
            pincode: {
                //required: true,
                digits: true,
                minlength: 6
            },

        },
        messages: {
            clerk_name: {
                required: 'Enter Moniter Name.'
            }, mobile_no: {
                required: 'Enter Valid Mobile Number.',
            }, email_id: {
                required: 'Enter Valid Email.',
            }, userid: {
                required: 'Enter Valid User Id.',
            },
            address: {
                required: 'Enter Valid Address.',
            }, city: {
                required: 'Enter Valid City.',
            },
            //states_id: {
            //required: 'Enter Valid State.',
            // }, 
            pincode: {
                required: 'Enter Valid Pincode.',
            },

        },
        highlight: function (element) {
            //$(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            //$(element).closest('.form-group').removeClass('has-error');
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
    //-----------------------------End Clerk--------------------------------//

    //-----START :Send Mail form Validation------//
    $('#send_mail_text').validate({
        focusInvalid: true,
        ignore: ":hidden",
        rules: {
            recipient_mail: {
                required: true,
                multiple_email: true,
                maxlength: 100
            }, mail_subject: {
                required: true,
                text_with_dot_space_numbers: true,
                maxlength: 100
            }, sender_name: {
                required: true,
                valid_address: true,
                maxlength: 30
            }, sender_email: {
                required: true,
                valid_email: true,
                maxlength: 30
            }, captcha_code: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6
            },

        },
        messages: {
            recipient_mail: {
                required: 'Enter recipient email ID'
            }, sender_name: {
                required: 'Enter Your Name',
            }, mail_subject: {
                required: 'Enter mail subject',
            }, sender_email: {
                required: 'Enter Your email ID',
            }, captcha_code: {
                required: 'Enter Captcha ',
            },

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
    //-----END :Send Mail form Validation------//

    //-----START :Send SMS form Validation------//
    $('#send_sms_text').validate({
        focusInvalid: true,
        ignore: ":hidden",
        rules: {
            recipient_mob: {
                required: true,
                multiple_mobile: true,
                maxlength: 100
            }, captcha_code: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6
            },

        },
        messages: {
            recipient_mob: {
                required: 'Enter recipient mobile no.'
            }, captcha_code: {
                required: 'Enter Captcha ',
            },

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
    //-----END :Send SMS form Validation------//


});
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function isLetter(evt) {
    var keyCode = (evt.which) ? evt.which : evt.keyCode
    if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32) {
        return false;
    }
    return true;
}
function isTime(evt) {
    if (evt.keyCode != 8 && evt.keyCode != 46) {
        var text = this.evt;
        if (text.length == 2) {
            this.value = text + ':';
        }
        if (text.length == 5) {
            this.value = text + ':';
        }
    }
}

$(document).ready(function () {
    $("#transaction_date11").keypress(function (e) {
        if (e.keyCode != 8 && e.keyCode != 46) {
            var text = this.value;
            if (text.length == 2) {
                if (text <= 31) {
                    this.value = text + '-';
                } else {
                    this.value = '';
                }
            }
            if (text.length == 5) {
                var text1 = this.value;
                var arr_val = text1.split('-');
                if (arr_val[1] <= 12) {
                    this.value = text + '-';
                } else {
                    ('#transaction_date').value = arr_val[1] - 1;
                }
            }
        }

    });
});
$(document).ready(function () {
    $("#accident_time").keypress(function (e) {
        if (e.keyCode != 8 && e.keyCode != 46) {
            var text = this.value;
            if (text.length == 2) {
                this.value = text + ':';
            }
            if (text.length == 5) {
                this.value = text + ':';
            }
        }

    });
});
$(document).ready(function () {
    $('[data-toggle="popover"]').popover({trigger: "hover"});
    $('body').on('click', function (e) {
//did not click a popover toggle, or icon in popover toggle, or popover
        if ($(e.target).data('toggle') !== 'popover'
                && $(e.target).parents('[data-toggle="popover"]').length === 0
                && $(e.target).parents('.popover.in').length === 0) {
            $('[data-toggle="popover"]').popover('hide');
        }
    });
    $(".age_calculate").change(function (e) {
        var id = $(this).attr('id');
        if (id == 'pet_age') {
            $('#pet_dob').val('');
        } else if (id == 'res_age') {
            $('#res_dob').val('');
        }


    });
    $(".add_dash").keypress(function (e) {
        if (e.keyCode != 8 && e.keyCode != 46) {
            var text = this.value;
            if (text.length == 3) {
                this.value = text + '-';
            }
        }
    });


    $(".comma_separated_mob").keypress(function (e) {
        var key = window.e ? e.keyCode : e.which;
        if (e.keyCode === 8 || e.keyCode === 46) {
            return true;
        } else if (key < 48 || key > 57) {
            return false;
        } else {
            var text = this.value;
            if (text.length == 10 || text.length == 21 || text.length == 32 || text.length == 43) {
                this.value = text + ',';
            } else if (text.length > 53) {
                alert('max length exceeded');
                return false;
            }
        }
    });
});
function delete_main_matter_data(value) {

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    var a = confirm("Are you sure that you really want to delete this record?");
    if (a == true)
    {
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, delete_main_matter: value},
            url: base_url + "New_case/delete_main_matter",
            success: function (data)
            {
                $.getJSON(base_url + "Login/get_csrf_new", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = result.CSRF_TOKEN_VALUE;
                    if (data == 'done') {
                        $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; Data deleted Successfully !  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        location.reload();
                    } else if (data == 'error') {
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>Some things went wrong !   <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    }

                });
            },
            error: function () {
                $.getJSON(base_url + "Login/get_csrf_new", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }

}
function update_grn_details(frm_num) {

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $('#modal_loader').show();
    $.ajax({
        type: "POST",
        url: base_url + "Payment_getway/get_gras_session_value",
        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, form_submit: frm_num},
        success: function (data) {
            var resArr = data.split('@@@');
            $('#modal_loader').hide();
            $("#getCodeModal").modal();
            if (resArr[0] == 1) {
                $('.body').append(resArr[1]);
                $('#getCodeModal').modal({
                    backdrop: 'static'
                });
                $('#getCodeModal').modal("show");
            } else if (resArr[0] == 2) {
                $('.form-response').show();
                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
            $.getJSON(base_url + "Login/get_csrf_new", function (result) {
                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
            });
        },
        error: function (result) {
            $.getJSON(base_url + "Login/get_csrf_new", function (result) {
                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
            });
        }
    });
}
function registration_request(get_state_id) {

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    var court_type = $('[name="court_type"]:checked').val();
    $('#getCodeModal').modal('hide');
    $.ajax({
        type: "POST",
        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, high_court_id: get_state_id, court_type: court_type},
        url: base_url + "Login/register_user_bar_no",
        success: function (data) {
            if (data == 'error') {
                location.reload();
            }
            $.getJSON(base_url + "Login/get_csrf_new", function (result) {
                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
            });
        }
    });
}

function DocDeleteAction(value) {
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    var a = confirm("Are you sure that you really want to delete this record ?");
    if (a == true)
    {
        $.ajax({
            type: "POST",
            url: base_url + "MiscellaneousFiling/deletePdfDoc",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, form_submit_val: value},
            success: function (result) {
                location.reload();
                $.getJSON(base_url + "Login/get_csrf_new", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function (result) {
                $.getJSON(base_url + "Login/get_csrf_new", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
}
$(document).ready(function () {
    $(".check_sbipayment_status").click(function () {
        var order_id = $(this).attr('data-order-id');
        $(this).append(' <i class="status_refresh fa fa-refresh fa-spin"></i>');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, order_id: order_id},
            url: base_url + "Payment_getway/update_payment_status",
            success: function (data) {
                $('.status_refresh').remove();
                var responce = data.split('|');
                if (responce[0] == 'SUCCESS') {
                    $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + responce[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    location.reload();
                } else if (responce[0] == 'ERROR') {
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + responce[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                }

                $.getJSON(base_url + "Login/get_csrf_new", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    });
});
$(document).ready(function () {
    $(".check_payment_status").click(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var order_id = $(this).attr('data-order-id');
        $('.form-responce').remove();
        $(this).append(' <i class="status_refresh fa fa-refresh fa-spin"></i>');
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, order_id: order_id},
            url: base_url + "Payment_getway/check_payment_status",
            success: function (data) {
                $('.status_refresh').remove();
                location.reload();
                $.getJSON(base_url + "Login/get_csrf_new", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    });
    


    $("[name='user_type']").change(function () {

        if ($(this).val() == 'D') {
            $("#show_defendent").show();
            $("#show_plantiff").hide();
        } else if ($(this).val() == 'P') {
            $("#show_plantiff").show();
            $("#show_defendent").hide();
        }

    });
    $("[name='paymode']").change(function () {

        var paymode = $(this).val();
        if (paymode == 'online' || paymode == 'offline')
        {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, mode: paymode},
                url: base_url + "Payment_getway/bank_list",
                success: function (data) {
                    var responce = data.split('||||');
                    if (responce[0] == 'SUCCESS')
                    {
                        $("#select_bank").html(responce[1]);
                    } else {
                        $("#select_bank").html(responce[1]);
                    }
                    $.getJSON(base_url + "Login/get_csrf_new", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        } else
        {
//$("#individual_name").show();
        }
    });
    //----------------Upload misc Documents Validation-------------//
    $('#uploadDocument').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            docType: {
                required: true
            }, doc_title: {
                required: true,
                text_with_dot_space_numbers: true
            }, pdfDocFile: {
                required: true,
                // filesize: 20000000
                filesize: 52428800
            }, user_type: {
                required: true
            },
            "user_name_pet[]": {required: '[name="user_type"][value="P"]:checked'},
            "user_name_res[]": {required: '[name="user_type"][value="D"]:checked'}
        },
        messages: {
            docType: {
                required: 'Select Document'
            }, doc_title: {
                required: 'Enter Document Title'
            }, pdfDocFile: {
                required: 'Choose File'
            }, user_type: {
                required: 'Select Plantiff OR Defendant  type'
            }
        },
        highlight: function (element) {
            //$(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            //$(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'error-tip',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else if (element.parent('.radio-inline').length) {
                error.insertAfter(element.parent().parent());
                element.addClass('text-center');
            } else if (element.parents('.checkbox').length) {
                element.parent().parent().parent().append(error);
                element.addClass('text-center');
            } else {
                error.insertAfter(element);
            }
        }
    });
    //----------------End Upload misc Documents Validation---------------//

    //----------------Upload Receipt Validation-------------//
    $('#payment_receipt').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            court_fee_type_id: {
                required: true
            }, transaction_no: {
                required: function () {
                    if ($("#payment_mode").val() == '2') {
                        return false;
                    } else {
                        return true;
                    }
                }
            }, court_fee: {
                required: true,
                maxlength: 9,
            }, payment_mode: {
                required: true,
            }, transaction_date: {
                required: function () {
                    if ($("#payment_mode").val() == '2') {
                        return false;
                    } else {
                        return true;
                    }
                }
            }, bank_name: {
                required: true,
                maxlength: 80
            }, party_name: {
                required: true
            }, payment_receipts: {
                required: function () {
                    if ($("#payment_mode").val() == '2') {
                        return false;
                    } else {
                        return true;
                    }
                },
                filesize: 20000000
            }
        },
        messages: {
            court_fee_type_id: {
                required: 'Select fee type'
            }, transaction_no: {
                required: 'Enter Challan/ Cheque/ DD/ eChallan No.'
            }, court_fee: {
                required: 'Enter Valid Amount'
            }, payment_mode: {
                required: 'Select payment mode'
            }, transaction_date: {
                required: 'Enter Transaction Date'
            }, bank_name: {
                required: 'Enter Bank name'
            }, party_name: {
                required: 'Enter party name'
            }, payment_receipts: {
                required: 'Upload Receipt'
            }
        },
        highlight: function (element) {
            //$(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            //$(element).closest('.form-group').removeClass('has-error');
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
    //----------------End Upload Receipt Validation---------------//


//----------------Add Admin  Validation-------------//
    $('#add_user').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            first_name: {
                required: true,
                text_space_only: true,
                maxlength: 50
            }, last_name: {
                required: true,
                text_space_only: true,
                maxlength: 50
            }, mobile_no: {
                required: true,
                digits: true,
                maxlength: 10
            }, email_id: {
                required: true,
                valid_email: true,
            }, login_id: {
                required: true,
                text_with_characters_hypen_numbers: true,
                maxlength: 20
            }, high_court_id: {
                required: true,
            }, state_id_for_distt: {
                required: true,
            }, admin_type_id: {
                required: true,
            },
            admin_type_id_est: {
                required: true,
            }
            , distt_list_for_distt: {
                required: true,
            }, state_id: {
                required: true,
            }, distt_court_list: {
                required: true,
            }, establishment_list: {
                required: true,
            }, hcadmintype: {
                required: true,
            }, estadmintype: {
                required: true,
            },

        },
        messages: {
            first_name: {
                required: 'Enter First Name.'
            }, last_name: {
                required: 'Enter Last Name.'
            }, mobile_no: {
                required: 'Enter Valid Mobile Number.',
            }, email_id: {
                required: 'Enter Valid Email.',
            }, login_id: {
                required: 'Enter Valid login Id.',
            }, high_court_id: {
                required: 'Enter Valid High Court.',
            }, state_id_for_distt: {
                required: 'Enter Valid State.',
            }, admin_type_id: {
                required: 'Enter Valid Admin Type.',
            },
            admin_type_id_est: {
                required: 'Enter Valid Admin Type.',
            }
            , distt_list_for_distt: {
                required: 'Enter Valid District.',
            }, state_id: {
                required: 'Enter Valid State.',
            }, distt_court_list: {
                required: 'Enter Valid District.',
            }, establishment_list: {
                required: 'Enter Valid Establishment.',
            }, hcadmintype: {
                required: 'Select Admin Type.',
            }, estadmintype: {
                required: 'Select Admin Type.',
            },
        },
        highlight: function (element) {
            //$(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            //$(element).closest('.form-group').removeClass('has-error');
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
    //----------------End Add Admin Validation---------------//

//--------------------Add ESTABLISHMENT------------------------//

    $('#add_estab_list').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            state_id: {
                required: true,
                digit: true,
            },
            district: {
                required: true,
                digit: true,
            }, establishment_list: {
                required: true,
            }, dto: {
                required: true,
                digit: true,
                minlength: 2,
                maxlength: 2
            }, sto: {
                required: true,
                digit: true,
                minlength: 2,
                maxlength: 2
            }, ddo: {
                required: true,
                digit: true,
                minlength: 4,
                maxlength: 4
            }, depcode: {
                required: true,
                minlength: 3,
                maxlength: 3
            }, city: {
                minlength: 4,
                maxlength: 50
            }, pincode: {
                digit: true,
                minlength: 6,
                maxlength: 6
            }, appip: {
                required: true,
                ip_address: true
            },
        },
        messages: {
            state_id: {
                required: 'Enter Valid State.',
            },
            district: {
                required: 'Enter Valid District.',
            }, establishment_list: {
                required: 'Enter Valid Establishment.',
            }, dto: {
                required: 'Enter Valid DTO.',
            }, sto: {
                required: 'Enter Valid STO.',
            }, ddo: {
                required: 'Enter Valid DDO.',
            }, depcode: {
                required: 'Enter Valid Department Code.',
            }, city: {

            }, pincode: {
                required: 'Enter Valid Pincode.',
            }, appip: {
                required: 'Enter Valid IP Address',

            },

        },
        highlight: function (element) {
            //$(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            //$(element).closest('.form-group').removeClass('has-error');
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

//--------------------END Establishment---------------------------//



    //---------------- Add Contact Validation---------------//

    $('#add_contact').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            email_id: {
                required: true,
                valid_email: true,
            },
            high_court_id: {
                required: true,
            }, state_id: {
                required: true,
            }, distt_court_list: {
                required: true,
            }, reg_court_complex_id: {
                required: true,
            }


        },
        messages: {
            email_id: {
                required: 'Enter Valid Email.',
            },
            high_court_id: {
                required: 'Enter Valid High Court.',
            }, state_id: {
                required: 'Enter Valid State.',
            }, distt_court_list: {
                required: 'Enter Valid District.',
            }, reg_court_complex_id: {
                required: 'Enter Valid Establishment.',
            }
        },
        highlight: function (element) {
            //$(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            //$(element).closest('.form-group').removeClass('has-error');
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



    //----START: Digital Copy Form Validation----//
    $('#add_document_copy').validate({
        ignore: ":hidden",
        rules: {
            doc_upload: {
                required: true
            }, doc_import_efiling: {
                required: true
            }, docs_mapped_with: {
                required: true
            }, doc: {
                required: true
            }, doc_category: {
                required: true
            }, document_type: {
                required: true
            }, document_reply: {
                required: true
            }, document_reply_of_list: {
                required: true
            }, doc_title: {
                required: true
            }, page_no_from: {
                required: true,
                digits: true,
                minlength: 1,
                maxlength: 3
            }, page_no_to: {
                required: true,
                digits: true,
                minlength: 1,
                maxlength: 3
            }, filing_date: {
                required: true
            }, doc_generated_by: {
                required: true
            }, docgenby: {
                required: true
            }, gen_by_CO: {
                required: true,
                minlength: 2,
                maxlength: 50
            }, amendment_allowed_data: {
                required: true
            }, amendment_copy_file_data: {
                required: true
            }, applicant_no: {
                required: true
            }, doc_verified_date: {
                required: true
            }, complete_doc_verified_by: {
                required: true
            }, application_no: {
                required: true
            }, evidence_type: {
                required: true
            }, docs_cat_details: {
                required: true
            }
        },
        messages: {
            doc_upload: {
                required: 'Select Document'
            }, doc_import_efiling: {
                required: 'Select Efiling number.'
            }, docs_mapped_with: {
                required: 'Select type'
            }, doc: {
                required: 'Select Document'
            }, doc_category: {
                required: 'Select category'
            }, document_type: {
                required: 'Select document type'
            }, document_reply: {
                required: 'Select document reply'
            }, document_reply_of_list: {
                required: 'Select file'
            }, doc_title: {
                required: 'Enter document title'
            }, page_no_from: {
                required: 'From'
            }, page_no_to: {
                required: 'To'
            }, filing_date: {
                required: 'Enter document Date'
            }, doc_generated_by: {
                required: ''
            }, docgenby: {
                required: 'Select Party'
            }, gen_by_CO: {
                required: 'Enter Party Name'
            }, amendment_allowed_data: {
                required: 'This field in requied'
            }, amendment_copy_file_data: {
                required: 'This field in requied'
            }, applicant_no: {
                required: 'This field in requied'
            }, doc_verified_date: {
                required: 'This field in requied'
            }, complete_doc_verified_by: {
                required: 'This field in requied'
            }, application_no: {
                required: 'This field in requied'
            }, evidence_type: {
                required: 'This field in requied'
            }, docs_cat_details: {
                required: 'This field in requied'
            }
        },
        highlight: function (element) {
            //$(element).closest('.form-group').addClass('has-error');
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
    //----END: Digital Copy Form Validation----//
//==============Witness==========================//
    $('#add_witness').validate({
        ignore: ":hidden",
        rules: {
            search_filing_type: {
                required: true
            },
            witname: {
                required: true
            },
            party_name: {
                required: true
            },

        },
        messages: {
            search_filing_type: {
                required: ''
            },
            witname: {
                required: 'Enter Witness name.'
            },
            party_name: {
                required: 'Select Party'
            },

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

    $('#add_case_details').validate({
        focusInvalid: false,
        ignore: ":hidden",
        rules: {
            diaryno: {
                required: true,
                digits: true,
            }, diary_year: {
                required: true,
                digits: true,
            }, sc_case_type: {
                required: true,
            }, case_number: {
                required: true,
                digits: true,
            }, case_year: {
                required: true,
                digits: true,
            }


        },
        messages: {
            diaryno: {
                required: 'Enter Valid Diary No.',
            }, diary_year: {
                required: 'Enter Valid Diary Year.',
            }, sc_case_type: {
                required: 'Enter Valid Case Type.',
            }, case_number: {
                required: 'Enter Valid Case Number.',
            }, case_year: {
                required: 'Enter Valid Case Year.',
            }
        },
        highlight: function (element) {
            //$(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            //$(element).closest('.form-group').removeClass('has-error');
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
    //==================End Witness=================//
    $(".reject_user_by_admin").click(function () {

        var user_id = $(this).attr('data-user-id');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {user_id: user_id, CSRF_TOKEN: CSRF_TOKEN_VALUE, },
            url: base_url + "admin/reject_user_popup",
            success: function (data) {
                var responce = data.split('||||');
                if (responce[0] == 'SUCCESS') {
                    $('body').prepend(responce[1]);
                    $("#myModal").modal();
                    $(".editor-wrapper").wysiwyg();
                }
                $.getJSON(base_url + "Login/get_csrf_new", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    });

    $('.closeall').click(function () {
        $('.collapse.in').collapse('hide');
        $('.closeall').addClass('hidden');
        $('.openall').removeClass('hidden');
    });
    $('.openall').click(function () {
        $('.collapse:not(".in")').collapse('show');
        $('.closeall').removeClass('hidden');
        $('.openall').addClass('hidden');
    });
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
});
function ActionToActivateUser(value) {


    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    var result = confirm("Are you sure to activate this user ?");
    if (result) {
        $.ajax({
            type: "POST",
            url: base_url + "admin/update_user_status",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, user_id: value},
            success: function (result) {
                $('#msg').show();
                if (result == 'activated') {
                    $(".form-response").html("<p class='message valid' id='msgdiv'> User activated successfully  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    setTimeout(function () {
                        window.location.href = base_url + "admin/new_registration";
                    }, 1000);
                } else if (result == 'invalid_id') {
                    $(".form-response").html("<p class='message invalid' id='msgdiv'> Invalid id <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (result == 'invalid_admin') {
                    $(".form-response").html("<p class='message invalid' id='msgdiv'> Invalid admin type  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (result == 'fail_to_update') {
                    $(".form-response").html("<p class='message invalid' id='msgdiv'> Fail to activate user   <span class='close' onclick=hideMessageDiv()>X</span></p>");
                }
                //location.reload();
                $.getJSON(base_url + "Login/get_csrf_new", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function (result) {
                $.getJSON(base_url + "Login/get_csrf_new", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }



}

 