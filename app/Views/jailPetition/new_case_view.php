<!-- page content -->

<?php $this->load->view('jailPetition/new_case_breadcrumb'); ?>

<?php

if ($this->uri->segment(2) == 'BasicDetails') {

    $this->load->view('jailPetition/case_details_view');

} elseif ($this->uri->segment(2) == 'Extra_petitioner') {

    $this->load->view('jailPetition/extra_petitioner_view');

}  elseif ($this->uri->segment(1) == 'uploadDocuments') {

    $this->load->view('uploadDocuments/upload_document');

} elseif ($this->uri->segment(2) == 'Subordinate_court') {

    $this->load->view('jailPetition/subordinateCourt_view');

}   elseif ($this->uri->segment(1) == 'affirmation') {

    $this->load->view('affirmation/affirmation_view');

} elseif ($this->uri->segment(2) == 'view') {

    $this->load->view('jailPetition/new_case_preview');
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
    $('#petitioner_dob').datepicker({
        onSelect: function (value) {
            var parts = value.split("/");
            var day = parts[0] && parseInt(parts[0], 10);
            var month = parts[1] && parseInt(parts[1], 10);
            var year = parts[2] && parseInt(parts[2], 10);
            var str = month + '/' + day + '/' + year;
            var today = new Date(),
                dob = new Date(str),
                age = new Date(today - dob).getFullYear() - 1970;
            $('#petitioner_age').val(age);
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