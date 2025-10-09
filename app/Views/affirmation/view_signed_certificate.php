<?php
if (isset($pdf) && !empty($pdf)) {
    $file_name = $pdf[0]->signed_pdf_file_name; 
    $file_path = $pdf[0]->signed_pdf_partial_path; 
    $docs_name = $pdf[0]->signed_pdf_file_name;
    
    $file = $file_path . '/' . $file_name;
    header("Content-Type: application/pdf");
    header("Content-Disposition:inline;filename=".$docs_name);
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    @readfile($file);
    
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

