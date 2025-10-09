<?php
$stages_array = array('', Draft_Stage, Initial_Defected_Stage,  E_REJECTED_STAGE);
if (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
    $hidepencilbtn='true';
}else{
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
                        <th>Caveatee Name</th>
                        <th>Age/D.O.B</th>
                        <th>Gender</th>
						<th>Contact </th>
                        <th>Address</th>
                        <?php
                        if($hidepencilbtn!='true'){ ?>
                            <th class="efiling_search">Action</th>
                        <?php } ?>

                    </thead>
                    <tbody>
                        <?php
                        $res_address = strtoupper($efiling_civil_data[0]['resadd']);
                        $caveatee_name =$efiling_civil_data[0]['res_name'];
                        $res_address = strtoupper($efiling_civil_data[0]['resadd']);
                        if ($efiling_civil_data[0]['res_sex'] == 'M') {
                            $res_gender = 'Male';
                        } elseif ($efiling_civil_data[0]['res_sex'] == 'F') {
                            $res_gender = 'Female';
                        } elseif ($efiling_civil_data[0]['res_sex'] == 'O') {
                            $res_gender = 'Other';
                        } else {
                            $res_gender = '';
                        }

                        if ($efiling_civil_data[0]['res_age'] == 0) {
                            $res_age = '';
                        } else {
                            $res_age = $efiling_civil_data[0]['res_age'];
                        }
                        $res_dob=!empty($efiling_civil_data[0]['res_dob']) ? $res_age.date('d-m-Y',strtotime($efiling_civil_data[0]['res_dob'])):$res_age;

                        if (isset($efiling_civil_data[0]['res_pincode']) && !empty($efiling_civil_data[0]['res_pincode'])) {
                            $res_address .= ' - ' . $efiling_civil_data[0]['res_pincode'];
                        }
                        $res_age='';
                        if (!empty($efiling_civil_data[0]['res_age'])){$res_age=$efiling_civil_data[0]['res_age'].'Yrs /' ;}
                        $res_dob=!empty($efiling_civil_data[0]['res_dob']) ? $res_age.date('d-m-Y',strtotime($efiling_civil_data[0]['res_dob'])):$res_age;
                        echo '<tr>
                            <td data-key="#">1</td>
                            <td data-key="Type">'.strtoupper('Caveatee').'</td>
                            <td data-key="Caveatee Name">'.strtoupper('Caveatee Party :'.$caveatee_name).'</td>
                            <td data-key="Age/D.O.B">'.$res_dob.'</td>
                            <td data-key="Gender">'.strtoupper($res_gender).'</td>
                            <td data-key="Contact ">'.$efiling_civil_data[0]['res_mobile'].'</td>
                            <td data-key="Address">'.$res_address.'</td>';
                            if($hidepencilbtn!='true'){
                                echo '<td data-key="Action" class="efiling_search"><a href="'.base_url('caveat/caveatee').'">Edit</a></td>';
                            }
                        echo '</tr>';
                        ?>
                    </tbody>
            </table>
        </div>
    </div>
</div>