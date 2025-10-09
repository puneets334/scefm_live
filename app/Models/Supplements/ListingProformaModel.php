<?php

namespace App\Models\Supplements;

use CodeIgniter\Model;

class Listing_proforma_model extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        //$this->db = $this->load->database('db', TRUE);
    }

    function insert_pdf_data($array)
    {
        $builder = $this->db->table('efil.tbl_listing_proforma_pdf_gen');
        $builder->insert($array);
        // Check if insertion was successful and return boolean
        if ($this->db->insertID()) {
            return true;
        } else {
            return false;
        }
    }
    //End of function insert_pdf_data()..

    function update_pdf_data($reg_ID, $data_update)
    {
        $builder = $this->db->table('efil.tbl_listing_proforma_pdf_gen');
        $builder->where('registration_id', $reg_ID);
        $builder->where('is_active', true);
        $builder->set($data_update);
        $builder->update();
        // Check if update was successful and return boolean
        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    //END of function update_pdf_data()..

    public function get_pdf_store_data($registration_id)
    {
        $builder = $this->db->table('efil.tbl_case_details tcd');
        $builder->select("
            tcd.*, tcp.party_name as pet_party_name, tcp.email_id as pet_email,
            tcp.mobile_num as pet_mobile, tcp2.party_name as res_party_name,
            tcp2.email_id as res_email, tcp2.mobile_num as res_mobile,
            s.sub_name1 as main_category, s1.sub_name4 as sub_category,
            c.nature as nature_of_mttr, mthcb.name as high_court_name,
            tfd.fir_no, tfd.police_station_name, tlcd.impugned_order_date,
            tlcd.judgment_type, tlcd.estab_id as hc_ID, tlcd.court_type as courtType,
            tlcd2.court_type as tribunal_court, tlcd2.agency_code,
            (CASE WHEN tlcd2.agency_code > 0 THEN ras.agency_state ELSE null END) as tribunal_state_name,
            tlppg.*
        ");
        $builder->join('efil.tbl_case_parties tcp', 'tcd.registration_id = tcp.registration_id', 'left');
        $builder->join('efil.tbl_case_parties tcp2', 'tcd.registration_id = tcp2.registration_id', 'left');
        // $builder->join('icmis.submaster s', 'tcd.subj_main_cat = s.id', 'left');
        // $builder->join('icmis.submaster s1', 'tcd.subj_sub_cat_1 = s1.id', 'left');
        $builder->join('icmis.submaster s', 'tcd.subj_main_cat = s.id', 'left')
            ->where('s.is_old', 'N')
            ->where('s.display', 'Y')
            ->where('s.is_3j', 0) // Use 0 as an integer
            ->where('s.is_5j', 0)
            ->where('s.is_7j', 0)
            ->where('s.is_9j', 0)
            ->where('s.is_11j', 0);
        // Perform the second left join with the conditions specified
        $builder->join('icmis.submaster s1', 'tcd.subj_sub_cat_1 = s1.id', 'left')
            ->where('s1.is_old', 'N')
            ->where('s1.display', 'Y')
            ->where('s1.is_3j', 0) // Use 0 as an integer
            ->where('s1.is_5j', 0)
            ->where('s1.is_7j', 0)
            ->where('s1.is_9j', 0)
            ->where('s1.is_11j', 0);
        $builder->join('icmis.casetype c', 'tcd.sc_case_type_id = c.casecode', 'left');
        $builder->join('efil.tbl_fir_details tfd', 'tcd.registration_id = tfd.registration_id', 'left');
        $builder->join('efil.tbl_lower_court_details tlcd', 'tcd.registration_id = tlcd.registration_id 
            AND tlcd.court_type = (
                SELECT court_type FROM efil.tbl_lower_court_details tlcd1 
                WHERE tlcd1.registration_id = ' . $registration_id . ' 
                ORDER BY court_type ASC 
                LIMIT 1
            )', 'left');
        $builder->join('efil.m_tbl_high_courts_bench mthcb', 'tlcd.estab_id = mthcb.hc_id', 'left');
        $builder->join('efil.tbl_lower_court_details tlcd2', 'tcd.registration_id = tlcd2.registration_id 
            AND tlcd2.court_type = (
                SELECT court_type FROM efil.tbl_lower_court_details tlcd3 
                WHERE tlcd3.registration_id = ' . $registration_id . ' 
                ORDER BY court_type DESC 
                LIMIT 1
            )', 'left');
        $builder->join('icmis.ref_agency_state ras', 'tlcd2.agency_code = ras.id AND tlcd2.agency_code > 0', 'left');
        $builder->join('efil.tbl_listing_proforma_pdf_gen tlppg', 'tcd.registration_id = tlppg.registration_id', 'left');
        $builder->where([
            'tcd.registration_id' => $registration_id,
            'tcd.is_deleted' => 'false',
            'tcp.p_r_type' => 'P',
            'tcp.m_a_type' => 'M',
            'tcp2.p_r_type' => 'R',
            'tcp2.m_a_type' => 'M',
            'mthcb.bench_id' => null,
            'mthcb.est_code' => null
        ]);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    //END of function get_pdf_store_data ()..

    public function get_pdf_data_check($pdf_registration_id)
    {
        $builder = $this->db->table('efil.tbl_listing_proforma_pdf_gen tlppg');
        $builder->select('tlppg.*');
        $builder->where('tlppg.registration_id', $pdf_registration_id);
        $builder->where('tlppg.is_active', true);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    //END of function get_pdf_data_check()..

    public function get_listing_proforma_list_pdf($registration_id)
    {
        $builder = $this->db->table('efil.tbl_case_details tcd');
        $builder->select("
            tcd.*, tcp.party_name as pet_party_name, tcp.email_id as pet_email,
            tcp.mobile_num as pet_mobile, tcp2.party_name as res_party_name,
            tcp2.email_id as res_email, tcp2.mobile_num as res_mobile,
            s.sub_name1 as main_category, s1.sub_name4 as sub_category,
            c.nature as nature_of_mttr, mthcb.name as high_court_name,
            tfd.fir_no, tfd.police_station_name, tlcd.impugned_order_date,
            tlcd.judgment_type, tlcd.estab_id as hc_ID, tlcd.court_type as courtType,
            tlcd2.court_type as tribunal_court, tlcd2.agency_code,
            (CASE WHEN tlcd2.agency_code > 0 THEN ras.agency_state ELSE null END) as tribunal_state_name
        ");
        $builder->join('efil.tbl_case_parties tcp', 'tcd.registration_id = tcp.registration_id', 'left');
        $builder->join('efil.tbl_case_parties tcp2', 'tcd.registration_id = tcp2.registration_id', 'left');
        // $builder->join('icmis.submaster s', 'tcd.subj_main_cat = s.id', 'left');
        // $builder->join('icmis.submaster s1', 'tcd.subj_sub_cat_1 = s1.id', 'left');
        $builder->join('icmis.submaster s', "tcd.subj_main_cat =s.id s.is_old='N' and s.display='Y' and s.is_3j='0' and s.is_5j='0' and s.is_7j='0' and s.is_9j='0' and s.is_11j='0'", 'left');
        $builder->join('icmis.submaster s1', "tcd.subj_sub_cat_1 =s1.id s1.is_old='N' and s1.display='Y' and s1.is_3j='0' and s1.is_5j='0' and s1.is_7j='0' and s1.is_9j='0' and s1.is_11j='0'", 'left');
        $builder->join('icmis.casetype c', 'tcd.sc_case_type_id = c.casecode', 'left');
        $builder->join('efil.tbl_fir_details tfd', 'tcd.registration_id = tfd.registration_id', 'left');
        $builder->join('efil.tbl_lower_court_details tlcd', 'tcd.registration_id = tlcd.registration_id 
            AND tlcd.court_type = (
                SELECT court_type FROM efil.tbl_lower_court_details tlcd1 
                WHERE tlcd1.registration_id = ' . $registration_id . ' 
                ORDER BY court_type ASC 
                LIMIT 1
            )', 'left');
        $builder->join('efil.m_tbl_high_courts_bench mthcb', 'tlcd.estab_id = mthcb.hc_id', 'left');
        $builder->join('efil.tbl_lower_court_details tlcd2', 'tcd.registration_id = tlcd2.registration_id 
            AND tlcd2.court_type = (
                SELECT court_type FROM efil.tbl_lower_court_details tlcd3 
                WHERE tlcd3.registration_id = ' . $registration_id . ' 
                ORDER BY court_type DESC 
                LIMIT 1
            )', 'left');
        $builder->join('icmis.ref_agency_state ras', 'tlcd2.agency_code = ras.id AND tlcd2.agency_code > 0', 'left');
        $builder->where([
            'tcd.registration_id' => $registration_id,
            'tcd.is_deleted' => 'false',
            'tcp.p_r_type' => 'P',
            'tcp.m_a_type' => 'M',
            'tcp2.p_r_type' => 'R',
            'tcp2.m_a_type' => 'M',
            'mthcb.bench_id' => null,
            'mthcb.est_code' => null
        ]);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    // End of function get_listing_proforma_list_pdf()..
    // and tcd.sc_case_type_id=tlcd.case_type_id

    public function get_act_list_pdf($registration_id)
    {
        $builder = $this->db->table('efil.tbl_act_sections act');
        $builder->select('act.id, act.act_id, act_m.act_name, act_m.year as act_year, act.act_section, act_m.is_approved, act_m.state_id, act_m.actno');
        $builder->join('icmis.act_master act_m', 'act.act_id = act_m.id');
        $builder->where('act.registration_id', $registration_id);
        $builder->where('act.is_deleted', false);
        $builder->where('act_m.display', 'Y');
        $builder->orderBy('act.id', 'asc');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    //End of function get_act_list_pdf()..

    //XXXXXXXXXXXXXXXXXXXXXXXX
    function get_data_proforma($registration_id)
    {
        $builder = $this->db->table('efil.tbl_listing_proforma_pdf_gen');
        $builder->where('registration_id', $registration_id);
        $query = $builder->get();
        return $query->getRowArray();
    }
    //End of function get_data_proforma()..

    function update_data_signaftr($table, $data, $condition)
    {
        $builder = $this->db->table($table);
        $builder->where($condition);
        $builder->set($data);
        return $builder->update();
    }

    public function table_data_signpdf($table, $condition = '1=1')
    {
        $builder = $this->db->table($table);
        $builder->where($condition);
        $builder->orderBy('1'); // Assuming you want to order by the first column
        $query = $builder->get();
        return $query->getResultArray();
    }

    //XXXXXXXXXXXXXXXXXXXXXXX
    public function pdf_data_cancel_listingproforma($reg_ID, $data_cancel)
    {
        $builder = $this->db->table('efil.tbl_listing_proforma_pdf_gen');
        $builder->where('registration_id', $reg_ID);
        $builder->where('is_active', true);
        $builder->set($data_cancel);
        $builder->update();
        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
