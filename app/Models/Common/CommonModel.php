<?php

namespace App\Models\Common;

use App\Libraries\webservices\Efiling_webservices;
use CodeIgniter\Encryption\Encryption;
use CodeIgniter\Model;
use Config\Services;

class CommonModel extends Model
{

    protected $efiling_webservices;
    protected $encrypt;

    function __construct()
    {
        parent::__construct();
        $this->efiling_webservices = new Efiling_webservices();
        $this->encrypt = Services::encrypter();
    }

    function bypass_hc($reg_id, $subordinate_court_details)
    {
        $this->db->transStart();
        if ($subordinate_court_details) {
            $curr_dt_time = date('Y-m-d H:i:s');
            $data = array(
                'is_deleted' => TRUE,
                'deleted_by' => getSessionData('login')['id'],
                'deleted_on' => $curr_dt_time,
                'deleted_by_ip' => getClientIP()
            );
            $builder = $this->db->table('efil.tbl_lower_court_details');
            $builder->WHERE('registration_id', $reg_id);
            $builder->WHERE('is_deleted', FALSE);
            $builder->UPDATE($data);
        }
        $query_hc = "insert into efil.tbl_lower_court_details(registration_id,is_hc_exempted) values ('" . $reg_id . "',true)";
        $rs_hc = $this->db->query($query_hc);
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->select("breadcrumb_status");
        $builder->where('registration_id', $reg_id);
        $query = $builder->get();
        $output = $query->getResult();
        $breadcrumb = $output[0]->breadcrumb_status;
        $breadcrumb_expld = explode(',', $breadcrumb);
        $rs_upd = '';
        if (!in_array('7', $breadcrumb_expld)) {
            $breadcrumb = $breadcrumb . ",7";
            $upd = "update efil.tbl_efiling_nums set breadcrumb_status='$breadcrumb' where registration_id=$reg_id";
            $rs_upd = $this->db->query($upd);
            getSessionData('efiling_details')['breadcrumb_status'] = $breadcrumb;
        }
        $this->db->transComplete();
        if ($rs_upd) {
            return 0;
        } else {
            return 1;
        }
    }

    function valid_cde($registration_id)
    {
        $this->db->transStart();
        $sql = "select breadcrumb_status,sc_case_type_id from efil.tbl_efiling_nums ten left join efil.tbl_case_details tcd on ten.registration_id=tcd.registration_id  where tcd.registration_id=$registration_id LIMIT 1";
        $rs = $this->db->query($sql)->getResult();
        $status = $rs[0]->breadcrumb_status;
        $ct = $rs[0]->sc_case_type_id;
        $this->db->transComplete();
        return $status . '-' . $ct;
    }

    function get_otp_details($type_id, $updated_by, $mobile, $efiling_num)
    {
        $sql = "select * from efil.tbl_sms_log where type_id=$type_id and validate_status like 'A' and updated_by=$updated_by and tbl_efiling_num_id=$efiling_num and mobile = '$mobile' order by id desc limit 1";
        $query = $this->db->query($sql);
        if ($query->getNumRows()) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function insert_otp($otp_details)
    {
        $builder = $this->db->table('efil.tbl_sms_log');
        $builder->INSERT($otp_details);
        if ($this->db->insertID()) {
            return true;
        } else {
            return false;
        }
    }

    function update_otp_details($type_id, $updated_by, $mobile, $efiling_num, $status)
    {
        $sql = "update efil.tbl_sms_log set validate_status='$status' where type_id=$type_id and validate_status like 'A' and updated_by=$updated_by and tbl_efiling_num_id=$efiling_num and mobile = '$mobile'";
        $query = $this->db->query($sql);
        if ($this->db->affectedRows() == 1) {
            return true;
        } else {
            return false;
        }
    }

    function verify_otp($registration_id)
    {
        $this->db->transStart();
        $query = "SELECT id, type_id, mobile, sms_text, updated_on, updated_by, tbl_efiling_num_id, captcha, start_time, end_time, validate_status, updated_by_ip from efil.tbl_sms_log where type_id=38 and tbl_efiling_num_id=$registration_id order by updated_on desc limit 1";
        $rsc = pg_query(pg_connect($query)) or die("error in the query plz check");
        while ($rw_c = pg_fetch_array($rsc)) {
            $captcha = $rw_c['captcha'];
            $endtime = $rw_c['end_time'];
            $str = $captcha . "-" . $endtime;
            echo $str;
        }
        $this->db->transComplete();
        return $str;
    }

    public function showEfilingData($advocateIds, $filingDateRange, $pendingFilingStages, $documentTypes, $diaryIds)
    {
        $start_filing_date = null;
        $end_filing_date = null;
        if ($filingDateRange != null) {
            $dates = explode('to', $filingDateRange);
            $start_filing_date = $dates[0];
            $end_filing_date = $dates[1];
        }
        $builder = $this->db->table('efil.tbl_efiling_nums ten');
        $builder->select('ten.registration_id as registrationId,efiling_no as efilingNumber,cause_title as causeTitle,
            efiling_type as applicationType,date(ten.create_on) as filedOn,ten.breadcrumb_status,
            ten.ref_m_efiled_type_id,diary_no as diaryNumber,diary_year as diaryYear,filing_for_parties as partyNames, tcd.sc_case_type_id,
            case when ten.ref_m_efiled_type_id in(1,5) and not(string_to_array(breadcrumb_status, \',\') && array[\'2\',\'3\']) then \'new_case_basic_detail\' 
            else case when ten.ref_m_efiled_type_id in(1,5) and not(string_to_array(breadcrumb_status, \',\') && array[\'7\']) then \'new_case_subordinate_court\' 
            else case when ten.ref_m_efiled_type_id in(1,5) and not(string_to_array(breadcrumb_status, \',\') && array[\'9\']) then \'new_case_index\' 
            else case when ten.ref_m_efiled_type_id in(1,5) and not(string_to_array(breadcrumb_status, \',\') && array[\'10\']) then \'new_case_payment\' 
            else case when ten.ref_m_efiled_type_id in(1,5) and not(string_to_array(breadcrumb_status, \',\') && array[\'12\']) then \'new_case_affirmation\' 
            else case when ten.ref_m_efiled_type_id =2 and not(string_to_array(breadcrumb_status, \',\') && array[\'2\']) then \'misc_appearing_for\' 
            else case when ten.ref_m_efiled_type_id =2 and not(string_to_array(breadcrumb_status, \',\') && array[\'5\']) then \'misc_index\' 
            else case when ten.ref_m_efiled_type_id =2 and not(string_to_array(breadcrumb_status, \',\') && array[\'7\']) then \'misc_certificate\' 
            else case when ten.ref_m_efiled_type_id =4 and not(string_to_array(breadcrumb_status, \',\') && array[\'2\']) then \'ia_appearing_for\' 
            else case when ten.ref_m_efiled_type_id =4 and not(string_to_array(breadcrumb_status, \',\') && array[\'5\']) then \'ia_index\' 
            else case when ten.ref_m_efiled_type_id =4 and not(string_to_array(breadcrumb_status, \',\') && array[\'7\']) then \'ia_certificate\'  
            end end end end end end end end end end end as pendingStage ');
        $builder->where('ten.is_deleted', 'false');
        $builder->where('ten.is_active', 'true');
        if ($advocateIds != null) {
            $builder->whereIn('adv_sci_bar_id', $advocateIds);
        }
        if ($diaryIds != null) {
            $builder->whereIn('concat(diary_no,diary_year)', $diaryIds);
        }
        if ($documentTypes != null) {
            $builder->whereIn('ten.ref_m_efiled_type_id', $documentTypes);
        }
        if ($start_filing_date != null && $end_filing_date == null) {
            $builder->where('date(ten.create_on)', $start_filing_date);
        }
        if ($start_filing_date != null && $end_filing_date != null) {
            $builder->where('date(ten.create_on) between \'' . $start_filing_date . '\' and \'' . $end_filing_date . '\'');
        }
        $builder->where('tens.stage_id', '1');
        $builder->where('ten.ref_m_efiled_type_id in(1,2,4,5)');
        $builder->join('efil.tbl_efiling_num_status tens', 'ten.registration_id =tens.registration_id', 'left');
        $builder->join('efil.tbl_case_details tcd', 'ten.registration_id =tcd.registration_id', 'left');
        $builder->join('efil.tbl_users u', 'ten.created_by=u.id');
        $builder->join('efil.tbl_misc_docs_ia tmdi', 'ten.registration_id =tmdi.registration_id', 'left');
        $builder->join('efil.m_tbl_efiling_type mtet', 'ten.ref_m_efiled_type_id =mtet.id');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        }
    }

    function showEfilingDocuments($diaryNos)
    {
        $diaryNos = implode("','", $diaryNos);
        $diaryNos = "'" . $diaryNos . "'";
        $sql = "select file_name as fileName,file_path as filePath,doc_title as docTitle,remarks,concat(sc_diary_num,sc_diary_year) as diaryId,'' as icmisDocNo from 
            efil.tbl_uploaded_pdfs tup join efil.tbl_case_details tcd on tup.registration_id=tcd.registration_id where concat(sc_diary_num,sc_diary_year) 
            in($diaryNos)
            union
            select file_name as fileName,file_path as filePath,doc_title as docTitle,remarks,icmis_diary_no as diaryId,concat(icmis_docnum,'/',icmis_docyear) as icmisDocNo 
            from efil.tbl_efiled_docs ted where icmis_diary_no in($diaryNos) order by diaryId";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function mentioningRequests($advocateIds, $filingDateRange, $status, $diaryIds)
    {
        $start_filing_date = null;
        $end_filing_date = null;
        if ($filingDateRange != null) {
            $dates = explode('to', $filingDateRange);
            $start_mentioning_date = $dates[0];
            $end_mentioning_date = $dates[1];
        }
        $builder = $this->db->table('efil.tbl_mentioning_requests tmr');
        $builder->select('efiling_number as efilingNumber,diary_no as diaryNumber,diary_year as diaryYear,reg_no_display as registrationNumber,
            cause_title as causeTitle,concat(first_name,\' \',last_name) as mentionedBy,synopsis_of_emergency as synopsis,
            case when is_for_fixed_date =true then to_char(requested_listing_date,\'dd-mm-yyyy\') else concat(to_char(lower(requested_listing_date_range),\'dd-mm-yyyy\'),\' to \'
            ,to_char(upper(requested_listing_date_range),\'dd-mm-yyyy\')) end as mentionedFor,
            case when (approval_status is null or approval_status =\'\') then \'Pending for Consideration\' else 
            case when approval_status=\'a\' then concat(\'Approved for \',to_char(approved_for_date,\'dd-mm-yyy\')) else 
            case when approval_status=\'r\' then concat(\'Rejected due to \',rejection_reason) end end end as status,
            arguing_counsel_name as arguingCounsel');
        if ($advocateIds != null) {
            $builder->whereIn('adv_sci_bar_id', $advocateIds);
        }
        if ($diaryIds != null) {
            $builder->whereIn('concat(diary_no,diary_year)', $diaryIds);
        }
        if ($status == 'a' || $status == 'r') {
            $builder->where('tmr.approval_status', $status);
        } else if ($status == 'p') {
            $builder->where('(tmr.approval_status is null or tmr.approval_status=\'\')');
        }
        if ($start_mentioning_date != null && $end_mentioning_date == null) {
            $builder->where('date(tmr.mentioned_on)', $start_mentioning_date);
        }
        if ($start_mentioning_date != null && $end_mentioning_date != null) {
            $builder->where('date(tmr.mentioned_on) between \'' . $start_mentioning_date . '\' and \'' . $end_mentioning_date . '\'');
        }
        $builder->join('efil.tbl_sci_cases tsc', 'tmr.tbl_sci_cases_id =tsc.id');
        $builder->join('efil.tbl_users u', 'tmr.mentioned_by =u.id');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_establishment_details()
    {
        $builder = $this->db->table('efil.m_tbl_establishments');
        $builder->select("id,estab_name, estab_code, access_ip, enable_payment_gateway, is_charging_printing_cost, printing_cost,payment_gateway_params,pg_request_function,pg_response_function, is_pet_state_required, is_pet_district_required, is_res_state_required, is_res_district_required, is_pet_mobile_required, is_pet_email_required, is_res_mobile_required, is_res_email_required, is_extra_pet_state_required, is_extra_pet_district_required, is_extra_res_state_required, is_extra_res_district_required, is_extra_pet_mobile_required, is_extra_pet_email_required, is_extra_res_mobile_required, is_extra_res_email_required,state_code");
        $builder->where('display', 'Y');
        $builder->orderBy("estab_name", "asc");
        $query = $builder->get();
        $output = $query->getResult();
        if ($query->getNumRows() >= 1) {
            $estab_details = $query->getResultArray();
            $_SESSION['estab_details'] = $estab_details[0];
            $gSession = getSessionData('estab_details');
            $_SESSION['estab_details']['efiling_for_type_id'] = E_FILING_FOR_SUPREMECOURT;
            $_SESSION['estab_details']['efiling_for_id'] = escape_data($estab_details[0]['id']);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_efiling_num_basic_Details($registration_id)
    {
        $sql = "SELECT 
            tcd.if_sclsc, 
            en.registration_id, 
            en.efiling_no, 
            en.efiling_year, 
            en.ref_m_efiled_type_id, 
            et.efiling_type, 
            en.efiling_for_type_id, 
            en.efiling_for_id, 
            en.breadcrumb_status, 
            en.signed_method, 
            en.allocated_to, 
            en.created_by, 
            en.sub_created_by, 
            CASE 
                WHEN en.ref_m_efiled_type_id = 1 THEN tcd.sc_diary_num 
                ELSE misc_ia.diary_no 
            END AS diary_no, 
            CASE 
                WHEN en.ref_m_efiled_type_id = 1 THEN tcd.sc_diary_year 
                ELSE misc_ia.diary_year 
            END AS diary_year, 
            cs.stage_id, 
            cs.activated_on, 
            tcd.is_govt_filing, 
            misc_ia.is_govt_filing AS doc_govt_filing, 
            (SELECT payment_status 
            FROM efil.tbl_court_fee_payment 
            WHERE registration_id = en.registration_id 
            ORDER BY id DESC 
            LIMIT 1) AS payment_status
        FROM 
            efil.tbl_efiling_nums AS en 
        LEFT JOIN 
            efil.tbl_misc_docs_ia AS misc_ia 
            ON en.registration_id = misc_ia.registration_id 
        JOIN 
            efil.tbl_efiling_num_status AS cs 
            ON en.registration_id = cs.registration_id 
        JOIN 
            efil.m_tbl_efiling_type AS et 
            ON en.ref_m_efiled_type_id = et.id 
        LEFT JOIN 
            efil.tbl_case_details AS tcd 
            ON en.registration_id = tcd.registration_id 
        WHERE 
            cs.is_active = 'TRUE' 
            AND cs.is_deleted = 'FALSE' 
            AND en.is_active = 'TRUE' 
            AND en.is_deleted = 'FALSE' 
            AND en.registration_id = $registration_id;
        ";
        $query = $this->db->query($sql);
        if ($query && $query->getNumRows() >= 1) {
            $efiling_details = $query->getRowArray();
            $datamain = array(['no_of_petitioners' => 0, 'no_of_respondents' => 0,]);
            $case_details = $this->get_case_details($registration_id);
            if (!empty($case_details)) {
                if (!empty($case_details['no_of_petitioners']) && !empty($case_details['no_of_respondents'])) {
                    $efiling_detailsFinal = array_merge($case_details, $efiling_details);
                } else {
                    $efiling_detailsFinal = array_merge($datamain[0], $efiling_details);
                }
            } else {
                $efiling_detailsFinal = array_merge($datamain[0], $efiling_details);
            }
            setSessionData('efiling_details', $efiling_detailsFinal);
            if ($efiling_details['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
                $builder = $this->db->table('public.tbl_efiling_caveat');
                $builder->where('ref_m_efiling_nums_registration_id', $registration_id);
                $query = $builder->get();
                $output = $query->getResult();
                if ($query->getNumRows() >= 1) {
                    $caveat_details = $output;
                    if ($caveat_details[0]->is_govt_filing == 1) {
                        getSessionData('efiling_details')['is_govt_filing'] = 1;
                    }
                }
            }
            return TRUE;
        } else {
            return false;
        }
    }

    function get_case_details($registration_id)
    {
        $builder = $this->db->table('efil.tbl_case_details as cd');
        $builder->select("cd.no_of_respondents, cd.no_of_petitioners");
        $builder->where('cd.registration_id', $registration_id);
        $query = $builder->get(1);
        $output = $query->getRowArray();
        if ($query->getNumRows() >= 1) {
            return $output;
        } else {
            return FALSE;
        }
    }

    function get_case_details_with_selected_subject_category($registration_id)
    {
        $builder = $this->db->table('efil.tbl_case_details as tcd');
        $builder->SELECT("tcd.registration_id,tcd.subject_cat,s.subcode1,s.subcode2,s.subcode3,s.subcode4,s.category_sc_old");
        // $builder->JOIN('icmis.submaster as s', 'tcd.subject_cat = s.id', 'left');
        // $builder->WHERE('s.display', 'Y');
        $builder->JOIN('icmis.submaster s', 'tcd.subject_cat = s.id', 'left');
        $builder->WHERE('tcd.registration_id', $registration_id);
        $builder->where('s.is_old', 'N');
        $builder->where('s.display', 'Y');
        $builder->where('s.is_3j', 0); // Use 0 as an integer
        $builder->where('s.is_5j', 0);
        $builder->where('s.is_7j', 0);
        $builder->where('s.is_9j', 0);
        $builder->where('s.is_11j', 0);
        $builder->LIMIT(1);
        $queryCD = $builder->get();
        if ($queryCD->getNumRows() >= 1) {
            return $case_details = $queryCD->getResultArray();
        } else {
            return FALSE;
        }
    }

    function get_intials_defects_remarks($registration_id, $current_stage_id)
    {
        if (empty($registration_id)) {
            return FALSE;
        }
        $builder = $this->db->table('efil.tbl_initial_defects');
        $builder->SELECT('*');
        $builder->WHERE('is_approved', FALSE);
        if (in_array($current_stage_id, array(Initial_Defected_Stage, DEFICIT_COURT_FEE, I_B_Defected_Stage, I_B_Rejected_Stage, E_REJECTED_STAGE, LODGING_STAGE, DELETE_AND_LODGING_STAGE))) {
            $builder->WHERE('is_defect_cured', FALSE);
        } elseif (in_array($current_stage_id, array(
            Initial_Approaval_Pending_Stage,
            Initial_Defects_Cured_Stage,
            DEFICIT_COURT_FEE_PAID,
            I_B_Approval_Pending_Stage,
            I_B_Approval_Pending_Admin_Stage,
            I_B_Defects_Cured_Stage
        ))) {
            $builder->WHERE('is_defect_cured', TRUE);
        }
        $builder->WHERE('registration_id', $registration_id);
        $builder->orderBy('initial_defects_id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $arr = $query->getRowArray();
            return $arr[0] ?? $arr;
        } else {
            return false;
        }
    }

    function get_cis_defects_remarks($registration_id, $show_always)
    {
        if (empty($registration_id)) {
            return FALSE;
        }
        if (!$show_always) {
            $builder = $this->db->table('efil.tbl_icmis_objections');
            $builder->SELECT('count(id) obj_count');
            $builder->WHERE('registration_id', $registration_id);
            $builder->WHERE('is_deleted', FALSE);
            $builder->WHERE('obj_removed_date', NULL);
            $query = $builder->get();
            $obj_exists = $query->getRowArray();
            if ($obj_exists['obj_count'] == 0) {
                return FALSE;
            }
        }
        $builder = $this->db->table('efil.tbl_icmis_objections obj');
        $builder->SELECT('obj.id, icmis_obj.objdesc, obj.remarks, obj.obj_prepare_date, obj.obj_removed_date, obj.pspdfkit_document_id, obj.to_be_modified_pspdfkit_document_pages_raw, obj.to_be_modified_pspdfkit_document_pages_parsed, obj.aor_cured');
        $builder->JOIN('icmis.objection icmis_obj', 'obj.obj_id = icmis_obj.objcode');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('obj.is_deleted', FALSE);
        $builder->orderBy('obj.id', 'asc');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $arr = $query->getResultArray();
            return $arr;
        } else {
            return false;
        }
    }

    function max_pending_stage_date($registration_id)
    {
        if (empty($registration_id)) {
            return FALSE;
        }
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->SELECT('max(activated_on) max_date');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('stage_id in (2,7,11,19)');
        $builder->WHERE('is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $arr = $query->getResult();
            return $arr;
        } else {
            return false;
        }
    }

    function getPdfInfo($pspdfkit_document_id)
    {
        $builder = $this->db->table('efil.tbl_efiled_docs edocs');
        $builder->SELECT('edocs.doc_title, edocs.pspdfkit_document_id, edocs.page_no');
        $builder->WHERE('edocs.pspdfkit_document_id', $pspdfkit_document_id);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $arr = $query->getResult();
            return $arr[0];
        } else {
            return false;
        }
    }

    function get_pdf_defects_remarks($reg)
    {
        $pdfdefectsarr = array();
        $result2 = $this->getPspdfkitDocId($reg);
        if ($result2) {
            foreach ($result2 as $res) {
                $pspdfkit_document_id = $res['pspdfkit_document_id'];
                if ($pspdfkit_document_id != '') {
                    $pages = array();
                    $notedpages = array();
                    $response = $this->getPdfAnnotations($pspdfkit_document_id);
                    $data = json_decode($response, true);
                    foreach ($data['data']['annotations'] as $obj) {
                        $pageIndex = (int)$obj['content']['pageIndex'];
                        $pageNo = $pageIndex + 1;
                        if (!in_array($pageNo, $notedpages)) {
                            array_push($pages, $pageNo);
                            array_push($notedpages, $pageNo);
                        }
                    }
                    if (!empty($pages)) {
                        $pdfdefectsarr[$pspdfkit_document_id] = $pages;
                    }
                }
            }
        }
        return $pdfdefectsarr;
    }

    function getPspdfkitDocId($reg)
    {
        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->SELECT('ed.pspdfkit_document_id');
        $builder->WHERE('ed.registration_id', $reg);
        $builder->WHERE('ed.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function getPdfAnnotations($pspdfkit_document_id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => PSPDFKIT_SERVER_URI . "/api/documents/" . $pspdfkit_document_id . "/annotations",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: Token token=secret"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    function chkifcde($registration_id)
    {
        $this->db->transStart();
        $sql = ("SELECT registration_id, efiling_for_type_id, efiling_for_id, efiling_no, efiling_year, ref_m_efiled_type_id, created_by, create_on, create_by_ip, is_active, updated_by, updated_on, updated_by_ip, signed_method, allocated_to, allocated_on, breadcrumb_status, sub_created_by, sub_created_on, sub_created_by_ip, is_deleted, deleted_by, deleted_on, deleted_by_ip
        FROM efil.tbl_efiling_nums where registration_id=$registration_id");
        $rs = pg_query(pg_connect($sql)) or die("error in the query plz check");
        while ($rw = pg_fetch_array($rs)) {
            $cde = $rw['breadcrumb_status'];
        }
        $this->db->transComplete();
        return $cde;
    }

    function upd_efil_num_sc($registration_id, $sc, $next_stage)
    {
        $registration_id = str_replace("'", " ", $registration_id);
        $this->db->transStart();
        $upd_sc = "update efil.tbl_efiling_nums set ifcde=1,security_code='$sc' where registration_id=$registration_id";
        $rs_sc = pg_query(pg_connect($upd_sc));
        if (pg_affected_rows($rs_sc) > 0) {
        }
        $update_data = array(
            'deactivated_on' => date('Y-m-d H:i:s'),
            'is_active' => FALSE,
            'updated_by' => $_SESSION['login']['id'],
            'updated_by_ip' => getClientIP()
        );
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('is_active', TRUE);
        $query = $builder->UPDATE($update_data);
        if ($this->db->affectedRows() > 0) {
            if ($next_stage == Initial_Approaval_Pending_Stage) {
                $assign_to = $this->assign_efiling_number($_SESSION['estab_details']['efiling_for_type_id'], $_SESSION['estab_details']['efiling_for_id']);
                $nums_data_update = array(
                    'allocated_to' => $assign_to[0]['id'],
                    'allocated_on' => date('Y-m-d H:i:s')
                );
                $builder = $this->db->table('efil.tbl_efiling_nums');
                $builder->WHERE('registration_id', $registration_id);
                $builder->WHERE('is_active', TRUE);
                $query = $builder->UPDATE($nums_data_update);
                if ($this->db->affectedRows() > 0) {
                    $data = array(
                        'registration_id' => $registration_id,
                        'admin_id' => $assign_to[0]['id'],
                        'allocated_on' => date('Y-m-d H:i:s'),
                        'updated_by' => $_SESSION['login']['id'],
                        'updated_on' => date('Y-m-d H:i:s'),
                        'update_ip' => getClientIP(),
                        'reason_to_allocate' => NULL
                    );
                    $builder = $this->db->table('efil.tbl_efiling_allocation');
                    $query = $builder->INSERT($data);
                    if ($this->db->insertID()) {
                        $action_res = TRUE;
                    } else {
                        $action_res = FALSE;
                    }
                } else {
                    $action_res = FALSE;
                }
            } else {
                $action_res = TRUE;
            }
            if ($action_res) {
                $proceed = false;
                if ($next_stage == Initial_Defects_Cured_Stage || $next_stage == DEFICIT_COURT_FEE_PAID) {
                    $update_def = array(
                        'is_active' => FALSE,
                        'is_defect_cured' => TRUE,
                        'defect_cured_date' => date('Y-m-d H:i:s'),
                        'updated_by' => $_SESSION['login']['id'],
                        'ip_address' => getClientIP()
                    );
                    $builder = $this->db->table('efil.tbl_initial_defects');
                    $builder->WHERE('registration_id', $registration_id);
                    $builder->WHERE('is_active', TRUE);
                    $query = $builder->UPDATE($update_def);
                    if ($this->db->affectedRows() > 0) {
                        $proceed = TRUE;
                    }
                } else {
                    $proceed = TRUE;
                }
                if ($proceed) {
                    $insert_data = array(
                        'registration_id' => $registration_id,
                        'stage_id' => $next_stage,
                        'activated_on' => date('Y-m-d H:i:s'),
                        'is_active' => TRUE,
                        'activated_by' => $_SESSION['login']['id'],
                        'activated_by_ip' => getClientIP()
                    );
                    $this->db->table('efil.tbl_efiling_num_status');
                    $query = $builder->INSERT($insert_data);
                    if ($this->db->insertID()) {
                        $this->db->transComplete();
                    }
                }
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function updateCaseStatus($registration_id, $next_stage)
    {
        $this->db->transStart();
        $update_data = array(
            'deactivated_on' => date('Y-m-d H:i:s'),
            'is_active' => FALSE,
            'updated_by' => $_SESSION['login']['id'],
            'updated_by_ip' => getClientIP()
        );
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('is_active', TRUE);
        $query = $builder->UPDATE($update_data);
        if ($this->db->affectedRows() > 0) {
            $stage_id = !empty(getSessionData('efiling_details')['stage_id']) ? getSessionData('efiling_details')['stage_id'] : NULL;
            if ($next_stage == Initial_Approaval_Pending_Stage || $stage_id == I_B_Defected_Stage) {
                $efiling_type = !empty(getSessionData('efiling_details')['efiling_type']) ? getSessionData('efiling_details')['efiling_type'] : NULL;
                $assignedUser = array();
                $allocatdTo = 0;
                $params = array();
                if (isset($efiling_type) && !empty($efiling_type)) {
                    if (isset($stage_id) && !empty($stage_id) && $stage_id == I_B_Defected_Stage) {
                        $dyear = !empty(getSessionData('efiling_details')['diary_year']) ? substr(getSessionData('efiling_details')['diary_year'], -4) : '';
                        $diaryNo = getSessionData('efiling_details')['diary_no'] . $dyear;
                        $assoc_arr = array();
                        $assoc_arr['diaryNo'] = $diaryNo;
                        $assoc_json = json_encode($assoc_arr);
                        $key = config('Encryption')->key;
                        $encrypted_string = $this->encrypt->encrypt($assoc_json);
                        $scrutinyUser = $this->efiling_webservices->getScrutinyUserByDiaryNo($diaryNo);
                        $usercode = !empty($scrutinyUser['user_details'][0]['usercode']) ? $scrutinyUser['user_details'][0]['usercode'] : NULL;
                        if (isset($usercode) && !empty($usercode)) {
                            $getuserDetailsArr = array();
                            $getuserDetailsArr['table_name'] = 'efil.tbl_users';
                            $getuserDetailsArr['whereFieldName'] = 'icmis_usercode';
                            $getuserDetailsArr['whereFieldValue'] = $usercode;
                            $preScrutinyUserAllocatedData = $this->getData($getuserDetailsArr);
                            $preAllocatdTo = !empty($preScrutinyUserAllocatedData[0]->id) ? $preScrutinyUserAllocatedData[0]->id : 0;
                        }
                    } else {
                        $params['user_type'] = USER_ADMIN;
                        $params['loginId'] = !empty(getSessionData('login')['id']) ? getSessionData('login')['id'] : NULL;
                        $params['pp_a'] = (!empty(getSessionData('login')['ref_m_usertype_id']) && getSessionData('login')['ref_m_usertype_id'] == 2) ? 'P' : 'A';
                        $params['not_in_user_id'] = unserialize(USER_NOT_IN_LIST);
                        $inputArr = array();
                        $inputArr['table_name'] = 'efil.tbl_efiling_nums';
                        $inputArr['whereFieldName'] = 'registration_id';
                        $inputArr['whereFieldValue'] = $registration_id;
                        $userAllocatedData = $this->getData($inputArr);
                        $preAllocatdTo = !empty($userAllocatedData[0]->allocated_to) ? (int)$userAllocatedData[0]->allocated_to : NULL;
                    }
                    switch ($efiling_type) {
                        case 'new_case':
                            $params['file_type_id'] = E_FILING_TYPE_NEW_CASE;
                            $assignedUser = $this->getAllocatedUser($params);
                            if (isset($preAllocatdTo) && !empty($preAllocatdTo)) {
                                $allocatdTo = $preAllocatdTo;
                            } else {
                                $allocatdTo = (isset($assignedUser[0]->id) && (!empty($assignedUser[0]->id))) ? $assignedUser[0]->id : 0;
                            }
                            break;
                        case 'IA':
                            $params['file_type_id'] = E_FILING_TYPE_IA;
                            $assignedUser = $this->getAllocatedUser($params);
                            if (isset($preAllocatdTo) && !empty($preAllocatdTo)) {
                                $allocatdTo = $preAllocatdTo;
                            } else {
                                $allocatdTo = (isset($assignedUser[0]->id) && (!empty($assignedUser[0]->id))) ? $assignedUser[0]->id : 0;
                            }
                            break;
                        case 'misc_document':
                            $params['file_type_id'] = E_FILING_TYPE_MISC_DOCS;
                            $assignedUser = $this->getAllocatedUser($params);
                            if (isset($preAllocatdTo) && !empty($preAllocatdTo)) {
                                $allocatdTo = $preAllocatdTo;
                            } else {
                                $allocatdTo = (isset($assignedUser[0]->id) && (!empty($assignedUser[0]->id))) ? $assignedUser[0]->id : 0;
                            }
                            break;
                        default:
                            $assign_to = $this->assign_efiling_number($_SESSION['estab_details']['efiling_for_type_id'], $_SESSION['estab_details']['efiling_for_id']);
                            $allocatdTo = !empty($assign_to[0]['id']) ? $assign_to[0]['id'] : 0;
                    }
                }
                $nums_data_update = array(
                    'allocated_to' => $allocatdTo,
                    'allocated_on' => date('Y-m-d H:i:s')
                );
                $builder = $this->db->table('efil.tbl_efiling_nums');
                $builder->WHERE('registration_id', $registration_id);
                $builder->WHERE('is_active', TRUE);
                $query = $builder->UPDATE($nums_data_update);
                if ($this->db->affectedRows() > 0) {
                    $data = array(
                        'registration_id' => $registration_id,
                        'admin_id' => $allocatdTo,
                        'allocated_on' => date('Y-m-d H:i:s'),
                        'updated_by' => $_SESSION['login']['id'],
                        'updated_on' => date('Y-m-d H:i:s'),
                        'update_ip' => getClientIP(),
                        'reason_to_allocate' => NULL
                    );
                    $builder = $this->db->table('efil.tbl_efiling_allocation');
                    $query = $builder->INSERT($data);
                    if ($this->db->insertID()) {
                        $action_res = TRUE;
                    } else {
                        $action_res = FALSE;
                    }
                } else {
                    $action_res = FALSE;
                }
            } else {
                $action_res = TRUE;
            }
            if ($action_res) {
                $proceed = false;
                if ($next_stage == Initial_Defects_Cured_Stage || $next_stage == DEFICIT_COURT_FEE_PAID) {
                    $update_def = array(
                        'is_active' => FALSE,
                        'is_defect_cured' => TRUE,
                        'defect_cured_date' => date('Y-m-d H:i:s'),
                        'updated_by' => $_SESSION['login']['id'],
                        'ip_address' => getClientIP()
                    );
                    $builder = $this->db->table('efil.tbl_initial_defects');
                    $builder->WHERE('registration_id', $registration_id);
                    $builder->WHERE('is_active', TRUE);
                    $query = $builder->UPDATE($update_def);
                    if ($this->db->affectedRows() > 0) {
                        $proceed = TRUE;
                    }
                } else {
                    $proceed = TRUE;
                }
                if ($proceed) {
                    $insert_data = array(
                        'registration_id' => $registration_id,
                        'stage_id' => $next_stage,
                        'activated_on' => date('Y-m-d H:i:s'),
                        'is_active' => TRUE,
                        'activated_by' => $_SESSION['login']['id'],
                        'activated_by_ip' => getClientIP()
                    );
                    $builder = $this->db->table('efil.tbl_efiling_num_status');
                    $query = $builder->INSERT($insert_data);
                    if ($this->db->insertID()) {
                        $this->db->transComplete();
                    }
                }
            }
        }
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function assign_efiling_number($efiling_for_type_id, $efiling_for_id)
    {
        $query = "SELECT users.id,  users.ref_m_usertype_id, users.admin_for_type_id, users.admin_for_id, count(allocated_on) efiling_num_count
        FROM efil.tbl_users users
        LEFT JOIN efil.tbl_efiling_nums en ON en.allocated_to = users.id AND to_char(en.allocated_on,'YYYY-MM-DD')::date ='" . date('Y-m-d') . "'
        WHERE users.admin_for_type_id ='" . $efiling_for_type_id . "' AND users.admin_for_id ='" . $efiling_for_id . "' AND
        users.is_deleted IS FALSE AND users.id not in(2656,2657,2658,2659,2660) and
        users.ref_m_usertype_id IN(" . USER_ADMIN . "," . USER_ACTION_ADMIN . ")                  
        GROUP BY users.id,users.ref_m_usertype_id,users.admin_for_type_id,users.admin_for_id ORDER BY efiling_num_count";
        $query = $this->db->query($query);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_uploaded_documents($registration_id)
    {
        $builder = $this->db->table('efil.tbl_efiled_docs');
        $builder->SELECT('*');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('is_deleted', false);
        $builder->ORDERBY('doc_id');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_uploaded_documents_efile_history($registration_id)
    {
        $builder = $this->db->table('efil.tbl_efiled_docs');
        $builder->SELECT('*');
        $builder->distinct('pdf_id');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('is_deleted', false);
        $builder->ORDERBY('doc_id');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            $filteredResult = [];
            $seenPdfIds = [];
            foreach ($result as $row) {
                if (!in_array($row['pdf_id'], $seenPdfIds)) {
                    $filteredResult[] = $row;
                    $seenPdfIds[] = $row['pdf_id'];
                }
            }
            return $filteredResult;
        } else {
            return FALSE;
        }
    }

    function get_estab_details_for_search($efiling_for_type_id, $efiling_for_id)
    {
        if (isset($efiling_for_type_id) && !empty($efiling_for_type_id) && isset($efiling_for_id) && !empty($efiling_for_id)) {
            $estab_details = $this->get_efiling_for_details($efiling_for_type_id, $efiling_for_id);
            if (isset($estab_details) && !empty($estab_details)) {
                $data = array(
                    'efiling_for_type_id' => $efiling_for_type_id,
                    'efiling_for_id' => $efiling_for_id,
                    'estab_name' => escape_data($estab_details[0]->estname),
                    'est_code' => escape_data($estab_details[0]->est_code),
                    'state_code' => escape_data($estab_details[0]->state_code),
                );
                $this->session->set_userdata(array('search_main_matter_details' => $data));
                return TRUE;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_efiling_for_details($efiling_for_type_id, $efiling_for_id)
    {
        if ($efiling_for_type_id == E_FILING_FOR_ESTABLISHMENT) {
            $builder = $this->db->table('m_tbl_establishments');
            $builder->SELECT("m_tbl_establishments.id,estname,est_code, ip_details,
            enable_payment_gateway, is_charging_printing_cost, printing_cost,payment_gateway_params,pg_request_function,pg_response_function,state_code,
                is_pet_state_required, is_pet_district_required, is_res_state_required, is_res_district_required, 
                is_pet_mobile_required, is_pet_email_required, is_res_mobile_required, is_res_email_required,
                is_extra_pet_state_required, is_extra_pet_district_required, is_extra_res_state_required, is_extra_res_district_required,
                is_extra_pet_mobile_required, is_extra_pet_email_required, is_extra_res_mobile_required, is_extra_res_email_required, is_purpose_of_listing_required,openapi_state_code,enable_e_payment");
            $builder->JOIN('m_tbl_state', 'm_tbl_establishments.state_code=m_tbl_state.state_id', 'left');
            $builder->WHERE('m_tbl_establishments.id', $efiling_for_id);
            $builder->WHERE('m_tbl_establishments.display', 'Y');
            $builder->ORDERBY("estname", "asc");
        } elseif ($efiling_for_type_id == E_FILING_FOR_HIGHCOURT) {
            $builder = $this->db->table('m_tbl_high_courts');
            $builder->SELECT("m_tbl_high_courts.id,hc_name estname,hc_code est_code, ip_details, 
                enable_payment_gateway, is_charging_printing_cost, printing_cost,payment_gateway_params,pg_request_function,pg_response_function,state_code,
                is_pet_state_required, is_pet_district_required, is_res_state_required, is_res_district_required, 
                is_pet_mobile_required, is_pet_email_required, is_res_mobile_required, is_res_email_required,
                is_extra_pet_state_required, is_extra_pet_district_required, is_extra_res_state_required, is_extra_res_district_required,
                is_extra_pet_mobile_required, is_extra_pet_email_required, is_extra_res_mobile_required, is_extra_res_email_required, is_purpose_of_listing_required,openapi_state_code,enable_e_payment");
            $builder->JOIN('m_tbl_state', 'm_tbl_high_courts.state_code=m_tbl_state.state_id', 'left');
            $builder->WHERE('m_tbl_high_courts.id', $efiling_for_id);
            $builder->WHERE('m_tbl_high_courts.is_active', TRUE);
            $builder->ORDERBY("hc_name", "asc");
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

    function prev_main_filing_details($reg_no, $stages = array())
    {
        $sql = "SELECT * FROM efil.tbl_efiling_num_status 
            WHERE registration_id = " . $reg_no . "
            AND is_active = TRUE 
            AND stage_id IN(" . $stages . ")";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_case_details_for_whatsapp_messages($registration_id)
    {
        $sql = "select tu.moblie_number as aor_mobile_number,tcd.cause_title,TO_CHAR(tcd.created_on::timestamp, 'DD-MM-YYYY') as filing_date,
            tu1.first_name,tu1.id as login_id,tu1.emp_id as employee_code
            from efil.tbl_case_details tcd 
            left join efil.tbl_users tu on tcd.created_by=tu.id 
            left join efil.tbl_users tu1 on tu1.id =7087
            where registration_id=$registration_id";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function getApproveStagesCasesListDetails($registration_id = null, $stages = array())
    {
        if (!empty($registration_id)) {
            $whereCondition = " and ten.registration_id  ='$registration_id'";
        } else {
            $whereCondition = " and 1=1";
        }
        if (is_array($stages)) {
            $stages = implode(",", $stages);
        }
        $sql = "SELECT ens.registration_id,ten.efiling_no,ten.efiling_year,ens.stage_id,ten.efiling_for_type_id,ten.ref_m_efiled_type_id  FROM  efil.tbl_efiling_nums ten 
        inner join efil.tbl_efiling_num_status ens on (ten.registration_id=ens.registration_id and ens.stage_id in(" . $stages . ") and ens.is_active=true)
        WHERE ten.is_active = true $whereCondition ";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function get_submitted_cases_for_cron($stages, $efiled_type_id, $usertype)
    {
        $sql = "SELECT ens.registration_id,ten.efiling_no,ten.efiling_year,ens.stage_id,ten.efiling_for_type_id,ten.ref_m_efiled_type_id, tu.ref_m_usertype_id 
        FROM  efil.tbl_efiling_nums ten 
        INNER JOIN efil.tbl_efiling_num_status ens on (ten.registration_id=ens.registration_id and ens.stage_id in($stages) and ens.is_active=true)
        INNER JOIN efil.tbl_users tu on tu.id=ten.created_by 
        WHERE ten.is_active = true and ref_m_efiled_type_id =$efiled_type_id and tu.ref_m_usertype_id = $usertype and ens.registration_id is not null and ten.portal_version = ".PORTAL_VERSION." and ens.stage_id is not null ";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function action_taken($action_id, $status)
    {
        if ($status == 'Active') {
            $data = array('is_active' => TRUE);
            $builder = $this->db->table('efil.tbl_users');
            $builder->WHERE('id', $action_id);
            $query = $builder->UPDATE($data);
            return true;
        }
        if ($status == 'Deactive') {
            $data = array('is_active' => FALSE);
            $builder = $this->db->table('efil.tbl_users');
            $builder->WHERE('id', $action_id);
            $query = $builder->UPDATE('efil.tbl_users', $data);
            return true;
        }
    }

    public function get_share_email_details($registration_id)
    {
        $builder = $this->db->table('efil.tbl_doc_share_email');
        $builder->SELECT("*");
        $builder->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_subject_category_casetype_court_fee($registration_id)
    {
        $sql = "select cd.sc_case_type_id,cd.subject_cat,mcf.court_fee,mcf.flag,s.subcode1,s.subcode2,s.subcode3,s.subcode4,s.sub_name1,s.sub_name2,s.sub_name3, s.sub_name4,s.category_sc_old,c.casename,c.nature, cd.court_fee_calculation_helper_flag, (select count(1) from  efil.tbl_lower_court_details lcd where lcd.registration_id=cd.registration_id and lcd.is_deleted='false' and lcd.is_judgment_challenged ='true') as order_challanged, sum(case when cp.p_r_type='P' and cp.is_deleted='false' then 1 else 0 end) as total_petitioners from efil.tbl_case_details cd 
        inner join icmis.m_court_fee mcf on cd.subject_cat =mcf.submaster_id 
        left join efil.tbl_case_parties cp on cd.registration_id =cp.registration_id 
        left join icmis.submaster s on cd.subject_cat =s.id and s.is_old='N' and s.display='Y' and s.is_3j=0 and s.is_5j=0 and s.is_7j=0 and s.is_9j=0 and s.is_11j=0 
        left join icmis.casetype c on cd.sc_case_type_id =c.casecode where cd.registration_id=$registration_id and mcf.display='Y' group by cd.sc_case_type_id,cd.subject_cat,mcf.court_fee,mcf.flag,s.subcode1,s.subcode2,s.subcode3,s.subcode4,s.sub_name1, s.sub_name2,s.sub_name3,s.sub_name4,s.category_sc_old,c.casename,c.nature,cd.court_fee_calculation_helper_flag,cd.registration_id";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return $this->get_court_fee_by_casetype_with_submaster_id_zero($registration_id);
         }
    }

    public function get_ia_or_misc_doc_court_fee($registration_id, $doccode, $doccode1)
    {
        if (!empty($registration_id)) {
            $sql = "select ud.doc_id,ud.doc_title,ud.doc_type_id,ud.court_fee_calculation_helper_flag,d.doccode,d.doccode1,d.docdesc,d.docfee,d.kntgrp,c.nature,ud.no_of_affidavit_copies,ud.no_of_petitioner_appellant, null as submit_with_affidavit_flag,(select count(1) from  efil.tbl_lower_court_details lcd where lcd.registration_id=ud.registration_id and lcd.is_deleted='false' and lcd.is_judgment_challenged ='true') as order_challanged, (select count(1) from  efil.tbl_case_parties cp  where cp.registration_id=ud.registration_id and cp.p_r_type='P') as total_petitioners, (select count(1) from public.etrial_lower_court elc where elc.registration_id=ud.registration_id and elc.is_deleted='false') as trial_court_order_challanged_for_caveat, mdi.diary_no,mdi.diary_year,cd.sc_diary_num,cd.sc_diary_year from efil.tbl_efiled_docs ud inner join icmis.docmaster d on (ud.doc_type_id=d.doccode and ud.sub_doc_type_id=d.doccode1 and  d.display!='N') left join efil.tbl_misc_docs_ia mdi on ud.registration_id =mdi.registration_id left join efil.tbl_case_details cd  on ud.registration_id =cd.registration_id left join icmis.casetype c on cd.sc_case_type_id =c.casecode where ud.registration_id =$registration_id and ud.is_active='TRUE' and ud.is_deleted='FALSE' ";
        } else {
            $sql = "SELECT d.doccode,d.doccode1,d.docdesc,d.docfee,d.kntgrp
                FROM icmis.docmaster d  WHERE  d.display!='N'
                and d.doccode=$doccode and d.doccode1=$doccode1 ";
        }
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function get_already_paid_court_fee($registration_id)
    {
        $builder = $this->db->table('efil.tbl_court_fee_payment cfp');
        $builder->SELECT("sum(received_amt) as court_fee_already_paid");
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('payment_status', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_court_fee_bifurcation($registration_id)
    {
        /* $sql = "";
        $query = $this->db->query($sql);
        if ($query->num_rows() >= 1) {
            $result = $query->result_array();
            return $result;
        } else{
            return FALSE;
        }*/
    }

    public function fetchCaveatDetails($table, $registration_id)
    {
        $output = false;
        if (isset($table) && !empty($table) && isset($registration_id) && !empty($registration_id)) {
            $builder = $this->db->table($table);
            $builder->select('*');
            $builder->WHERE('registration_id', $registration_id);
            if ($table == 'public.tbl_efiling_case_status') {
                $builder->WHERE('is_active', true);
            } else {
                $builder->WHERE('is_deleted', false);
            }
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getHighCourtData($params = array())
    {
        $output = false;
        if (isset($params) && !empty($params) && is_array($params)) {
            if (isset($params['type']) && !empty($params['type'])) {
                $type = (int)$params['type'];
                switch ($type) {
                    case 1:
                        $builder = $this->db->table('efil.m_tbl_high_courts_bench mthcb');
                        $builder->select('mthcb.*');
                        $builder->where('mthcb.bench_id IS NULL');
                        $builder->where('mthcb.est_code IS NULL');
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    case 2:
                        $builder = $this->db->table('efil.m_tbl_high_courts_bench mthcb');
                        $builder->select('mthcb.*');
                        if (isset($params['hc_id']) && !empty($params['hc_id'])) {
                            $builder->where('mthcb.hc_id', (int)$params['hc_id']);
                        }
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    case 3:
                        $builder = $this->db->table('efil.m_tbl_high_courts_case_types mhcct');
                        $builder->select('mhcct.*');
                        if (isset($params['est_code']) && !empty($params['est_code'])) {
                            $builder->where('mhcct.est_code', $params['est_code']);
                        }
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    default:
                        $output = false;
                }
            }
        }
        return $output;
    }

    public function get_Diary_no_forMsg($registration_id)
    {
        $sql = "select CONCAT(diary_no, '/',diary_year) as diaryno from efil.tbl_misc_docs_IA where registration_id=$registration_id and is_deleted='f'";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function getSubordinateCourtData($params = array())
    {
        $output = false;
        if (isset($params) && !empty($params) && is_array($params)) {
            if (isset($params['type']) && !empty($params['type'])) {
                $type = (int)$params['type'];
                switch ($type) {
                    case 1:
                        $builder = $this->db->table('efil.m_tbl_district_courts_establishments mtdce');
                        $builder->select('mtdce.state_name,mtdce.state_code');
                        $builder->distinct('mtdce.state_name');
                        $builder->orderBy('mtdce.state_name', 'ASC');
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    case 2:
                        $builder = $this->db->table('efil.m_tbl_district_courts_establishments mtdce');
                        $builder->select('mtdce.district_name,mtdce.district_code');
                        $builder->distinct('mtdce.state_name');
                        if (isset($params['state_code']) && !empty($params['state_code'])) {
                            $builder->where('mtdce.state_code', (int)$params['state_code']);
                        }
                        $builder->orderBy('mtdce.district_name', 'ASC');
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    case 3:
                        $builder = $this->db->table('efil.m_tbl_district_courts_establishments mtdce');
                        $builder->select('mtdce.estab_name,mtdce.estab_code');
                        $builder->distinct('mtdce.estab_name');
                        if (isset($params['state_code']) && !empty($params['state_code'])) {
                            $builder->where('mtdce.state_code', (int)$params['state_code']);
                        }
                        if (isset($params['district_code']) && !empty($params['district_code'])) {
                            $builder->where('mtdce.district_code', (int)$params['district_code']);
                        }
                        $builder->orderBy('mtdce.estab_name', 'ASC');
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    case 4:
                        $builder = $this->db->table('efil.m_tbl_district_courts_case_types mtdcct');
                        $builder->select('mtdcct.type_name,mtdcct.id,mtdcct.case_type');
                        $builder->distinct('case_type.type_name');
                        if (isset($params['est_code']) && !empty($params['est_code'])) {
                            $builder->where('mtdcct.est_code', (string)$params['est_code']);
                        }
                        $builder->orderBy('mtdcct.type_name', 'ASC');
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    default:
                        $output = false;
                }
            }
        }
        return $output;
    }

    public function insertFir($params = array())
    {
        $output = false;
        if (isset($params) && !empty($params) && count($params) > 0) {
            $builder = $this->db->table('efil.tbl_fir_details');
            $query = $builder->INSERT($params);
            $output = $this->db->insertID();
        }
        return $output;
    }

    public function getUserDetailsById($userId)
    {
        $output = false;
        if (isset($userId) && !empty($userId)) {
            $builder = $this->db->table('efil.tbl_users');
            $builder->select('id,adv_sci_bar_id,aor_code,ref_m_usertype_id');
            $builder->where('id', (int)$userId);
            $builder->where('is_active', '1');
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getBarDetailsById($barId)
    {
        $output = false;
        if (isset($barId) && !empty($barId)) {
            $builder = $this->db->table('icmis.bar');
            $builder->select('bar_id, aor_code,pp,concat(title,name) as name,mobile,email');
            $builder->where('bar_id', (int)$barId);
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getHighCourtDetailsByEstabCode($estabCode)
    {
        $output = false;
        if (isset($estabCode) && !empty($estabCode)) {
            $builder = $this->db->table('efil.m_tbl_high_courts_bench');
            $builder->select('cmis_state_id,ref_agency_code_id');
            $builder->where('est_code', $estabCode);
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getData($params = array())
    {
        $output = false;
        if (isset($params['table_name']) && !empty($params['table_name']) && isset($params['whereFieldName']) && !empty($params['whereFieldName']) && isset($params['whereFieldValue']) && !empty($params['whereFieldValue'])) {
            $builder = $this->db->table($params['table_name']);
            $builder->select('*');
            $builder->where($params['whereFieldName'], $params['whereFieldValue']);
            if (isset($params['is_active']) && !empty($params['is_active'])) {
                $builder->WHERE('is_active', $params['is_active']);
            }
            if (isset($params['sc_diary_year']) && !empty($params['sc_diary_year'])) {
                $builder->WHERE('sc_diary_year', (string)$params['sc_diary_year']);
            }
            if (isset($params['is_deleted']) && !empty($params['is_deleted'])) {
                $builder->WHERE('is_deleted', false);
            }
            $query = $builder->get();
            if ($query !== false && is_object($query)) {
                $output = $query->getResult();
            }
        }
        return $output;
    }

    function getDetailsByRegistrationId($registrationId)
    {
        $builder = $this->db->table('efil.tbl_efiled_docs ted');
        $builder->select('ted.*,tu.first_name,tu.last_name,tu.aor_code,dm.docdesc,tup.file_path as file');
        $builder->join('efil.tbl_users tu', 'ted.uploaded_by=tu.id');
        $builder->join('efil.tbl_uploaded_pdfs tup', 'tup.registration_id =ted.registration_id and tup.doc_id =ted.pdf_id ');
        $builder->JOIN('icmis.docmaster dm', 'ted.doc_type_id = dm.doccode AND ted.sub_doc_type_id = dm.doccode1 and dm.display!=\'N\'');
        $builder->where('ted.registration_id', $registrationId)->where('ted.is_active', true)->where('ted.is_deleted', false)->where('tup.is_deleted', false);
        $builder->orderBy('ted.doc_id');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getDocDetailsById($doc_id)
    {
        $builder = $this->db->table('efil.tbl_efiled_docs ted');
        $builder->select('ted.*,tu.aor_code,tu.first_name,tu.last_name,ten.efiling_no');
        $builder->join('efil.tbl_users tu', 'ted.uploaded_by=tu.id')->join('efil.tbl_efiling_nums ten', 'ten.registration_id=ted.registration_id');
        $builder->where('doc_id', $doc_id);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function updateTableData($params = array())
    {
        $output = false;
        if (isset($params['table_name']) && !empty($params['table_name']) && !empty($params['whereFieldName']) && isset($params['whereFieldName']) && isset($params['whereFieldValue']) && !empty($params['whereFieldValue']) && isset($params['updateArr']) && !empty($params['updateArr'])) {
            $builder = $this->db->table($params['table_name']);
            $builder->where($params['whereFieldName'], $params['whereFieldValue']);
            $builder->set($params['updateArr']);
            if ($builder->update()) {
                $output = true;
            }
        }
        return $output;
    }

    public function updateTableDataWithWhereArray($params = array())
    {
        $output = false;
        if (isset($params['table_name']) && !empty($params['table_name']) && isset($params['whereArr']) && !empty($params['whereArr']) && isset($params['updateArr']) && !empty($params['updateArr'])) {
            $builder = $this->db->table($params['table_name']);
            $builder->WHERE($params['whereArr']);
            $query = $builder->UPDATE($params['updateArr']);
            if ($query) {
                $output = true;
            } else {
                $output = false;
            }
        }
        return $output;
    }

    public function getSrAdvocateData()
    {
        $output = false;
        $builder = $this->db->table('dscr.tbl_senior_advocates sa');
        $builder->SELECT('sa.id,sa.userid,sa.first_name,sa.middle_name,sa.last_name,ho.honorific_name');
        $builder->JOIN('dscr.honorific ho', 'sa.honorific_id = ho.honorific_id');
        $builder->WHERE('sa.status', '1');
        $builder->WHERE('ho.honorific_status', '1');
        $builder->ORDERBY('sa.first_name', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getAssignedSrAdvocate($params = array())
    {
        $output = false;
        if (isset($params['advocateId']) && !empty($params['advocateId']) && isset($params['diary_no']) && !empty($params['diary_no']) && isset($params['user_type']) && !empty($params['user_type'])) {
            $userType = trim($params['user_type']);
            switch ($userType) {
                case 'arguing_counsel':
                    $builder = $this->db->table('dscr.tbl_arguing_counsels tac');
                    $builder->SELECT('tac.id,tac.tbl_users_id as userid,tac.advocate_name as first_name,sae.user_type,sae.createdAt,sae.updatedAt,sae.is_active');
                    $builder->JOIN('efil.tbl_sr_advocate_engage sae', 'sae.sr_advocate_id = tac.id');
                    $builder->WHERE('tac.is_deleted', false);
                    $builder->WHERE('sae.createdby', (int)$params['advocateId']);
                    $builder->WHERE('sae.diary_no', (int)$params['diary_no']);
                    if (isset($params['user_type']) && !empty($params['user_type'])) {
                        $builder->WHERE('sae.user_type', (string)$params['user_type']);
                    }
                    $builder->ORDERBY('tac.advocate_name', 'ASC');
                    $query = $builder->get();
                    $output = $query->getResultArray();
                    break;
                case 'sr_advocate':
                    $builder = $this->db->table('dscr.tbl_senior_advocates sa');
                    $builder->SELECT('sa.id,sa.userid,sa.first_name,sa.middle_name,sa.last_name,sae.user_type,sae.createdAt,sae.updatedAt,sae.is_active,ho.honorific_name');
                    $builder->JOIN('efil.tbl_sr_advocate_engage sae', 'sae.sr_advocate_id = CAST(coalesce(sa.id, \'0\') AS integer)');
                    $builder->JOIN('dscr.honorific ho', 'sa.honorific_id = ho.honorific_id');
                    $builder->WHERE('sa.status', '1');
                    $builder->WHERE('sae.createdby', (int)$params['advocateId']);
                    $builder->WHERE('sae.diary_no', (int)$params['diary_no']);
                    if (isset($params['user_type']) && !empty($params['user_type'])) {
                        $builder->WHERE('sae.user_type', (string)$params['user_type']);
                    }
                    $builder->ORDERBY('sa.first_name', 'ASC');
                    $query = $builder->get();
                    $output = $query->getResultArray();
                    break;
                default:
            }
        }
        return $output;
    }

    public function getSrAdvocateDataByDiaryNo($params = array())
    {
        $output = false;
        if ((isset($params['diary_no']) && !empty($params['diary_no']) && count($params['diary_no']) > 0) && isset($params['sr_advocate_id']) && !empty($params['sr_advocate_id']) && isset($params['sr_advocate_arguing_type']) && !empty($params['sr_advocate_arguing_type'])) {
            $type = $params['sr_advocate_arguing_type'];
            switch ($type) {
                case SR_ADVOCATE:
                    $builder = $this->db->table('efil.tbl_sr_advocate_engage sae')->select('sae.diary_no,sae.createdAt,concat(ib.title,ib.name) as assignedBy');
                    $builder->join('icmis.bar ib', 'sae.createdby= ib.bar_id');
                    $builder->whereIn('sae.diary_no', $params['diary_no']);
                    $builder->where('sae.sr_advocate_id', $params['sr_advocate_id']);
                    $builder->where('sae.is_active', true);
                    $query = $builder->get();
                    $output = $query->getResult();
                    break;
                case ARGUING_COUNSEL:
                    $builder = $this->db->table('efil.tbl_sr_advocate_engage sae')->select('sae.diary_no,sae.createdAt,concat(ib.title,ib.name) as assignedBy');
                    $builder->join('dscr.tbl_arguing_counsels tac', 'sae.sr_advocate_id = tac.id');
                    $builder->join('icmis.bar ib', 'sae.createdby= ib.bar_id');
                    $builder->whereIn('sae.diary_no', $params['diary_no']);
                    $builder->where('tac.tbl_users_id', $params['sr_advocate_id']);
                    $builder->where('sae.is_active', true);
                    $query = $builder->get();
                    $output = $query->getResult();
                    break;
                default:
            }
        }
        return $output;
    }

    public function changeIaMiscDocStage($registration_id, $stage_id)
    {
        $output = false;
        if (isset($registration_id) && !empty($registration_id) && isset($stage_id) && !empty($stage_id)) {
            $update_data = array(
                'deactivated_on' => date('Y-m-d H:i:s'),
                'is_active' => FALSE,
                'updated_by' => $_SESSION['login']['id'],
                'updated_by_ip' => getClientIP()
            );
            $builder = $this->db->table('efil.tbl_efiling_num_status');
            $builder->WHERE('registration_id', $registration_id);
            $builder->WHERE('is_active', TRUE);
            $builder->UPDATE($update_data);
            $insert_data = array(
                'registration_id' => $registration_id,
                'stage_id' => $stage_id,
                'activated_on' => date('Y-m-d H:i:s'),
                'is_active' => TRUE,
                'activated_by' => $_SESSION['login']['id'],
                'activated_by_ip' => getClientIP()
            );
            $builder = $this->db->table('efil.tbl_efiling_num_status');
            $query = $builder->INSERT($insert_data);
            if ($this->db->insertID()) {
                $output = TRUE;
            } else {
                $output = FALSE;
            }
        }
        return $output;
    }

    public function getHighCourtDetailsByHcIdAndEstabCode($params = array())
    {
        $output = false;
        if (isset($params['hc_id']) && !empty($params['hc_id']) && isset($params['est_code']) && !empty($params['est_code'])) {
            $builder = $this->db->table('efil.tbl_case_details tcd');
            $builder->SELECT('mthc.hc_id,mthc.name hname,mthcb.est_code,mthcb.bench_id,mthcb.name bname');
            $builder->JOIN('efil.m_tbl_high_courts_bench mthc', 'tcd.estab_id = mthc.hc_id and mthc.est_code is null', 'left');
            $builder->JOIN('efil.m_tbl_high_courts_bench mthcb', 'tcd.estab_code = mthcb.est_code', 'left');
            $builder->WHERE('tcd.estab_id', $params['hc_id']);
            $builder->WHERE('tcd.estab_code', $params['est_code']);
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

    public function getDistrictDataByCourtTypeAndStateIdDistrictId($params = array())
    {
        $output = false;
        if (isset($params['state_id']) && !empty($params['state_id']) && isset($params['district_id']) && !empty($params['district_id']) && isset($params['court_type']) && !empty($params['court_type'])) {
            $builder = $this->db->table('efil.m_tbl_district_courts_establishments mtdce');
            $builder->SELECT('mtdce.state_code,mtdce.state_name,mtdce.district_code,mtdce.district_name');
            $builder->WHERE('mtdce.state_code', $params['state_id']);
            $builder->WHERE('mtdce.district_code', $params['district_id']);
            $builder->WHERE('mtdce.court_code', $params['court_type']);
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

    public function getStateAgencyDetailsByStateIdAndEstabId($params = array())
    {
        $output = false;
        if (isset($params['state_id']) && !empty($params['state_id']) && isset($params['estab_id']) && !empty($params['estab_id'])) {
            $builder = $this->db->table('efil.tbl_case_details tcd');
            $builder->SELECT('ras.cmis_state_id,ras.agency_state,rac.id,rac.agency_name,rac.short_agency_name');
            $builder->JOIN('icmis.ref_agency_state ras', 'tcd.state_id = ras.cmis_state_id ', 'left');
            $builder->JOIN('icmis.ref_agency_code rac', 'tcd.estab_id = rac.id', 'left');
            $builder->WHERE('tcd.estab_id', $params['estab_id']);
            $builder->WHERE('tcd.state_id', $params['state_id']);
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

    function getEarlierCourtDetailByRegistrationId($registration_id)
    {
        if (isset($registration_id) && !empty($registration_id)) {
            $builder = $this->db->table('efil.tbl_case_details tcd');
            $builder->SELECT("tcd.estab_code,tcd.estab_id,tcd.court_type,tcd.state_id,tcd.district_id,
            (case when tcd.court_type = '1' then hcb.cmis_state_id   
            else case when tcd.court_type = '3' then dce.cmis_id_no
            else case when tcd.court_type = '5' then rac.cmis_state_id
            else case when tcd.court_type = '4' then 0 ::integer
            else case when tcd.court_type is null  then 0 ::integer
            end end end end end )as cmis_state_id  ,
            (case when tcd.court_type = '1' then hcb.ref_agency_code_id   
            else case when tcd.court_type = '3' then dce.id
            else case when tcd.court_type = '5' then rac.id
            else case when tcd.court_type = '4' then 0 ::integer
            else case when tcd.court_type is null  then 0 ::integer
            end end end end end )as ref_agency_code_id   
             ");
            $builder->JOIN('efil.m_tbl_high_courts_bench hcb', 'tcd.estab_code=hcb.est_code', 'left');
            $builder->JOIN('efil.m_tbl_district_courts_establishments dce', 'tcd.state_id=dce.state_code AND tcd.district_id= dce.district_code AND dce.court_code=3', 'left');
            $builder->JOIN('icmis.ref_agency_code rac', 'tcd.state_id=rac.cmis_state_id AND tcd.estab_id=rac.id  ', 'left');
            $builder->WHERE('tcd.registration_id', $registration_id);
            $builder->WHERE('tcd.is_deleted', FALSE);
            $query = $builder->get();
            if ($query->getNumRows() >= 1) {
                $new_case_details = $query->getResultArray();
                return $new_case_details;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function getAllocatedUser($params = array())
    {
        $output = false;
        if (isset($params['user_type']) && !empty($params['user_type']) && isset($params['not_in_user_id']) && !empty($params['not_in_user_id']) && isset($params['file_type_id']) && !empty($params['file_type_id']) && isset($params['pp_a']) && !empty($params['pp_a'])) {
            $sub_builder = $this->db->table('efil.tbl_efiling_nums');
            $sub_builder->SELECT('count(allocated_to) as counts');
            $sub_builder->WHERE("cast(allocated_on as date)='" . date('Y-m-d') . "'");
            $sub_builder->WHERE('allocated_to=tu.id');
            $subquey = $sub_builder->getCompiledSelect();
            $builder = $this->db->table('efil.tbl_users tu');
            $builder->SELECT("tu.id,tfaaf.file_type_id, ($subquey) as counts");
            $builder->JOIN('efil.tbl_filing_admin_assigned_file tfaaf', 'tu.id=tfaaf.user_id', 'left');
            $builder->JOIN('efil.m_tbl_efiling_type mtet', 'tfaaf.file_type_id=mtet.id');
            $builder->WHERE('tu.is_active', '1');
            $builder->WHERE('tfaaf.is_active', '1');
            $builder->WHERE('tfaaf.file_type_id', $params['file_type_id']);
            $builder->WHERE('tu.ref_m_usertype_id', $params['user_type']);
            $builder->WHERE('tu.pp_a', $params['pp_a']);
            $builder->WHERE('tu.attend', 'P');
            $builder->whereNotIn('tu.id', $params['not_in_user_id']);
            $builder->GROUPBY('tu.id,tfaaf.file_type_id');
            $builder->ORDERBY('counts, RANDOM()');
            $builder->LIMIT(1);
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

    public function updateLrData($params = array())
    {
        $output = false;
        if (isset($params['table_name']) && !empty($params['table_name']) && !empty($params['whereFieldName']) && isset($params['whereFieldName']) && isset($params['whereFieldValue']) && !empty($params['whereFieldValue']) && isset($params['updateArr']) && !empty($params['updateArr'])) {
            $builder = $this->db->table($params['table_name']);
            $builder->LIKE($params['whereFieldName'], $params['whereFieldValue'], 'after');
            $query = $builder->UPDATE($params['updateArr']);
            if ($query == true) {
                $output = true;
            }
        }
        return $output;
    }

    function get_index_items_list_mail($registration_id)
    {
        $builder = $this->db->table('efil.tbl_efiled_docs ed');
        $builder->SELECT('ed.*,tup.page_no,tup.doc_title as pdf,tup.file_size,tup.doc_hashed_value,dm.docdesc');
        $builder->JOIN('icmis.docmaster dm', 'ed.doc_type_id = dm.doccode AND ed.sub_doc_type_id = dm.doccode1 and dm.display!=\'N\'');
        $builder->JOIN('efil.tbl_uploaded_pdfs tup', 'ed.pdf_id=tup.doc_id');
        $builder->WHERE('ed.registration_id', $registration_id);
        $builder->WHERE('ed.is_active', TRUE);
        $builder->WHERE('ed.is_deleted', FALSE);
        $builder->ORDERBY('ed.index_no');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function getIcmisUserCodeByRegistrationId($params = array())
    {
        $output = false;
        if (isset($params['registration_id']) && !empty($params['registration_id'])) {
            $builder = $this->db->table('efil.tbl_efiling_nums en');
            $builder->SELECT('en.registration_id,en.allocated_to,tu.id,tu.icmis_usercode');
            $builder->JOIN('efil.tbl_users tu', 'en.allocated_to = tu.id');
            $builder->WHERE('en.registration_id', (int)$params['registration_id']);
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

    public function getTotalFeeByRegistrationId($params = array())
    {
        $output = false;
        if (isset($params['registration_id']) && !empty($params['registration_id'])) {
            $builder = $this->db->table('efil.tbl_court_fee_payment tfd');
            $builder->SELECT('tfd.registration_id,sum(tfd.received_amt) total');
            $builder->WHERE('tfd.registration_id', (int)$params['registration_id']);
            $builder->WHERE('tfd.is_deleted', FALSE);
            $builder->WHERE('tfd.payment_status', 'Y');
            $builder->groupBy('tfd.registration_id');
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

    function insert_efiling_sms_dtl($sms_details)
    {
        $builder = $this->db->table('efil.tbl_efiling_sms_log');
        $query = $builder->INSERT($sms_details);
        if ($this->db->insertID()) {
            return true;
        } else {
            return false;
        }
    }

    public function getArguingCounselData()
    {
        $output = false;
        $builder = $this->db->table('efil.tbl_users tu');
        $builder->SELECT('tac.id,tu.first_name');
        $builder->JOIN('dscr.tbl_arguing_counsels tac', 'tu.id = tac.tbl_users_id ');
        $builder->WHERE('tu.is_active', '1');
        $builder->WHERE('tu.is_deleted', false);
        $builder->WHERE('tu.ref_m_usertype_id', ARGUING_COUNSEL);
        $builder->WHERE('tac.account_status', 1);
        $builder->WHERE('tac.is_deleted', false);
        $builder->ORDERBY('tu.first_name', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getArguingCounselDiaryNo($params = array())
    {
        $output = false;
        if (isset($params['login_id']) && !empty($params['login_id'])) {
            $builder = $this->db->table('efil.tbl_sr_advocate_engage sae');
            $builder->SELECT('tac.tbl_users_id ,trim(array_agg(sae.diary_no)::text,\'{}\') as diary_no');
            $builder->join('dscr.tbl_arguing_counsels tac ', 'sae.sr_advocate_id = tac.id');
            $builder->WHERE('tac.tbl_users_id', (int)$params['login_id']);
            $builder->WHERE('sae.is_active', true);
            $builder->WHERE('tac.is_deleted', false);
            $builder->GROUPBY('tac.tbl_users_id');
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getAorSrAdvocateArguinCounsel($params = array())
    {
        $output = false;
        if (isset($params['shareUserType']) && !empty($params['shareUserType'])) {
            $builder = $this->db->table('efil.tbl_users tu');
            $builder->SELECT('tu.id,case when (tu.last_name is not null AND tu.last_name !=\'NULL\') then concat(tu.first_name,\' \' ,tu.last_name) else tu.first_name  end  as first_name,tu.moblie_number ,tu.emailid,tu.ref_m_usertype_id');
            $builder->WHERE('tu.ref_m_usertype_id', (int)$params['shareUserType']);
            $builder->WHERE('tu.is_deleted', FALSE);
            $builder->WHERE('tu.is_active', '1');
            $builder->WHERE('tu.first_name !=', '0');
            $builder->orderBy('tu.first_name');
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getEngagedDiaryIds($params = array())
    {
        $output = false;
        if (isset($params['is_active']) && !empty($params['is_active'])) {
            $builder = $this->db->table('efil.tbl_sr_advocate_engage sar')->select('sar.diary_no');
            $builder->join('efil.tbl_sr_advocate_engage_history as saeh ', 'sar.diary_no =saeh.diary_no');
            $builder->where('sar.is_active', $params['is_active']);
            $builder->where('saeh.is_active', $params['is_active']);
            $builder->groupBy('sar.diary_no');
            $query = $builder->get();
            $output = $query->getResultArray();
        }
        return $output;
    }

    public function getDocumentFeeByRegistrationId($params = array())
    {
        $output = false;
        if (isset($params['registration_id']) && !empty($params['registration_id'])) {
            $builder = $this->db->table('efil.tbl_court_fee_payment');
            $builder->SELECT('user_declared_court_fee,uploaded_pages,no_of_copies,per_page_charges,printing_total,user_declared_total_amt,received_amt');
            $builder->WHERE('registration_id', (int)$params['registration_id']);
            $builder->WHERE('is_deleted', FALSE);
            $builder->WHERE('payment_status', 'Y');
            $builder->ORDERBY('id', 'DESC');
            $builder->LIMIT(1);
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

    public function isRefilingCompulsoryIADefect($registration_id)
    {
        $output = false;
        if (isset($registration_id) && !empty($registration_id)) {
            $builder = $this->db->table('efil.tbl_efiled_docs');
            $builder->SELECT('*');
            $builder->WHERE('registration_id', (int)$registration_id);
            $builder->WHERE('doc_type_id', INTERLOCUTARY_APPLICATION);
            $builder->WHERE('sub_doc_type_id', CONDONATION_OF_DELAY_IN_REFILING_OR_CURING_THE_DEFECTS);
            $builder->WHERE('is_deleted', FALSE);
            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

    function get_old_efiling_cases($diary_no = null, $aor_code = null)
    {
        $builder = $this->db->table('icmis.old_efiling_cases oec');
        $builder->SELECT("*");
        $builder->WHERE('aor_code', $aor_code);
        $builder->WHERE('diary_no', $diary_no);
        $builder->WHERE('oec.is_deleted', FALSE);
        $queryCD = $builder->get();
        if ($queryCD->getNumRows() >= 1) {
            return $case_details = $queryCD->getResultArray();
        } else {
            return FALSE;
        }
    }

    public function get_all_old_efiling_submitted_cases_for_cron($stages, $efiled_type_id, $usertype)
    {
        $sql = "SELECT ens.registration_id,ten.efiling_no,ten.efiling_year,ens.stage_id,ten.efiling_for_type_id,ten.create_by_ip,ten.create_on,
       ten.ref_m_efiled_type_id, tu.ref_m_usertype_id,
       tu.aor_code,tmdi.diary_no,tmdi.diary_year
        FROM  efil.tbl_efiling_nums ten 
        INNER JOIN efil.tbl_efiling_num_status ens on (ten.registration_id=ens.registration_id and ens.stage_id in($stages) and ens.is_active=true)
        INNER JOIN efil.tbl_users tu on tu.id=ten.created_by 
        left join efil.tbl_misc_docs_ia tmdi on ten.registration_id=tmdi.registration_id
        WHERE ten.is_active = true and ten.ref_m_efiled_type_id =$efiled_type_id and tu.ref_m_usertype_id = $usertype 
        and tmdi.is_deleted='F' and ens.registration_id is not null and ens.stage_id is not null  ";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function openDefectApplicationsList($open_defects_diary_ids_csv)
    {
        $sql = 'select en.*,concat(cd.sc_diary_num,cd.sc_diary_year) as diaryid from efil.tbl_efiling_nums en inner join efil.tbl_case_details cd on en.registration_id=cd.registration_id where concat(cd.sc_diary_num,cd.sc_diary_year) in (' . $open_defects_diary_ids_csv . ')';
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return array();
        }
    }

    public function getCaveatDataByRegistrationId($arr = array())
    {
        $output = false;
        $query = '';
        if (isset($arr) && !empty($arr) && is_array($arr)) {
            if (isset($arr['registration_id']) && !empty($arr['registration_id']) && !empty($arr['step']) && isset($arr['step'])) {
                $step = (int)trim($arr['step']);
                $registration_id = (int)trim($arr['registration_id']);
                switch ($step) {
                    case 1:
                        $builder = $this->db->table('public.tbl_efiling_caveat');
                        $builder->select('id,case_type_id,ref_m_efiling_nums_registration_id,pet_inperson,orgid,pet_name,lpet_name,pet_sex,pet_gender,pet_father_name,lpet_father_name,
                        pet_father_flag,pet_dob,pet_age,pet_email,pet_mobile,petadd,state_id,dist_code,pet_pincode,org_state,org_state_name,org_dept_name,org_dept,org_post,
                        org_post_name,pet_city,pet_extracount,is_govt_filing');
                        $builder->where('ref_m_efiling_nums_registration_id', $registration_id);
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    case 2:
                        $builder = $this->db->table('public.tbl_efiling_caveat');
                        $builder->select('id,case_type_id,ref_m_efiling_nums_registration_id,res_name,resorgid,res_sex,res_gender,res_father_flag,res_father_name,res_age,res_dob,
                        res_email,res_mobile,resadd,res_pincode,res_state_id,res_dist,res_city,res_org_state,res_org_state_name,res_org_dept,res_org_dept_name,
                        res_org_post,res_org_post_name,res_extracount,is_govt_filing');
                        $builder->where('ref_m_efiling_nums_registration_id', $registration_id);
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    case 3:
                        $builder = $this->db->table('public.tbl_efiling_caveat ec');
                        $builder->select(" 'P' as p_r_type, 'M' as m_a_type,1 as party_id,1 as party_no,
                            ec.id, ec.ref_m_efiling_nums_registration_id,ec.createdby,ec.case_type_id,
                            ec.orgid as party_type, 
                            ec.pet_name as party_name, 
                            ec.pet_gender as gender, ec.pet_father_flag as relation,ec.pet_father_name as relative_name,
                            ec.pet_dob as party_dob,
                            ec.pet_age as party_age, ec.pet_email as email_id, ec.pet_mobile as mobile_num,
                            ec.petadd as address, ec.state_id, ec.dist_code as district_id, 
                            ec.pet_pincode as pincode, ec.org_state as org_state_id, ec.org_state_name as org_state_name,
                            ec.org_dept_name as org_dept_name, ec.org_dept as org_dept_id, ec.org_post as org_post_id,
                            ec.org_post_name as org_post_name, ec.pet_city as city, dist.name addr_dist_name, 
                            a.authdesc, d.deptname, st.agency_state addr_state_name, 
                            vst.deptname fetch_org_state_name, ec.is_govt_filing");
                        $builder->JOIN('icmis.ref_agency_state st', 'ec.state_id = st.cmis_state_id', 'left');
                        $builder->JOIN('icmis.view_state_in_name vst', 'ec.org_state = vst.deptcode', 'left');
                        $builder->JOIN('icmis.state dist', 'ec.dist_code = dist.id_no', 'left');
                        $builder->JOIN('icmis.deptt d', 'ec.org_dept=d.deptcode', 'left');
                        $builder->JOIN('icmis.authority a', 'ec.org_post=a.authcode', 'left');
                        $builder->where('ec.ref_m_efiling_nums_registration_id', $registration_id);
                        $query = $builder->get();
                        $output = $query->getResult();
                        break;
                    case 4:
                        $builder = $this->db->table('public.tbl_efiling_caveat ec');
                        $builder->select(" 'R' as p_r_type, 'M' as m_a_type,1 as party_id,1 as party_no,
                            ec.id, ec.ref_m_efiling_nums_registration_id, resorgid as party_type, ec.res_name as party_name, 
                            ec.res_gender as gender, ec.res_father_flag as relation, ec.res_father_name as relative_name,
                            ec.res_dob as party_dob,ec.res_age as  party_age,  ec.res_email as  email_id, 
                            ec.res_mobile as mobile_num, ec.resadd as  address,ec.res_state_id as state_id, ec.res_dist as district_id,
                            ec.res_pincode as pincode,ec.res_org_state as org_state_id, ec.res_org_state_name as org_state_name,  
                                ec.res_org_dept as org_dept_id,
                            ec.res_org_dept_name as org_dept_name, ec.res_org_post as org_post_id, ec.res_org_post_name as org_post_name,
                            ec.res_city as city,
                            dist.name addr_dist_name, a.authdesc, d.deptname, st.agency_state addr_state_name, vst.deptname fetch_org_state_name, ec.is_govt_filing");
                        $builder->JOIN('icmis.ref_agency_state st', 'ec.state_id = st.cmis_state_id', 'left');
                        $builder->JOIN('icmis.view_state_in_name vst', 'ec.org_state = vst.deptcode', 'left');
                        $builder->JOIN('icmis.state dist', 'ec.dist_code = dist.id_no', 'left');
                        $builder->JOIN('icmis.deptt d', 'ec.org_dept=d.deptcode', 'left');
                        $builder->JOIN('icmis.authority a', 'ec.org_post=a.authcode', 'left');
                        $builder->WHERE('ec.ref_m_efiling_nums_registration_id', $registration_id);
                        $query = $builder->get();
                        $output = $query->getResult();
                        break;
                    case 5:
                        $builder = $this->db->table('public.tbl_efiling_civil_extra_party as eparty');
                        $builder->SELECT("case when eparty.type=1 then 'P' else 'R' end as p_r_type, 
                            'A' as m_a_type,
                            eparty.party_id,eparty.party_no,
                            eparty.id, eparty.ref_m_efiling_nums_registration_id,
                            eparty.orgid as party_type, 
                            eparty.name as party_name, 
                            eparty.pet_gender as gender, eparty.father_flag as relation,eparty.father_name as relative_name,
                            eparty.pet_dob as party_dob,
                            eparty.pet_age as party_age, eparty.pet_email as email_id, eparty.pet_mobile as mobile_num,
                            eparty.address as address, eparty.state_id, eparty.dist_code as district_id, 
                            eparty.pincode as pincode, eparty.extra_party_org_state as org_state_id, eparty.extra_party_org_state_name as org_state_name,
                            eparty.extra_party_org_dept_name as org_dept_name, eparty.extra_party_org_dept as org_dept_id, eparty.extra_party_org_post as org_post_id,
                            eparty.extra_party_org_post_name as org_post_name, eparty.pet_city as city, dist.name addr_dist_name, 
                            a.authdesc, d.deptname, st.agency_state addr_state_name, 
                            vst.deptname fetch_org_state_name");
                        $builder->JOIN('icmis.ref_agency_state st', 'eparty.state_id = st.cmis_state_id', 'left');
                        $builder->JOIN('icmis.ref_agency_state altst', 'eparty.altstate_id = altst.cmis_state_id', 'left');
                        $builder->JOIN('icmis.view_state_in_name vst', 'eparty.extra_party_org_state = vst.deptcode', 'left');
                        $builder->JOIN('icmis.state dist', 'eparty.dist_code = dist.id_no', 'left');
                        $builder->JOIN('icmis.state altdist', 'eparty.altdist_code = altdist.id_no', 'left');
                        $builder->JOIN('icmis.deptt d', 'eparty.extra_party_org_dept=d.deptcode', 'left');
                        $builder->JOIN('icmis.authority a', 'eparty.extra_party_org_post=a.authcode', 'left');
                        $builder->WHERE('eparty.ref_m_efiling_nums_registration_id', $registration_id);
                        $builder->WHERE('eparty.parentid', NULL);
                        $builder->WHERE('eparty.display', 'Y');
                        $builder->orderBy("eparty.type", "asc");
                        $builder->orderBy("eparty.party_id", "asc");
                        $query = $builder->get();
                        $output = $query->getResult();
                        break;
                    case 6:
                        $builder = $this->db->table('public.tbl_efiling_caveat tec');
                        $builder->select('id,case_type_id,c.nature,ref_m_efiling_nums_registration_id,pet_inperson,orgid,pet_name,lpet_name,pet_sex,pet_gender,pet_father_name,lpet_father_name,
                        pet_father_flag,pet_dob,pet_age,pet_email,pet_mobile,petadd,state_id,dist_code,pet_pincode,org_state,org_state_name,org_dept_name,org_dept,org_post,
                        org_post_name,pet_city, tec.is_govt_filing');
                        $builder->JOIN('icmis.casetype c', 'tec.case_type_id = c.casecode', 'left');
                        $builder->where('ref_m_efiling_nums_registration_id', $registration_id);
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    default:
                        $output = false;
                }
            }
        }
        return $output;
    }

    function check_efiling_sms_log($mobileNo, $sms_datetime)
    {
        $from_sms_datetime = $_SESSION['from_sms_datetime'];
        $end_sms_datetime = $_SESSION['last_sms_datetime'];
        $builder = $this->db->table('efil.tbl_efiling_sms_log');
        $builder->select('*');
        $builder->where('mobile_no', $mobileNo);
        $builder->where('sent_on >=', $from_sms_datetime);
        $builder->where('sent_on <=', $end_sms_datetime);
        $builder->orderBy('sent_on', 'DESC');
        $queryCD = $builder->get();
        if ($queryCD->getNumRows() >= 1) {
            return $queryCD->getResultArray();
        } else {
            return FALSE;
        }
    }

    function insert_efiling_sms_email_dtl($sms_details)
    {
        $builder = $this->db->table('efil.otp_requests');
        $builder->insert($sms_details);
        $insertID = $this->db->insertID();
        if ($insertID) {
            return true;
        } else {
            return false;
        }
    }

    function check_efiling_sms_email_log($to_email, $request_time_time_period, $ip_address)
    {
        $builder = $this->db->table('efil.otp_requests');
        $builder->where('ip_address', $ip_address)
            ->where('email', $to_email)
            ->where('request_time >', $request_time_time_period);
        $request_count = $builder->countAllResults();
        return $request_count;
    }

    function check_efiling_sms_mobile_no_log($mobile_no, $request_time_time_period, $ip_address)
    {
        $builder = $this->db->table('efil.otp_requests');
        $builder->where('ip_address', $ip_address)
            ->where('mobile_no', $mobile_no)
            ->where('request_time >', $request_time_time_period);
        $request_count = $builder->countAllResults();
        return $request_count;
    }

    public function update_efiling_nums($registration_id, $case_details_update_data)
    {
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->where('registration_id', $registration_id)
            ->where('is_active', TRUE)
            ->update($case_details_update_data);
    }

    function get_ia_docs_intials_defects_remarks($registration_id, $current_stage_id)
    {
        if (empty($registration_id)) {
            return FALSE;
        }
        $builder = $this->db->table('efil.tbl_initial_defects');
        $builder->select('*')->where('is_approved', false);
        if (in_array(
            $current_stage_id,
            [
                Initial_Defected_Stage,
                DEFICIT_COURT_FEE,
                I_B_Defected_Stage,
                I_B_Rejected_Stage,
                E_REJECTED_STAGE,
                LODGING_STAGE,
                DELETE_AND_LODGING_STAGE,
            ]
        )) {
            $builder->where('is_defect_cured', false);
        } elseif (in_array(
            $current_stage_id,
            [
                Initial_Approaval_Pending_Stage,
                Initial_Defects_Cured_Stage,
                DEFICIT_COURT_FEE_PAID,
                I_B_Approval_Pending_Stage,
                I_B_Approval_Pending_Admin_Stage,
                I_B_Defects_Cured_Stage,
            ]
        )) {
            $builder->where('is_defect_cured', true);
        }
        $builder->where('registration_id', $registration_id)->orderBy('initial_defects_id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $arr = $query->getResultObject();
            return $arr[0];
        } else {
            return false;
        }
    }

    function get_ia_docs_cis_defects_remarks($registration_id, $show_always)
    {
        if (empty($registration_id)) {
            return FALSE;
        }
        if (!$show_always) {
            $builder = $this->db->table('efil.tbl_icmis_ai_docs_objections');
            $builder->select('count(id) as obj_count')
                ->where('registration_id', $registration_id)
                ->where('is_deleted', false)
                ->where('obj_removed_date', null);
            $query = $builder->get();
            $obj_exists = $query->getResult();
            if ($obj_exists[0]->obj_count == 0) {
                return false;
            }
        }
        $builder = $this->db->table('efil.tbl_icmis_ai_docs_objections AS obj');
        $builder->select([
            'obj.obj_id',
            'obj.id',
            'icmis_obj.objdesc',
            'obj.remarks',
            'obj.obj_prepare_date',
            'obj.obj_removed_date',
            'obj.pspdfkit_document_id',
            'obj.to_be_modified_pspdfkit_document_pages_raw',
            'obj.to_be_modified_pspdfkit_document_pages_parsed',
            'obj.aor_cured',
        ])
            ->join('icmis.objection AS icmis_obj', 'obj.obj_id = icmis_obj.objcode', 'left')
            ->where('registration_id', $registration_id)
            ->where('obj.is_deleted', false)
            ->orderBy('obj.id', 'asc');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $arr = $query->getResult();
            return $arr;
        } else {
            return false;
        }
    }

    function get_ia_docs_pdf_defects_remarks($reg)
    {
        $pdfdefectsarr = array();
        $result2 = $this->getIaDocsPspdfkitDocId($reg);
        if ($result2) {
            foreach ($result2 as $res) {
                $pspdfkit_document_id = $res['pspdfkit_document_id'];
                if ($pspdfkit_document_id != '') {
                    $pages = array();
                    $notedpages = array();
                    $response = $this->getIaDocsPdfAnnotations($pspdfkit_document_id);
                    $data = json_decode($response, true);
                    foreach ($data['data']['annotations'] as $obj) {
                        $pageIndex = (int)$obj['content']['pageIndex'];
                        $pageNo = $pageIndex + 1;
                        if (!in_array($pageNo, $notedpages)) {
                            array_push($pages, $pageNo);
                            array_push($notedpages, $pageNo);
                        }
                    }
                    if (!empty($pages)) {
                        $pdfdefectsarr[$pspdfkit_document_id] = $pages;
                    }
                }
            }
        }
        return $pdfdefectsarr;
    }

    function get_certified_copy_details($registration_id)
    {
        $builder = $this->db->table('efil.tbl_certified_copy_details tccd');
        $builder->select([
            'tccd.*',
            'tlcd.court_type',
            "CASE
                WHEN tlcd.court_type = '1' THEN 'High Court'
                WHEN tlcd.court_type = '3' THEN 'District Court'
                WHEN tlcd.court_type = '5' THEN 'State Agency or Tribunal'
                WHEN tlcd.court_type = '4' THEN 'Supreme Court'
                ELSE 'Unknown Court'
            END AS court_type_name",
        ]);
        $builder->join('efil.tbl_lower_court_details tlcd', 'tccd.ref_tbl_lower_court_details_id = tlcd.id AND tccd.registration_id = tlcd.registration_id');
        $builder->where('tccd.registration_id', $registration_id);
        $builder->where('tccd.is_deleted', false);
        $builder->where('tlcd.is_deleted', false);
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getIaDocsPspdfkitDocId($reg)
    {
        $builder = $this->db->table('efil.tbl_efiled_docs AS ed');
        $builder->select('ed.pspdfkit_document_id')
            ->where('ed.registration_id', $reg)
            ->where('ed.is_deleted', false);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function getIaDocsPdfAnnotations($pspdfkit_document_id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('PSPDFKIT_SERVER_URI') . "/api/documents/" . $pspdfkit_document_id . "/annotations",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: Token token=secret"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /*Start Recategorization*/
    public function get_court_fee_by_casetype_with_submaster_id_zero($registration_id)
    {
        $sql = "select cd.sc_case_type_id,cd.subject_cat,mcf.court_fee,mcf.flag,s.subcode1,s.subcode2,s.subcode3,s.subcode4,s.sub_name1,s.sub_name2,s.sub_name3,
            s.sub_name4,s.category_sc_old,c.casename,c.nature,
            cd.court_fee_calculation_helper_flag,
            (select count(1) from  efil.tbl_lower_court_details lcd where lcd.registration_id=cd.registration_id and lcd.is_deleted='false' and lcd.is_judgment_challenged ='true') as order_challanged,
            sum(case when cp.p_r_type='P' and cp.is_deleted='false' then 1 else 0 end) as total_petitioners                    
            from efil.tbl_case_details cd                     
            inner join icmis.m_court_fee mcf on (cd.sc_case_type_id=mcf.casetype_id and mcf.submaster_id=0) 
            left join efil.tbl_case_parties cp on cd.registration_id =cp.registration_id 
            left join icmis.submaster s on cd.subject_cat =s.id and s.is_old='N' and s.display='Y' and s.is_3j=0 and s.is_5j=0 and s.is_7j=0 and s.is_9j=0 and s.is_11j=0
            left join icmis.casetype c on cd.sc_case_type_id =c.casecode 
            where cd.registration_id=$registration_id and mcf.display = 'Y' 
            group by cd.sc_case_type_id,cd.subject_cat,mcf.court_fee,mcf.flag,s.subcode1,s.subcode2,s.subcode3,s.subcode4,s.sub_name1,
            s.sub_name2,s.sub_name3,s.sub_name4,s.category_sc_old,c.casename,c.nature,cd.court_fee_calculation_helper_flag,cd.registration_id order by mcf.court_fee desc limit 1";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            $sql2 = "select cd.sc_case_type_id,cd.subject_cat,1500 as court_fee ,mcf.flag,s.subcode1,s.subcode2,s.subcode3,s.subcode4,s.sub_name1,s.sub_name2,s.sub_name3,
                s.sub_name4,s.category_sc_old,c.casename,c.nature,
                cd.court_fee_calculation_helper_flag,
                (select count(1) from  efil.tbl_lower_court_details lcd where lcd.registration_id=cd.registration_id and lcd.is_deleted='false' and lcd.is_judgment_challenged ='true') as order_challanged,
                sum(case when cp.p_r_type='P' and cp.is_deleted='false' then 1 else 0 end) as total_petitioners                    
                from efil.tbl_case_details cd                     
                left join icmis.m_court_fee mcf on cd.sc_case_type_id=mcf.casetype_id and mcf.submaster_id=0  and mcf.display = 'Y' 
                left join efil.tbl_case_parties cp on cd.registration_id =cp.registration_id 
                left join icmis.submaster s on cd.subject_cat =s.id and s.is_old='N' and s.display='Y' and s.is_3j=0 and s.is_5j=0 and s.is_7j=0 and s.is_9j=0 and s.is_11j=0
                left join icmis.casetype c on cd.sc_case_type_id =c.casecode 
                where cd.registration_id=$registration_id
                group by cd.sc_case_type_id,cd.subject_cat,mcf.court_fee,mcf.flag,s.subcode1,s.subcode2,s.subcode3,s.subcode4,s.sub_name1,
                s.sub_name2,s.sub_name3,s.sub_name4,s.category_sc_old,c.casename,c.nature,cd.court_fee_calculation_helper_flag,cd.registration_id order by mcf.court_fee desc limit 1";
            $query = $this->db->query($sql2);
            if ($query->getNumRows() >= 1) {
                $result = $query->getResultArray();
                return $result;
            } else {
                return FALSE;
            }
        }
    }
    /*end Recategorization*/
}
