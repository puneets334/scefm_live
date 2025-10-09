<?php
$segment = service('uri');
?>

@include('miscellaneous_docs.misc_docs_breadcrumb')
<?php if ($segment->getSegment(1) == 'uploadDocuments' || $segment->getSegment(1) == 'documentIndex') {

    //$this->load->view('uploadDocuments/upload_document');?>
    @include('documentIndex.documentIndex_view')
<?php } elseif ($segment->getSegment(1) == 'documentIndex') {?>

    @include('documentIndex.documentIndex_view')
<?php } elseif ($segment->getSegment(1) == 'affirmation') {?>

    @include('affirmation.affirmation_view')
<?php } elseif ($segment->getSegment(2) == 'courtFee') {
    if(getSessionData('efiling_details')['doc_govt_filing']==1) { ?>
    @include('newcase.courtFee_govt_view')
    <?php }else{ ?>
        @include('miscellaneous_docs.courtFee_view')
<?php }
} elseif ($segment->getSegment(2) == 'view') { ?>

    @include('miscellaneous_docs.misc_docs_preview')
<?php }
?>
@include('modals')
</div>
</div>
</div>
</div>             
</div>
</div>
</div>