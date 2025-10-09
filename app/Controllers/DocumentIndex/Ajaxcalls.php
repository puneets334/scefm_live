<?php
namespace App\Controllers\DocumentIndex;
use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\DocumentIndex\DocumentIndexDropDownModel;
use App\Models\DocumentIndex\DocumentIndexModel;
use App\Models\DocumentIndex\DocumentIndexSelectModel;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use CodeIgniter\HTTP\Response;

class Ajaxcalls extends BaseController {

    protected $DocumentIndex_model;
    protected $DocumentIndex_Select_model;
    protected $DocumentIndex_DropDown_model;

    public function __construct() {
        parent::__construct();
        $this->DocumentIndex_model = new DocumentIndexModel();
        $this->DocumentIndex_Select_model = new DocumentIndexSelectModel();
        $this->DocumentIndex_DropDown_model = new DocumentIndexDropDownModel();
    }

    function check_login() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, AMICUS_CURIAE_USER);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
    }

    public function load_pdf_viewer() {
        $this->check_login();
        $pdf_id = url_decryption($_POST['document']);
        if (!empty($pdf_id)) {
            $result = $this->DocumentIndex_Select_model->get_original_pdf_details($pdf_id);
            $last_page = $result[0]['last_page'] + 1;
            $pdf_total_page = $result[0]['page_no'];
            if ($pdf_total_page > $last_page) {
                $last_page_no = $last_page;
            } else if ($pdf_total_page + 1 == $last_page) {
                $last_page_no = 'all_page_done';
            } elseif ($result[0]['last_page'] == $pdf_total_page) {
                $last_page_no = 'all_page_done';
            } else {
                $last_page_no = $pdf_total_page;
            }
            $_SESSION['case_details']['case_pdf_id'] = $result[0]['id'];
            $pdf_url = base_url($result[0]['file_path']) . '#page=' . $last_page_no;
            echo '2@@@' . $last_page_no . '@@@' . $pdf_total_page . '@@@<i class="fa fa-file-pdf-o" style="color:red;"></i> ' . '@@@' . $pdf_url;
        } else {
            echo '1@@@Invalid document Id.';
        }
    }

    public function load_document_index() {
        $this->check_login();
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        if (!empty($registration_id)) {
            $efiled_docs_list = $this->DocumentIndex_Select_model->get_index_items_list($registration_id);
        } else {
            $efiled_docs_list = NULL;
        }
        $i = 1;
        $caveatDocNum = '';
        $index_data = '<div class="col-md-12 col-sm-12 col-xs-12"> 
        <table id="datatableresponsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
                <tr class="success">
                    <th width="1%">#</th>
                    <th width="20%">Title</th>
                    <th width="3%">Action</th>
                </tr>
            </thead>
        <tbody>';
        if (!empty($efiled_docs_list)) {
            if((!empty($_SESSION['efiling_details']['ref_m_efiled_type_id'])) && ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT)){
                $caveatDocNum = count($efiled_docs_list);
            }
            $Common_model = new CommonModel();
            $result = $Common_model->max_pending_stage_date($_SESSION['efiling_details']['registration_id']);
            $indx = 0;
            $indx_running=0;
            $st_indx = 1;
            $end_indx = 0;
            foreach ($efiled_docs_list as $doc_list) {
                if(isset($result) && strtotime($doc_list['uploaded_on'] ?? '') > strtotime($result[0]->max_date ?? '')) {
                    $delete_button = '<a onclick = "delete_index(' . "'" . htmlentities(url_encryption(trim($doc_list['doc_id'] . '$$' . $registration_id), ENT_QUOTES)) . "'" . ')"class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>';
                } else {
                    $delete_button = '-';
                }
                $indx = $doc_list['st_page'] . ' - ' . $doc_list['end_page'];
                $end_indx = $end_indx + $doc_list['no_of_copies'];
                $indx_running = $st_indx . ' - ' . $end_indx;
                $st_indx = $end_indx + 1;
                $index_data .= '<tr>
                    <td data-key="#" width="1%">' . $i++ . '</td>
                        <td data-key="Title" width="20%">'  . $doc_list['docdesc'] . '</td>
                    <td data-key="Action" width="3%"> 
                    '.$delete_button.'
                    </td>
                </tr>';
            }
        } else {
            $index_data .= '<tr><td colspan="7" class="text-center">No record found.</td></tr>';
        }
        $index_data .= '</tbody></table><input type="hidden" name="caveatDocNum" id="caveatDocNum" data-caveatDocNum="'.$caveatDocNum.'"</div>';
        echo $index_data;
    }

    public function get_doc_type() {
        // MAKE SUB INDEX ITEM DROP DOWN ITEMS
        $doc_type_code = url_decryption($_POST['doc_type_code']);
        if (empty($doc_type_code) || !preg_match("/^[0-9]*$/", $doc_type_code)) {
            return FALSE;
        }
        $doc_res = $this->DocumentIndex_DropDown_model->get_sub_document_type($doc_type_code);
        $dropDownOptions = '';
        if (isset($doc_res) && !empty($doc_res)) {
            $dropDownOptions = '<option value="">Select Index Sub Item</option>';
            $courtFeeText='';
            foreach ($doc_res as $doc_list) {
                if($doc_list['doccode']=='16' && $doc_list['doccode1']!='0')
                    $courtFeeText='';
                else
                    $courtFeeText='(Court Fee:'.escape_data($doc_list["docfee"]).')';
                    $dropDownOptions .= '<option value="' . escape_data(url_encryption($doc_list['doccode1'])) . '">' . escape_data($doc_list['docdesc']).'</option>';
                    //change on 28072023 FOR not showing court fee with the documents indexing
                /*end of changes*/
            }
        }
        echo $dropDownOptions;
    }

    public function get_sub_doc_type() {
        // MAKE CAT TEXT SUB TEXT MERGE INTO INDEX TITLE
        $sub_doc_type = url_decryption($_POST['sub_doc_type']);
        $doc_type_code = url_decryption($_POST['doc_type_code']);
        if (empty($doc_type_code) || !preg_match("/^[0-9]*$/", $doc_type_code)) {
            return FALSE;
        }
        if (empty($sub_doc_type) || !preg_match("/^[0-9]*$/", $sub_doc_type)) {
            return FALSE;
        }
        $doc_res = $this->DocumentIndex_DropDown_model->get_sub_document_type($doc_type_code,$sub_doc_type);
        $dropDownOptions = 0;
        if (isset($doc_res) && !empty($doc_res)) {
            if($doc_res[0]['doccode1']!='0'){
                $dropDownOptions=$doc_res[0]['docdesc'];
            }
        }
        echo $dropDownOptions;
    }

    public function get_sub_doc_type_check() {
        $doc_type_code = url_decryption($_POST['doc_type_code']);
        $sub_doc_type = 0;// url_decryption($_POST['sub_doc_type']);
        if (!empty($doc_type_code) && $sub_doc_type == 0) {
            if ($doc_type_code == 2 or $doc_type_code == 5 or $doc_type_code == 19) {
                $dataResult = '<label class="control-label col-sm-12 input-sm mt-4"> If with affidavit? <span style="color: red">*</span></label>
                <div class="col-sm-8">
                    <input type="hidden" name="affidavitCheck" value="affidavitCheck">
                    <input type="radio" id="Yes" name="if_with_affidavit" value="Y" required>
                    <label for="Yes">Yes</label>
                    <input type="radio" id="No" name="if_with_affidavit" value="N">
                    <label for="No">No</label>
                </div>';
            } else if ($doc_type_code == 15) {
                $dataResult = '<label class="control-label col-sm-5 input-sm">Is Letter of Inspection of File? <span style="color: red">*</span></label>
                <div class="col-sm-7">
                    <input type="hidden" name="inspectionLetterCheck" value="inspectionLetterCheck">
                    <input type="radio" id="Yes" name="letter_of_inspection_of_file" value="Y" required>
                    <label for="Yes">Yes</label>
                    <input type="radio" id="No" name="letter_of_inspection_of_file" value="N">
                    <label for="No">No</label>
                </div>';
            } else {
                $dataResult = '';
            }
        } else {
            $dataResult = '';
        }
        echo $dataResult;
    }

    public function delete_index() {
        // DELETES INDEX ENTRY
        $this->check_login();
        $input_array = explode('$$', url_decryption(escape_data($_POST["form_submit_val"])));
        if (count($input_array) != 2) {
            echo '1@@@Invalid Action';
        }
        $doc_id = $input_array[0];
        $registration_id = $input_array[1];
        if (empty($doc_id)) {
            echo '1@@@Invalid attempt.';
            exit(0);
        }
        if ($registration_id != $_SESSION['efiling_details']['registration_id']) {
            echo '1@@@Invalid attempt.';
            exit(0);
        }
        switch ($_SESSION['efiling_details']['ref_m_efiled_type_id']) {
            case E_FILING_TYPE_NEW_CASE :
                $breadcrumb_to_remove = array(NEW_CASE_INDEX, NEW_CASE_AFFIRMATION);
                break;
            case E_FILING_TYPE_MISC_DOCS :
                $breadcrumb_to_remove = array(MISC_BREAD_DOC_INDEX, MISC_BREAD_AFFIRMATION);
                break;
            case E_FILING_TYPE_IA :
                $breadcrumb_to_remove = array(IA_BREAD_DOC_INDEX, IA_BREAD_AFFIRMATION);
                break;
            case E_FILING_TYPE_CAVEAT :
                $breadcrumb_to_remove = array(CAVEAT_BREAD_DOC_INDEX, CAVEAT_BREAD_COURT_FEE);
                break;
            case OLD_CASES_REFILING :
                $breadcrumb_to_remove = array(MISC_BREAD_DOC_INDEX, MISC_BREAD_COURT_FEE);
                break;
        }
        if (!empty($doc_id)) {
            $last_page = $this->DocumentIndex_model->delete_index($doc_id, $breadcrumb_to_remove);
            if (!empty($last_page)) {
                echo '2@@@' . htmlentities('Entry deleted successfully.', ENT_QUOTES); //. '@@@' . htmlentities($last_page, ENT_QUOTES);
                reset_affirmation($_SESSION['efiling_details']['registration_id']);
                exit(0);
            } else {
                echo '1@@@' . htmlentities('Failed to delete. Please try again.', ENT_QUOTES);
                exit(0);
            }
        } else {
            echo '1@@@' . htmlentities('Invalid Index id.', ENT_QUOTES);
            exit(0);
        }
    }

    /**changes done on 01 September 2020*/
    public function get_index_type() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $doc_type = escape_data(url_decryption($_POST["doc_type_code"]));
        echo $doc_type;
        return;
    }
    /*End of changes*/

    public function syncIndexedFile($pspdfkit_document_id) {
        try {
            $new_file_contents = file_get_contents(PSPDFKIT_SERVER_URI . "/api/documents/$pspdfkit_document_id/pdf", false, stream_context_create([
                'http' => [
                    'header' => "Authorization: Token token=\"secret\"",
                ],
            ]));
            if (!empty($new_file_contents)) {
                $indexed_contents = (array)$this->DocumentIndex_model->getIndexedDocFromPspdfkitDocumentId($pspdfkit_document_id);
                if (!empty($indexed_contents)) {
                    $file = $indexed_contents['file_path'] . $indexed_contents['file_name'];
                    file_put_contents($file, $new_file_contents);
                    $new_file_hash = hash_file('sha256', $file);
                    $new_file_size = filesize($file);
                    $new_file_page_count = (int)@exec('pdfinfo ' . $file . ' | awk \'/Pages/ {print $2}\'', $output);//poppler-utils variant
                    if ($new_file_size > 0) {
                        //Code added on  21 nov 2020 and modified on 4 dec 2020
                        $master_pdf_id = $indexed_contents['pdf_id'];
                        $this->consolidateIndexedPdfIntoParentPdf($master_pdf_id);
                        $va= $this->update_metadata_of_Index($new_file_page_count, $pspdfkit_document_id, $new_file_size, $indexed_contents['pdf_id']);
                        if ($va > 0) {
                            $response = service('response'); 
                            $response->setStatusCode(200); 
                            $response->setBody(json_encode([])); 
                            $response->send();
                        } else {
                            $response = service('response'); 
                            $response->setStatusCode(500); 
                            $response->setBody(json_encode(['errors' => ['Something went wrong while saving the PDF details']])); 
                            $response->send();
                        }
                    } else {
                        $response = service('response'); 
                        $response->setStatusCode(500); 
                        $response->setBody(json_encode(['errors' => ['Invalid PDF. Unable to sync']])); 
                        $response->send();
                    }
                } else {
                    $response = service('response'); 
                    $response->setStatusCode(500); 
                    $response->setBody(json_encode(['errors' => ['Couldn\'t fetch the PDF details.']])); 
                    $response->send();
                }
            } else {
                $response = service('response'); 
                $response->setStatusCode(500); 
                $response->setBody(json_encode(['errors' => ['Couldn\'t fetch the latest PDF.']])); 
                $response->send();
            }
        } catch (Exception $e) {
            $response = service('response'); 
            $response->setStatusCode(500); 
            $response->setBody(json_encode(['errors' => ['Something went wrong while syncing the PDF.']])); 
            $response->send();
        }
    }

    //Function to consoloidate index and parent pdf file and update its metadata
    public function consolidateIndexedPdfIntoParentPdf($master_pdf_id) {
        $index_file_details = $this->DocumentIndex_model->getindexfiledetails($master_pdf_id); //used to get index pdf's
        $hash_master_pdf_id = $this->DocumentIndex_model->getmasterfiledetails($master_pdf_id); //used to get master pdf
        $new_file = getcwd() . '/' . $hash_master_pdf_id[0]->file_path; //get the same path as of parent pdf file
        $file = "";   //declare variable used for index file name
        if (file_exists($new_file)) {
            $pg_list = array();
            $pg_no_not_index = array();
            //code used to get index pages with its path
            foreach ($index_file_details as $sub_index_file) {
                $pg_list[$sub_index_file->st_page] = $sub_index_file->file_path . $sub_index_file->file_name . " ";
            }
            //code used to get range of index pages
            $index_pg_range = array();
            foreach ($index_file_details as $sub_index_file) {
                $index_pg_range = array_merge($index_pg_range, range($sub_index_file->st_page, $sub_index_file->end_page));
            }
            //code added on 2 dec 20
            //code used to get pages in parent pdf
            $parent_pdf_count_arr = range(1, $hash_master_pdf_id[0]->page_no);
            //code used to get unindex pages
            $pg_no_not_index = array_diff($parent_pdf_count_arr, $index_pg_range);
            $result = array_merge(array_keys($pg_list), $pg_no_not_index);
            sort($result);
            //code added on 3 dec 2020
            $duplicate_file = trim($new_file, '.pdf');
            $parent_pdf_path = $duplicate_file . "_copy.pdf";
            copy($new_file, $parent_pdf_path);
            foreach ($result as $no) {
                if (in_array($no, array_keys($pg_list))) {
                    $file .= " " . $pg_list[$no];
                } else {
                    $file .= " " . $parent_pdf_path . " " . $no;
                }
            }
            exec("qpdf $parent_pdf_path --pages $file '--' $new_file");
            //code added on 8 dec 202o to update the file over pspdfkit
            $this->DocumentIndex_model->deleteDocument($hash_master_pdf_id[0]->pspdfkit_document_id);
            $filecontent = file_get_contents($new_file);
            $pspdfkit_document = $this->uploadDocument($filecontent);
            $pspdfkit_document_id = $pspdfkit_document['document_id'];
            if (empty($pspdfkit_document_id)) {
                echo '1@@@' . htmlentities('Failed. PsPDFkit issue. Contact Technical Support', ENT_QUOTES);
                exit(0);
            }
            unlink($parent_pdf_path);//code for delete copy of parent pdf file
            //update the master pdf file details in table
            $new_file_hash_value = hash_file('sha256', $new_file);
            $new_file_size = filesize($new_file);
            $new_file_page_count = (int)@exec('pdfinfo ' . $new_file . ' | awk \'/Pages/ {print $2}\'', $output);//poppler-utils variant
            if ($new_file_size > 0) {
                $this->db->WHERE('pspdfkit_document_id', $hash_master_pdf_id[0]->pspdfkit_document_id);
                $this->db->UPDATE('efil.tbl_uploaded_pdfs', ['page_no' => $new_file_page_count, 'file_size' => $new_file_size, 'doc_hashed_value' => $new_file_hash_value,'pspdfkit_document_id'=>$pspdfkit_document_id]);
            }
        }
    }

    //Function to update metadata when any index file file modified
    public function update_metadata_of_Index($new_file_page_count, $pspdfkit_document_id, $new_file_size, $index_pdf_id) {
        $old_index_file = $this->DocumentIndex_Select_model->previous_index_file_count($pspdfkit_document_id);
        $old_index_file_count = (int)$old_index_file[0]['page_no'];
        $start_pg = $old_index_file[0]['st_page'];
        // update all rest of index file start pg and end page
        if ($new_file_page_count > $old_index_file_count) {
            //Code when new pages added
            $pg_count_diff = $new_file_page_count - $old_index_file_count;
            //update new count of editable index file
            $this->db->SET('end_page', 'end_page+' . $pg_count_diff, FALSE);
            $this->db->WHERE('pspdfkit_document_id', $pspdfkit_document_id);
            $this->db->UPDATE('efil.tbl_efiled_docs', ['page_no' => $new_file_page_count, 'file_size' => $new_file_size, 'doc_hashed_value' => $new_file_hash]);
            $count=$this->db->affected_rows();
            //update rest of the index files range
            $this->db->set('st_page', 'st_page+' . $pg_count_diff, FALSE);
            $this->db->set('end_page', 'end_page+' . $pg_count_diff, FALSE);
        } else {
            //code when remove some pages
            $pg_count_diff = $old_index_file_count - $new_file_page_count;
            //update new count of editable index file
            $this->db->SET('end_page', 'end_page-' . $pg_count_diff, FALSE);
            $this->db->WHERE('pspdfkit_document_id', $pspdfkit_document_id);
            $this->db->UPDATE('efil.tbl_efiled_docs', ['page_no' => $new_file_page_count, 'file_size' => $new_file_size, 'doc_hashed_value' => $new_file_hash]);
            $count=$this->db->affected_rows();
            //update rest of the index files range
            $this->db->set('st_page', 'st_page-' . $pg_count_diff, FALSE);
            $this->db->set('end_page', 'end_page-' . $pg_count_diff, FALSE);
        }
        $this->db->table('efil.tbl_efiled_docs');
        $this->db->WHERE('pspdfkit_document_id!=', $pspdfkit_document_id);
        $this->db->WHERE('pdf_id', $index_pdf_id);
        $this->db->WHERE('st_page >', $start_pg);
        $this->db->UPDATE();
        return $count;
    }

    public function uploadDocument($content) {
        $pspdfkit_document = [];
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post(
                PSPDFKIT_SERVER_URI . '/api/documents',
                [
                    'headers' => [
                        'Authorization' => 'Token token="secret"',
                        'Content-Length' => 0,
                        'Content-Type' => 'application/pdf',
                        'Transfer-Encoding' => 'chunked',
                        'cache-control' => 'no-cache',
                    ],
                    'http_errors' => false,
                    'body' => $content
                ]
            );
            if ($response->getStatusCode() == 200) {
                $pspdfkit_document = json_decode($response->getBody()->getContents(), true)['data'];
            }
        } catch (GuzzleException $e) {
            //$if_save_case_file_paper_book = false;
        }
        return $pspdfkit_document;
    }

    public function markCuredDefect() {
        extract($_POST);
        if (!empty($type) && $type !='All'){
            if(!empty($val) && !empty($objectionId)) {
                $dbs = \Config\Database::connect();
                $builder = $dbs->table('efil.tbl_icmis_objections');
                $builder->SET('aor_cured', $val);
                $builder->WHERE('id', $objectionId);
                $builder->WHERE('is_deleted', false);
                $updated = $builder->update();
            }
        } else if (!empty($type) && $type=='All') {
            if(!empty($_POST)){
                if(!empty($objectionId)) {
                    foreach ($objectionId as $key => $value) {
                        $id = $value;
                        if (!empty($objectionId)) {
                            $dbs = \Config\Database::connect();
                            $builder = $dbs->table('efil.tbl_icmis_objections');
                            $builder->SELECT('*');
                            $builder->WHERE('id', $id);
                            $builder->WHERE('is_deleted', false);
                            $builder->WHERE('aor_cured', true);
                            $query = $builder->get();
                            if ($query->getNumRows() >= 1) {

                            } else {
                                $dbs = \Config\Database::connect();
                                $builder = $dbs->table('efil.tbl_icmis_objections');
                                $builder->SET('aor_cured', true);
                                $builder->WHERE('id', $id);
                                $builder->WHERE('is_deleted', false);
                                $builder->UPDATE();
                            }
                        }
                    }
                } else{
                    $registration_id=$_SESSION['efiling_details']['registration_id'];
                    if (!empty($registration_id)){
                        $dbs = \Config\Database::connect();
                        $builder = $dbs->table('efil.tbl_icmis_objections');
                        $builder->SET('aor_cured', false);
                        $builder->WHERE('registration_id', $registration_id);
                        $builder->WHERE('is_deleted', false);
                        $builder->UPDATE();
                    }
                }
            }
        }
    }
    public function markCuredDefectIAMiscDocs()
    {
        extract($_GET);
        if (!empty($type) && $type !='All'){
            if(!empty($val) && !empty($objectionId)){
                $builder = $this->db->table('efil.tbl_icmis_ai_docs_objections');
                $builder->where('id', $objectionId);
                $builder->where('is_deleted', false);
                $builder->set('aor_cured', $val); 
                $builder->update();
            }
        }else if (!empty($type) && $type=='All'){
            if(!empty($_GET)){
                if(!empty($objectionId)) {
                    foreach ($objectionId as $key => $value) {
                        $id = $value;
                        if (!empty($objectionId)) {
                            $builder = $this->db->table('efil.tbl_icmis_ai_docs_objections'); 
                            $builder->select('*');
                            $builder->where('id', $id);
                            $builder->where('is_deleted', false);
                            $builder->where('aor_cured', true);
                            $query = $builder->get(); 

                            if ($query->getNumRows() >= 1) {

                            } else {
                                $builder = $this->db->table('efil.tbl_icmis_ai_docs_objections'); 
                                $builder->where('id', $id);
                                $builder->where('is_deleted', false);
                                $builder->set('aor_cured', true);  
                                $builder->update();
                            }
                        }
                    }
                }else{
                    $registration_id=$_SESSION['efiling_details']['registration_id'];
                    if (!empty($registration_id)){
                        $builder = $this->db->table('efil.tbl_icmis_ai_docs_objections'); 
                        $builder->where('registration_id', $registration_id);
                        $builder->where('is_deleted', false);
                        $builder->set('aor_cured', false);  
                        $builder->update();
                    }
                }
            }
        }

    }
}