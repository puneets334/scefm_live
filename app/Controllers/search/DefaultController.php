<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DefaultController extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        unset($_SESSION['efiling_details']);
        unset($_SESSION['estab_details']);
        unset($_SESSION['stage']);
        unset($_SESSION['pdf_signed_details']['doc_signed_used']);
        unset($_SESSION['pdf_signed_details']);
        unset($_SESSION['crt_fee_and_esign_add']);
        unset($_SESSION['advocate_details']);
        unset($_SESSION['search_case_data_save']);
        unset($_SESSION['document']);
        unset($_SESSION['mobile_no_for_updation']);
        unset($_SESSION['email_id_for_updation']);
        unset($_SESSION['efiling_type']);
        unset($_SESSION['search_key']);
        unset($_SESSION['efiling_for_details']);
        unset($_SESSION['breadcrumb_enable']);
        unset($_SESSION['cnr_details']);
        unset($_SESSION['file_uploaded_done']);
        unset($_SESSION['register_req_info']);
        unset($_SESSION['captchaWord']);

        redirect('login');
        exit(0);
    }
    public function search_paperbook_data_in_dspace7()
    {
         //var_dump($_POST);

        $diary_no=escape_data((!empty($_POST['diary_no']))?url_decryption($_POST['diary_no']):null);
        $search_text=escape_data($this->input->post("search_text"));
        //$dspace_search_function_url=SEARCH_PAPER_BOOK_DOCUMENT.$search_text.'/'.$diary_no;
        //Str_replace(' ','%20',$search_text)


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => SEARCH_PAPER_BOOK_DOCUMENT,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('search_text' => $search_text,'diary_no' =>$diary_no,'aor_code'=>$_SESSION['login']['aor_code'],'bar_id'=>$_SESSION['login']['adv_sci_bar_id'],
                'advocate_name'=>$_SESSION['login']['first_name'],'mobile_no'=>$_SESSION['login']['mobile_number'],'email_id'=>$_SESSION['login']['emailid'],'scope'=>'11b74cad-f31d-445f-b897-0c4e919d1e4c')
            //cases community uuid in the dspace is: 11b74cad-f31d-445f-b897-0c4e919d1e4c
        ));

        $response = curl_exec($curl);

        $response=(array)json_decode($response,true);
        curl_close($curl);


        $result_html='<div class="panel panel-success">
      <div class="panel-heading searchData">Searched Result</div>
      <div class="panel-body"> <div id="accordion" class="col-sm-12">';
        $diary_no=$response[0]["_embedded"]["indexableObject"]["metadata"]["sc.case.diaryId"][0]["value"];
        $header_created_status_for_diary_no='F';
        $area_expanded='false';
        $card_body=array(array());$card_header=array();
        if(!empty($response))
        {

            $doc_url=PRODUCTION_SERVER.'/index.php/dspace/DefaultController/display_bitstream_content/';
            //$doc_url=base_url('index.php/dspace/DefaultController/display_bitstream_content/');
            $case_document_count=0;
            $case_no=0;
            for($i=0, $ni=count($response); $i < $ni; $i++)
            {
                $diary_id=$response[$i]["_embedded"]["indexableObject"]["metadata"]["sc.case.diaryId"][0]["value"];
                $item_uuid=$response[$i]["_embedded"]["indexableObject"]["uuid"];
                $item_orginal_bundle_uuid = file_get_contents(GET_ITEM_ORIGINAL_BUNDLE_UUID.'/'.$item_uuid, true);
                $original_bundle_bitstream_uuid = file_get_contents(GET_BUNDLE_BITSTREAM_UUID.'/'.$item_orginal_bundle_uuid);

                if($diary_no!=$diary_id ) //for new case document found
                {
                    $header_created_status_for_diary_no = 'F';
                    $case_document_count=0;
                    $case_no++;
                }

                if($diary_no==$diary_id || $header_created_status_for_diary_no=='F')
                {
                    $document_title[$case_no]=strtoupper($response[$i]["_embedded"]["indexableObject"]["metadata"]["dc.subject"][0]["value"]);
                    $reg_no_display[$case_no]=strtoupper($response[$i]["_embedded"]["indexableObject"]["metadata"]["sc.case.regNoDisplay"][0]["value"]);
                    $petitioner_name[$case_no]=strtoupper($response[$i]["_embedded"]["indexableObject"]["metadata"]["sc.petitioner.name"][0]["value"]);
                    $respondent_name[$case_no]=strtoupper($response[$i]["_embedded"]["indexableObject"]["metadata"]["sc.respondent.name"][0]["value"]);
                    $diary_nos[$case_no]=$response[$i]["_embedded"]["indexableObject"]["metadata"]["sc.case.diaryId"][0]["value"];
                    $from_page[$case_no]=$response[$i]["_embedded"]["indexableObject"]["metadata"]["sc.paperbook.indexPageNoFrom"][0]["value"];
                    $to_page[$case_no]=$response[$i]["_embedded"]["indexableObject"]["metadata"]["sc.paperbook.indexPageNoTo"][0]["value"];


                    if($header_created_status_for_diary_no=='F' && $case_document_count==0) { //create collapse header part

                        $case_document_count++;
                        $card_body[$case_no][$case_document_count]= " <tr>
                                <td>$case_document_count.</td>
                                <td style='color:blue !important;'><a class=\"text-danger text-bold\" href='//$doc_url$original_bundle_bitstream_uuid' target='_blank'>$diary_nos[$case_no]-$document_title[$case_no]</a></td>
                                 <td>$from_page[$case_no]</td>
                                <td>$to_page[$case_no]</td>
                                </tr> ";
                        $header_created_status_for_diary_no = 'T';
                        $diary_no=$diary_id;

                    }
                    else // add all the searched item of the case
                    {

                        $case_document_count++;
                        $card_body[$case_no][$case_document_count]=" <tr>
                                <td>$case_document_count.</td>
                                <td><a class=\"text-danger\" href='//$doc_url$original_bundle_bitstream_uuid' target=\'_blank\'>$diary_nos[$case_no]-$document_title[$case_no]</a></td>
                                <td>$from_page[$case_no]</td>
                                <td>$to_page[$case_no]</td>                                
                                </tr> ";
                    }

                }

            }
            $inner_html='';
            for($i=0;  $i<=$case_no; $i++) //again run the loop for adding closing div for each card_body
            {
                if($i==0)
                    $show='show';
                else
                    $show='';
                $header_html="
     
                        <div class='card'>
                            <div class='card-header'  role='tab' id='heading$i' style='overflow:hidden;block:inline;postion:relative;word-wrap: break-word'>
                              <h5 class='mb-0'>
                                <button class='btn btn-default col-sm-12' data-toggle='collapse' data-target='#collapse$i' aria-expanded='$area_expanded' aria-controls='collapse$i'><strong><span style='color:#002266'>$reg_no_display[$i] @ $diary_nos[$i] </span>   ($petitioner_name[$i] Vs. $respondent_name[$i])</></strong> <i style='float:right' class='glyphicon glyphicon-chevron-down rotate-icon'></i></button>         </h5>
                                </div>
                                ";
                $result_html.=$header_html;
                if(!empty($card_body)) {
                    $body_html="<div id='collapse$i' class='collapse show1' aria-labelledby='heading$i' data-parent='#accordion'>
<div class=\"card-body\">
                        <table class='table table-striped table-bordered'>
                        <thead>
                        <tr>
                            <th width='10px;'>Sr.No</th>
                            <th>Document Title</th>
                            <th>From Page</th>   
                            <th>To Page</th>                                                     
                        </tr>
                        </thead>
                        <tbody>";

                    foreach ($card_body as $key=>$docs) {
                        if($key==$i) {
                            foreach ($docs as $sub_docs) {
                                $body_html.= $sub_docs;
                            }
                        }
                        else
                            continue;
                    }
                    $body_html.='</tbody></table></div></div>'; //end div for collapse and card
                }


                $result_html.=$body_html;
            }
            $result_html.='</div></div>'; //panel body and panel div
        }
        else
        {
            $result_html='<div class="alert alert-danger">
  <strong>Please try again!</strong>No Document Available .
</div>';
        }


        echo $result_html;



    }


}

?>