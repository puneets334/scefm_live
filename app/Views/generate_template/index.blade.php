@extends('layout.advocateApp')
@section('content')
<style>
    .orcls {
        display: flex;
        align-items: center;
        width: 40px;
        height: 40px;
        font-weight: bold;
    }
</style>
<?php $session = session(); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="dashboard-section dashboard-tiles-area"></div>
			<div class="row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-12">
					<div class="dash-card">
						{{-- Page Title Start --}}
						<div class="title-sec">
							<h5 class="unerline-title"> Generate Template </h5>
							<a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
						</div>
						{{-- Page Title End --}}
						{{-- Main Start --}}
						<div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-6 ">
                                    <?php
                                    echo session()->getFlashdata('message');
                                    if(!empty(validation_errors())) {
                                        ?>
                                        <div class='alert alert-danger'><?php echo validation_errors(); ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php 
                            $attribute = array('class' => 'form-horizontal', 'id' => 'generate_template_id', 'name' => 'generate_template_name', 'autocomplete' => 'off');
                            $url = 'generate_template/GenerateTemplate_Controller/index?case='.$case;
                            echo form_open($url, $attribute);
                            ?>
                                <fieldset class="uk-fieldset">
                                    <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                                        <label><input class="uk-radio" type="radio" value="P" name="case_name" <?= $case == 'P' ? 'checked' : ''; ?>> Petition</label>
                                        <label><input class="uk-radio" type="radio" value="IA" name="case_name" <?= $case == 'IA' ? 'checked' : ''; ?>> IA</label>
                                    </div>
                                    <?php if($case == 'P') { ?>
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <input type="text" onfocus="document.getElementById('diary_no').value = ''" value="<?= session()->getFlashdata('efileno'); ?>" name="efileno" id="efileno" class="form-control cus-form-ctrl" autocomplete="off" placeholder="E-file No"> 
                                            </div>
                                            <div class="col-md-1 col-sm-1 col-xs-1 orcls">OR</div>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <input type="text" onfocus="document.getElementById('efileno').value = ''" value="<?= session()->getFlashdata('diary_no'); ?>" name="diary_no" id="diary_no" class="form-control cus-form-ctrl" autocomplete="off" placeholder="Diary No">
                                            </div>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <select name="diary_year" class="form-control cus-form-ctrl filter_select_dropdown" id="diary_year" aria-label="Diary Year">
                                                    <option value="">Select Year</option>
                                                    <?php for ($year = 2024; $year > 1973; $year--) {
                                                        if(isset($diary_year) && !empty($diary_year)) {
                                                            ?>
                                                            <option value="<?= $year; ?>" <?= $diary_year == $year ? 'selected' : ''; ?>><?= $year; ?></option>
                                                        <?php }else{ ?>
                                                            <option value="<?= $year; ?>" <?= session()->getFlashdata('diary_year') == $year ? 'selected' : ''; ?>><?= $year; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                    } else{
                                        ?>
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <input type="text" onfocus="document.getElementById('efileno').value = ''" value="<?= session()->getFlashdata('diary_no'); ?>" name="diary_no" id="diary_no" class="form-control cus-form-ctrl" autocomplete="off" placeholder="Diary No">
                                            </div>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <select name="diary_year" class="form-control cus-form-ctrl filter_select_dropdown" id="diary_year" aria-label="Diary Year">
                                                    <option value="">Select Year</option>
                                                    <?php for ($year = 2024; $year > 1973; $year--) {
                                                        if(isset($diary_year) && !empty($diary_year)) {
                                                            ?>
                                                            <option value="<?= $year; ?>" <?= $diary_year == $year ? 'selected' : ''; ?>><?= $year; ?></option>
                                                        <?php }else{
                                                            ?>
                                                            <option value="<?= $year; ?>" <?= session()->getFlashdata('diary_year') == $year ? 'selected' : ''; ?>><?= $year; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="row mt-3">
                                        <div class="col-md-3 col-sm-3 col-xs-3 ">
                                            <label for="remarks">Template Type</label>
                                            <select name="sc_template_id" id="sc_template_id" class="form-control cus-form-ctrl filter_select_dropdown">
                                                <option value="">Select Template Type</option>
                                                <?php if(count($sc_case_types)) {
                                                    foreach ($sc_case_types as $sc_case_type) {
                                                        if(isset($template_id)) {
                                                            ?>
                                                            <option <?= $template_id == $sc_case_type->id ? 'selected' : ''; ?> value="<?= $sc_case_type->id; ?>"><?= $sc_case_type->name; ?></option>
                                                        <?php }else{ ?>
                                                            <option <?= session()->getFlashdata('template_id') == $sc_case_type->id ? 'selected' : ''; ?> value="<?= $sc_case_type->id; ?>"><?= $sc_case_type->name; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-3 ">
                                            <label>&nbsp;</label>
                                            <div class="form-group">&nbsp;
                                                <button type="submit" id="only_serach" class="quick-btn">Download</button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            <?= form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>     
    </div>
</div>
@endsection
@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('input[name=case_name]').change(function() {
            if($(this).val()=='IA') {
                document.location.href="{{base_url('generate_template/GenerateTemplate_Controller/index')}}?case=IA"
            } else{
                document.location.href="{{base_url('generate_template/GenerateTemplate_Controller/index')}}?case=P"
            }
        });
    });
    if ($('#only_serach').length > 0) {
        var only_serach_element = document.getElementById("only_serach");
        only_serach_element.onclick = function() {
            setTimeout(function() {
               $('#loading-overlay').hide();
               window.location.replace(window.location.href);
            }, 2000);
        };
    }
</script>
@endpush