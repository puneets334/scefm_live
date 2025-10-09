<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">        
        <h4>Total : <?= isset($aor_cause_list) ? count($aor_cause_list) : 0; ?> </h4>
    </div>
</div>
<div class="box-body">
    <table class="table table-hover" style="border-left: 1px solid #cdd0d4;border-right: 1px solid #cdd0d4;border-bottom: 1px solid #cdd0d4;">
        <tr>
            <th>Sr.No.</th>
            <th>Case No.</th>
            <th>Cause Title</th>
            <th>List Dated</th>
            <th>Causelist Type</th>
            <?php if ($param[1] == 3) { ?>
                <th>Elimination Reason</th>
            <?php } ?>
        </tr>
        <?php
        $mc_sr_no = 1;
        foreach ($aor_cause_list as $row) {
            ?>
            <tr style="border-spacing: 10em !important;">
                <td width="10%"> <?= $mc_sr_no++ ?> </td>
                <td width="25%" > <?= $row['registration_number_desc'] ?>&nbsp;<br>(D No.<?= $row['diary_no'] ?>)<br>
                    <?php if ($row['main_or_connected'] != 'Main') { ?> (<strong> <?= $row['main_or_connected'] ?> Matter</strong>) <?php } ?>
                </td>
                <td width="20%" ><?= $row['petitioner_name'] ?><br><span style="text-align: center; margin-left: 50px;"><strong >Vs.</strong></span><br><?= $row['respondent_name'] ?></td>
                <td width="15%"><?= date('d-m-Y', strtotime($row['listing_date'])); ?><br>
                    <?php if ($param[1] == 1) { ?>
                        Court No. : <?= $row['court_number'] ?><br>
                        Item No. : <?= $row['item_number'] ?>
                    <?php } ?>
                </td>
                <td width="10%"><?= $row['list_type'] ?></td>
                <?php if ($param[1] == 3) { ?>
                    <td width="15%"><?= $row['reason'] ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>
</div>
