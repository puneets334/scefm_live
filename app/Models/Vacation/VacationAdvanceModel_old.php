<?php

namespace App\Models\Vacation;
use CodeIgniter\Model;
use DateTime;

class VacationAdvanceModel_old extends Model {

    function __construct() {
        parent::__construct();
    }

    public function is_vacation_advance_list_duration() {
        $output = false;
        $current_year = date('Y');
        $builder = $this->db->table('icmis.vacation_advance_list_duration');
        $builder->SELECT("*");
        $builder->WHERE('vacation_list_year', $current_year);
        $builder->WHERE('is_active', 't');
        $query = $builder->get();
        $result= $query->getRowArray();
        if ($result) {
            $current_date=date('Y-m-d');            
            $activated_from_date=$result['activated_from_date'];
            $activated_to_date=$result['activated_to_date'];    
            $activated_from_time=$result['activated_from_time'];
            $activated_to_time=$result['activated_to_time'];
            $activated_from_date =!empty($activated_from_date) ? date('Y-m-d', strtotime($activated_from_date)) : '';
            $activated_to_date =!empty($activated_to_date) ? date('Y-m-d', strtotime($activated_to_date)) : '';    
            if (($current_date >= $activated_from_date) && ($current_date <= $activated_to_date)){
                $response="is between";
                if (!empty($activated_from_time) && !empty($activated_to_time)) {
                    $current_time=date('H:i a');
                    $sunrise = $activated_from_time;
                    $sunset = $activated_to_time;
                    $date1 = DateTime::createFromFormat('H:i a', $current_time);
                    $date2 = DateTime::createFromFormat('H:i a', $sunrise);
                    $date3 = DateTime::createFromFormat('H:i a', $sunset);
                    if ($date1 > $date2 && $date1 < $date3) {
                        $output=true;
                    } else {
                        
                    }
                } else {
                    $output=true;
                }    
            }    
        }    
        if ($output) {
            return $output;
        } else {
            return $output;
        }
    }

    public function get_vacation_advance_list($aor_code, $decline_condition = null) {
        $decline_condition=(!empty($decline_condition) && $decline_condition =='D') ? " and declined_by_aor='t' " :'';
        $current_year=date('Y');
        $query = "SELECT DISTINCT
            m.diary_no,
            m.conn_key,
            CASE
                WHEN
                    (m.diary_no::text = m.conn_key
                        OR m.conn_key = '0'
                        OR m.conn_key = ''
                        OR m.conn_key IS NULL)
                THEN
                    0
                ELSE 1
            END AS main_or_connected,
            val.is_fixed,
            CONCAT(m.reg_no_display,' @ ',left((cast(m.diary_no as text)),-4),'/', right((cast(m.diary_no as text)),4)) AS case_no,
        TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') as filing_date,
            concat(m.pet_name,' VS ', m.res_name) as cause_title,
            x.advocate,
            val.is_deleted AS declined_by_admin,
            x.declined_by_aor
        FROM
            icmis.vacation_advance_list val
            inner join icmis.main m on val.diary_no=m.diary_no
        INNER JOIN (
            SELECT DISTINCT
                vala3.diary_no,
                (
                    SELECT
                        is_deleted
                    FROM
                        icmis.vacation_advance_list_advocate
                    WHERE
                        diary_no = vala3.diary_no
                        AND aor_code = $aor_code
                        and vacation_list_year = $current_year
                    ORDER BY
                        vacation_list_year desc,
                        is_deleted DESC
                    LIMIT 1
                ) AS declined_by_aor,
                STRING_AGG(
                    DISTINCT CONCAT(
                        COALESCE(b1.name,''),
                        '<font color=\"blue\" weight=\"bold\">(',
                        COALESCE(adv1.pet_res,''),
                        ')</font><font color=\"red\" weight=\"bold\">',
                        CASE WHEN vala3.is_deleted='t' THEN '(Declined)' ELSE '' END,
                        '</font>'
                    ),
                    '<br/>'
                ) AS advocate
            FROM
                icmis.vacation_advance_list_advocate vala3
            LEFT JOIN
                icmis.advocate adv1 ON vala3.diary_no = adv1.diary_no
            INNER JOIN
                icmis.bar b1 ON adv1.advocate_id = b1.bar_id AND b1.aor_code = vala3.aor_code
            WHERE
                adv1.display = 'Y'
                AND b1.isdead = 'N'
                AND b1.if_aor = 'Y'
                AND vala3.vacation_list_year=$current_year
                AND vala3.diary_no IN (
                    SELECT DISTINCT
                        vala4.diary_no
                    FROM
                        icmis.vacation_advance_list_advocate vala4
                    WHERE
                        vala4.aor_code = $aor_code
                )
            GROUP BY
                vala3.diary_no
        ) x ON val.diary_no = x.diary_no
        inner join icmis.vacation_advance_list_advocate vala on vala.diary_no = val.diary_no
        WHERE
            val.vacation_list_year = $current_year and
            vala.vacation_list_year = $current_year and vala.aor_code =$aor_code  $decline_condition
        GROUP BY
            m.reg_no_display,
            m.diary_no,
            m.diary_no_rec_date,
            m.pet_name,
            m.res_name,
            main_or_connected,
            val.is_fixed,
            x.advocate,
            val.is_deleted,
            x.declined_by_aor
        ORDER BY
            main_or_connected ASC;";
        $query = $this->db->query($query);
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return array();
        }
    }

    public function get_matters_declined_by_counter_list($aor_code) {
        $current_year = date('Y');
        $query = "select
            distinct m.diary_no,
            concat('It is Notified that learned counsel (',b1.name,'(Aor Code :',x.aor_code,')), does not want to take up instant matter ',concat(m.reg_no_display,' @ ',CONCAT(m.reg_no_display,' @ ',left((cast(m.diary_no as text)),-4),'/', right((cast(m.diary_no as text)),4))),' mentioned in  Advance List during the Summer Vacation, $current_year.This notification does not require any written request for deletion.') as msg,
            CONCAT(m.reg_no_display,' @ ',left((cast(m.diary_no as text)),-4),'/', right((cast(m.diary_no as text)),4)) AS case_no,
            concat(m.pet_name,' VS ', m.res_name) as cause_title,
            b1.name as decline_by_Advocate,
            TO_CHAR(x.updated_on, 'DD-MM-YYYY') as updated_on,x.aor_code
            from  icmis.main m inner join
            (
            select * from icmis.vacation_advance_list_advocate vala1
            where diary_no in
            (
            select distinct diary_no from icmis.vacation_advance_list_advocate
            where aor_code=$aor_code
            )
            and is_deleted='t' and vala1.aor_code !=$aor_code
            ) x
            on  m.diary_no=x.diary_no
            left join icmis.advocate adv1 on x.diary_no=adv1.diary_no
            left join icmis.bar b1 on adv1.advocate_id=b1.bar_id and b1.aor_code=x.aor_code
            where adv1.display='Y'  and b1.isdead='N' and b1.if_aor='Y' and vacation_list_year=$current_year
            order by updated_on desc";
        $query = $this->db->query($query);
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return array();
        }
    }

    public function get_vacation_advance_list_advocate($dairyNos, $aor_code) {
        $builder = $this->db->table('icmis.vacation_advance_list_advocate');
        $builder->select("*");
        $builder->whereIn('diary_no', $dairyNos);
        $builder->where('is_deleted', 'f');
        $builder->where('aor_code', $aor_code);
        $builder->orderBy("aor_code", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    public function get_vacation_advance_list_advocate_restore($diary_no, $aor_code) {
        $builder = $this->db->table('icmis.vacation_advance_list_advocate');
        $builder->select("*");
        $builder->where('diary_no', $diary_no);
        $builder->where('is_deleted', 't');
        $builder->where('aor_code', $aor_code);
        $builder->orderBy("aor_code", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    public function insert_vacation_advance_list_advocate_log($tableName, $data) {
        $output = true;        
        if (isset($tableName) && !empty($tableName) && isset($data) && !empty($data)) {
            $builder = $this->db->table($tableName);
            $builder->insertBatch($data);
        }
        return $output;
    }

}