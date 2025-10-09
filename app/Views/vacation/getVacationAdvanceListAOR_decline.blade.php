<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
<?php
if (!is_vacation_advance_list_duration()) {
    echo 'Advance Summer Vacation List not authorized access';
    exit();
}
$mainhaed_text = 'REGULAR';
if (isset($_GET['mainhead']) && $_GET['mainhead'] == 'M') {
    $mainhaed_text = 'MISCELLANEOUS';
}
// echo 'Vacation Advance List Duration='.$is_action;
?>
<h4 class="mt-2" style="text-align: center;">LIST OF <b><?php echo $mainhaed_text; ?></b> MATTERS DECLINED BY SELF</h4>
<table class="table table-striped custom-table text-left" id="datatable-responsive1">
    <thead>
        <tr>
            <th>#</th>
            <th>Case No @ Diary No.</th>
            <th>Cause Title</th>
            <th>Advocate</th>
            <!-- <th width="30%">Advocate</th>-->
            <th>Decline/Listed</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $psrno = "1";
        $srNo = 0;
        if (!empty($vacation_advance_list)) {
            foreach ($vacation_advance_list as $r) {
                // while ($r = $stmt->fetch()) {
                if ($r['diary_no'] == $r['conn_key'] or $r['conn_key'] == 0) {
                    // $print_brdslno = $row['brd_slno'];
                    $print_brdslno = $psrno;
                    $print_srno = $psrno;
                    $con_no = "0";
                    $is_connected = "";
                } else if ($r['main_or_connected'] == 1) {
                    $print_brdslno = "&nbsp;" . $print_srno . "." . ++$con_no;
                    $is_connected = "<span style='color:red;'>Connected</span><br/>";
                }
                // $srNo++;
                $srNo++;
                ?>
                <tr>
                    <td>
                        <?= $srNo; ?>
                        <?php
                        if ($is_connected != '') {
                            // $print_srno = "";
                        } else {
                            $print_srno = $print_srno;
                            $psrno++;
                        }
                        ?>
                    </td>
                    <td style="text-align: left;">
                        <?= $r['case_no']; ?>
                        <br>
                        <?php
                        if ($r['main_or_connected'] == 1) {
                            echo "<span style='color:red;'>Connected</span><br/>";
                        }
                        ?>
                    </td>
                    <td style="text-align: left;"><?= sprintf('%s',  $r['cause_title']); ?></td>
                    <td style="text-align: left;"><?= sprintf('%s',  $r['advocate']); ?></td>
                    <td>
                        <?PHP
                        if ($r['declined_by_aor'] == 't') {
                            $diary_no = $r['diary_no'];
                            ?>
                            <span style="color: red;">Declined</span>
                            <?php
                        } else {
                            echo '<span style="color: red;">Pending Declined</span>';
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
        } else{
            ?>
            <tr>
                <td colspan="5"><span class="text-center text-danger fw-bold">No Records Found</span></td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Case No @ Diary No.</th>
            <th style="width:25%;">Cause Title</th>
            <th style="width:25%;">Advocate</th>
            <!-- <th>Advocate</th>-->
            <th width="15%">Decline/Listed</th>
        </tr>
    </tfoot>
</table>
<hr>
<br><br>
<?php
if (!empty($matters_declined_by_counter)) {
    ?>
    <h4 class="mt-2" style="text-align: center;">LIST OF <b><?php echo $mainhaed_text; ?></b> MATTERS DECLINED BY COUNTER PART</h4>
    <table class="table table-striped custom-table text-left" id="datatable-responsive2">
        <thead>
            <tr>
                <th>#</th>
                <th>Case Details</th>
                <th style="width:50%">Message</th>
                <th>Decline Date@Time</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td style="text-align: left;"></td>
                <td style="text-align: left;"></td>
                <td style="text-align: left;"></td>
            </tr>
            <?php
            $psrno1 = "1";
            $srNo1 = 0;
            foreach ($matters_declined_by_counter as $r1) {
                $srNo1++;
                ?>
                <tr>
                    <td><?= $srNo1; ?></td>
                    <td style="text-align: left;"><?= $r1['case_no']; ?></td>
                    <td style="text-align: left;"><?= sprintf('%s',  $r1['msg']); ?></td>
                    <td style="text-align: left;"><?= sprintf('%s',  $r1['updated_on']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Case Details</th>
                <th>Message</th>
                <th>Decline Date@Time</th>
            </tr>
        </tfoot>
    </table>
<?php } ?>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/angular.min.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/moment.min.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/jquery.validate.min.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable-responsive1').DataTable({
            paging: true,
            // scrollX: true,
            lengthChange : true,
            searching: true,
            ordering: true
        });
        $('#datatable-responsive2').DataTable({
            paging: true,
            // scrollX: true,
            lengthChange : true,
            searching: true,
            ordering: true
        });
    });
</script>