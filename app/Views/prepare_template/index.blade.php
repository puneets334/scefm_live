@extends('layout.advocateApp')
@section('content')
<link rel="stylesheet" href="<?=base_url('/assets/editor/jodit/jodit.min.css');?>">
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
         <div class="dashboard-section dashboard-tiles-area"></div>
			<div class="row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-12">
					<div class="dash-card">
						{{-- Page Title Start --}}
						<div class="title-sec">
							<h5 class="unerline-title"> Prepare Template </h5>
							<a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
						</div>
						{{-- Page Title End --}}
						{{-- Main Start --}}
						<div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-6 center-block">
                                    {{$this->session->flashdata('message')}}
                                    @if(!empty(validation_errors()))
                                    <div class='alert alert-danger'>{{validation_errors()}}</div>
                                    @endif
                                </div>
                            </div>
                            <?php 
                            $attribute = array('class' => 'form-horizontal', 'id' => 'create_update_template', 'name' => 'search_case_details_pdf', 'autocomplete' => 'off');
                            if(isset($template_type) && !empty($template_type)) {
                                $url = 'admin/PrepareTemplate_Controller?template_type='.$template_type;
                            } else{
                                $url = 'admin/PrepareTemplate_Controller';
                            }
                            echo form_open($url, $attribute);
                            ?>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <div class="form-group">
                                            <label for="remarks">Template Type</label>
                                            <select name="template_id" id="template_id" class="form-control">
                                                <option value="">Select Template Type</option>
                                                <?php
                                                if (count($templates)) {
                                                    foreach ($templates as $template) {
                                                        ?>
                                                        <option <?= $template_type == $template->id ? 'selected' : ''; ?> value="<?= $template->id; ?>"><?= $template->name; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php if(isset($template_type) && !empty($template_type)) { ?>
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <label for="remarks">Variables:</label>
                                            <?php
                                            if (count($templates)) {
                                                foreach ($templates as $template) {
                                                    if($template_type==$template->id) {
                                                        echo $template->variables;
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    <?php } ?>
                                </div>                        
                                <?php if(isset($template_type) && !empty($template_type)) { ?>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label for="template_text">Template Text</label>
                                            <textarea id="template_text" name="template_text"><?= $created_template->template_text; ?></textarea>
                                        </div>
                                    </div>
                                <?php } ?>                        
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <label>&nbsp;</label>
                                        <div class="form-group">
                                            <?php if(!empty($this->session->userdata('login')['ref_m_usertype_id']) && $this->session->userdata('login')['ref_m_usertype_id'] == USER_ADVOCATE) { ?>
                                                <button type="submit" class="quick-btn Download1 btn-block">Download</button>
                                            <?php } else{ ?>
                                                <button type="submit" class="quick-btn Download1 btn-block">Save</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
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
<script src="<?=base_url('/assets/editor/jodit/jodit.min.js');?>"></script>
<script>
    if ($('#template_text').length > 0) {
        const editor = Jodit.make("#template_text", {
            "autofocus": true,
            "useSearch": false,
            "askBeforePasteHTML": false,
            "minHeight": 700,
        });
    }
    $(document).ready(function() {
        $("#template_id").change(function() {
            if(this.value) {
                document.location.href="{{base_url('admin/PrepareTemplate_Controller')}}?template_type="+this.value;
            } else{
                document.location.href="{{base_url('admin/PrepareTemplate_Controller')}}";
            }            
        });
    });
</script>
@endpush