@extends('layout.advocateApp')
@section('content')
<div class="container-fluid">
    <div class="row card">
        <div class="col-lg-12">
            <div class="card-body">
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="result" class="text-center"></div>
                            <div class="loader_div" id="loader_div"></div>
                            <div class="dash-card">
                                <div class="title-sec">
                                    <h5 class="unerline-title">Defect Petition Documents Report</h5>
                                    @if(!empty(getSessionData('login')))
                                        <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                    @endif
                                </div>
                                {{-- Page Title End --}}
                                {{-- Main Start --}}
                                <div class="uk-margin-small-top uk-border-rounded">
                                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                            <tr class="success">
                                                <th>S.N.</th>
                                                <th>Defect Label </th>
                                                <th>Defect Status </th>
                                                <th>Defect Affected Pages </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($data['defect_list'])) {
                                                $sr = 1;
                                                $defect_affected_pages = $defect_affected_pages_1 = $defect_affected_pages_2 = $defect_status = '';
                                                foreach ($data['defect_list'] as $json) {
                                                    if (!empty($json['defect_data'])) {
                                                        if (is_array($json['defect_data']['defect_affected_pages'])) {
                                                            if (isset($json['defect_data']['defect_affected_pages']['system_truth'])) {

                                                            } else{
                                                                $defect_affected_pages_1 = isset($json['defect_data']['defect_affected_pages'][0]) ? $json['defect_data']['defect_affected_pages'][0] : NULL;
                                                                $defect_affected_pages_2 = isset($json['defect_data']['defect_affected_pages'][1]) ? $json['defect_data']['defect_affected_pages'][1] : NULL;
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?= $sr++; ?></td>
                                                        <td><?= $json['defect_label']; ?></td>
                                                        <td><?= $json['defect_data']['defect_status'];?></td>
                                                        <td>
                                                            <?= !empty($defect_affected_pages_1) && !empty($defect_affected_pages_2) ? $defect_affected_pages_1.'/'.$defect_affected_pages_2 : $defect_affected_pages_1.$defect_affected_pages_2; ?>
                                                        </td>
                                                    </tr>
                                                    <?php
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