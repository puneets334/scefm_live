<div id="tabs">
    <ul class="nav nav-tabs" id="causeListTabs">
        <li class="active"><a href="#cause_list">Cause List</a></li>
        <li><a href="#advance">Advance</a></li>
        <li><a href="#elimination">Elimination</a></li>
    </ul>
    <div class="tab-content">
        <div id="cause_list" class="tab-pane active"> <?php $this->load->view('mycause_list/all_cause_list'); ?></div>
        <div id="advance" class="tab-pane"></div>
        <div id="elimination" class="tab-pane"></div>
    </div>

</div>
<script type="text/javascript">

    $('#tabs').on('click', '#causeListTabs a', function (e) {
        e.preventDefault();
        var pane = $(this), href = this.hash;
        var target = href.replace("#", "");

        openModal();
        $.ajax({
            type: "GET",
            url: "<?php echo base_url('mycause_list/DefaultController/') ?>" + target,
            error: function (data) {
                closeModal();
                $('#msg').show();
                $(".form-response").html("<p class='message invalid' id='msgdiv'>There was a problem <span class='close' onclick=hideMessageDiv()>X</span></p>");
            },
            success: function (data) {

                closeModal();
                $(href).html(data);
                pane.tab('show');
            }
        });
    });
</script>