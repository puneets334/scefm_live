@extends('layout.app')
@section('content')
<div class="container-fluid">
    <div class="row">
    <div class="dash-card">

<div class="panel panel-default">
    <div class="panel-body">
        <h4 style="text-align: center;color: #31B0D5">AI Assisted Case filing Report </h4>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>

                <tr class="success">
                    <th>S.N.</th>
                    <th>Documents(s) Uploaded By </th>
                    <th>Uploaded Documents(s) </th>
                    <th>Uploaded On </th>
                    <th>API Updated On </th>
                    <th>Pages</th>
                    <th>AI Assisted Case filing</th>
                    <th>API URL </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sr = 1;
                //$filing_num_label='E-Filing no :DRAFT-';
                $filing_num_label='DRAFT-'; $defect='';

                // pr($data['report_list']);
                foreach ($data['report_list'] as $doc) {
                    if ($doc['stage_id']==1 || empty($doc['stage_id'])){
                        $file_name = !empty($doc['file_name']) ? $doc['file_name'] : NULL;
                        $iitm_api_json = !empty($doc['iitm_api_json']) ? $doc['iitm_api_json'] : NULL;
                        if (!empty($doc['efiling_no'])){
                            $efiling_no = $doc['efiling_no'];
                            $efiling_no='<span class="btn btn-danger btn-sm"  style="color: #ffffff;">' . $filing_num_label . htmlentities(efile_preview($efiling_no), ENT_QUOTES) . '</strong></span> ';

                        }else{
                            $efiling_no = '';
                        }

                        ?>
                        <tr>
                            <td><?= $sr++; ?></td>
                            <td><?= $doc['user_uploaded_by']; ?></td>
                            <td><a target="_blank" href="<?php echo base_url('AIAssisted/viewDocument/' . url_encryption($doc['id'])); ?>">
                                    <?php echo_data($doc['doc_title']); ?>
                                    <img src="<?= base_url('assets/images/pdf.png') ?>"> <br/>
                                    </a>
                            </td>
                            <td><?= date('d-m-Y H:i:s', strtotime($doc['uploaded_on'])) ?></td>
                            <td><?= !empty($doc['iitm_api_json_updated_on']) ? date('d-m-Y H:i:s', strtotime($doc['iitm_api_json_updated_on'])): ''; ?></td>
                            <td><?= htmlentities($doc['page_no'], ENT_QUOTES); ?></td>
                            <td>
                                <?php if(!empty($doc['iitm_api_json']) && $doc['iitm_api_json'] !='' && $doc['iitm_api_json'] !=null  && $doc['iitm_api_json'] !='Internal Server Error'){
                                    $iitm_api_json_decode=json_decode($doc['iitm_api_json'],TRUE) ;
                                    if (!empty($iitm_api_json_decode)){
                                        $defect=$iitm_api_json_decode['defect'];
                                    }
                                    ?>
                                    <a style="color:blue;font-size: initial; " href="#"> <?=$efiling_no;?> </a>
                                    <br/>
                                    <!--<a style="color:green;font-size: initial; " target="_blank" href="<?php /*echo base_url('AIAssisted/CaseWithAI/jsonapiIITM/' . url_encryption($doc['id'])); */?>"><i class="fa fa-eye-slash">Json</i></a>
                                    <br/>-->
                                    <a style="color:green;font-size: initial; " target="_blank" href="<?php echo base_url('AIAssisted/Report/json_decode/' . url_encryption($doc['id'])); ?>"><i class="fa fa-eye-slash"></i> Extracted Metadata JSON</a>

                                <?php }else{
                                    if ($doc['iitm_api_json'] !='Internal Server Error'){
                                        echo '<span class="text-success">Please wait..meta-data extraction may take some time</span>';
                                    }else{
                                        echo '<span class="text-danger">Please wait..meta-data extraction may take some time</span>';
                                    }

                                }?>

                                <?php
                                if(!empty($doc['iitm_api_json_defect']) && $doc['iitm_api_json_defect'] !='' && $doc['iitm_api_json_defect'] !=null  && strtolower($doc['iitm_api_json_defect']) !='internal server error') {
                                    $iitm_api_json_defect_decode = json_decode($doc['iitm_api_json_defect'], TRUE);
                                    if (!empty($iitm_api_json_defect_decode)) {
                                        $defect = $iitm_api_json_decode;
                                    }
                                }
                                if (!empty($defect)){ ?>
                                    <br/><a style="color:red;font-size: initial; " target="_blank" href="<?php echo base_url('AIAssisted/Report/defect_list/' . url_encryption($doc['id'])); ?>"><i class="fa fa-eye-slash"></i> AI Assisted defects(<?=sizeof($defect);?>)</a>
                                <?php }?>
                            </td>
                            <td><?= $doc['api_url']; ?></td>

                        </tr>
                    <?php } }?>

                </tbody>
            </table>


        </div>
    </div>
</div>
</div>
</div>
</div>
@endsection