<?php
$stages_array = array('', Draft_Stage, Initial_Defected_Stage,  E_REJECTED_STAGE);
if (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
    $hidepencilbtn='true';
} else{
    $hidepencilbtn='false';
}
?>
<div class="panel panel-default">
    <div class="panel-body">        
        <div class="col-md-12 col-sm-12 col-xs-12">             
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th>#</th>
                        <th>Extra Party Is</th>
                        <th>Extra Party Name</th>
                        <th>Age/D.O.B</th>
                        <th>Gender</th>
						<th>Contact </th>
                        <th>Address</th>
                        <?php
                        if($hidepencilbtn!='true'){ ?>
                            <th class="efiling_search">Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($extra_party_details) && !empty($extra_party_details)) {
                        $i = 1;
                        foreach ($extra_party_details as $exp) {
                            if ($exp->pet_sex == 'M') {
                                $ext_gender = 'Male';
                            } elseif ($exp->pet_sex == 'F') {
                                $ext_gender = 'Female';
                            } elseif ($exp->pet_sex == 'O') {
                                $ext_gender = 'Other';
                            } else{
                                $ext_gender = '';
                            }
                            $extra_party_type ='';
                            if ($exp->type == '1') {
                                $extra_party_type = "<strong>Extra Party Type :</strong> Caveator";
                            } elseif ($exp->type == '2') {
                                $extra_party_type = "<strong>Extra Party Type :</strong> Caveatee";
                            }
                            if ($exp->pet_age == 0) {
                                $extra_party_age = '';
                            } else{
                                $extra_party_age = $exp->pet_age;
                            }
                            $extra_party_address = strtoupper($exp->address);
                            if (isset($exp->pincode) && !empty($exp->pincode)) {
                                $extra_party_address .= ' - ' . $exp->pincode;
                            }
                            $extraType =$exp->extra_party_is;
                            if (!empty($extra_party_age)) { $extra_party_age=$extra_party_age.'Yrs /' ; }
                            $party_age_dob=!empty($exp->pet_dob) ? $extra_party_age.date('d-m-Y',strtotime($exp->pet_dob)):$extra_party_age;
                            if(isset($exp->orgid) && !empty($exp->orgid) && $exp->orgid != 'I') {
                                $extra_party_org_dept_name=!empty($exp->extra_party_org_dept_name)? '<br/>Department Name : '.$exp->extra_party_org_dept_name.'<br/>':'';
                                $extra_party_org_post_name=!empty($exp->extra_party_org_post_name)? 'Post Name : '.$exp->extra_party_org_post_name.'<br/>':'';
                                $extra_party_org_state_name=!empty($exp->extra_party_org_state_name)? 'State Name : '.$exp->extra_party_org_state_name:'';
                                $extra_party_type_details =$extra_party_org_dept_name.$extra_party_org_post_name.$extra_party_org_state_name;
                            } else if(isset($exp->orgid) && !empty($exp->orgid) && $exp->orgid == 'I') {
                                $extra_party_type_details = ',Extra Party Name: '.$exp->name;
                            }
                            echo '<tr>
                                <td data-key="#">'.$i.'</td>
                                <td data-key="Extra Party Is">'.strtoupper($extraType).'</td>
                                <td data-key="Extra Party Name">'.strtoupper($extra_party_type.$extra_party_type_details).'</td>
                                <td data-key="Age/D.O.B">'.$party_age_dob.'</td>
                                <td data-key="Gender">'.strtoupper($ext_gender).'</td>
                                <td data-key="Contact ">'.$exp->pet_mobile.'</td>
                                <td data-key="Address">'.$extra_party_address.'</td>';
                                if($hidepencilbtn!='true'){
                                    echo '<td data-key="Action" class="efiling_search"><a href="' . base_url('caveat/extra_party/' . url_encryption(trim(escape_data($exp->id)) . '$$party_id')) . '">Edit</a></td>';
                                }
                            echo '</tr>';
                            $i++;
                        }
                    }
                    ?>
                </tbody>                    
            </table>
        </div>
    </div>
</div>