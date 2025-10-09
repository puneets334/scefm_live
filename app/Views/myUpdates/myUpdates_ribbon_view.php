<h3>My Updates</h3>
<div id="tabs">
    <ul class="nav nav-tabs" id="prodTabs">
        <li><a href="#yesterday">Yesterday</a></li>
        <li class="active"><a href="#today">Today</a></li>
        <li><a href="#tomorrow">Tomorrow</a></li>
        <li><a href="#total">Total</a></li>
    </ul>
    <div class="tab-content">
        <div id="yesterday" class="tab-pane"></div>
        <div id="today" class="tab-pane active">
            <?php
            $this->load->view('myUpdates/myUpdates_data_view');
            ?>
        </div>
        <div id="tomorrow" class="tab-pane "></div>
        <div id="total" class="tab-pane "></div>
    </div>
</div>

<script type="text/javascript">
    $('#tabs').on('click', '.tablink,#prodTabs a', function (e) {
        e.preventDefault();
        var pane = $(this), href = this.hash;
        var target = href.replace("#", "");
        $.ajax({
            type: "GET",
            url: "<?php echo base_url('myUpdates/showMyUpdates/') ?>" + target,
            error: function (data) {
                $('#msg').show();
                $(".form-response").html("<p class='message invalid' id='msgdiv'>There was a problem <span class='close' onclick=hideMessageDiv()>X</span></p>");
            },
            success: function (data) {
                $(href).html(data);
                pane.tab('show');
            }
        });
    });
</script>