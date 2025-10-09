<?php //declare(strict_types=1); ?>
@extends('layout.advocateApp')
@section('content')
<style>
    .uk-table.uk-table-hover tbody tr:hover, .uk-table.uk-table-hover > tr:hover {background:#ffffff!important;}
    .scif:hover {background:#ffffff!important;color: #ffffff!important;}
    .scif {color: #ffffff;}
    .loader {position: fixed;top: 0;left: 0;width: 100%;height: 100%;background: rgba(255, 255, 255, 0.7);display: flex;justify-content: center;align-items: center;z-index: 9999;}
    .loader img {width: 50px;height: 50px;}
    .norecords {display: flex;justify-content: center;align-items: center;}
    .uk-table.uk-table-hover tbody tr:hover, .uk-table.uk-table-hover > tr:hover {
        background: #ffffff !important;
    }
    .scif:hover {
        background: #ffffff !important;
        color: black !important;
    }
    .scif {
        visibility: hidden;
        color: black;
    }
    tr.hide-table-padding td {
        padding: 0;
    }
    .expand-button {
        position: relative;
    }
    .accordion-toggle .expand-button:after {
        position: absolute;
        left:.75rem;
        top: 50%;
        transform: translate(0, -50%);
        content: '-';
    }
    .accordion-toggle.collapsed .expand-button:after {
        content: '+';
    }
    .custom-ul {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        gap: 10px; /* Adjust gap as needed */
    }
    .custom-ul li {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .custom-ul li a {
        display: block;
        padding: 8px 16px;
        text-decoration: none;
    }
    /*.customFilterBtnDiv:not(:last-child) {
        border-right: 1px solid #ddd;
    }*/
    #example_filter {
        max-width:100%;
        text-align: right;
        display: -webkit-inline-box;
        text-align: center;
    }
    #example_filter label input {
        max-width: 100% !important;
        height: 36px;
    }
    .dataTables_wrapper {
        margin-top: 25px !important;
    }
    .row.uk-grid.uk-flex-middle.uk-grid-small.dt-uikit-header {
        display: none !important;
    }
    div .dataTables_wrapper ~ .dataTables_info {
        display: none !important;
    }
    .mdi:before {
        font-size: 16px !important;
    }
    a.quick-btn.pull-right:hover {
        text-decoration: none;
    }
    #example_filter:nth-child(1) {
        display: none;
    }

    .uk-pagination li.active a {background-color: #1e87f0;color: #fff;font-weight: bold;border-radius: 5px;padding: 0px 10px;}
    .uk-pagination li.active {pointer-events: none;}

</style>
<link rel="stylesheet" href="{{base_url('assets/responsive_variant/templates/uikit_scutum_2/assets/css/main.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{base_url('assets/responsive_variant/frameworks/uikit_3-4-1/css/uikit.min.css')}}" />
<!--start datatable-->
<div class="container-fluid">
    <?php // render('case_status.case_status_view'); ?>
    <?php
    $adv_cases_response_data = array();
    // $adv_cases_response_data = $adv_cases_response->data;
    if(isset($meta_data['adv_cases_response']['data']) && count($meta_data['adv_cases_response']['data']) > 0) {
        $adv_cases_response_data = $meta_data['adv_cases_response']['data'];
    } else {
        $adv_cases_response_data = [];
    }
    $fgc_context=array(
        'http' => array(
            'user_agent' => 'Mozilla',
        ),
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );
    if(isset($adv_cases_response_data) && is_array($adv_cases_response_data) && count($adv_cases_response_data) > 0) {
        foreach ($adv_cases_response_data as $k=>$v) {
            $userType = !empty($_SESSION['login']['ref_m_usertype_id']) ? $_SESSION['login']['ref_m_usertype_id'] : NULL;
            $v['userType'] = $userType;
            $adv_cases_response_data[$k] = $v;
        }
    } else {
        if(isset($meta_data['sr_advocate_data']) && is_array($meta_data['sr_advocate_data']) && count($meta_data['sr_advocate_data']) > 0) {
            foreach ($meta_data['sr_advocate_data'] as $k=>$v) {
                $tmp = array();
                $userType = !empty($_SESSION['login']['ref_m_usertype_id']) ? $_SESSION['login']['ref_m_usertype_id'] : NULL;
                $tmp['userType'] = $userType;
                $tmp['diaryId'] = $v->diary_no;
                $tmp['status'] = $v->c_status;
                $tmp['registrationNumber'] = $v->reg_no_display;
                $tmp['petitionerName'] = $v->pet_name;
                $tmp['respondentName'] = $v->res_name;
                $tmp['filedOn'] = $v->createdAt;
                $tmp['assignedby'] = $v->assignedby;
                $adv_cases_response_data[] = $tmp;
            }
        }
    }
    ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-section dashboard-tiles-area"></div>
            <div class="dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="dash-card">
                            <div class="title-sec">
                                <h5 class="unerline-title">My Cases</h5>
                                <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                            </div>
                            <section>
                                <?php if (!empty(getSessionData('login')) && getSessionData('login')['ref_m_usertype_id'] == AMICUS_CURIAE_USER) { ?>
                                    <h3>Cases in Which You Were Appointed as <b>Amicus Curiae</b> by the Hon'ble Court</h3> 
                                <?php } ?>
                                <div id="myFilter">
                                    <?php if(isset($_SESSION['login']) && !in_array($_SESSION['login']['ref_m_usertype_id'],[AMICUS_CURIAE_USER,SR_ADVOCATE])) { ?>
                                        <div class="uk-grid-small uk-grid-divider uk-child-width-auto" uk-grid>
                                            <div class="customFilterBtnDiv">
                                                <ul  class="uk-subnav uk-subnav-pill" uk-margin>
                                                    <li uk-filter-control id="clear_filter_status_all">
                                                        <a class="filter-control" data-filter-type="clear_filter_status" data-filter-value="all">Clear Filters</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="customFilterBtnDiv">
                                                <ul class="uk-subnav uk-subnav-pill" uk-margin>
                                                    <li class="uk-active case_status" id="case_status_all" >
                                                        <a class="filter-control" data-filter-type="case_status" data-filter-value="all">All</a>
                                                    </li>
                                                    <li class="case_status" id="case_status_P">
                                                        <a class="filter-control" data-filter-type="case_status" data-filter-value="P">Pending</a>
                                                    </li>
                                                    <li class="case_status" id="case_status_D">
                                                        <a class="filter-control" data-filter-type="case_status" data-filter-value="D">Disposed</a>
                                                    </li>
                                                </ul> 
                                            </div>
                                            <div class="customFilterBtnDiv">
                                                <ul class="uk-subnav uk-subnav-pill" uk-margin>
                                                    <li class="uk-active advocate_appearing" id="advocate_appearing_all">
                                                        <a href="#" class="filter-control" data-filter-type="advocate_appearing" data-filter-value="all">All</a>
                                                    </li>
                                                    <li class="advocate_appearing" id="advocate_appearing_P">
                                                        <a class="filter-control" data-filter-type="advocate_appearing" data-filter-value="P">Appearing for Petitioner</a>
                                                    </li>
                                                    <li class="advocate_appearing" id="advocate_appearing_R">
                                                        <a class="filter-control" data-filter-type="advocate_appearing" data-filter-value="R">Appearing for Respondent</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="customFilterBtnDiv">
                                                <ul class="uk-subnav uk-subnav-pill" uk-margin>
                                                    <li class="uk-active case_registration_status" id="case_registration_status_all">
                                                        <a href="#" class="filter-control" data-filter-type="case_registration_status" data-filter-value="all">All</a>
                                                    </li>
                                                    <li class="case_registration_status" id="case_registration_status_R">
                                                        <a class="filter-control" data-filter-type="case_registration_status" data-filter-value="R">Registered Cases</a>
                                                    </li>
                                                    <li class="case_registration_status" id="case_registration_status_U">
                                                        <a class="filter-control" data-filter-type="case_registration_status" data-filter-value="U">Unregistered Cases</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="customFilterBtnDiv">
                                                <ul class="uk-subnav uk-subnav-pill" uk-margin>
                                                    <li class="uk-active case_engaged_status" id="case_engaged_status_all">
                                                        <a href="#" class="filter-control" data-filter-type="case_engaged_status" data-filter-value="all">All</a>
                                                    </li>
                                                    <li class="case_engaged_status" id="case_engaged_status_EC">
                                                        <a class="filter-control" data-filter-type="case_engaged_status" data-filter-value="EC">Engaged Counsel</a>
                                                    </li>
                                                    <li class="case_engaged_status" id="case_engaged_status_AC">
                                                        <a class="filter-control" data-filter-type="case_engaged_status" data-filter-value="AC">Amicus Curiae</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="uk-grid uk-flex-middle uk-grid-small dt-uikit-header uk-margin">
                                        <div class="col-2">
                                            <div>
                                            <select ng-model="countperpage" ng-change="chnagePerPage()" ng-options="x for x in countperpageArray" class="form-control cus-form-ctrl">
                                            </select></div>
                                        </div>
                                        <div class="col-8">&nbsp;</div>
                                        <div class="col-2">
                                            <div id="efiled-cases-table_filter" class="dataTables_filter">
                                                <input type="text" class="form-control cus-form-ctrl" ng-model="searchQuery"  placeholder="Diary / Reg. No..." ng-change="fetchData()" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="uk-margin uk-card uk-card-default uk-card-body">
                                        <input type="text" class="form-control cus-form-ctrl" ng-model="searchQuery" ng-focus="searchQuery=''" placeholder="Diary / Reg. No..." ng-blur="fetchData()" />
                                    </div> -->
                                    <div class="row">
                                    <div class="col-12">
                                    <table ng-show="!loader.isLoading() && cases.length>0" id='' class="table table-striped custom-table" ukfilter="target: .js-filter">
                                        <thead>
                                            <tr>
                                                <td colspan="7" scope="row" class="text-left">Total Records (@{{totalRecords}})</td>
                                            </tr>
                                            <tr role="row">
                                                <th class="sorting_asc" tabindex="0" aria-controls="example">S.N</th>
                                                <th class="sorting" tabindex="0" aria-controls="example">Diary / Reg. No.</th>
                                                <th class="sorting" tabindex="0" aria-controls="example"  aria-label="Cause Title: activate to sort column ascending" style="width: 212px;">Cause Title</th>
                                                <th class="sorting" tabindex="0" aria-controls="example"  aria-label="Status: activate to sort column ascending" style="width: 213px;">Status</th>
                                                @if(!empty($sr_advocate_data) && $this->session->userdata['login']['ref_m_usertype_id'] == SR_ADVOCATE)
                                                <th class="sorting" tabindex="0" aria-controls="example"  aria-label="" style="width: 213px;">Engaged By/Date</th>
                                                @endif
                                                <th class="sorting" tabindex="0" aria-controls="example"  aria-label="ADV Status date: activate to sort column ascending" style="width: 213px;"></th>
                                                <th class="sorting" tabindex="0" aria-controls="example"  aria-label="Reg. Cases: activate to sort column ascending" style="width: 213px;">...</th>
                                            </tr>
                                        </thead>
                                        <tbody class="js-filter">
                                            <tr ng-repeat="case in cases">
                                            <td data-key="S.N" ng-if="searchQuery !=''" ng-bind="$index + totalPages"></td>
                                            <td data-key="Diary / Reg. No." ng-if="searchQuery ===''" ng-bind="(page-1)*countperpage+$index+1"></td>
                                                <td data-key="Cause Title">
                                                    <a href=""  title="show CaseStatus"  data-diary_no="@{{case.diaryId}}" data-diary_year="">
                                                        <span class="uk-text-muted" ng-bind="case.diaryId"></span>
                                                        <br>
                                                        <span class="uk-text-emphasis" ng-bind="case.registrationNumber"></span>
                                                    </a>
                                                    <b class="scif" ng-if="case.advocateType=='P'" >AfP</b><b class="scif" ng-if="case.advocateType=='R' || case.advocateType=='I'" >AfR</b>
                                                </td>
                                                <td data-key="Status">
                                                    <div>
                                                        <div>
                                                            <b ng-if="case.advocateType!='P'">P:</b>
                                                            <b ng-if="case.advocateType=='P'" class="uk-background-secondary md-color-grey-50" style="padding:0.05rem 0.2rem 0.2rem 0.2rem;" uktooltip="@{{case.petitionerName}}">P:</b>
                                                            &nbsp;<span ng-bind="case.petitionerName"></span>
                                                            <br/>
                                                            <b ng-bind="((case.advocateType!='R' || case.advocateType!='I') && case.advocateType=='P' && (case.is_ac=='Y')) ? '[Amicus Curiae]' : '' " ></b>
                                                        </div>
                                                        <div>
                                                            <b ng-if="case.advocateType!='R' && case.advocateType!=='I'">R:</b>
                                                            <b ng-if="case.advocateType=='R' || case.advocateType=='I'" class="uk-background-primary md-color-grey-50" style="padding:0.05rem 0.2rem 0.2rem 0.2rem;" uktooltip="@{{case.respondentName}}">R:</b>
                                                            &nbsp;<span ng-bind="case.respondentName"></span>
                                                            <br/>
                                                            <b ng-bind="((case.advocateType=='R' || case.advocateType=='I') && case.advocateType!='P' && (case.is_ac=='Y')) ? '[Amicus Curiae]' : '' " ></b>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td data-key="" ng-bind="case.status=='P' ? 'Pending' : 'Disposed'"></td>
                                                <td data-key="..." ng-if="case.assignedBy">
                                                    <span class="uk-text-muted" ng-bind="case.assignedBy"></span>
                                                    <br>
                                                    <span class="uk-text-emphasis" ng-bind="case.filedOn"></span>
                                                </td>
                                                <td ng-if="case.userType!=19">
                                                    @if(isset($_SESSION['login']) && !in_array($_SESSION['login']['ref_m_usertype_id'],array(AMICUS_CURIAE_USER)))
                                                    <a onclick="open_contact_box(this.id)" ng-click="open_contact_box(this.id)" ukicon = "icon:receiver"    title="Add contact"  id="@{{case.diaryId}}"><i class="mdi mdi-account-plus sc-icon-22"></i></a>&nbsp;&nbsp;
                                                    <a onclick="get_message_data(this.id,'mail')" ng-click="get_message_data(this.id,'mail')" ukicon = "icon:mail"   title="Send SMS"  id="@{{case.diaryId+'-'+case.registrationNumber+'-'+case.petitionerName+'-'+case.respondentName+'-'+case.status}}" >
                                                        <!-- <i class="mdi mdi-android-messages sc-icon-20"></i> -->
                                                        <i class="fas fa-envelope sc-icon-22"></i>
                                                    </a>&nbsp;&nbsp;
                                                    <a style="color:green;font-weight: bold; font-size: 21px;" ng-if="diaryEngaged.indexOf(case.diaryId) !== -1" href="{{base_url('case/advocate')}}/@{{case.diaryId}}" title="Engaged Counsel"><i class="mdi mdi-account-multiple-plus"></i></a>
                                                    <b class="scif" ng-bind="case.lastListed==null ? 'UL' : 'L-C'" ></b>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="uk-icon-button" uk-icon="more-vertical" ng-if="case.status=='P'"></button>
                                                    <div class="uk-padding-small md-bg-grey-700" uk-dropdown="pos:left-center;mode:click;" ng-if="case.status=='P'">
                                                        <ul class="uknav-parent-icon uk-dropdown-nav"  uk-nav>
                                                            @if(isset($_SESSION['login']) && !in_array($_SESSION['login']['ref_m_usertype_id'],array(AMICUS_CURIAE_USER)))
                                                            <li ng-if="case.userType!=19" class="uk-nav-header uk-padding-remove-left text-white">File a new</li>
                                                            <li ng-if="case.userType!=19"><a href="{{base_url('case/interim_application/crud')}}/@{{case.diaryId}}" class="text-white uknav-divider ukmargin-remove"> IA</a></li>
                                                            <li ng-if="case.userType!=19"><a href="{{base_url('case/document/crud')}}/@{{case.diaryId}}" class="text-white uknav-divider ukmargin-remove"> Misc. Docs</a></li>
                                                            <li ng-if="case.userType =='1' && case.is_ac!='Y'"><a href="{{base_url('case/advocate')}}/@{{case.diaryId}}" class="text-white uknav-divider ukmargin-remove">Engage Counsel</a></li>
                                                            <li ng-if="case.case_grp=='R'"><a href="{{base_url('case/certificate/crud')}}/@{{case.diaryId}}" class="text-white uknav-divider ukmargin-remove"> Certificate Request</a></li>
                                                            <li class="uk-nav-divider uk-margin-remove"></li>
                                                            <li class="uk-nav-header uk-padding-remove-left uk-margin-remove-top text-white">View</li>
                                                            @endif
                                                            @if(isset($_SESSION['login']) && in_array($_SESSION['login']['ref_m_usertype_id'],array(AMICUS_CURIAE_USER)))
                                                            <li ng-if="case.userType!=19"><a href="{{base_url('case/interim_application/crud')}}/@{{case.diaryId}}" class="text-white uknav-divider ukmargin-remove"> IA</a></li>
                                                            <li ng-if="case.userType!=19"><a href="{{base_url('case/document/crud')}}/@{{case.diaryId}}" class="text-white uknav-divider ukmargin-remove"> Misc. Docs</a></li>
                                                            @endif
                                                            <li>
                                                                <a href="{{base_url('case/paper_book_viewer')}}/@{{case.diaryId}}" target="_blank" rel="noopener" class="text-white uknav-divider ukmargin-remove">
                                                                    <span uk-icon="icon: bookmark"></span> Paper Book (with Indexing)
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <b class="scif" ng-if="case.registrationNumber==''" >Unr</b><b class="scif" ng-if="case.registrationNumber!=''" >Reg</b>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="6" scope="row">Total Records (@{{totalRecords}})</th>
                                            </tr>
                                            <tr>
                                                <th colspan="6"> 
                                                    <ul class="uk-pagination uk-flex-right" ng-show="!loader.isLoading()">
                                                        <li ng-class="{ 'uk-disabled': page === 1 }">
                                                            <a href="javascript:void(0)" ng-click="changePage(page-1)" ng-disabled="page === 1">&laquo; Previous</a>
                                                        </li>
                                                        <li ng-repeat="pageno in getPages()" ng-class="{active: page === pageno, disabled: pageno === '...'}"
                                                        ng-click="pageno !== '...' && changePage(pageno)">
                                                            <a href="javascript:void(0)" ng-if="pageno !== '...'">@{{ pageno }}</a>
                                                            <span ng-if="pageno === '...'">...</span>
                                                        </li>
                                                        <li ng-class="{ 'uk-disabled': page === totalPages }">
                                                            <a href="javascript:void(0)" ng-click="changePage(page+1)" ng-disabled="page === totalPages">Next &raquo;</a>
                                                        </li>
                                                    </ul>
                                                </th>
                                            </tr>
                                        </tfoot>
                                        
                                    </table>
                                </div>
                                </div>
                                </div>
                                <div class="uk-margin-large uk-card uk-card-default uk-card-body" ng-show="!loader.isLoading() && cases.length === 0">
                                    No results found...
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end datatable-->
<div id="case_status" uk-modal class="uk-modal-full" style="display: none;">
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title"><div id="case_status_diary">CASE STATUS</div></h2>
        </div>
        <div class="uk-modal-body">
            <div id="view_case_status_data"></div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button" >Cancel</button>
        </div>
    </div>
</div>
<!-- Case Status modal-end-->
<!-- code for notes-->
<div id="notes" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title"><div id="notes_diary"></div></h2>
            <input type="hidden" id="notes_d">
        </div>
        <div class="uk-modal-body">
            <div id="notesBack"></div>
            <div id="add_notes_alerts"></div>
            <p><textarea id= "notes_text" rows="5" cols="80" placeholder="write your Notes here "></textarea> </p>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <input type="hidden" id="notes_id" name="notes_id" value="">
            <button class="uk-button uk-button-default uk-modal-close" type="button" >Cancel</button>
            <!-- <button class="uk-button uk-button-primary" type="button" id="save_citation">Save Citation</button>-->
            <input type="button " id="save_notes"  value="Save Notes " class="uk-button uk-button-primary" onclick="save_notes()" readonly>
            <input type="button " id="update_notes"  value="Update Notes " class="uk-button uk-button-primary" style="display: none;" onclick="update_notes()" readonly>
        </div>
        <div id="view_notes_text"></div>
        <div id="view_notes_data"></div>
    </div>
</div>
<!-- end of code for writing notes -->
<!-- for contacts-->
<div id="contacts" uk-modal>
    <div class="uk-modal-dialog" id="view_contacts_text" align="center"><h2S> Case Contacts for Diary No. <div id="con_d"></div></h2S>
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title"> </h2><div id="con_d"></div>
            <input type="hidden" id="con_diary">
            <input type="hidden" id="contact_type">
        </div>
        <div class="uk-modal-body">
            <div id="add_contacts_alert"></div>
            <label class="radio-inline"><input type="radio" name="contact" id="new" value="1" onclick="show_contact_list(1)" checked="">New</label>
            <label class="radio-inline"><input type="radio" name="contact" id="aor" value="3" onclick="show_contact_list(2)">AOR</label>
            <div id="aor_contact" style="display: none;">
                <div>
                    <div>
                        <select name="aor_name" id="aor_name" class="form-control cus-form-ctrl" style="width: 50%">
                            <option>Select Contact</option>
                        </select>
                    </div>
                </div>
            </div>
            <div  align="center" id="new_contact">
                <br> <div><input type="text" class="form-control cus-form-ctrl" placeholder="NAME" title="NAME" maxlength="30" id="name" size="50"></div>
                <br> <div><input type="text" class="form-control cus-form-ctrl" size="50" placeholder="EMAIL ID" maxlength="30" title="EMAIL ID" id="email"></div>
                <br> <div><input type="text" class="form-control cus-form-ctrl" size="50" placeholder="MOBILE"  maxlength="10" title="mobile" maxlength="30" id="mobile"></div>
                <br> <div><input type="text" class="form-control cus-form-ctrl" size="50" placeholder="OTHER CONTACT" maxlength="30" title="OTHER CONTACT" id="other"></div>
                <br>  <div><input type="text" class="form-control cus-form-ctrl" size="50" maxlength="30" placeholder="PETITONER RESPONDENT WITNESS OTHER" title="PETITONER RESPONDENT WITNESS OTHER" id="remark"></div>
            </div>
        </div><!-- end of new contact div-->
        <div id="edit_contact"></div>
        <div id="aor_contact"> </div>
        <div class="uk-modal-footer uk-text-right" id="con_footer">
            <button class="uk-button uk-button-default uk-modal-close" type="button" >Cancel</button>
            <input type="button " id="add_case_contact"  value="Save Contact " class="uk-button uk-button-primary" onclick="add_case_contact()" readonly>
        </div>
        <div id="contact_list" style="padding: 2%;"></div>
    </div>
</div>
<!-- end of code -->
<!-- code for sending mail-->
<div id="mail" uk-modal>
    <div class="uk-modal-dialog" id="view_contacts_text" align="center"><h4> SMS CASE DETAILS <div id="mail_d"></div></h4>
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title"><div id="contact_diary"></div></h2>
        </div>
        <div class="uk-modal-body">
            <div id="mail_msg" ></div>
            <div class="mail-response" id="mail_msg" ></div>
            <div id="emailids" style="display: none;"></div>
            <div  id="recipient_mail1" style="display: none;"></div>
            SMS To: <input type="text" class="form-control cus-form-ctrl" size="60" id="recipient_mail" name="recipient_mail"  maxlength="250" placeholder="Select Contacts or Enter Mobile No. e.g 9999999999,8888888888">
            <div>
                SMS Message: <input type="text" class="form-control cus-form-ctrl" size =50 id="mail_subject" name="mail_subject" class="form-control" maxlength="100" placeholder="SMS Message">
            </div>
            <br>
            Body:<div id ='caseinfomsg'></div>
            <div id ='caseinfosms'></div>
            <div class="col-md-12 col-sm-12 col-xs-12" id="recipient_mob1"></div>
            <div id="mail_message"></div>
        </div>
        <div class="uk-modal-footer uk-text-right" id="con_footer">
            <button class="uk-button uk-button-default uk-modal-close" type="button" >Cancel</button>
            <input type="button " id="send_mail"  value="Send SMS " class="uk-button uk-button-primary" onclick="send_mail()" readonly>
            <div id="m_contact_list"></div>
        </div>
    </div>
</div>
<!-- end of code for sending mail-->
<!-- code for citation-->
<div id="citations" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title"><div id="cit_diary"></div></h2>
            <input type="hidden" id="citdiary">
        </div>
        <div class="uk-modal-body">
            <div id="citationBack"></div>
            <div id="add_citation_alerts"></div>
            <p><textarea id= "citation_text" rows="5" cols="80" placeholder="write your citation here "></textarea> </p>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <input type="hidden" id="citation_id" name="citation_id" value="">
            <button class="uk-button uk-button-default uk-modal-close" type="button" >Cancel</button>
            <input type="button " id="save_citation"  value="Save Citation " class="uk-button uk-button-primary" onclick="save_citation()" readonly>
            <input type="button " id="update_citation"  value="Update Citation " class="uk-button uk-button-primary" style="display: none;" onclick="update_citation()" readonly>
        </div>
        <div id="view_citation_text"></div>
        <div id="view_citation_data"></div>
    </div>
</div>
<input type="hidden" value="" id="AllcaseStatusKeypressP">
<input type="hidden" value="" id="AllcaseStatusKeypressD">
<input type="hidden" value="" id="AllADVStatusKeypressP">
<input type="hidden" value="" id="AllADVStatusKeypressR">
<input type="hidden" value="" id="AllRegStatusKeypressR">
<input type="hidden" value="" id="AllRegStatusKeypressU">
<input type="hidden" value="" id="AllListedCasesKeypressL">
<!--end when click tab all-cases-->
<!--start when click individual tab all-cases-->
<input type="hidden" value="Pending" id="caseStatusKeypressP">
<input type="hidden" value="Disposed" id="caseStatusKeypressD">
<input type="hidden" value="AfP" id="ADVStatusKeypressP">
<input type="hidden" value="AfR" id="ADVStatusKeypressR">
<input type="hidden" value="Reg" id="RegStatusKeypressR">
<input type="hidden" value="Unr" id="RegStatusKeypressU">
<input type="hidden" value="L-C" id="ListedCasesKeypressL">
<input type="hidden" value="engagedCounsel" id="engagedCounsel">
<!-- end of code for writing citation -->
@endsection
@push('script')
<script src="{{base_url('assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit.min.js')}}"></script>
<script src="{{base_url('assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit-icons.min.js')}}"></script>
<script>
    // function AllcasesShowAlert(id) {
    //     $( "#AllcaseStatusKeypressP" ).keypress();
    //     $( "#AllcaseStatusKeypressD" ).keypress();
    //     $( "#AllADVStatusKeypressP" ).keypress();
    //     $( "#AllADVStatusKeypressR" ).keypress();
    //     $( "#AllRegStatusKeypressR" ).keypress();
    //     $( "#AllRegStatusKeypressU" ).keypress();
    //     $( "#AllListedCasesKeypressL" ).keypress();
    // }
    // function ListedCasesShowAlert(id) { $( "#ListedCasesKeypressL" ).keypress();}
    // function caseStatusPShowAlert(id) { $( "#caseStatusKeypressP" ).keypress();}
    // function caseStatusDShowAlert(id) { $( "#caseStatusKeypressD" ).keypress();}
    // function showEngagedCounsel(id) {
    //     $('#CheckADVStatusR').removeClass('ADVStatusActive');
    //     $('#CheckADVStatusP').addClass('ADVStatusActive');
    //     $("#engagedCounsel" ).keypress();
    // }
    // function ADVStatusPShowAlert(id) {
    //     $('#CheckADVStatusR').removeClass('ADVStatusActive');
    //     $('#CheckADVStatusP').addClass('ADVStatusActive');
    //     //alert($(".RegStatusActive").attr('value'));
    //     $( "#ADVStatusKeypressP" ).keypress();
    // }
    // function ADVStatusRShowAlert(id) {
    //     $('#CheckADVStatusP').removeClass('ADVStatusActive');
    //     $('#CheckADVStatusR').addClass('ADVStatusActive');
    //     // alert($(".RegStatusActive").attr('value'));
    //     $( "#ADVStatusKeypressR" ).keypress();
    // }
    // function RegStatusRShowAlert(id) {
    //     $('#CheckRegStatusU').removeClass('RegStatusActive');
    //     $('#CheckRegStatusR').addClass('RegStatusActive');
    //     /* var court_type = $("li[class='uk-active']").val();
    //      alert(court_type);*/
    //     //alert($(".ADVStatusActive").attr('value'));
    //     $( "#RegStatusKeypressR" ).keypress();
    // }
    // function RegStatusUShowAlert(id) {
    //     $('#CheckRegStatusR').removeClass('RegStatusActive');
    //     $('#CheckRegStatusU').addClass('RegStatusActive');
    //     //alert($(".ADVStatusActive").attr('value'));
    //     $( "#RegStatusKeypressU" ).keypress();
    // }
    function open_case_status() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var diary_no = $("a:focus").attr('data-diary_no');
        var diary_year = $("a:focus").attr('data-diary_year');
        //alert(diary_no+'#'+diary_year);
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,diary_no:diary_no, diary_year:diary_year},
            beforeSend: function (xhr) {
                $("#view_case_status_data").html("<div style='margin:0 auto;margin-top:20px;width:100%;text-align: center;'><img src='<?=base_url()?>/assets/images/loading-data1.gif'></div>");
            },
            url: "<?php echo base_url('case_status/defaultController/showCaseStatus'); ?>",
            success: function (data) {
                // $('#view_case_status_data').innerHTML=data;
                document.getElementById('view_case_status_data').innerHTML=data;
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
        UIkit.modal('#case_status').toggle();
    }
</script>
<script type="text/javascript">
    var app = angular.module('myApp', []);
    app.controller('myCtrl', function($scope) {
        $scope.count = 0;
    });
    $('body').attr('ng-app','casesApp').attr('ng-controller','casesController');
    var mainApp = angular.module("casesApp", []);
    mainApp.directive('onFinishRender', function ($timeout) {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                if (scope.$last === true) {
                    $timeout(function () {
                        scope.$emit(attr.onFinishRender);
                    });
                }
            }
        }
    });
    mainApp.directive('ngFocusModel',function($timeout){
        return {
            link:  function(scope, element, attrs){
                element.bind('click',function(){
                    $timeout(function () {
                        jQuery('[ng-model="'+attrs.ngFocusModel+'"]').focus();
                    });
                })
            }
        }
    });
    mainApp.directive('ngFocus',function($timeout){
        return {
            link:  function(scope, element, attrs){
                element.bind('click',function(){
                    $timeout(function () {
                        jQuery(attrs.setFocus).focus();
                    });
                })
            }
        }
    });
    mainApp.factory('LoaderService', function() {
        var loader = {
            isLoading: false
        };
        return {
            showLoader: function() {
                loader.isLoading = true;
            },
            hideLoader: function() {
                loader.isLoading = false;
            },
            isLoading: function() {
                return loader.isLoading;
            }
        };
    });
    mainApp.controller('casesController', function ($scope, $http, $filter, $interval, $compile, LoaderService) {
        $scope.page = 1;
        $scope.totalPages = 0;
        $scope.totalRecords = 0;
        $scope.params = {};
        $scope.cases = [];
        $scope.diaryEngaged = [];
        $scope.loading = true;
        $scope.loader = LoaderService;
        $scope.searchQuery = '';
        $scope.pageRange = 5;
        $scope.limit     = 10;
        $scope.countperpageArray = [10,25,50,100];
        $scope.countperpage = 10;
        $scope.filters = {
            clear_filter_status:'',
            case_status: '',
            advocate_appearing: '',
            case_registration_status:'',
            case_engaged_status:''
        };
        // Function to load data via AJAX with the selected filters
        $scope.loadData = function() {
            LoaderService.showLoader();
            $scope.params = {
                case_status: $scope.filters.case_status,
                advocate_appearing: $scope.filters.advocate_appearing,
                case_registration_status: $scope.filters.case_registration_status,
                case_engaged_status:$scope.filters.case_engaged_status,
                clear_filter_status:$scope.filters.clear_filter_status,
                search: $scope.searchQuery,
                page: $scope.page,
                limit: $scope.limit,
            };
            $http.get("<?php echo base_url('showMyCases'); ?>", { params: $scope.params })
            .then(function(response) {
                $scope.populateDataTable(response.data);
            }).catch(function(error) {
                console.error('Error fetching data:', error);
            }).finally(function() {
                LoaderService.hideLoader();
            });
        };
        $scope.populateDataTable = function(result){
            $scope.cases        = result.cases;
            $scope.diaryEngaged = result.diaryEngaged;
            $scope.totalPages   = result.totalPages
            $scope.totalRecords = result.totalRecords
        };
        $scope.changePage = function(page) {
            if (page < 1 || page > $scope.totalPages) return;
            if (page >= 1 && page <= $scope.totalPages) {
                $scope.page = page;
                $scope.loadData();
            }
        };
        $scope.fetchData = function(){
            $scope.page = 1;
            $scope.loadData();
        }
        $scope.clearFilters = function(type,value) {
            document.querySelectorAll('.filter-control').forEach(function(element) {
                if(type=='clear_filter_status' && value=='all') {
                    $scope.$apply(function () { 
                        $scope.filters[element.getAttribute('data-filter-type')] = '';
                    });
                } else if(type=='case_status' && value=='all') {
                    $scope.$apply(function () { 
                        $scope.filters[type] = '';
                    })
                } else if(type=='advocate_appearing' && value=='all') {
                    $scope.$apply(function () { 
                        $scope.filters[type] = '';
                    })
                } else if(type=='case_registration_status' && value=='all') {
                    $scope.$apply(function () { 
                        $scope.filters[type] = '';
                    })
                } else if(type=='case_engaged_status' && value=='all') {
                    $scope.$apply(function () { 
                        $scope.filters[type] = '';
                    })
                }
                $scope.filters['search']='';
                $scope.searchQuery = '';
            });
        };
        $scope.chnagePerPage = function(){
            $scope.limit = $scope.countperpage;
            $scope.loadData();
        }
        // Function to generate page numbers
        $scope.getPages = function() {
            var pages = [];
            var start = Math.max(1, $scope.page - $scope.pageRange);
            var end = Math.min($scope.totalPages, $scope.page + $scope.pageRange);
            // Add first page and "..." if needed
            if (start > 1) {
                pages.push(1);
                if (start > 2) pages.push('...');
            }
            // Add page range
            for (var i = start; i <= end; i++) {
                pages.push(i);
            }
            // Add last page and "..." if needed
            if (end < $scope.totalPages) {
                if (end < $scope.totalPages - 1) pages.push('...');
                pages.push($scope.totalPages);
            }
            return pages;
        };
        // Event listener for UIkit filter control clicks
        document.querySelectorAll('.filter-control').forEach(function(element) {
            element.addEventListener('click', function(event) {
                event.preventDefault();
                var filterType  = element.getAttribute('data-filter-type');
                var filterValue = element.getAttribute('data-filter-value');
                $scope.clearFilters(filterType,filterValue);
                // Update the filter values based on the user's selection
                $scope.$apply(function () { // Apply changes to AngularJS scope
                    if(filterValue=='all') {
                        $scope.filters[filterType] = '';
                    } else{
                        $scope.filters[filterType] = filterValue;
                    }
                    $scope.page = 1;
                });                
                if(filterType=='clear_filter_status' && filterValue=='all') {
                    $( ".case_status" ).removeClass( "uk-active" );
                    $( ".advocate_appearing" ).removeClass( "uk-active" );
                    $( ".case_registration_status" ).removeClass( "uk-active" );
                    $( ".case_engaged_status" ).removeClass( "uk-active" );
                    $("#case_status_all").addClass( "uk-active" );
                    $("#advocate_appearing_all").addClass( "uk-active" );
                    $("#case_registration_status_all").addClass( "uk-active" );
                    $("#case_engaged_status_all").addClass( "uk-active" );
                } else{
                    $( "."+filterType ).removeClass( "uk-active" );
                    $( "#"+filterType+'_'+filterValue ).addClass( "uk-active" );
                }
                $scope.loadData();
            });
        });
        // Load data initially without any filters
        $scope.loadData();
    });
</script>
<!--start other activity -->
<script type="text/javascript">
    $('#aor_name').change(function () {
        var aor_name = $(this).val();
        var resArr = aor_name.split('#$');
        $('#name').val(resArr[0]);
        $('#mobile').val(resArr[1]);
        $('#email').val(resArr[2]);
        $('#remark').val('ADVOCATE');
    });
    function send_mail() {
        var caseinfomsg=document.getElementById('caseinfosms').innerHTML;
        var recipient_mail=$('#recipient_mail').val();
        var mail_subject=$('#mail_subject').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('mycases/citation_notes/send_sms_and_mail'); ?>",
            data: {recipient_mail:recipient_mail,case_details_mail:caseinfomsg,mail_subject:mail_subject},
            beforeSend: function () {

            },
            success: function (data) {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#mail_msg').show();
                    document.getElementById('mail_msg').innerHTML="<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>";
                } else if (resArr[0] == 2) {
                    $('#mail_msg').show();
                    document.getElementById('mail_msg').innerHTML="<h4><p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p></h4>";
                    setTimeout(function () {
                        $('#mail_msg').hide();
                        UIkit.modal('#mail').toggle();
                    }, 5000);
                }
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
        return false;
    }
    function get_message_data(id, type) {
        String.prototype.ucwords = function() {
            return this.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
        }
        UIkit.modal('#mail').toggle();
        $('#emailids').html('');
        $('#recipient_mail').val('');
        var x=id.split("-");
        var diaryGet=x[0];
        var caseno=x[1];
        var pet_nameG=x[2].toLowerCase();
        var pet_name=pet_nameG.ucwords();
        var res_nameG=x[3].toLowerCase();
        var res_name=res_nameG.ucwords();
        var casesStatus=x[4];
        var dnl= diaryGet.slice(-4);
        var dnf = diaryGet.substring(0,diaryGet.length-4);
        var diary=dnf+'/'+dnl;
        if(casesStatus=='P') {
            cases_status='Pending.';
        } else{
            cases_status='Disposed.';
        }
        if(caseno=='') {
            caseno='UNREGISTERED';
        }
        var type = type;
        document.getElementById('mail_subject').value="Case Details of Diary No. "+diary;
        $('#caseinfosms').hide();
        document.getElementById('caseinfomsg').innerHTML="<b>Diary No. </b>"+diary +"<br><b> Registration No.</b>"+ caseno +"<br><b>Cause Title. </b>"+pet_name+" VS "+res_name;
        document.getElementById('caseinfosms').innerHTML="Diary No. "+diary +" Registration No."+ caseno +"Cause Title. "+pet_name+" VS "+res_name;
        var subject = diary;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, subject: subject, type: type},
            url: '<?php echo base_url('mycases/citation_notes/get_recipients_mail'); ?>',
            success: function (data) {
                var resArr = data.split('@@@');
                $('#recipient_mail1').html(resArr[0]);
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
        if (type == 'mail') {
            $('#mail_subject').val("Case No "+  caseno  +" wearing diary no "+ diary + " causetitle "+ pet_name + " vs "+ res_name + " is " +  cases_status);
            $('.msg_text').html(data);
            $('#case_details_mail').val(data);
        } else if (type == 'sms') {
            $('.msg_text').html(data);
            $('#case_details_sms').val(data);
        }
    }    
    function update_case_contacts(contact_id) {
        var dno=$('#con_diary').val();
        var cid=contact_id;
        var name=$('#p_name').val();
        var email_id=$('#p_email_id').val();
        var mobile_no=$('#p_mobile_no').val();
        var o_contact=$('#p_o_contact').val();
        var contact_type=$('#p_contact_type').val();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('mycases/citation_notes/update_case_contacts'); ?>',
            data: {contact_id:cid,diary_no:dno,name:name,email_id:email_id,mobile_no:mobile_no,o_contact:o_contact,contact_type:contact_type},
            success: function (data) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var resArr = data.split('@@@');
                    if (resArr[0] == 2) {
                        alert(resArr[1]);
                        $('#edit_contact').hide();
                        $('#new_contact').show();
                        $('#con_footer').show();
                        $('#contact_list').show();
                        get_contact_details(dno);
                    } else if (resArr[0] == 1) {
                        alert(resArr[1]);
                    }
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    function get_aor_contact_list() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('mycases/citation_notes/aor_contact_list'); ?>',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
            success: function (data) {
                $('#aor_name').html(data);
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    function get_contacts(contact_id) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('mycases/citation_notes/case_contact'); ?>',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, id: contact_id},
            success: function (data) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var resArr = data.split('@@@');
                    if (resArr[0] == 2) {
                        $('#new_contact').hide();
                        $('#con_footer').hide();
                        $('#contact_list').show();
                        $('#edit_contact').show();
                        document.getElementById('edit_contact').innerHTML=resArr[1];
                    } else if (resArr[0] == 1) {
                        alert(resArr[1]);
                    }
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    function get_contact_details(cnr_number) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $("#getCodeModal").remove();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('mycases/citation_notes/get_contact_list'); ?>',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, cnr_number: cnr_number},
            success: function (data) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var resArr = data.split('@@@');
                    if (resArr[0] == 2) {
                        document.getElementById('contact_list').innerHTML=resArr[1];
                    } else if (resArr[0] == 1) {
                        alert(resArr[1]);
                    }
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    function add_case_contact() {
        var diary_no=$('#con_diary').val();
        var name=$('#name').val();
        var email_id=$('#email').val();
        var mobile_no=$('#mobile').val();
        var o_contact=$('#other').val();
        var contact_type=$('#remark').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {diary_no:diary_no,name:name,email_id:email_id,mobile_no:mobile_no,o_contact:o_contact,contact_type:contact_type},
            url: "<?php echo base_url('mycases/citation_notes/add_case_contact'); ?>",
            success: function (data) {
                var resArr = data.split('@@@');
                if (resArr[0] == 2) {
                    alert(resArr[1]);
                    $('#name').val('');
                    $('#email').val('');
                    $('#mobile').val('');
                    $('#other').val('');
                    $('#remark').val('');
                    get_contact_details(diary_no);
                } else if (resArr[0] == 1) {
                    alert(resArr[1]);

                }
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    function show_contact_list(element) {
        if (element == 1) {
            $('#aor_contact').hide();
            document.getElementById('contact_type').value=element;
            $('#new_contact').show();
        } else if (element == 2) {
            document.getElementById('contact_type').value=element;
            $('#aor_contact').show();
            get_aor_contact_list();
        }
    }
    function save_notes() {
        var temp = document.getElementById('notes_text').value;
        var diary_no = $('#notes_d').val();
        if (temp) {
            if (temp.length > 500) {
                $('#add_notes_alerts').html('<div class="alert alert-danger">Maximum length 500 allowed.</div>');
            } else {
                $('#notes_description').val($('.editor-notes').html());
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    data:{dno:diary_no,temp:temp},
                    url: "<?php echo base_url('mycases/citation_notes/add_notes_mycases'); ?>",
                    success: function (data) {
                        var resArr = data.split('@@@');
                        getCitation_n_NotesDetails(diary_no, 2);
                        if (resArr[0] == 2) {
                            alert("Notes Added Successully");
                        } else if (resArr[0] == 1) {
                            alert(resArr[0]);   }

                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            }
        } else {
            $('#add_notes_alerts').html('<div class="alert alert-danger">Write Notes Details.</div>');
        }
    } // end of save notes function
    function update_notes() {
        var temp = document.getElementById('notes_text').value;
        var notes_id = document.getElementById('notes_id').value;
        var diary_no = $('#notes_d').val();
        if (temp) {
            if (temp.length > 500) {
                $('#add_notes_alerts').html('<div class="alert alert-danger">Maximum length 500 allowed.</div>');
            } else {
                $('#notes_description').val($('.editor-notes').html());
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    data:{notes_id:notes_id,temp:temp,dno:diary_no},
                    url: "<?php echo base_url('mycases/citation_notes/update_notes_mycases'); ?>",
                    success: function (data) {
                        var resArr = data.split('@@@');
                        getCitation_n_NotesDetails(diary_no, 2);
                        if (resArr[0] == 2) {
                            //  $("#add_notes_modal").modal("hide");
                            alert("Notes Update Successully");
                            // getCitation_n_NotesDetails(diary_no, 2);
                        } else if (resArr[0] == 1) {
                            alert(resArr[0]);
                        }
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            }
        } else {
            $('#add_notes_alerts').html('<div class="alert alert-danger">Write Notes Details.</div>');
        }
    }
    function open_citation_box(id) {
        $('#save_citation').show();
        $('#update_citation').hide();
        $('#citation_text').val('');
        $('#citation_id').val('');
        var back=404;
        if(id!=back) {
            var citationBack='<button type="button" class="uk-button uk-button-primary pull-right" onclick="open_citation_box('+back+')">Back</button>';
            $('#citationBack').html(citationBack);
            $('#citdiary').val('');
            document.getElementById('cit_diary').innerHTML="Write Citation Details for Diary no "+id;
            document.getElementById('citdiary').value=id;
            document.getElementById('citation_text').innerHTML='';
            document.getElementById('view_citation_text').innerHTML='';
            document.getElementById('view_citation_data').innerHTML='';
            //alert( "diary no is "+id);
            getCitation_n_NotesDetails(id, 1);
            UIkit.modal('#citations').toggle();
        } else {
           // alert('else='+back);
            return true;
        }
    }
    function edit_citation_n_notes(citation_text,id,desc_type) {
        //alert('citation_text=' + citation_text +'id='+ id + 'desc_type=' +desc_type);
        if(desc_type==1) { //only citation edit form
            //getCitation_n_NotesDetails(cnr_number, desc_type);
            $('#citation_text').val('');
            $('#citation_id').val('');
            $('#save_citation').hide();
            $('#update_citation').show();
            $('#citation_text').val(citation_text);
            $('#citation_id').val(id);
        } else{ //only notes edit form
            $('#notes_text').val('');
            $('#notes_id').val('');
            $('#save_notes').hide();
            $('#update_notes').show();
            $('#notes_text').val(citation_text);
            $('#notes_id').val(id);
        }
    }
    function open_mail_box(id) {
        document.getElementById('mail_d').innerHTML=id;
        UIkit.modal('#mail').toggle();
    }
    function open_contact_box(id) {
        document.getElementById('con_diary').value=id;
        document.getElementById('con_d').innerHTML=id;
        $('#new_contact').show();
        $('#edit_contact').hide();
        $('#aor_contact').hide();
        get_contact_details(id);
        UIkit.modal('#contacts').toggle();
    }
    function open_notes_box(id) {
        $('#save_notes').show();
        $('#update_notes').hide();
        var back=404;
        if(id!=back){
            var notesBack='<button type="button" class="uk-button uk-button-primary pull-right" onclick="open_notes_box('+back+')">Back</button>';
            $('#notesBack').html(notesBack);
            document.getElementById('notes_diary').innerHTML="Write Notes Details for Diary no "+id;
            document.getElementById('notes_d').value=id;
            document.getElementById('view_notes_text').innerHTML='';
            document.getElementById('view_notes_data').innerHTML='';
            getCitation_n_NotesDetails(id, 2);
            UIkit.modal('#notes').toggle();
        } else {
            $('#notes_text').val('');
            return true;
        }
    }
    function whatsapp_openWin(id,val) {
        var x=val.split("-");
        var diary=x[0];
        var caseno=x[1];
        var pet_name=x[2];
        var res_name=x[3];
        if(caseno=='') {
            caseno='unregistered';
        }
        var whatsapp_message ="Diary No. "+diary +" Registration No."+ caseno +"Cause Title. "+pet_name+" VS "+res_name;
        var api_url = 'https://api.whatsapp.com/send?phone=&text=';
        myWindow = window.open(api_url + whatsapp_message, "_blank", "width=800,height=600");
    }
    function closeWin() {
        myWindow.close();
    }
    function view_contact_details(diary_no) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#add_contact_model').modal("show");
        $('.view_contact_list').html("");
        $('#diary_number').val(diary_no);
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('mycases/citation_notes/view_contact_list'); ?>',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, diary_no: diary_no},
            success: function (data) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var resArr = data.split('@@@');
                    if (resArr[0] == 2) {
                        $('.view_contact_list').html(resArr[1]);
                        $('.add_diary_contact_form').hide();
                    } else if (resArr[0] == 1) {
                        $('.add_diary_contact_form').show();
                    }
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    function save_citation() {
        var temp = document.getElementById('citation_text').value;
        if (temp) {
            if (temp.length > 500) {
                $('#add_citation_alerts').html('<div class="alert alert-danger">Maximum length 500 allowed.</div>');
            } else {
                // $('#citation_description').val($('.editor-citation').html());
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                //var diary_no = $('#citation_cnr_no').val();
                var diary_no = $('#citdiary').val();
                $.ajax({
                    type: "POST",
                    data:{dno:diary_no,temp:temp},
                    url: "<?php echo base_url('mycases/citation_notes/add_citation_mycases'); ?>",
                    success: function (data) {
                        var resArr = data.split('@@@');
                        if (resArr[0] == 2) {
                            alert(resArr[1]);
                            getCitation_n_NotesDetails(diary_no, 1);
                        } else if (resArr[0] == 1) {
                            alert(resArr[1]);
                        }
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            }
        } else {
            $('#add_citation_alerts').html('<div class="alert alert-danger">Write Citation Details.</div>');
        }
    } //  end of function
    function update_citation() {
        var temp = document.getElementById('citation_text').value;
        var citation_id = document.getElementById('citation_id').value;
        if (temp) {
            if (temp.length > 500) {
                $('#add_citation_alerts').html('<div class="alert alert-danger">Maximum length 500 allowed.</div>');
            } else {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                var diary_no = $('#citdiary').val();
                $.ajax({
                    type: "POST",
                    data:{citation_id:citation_id,temp:temp,dno:diary_no},
                    url: "<?php echo base_url('mycases/citation_notes/update_citation_mycases'); ?>",
                    success: function (data) {
                        var resArr = data.split('@@@');
                        if (resArr[0] == 2) {
                            alert(resArr[1]);
                            getCitation_n_NotesDetails(diary_no, 1);
                        } else if (resArr[0] == 1) {
                            alert(resArr[1]);
                        }
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            }
        } else {
            $('#add_citation_alerts').html('<div class="alert alert-danger">Write Citation Details.</div>');
        }
    }
    function getCitation_n_NotesDetails(cnr_number, desc_type) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $("#getCodeModal").remove();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('mycases/citation_notes/get_citation_and_notes_list'); ?>',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, type: desc_type, cnr_number: cnr_number},
            success: function (data) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var resArr = data.split('@@@');
                    document.getElementById('view_citation_text').innerHTML="<b><font color ='red'>Already Added Citation</font></b>";
                    document.getElementById('view_notes_text').innerHTML="<b><font color ='red'>Already Added Notes</font></b>";
                    document.getElementById('view_citation_data').innerHTML=resArr[1];
                    document.getElementById('view_notes_data').innerHTML=resArr[1];
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    function updateTextArea(id,email_checked) {
        const resultEl = document.getElementById('emailids');
        resultEl.innerHTML = Array.from(document.querySelectorAll('input[name="emailMulti"]'))
            .filter((checkbox) => checkbox.checked)
            .map((checkbox) => checkbox.value);
        var emailid = $('#emailids').html();
        $('#recipient_mail').val(emailid);
    }
    function updatemobArea(id,mob_checked) {
        var mob='';
        if (mob_checked.is(':checked')) {
            mob = $('#mob_' + id).val();
        }
        if (mob_checked.is(':unchecked')) {
            var uncheck =$('#mob_' + id).val();
        }
        $('#mobids').append(mob + ',');
        var em = $('#mobids').html();
        if (em.indexOf(uncheck) != -1) {
            em = em.replace(uncheck+',', '');
            $('#mobids').html(em);
        } else{
            $('#mobids').html(em);
        }
        var mobno = $('#mobids').html();
        $('#recipient_no').val(mobno);
    }
    function delete_citation_n_notes(cino, date, type) {
        y = confirm("Are you sure want to delete ?");
        if (y == false) {
            return false;
        }
        if(type=='1') {
            var diary_no = $('#citdiary').val();
        }
        if(type=='2') {
            var diary_no = $('#notes_d').val();
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('.modal').remove();
        $('.modal-backdrop').remove();
        $('.body').removeClass("modal-open");
        $.ajax({
            type: 'POST',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, cino: cino, date: date, type: type,diary_no:diary_no},
            url: '<?php echo base_url('mycases/citation_notes/delete_citation_n_notes'); ?>',
            success: function (data) {
                $("#getCodeModal").remove();
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg_modal').show();
                    alert( resArr[1]);
                    setTimeout(function () {
                        $('#msg_modal').hide();
                    }, 3000);
                } else if (resArr[0] == 2) {
                    //$('#msg_modal').show();
                    $('#save_citation').show();
                    $('#update_citation').hide();
                    $('#citation_text').val('');
                    $('#citation_id').val('');
                    $('#notes_text').val('');
                    $('#save_notes').show();
                    $('#update_notes').hide();
                    alert(resArr[1]);
                    getCitation_n_NotesDetails(diary_no,type);
                }
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    function delete_contacts(id,diary_no) {
        y = confirm("Are you sure want to delete ?");
        if (y == false) {
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('.modal').remove();
        $('.modal-backdrop').remove();
        $('.body').removeClass("modal-open");
        $.ajax({
            type: 'POST',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, id: id,diary_no:diary_no},
            url: '<?php echo base_url('mycases/citation_notes/delete_contacts'); ?>',
            success: function (data) {
                $("#getCodeModal").remove();
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg_modal').show();
                    alert( resArr[1]);
                    setTimeout(function () {
                        $('#msg_modal').hide();
                    }, 3000);
                } else if (resArr[0] == 2) {
                    alert(resArr[1]);
                    get_contact_details(diary_no);
                }
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
</script>
<!--end other activity -->
@endpush