@if($previous_list_advocates)
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Advocates Master List</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table first-th-left dt-responsive nowrap" id="datatable-responsive">   
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="chkall" id="chkall" value="ALL" onClick="chkall1(this);" /></th>
                                    <th>All</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($previous_list_advocates as $row)
                                    <tr>
                                        <td data-key="">
                                            <input type="checkbox" id="chk_master_list_id" name="chk_master_list_id" value="{{$row->id}}" />
                                        </td>
                                        <td data-key="All">{{$row->advocate_title.' '.$row->advocate_name.', '.$row->advocate_type}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td data-key="" colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" 
                            data-appearing_for="{{$data['appearing_for']}}" 
                            data-courtno="{{$data['courtno']}}"
                            data-brd_slno="{{$data['brd_slno']}}"
                            data-next_dt="{{$data['next_dt']}}"
                            data-diary_no="{{$data['diary_no']}}" 
                            class="btn btn-dark btn-block master_list_submit">
                            Add
                    </button>
                </div>           

            </div>
        </div>
    </div>

@else
        <span class="text-danger">No Records Found</span>
@endif



