@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-section dashboard-tiles-area"></div>
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                                {{-- Page Title Start --}}
                                <div class="title-sec">
                                    <h5 class="unerline-title"> User List </h5>
                                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                                {{-- Page Title End --}}
                                {{-- Main Start --}}
                                <div class="table-sec">
                                    <div class="table-responsive">
                                        <table id="datatable-responsive" class="table table-striped custom-table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Emp ID</th>
                                                    <th>Assigned File Type</th>
                                                    <th>User Type</th>
                                                    <th>Present/Absent</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                if (isset($users) && !empty($users)) {
                                                    foreach ($users as $k => $v) {
                                                        $first_name = !empty($v->first_name) ? $v->first_name : '';
                                                        $emailid = !empty($v->emailid) ? $v->emailid : '';
                                                        $emp_id = !empty($v->emp_id) ? $v->emp_id : '';
                                                        $attend = !empty($v->attend) && $v->attend == 'P' ? 'Present' : 'Absent';
                                                        $pp_a = !empty($v->pp_a) && $v->pp_a == 'P' ? 'Party In Person' : 'Advocate';
                                                        $efiling_type = !empty($v->efiling_type) ? $v->efiling_type : '';
                                                        $user_id = !empty($v->user_id) ? $v->user_id : '';
                                                        $roleText = '';
                                                        if (isset($efiling_type) && !empty($efiling_type) && $efiling_type != 'NULL') {
                                                            $fileType = explode(',', $efiling_type);
                                                            foreach ($fileType as $k => $v) {
                                                                $roleText .= str_replace('_', ' ', strtoupper($v)) . '</br>';
                                                            }
                                                        } elseif ($efiling_type == 'NULL') {
                                                            $roleText = '---';
                                                        }
                                                        echo '<tr>
                                                            <td data-key="#">' . $i . '</td>
                                                            <td data-key="Name">' . strtoupper($first_name) . '</td>
                                                            <td data-key="Emp ID">' . $emp_id . '</td>
                                                            <td data-key="Assigned File Type">' . strtoupper($roleText) . '</td>
                                                            <td data-key="User Type">' . strtoupper($pp_a) . '</td>
                                                            <td data-key="Present/Absent">' . strtoupper($attend) . '</td>
                                                        </tr>';
                                                        $i++;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
<script>
    $(document).ready(function() {
        $("#datatable-responsive").DataTable({
            "ordering": false,
        });
    });
</script>
@endpush