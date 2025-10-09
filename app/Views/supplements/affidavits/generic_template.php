<?php
echo $this->session->flashdata('msg');
echo $this->session->flashdata('msg_error');

$attribute = array('name' => 'add_affidavits_details', 'id' => 'add_affidavits_details', 'autocomplete' => 'off');
echo form_open('supplements/DefaultController/affidavit_template/'.$this->uri->segment(4), $attribute);
?>
<div class="panel panel-default">
    <div class="panel-body">
        <h4 style="text-align: center;color: #31B0D5">Affidavit [ <?php echo $affidavit_template[0]['casename'];?> ] </h4><br/>
        <?php
        if(!empty($affidavit_list) && count($affidavit_list) > 0 && !empty($affidavit_list[0]['id']) && $affidavit_list[0]['id']!=null){?>
            <input type="hidden" name="affidavit_id" id="affidavit_id" value="<?php echo $affidavit_list[0]['id'];?>">
        <?php }?>
        <?php
        if (!empty($deponent_type) && $deponent_type!=null){
            if ($deponent_type=='1'){
                $deponent_type_name='Petitioner';
            }elseif ($deponent_type=='2'){
                $deponent_type_name='Power of attorney';
            }elseif ($deponent_type=='3'){
                $deponent_type_name='Pairokar';
            }
            //url_encryption($deponent_type);
            echo 'Deponent Type:'.$deponent_type_name.  '&nbsp;&nbsp;&nbsp;&nbsp;<input type="hidden" name="deponent_type" id="deponent_type" value="' . url_encryption($deponent_type) . '" required>';
        }
        if (!empty($affidavit_template[0]['sc_case_type_id']) && $affidavit_template[0]['sc_case_type_id']==9 || $affidavit_template[0]['sc_case_type_id']==10 || $affidavit_template[0]['sc_case_type_id']==12 || $affidavit_template[0]['sc_case_type_id']==13) {
            echo 'SLP Name<input type="text" name="slp_name" id="slp_name" readonly value="' . $affidavit_list[0]['slp_name'] . '" style="width: 21%;">';
            echo 'Case No:<input type="text" name="case_num" id="case_num" value="' . $affidavit_list[0]['case_num'] . '">';
            echo ',Case Year:<input type="text" name="case_year" id="case_year" value="' . $affidavit_list[0]['case_year'] . '">';
        }else if (!empty($affidavit_template[0]['sc_case_type_id']) && $deponent_type==3){
            echo 'SLP Name<input type="text" name="slp_name" id="slp_name" readonly value="' . $affidavit_list[0]['slp_name'] . '" style="width: 21%;">';
        }
        echo '<br><br>';
        $text = explode(PHP_EOL, $affidavit_template[0]['deponent']);
        $affidavit_template_id=$affidavit_template[0]['id'];
        echo '<input type="hidden" name="ref_affidavit_formats" id="ref_affidavit_formats" value="'.$affidavit_template_id.'">';
        foreach ($text as $line){
            $sentences = explode('$%', $line);
            $html = ''; $readonly='';
            foreach ($sentences as $x){
                $y = explode( '%$', $x);
                if(sizeof($y)==2){
                    if ($affidavit_template[0]['sc_case_type_id']==13 || $affidavit_template[0]['sc_case_type_id']==14 && $y[0]=='party_age' || $y[0]=='petitioner_address'){
                        $html.='<input type="text" name="'.$y[0].'" id="'.$y[0].'" value="'.$affidavit_list[0][$y[0]].'">'.$y[1];
                    }else if($y[0]=='relation' && $deponent_type!=3){
                        $html.='<input type="text" style="border:none;width: 2.1%;" name="'.$y[0].'" id="'.$y[0].'" readonly value="'.$affidavit_list[0][$y[0]].'">'.$y[1];
                    }else{
                        if ($deponent_type==3 && $y[0]=='pairokar_petitioner_name' || $y[0]=='pairokar_age' || $y[0]=='pairokar_pincode'){
                            $html .= '<input type="text" name="' . $y[0] . '" id="' . $y[0] . '"  value="' . $affidavit_list[0][$y[0]] . '">' . $y[1];
                        }else{
                            $html.='<input type="text" name="'.$y[0].'" id="'.$y[0].'" readonly value="'.$affidavit_list[0][$y[0]].'">'.$y[1];
                        }
                    }

                }
                else{
                    $html.=$y[0];
                }
                //echo '<br/>';
            }
            echo "<p>$html</p>";
        }

        ?>
        <?php
        $text = explode(PHP_EOL, $affidavit_template[0]['description']);
        foreach ($text as $line){
            $sentences = explode('$%', $line);
            $html = '';
            foreach ($sentences as $x){
                $y = explode( '%$', $x);
                if(sizeof($y)==2){
                    if ($deponent_type==3 && $y[0]=='petitioner_name'){
                        //$html .= '<u> ' . ucwords(strtolower($affidavit_list[0][$y[0]])) . '</u>' . $y[1];
                        $html.='<input type="text" style="border-bottom: 1px solid black;border-top:none;border-left:none;border-right:none;" readonly name="'.$y[0].'" id="'.$y[0].'" value="'.ucwords(strtolower($affidavit_list[0][$y[0]])).'">'.$y[1];
                    }else{
                        $html.='<input type="text" name="'.$y[0].'" id="'.$y[0].'" value="'.$affidavit_list[0][$y[0]].'">'.$y[1];
                    }
                }
                else{
                    $html.=$y[0];
                }
                //echo '<br/>';
            }
            echo "<p>$html</p>";
        }
        ?>
    </div>
</div>



<div class="span7 text-center">
    <?php if(!empty($affidavit_list) && count($affidavit_list) > 0 && !empty($affidavit_list[0]['id']) && $affidavit_list[0]['id']!=null){?>
        <input type="submit" tabindex = '16' class="btn btn-success" name="affidavit_update" id="affidavit_update" value="UPDATE">
    <?php }else{?>
        <input type="submit" tabindex = '16' class="btn btn-success" name="affidavit_save" id="affidavit_save" value="SAVE">
    <?php }?>
    <?php echo form_close(); ?>
</div>
</div>







