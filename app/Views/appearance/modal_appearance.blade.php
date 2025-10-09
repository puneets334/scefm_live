
<link href="<?= base_url() ?>calender/all.min.css" rel="stylesheet">
<style>
    .callout-danger{border-left-color:#bd2130}
    .callout{border-radius:.25rem;box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);background-color:#fff;border-left:5px solid #e9ecef;margin-bottom:1rem;padding:1rem}.callout a{color:#495057;text-decoration:underline}.callout a:hover{color:#e9ecef}.callout p:last-child{margin-bottom:0}.callout.callout-danger{border-left-color:#bd2130}.callout.callout-warning{border-left-color:#d39e00}.callout.callout-info{border-left-color:#117a8b}.callout.callout-success{border-left-color:#1e7e34}.dark-mode .callout{background-color:#3f474e}.dark-mode .callout.callout-danger{border-left-color:#ed7669}.dark-mode .callout.callout-warning{border-left-color:#f5b043}.dark-mode .callout.callout-info{border-left-color:#5faee3}.dark-mode .callout.callout-success{border-left-color:#00efb2}
    a {text-decoration: none;color: #393939;}
    .badge-light{color:#1f2d3d;background-color:#f8f9fa}a.badge-light:focus,a.badge-light:hover{color:#1f2d3d;background-color:#dae0e5}a.badge-light.focus,a.badge-light:focus{outline:0;box-shadow:0 0 0 .2rem rgba(248,249,250,.5)}.badge-dark{color:#fff;background-color:#343a40}a.badge-dark:focus,a.badge-dark:hover{color:#fff;background-color:#1d2124}a.badge-dark.focus,a.badge-dark:focus{outline:0;box-shadow:0 0 0 .2rem rgba(52,58,64,.5)}.jumbotron{padding:2rem 1rem;margin-bottom:2rem;background-color:#e9ecef;border-radius:.3rem}@media (min-width:576px){.jumbotron{padding:4rem 2rem}}.jumbotron-fluid{padding-right:0;padding-left:0;border-radius:0}.alert{position:relative;padding:.75rem 1.25rem;margin-bottom:1rem;border:1px solid transparent;border-radius:.25rem}.alert-heading{color:inherit}    
    .fontIcon {
        float: right; color: #393939 !important; font-size: 25px; position: relative; bottom: 30px;
    }
    .close {
        margin-left: 72% !important;
    }
</style>
<div class="modal-header">
    <h4 class="modal-title"><b>Appearance Slip</b></h4>
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    @if($posted_data['next_dt'] == CURRENT_DATE && date('H:i:s') > APPEARANCE_ALLOW_TIME)
        <div class="row">
            <div class="col-12">
                <div class="callout callout-danger">
                    You have already submited your appearance slip and forwarded to court master of court room no. {{ $posted_data['courtno'] == '21' ? 'Registrar Court' : $posted_data['courtno'] }}.
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="callout callout-danger">
                    The appearance should be updated by <?php echo APPEARANCE_ALLOW_TIME_STRING; ?> on the day when the matter is listed.
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-body box-profile">
                        <ul class="list-group mb-3">
                            <li class="list-group-item">
                                <b>List Date : </b> <a class="float-right">{{date('d-m-Y', strtotime($posted_data['next_dt']))}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Court No. : </b> <a class="float-right">{{$posted_data['courtno']}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Item No. : </b> <a class="float-right">{{$posted_data['brd_slno']}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Case No. : </b> <a class="float-right">{{$posted_data['reg_no_display'] ?: 'Diary No. '.$posted_data['diary_no']}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Title : </b> <a class="float-right">{{$posted_data['pet_name'].' Vs. '.$posted_data['res_name']}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Appearing For : </b> <a class="float-right">{{$posted_data['appearing_for'] == 'P' ? 'Petitioner' : 'Respondent'}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @if($posted_data['next_dt'] == CURRENT_DATE && date('H:i:s') > APPEARANCE_ALLOW_TIME)
            <span class="badge badge-danger"><?= MSG_TIME_OUT; ?></span>
        @else
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Add - Name of Advocates </h3>                          
                            <div class="card-tools">
                                <span title="Master List - You may add previously added name of advocates"  >
                                    <a href="#"
                                    data-diary_no="{{$posted_data['diary_no']}}"
                                    data-next_dt="{{$posted_data['next_dt']}}"
                                    data-appearing_for="{{$posted_data['appearing_for']}}"
                                    data-courtno="{{$posted_data['courtno']}}"
                                    data-brd_slno="{{$posted_data['brd_slno']}}"
                                    class="fa-solid fa-address-book add_from_case_advocate_master_list text-dark fontIcon" ></a>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <form>
                                <input type="hidden" name="csrf_test_name" value="{{ csrf_token() }}">
                                <div class="input-group">
                                    <select class="form-control cus-form-ctrl mb-2" id="advocate_type" name="advocate_type" aria-labelledby="advocate_type_addon" required>
                                        <option value='Adv.'>Advocate</option>
                                        <option value='Sr. Adv.'>Sr. Advocate</option>
                                        <option value='AOR'>AOR</option>
                                        <option value='Attorney General for India'>Attorney General for India</option>
                                        <option value='Solicitor General'>Solicitor General</option>
                                        <option value='A.S.G.'>A.S.G.</option>
					                    <option value='Advocate General'>Advocate General</option>
                                        <option value='Sr. A.A.G.'>Sr. A.A.G.</option>
                                        <option value='A.A.G.'>A.A.G.</option>
                                        <option value='D.A.G.'>D.A.G.</option>
                                        <option value='Amicus Curiae'>Amicus Curiae</option>
                                    </select>
                                    <select class="form-control cus-form-ctrl mb-2" id="advocate_title" name="advocate_title" aria-labelledby="advocate_title_addon" required>
                                        <option value=''>-Title-</option>
                                        <option value='Mr.'>Mr.</option>
                                        <option value='Mrs.'>Mrs.</option>
                                        <option value='Ms.'>Ms.</option>
                                        <option value='M/s'>M/S</option>
                                        <option value='Dr.'>Dr.</option>
                                        <option value='None'>None</option>
                                    </select>
                                    <input type="text" name="advocate_name" id="advocate_name" placeholder="Type Name ..." class="form-control cus-form-ctrl">
                                    <span class="input-group-append">
                                        <button type="button"
                                        data-diary_no="{{$posted_data['diary_no']}}"
                                        data-next_dt="{{$posted_data['next_dt']}}"
                                        data-appearing_for="{{$posted_data['appearing_for']}}"
                                        data-courtno="{{$posted_data['courtno']}}"
                                        data-brd_slno="{{$posted_data['brd_slno']}}"
                                        name="btn_save" type="button" class="btn_save btn quick-btn mt-2">Add</button>
                                    </span>
                                    <span class="load_process"></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row display_master_list justify-content-center"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">List of Advocates Added</h3>
                        </div>
                        <div class="card-body p-0">                            
                            <table class="table_added_advocates table table-sortable" >
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Advocate Name</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="sortable">
                                    @if($data['added_data'])
                                        @foreach($data['added_data'] as $added_data)
                                            <tr>
                                                <td data-key="#">
                                                    <span class="drag_to_sort fas fa-arrows-alt"></span>
                                                    <input type="hidden" name="sortable_id[]" value="{{$added_data->id}}" />
                                                </td>
                                                <td data-key="Advocate Name">{{$added_data->advocate_title.' '.$added_data->advocate_name.', '.$added_data->advocate_type}}</td>
                                                <td data-key="" class="text-right py-0 align-middle">
                                                    <span class="badge badge-light">{{date('d-m-Y h:i:s A', strtotime($added_data->entry_date))}}</span>
                                                    <div class="btn-group btn-group-sm advocate_remove_{{$added_data->id}}">
                                                        <a href="#" data-next_dt="{{$posted_data['next_dt']}}" data-id="{{$added_data->id}}" data-is_active="{{$added_data->is_active}}" class="@if($added_data->is_active == 't') btn-danger @else btn-warning @endif btn advocate_remove" title="@if($added_data->is_active == 't') Click to Remove @else Click to Restore @endif">
                                                            @if($added_data->is_active == 't')
                                                                <i class="fa fa-trash"></i></a>
                                                        @else
                                                            <img src="<?php echo base_url('webfonts/reload.png'); ?>" alt="Click to Restore" style="width: 20px; height: 20px;"></a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                    @endif
                                </tbody>
                            </table>                            
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="callout callout-danger">
                    <div class="d-flex">
                        <div class="d-inline-block mt-1">
                            <input type="checkbox" style="min-width: 20px" value="1" class="form-control" name="certify_check_box" id="certify_check_box" />
                        </div>
                        <div class="d-inline-block ml-2"> I certify that above Senior Advocates/Advocates will appear/appeared in the above mentioned matter. </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-right" style="text-align: right!important;">
                <?= str_replace(' @ ','.',ucwords(strtolower(str_replace('.',' @ ', getSessionData('login.first_name').' '.getSessionData('login.last_name'))))); ?>
                <br>
                For the {{$posted_data['appearing_for'] == 'P' ? 'Petitioner' : 'Respondent'}}
            </div>
        </div>
    </div>
@endif
<div class="modal-footer justify-content-between" style="float:right !important;">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
    <?php if(($posted_data['next_dt'] > CURRENT_DATE) || ($posted_data['next_dt'] == CURRENT_DATE) && date('H:i:s') <= APPEARANCE_ALLOW_TIME) { ?>
        <button type="button" class="btn btn-primary final-submit"
            data-diary_no="{{$posted_data['diary_no']}}"
            data-next_dt="{{$posted_data['next_dt']}}"
            data-appearing_for="{{$posted_data['appearing_for']}}"
            data-courtno="{{$posted_data['courtno']}}"
            data-brd_slno="{{$posted_data['brd_slno']}}"
            data-case_no="{{$posted_data['reg_no_display'] ?: 'Diary No. '.$posted_data['diary_no']}}"
            data-cause_title="{{$posted_data['pet_name'].' Vs. '.$posted_data['res_name']}}"
        >Submit</button>
    <?php } ?>
</div>
<script>
    $(function() {
        $(".display_master_list").hide();

        $('.table-sortable tbody').sortable({
            handle: 'span'
        });
    });
</script>