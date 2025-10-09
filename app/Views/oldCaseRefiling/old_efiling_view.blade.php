<?php
$segment = service('uri');
?>
@include('oldCaseRefiling.old_efiling_breadcrumb')


<?php if ($segment->getSegment(1) == 'uploadDocuments' || $segment->getSegment(1) == 'documentIndex') { ?>

    @include('documentIndex.documentIndex_view')
<?php } elseif ($segment->getSegment(1) == 'documentIndex') {?>

    @include('documentIndex.documentIndex_view')
<?php } elseif ($segment->getSegment(1) == 'affirmation') { ?>

    @include('affirmation.affirmation_view')
<?php } elseif ($segment->getSegment(2) == 'courtFee') { ?>
    
    @include('oldCaseRefiling.courtFee_view')
<?php } elseif ($segment->getSegment(2) == 'view') {?>
    @include('oldCaseRefiling.old_efiling_preview')
<?php }
?>
</div>
</div>
</div>
</div>             
</div>
</div>
</div>