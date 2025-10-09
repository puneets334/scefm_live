<!-- page content -->

<?php
if(!empty($_SESSION['efiling_details']['ref_m_efiled_type_id']) && $_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT)
    // for caveat
    $this->load->view('newcaseQF/caveat/caveat_breadcrumb');
else
    $this->load->view('newcaseQF/new_case_breadcrumb');
?>

<?php

if ($this->uri->segment(2) == 'caseDetails') {
    
    $this->load->view('newcaseQF/case_details_view');
    
} elseif ($this->uri->segment(2) == 'petitioner') {
    
    $this->load->view('newcaseQF/petitioner_view');
    
} elseif ($this->uri->segment(2) == 'respondent') { 
    
    $this->load->view('newcaseQF/respondent_view');
    
} elseif ($this->uri->segment(2) == 'extra_party') {
    
    $this->load->view('newcaseQF/extra_party_view');
    
} elseif ($this->uri->segment(2) == 'lr_party') {
    
    $this->load->view('newcaseQF/lr_party_view');
    
} elseif ($this->uri->segment(2) == 'actSections') {
   
    $this->load->view('newcaseQF/act_sections_view');
    
} elseif ($this->uri->segment(2) == 'uploadDocuments') {

    $this->load->view('newcaseQF/uploadDocuments/upload_document');
    
} elseif ($this->uri->segment(1) == 'documentIndex') {
    
    $this->load->view('documentIndex/documentIndex_view');
    
} elseif ($this->uri->segment(2) == 'subordinate_court') {
    
    $this->load->view('newcaseQF/subordinateCourt_view');
    
}   elseif ($this->uri->segment(1) == 'affirmation') {
    
    $this->load->view('affirmation/affirmation_view');
        
} elseif ($this->uri->segment(2) == 'courtFee') {

    $this->load->view('newcaseQF/courtFee_view');
    
} elseif ($this->uri->segment(2) == 'view') {
    
    $this->load->view('newcaseQF/new_case_preview');
}
?>
</div>
</div>
</div>
</div>             
</div>
</div>
</div>


<script>
    $('#party_dob').datepicker({
        onSelect: function (value) {
            var parts = value.split("/");
            var day = parts[0] && parseInt(parts[0], 10);
            var month = parts[1] && parseInt(parts[1], 10);
            var year = parts[2] && parseInt(parts[2], 10);
            var str = month + '/' + day + '/' + year;
            var today = new Date(),
                    dob = new Date(str),
                    age = new Date(today - dob).getFullYear() - 1970;
            $('#party_age').val(age);
        },
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:-1",
        dateFormat: "dd/mm/yy",
        defaultDate: '-40y'
    });

    
    $('#cause_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",
        maxDate: new Date

    });
    $('#filing_date,#order_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",
        maxDate: new Date
    });
    $('#decision_date,#cc_applied_date,#cc_ready_date,#offence_date,#charge_sheet_date,#accident_date,#fir_file_date,\n\
       #trial_decision_date,#trial_cc_applied_date,#trial_cc_ready_date,#hc_decision_date,#hc_cc_applied_date,\n\
       #hc_cc_ready_date,#app_case_order_dt,#trial_case_order_dt').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",
        maxDate: new Date
    });
    
</script>