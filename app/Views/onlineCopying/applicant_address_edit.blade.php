<div class="row m-1" id="modal">
    <div class="form-row">
        <div class="input-group col-12 mt-4 mb-4">
            <!-- <fieldset class="the-fieldset"> -->
                <legend class="the-legend bg-light col-3 text-black">Address Type <span style="color: red;">*</span> </legend>
                <label class="col-2 ml-3 radio-inline text-black">
                    <input type="radio" name="modal_rdbtn_select" id="modal_home_address_type" value="Home" <?=$address_type == 'Home' ? 'checked' : '' ?>> Home
                </label>
                <label class="col-2 ml-3 radio-inline">
                    <input type="radio" name="modal_rdbtn_select" id="modal_work_address_type" value="Work" <?=$address_type == 'Work' ? 'checked' : '' ?>> Work
                </label>
                <label class="col-2 ml-3 radio-inline">
                    <input type="radio" name="modal_rdbtn_select" id="modal_other_address_type" value="Other" <?=$address_type == 'Other' ? 'checked' : '' ?>> Other
                </label>
            <!-- </fieldset> -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="input-group mb-3">
                <label class="input-group-text" id="applicant_first_name_addon">Full Name <span style="color: red;">*</span></label>
                <input type="text" class="form-control cus-form-ctrl" value="<?= $first_name ?>" id="modal_applicant_first_name" aria-labelledby="applicant_first_name_addon" readonly>
                <p id="p1"></p> <!--This Segment Displays The Validation Rule For Name-->
            </div>
        </div>
        <!-- <div class="col-md-4">
            <div class="input-group mb-3">
            <label class="input-group-text" id="applicant_second_name_addon">Second Name <span style="color: red;">*</span></label>
                <input type="text" class="form-control cus-form-ctrl" value="<?// = $second_name ?>" id="modal_applicant_second_name" aria-labelledby="applicant_second_name" readonly>
            </div>
        </div> -->
        <div class="col-md-4">
            <div class="input-group mb-3">
            <label class="input-group-text" id="pincode_addon">Pincode <span style="color: red;">*</span></label>
                <input type="text" class="form-control cus-form-ctrl" value="<?= $pincode ?>" id="modal_pincode" aria-labelledby="pincode_addon" maxlength='6' required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group mb-3">
            <label class="input-group-text" id="postal_add_addon">Address <span style="color: red;">*</span></label>
                <input type="text" class="form-control cus-form-ctrl" value="<?= $address ?>" id="modal_postal_add" aria-labelledby="postal_add_addon"  required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="input-group mb-3">
            <label class="input-group-text" id="applicant_city_addon">City <span style="color: red;">*</span></label>
                <input type="text" class="form-control cus-form-ctrl" value="<?= $city ?>" id="modal_applicant_city" aria-labelledby="applicant_city_addon"  required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group mb-3">
            <label class="input-group-text" id="applicant_district_addon">District <span style="color: red;">*</span></label>
                <input type="text" class="form-control cus-form-ctrl" value="<?= $district ?>" id="modal_applicant_district" aria-labelledby="applicant_district_addon"  required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group mb-3">
            <label class="input-group-text" id="applicant_state_addon">State <span style="color: red;">*</span></label>
                <input type="text" class="form-control cus-form-ctrl" value="<?= $state ?>" id="modal_applicant_state" aria-labelledby="applicant_state_addon" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="input-group mb-3">
            <label class="input-group-text" id="applicant_country_addon">Country <span style="color: red;">*</span></label>
                <input type="text" class="form-control cus-form-ctrl" id="modal_applicant_country" aria-labelledby="applicant_country_addon" value="<?= $country ?>"  required>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
    <button id="btn_address_edit_action" class="btn btn-success" type="button" data-button_action_type="edit" data-address_table_id="<?=$address_id?>">Edit/Save</button>
</div>
<div id="result" class="show_msg_modal" style="overflow: auto;"></div>