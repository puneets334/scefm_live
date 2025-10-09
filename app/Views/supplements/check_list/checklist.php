<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5">Advocate CheckList </h4>
</div>
    <?php
        echo $this->session->flashdata('msg');
        echo $this->session->flashdata('msg_error');
        $attribute = array('name' => 'add_checklist_details', 'id' => 'add_checklist_details', 'autocomplete' => 'off');
        echo form_open('supplements/DefaultController/checklist', $attribute);
        foreach ($form_data as $field){
    ?>
<div class="panel panel-default">
    <div class="panel-body">
        <?php if($field['question_no']=='16.a'){ ?>
        <label style="width: 25px;">16. </label><label style="font-weight: normal">In a petition (PIL) under clause (d) of Rule 12(1) Order
            XXXVIII, the petition has disclosed</label><br/>
        <?php } ?>
        <label style="width: 25px;"><?=$field['question_no']?>. </label><label style="font-weight: normal"> &nbsp;<?=$field['question']?></label><br/>
        <label class="radio-inline">
            <input type="radio" name="<?=$field['field_name']?>" value="Y" <?php if($filled_data[$field['field_name']]=='Y') echo 'checked';?>>Yes
        </label>
        <label class="radio-inline">
            <input type="radio" name="<?=$field['field_name']?>" value="N" <?php if($filled_data[$field['field_name']]=='N') echo 'checked';?>>No
        </label>
        <label class="radio-inline">
            <input type="radio" name="<?=$field['field_name']?>" value="NA" <?php if($filled_data[$field['field_name']]=='NA') echo 'checked';?>>NA
        </label>
    </div>
</div>

<?php } ?>


<div class="span7 text-center">
<input type="submit" tabindex = '16' class="btn btn-success" name="checklist_save" id="checklist_save" value="SAVE">
<?php echo form_close(); ?>
</div>
</div>
