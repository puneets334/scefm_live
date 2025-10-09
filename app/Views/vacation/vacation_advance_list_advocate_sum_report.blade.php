<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Information regarding matters to be listed during Partial Court Working Days</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            background-color: #00a6c9;
            color: white;
            font-weight: bold;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
        }
        .button-container {
            margin-top: 20px;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .table-bordered>thead>tr>th,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>tbody>tr>td,
        .table-bordered>tfoot>tr>td {
            border: 1px solid #000 !important;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #000000;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .05);
        }
    </style>
</head>
<body>
    <div class="container" id="result">
        <div class="row">
            <div>
                <br />
                <div class="header">
                    Statistics to be listed during Partial Court Working Days <?= date('Y'); ?>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="3" style="vertical-align: middle;">Description</th>
                            <th colspan="2">Total No. of Matters notified with Notice dated <?php echo $notice_date_main; ?></th>
                            <th colspan="2">No. of Matters in which more than 50% Advocates have declined</th>
                            <th colspan="2">No. of matters in which upto 50% Advocates have declined</th>
                            <th colspan="2">Matters in which none of the Advocates have
                                declined</th>
                            <th colspan="2">Matters in which there are no Advocates</th>
                        </tr>
                        <tr>
                            <th>Main Matters</th>
                            <th>Connected Matters</th>
                            <th>Main Matters</th>
                            <th>Connected Matters</th>
                            <th>Main Matters</th>
                            <th>Connected Matters</th>
                            <th colspan="1">Main Matters</th>
                            <th colspan="1">Connected Matters</th>
                            <th colspan="1">Main Matters</th>
                            <th colspan="1">Connected Matters</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Miscellaneous Matters</td>
                            <td><?php echo $main2['total_misc_main_matters']; ?></td>
                            <td><?php echo $main2['total_misc_conn_matters']; ?></td>
                            <td><?php echo isset($data['M']) ? $data['M']['main_more_than_50p'] : ''; ?></td>
                            <td><?php echo isset($data['M']) ? $data['M']['conn_more_than_50p'] : ''; ?></td>
                            <td><?php echo isset($data['M']) ? $data['M']['main_upto_50p'] : ''; ?></td>
                            <td><?php echo isset($data['M']) ? $data['M']['conn_upto_50p'] : ''; ?></td>
                            <td><?php echo isset($data['M']) ? $data['M']['main_no_consent'] : ''; ?></td>
                            <td><?php echo isset($data['M']) ? $data['M']['conn_no_consent'] : ''; ?></td>
                            <td><?php echo isset($data1['M']) ? $data1['M']['main'] : ''; ?></td>
                            <td><?php echo isset($data1['M']) ? $data1['M']['conn'] : ''; ?></td>
                        </tr>
                        <tr>
                            <td>Regular Hearing Matters</td>
                            <td><?php echo $main2['total_regular_main_matters']; ?></td>
                            <td><?php echo $main2['total_regular_conn_matters']; ?></td>
                            <td><?php echo $data['F']['main_more_than_50p']; ?></td>
                            <td><?php echo $data['F']['conn_more_than_50p']; ?></td>
                            <td><?php echo $data['F']['main_upto_50p']; ?></td>
                            <td><?php echo $data['F']['conn_upto_50p']; ?></td>
                            <td><?php echo $data['F']['main_no_consent']; ?></td>
                            <td><?php echo $data['F']['conn_no_consent']; ?></td>
                            <td><?php echo $data1['F']['main']; ?></td>
                            <td><?php echo $data1['F']['conn']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!--Supplementary Start-->
            <div class="row" id="result">
                <div id="result">
                    <br />
                    <div class="header">
                        Statistics to be listed during Partial Court Working Days <?= $supplementary; ?>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th rowspan="3" style="vertical-align: middle;">Description</th>
                                <th colspan="2">Total No. of Matters notified with Notice dated <?php echo $notice_date_supp; ?></th>
                                <th colspan="2">No. of Matters in which more than 50% Advocates have declined</th>
                                <th colspan="2">No. of matters in which upto 50% Advocates have declined</th>
                                <th colspan="2">Matters in which none of the Advocates have
                                    declined</th>
                                <th colspan="2">Matters in which there are no Advocates</th>
                            </tr>
                            <tr>
                                <th>Main Matters</th>
                                <th>Connected Matters</th>
                                <th>Main Matters</th>
                                <th>Connected Matters</th>
                                <th>Main Matters</th>
                                <th>Connected Matters</th>
                                <th colspan="1">Main Matters</th>
                                <th colspan="1">Connected Matters</th>
                                <th colspan="1">Main Matters</th>
                                <th colspan="1">Connected Matters</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Miscellaneous Matters</td>
                                <td><?php echo $main2_supp['total_misc_main_matters']; ?></td>
                                <td><?php echo $main2_supp['total_misc_conn_matters']; ?></td>
                                <td><?php echo isset($data_supp['M']) ? $data_supp['M']['main_more_than_50p'] : ''; ?></td>
                                <td><?php echo isset($data_supp['M']) ? $data_supp['M']['conn_more_than_50p'] : ''; ?></td>
                                <td><?php echo isset($data_supp['M']) ? $data_supp['M']['main_upto_50p'] : ''; ?></td>
                                <td><?php echo isset($data_supp['M']) ? $data_supp['M']['conn_upto_50p'] : ''; ?></td>
                                <td><?php echo isset($data_supp['M']) ? $data_supp['M']['main_no_consent'] : ''; ?></td>
                                <td><?php echo isset($data_supp['M']) ? $data_supp['M']['conn_no_consent'] : ''; ?></td>
                                <td><?php echo isset($data1_supp['M']) ? $data1_supp['M']['main'] : ''; ?></td>
                                <td><?php echo isset($data1_supp['M']) ? $data1_supp['M']['conn'] : ''; ?></td>
                            </tr>
                            <tr>
                                <td>Regular Hearing Matters</td>
                                <td><?php echo $main2_supp['total_regular_main_matters']; ?></td>
                                <td><?php echo $main2_supp['total_regular_conn_matters']; ?></td>
                                <td><?php echo $data_supp['F']['main_more_than_50p']; ?></td>
                                <td><?php echo $data_supp['F']['conn_more_than_50p']; ?></td>
                                <td><?php echo $data_supp['F']['main_upto_50p']; ?></td>
                                <td><?php echo $data_supp['F']['conn_upto_50p']; ?></td>
                                <td><?php echo $data_supp['F']['main_no_consent']; ?></td>
                                <td><?php echo $data_supp['F']['conn_no_consent']; ?></td>
                                <td><?php echo $data1_supp['F']['main']; ?></td>
                                <td><?php echo $data1_supp['F']['conn']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--Supplementary End-->
            </div>
            <div>
                <br />
                <center><button onclick="printDiv('result')" class="btn btn-primary">Print</button></center>
            </div>
        </div>
    </div>
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</body>
</html>