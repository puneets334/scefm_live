<link rel="shortcut icon" href="<?= base_url() . 'assets/newDesign/images/logo.png' ?>" type="image/png" />
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
    echo '<p style="background-color: lightgray; padding: 10px; border: 1px solid black; border-radius: 10px; color: red;">Advance Summer Vacation List not authorized access</p>';
    exit();
}
$mainhaed_text = 'REGULAR';
if (isset($_GET['mainhead']) && $_GET['mainhead'] == 'M') {
    $mainhaed_text = 'MISCELLANEOUS';
}
// echo 'Vacation Advance List Duration='.$is_action;
$attribute = array('class' => 'form-horizontal', 'name' => 'vacation', 'id' => 'vacation', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
echo form_open(base_url('vacation_supp/advance/declineVacationListCasesAOR'), $attribute);

form_close();
?>
<h4 class="mt-2" style="text-align: center;"> LIST OF <b><?php echo $mainhaed_text; ?></b> MATTERS FOR CONSENT</h4>
<div align="right">
    <button id="declineButton" class="btn btn-primary btn-success text-center pull-right mb-2" onclick="javascript:confirmBeforeDecline();"><strong>Decline Selected Case(s)</strong></button>
</div>
<input type="hidden" id="aorCode" name="aorCode" value="<?= getSessionData('login.aor_code') ?>">
<input type="hidden" id="mainhead" name="mainhead" value="<?= $mainhead; ?>">
<input type="hidden" id="fromIP" name="fromIP" value="<?= getClientIP() ?>">
<table class="table table-striped custom-table text-left" id="datatable-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Case Details</th>
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
                    <!--   <td ><?/*=date('d-m-Y', strtotime($r['filing_date']));*/ ?></td>-->
                    <!--<td><?/*=$r['advocate'];*/ ?></td>-->
                    <td>
                        <?php
                        if (!empty($r['declined_by_aor']) && $r['declined_by_aor'] === 't') {
                            $diary_no = $r['diary_no'];
                            ?>
                            <a class='btn btn-xs btn-primary text-center' title="List" onclick="confirmBeforeList(<?= $diary_no; ?>)">Restore</a><br />
                            <span style="color: red;">Declined</span>
                            <?php
                        } else {
                            if ($r['is_fixed'] != 'Y') {
                                echo "<input type='checkbox' name='vacationList' id='vacationList' value='$r[diary_no]'>";
                            } else {
                                echo "<span style='color:green;'>Fixed For <br> Vacation</span><br/>";
                            }
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="5"><span class="text-center text-danger fw-bold">No Records Found</span></td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Case Details</th>
            <th style="width:25%;">Cause Title</th>
            <th style="width:25%;">Advocate</th>
            <!-- <th>Advocate</th>-->
            <th width="15%">Decline/Listed</th>
        </tr>
    </tfoot>
</table>
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
        $('#datatable-responsive').DataTable({
            paging: true,
            // scrollX: true,
            lengthChange: true,
            searching: true,
            ordering: true
        });
        $('#datatable-responsive1').DataTable({
            paging: true,
            // scrollX: true,
            lengthChange: true,
            searching: true,
            ordering: true
        });
        $('#datatable-responsive2').DataTable({
            paging: true,
            // scrollX: true,
            lengthChange: true,
            searching: true,
            ordering: true
        });
    });
    function confirmBeforeDecline() {
        var allVals = [];
        var noOfCases;
        $("input:checkbox[name=vacationList]:checked").each(function() {
            allVals.push($(this).val());
        });
        noOfCases = allVals.length;
        if (noOfCases < 1) {
            alert('Please select atleast one Case which need to be Decline');
            return false;
        } else {
            var choice = confirm("I hereby agree with the Notice dated 15-04-2025 and matter so declined are after serving a copy there of on other side for Not Listing the case before the Bench during forthcoming Partial Court Working days, <?= $current_year = date('Y'); ?>. \n\n  Do you really want to decline the case.....?");
            if (choice === true) {
                declineVacationCase(allVals);
            } else
                return false;
        }
    }
    function confirmBeforeList(diary_no) {
        var choice = confirm('Do you really want to List the Selected Case.....?');
        if (choice === true) {
            ListVacationCase(diary_no);
        }
    }
    function declineVacationCase(allVals) {
        var userIP = $('#fromIP').val();
        // var userID=$('#user_mid').val();
        var userID = '<?php echo getSessionData('login.id'); ?>';
        var aorCode = $('#aorCode').val();
        var mainhead = $('#mainhead').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#msg').hide();
        $(".form-response").html('');
        $.ajax({
            url: "<?= base_url('vacation_supp/advance/declineVacationListCasesAOR') ?>",
            type: "POST",
            data: {
                diary_no: allVals,
                userIP: userIP,
                userID: userID,
                aorCode: aorCode,
                mainhead: mainhead,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(data) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    alert(resArr[1]);
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    alert('Selected Case with Diary No:(' + allVals + ') Successfully Declined');
                    location.reload();
                }
                // getVacationAdvanceListAOR();
            },
            error: function() {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
                alert('Selected Case with Diary No:(' + allVals + ') Successfully Declined');
            }
        });
    }
    function ListVacationCase(diary_no) {
        var userIP = $('#fromIP').val();
        var userID = $('#user_mid').val();
        var aorCode = $('#aorCode').val();
        userIP = $('#fromIP').val();
        var mainhead = $('#mainhead').val();
        // var userID=$('#user_mid').val();
        var userID = '<?php echo getSessionData('login.id'); ?>';
        aorCode = $('#aorCode').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#msg').hide();
        $(".form-response").html('');
        $.ajax({
            url: "<?= base_url('vacation_supp/advance/restoreVacationAdvanceListAOR') ?>",
            type: "POST",
            data: {
                diary_no: diary_no,
                userIP: userIP,
                userID: userID,
                aorCode: aorCode,
                mainhead: mainhead,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(data) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    alert(resArr[1]);
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                } else if (resArr[0] == 2) {
                    alert('Selected Case with Diary NO:' + diary_no + ' Successfully Restore');
                    location.reload();
                }
                // getVacationAdvanceListAOR();
            },
            error: function() {
                alert('ERROR');
            }
        });
    }
</script>