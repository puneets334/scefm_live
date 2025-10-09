@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="dashboard-section">
            <div class="col-lg-12 col-xs-12">
                <div class="" id="msg">
                 <?php 
                 $session = session();
                 if ($session->getFlashdata('MSG')) {
                    echo '<div class="alert alert-danger">'.$session->getFlashdata('MSG').'</div>';
                }
                ?>
                 <?php 
                 $session = session();
                 if ($session->getFlashdata('success')) {
                    echo '<div class="alert alert-success">'.$session->getFlashdata('success').'</div>';
                }
                ?>
            </div>
            
            <div class="dash-card">
                <div class="col-lg-12 col-xs-12">
                    <h4 class="title1"> Change Case Status </h4>
                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right mb-3" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                </div>
                <div class="table-responsiv">
                    <table id="datatable-responsive" class="table table-striped table-border custom-table" cellspacing="0" width="100%">
                        <thead>
                            <tr class="success input-sm">
                                <th>Sno</th>
                                <th>Filing Number</th>
                                <th>Current Status</th>
                                <th>Status To Be Updated and Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($status_that_can_be_changed as $s) {

                                if (($s['stage_id'] == Initial_Defected_Stage || $s['stage_id'] == DEFICIT_COURT_FEE || $s['stage_id'] == Transfer_to_IB_Stage || $s['stage_id'] == LODGING_STAGE || $s['stage_id'] == MARK_AS_ERROR)) {
                                    echo '<tr>';
                                    echo '<td data-key="Sno">' . ++$i . '</td>';
                                    echo '<td data-key="Filing Number">' . efile_preview($s['efiling_no']) . '</td>';

                                    if ($s['stage_id'] == Initial_Defected_Stage)
                                        echo '<td data-key="Current Status">' . 'For Compliance' . '</td>';
                                    else if ($s['stage_id'] == DEFICIT_COURT_FEE)
                                        echo '<td data-key="Current Status">' . 'Pay Deficit Fee' . '</td>';
                                    else if ($s['stage_id'] == Transfer_to_IB_Stage)
                                        echo '<td data-key="Current Status">' . 'Transfer to CIS' . '</td>';
                                    else if ($s['stage_id'] == MARK_AS_ERROR)
                                        echo '<td data-key="Current Status">' . 'Mark as error' . '</td>';
                                    else
                                        echo '<td data-key="Current Status">' . 'Idle / Unprocessed' . '</td>';
                                    echo '<td data-key="Status To Be Updated and Remarks">';
                                    $attribute = array('class' => 'form-horizontal form-label-left', 'name' => 'status_change', 'id' => 'status_change', 'autocomplete' => 'off');
                                    echo form_open('admin/supadmin/final_case_status_change', $attribute);
                                    echo '<div class="row"><div class="col-12 col-sm-12 col-md-4 col-lg-4"><select class="form-select cus-form-ctrl" id="to_change_status_id" name="to_change_status_id" required="required">
                                    <option disabled="disabled" selected="selected">Select Status to be Changed</option>';
                                    echo $s['last_stage_to_be_updated'];
                                    if ($s['last_stage_to_be_updated'] == New_Filing_Stage) {
                                        setSessionData('last_stage_to_be_updated', $s['last_stage_to_be_updated']);
                                            // $this->session->set(array(
                                            //     'last_stage_to_be_updated' => $s['last_stage_to_be_updated']));

                                        echo '<option value="' . htmlentities($s['last_stage_to_be_updated'], ENT_QUOTES) . '">New Filing Stage</option>';
                                    }
                                    if ($s['last_stage_to_be_updated'] == Initial_Defects_Cured_Stage) {
                                        setSessionData('last_stage_to_be_updated',$s['last_stage_to_be_updated']);
                                            // $this->session->set(array(
                                            //     'last_stage_to_be_updated' => $s['last_stage_to_be_updated']));
                                        echo '<option value="' . htmlentities($s['last_stage_to_be_updated'], ENT_QUOTES) . '">Refiling Stage</option>';
                                    }
                                    if ($s['last_stage_to_be_updated'] == Draft_Stage) {
                                     setSessionData('last_stage_to_be_updated', $s['last_stage_to_be_updated']);
                                            // $this->session->set(array(
                                            //     'last_stage_to_be_updated' => $s['last_stage_to_be_updated']));
                                     echo '<option value="' . htmlentities($s['last_stage_to_be_updated'], ENT_QUOTES) . '">Draft Stage</option>';
                                 }
                                 echo '</select></div>';

                                 echo '&nbsp;&nbsp;<div class="col-12 col-sm-12 col-md-4 col-lg-4"><input type="text" class="form-control cus-form-ctrl" id="remark" name="remark" required="required" placeholder=" Remarks "></div>';
                                 echo '&nbsp;&nbsp;<div class="col-12 col-sm-12 col-md-4 col-lg-3"><button type="submit" class="quick-btn" name="submit" >Change Status</button></div>';
                                    // <input type="submit" class="btn  quick-btn" name="submit" value="Change Status">';

                                 echo form_close();
                                 echo '</div>';
                                 echo '</td>';
                                 echo '</tr>';
                             } else if (( $s['stage_id'] == Transfer_to_Dealing_Section)) {

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
@endsection
@push('script')
@endpush