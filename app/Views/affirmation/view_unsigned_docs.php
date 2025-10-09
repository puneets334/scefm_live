<?php
if (isset($pdf) && !empty($pdf)) {
   
    $file_name = $pdf['file_name']; 
    $file_path = $pdf['file_path']; 
    
    header("Content-Type: application/pdf");
    header("Content-Disposition:inline;filename=$file_name");
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    @readfile($file_path);
} elseif (isset($msg)) {
    ?>

    <div class="message_box" style="text-align: center">
        <strong>ERROR!</strong> <?php echo $msg; ?>.
    </div>
<?php }
?>

<style>
    .message_box {
        padding: 20px;
        background-color: #EDEDED;
        border: 1px dotted !important;
    }

</style>

