

<?php
if (!empty(getSessionData('efiling_details')) && !strpos(getSessionData('efiling_details')['breadcrumb_status'], '8')) {
    $s = 'CASE DATA ENTRY';
} else {
    $s = 'E FILE';
}
$allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, JAIL_SUPERINTENDENT);
$allowed_users_trash = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
if (!empty(getSessionData('login')) && in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
    $stages_array = array(Draft_Stage);
    if (!empty(getSessionData('efiling_details')) && in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
        if(!empty(getSessionData('efiling_details')) && getSessionData('efiling_details')['ref_m_efiled_type_id']==1){
            if (in_array(NEW_CASE_COURT_FEE, explode(',', getSessionData('efiling_details')['breadcrumb_status']))) {
                if(getSessionData('login')['ref_m_usertype_id']==USER_ADVOCATE) {
                    ?>
                <button class="btn btn-success btn-sm" id='efilaor'> SUBMIT FOR EFILING </button>
            <?php }
                else{
                    ?>
                    <button class="btn btn-success btn-sm" id='efilpip'> SUBMIT FOR EFILING </button>
                        <?php
                }
                }
        }
        else{
            if (!empty(getSessionData('efiling_details')) && in_array(JAIL_PETITION_SUBORDINATE_COURT, explode(',', getSessionData('efiling_details')['breadcrumb_status']))) {
                ?><a href="<?php echo base_url('jailPetition/FinalSubmit') ?>" class="btn btn-success btn-sm" id='jail'>
                    SUBMIT FOR EFILING</a>
            <?php }
        }
    }
    $stages_array = array(Initial_Defected_Stage, I_B_Defected_Stage);
    if (!empty(getSessionData('efiling_details')) && in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
        echo '<div class="col-md-8"><h5>Please ensure that you have cured the defects notified by admin. Then only proceed with final submit.</h5></div>';
        if (in_array(NEW_CASE_COURT_FEE, explode(',', getSessionData('efiling_details')['breadcrumb_status']))) {
            echo '<a href="' . base_url('newcase/finalSubmit') . '" class="btn btn-success btn-sm">SUBMIT FOR RE-FILING </a>';
        }
    }
    // $stages_array = array(Draft_Stage);
    // if (!empty(getSessionData('efiling_details')) && in_array(!empty(getSessionData('login')) && getSessionData('login')['ref_m_usertype_id'], $allowed_users_trash) && in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
        ?>
        {{-- <a class="btn btn-danger btn-sm" onclick="ActionToTrash('UAT')">Trash</a> --}}
    <?php //}
}

?>

