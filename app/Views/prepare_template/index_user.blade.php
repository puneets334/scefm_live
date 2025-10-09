@extends('layout.advocateApp')
@section('content')
<link rel="stylesheet" href="<?=base_url('/assets/editor/jodit/jodit.min.css');?>">
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
							<h5 class="unerline-title"> Download Template </h5>
							<a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
						</div>
						{{-- Page Title End --}}
						{{-- Main Start --}}
						<div class="panel-body">
                     <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6 center-block">
                           <?php
                           echo session()->getFlashdata('message');
                           if(!empty(validation_errors())) {
                              ?>
                              <div class='alert alert-danger'><?php echo validation_errors(); ?></div>
                           <?php } ?>
                        </div>
                     </div>
                     <?php 
                     $attribute = array('class' => 'form-horizontal', 'id' => 'create_update_template', 'name' => 'search_case_details_pdf', 'autocomplete' => 'off');
                     $url = 'admin/PrepareTemplate_Controller/prepared_templates_download?case='.$case;
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
                                    <div class="form-group">
                                       <input type="text" onfocus="document.getElementById('diary_no').value = ''" value="<?= session()->getFlashdata('efileno'); ?>" name="efileno" id="efileno" class="form-control cus-form-ctrl" autocomplete="off" placeholder="E-file No">
                                    </div>
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
                              <div class="col-md-3 col-sm-3 col-xs-3 center-block">
                                 <div class="form-group">
                                    <label for="remarks">Template Type</label>
                                    <select name="sc_template_type" id="sc_template_type" class="form-control cus-form-ctrl filter_select_dropdown">
                                       <option value="">Select Template Type</option>                                    
                                       <?php
                                       if(count($sc_case_types)) {
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
                              </div>
                              <div class="col-md-3 col-sm-3 col-xs-3 center-block">
                                 <label>&nbsp;</label>
                                 <div class="form-group">&nbsp;
                                    <input type="hidden" name="download" id="downloadinput" value="0">
                                    <button type="submit" id="only_serach" class="quick-btn">Search</button>
                                 </div>
                              </div>
                              <?php if(isset($template_type) && !empty($template_type)) { ?>
                                 <div class="col-md-3 col-sm-3 col-xs-3">
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
                           <hr>                  
                           <?php if(isset($created_template) && !empty($created_template)) { ?>
                              <div class="row">
                                 <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                       <label for="template_text">Template Text</label>
                                       <textarea id="template_text" name="template_text"><?= $created_template; ?></textarea>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-4 col-sm-4 col-xs-4">
                                    <label>&nbsp;</label>
                                    <div class="form-group">
                                       <button type="submit" id="download_pdf" class="quick-btn">Download</button>
                                    </div>
                                 </div>
                              </div>
                           <?php } ?>
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
<script src="<?=base_url('/assets/editor/jodit/jodit.min.js');?>"></script>
<script>
   if ($('#template_text').length > 0) {
      const editor = Jodit.make("#template_text", {
         "autofocus": true,
         "useSearch": false,
         "askBeforePasteHTML": false,
         "minHeight": 700
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
      var template_type = '<?= isset($template_type) ? $template_type : '';?>';
      $('input[name=case_name]').change(function() {
         if($(this).val()=='IA') {
            document.location.href="{{base_url('admin/PrepareTemplate_Controller/prepared_templates_download')}}?case="+$(this).val()
         } else{
            document.location.href="{{base_url('admin/PrepareTemplate_Controller/prepared_templates_download')}}?case="+$(this).val()
         }
      });
      if ($('#download_pdf').length > 0) {
         var download_pdf_element = document.getElementById("download_pdf");
         download_pdf_element.onclick = function() {
            document.getElementById('downloadinput').value='1'; 
            setTimeout(function() {
               $('#loading-overlay').hide();
               window.location.replace(window.location.href);
            }, 2000);
         };
      }
      if ($('#only_search').length > 0) {
         var only_search_element = document.getElementById("only_search");
         only_search_element.onclick = function() {
            document.getElementById('downloadinput').value='0';
            setTimeout(function() {
               $('#loading-overlay').hide();
               window.location.replace(window.location.href);
            }, 2000);
         };
      }
   });
</script>
@endpush