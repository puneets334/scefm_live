<?php if ($listFor == 'total') { ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">        
            <h4>From <?php echo $from_date = date("d-m-Y", strtotime("-7 Days")); ?> TO <?php echo $next_date = date("d-m-Y", strtotime("+7 Days")); ?> </h4>
        </div>
    </div>
<?php } ?>
<?php
$datetoday = new DateTime('today');
$datetomarrow = new DateTime('tomorrow');
$dateyesterday = new DateTime('yesterday');

if ($myUpdatesCount['diary_received'] == 'today') {
    $select_date = $datetoday->format('d-m-Y');
} elseif ($myUpdatesCount['diary_received'] == 'tomorrow') {
    $select_date = $datetomarrow->format('d-m-Y');
} elseif ($myUpdatesCount['diary_received'] == 'yesterday') {
    $select_date = $dateyesterday->format('d-m-Y');
} elseif ($myUpdatesCount['diary_received'] == 'total') {
    $from_date = '17-03-2020';
    $todate = '31-03-2020';
}
$select_date = '2019-08-01';
//echo $select_date;
//echo $datetomarrow->format('d-m-Y');
//echo $dateyesterday->format('d-m-Y');
?>
<div class="row tile_count" >    
    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-4 tile_stats_count">
        <span class="count_top"> Diary Received</span>
        <div class="count"><a href="<?= base_url('myUpdates/lists/diary/' . $listFor . '/diaries/' . url_encryption($select_date)); ?>"><?php echo_data($myUpdatesCount['diary_received']); ?></a></div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-4 tile_stats_count">
        <span class="count_top">Defects</span>
        <div class="count"><a href="<?= base_url('myUpdates/lists/defective/' . $listFor . '/disposed/' . url_encryption($select_date)); ?>"><?php echo_data($myUpdatesCount['defects_notified_count']); ?></a></div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-4 tile_stats_count">
        <span class="count_top">Registered</span>
        <div class="count"><a href="<?= base_url('myUpdates/lists/registered/' . $listFor . '/registered/' . url_encryption($select_date)); ?>"><?php echo_data($myUpdatesCount['registered_count']); ?></a></div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-4 tile_stats_count">
        <span class="count_top">Re-filed</span>
        <div class="count"><a href="<?= base_url('myUpdates/lists/refiled/' . $listFor . '/refiled/' . url_encryption($select_date)); ?>"><?php echo_data($myUpdatesCount['re_filed']); ?></a></div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-4 tile_stats_count">
        <span class="count_top">Caveat</span>
        <div class="count"><a href="<?= base_url('myUpdates/lists/caveat/' . $listFor . '/disposed/' . url_encryption($select_date)); ?>"><?php echo_data($myUpdatesCount['caveat_count']); ?></a></div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-4 tile_stats_count">
        <span class="count_top">IA(s)</span>
        <div class="count"><a href="<?= base_url('myUpdates/lists/ia/' . $listFor . '/ia/' . url_encryption($select_date)); ?>"><?php echo_data($myUpdatesCount['IA_count']); ?></a></div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-4 tile_stats_count">
        <span class="count_top">Misc. Document(s)</span>
        <div class="count"><a href="<?= base_url('myUpdates/lists/miscDoc/' . $listFor . '/disposed/' . url_encryption($select_date)); ?>"><?php echo_data($myUpdatesCount['misc_count']); ?></a></div>
    </div>

    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-4 tile_stats_count">
        <span class="count_top">Disposed</span>
        <div class="count"><a id="mydate" href="<?= base_url('myUpdates/lists/disposed/' . $listFor . '/disposed/' . url_encryption($select_date)); ?>"><?php echo_data($myUpdatesCount['disposed_count']); ?></a></div>
    </div>

    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-4 tile_stats_count">
        <span class="count_top">Judgment(s)</span>
        <div class="count"><a href="<?= base_url('myUpdates/lists/judgment/' . $listFor . '/judgment/' . url_encryption($select_date)); ?>"><?php echo_data($myUpdatesCount['judgment_count']); ?></a></div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-4 tile_stats_count">
        <span class="count_top">Daily Order(s)</span>
        <div class="count"><a href="<?= base_url('myUpdates/lists/dailyOrder/' . $listFor . '/disposed/' . url_encryption($select_date)); ?>"><?php echo_data($myUpdatesCount['daily_count']); ?></a></div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-4 tile_stats_count">
        <span class="count_top">Certified Copy</span>
        <div class="count"><a href="<?= base_url('myUpdates/lists/certCopy/' . $listFor . '/disposed/' . url_encryption($select_date)); ?>"><?php echo_data($myUpdatesCount['certified_copy_count']); ?></a></div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-4 tile_stats_count">
        <span class="count_top">Office Report</span>
        <div class="count"><a href="<?= base_url('myUpdates/lists/officeReport/' . $listFor . '/disposed/' . url_encryption($select_date)); ?>"><?php echo_data($myUpdatesCount['office_report']); ?></a></div>
    </div>

</div> 