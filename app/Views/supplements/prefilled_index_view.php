<?php //echo $divshow . "hulala"; exit();
//if(isset($this->session->userdata('INDX_ID_MSG'))){
/*if(!empty($this->session->userdata('INDX_ID_MSG'))){
    $Msg_display_IndxId= $this->session->userdata('INDX_ID_MSG');
    echo $Msg_display_IndxId ; exit();
}*/


?>
<div class="panel panel-default">
    <!--<h4 style="text-align: center;color: #31B0D5">INDEX </h4>-->
    <div class="panel-body">
        <p id="index_data"></p>
        <?php
        if($divshow=='EDITInsrt'){
            $attribute = array('class' => 'form-horizontal', 'name' => 'upd_indx_data', 'id' => 'upd_indx_data', 'autocomplete' => 'off');
        }else{
            $attribute = array('class' => 'form-horizontal', 'name' => 'add_act_section', 'id' => 'add_act_section', 'autocomplete' => 'off');
        }

        //$attribute = array('class' => 'form-horizontal', 'name' => 'add_act_section', 'id' => 'add_act_section', 'autocomplete' => 'off');
        echo form_open('#', $attribute);
        ?>

        <!--<table id="tblAppendGrid"  class="table table-striped table-bordered dt-responsive nowrap" style="width: 100% " ></table>-->



        <div class="col-sm-12 col-xs-12">
            <div class="row">


                <div class="container-lg">
                    <div class="table-responsive">
                        <div class="table-wrapper">
                            <div class="table-title">
                                <div class="row">
                                    <div class="col-sm-8"><h2>INDEX <b>Details</b></h2></div>
                                    <div class="col-sm-4">
                                        <button type="button" class="btn btn-info add-new" id="addrow"><i class="fa fa-plus"></i> Add New Rows</button>
                                    </div>
                                </div>
                            </div>
                            <!--//XXXXXXXXXXXXXXXXXXXXXXX-->
                            <!--<table id="myTable" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 5%" rowspan="2">Sl.No.</th>
                                    <th rowspan="2">Particulars of Document</th>
                                    <th colspan="2" style="text-align: center">Page No. of part to which it belongs</th>
                                    <th rowspan="2">Remarks</th>

                                </tr>
                                <tr>

                                    <th rowspan="2">Part I (Contents of Paper Book)</th>

                                    <th>Part II (Contents of file alone)</th>

                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>-->





                            <!--//XXXXXXXXXXXXXXXXXXXXXX-->
                            <!--<div id="NEW_INSRT">-->
                            <table id="myTable" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 5%">Sl.No.</th>
                                    <th style="width: 20%">Particulars of Document</th>
                                    <th style="width: 5%">From Page No.</th>
                                    <th style="width: 5%">To Page No.</th>
                                    <th style="width: 20%">Remarks</th>
                                    <th style="width: 15%">Contents PartI / PartII</th>
                                    <th style="width: 10%">Volume</th>
                                    <th style="width: 15%">Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i=1;
                                $sr=1;
                                $srtr='';
                                $sr_td=1;
                                ?>
                                <tr id="<?php echo 'row'.'_'.$sr;?>">
                                    <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?php echo $i++;?></td>
                                    <!--<td><input type="text" name="docs[]" id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>" value="Court Fee" class="form-control" readonly/></td>-->
                                    <td><textarea id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="docs[]" rows="2" cols="50" readonly style="width: 257px;"> Court Fee </textarea></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="Frompage[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" name="Topage[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" name="remrks[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td>
                                        <select name="contents[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <option value="">Select Contents</option>
                                            <option value="1" selected="selected">PART I</option>
                                            <option value="2">PART II</option>
                                            <option value="3">BOTH</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="vol[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <!--<option value="">Select VOL</option>-->
                                            <option value="1">VOL I</option>
                                            <option value="2">VOL II</option>
                                            <option value="3">VOL III</option>

                                        </select>
                                    </td>
                                    <td class="col-sm-2" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><a class="deleteRow"></a> </td>
                                    <input type="hidden" name="docs_read_only[]" id="docs_read_only" value="Y" class="form-control" />


                                </tr>
                                <?php

                                $sr++;
                                $sr_td=1;
                                ?>

                                <tr id="<?php echo 'row'.'_'.$sr;?>">
                                    <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?php echo $i++;?></td>
                                    <!--<td><input type="text" name="docs[]" id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>" value="O/R on Limitation" class="form-control" readonly/></td>-->
                                    <td><textarea id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="docs[]" rows="2" cols="50" readonly style="width: 257px;" > O/R on Limitation </textarea></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="Frompage[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" name="Topage[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="remrks[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td>
                                        <select name="contents[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <option value="">Select Contents</option>
                                            <option value="1">PART I</option>
                                            <option value="2">PART II</option>
                                            <option value="3" selected="selected">BOTH</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="vol[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                           <!-- <option value="">Select VOL</option>-->
                                            <option value="1">VOL I</option>
                                            <option value="2">VOL II</option>
                                            <option value="3">VOL III</option>

                                        </select>
                                    </td>
                                    <td class="col-sm-2" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><a class="deleteRow"></a> </td>
                                    <input type="hidden" name="docs_read_only[]" id="docs_read_only" value="Y" class="form-control" />
                                </tr>
                                <?php

                                $sr++;
                                $sr_td=1;
                                ?>
                                <tr id="<?php echo 'row'.'_'.$sr;?>">
                                    <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?php echo $i++;?></td>
                                    <!--<td><input type="text" name="docs[]" id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>" value="Listing Proforma " class="form-control" readonly/></td>-->
                                    <td><textarea id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="docs[]" rows="2" cols="50" readonly style="width: 257px;"> Listing Proforma </textarea></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="Frompage[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" name="Topage[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="remrks[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td>
                                        <select name="contents[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <option value="">Select Contents</option>
                                            <option value="1">PART I</option>
                                            <option value="2">PART II</option>
                                            <option value="3" selected="selected">BOTH</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="vol[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <!--<option value="">Select VOL</option>-->
                                            <option value="1">VOL I</option>
                                            <option value="2">VOL II</option>
                                            <option value="3">VOL III</option>

                                        </select>
                                    </td>
                                    <td class="col-sm-2" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><a class="deleteRow"></a> </td>
                                    <input type="hidden" name="docs_read_only[]" id="docs_read_only" value="Y" class="form-control" />
                                </tr>
                                <?php

                                $sr++;
                                $sr_td=1;
                                ?>
                                <tr id="<?php echo 'row'.'_'.$sr;?>">
                                    <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?php echo $i++;?></td>
                                    <!--<td><input type="text" name="docs[]" id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>" value="Cover Page of Paper Book" class="form-control" readonly/></td>-->
                                    <td><textarea id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="docs[]" rows="2" cols="50" readonly style="width: 257px;"> Cover Page of Paper Book </textarea></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="Frompage[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" name="Topage[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="remrks[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td>
                                        <select name="contents[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <option value="">Select Contents</option>
                                            <option value="1">PART I</option>
                                            <option value="2" selected="selected">PART II</option>
                                            <option value="3">BOTH</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="vol[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <!--<option value="">Select VOL</option>-->
                                            <option value="1">VOL I</option>
                                            <option value="2">VOL II</option>
                                            <option value="3">VOL III</option>

                                        </select>
                                    </td>
                                    <td class="col-sm-2" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><a class="deleteRow"></a> </td>
                                    <input type="hidden" name="docs_read_only[]" id="docs_read_only" value="Y" class="form-control" />
                                </tr>
                                <?php

                                $sr++;
                                $sr_td=1;
                                ?>
                                <tr id="<?php echo 'row'.'_'.$sr;?>">
                                    <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?php echo $i++;?></td>
                                    <!--<td><input type="text" name="docs[]" id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>" value="Index of Record of Proceedings" class="form-control" readonly/></td>-->
                                    <td><textarea id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="docs[]" rows="2" cols="50" readonly style="width: 257px;"> Index of Record of Proceedings </textarea></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="Frompage[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" name="Topage[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="remrks[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td>
                                        <select name="contents[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <option value="">Select Contents</option>
                                            <option value="1">PART I</option>
                                            <option value="2" selected="selected">PART II</option>
                                            <option value="3">BOTH</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="vol[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <!--<option value="">Select VOL</option>-->
                                            <option value="1">VOL I</option>
                                            <option value="2">VOL II</option>
                                            <option value="3">VOL III</option>

                                        </select>
                                    </td>
                                    <td class="col-sm-2" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><a class="deleteRow"></a> </td>
                                    <input type="hidden" name="docs_read_only[]" id="docs_read_only" value="Y" class="form-control" />
                                </tr>
                                <?php

                                $sr++;
                                $sr_td=1;
                                ?>
                                <tr id="<?php echo 'row'.'_'.$sr;?>">
                                    <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?php echo $i++;?></td>
                                    <!--<td><input type="text" name="docs[]" id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>" value="Limitation Report prepared by the Registry" class="form-control" readonly/></td>-->
                                    <td><textarea id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="docs[]" rows="2" cols="50" readonly style="width: 257px;"> Limitation Report prepared by the Registry</textarea></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="Frompage[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" name="Topage[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="remrks[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td>
                                        <select name="contents[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <option value="">Select Contents</option>
                                            <option value="1">PART I</option>
                                            <option value="2" selected="selected">PART II</option>
                                            <option value="3">BOTH</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="vol[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                           <!-- <option value="">Select VOL</option>-->
                                            <option value="1">VOL I</option>
                                            <option value="2">VOL II</option>
                                            <option value="3">VOL III</option>

                                        </select>
                                    </td>
                                    <td class="col-sm-2" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><a class="deleteRow"></a> </td>
                                    <input type="hidden" name="docs_read_only[]" id="docs_read_only" value="Y" class="form-control" />
                                </tr>
                                <?php

                                $sr++;
                                $sr_td=1;
                                ?>
                                <tr id="<?php echo 'row'.'_'.$sr;?>">
                                    <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?php echo $i++;?></td>
                                    <!--<td><input type="text" name="docs[]" id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>" value="Defect List" class="form-control" readonly/></td>-->
                                    <td><textarea id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="docs[]" rows="2" cols="50" readonly style="width: 257px;"> Defect List </textarea></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="Frompage[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" name="Topage[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="remrks[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td>
                                        <select name="contents[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <option value="">Select Contents</option>
                                            <option value="1">PART I</option>
                                            <option value="2" selected="selected">PART II</option>
                                            <option value="3">BOTH</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="vol[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <!--<option value="">Select VOL</option>-->
                                            <option value="1">VOL I</option>
                                            <option value="2">VOL II</option>
                                            <option value="3">VOL III</option>

                                        </select>
                                    </td>
                                    <td class="col-sm-2" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><a class="deleteRow"></a> </td>
                                    <input type="hidden" name="docs_read_only[]" id="docs_read_only" value="Y" class="form-control" />
                                </tr>
                                <?php

                                $sr++;
                                $sr_td=1;
                                ?>
                                <tr id="<?php echo 'row'.'_'.$sr;?>">
                                    <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?php echo $i++;?></td>
                                    <!--<td><input type="text" name="docs[]" id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>" value="Note Sheet" class="form-control" readonly/></td>-->
                                    <td><textarea id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="docs[]" rows="2" cols="50" readonly style="width: 257px;"> Note Sheet </textarea></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="Frompage[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" name="Topage[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="remrks[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td>
                                        <select name="contents[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <option value="">Select Contents</option>
                                            <option value="1">PART I</option>
                                            <option value="2" selected="selected">PART II</option>
                                            <option value="3">BOTH</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="vol[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                           <!-- <option value="">Select VOL</option>-->
                                            <option value="1">VOL I</option>
                                            <option value="2">VOL II</option>
                                            <option value="3">VOL III</option>

                                        </select>
                                    </td>
                                    <td class="col-sm-2" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><a class="deleteRow"></a> </td>
                                    <input type="hidden" name="docs_read_only[]" id="docs_read_only" value="Y" class="form-control" />
                                </tr>
                                <?php

                                $sr++;
                                $sr_td=1;
                                ?>
                                <tr id="<?php echo 'row'.'_'.$sr;?>">
                                    <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?php echo $i++;?></td>
                                    <!--<td><input type="text" name="docs[]" id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>" value="List of Dates" class="form-control" readonly/></td>-->
                                    <td><textarea id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="docs[]" rows="2" cols="50" readonly style="width: 257px;"> List of Dates </textarea></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="Frompage[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" name="Topage[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="remrks[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td>
                                        <select name="contents[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <option value="">Select Contents</option>
                                            <option value="1" selected="selected">PART I</option>
                                            <option value="2">PART II</option>
                                            <option value="3">BOTH</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="vol[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                           <!-- <option value="">Select VOL</option>-->
                                            <option value="1">VOL I</option>
                                            <option value="2">VOL II</option>
                                            <option value="3">VOL III</option>

                                        </select>
                                    </td>
                                    <td class="col-sm-2" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><a class="deleteRow"></a> </td>
                                    <input type="hidden" name="docs_read_only[]" id="docs_read_only" value="Y" class="form-control" />
                                </tr>
                                <?php

                                $sr++;
                                $sr_td=1;
                                ?>
                                <tr id="<?php echo 'row'.'_'.$sr;?>">
                                    <td id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><?php echo $i++;?></td>
                                    <!--<td><input type="text" name="docs[]" id="<?php /*echo 'row'.'_'.$sr.$sr_td++;*/?>" value="Impugned Order" class="form-control" readonly/></td>-->
                                    <td><textarea id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="docs[]" rows="2" cols="50"  style="width: 257px;"> Impugned Order </textarea></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="Frompage[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" name="Topage[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" name="remrks[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td>
                                        <select name="contents[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <option value="">Select Contents</option>
                                            <option value="1" selected="selected">PART I</option>
                                            <option value="2">PART II</option>
                                            <option value="3">BOTH</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="vol[]" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <!--<option value="">Select VOL</option>-->
                                            <option value="1">VOL I</option>
                                            <option value="2">VOL II</option>
                                            <option value="3">VOL III</option>

                                        </select>
                                    </td>
                                    <td class="col-sm-2" id="<?php echo 'row'.'_'.$sr.$sr_td++;?>"><a class="deleteRow"></a> </td>
                                    <input type="hidden" name="docs_read_only[]" id="docs_read_only" value="N" class="form-control" />
                                </tr>
                                <?php

                                    $sr++;
                                    $sr_td=1;

                                ?>
                                </tbody>
                                <!--<tfoot>
                                <tr>
                                    <td colspan="5" style="text-align: left;">
                                        <input type="button" class="btn btn-lg btn-block " id="addrow" value="Add Row" />
                                    </td>
                                </tr>
                                <tr>
                                </tr>
                                </tfoot>-->
                            </table>
                            <!--</div>-->
                            <!--<div id="IA_DOCS">-->
                            <table id="myTableIA" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 5%">Sl.No.</th>
                                    <th style="width: 20%">Particulars of Document</th>
                                    <th style="width: 5%">From Page No.</th>
                                    <th style="width: 5%">To Page No.</th>
                                    <th style="width: 20%">Remarks</th>
                                    <th style="width: 15%">Contents PartI / PartII</th>
                                    <th style="width: 10%">Volume</th>
                                    <th style="width: 15%">Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i=1;
                                $sr=1;
                                $srtr='';
                                $sr_td=1;
                                ?>
                                <tr id="<?php echo 'rowIA'.'_'.$sr;?>">
                                    <td id="<?php echo 'rowIA'.'_'.$sr.$sr_td++;?>"><?php echo $i++;?></td>
                                    <!--<td><input type="text" name="docs[]" id="<?php /*echo 'rowIA'.'_'.$sr.$sr_td++;*/?>" value="" class="form-control" /></td>-->
                                    <td><textarea id="<?php echo 'rowIA'.'_'.$sr.$sr_td++;?>" name="docs[]" rows="2" cols="50"  style="width: 257px;"></textarea></td>
                                    <td><input type="text" id="<?php echo 'rowIA'.'_'.$sr.$sr_td++;?>" name="Frompage[]" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td ><input type="text" name="Topage[]" id="<?php echo 'rowIA'.'_'.$sr.$sr_td++;?>" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td><input type="text" name="remrks[]" id="<?php echo 'rowIA'.'_'.$sr.$sr_td++;?>" value="" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    <td>
                                        <select name="contents[]" id="<?php echo 'rowIA'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <option value="">Select Contents</option>
                                            <option value="1">PART I</option>
                                            <option value="2">PART II</option>
                                            <option value="3">BOTH</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="vol[]" id="<?php echo 'rowIA'.'_'.$sr.$sr_td++;?>" class="form-control">
                                            <!--<option value="">Select VOL</option>-->
                                            <option value="1">VOL I</option>
                                            <option value="2">VOL II</option>
                                            <option value="3">VOL III</option>

                                        </select>
                                    </td>
                                    <td class="col-sm-2" id="<?php echo 'rowIA'.'_'.$sr.$sr_td++;?>"><a class="deleteRow"></a> </td>
                                    <input type="hidden" name="docs_read_only[]" id="docs_read_only" value="N" class="form-control" />

                                </tr>
                                <?php

                                $sr++;
                                $sr_td=1;
                                ?>
                                </tbody>
                            </table>
                            <!--</div>-->
                            <!--//XXXXXX EDIT TABLE XXXXXXXX-->
                            <table id="myTableEDIT" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 5%">Sl.No.</th>
                                    <th style="width: 20%">Particulars of Document</th>
                                    <th style="width: 5%">From Page No.</th>
                                    <th style="width: 5%">To Page No.</th>
                                    <th style="width: 20%">Remarks</th>
                                    <th style="width: 15%">Contents PartI / PartII</th>
                                    <th style="width: 10%">Volume</th>
                                    <th style="width: 15%">Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $indx_id_rcd=$docs_indx_data[0]['index_id'];
                                $i=1;
                                $sr=1;
                                foreach($docs_indx_data as $val_edit_data ){
                                    $sr_td=1;
                                    if($val_edit_data['documents']!=''){

                                    ?>
                                    <tr id="<?php echo 'rowEdit'.'_'.$sr;?>">
                                        <td id="<?php echo 'rowEdit'.'_'.$sr.$sr_td++;?>"><?php echo $i++;?></td>
                                        <?php
                                        if($val_edit_data['docs_readonly_flag']=='Y'){ ?>
                                            <!--<td><input type="text" name="docs[]" id="docs" value="<?/*= $val_edit_data['documents'];*/?>" class="form-control" readonly /></td>-->
                                            <td><textarea id="<?php echo 'rowEdit'.'_'.$sr.$sr_td++;?>" name="docs[]" rows="2" cols="50" readonly style="width: 257px;"> <?= $val_edit_data['documents'];?></textarea></td>
                                        <?php }else{ ?>
                                            <!--<td><input type="text" name="docs[]" id="docs" value="<?/*= $val_edit_data['documents'];*/?>" class="form-control" /></td>-->
                                            <td><textarea id="<?php echo 'rowEdit'.'_'.$sr.$sr_td++;?>" name="docs[]" rows="2" cols="50"  style="width: 257px;"><?= $val_edit_data['documents'];?></textarea></td>
                                        <?php } ?>


                                        <td><input type="text" id="<?php echo 'rowEdit'.'_'.$sr.$sr_td++;?>" name="Frompage[]" value="<?= $val_edit_data['from_page_no'];?>" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                        <td><input type="text" name="Topage[]" id="<?php echo 'rowEdit'.'_'.$sr.$sr_td++;?>" value="<?= $val_edit_data['to_page_no'];?>" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                        <td><input type="text" name="remrks[]" id="<?php echo 'rowEdit'.'_'.$sr.$sr_td++;?>" value="<?= $val_edit_data['remarks'];?>" class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                        <td>

                                            <select name="contents[]" id="<?php echo 'rowEdit'.'_'.$sr.$sr_td++;?>" class="form-control">
                                                <option value="">Select Contents</option>
                                                <?php if($val_edit_data['contents']==1) { ?>
                                                    <option value="1" selected="selected">PART I</option>
                                                <?php }else{ ?>
                                                    <option value="1" >PART I</option>
                                                <?php }?>

                                                <?php if($val_edit_data['contents']==2) { ?>
                                                    <option value="2" selected="selected">PART II</option>
                                                <?php }else{ ?>
                                                    <option value="2">PART II</option>
                                                <?php }?>

                                                <?php if($val_edit_data['contents']==3) { ?>
                                                    <option value="3" selected="selected">BOTH</option>
                                                <?php }else{ ?>
                                                    <option value="3">BOTH</option>
                                                <?php }?>

                                            </select>
                                        </td>
                                        <td>
                                            <select name="vol[]" id="<?php echo 'rowEdit'.'_'.$sr.$sr_td++;?>" class="form-control">
                                                <!--<option value="">Select VOL</option>-->
                                            <?php if($val_edit_data['volume']==1) { ?>
                                                <option value="1" selected="selected">VOL I</option>
                                            <?php }else{ ?>
                                            <option value="1">VOL I</option>
                                            <?php }?>

                                            <?php if($val_edit_data['volume']==2) { ?>
                                                <option value="2" selected="selected">VOL II</option>
                                            <?php }else{ ?>
                                                <option value="2">VOL II</option>
                                            <?php }?>

                                            <?php if($val_edit_data['volume']==3) { ?>
                                                <option value="3" selected="selected">VOL III</option>
                                            <?php }else{ ?>
                                                <option value="3">VOL III</option>
                                            <?php }?>

                                            </select>
                                        </td>
                                        <!--<td class="col-sm-2"><a class="deleteRow"></a> </td>-->
                                        <?php
                                        if($val_edit_data['docs_readonly_flag']=='Y'){ ?>
                                            <input type="hidden" name="docs_read_only[]" id="docs_read_only" value="Y" class="form-control" />

                                        <?php }else{ ?>
                                            <td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete">
                                                <span class="up btn btn-success">
                                                    <i class="fa fa-sort-up fa-lg"></i>
                                                </span>
                                                <span class="down btn btn-success">
                                                    <i class="fa fa-sort-down fa-lg"></i>
                                                </span>
                                            </td>

                                            <input type="hidden" name="docs_read_only[]" id="docs_read_only" value="N" class="form-control" />
                                        <?php } ?>
                                        <input type="hidden" id="indx_Id_RCD " name="indx_Id_RCD[]" value="<?php echo $indx_id_rcd ;?> " />

                                    </tr>

                                        <?php

                                        $sr++;
                                        $sr_td++;
                                        if($sr_td==7){
                                            $sr_td=0;
                                        } ?>

                                <?php  } }//End of foreach loop ().. ?>



                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <button type="button" class="btn btn-info add-new" id="addrow"><i class="fa fa-plus"></i> Add New Rows</button>
                </div>






            </div>
        </div>


        <div class="form-group">
            <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4">
                <?php
                if($divshow=='EDITInsrt'){ ?>

                    <input  tabindex = '8' type="submit" class="btn btn-success" id="pet_save" value="Update">
                <?php }else{ ?>

                    <input  tabindex = '8' type="submit" class="btn btn-success" id="pet_save" value="SAVE">
                <?php } ?>


            </div>
        </div>

        <?php echo form_close();
        ?>

    </div>
</div>



<script>
    $(document).ready(function () {
        var r= '<?php echo $divshow ; ?>';
        //alert(r);
        if(r=='newInsrt'){
            $('#myTableIA').hide();
            $('#myTable').show();
            $('#myTableEDIT').hide();
            /*$('#myTableIA').attr("disabled",true);
            $('#myTable').attr("disabled",false);*/
            $("#myTableIA").find("input,hidden,button,textarea,select").attr("disabled", "disabled");
            $("#myTableEDIT").find("input,hidden,button,textarea,select").attr("disabled", "disabled");
        }else if(r=='IaInsrt'){
            $('#myTableIA').show();
            $('#myTable').hide();
            $('#myTableEDIT').hide();
            /*$('#myTableIA').attr("disabled",false);
            $('#myTable').attr("disabled",true);*/
            $("#myTable").find("input,hidden,button,textarea,select").attr("disabled", "disabled");
            $("#myTableEDIT").find("input,hidden,button,textarea,select").attr("disabled", "disabled");
        }
        else if(r=='EDITInsrt'){
            $('#myTableIA').hide();
            $('#myTable').hide();
            $('#myTableEDIT').show();
            /*$('#myTableIA').attr("disabled",true);
            $('#myTable').attr("disabled",false);*/
            $("#myTableIA").find("input,hidden,button,textarea,select").attr("disabled", "disabled");
            $("#myTable").find("input,hidden,button,textarea,select").attr("disabled", "disabled");

        }
        var counter = 0;

        //$("#addrow").on("click", function () {
        $(".add-new").on("click", function () {
            //alert("rounak mishra");

            if(r=='newInsrt'){

                var rowCount = $('#myTable tr').length;
                var ro= 'row_'+rowCount ;
            }else if(r=='IaInsrt'){

                var rowCount = $('#myTableIA tr').length;
                var ro= 'rowIA_'+rowCount ;
            }else if(r=='EDITInsrt'){
                var indexRcdId='<?php echo $indx_id_rcd;?>';
                var rowCount = $('#myTableEDIT tr').length;
                var ro= 'rowEdit_'+rowCount ;
            }
            
           // var ro= 'row_'+rowCount ;
            var newRow = $('<tr id="'+ro+'">');


            var cols = "";

            cols += '<td id="'+ro+ 1+'">'+rowCount+'</td>';
            /*cols += '<td><input type="text" class="form-control" id="'+ro+ 2+'" name="docs[]" onkeyup="this.value = this.value.toUpperCase();"/></td>';*/

            cols += '<td><textarea id="'+ro+ 2+'" name="docs[]" onkeyup="this.value = this.value.toUpperCase();" style="width: 257px;"></textarea></td>';

            cols += '<td><input type="text" class="form-control" id="'+ro+ 3+'" name="Frompage[]" onkeyup="this.value = this.value.toUpperCase();"/></td>';
            cols += '<td><input type="text" class="form-control" id="'+ro+ 4+'" name="Topage[]" onkeyup="this.value = this.value.toUpperCase();"/></td>';
            cols += '<td><input type="text" class="form-control" id="'+ro+ 5+'" name="remrks[]" onkeyup="this.value = this.value.toUpperCase();"/></td>';
            cols +='<td><select class="form-control" id="'+ro+ 6+'" name="contents[]"><option value="">Select Contents</option><option value="1" selected="selected">PART I</option><option value="2">PART II</option><option value="3">BOTH</option></select></td>';

            cols +='<td><select class="form-control" id="'+ro+ 7+'" name="vol[]"><option value="1">VOL I</option><option value="2">VOL II</option><option value="3">VOL III</option></select></td>';

            cols += '<td id="'+ro+ 8+'"><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete">' +
                /*'<input type="button" class="up btn btn-md " value="UP">' +
                '<i class="fa fa-sort-up fa-lg up"></i>&nbsp;&nbsp;&nbsp;&nbsp;'+
                '<i class="fa fa-sort-down fa-lg down"></i>'+*/
                '<span class="up btn btn-success">' +
                '<i class="fa fa-sort-up fa-lg"></i>' +
                '</span>'+
                '<span class="down btn btn-success">' +
                '<i class="fa fa-sort-down fa-lg"></i>' +
                '</span>'+


                /*'<input type="button" class="down btn btn-md btn-danger "  value="Down">' +*/


                '</td>';




            cols += '<input type="hidden" name="docs_read_only[]" id="docs_read_only" value="N" class="form-control" />';

            if(r=='EDITInsrt'){

                //cols += '<input type="hidden" name="docs_read_only[]" id="docs_read_only" value="N" class="form-control" />';

                cols += '<input type="hidden" id="indx_Id_RCD " name="indx_Id_RCD[]" value="'+indexRcdId+'" />';
            }


            newRow.append(cols);
            $("table.table-bordered").append(newRow);
            counter++;

            //alert(counter);
        });


        $("table.table-bordered").on("click", ".ibtnDel", function (event) {
            $(this).closest("tr").remove();
            counter -= 1
        });


        //$(".up,.down").live('click',function(){
            $("table.table-bordered").on("click", ".up,.down", function (event) {
            //alert("rounak mishra");return;
            var row = $(this).parents("tr:first");
            if ($(this).is(".up")) {
                row.insertBefore(row.prev());
            } else {
                row.insertAfter(row.next());
            }
        });


    });



    /*function calculateRow(row) {
        var price = +row.find('input[name^="price"]').val();

    }

    function calculateGrandTotal() {
        var grandTotal = 0;
        $("table.table-bordered").find('input[name^="price"]').each(function () {
            grandTotal += +$(this).val();
        });
        $("#grandtotal").text(grandTotal.toFixed(2));
    }*/
</script>



<script type="text/javascript">



   // $(document).ready(function () {


        /*Changes end */

        $('#add_act_section').on('submit', function () {

            //alert("Rounak Mishra");return ;*/

            var r= '<?php echo $divshow ; ?>';

            if(r=='newInsrt'){
               // alert("hulalalaal");return;
                var rowCount_valid = $('#myTable tr').length;
                var ro= 'row_';

            }else if(r=='IaInsrt'){
               // alert("oyeeee");return;

                var rowCount_valid = $('#myTableIA tr').length;
                var ro= 'rowIA_' ;
            }

            //alert(rowCount_valid); return;
            for (var i=1 ; i < rowCount_valid; i++ ){
                var trid= ro+[i];
                var tdid3=3;
                var fromPG_chk=trid + tdid3 ;

                var fromPG_chk_valid=$('#'+fromPG_chk).val();

                //alert(fromPG_chk + ' / ' + fromPG_chk_valid); return;

                if(fromPG_chk_valid!='' ){
                    var tdid4=4;
                    var tdid6=6;
                    var toPG_chk=trid + tdid4;
                    var contents_chk=trid + tdid6;
                    var toPG_chk_valid=$('#'+toPG_chk).val();
                    var contents_chk_valid=$('#'+contents_chk).val();

                    if(toPG_chk_valid=='' || contents_chk_valid==''){
                        if(toPG_chk_valid==''){
                            //alert("To page No Mandatoury!");
                            $('#'+trid).append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>To page No Required* </strong></div>');
                            return false;
                        }
                        else if(contents_chk_valid==''){
                            //alert("Select contents PART I / PART II Mandatoury!");
                            $('#'+trid).append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Select contents PART I / PART II Required!* </strong></div>');
                            return false;
                        }
                    }
                }

            }//End of for loop()..


            if ($('#add_act_section').valid()) {
                var form_data = $(this).serialize();
                /*alert(form_data);
                console.log(form_data);
                return ;*/
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('supplements/Prefilled_index_controller/add_index_data'); ?>",
                    data: form_data,
                    async: false,
                    beforeSend: function () {
                        $('#pet_save').val('Please wait...');
                        $('#pet_save').prop('disabled', true);
                    },
                    success: function (data) {
                        /*alert(data);
                        console.log(data);
                        return;*/
                        //var Msg_display_IndxId = '<//?php echo $Msg_display_IndxId ; ?>';
                        var Msg_display_IndxId = data;
                       // alert(Msg_display_IndxId);return;
                       /* $('#pet_save').val('SAVE');
                        $('#pet_save').prop('disabled', false);*/
                        var msgread='<div class="alert alert-success text-center">PDF GENRATED SUCCESSFULLY "'+Msg_display_IndxId+'"  </div>';
                        $('#index_data').html(msgread);
                        setTimeout(function() {
                            location.reload();
                        }, 5000);
                        $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }

                });
                return false;
            } else {
                return false;
            }
        });


       $('#upd_indx_data').on('submit', function () {
           //alert("Rounak Mishra");return ;

           var rowCount_valid = $('#myTableEDIT tr').length;
           var ro= 'rowEdit_' ;

           //alert(rowCount_valid); return;
           for (var i=1 ; i < rowCount_valid; i++ ){
               var trid= ro+[i];
               var tdid3=3;
               var fromPG_chk=trid + tdid3 ;

               var fromPG_chk_valid=$('#'+fromPG_chk).val();

               //alert(fromPG_chk + ' / ' + fromPG_chk_valid); return;

               if(fromPG_chk_valid!='' ){
                   var tdid4=4;
                   var tdid6=6;
                   var toPG_chk=trid + tdid4;
                   var contents_chk=trid + tdid6;
                   var toPG_chk_valid=$('#'+toPG_chk).val();
                   var contents_chk_valid=$('#'+contents_chk).val();

                   if(toPG_chk_valid=='' || contents_chk_valid==''){
                       if(toPG_chk_valid==''){
                           //alert("To page No Mandatoury!");
                           $('#'+trid).append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>To page No Required* </strong></div>');
                           return false;
                       }
                       else if(contents_chk_valid==''){
                           //alert("Select contents PART I / PART II Mandatoury!");
                           $('#'+trid).append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Select contents PART I / PART II Required!* </strong></div>');
                           return false;
                       }
                   }
               }

           }//End of for loop()..


           if ($('#upd_indx_data').valid()) {
               var form_data = $(this).serialize();
               /*alert(form_data);
               console.log(form_data);
               return ;*/
               var CSRF_TOKEN = 'CSRF_TOKEN';
               var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

               $.ajax({
                   type: "POST",
                   url: "<?php echo base_url('supplements/Prefilled_index_controller/update_index_data'); ?>",
                   data: form_data,
                   async: false,
                   beforeSend: function () {
                       $('#pet_save').val('Please wait...');
                       $('#pet_save').prop('disabled', true);
                   },
                   success: function (data) {
                         /*alert(data);
                         console.log(data);
                         return;*/
                       /* $('#pet_save').val('SAVE');
                        $('#pet_save').prop('disabled', false);*/
                       var msgread='<div class="alert alert-success text-center">PDF UPDATED SUCCESSFULLY </div>';
                       $('#index_data').html(msgread);
                       setTimeout(function() {
                           location.reload();
                       }, 5000);
                       $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                           $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                       });
                   },
                   error: function () {
                       $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                           $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                       });
                   }

               });
               return false;
           } else {
               return false;
           }
       });




    //});//END of function document.ready function()..



</script>