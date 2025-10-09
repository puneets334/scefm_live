<?php
$this->load->view('mentioning/mentioning_breadcrumb');

if ($this->uri->segment(1) == 'uploadDocuments') {

    $this->load->view('uploadDocuments/upload_document');
} elseif ($this->uri->segment(1) == 'documentIndex') {

    $this->load->view('documentIndex/documentIndex_view');
} elseif ($this->uri->segment(1) == 'affirmation') {

    $this->load->view('affirmation/affirmation_view');
} elseif ($this->uri->segment(2) == 'courtFee') {

    $this->load->view('mentioning/courtFee_view');
} elseif ($this->uri->segment(2) == 'view') {

    $this->load->view('mentioning/mentioning_preview');
}
?>
</div>
</div>
</div>
</div>             
</div>
</div>
</div>