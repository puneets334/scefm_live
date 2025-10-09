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
                        <th>Type</th>
						<th>Caveator Name</th>
						<th>Age/D.O.B</th>
						<th>Gender</th>
						<th>Contact</th>   
						<th>Address</th>
                        <?php if($hidepencilbtn!='true'){ ?>
                            <th class="efiling_search">Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $caveator_name=''; 
                    if(isset($efiling_civil_data[0]['pet_name']) && !empty($efiling_civil_data[0]['pet_name'])) {
                        //$caveator_name ='Caveator is : Individual <br/>Caveator Name : '.$efiling_civil_data[0]['pet_name'];
                        $caveator_name =$efiling_civil_data[0]['pet_name'];
                    } else if(isset($efiling_civil_data[0]['orgid']) && !empty($efiling_civil_data[0]['orgid']) && $efiling_civil_data[0]['orgid'] == 'D1') {
                        $caveator_name = 'State Department';
                    } else if(isset($efiling_civil_data[0]['orgid']) && !empty($efiling_civil_data[0]['orgid']) && $efiling_civil_data[0]['orgid'] == 'D2') {
                        $caveator_name = 'Central Department';
                    } else if(isset($efiling_civil_data[0]['orgid']) && !empty($efiling_civil_data[0]['orgid']) && $efiling_civil_data[0]['orgid'] == 'D3') {
                        $caveator_name = 'Other Organisation';
                    }
                    if ($efiling_civil_data[0]['pet_sex'] == 'M') {
                        $gender = 'Male';
                    } elseif ($efiling_civil_data[0]['pet_sex'] == 'F') {
                        $gender = 'Female';
                    } elseif ($efiling_civil_data[0]['pet_sex'] == 'O') {
                        $gender = 'Other';
                    } else {
                        $gender = '';
                    }
                    if ($efiling_civil_data[0]['res_sex'] == 'M') {
                        $res_gender = 'Male';
                    } elseif ($efiling_civil_data[0]['res_sex'] == 'F') {
                        $res_gender = 'Female';
                    } elseif ($efiling_civil_data[0]['res_sex'] == 'O') {
                        $res_gender = 'Other';
                    } else {
                        $res_gender = '';
                    }						
                    $pet_address = strtoupper($efiling_civil_data[0]['petadd']);
                    if (isset($efiling_civil_data[0]['pet_pincode']) && !empty($efiling_civil_data[0]['pet_pincode'])) {
                        $pet_address .= ' - ' . $efiling_civil_data[0]['pet_pincode'];
                    }                    
                    if ($efiling_civil_data[0]['res_age'] == 0) {
                        $res_age = '';
                    } else {
                        $res_age = $efiling_civil_data[0]['res_age'];
                    }
                    $pet_address = strtoupper($efiling_civil_data[0]['petadd']);
                    if (isset($efiling_civil_data[0]['pet_pincode']) && !empty($efiling_civil_data[0]['pet_pincode'])) {
                        $pet_address .= ' - ' . $efiling_civil_data[0]['pet_pincode'];
                    }
                    $pet_age='';
                    $caveator_details='';
					if(isset($efiling_civil_data[0]['orgid']) && !empty($efiling_civil_data[0]['orgid']) && $efiling_civil_data[0]['orgid'] != 'I'){
						$org_dept_name=!empty($efiling_civil_data[0]['org_dept_name'])? '<br/>Department Name : '.$efiling_civil_data[0]['org_dept_name'].'<br/>':'';
						$org_post_name=!empty($efiling_civil_data[0]['org_post_name'])? 'Post Name : '.$efiling_civil_data[0]['org_post_name'].'<br/>':'';
						$org_state_name=!empty($efiling_civil_data[0]['org_state_name'])? 'State Name : '.$efiling_civil_data[0]['org_state_name']:'';
						$caveator_details =$org_dept_name.$org_post_name.$org_state_name;
					}
                    $pet_age='';
                    if (!empty($efiling_civil_data[0]['pet_age'])){$pet_age=$efiling_civil_data[0]['pet_age'].'Yrs /' ;}
                    $pet_dob=!empty($efiling_civil_data[0]['pet_dob']) ? $pet_age.date('d-m-Y',strtotime($efiling_civil_data[0]['pet_dob'])):$pet_age;
                    echo '<tr>
                        <td data-key="#">1</td>
                        <td data-key="Type">'.strtoupper('Caveator').'</td>
                        <td data-key="Caveator Name">'.strtoupper('Caveator Party :'.$caveator_name.$caveator_details).'</td>
                        <td data-key="Age/D.O.B">'.$pet_dob.'</td>
                        <td data-key="Gender">'.strtoupper($gender).'</td>
                        <td data-key="Contact">'.$efiling_civil_data[0]['pet_mobile'].'</td>
                        <td data-key="Address">'.$pet_address.'</td>';
                        if($hidepencilbtn!='true') {
                            echo '<td data-key="Action" class="efiling_search"><a href="'.base_url('caveat').'">Edit</a></td> ';
                        }
                    echo '</tr>';
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>