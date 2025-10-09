<div class="tab-form-inner">
	<div class="row">
		<h6 class="text-center fw-bold">Upload Document / Index</h6>
	</div>
	<?= ASTERISK_RED_MANDATORY ?>
	<h5 style="text-align: left;" class="mb-4">All documents, Interlocutory Applications, if any, along with main petition are to be uploaded using this feature.</h5>
	<?php
	$attribute = array('class' => 'form-horizontal', 'name' => 'uploadDocument', 'id' => 'uploadDocument', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
	echo form_open('#', $attribute);
	?>
		<div class="row">
			<?php if (getSessionData('login')['ref_m_usertype_id'] == JAIL_SUPERINTENDENT) { ?>
				<div class="col-12 col-sm-12 col-md-4 col-lg-4">
					<div class="mb-3">
						<label for="" class="form-label">Proforma for Custody and Surrender Certificate.Download,fill and upload.</label>
						<a href="<?= base_url('uploadDocuments/DefaultController/file_download/S') ?>"> Download Surrender Certificate</a>
						<a href="<?= base_url('uploadDocuments/DefaultController/file_download/C') ?>"> Download Custody Certificate</a>
          </div>
				</div>
			<?php } ?>
			<div class="col-12 col-sm-12 col-md-4 col-lg-4">
				<div class="mb-3">
					<label for="" class="form-label">Title<span style="color: red" class="astriks">*</span></label>

					<textarea tabindex="2" rows="1" id="doc_title" name="doc_title" minlength="3" maxlength="75" class="form-control cus-form-ctrl" placeholder="PDF Title" type="text" style="text-transform: uppercase" required ></textarea>
					<!-- <input type="text" class="form-control cus-form-ctrl" tabindex="2" name="doc_title" id="doc_title" required="" placeholder="PDF Title" minlength="3" maxlength="75" style="text-transform: uppercase"> -->
					<span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="PDF title max. length can be 75 characters only.  Only numbers, letters, spaces, hyphens,dots and underscores are allowed." data-original-title="" title="PDF title max. length can be 75 characters only.  Only numbers, letters, spaces, hyphens,dots and underscores are allowed.">
						<i class="fa fa-question-circle-o"></i>
					</span>
				</div>
			</div>
			<div class="col-12 col-sm-12 col-md-8 col-lg-8">
				<div class="mb-3">
					<label for="" class="form-label">Browse PDF <span style="color: red">*</span></label>
					<input name="pdfDocFile" id="browser" tabindex="3" class="form-control cus-form-ctrl" required="required" accept="application/pdf" type="file">
				</div>
			</div>
		</div>
		<div class="row">
			<?php
			if (!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
				$prev_url = base_url('newcase/extra_party');
				$next_url = base_url('documentIndex');
			} elseif (!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
				$prev_url = base_url('on_behalf_of');
				$next_url = base_url('documentIndex');
			} elseif (!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
				$prev_url = base_url('on_behalf_of');
				$next_url = base_url('documentIndex');
			} elseif (!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
				$prev_url = base_url('on_behalf_of');
				$next_url = base_url('mentioning/courtFee');
			} elseif (!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_JAIL_PETITION) {
				$prev_url = base_url('jailPetition/Subordinate_court');
				$next_url = base_url('affirmation');
			} else if (!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
				$prev_url = base_url('caveat/subordinate_court');
				$next_url = base_url('documentIndex');
			} else {
				$prev_url = '#';
				$next_url = '#';
			}
			?>
			<div class="col-12 col-sm-12 col-md-12 col-lg-12 my-3">
				<div class="text-center">
					<button type="submit" class="quick-btn" id="upload_doc" value="UPLOAD">UPLOAD</button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="progress mb-3" style="display: none">
				<div class="progress-bar progress-bar-success myprogress" role="progressbar" value="0" max="100" style="width:0%">0%</div>
			</div>
		</div>
	<?php echo form_close(); ?>
</div>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<?php
if (!empty($uploaded_docs)) {
	echo '<script>$("#nextButton").show();</script>';
	render('uploadDocuments.uploaded_doc_list', ['uploaded_docs' => @$uploaded_docs]);
}
?>