<div id="loader-wrapper" style="display: none;">
    <div id="loader"></div>
</div>
<div class="table-responsive" style="height: auto; overflow-x: overlay;">
<input type="hidden" class="txt_csrfname" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
<div class="table-sec">
                                                        <div class="table-responsive">
    <table id="userTables"
        class="table table-striped custom-table " style="width: 100% !important">
        <thead>
            <tr>
                <th data-key="Sr. No.">Sr. No.</th>
                <th data-key="Stage">Stage</th>
                <th data-key="eFiling No.">eFiling No.</th>
                <th data-key="Type">Type</th>
                <th data-key="Case Detail">Case Detail</th>
                <th data-key="Submitted On">Submitted On</th>
                <th data-key="...">...</th>
                <th data-key="Allocated To DA">Allocated To DA</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $allocated = '';
            
            ?>
        </tbody>
    </table>
</div>
</div>
</div>
<script>
    $(document).ready(function(){
        $('#userTables').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'searching' : true,
            'ordering': true, // Enable sorting
            'order': [[0, 'asc']],
            'ajax': {
                'url':"<?=base_url('getData')?>",
                'data': function(data){
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                return {
                    data: data,
                    [csrfName]: csrfHash // CSRF Token
                };
                },
                dataSrc: function(data){
                    // Update token hash
                    $('.txt_csrfname').val(data.token);
                    showLoader();
                    // Datatable data
                    return data.aaData;
                }
            },
            dataSrc: function(data){

              // Update token hash
              $('.txt_csrfname').val(data.token);
               showLoader();
              // Datatable data
              return data.aaData;
            },
         'columns': [
            { data: 'id' },
            { data: 'user_stage_name' },
            { data: 'efiling_no' },
            { data: 'type' },
            { data: 'case_details' },
            { data: 'submitted_on' },
            { data: 'action' },
            { data: 'allocated_to' },
         ],
         'createdRow': function(row, data, dataIndex) {
            // Set data-key on each <td>
            $(row).find('td').each(function(index) {
                var keys = [
                    data.data_keys.id,
                    data.data_keys.user_stage_name,
                    data.data_keys.efiling_no,
                    data.data_keys.type,
                    data.data_keys.case_details,
                    data.data_keys.submitted_on,
                    data.data_keys.action,
                    data.data_keys.allocated_to
                ];
                $(this).attr('data-key', keys[index]);
            });
         }
      });
   });
   function showLoader() {
        $('#loader-wrapper').show();
        setTimeout(function() {
            $('#loader-wrapper').hide();
        }, 1000); // Hides the loader after 3 seconds
    }
</script>