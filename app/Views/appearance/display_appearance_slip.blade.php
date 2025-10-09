<style>
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
    <div class="container">
        <div class="card">
            <div class="card-header">
                <span class="float-right"> <strong>Status:</strong> Submitted Successfully</span>
            </div>
            <div class="card-body mt-3">
                <div class="row justify-content-center col-sm-12">I certify that following Senior Advocates/Advocates will appear/appeared in the below mentioned matter.</div>
                <div class="row mb-4 mt-3">
                    <div class="col-sm-12">
                        <div>List Date : <strong>{{date('d-m-Y', strtotime($posted_data['next_dt']))}}</strong></div>
                        <div>Court No. : <strong>{{$posted_data['courtno']}}</strong></div>
                        <div>Item No. : <strong>{{$posted_data['brd_slno']}}</strong></div>
                        <div>Case No. : <strong>{{$posted_data['case_no']}}</strong></div>
                        <div>Title : <strong>{{$posted_data['cause_title']}}</strong></div>
                        <div>Appearing For : <strong>{{$posted_data['appearing_for'] == 'P' ? 'Petitioner' : 'Respondent'}}</strong></div>
                    </div>
                </div>
                <?php $sno = 1; ?>
                @if($data['slip_data'])
                    <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th>Name of Advocates</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['slip_data'] as $added_data)
                                    <tr>
                                        <td data-key="#" class="center">{{$sno++}}</td>
                                        <td data-key="Name of Advocates" class="left strong">{{$added_data->advocate_title.' '.$added_data->advocate_name.', '.$added_data->advocate_type}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    No Advocates Found
                @endif
                <div class="row">
                    <div class="col-12 text-right" style="text-align: right!important;">
                        <?= str_replace(' @ ','.',ucwords(strtolower(str_replace('.',' @ ', getSessionData('login.first_name').' '.getSessionData('login.last_name'))))); ?>
                        <br>
                        For the {{$posted_data['appearing_for'] == 'P' ? 'Petitioner' : 'Respondent'}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer justify-content-between" style="float:right !important;">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
</div>