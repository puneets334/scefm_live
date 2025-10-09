<div class="panel panel-default">
    <div class="panel-body">
        <h4 style="text-align: center;color: #31B0D5">Cron Triggered Report </h4>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                <tr class="success">
                    <th>S.N.</th>
                    <th>API URL </th>
                    <th>File Name</th>
                    <th>Last Triggered Started on</th>
                    <th>Last Triggered Completed on</th>
                    <th>Is Available for Triggered</th>
                    <th>No of Times Already Triggered</th>
                    <th>Date of Triggered</th>

                </tr>
                </thead>
                <tbody>
                <?php
                $sr = 1;
                foreach ($cronIITM as $corn) {
                    ?>
                    <tr>
                        <td><?= $sr++; ?></td>
                        <td><?= $corn['api_url']; ?></td>
                        <td><?= $corn['pdf_file']; ?></td>
                        <td><?= !empty($corn['last_triggered_started_on']) ? date('d-m-Y H:i:s', strtotime($corn['last_triggered_started_on'])) :'' ?></td>
                        <td><?= !empty($corn['last_triggered_completed_on']) ? date('d-m-Y H:i:s', strtotime($corn['last_triggered_completed_on'])) :'' ?></td>
                        <td><?=$corn['is_available_for_triggered']==1 ? 'Yes' :'No'; ?></td>
                        <td><?= $corn['no_of_times_already_triggered']; ?></td>
                        <td><?= !empty($corn['triggered_date']) ? date('d-m-Y', strtotime($corn['triggered_date'])) :'' ?></td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>


        </div>
    </div>
</div>