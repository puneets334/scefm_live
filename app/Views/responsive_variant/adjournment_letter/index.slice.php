@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Adjournment Letter')
@section('heading', 'Adjournment Letter')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')
<div class="form-response" id="msg" >
    <?php
    /*    if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
            echo $_SESSION['MSG'];
        } unset($_SESSION['MSG']);
        */?>
</div>
<!--start akg-->
<style>
    .pointer {cursor: pointer;}
    /*#ShowCases {background-color:blue;}
    #ShowMeAdjournmentRequests {background-color:orange;}
    #ShowOthersAdjournmentRequests {background-color:red;}*/
</style>
<div id="widgets-container" ng-init="widgets.recentDocuments.byOthers.ifVisible=true;" class="uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@l ukchild-width-1-4@xl ukmargin-medium-top uk-grid-medium ukflex-between uk-grid" uk-grid="">
    <div ng-show="widgets.recentDocuments.byOthers.ifVisible" class="uk-first-column">
        <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded documents-widget" style="border-top:0.15rem dashed #ccc;">
            <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:0.6rem 1.5rem 1.2rem 1rem;" uk-grid=""  class="tablink" onclick="openAdjournment('ShowCases', this, 'uk-label-primary')" id="defaultOpen">

                <div>
                    <span class="uk-label uk-label-primary sc-padding sc-padding-small-ends uk-text-bold uk-text-large">{{count($scheduled_cases);}}</span>
                </div>
                <div class="uk-first-column">
                    <div>
                        <span class="uk-text-bold uk-text-primary uk-text-uppercase">Cases <span class="uk-text-small">(Soon to be listed)</span></span>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded applications-widget" style="border-top:0.15rem dashed #ccc;">
            <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:0.6rem 1.5rem 1.2rem 1rem;" uk-grid="" onclick="openAdjournment('ShowMeAdjournmentRequests', this, 'uk-label-warning')">
                <div>
                    <span class="uk-label uk-label-warning sc-padding sc-padding-small-ends uk-text-bold uk-text-large">{{count($existing_requests);}}</span>
                </div>
                <div class="uk-first-column">
                    <span class="uk-text-bold uk-text-warning uk-text-uppercase">Adjournment Requests By Me</span>

                </div>
            </div>
        </div>
    </div>
    <div class="defects-widget-container">
        <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded defects-widget" style="border-top:0.15rem dashed #ccc;">
            <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:0.6rem 1.5rem 1.2rem 1rem;" uk-grid="" onclick="openAdjournment('ShowOthersAdjournmentRequests', this, 'danger')">
                <div>
                    <span class="uk-label uk-label-danger sc-padding sc-padding-small-ends uk-text-bold uk-text-large">{{count($existing_requests_others);}}</span>
                </div>
                <div class="uk-first-column">
                    <span class="uk-text-bold uk-text-danger uk-text-uppercase">Adjournment Requests By Others</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!--end akg-->
<div class="uk-margin-small-top uk-border-rounded">


    <!--<div class="uk-width">
    @php
        print_r($scheduled_cases);
        @endphp

    </div>-->
    <div class="uk-width-expand uk-margin-medium-top uk-overflow-auto tabcontent" id="ShowCases">
        <h4 class="uk-heading-bullet uk-text-bold">Cases <span class="uk-text-small">Soon to be listed</span></h4>
        <table class="uk-table uktable-justify uktable-striped uk-table-hover uk-table-divider display" id="datatable-responsive">
            <!--<table class="display" style="width:100%" id="efiled-cases-table">-->
            <thead>
            <tr class="uk-text-bold">
                <th class="uk-text-bold d-print-none">#</th>
                <th class="uk-text-bold">Case Number</th>
                <th class="uk-text-bold">Causetitle</th>
                <th class="uk-text-bold">Listed On </th>
                <th class="uk-text-bold">Court No./Item No.</th>
                <th class="uk-text-bold">Action</th>

                <th class="uk-text-bold d-print-none">...</th>
            </tr>
            </thead>
            <!--Start-->

            @foreach ($scheduled_cases as $index=>$scheduled_case)
            <tr>
                <td>{{$index+1}}</td>
                <td>{{!empty($scheduled_case->registration_number)?$scheduled_case->registration_number.'<br/>':''}}
                D. No.{{$scheduled_case->diary_number}}/{{$scheduled_case->diary_year}}
                </td>
                <td>{{$scheduled_case->petitioner_name}} Vs. {{$scheduled_case->respondent_name}}</td>
                <td>{{date_format(date_create($scheduled_case->meta->listing->listed_on), 'D, jS M')}}</td>
                <td>{{$scheduled_case->meta->listing->court->listing_cum_board_type . str_replace(':','.',$scheduled_case->meta->listing->court->scheduled_time)}}<br/>{{'Item '.($scheduled_case->item_number_alt ?: $scheduled_case->item_number)}} @ {{$scheduled_case->meta->listing->court->name}}</td>
                @php

                $request_data=$scheduled_case->diary_id.'#'.$scheduled_case->registration_number.'#'.$scheduled_case->meta->listing->listed_on.'#'.$scheduled_case->meta->listing->court->name.'#'.$scheduled_case->item_number;
                //print_r(json_encode($data_array));

                $redirect_url=base_url('adjournment_letter/DefaultController/doAdjournmentRequest/'.url_encryption($request_data));
                @endphp
                <td><div class=""><a class="sc-button sc-button-flat sc-button-flat-danger sc-js-button-wave-danger waves-effect waves-button waves-danger" href="{{$redirect_url}}">Request Adjournment</a></div></td>

            </tr>
            @endforeach
            </tbody>

        </table>
    </div>
    <div class="uk-width-expand uk-margin-medium-top uk-overflow-auto tabcontent" id="ShowMeAdjournmentRequests">
        <h4 class="uk-heading-bullet uk-text-bold">Adjournment Requests By Me</h4>
        <table class="uk-table uktable-justify uktable-striped uk-table-hover uk-table-divider" id="MeAdjournmentRequests">
            <thead>
            <tr class="uk-text-bold">
                <th class="uk-text-bold d-print-none">#</th>
                <th class="uk-text-bold">Case Number</th>
                <th class="uk-text-bold">Listed On </th>
                <th class="uk-text-bold">Court No./Item No.</th>
                <th class="uk-text-bold">File</th>

                <th class="uk-text-bold" style="width: 30%;">Reply Details</th>
            </tr>
            </thead>
            <!--Start-->

            @foreach ($existing_requests as $index=>$requests)
            <tr>
                <td>{{$index+1}}</td>
                <td>{{!empty($requests->case_number)?$requests->case_number.'<br/>':''}}
                    D. No.{{$requests->diary_number}}
                </td>
                <td>{{date_format(date_create($requests->listed_date), 'D, jS M')}}</td>
                <td>{{'Item '.($requests->item_number ?: $requests->item_number)}} @ {{$requests->court_number}}</td>
                @php
                $redirect_url=base_url('adjournment_letter/DefaultController/showFile/'.url_encryption($requests->id));
                @endphp
                <td><div class="">
                        <a target="_blank" href="{{$redirect_url}}"><span data-uk-icon="icon: file-pdf"></span></a>
                    </div></td>
                 <td>
                     <!--start area Action-->


                     @if(!empty($requests->tbl_adjournment_details_id) && $requests->id==$requests->tbl_adjournment_details_id && !empty($requests->doc_id))


                     @foreach ($requests->reply_list as $index=>$reply)

                     @php
                     if(!empty($reply->consent) && $reply->consent=='C'){
                     $consent_type ='Concede';
                     } else if(!empty($reply->consent) && $reply->consent=='O'){
                     $consent_type ='Have Objection';
                     }else{
                     $consent_type ='';
                     }
                     $redirect_url=base_url('adjournment_letter/DefaultController/showFile_Others/'.url_encryption($reply->doc_id));
                     @endphp

                         {{ucwords(strtolower($reply->reply_first_name))}}
                         ({{$reply->created_at}}) <br> {{ $consent_type }}
                         @if(!empty($reply->consent) && $reply->consent=='O')
                         <a target="_blank" href="{{$redirect_url}}"><span data-uk-icon="icon: file-pdf"></span></a>
                         @endif
                     <br/>
                     @endforeach
                     @endif
                 </td>
            </tr>
            @endforeach
            </tbody>

            <!--END-->
        </table>
    </div>

    <div class="uk-width-expand uk-margin-medium-top uk-overflow-auto tabcontent" id="ShowOthersAdjournmentRequests">
        <h4 class="uk-heading-bullet uk-text-bold">Adjournment Requests By Others &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="uk-text-success text-center" id="divAction"><!--Adjournment Request already moved in this case--></span></h4>

        <table class="uk-table uktable-justify uktable-striped uk-table-hover uk-table-divider" id="OthersAdjournmentRequests">
            <thead>
            <tr class="uk-text-bold">
                <th class="uk-text-bold d-print-none">#</th>
                <th class="uk-text-bold">Case Number</th>
                <th class="uk-text-bold">Court No./Item No. </th>
                <th class="uk-text-bold">Filed By</th>
                <th class="uk-text-bold">File</th>

                <th class="uk-text-bold" style="width: 30%;">Reply Details</th>
            </tr>
            </thead>
            <!--Start-->


            @foreach ($existing_requests_others as $index=>$requests)
            <tr>
                <td>{{$index+1}}</td>
                <td>{{substr($requests->diary_number,0,-4)}}/{{substr($requests->diary_number,-4)}}
                    <br/> {{$requests->case_number}}
                </td>
                <td>{{date_format(date_create($requests->listed_date), 'D, jS M')}}<br/>
                    {{$requests->item_number}} in {{$requests->court_number}}</td>
                <td>{{ucwords(strtolower($requests->first_name))}}<br>
                    {{$requests->created_at}}</td>

                <td>
                    @php
                    $redirect_url=base_url('adjournment_letter/DefaultController/showFile/'.url_encryption($requests->id));
                    @endphp
                    <div class="">
                        <a target="_blank" href="{{$redirect_url}}"><span data-uk-icon="icon: file-pdf"></span></a>
                    </div>

                </td>
                <td><!--start area Action-->
                    @php
                    $user_id=$this->session->userdata['login']['id'];
                    $already_exist_reply_created_by='';
                    @endphp

                    @foreach ($requests->reply_list as $index=>$reply)

                    @if($reply->reply_created_by==$user_id && !empty($reply->tbl_adjournment_details_id) && $requests->id==$reply->tbl_adjournment_details_id && !empty($reply->doc_id))
                    @php
                    $already_exist_reply_created_by=$user_id;
                    if(!empty($reply->consent) && $reply->consent=='C'){
                    $consent_type ='Concede';
                    } else if(!empty($reply->consent) && $reply->consent=='O'){
                    $consent_type ='Have Objection';
                    }else{
                    $consent_type ='';
                    }
                    $redirect_url=base_url('adjournment_letter/DefaultController/showFile_Others/'.url_encryption($reply->doc_id));
                    @endphp

                    {{ucwords(strtolower($reply->reply_first_name))}}
                    ({{$reply->created_at}}) <br> {{ $consent_type }}
                    @if(!empty($reply->consent) && $reply->consent=='O')
                    <a target="_blank" href="{{$redirect_url}}"><span data-uk-icon="icon: file-pdf"></span></a>
                    @endif
                    @endif

                    @endforeach

                    @if(empty($already_exist_reply_created_by))
                    <div id="HidePDF_{{$requests->id}}">
                        <label><input class="uk-radio typeAdjournmentNo" data-filetype="ShowFileadjournment_{{$requests->id}}" type="radio" name="typeAdjournment_{{$requests->id}}" id="typeAdjournment_{{$requests->id}}" value="C">Concede</label>
                        <label><input class="uk-radio typeAdjournmentYes" data-filetype="ShowFileadjournment_{{$requests->id}}" type="radio" name="typeAdjournment_{{$requests->id}}" id="typeAdjournment_{{$requests->id}}" value="O">Have Objection</label>

                        <div uk-form-custom="target: true" id="ShowFileadjournment_{{$requests->id}}" style="display:none;">
                            <input type="file" name="fileAdjournment_{{$requests->id}}" id="fileAdjournment_{{$requests->id}}">
                            <input class="uk-form-width-small" type="text" placeholder="Select file">
                        </div>

                        <button class="btn btn-danger adjournmentSubmit" name="adjournment_{{$requests->id}}" id="adjournment_{{$requests->id}}" value="{{$requests->id}}" data-diary_number="{{$requests->diary_number}}" data-listed_date="{{$requests->listed_date}}" data-case_number="{{$requests->case_number}}" data-item_number="{{$requests->item_number}}" data-court_number="{{$requests->court_number}}" data-created_at="{{$requests->created_at}}" data-first_name="{{$requests->first_name}}">Save</button>
                        <button class="btn btn-success uk-alert-success"  id="success_adjournment_{{$requests->id}}" style="display: none;">Already Saved</button>

                    </div>
                  {{$already_exist_reply_created_by}}
                    @endif



                    <span id="ShowPDF_{{$requests->id}}"></span>
                    <!--end area Action-->
                </td>

            </tr>
            @endforeach
            </tbody>

            <!--END-->
        </table>
    </div>

</div>

<script>
    $(document).ready(function(){
    $(".typeAdjournmentYes").click(function(){
        var filetype= $(this).data('filetype');
        $('#'+filetype).show();
    });
    $(".typeAdjournmentNo").click(function(){
        var filetype= $(this).data('filetype');
        $('#'+filetype).hide();
    });

    $(".adjournmentSubmit").click(function(){
            var id=$(this).val();
        var case_number= $(this).data('case_number');
        var item_number= $(this).data('item_number');
        var court_number= $(this).data('court_number');
        var created_at= $(this).data('created_at');
        var first_name= $(this).data('first_name');
        var diary_number= $(this).data('diary_number');
        var listed_date= $(this).data('listed_date');
        var ShowFileadjournment='#ShowFileadjournment_'+id;
        var btnSubmit='#adjournment_'+id;
        var success_adjournment='#success_adjournment_'+id;
        var namefiletype='#typeAdjournment_'+id;
        var HidePDF='#HidePDF_'+id;
        var ShowPDF='#ShowPDF_'+id;

        var filetype = $(namefiletype+':checked').val();
        //var fileName       = e.target.files[0].name;
        var fileAdjournment='#fileAdjournment_'+id;
        var file_data     = $(fileAdjournment).prop("files")[0];
        var form_data     = new FormData();
        form_data.append("pdfDocFile", file_data);
        form_data.append("case_number", case_number);
        form_data.append("item_number", item_number);
        form_data.append("court_number", court_number);
        form_data.append("created_at", created_at);
        form_data.append("first_name", first_name);
        form_data.append("diary_number", diary_number);
        form_data.append("listed_date", listed_date);
        form_data.append("adjournment_details_id", id);
        form_data.append("consent_type", filetype);
        $("#divAction").html(" ");
        $.ajax({
            enctype: 'multipart/form-data',
            url: "<?php echo base_url('adjournment_letter/DefaultController/saveAdjournmentRequestOthers'); ?>",
            dataType: 'script',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                //$("#upload_doc").prop("disabled", true);
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    if (resArr[0] == 1 && resArr[1] =='Invalid attempt.') {
                        location.reload();
                    }
                    $('#msg').show();
                    $(".form-response").html("<p class='uk-alert-danger uk-alert' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    $(".form-response").html("<p class='uk-alert-success uk-alert' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $('#msg').show();
                    $(ShowFileadjournment).hide();
                    $(HidePDF).hide();
                    $(btnSubmit).hide();
                    $(success_adjournment).show();
                    $(ShowPDF).html(resArr[2]);
                    $("#divAction").html("<span class='uk-success'>Successfully Adjournment letter Others Saved!</span>");

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

        });

    });
</script>
<script>
    function openAdjournment(cityName,elmnt,color) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        /*tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].style.backgroundColor = "";
        }*/
        document.getElementById(cityName).style.display = "block";
        //elmnt.style.backgroundColor = color;

    }
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
</script>
@endsection
<script type="text/javascript">
    $('#efiled-cases-table').DataTable({
        "bSort": false
    });
</script>