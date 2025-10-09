<?php
declare(strict_types=1);
$getResult = getEcopyingDashbordData($dashboard_flag);
if (is_array($getResult) && count($getResult) > 0) {
    ?>
    <div class="title-comnt-bref doc-byme-tble">
        <div class="table-height-div">
            <div class="table-responsive">
                <table class="table table-striped custom-table ia">
                    <thead class="md-bg-red-600">
                        <tr>
                            <th class="uk-text-bold">#</th>
                            <th class="uk-text-bold">Case No.</th>
                            <th class="uk-text-bold">Diary No.</th>
                            <th class="uk-text-bold">Diary Year</th>
                            <th class="uk-text-bold">Application No.</th>
                            <th class="uk-text-bold">Title</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1;
                        foreach($getResult as $row) {
                            ?>
                            <tr>
                                <td data-key="#"><?= $sno++ ?></td>
                                <td data-key="Case No."><?= $row->reg_no_display ?></td>
                                <td data-key="Diary No."><?= $row->dno ?></td>
                                <td data-key="Diary Year"><?= $row->dyear ?></td>
                                <td data-key="Application No."><?= $row->application_number_display ?></td>
                                <td data-key="Title"><?= $row->title ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>
<style>
    .doc-byme-tble {
        /* right: 0;
        width: 710px !important;
        height: auto !important;
        max-height: 350px; */
    }
    .doc-byme-tble::after {
        /* right: 20px;
        left: auto; */
    } 
</style>