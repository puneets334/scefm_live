<?php

namespace App\Models\Vacation;
use CodeIgniter\Model;
use DateTime;

class VacationAdvanceModel extends Model {

    function __construct() {
        parent::__construct();
    }
    public function is_vacation_advance_list_duration()
    {
        //$output='N';
        $output = false;
        $current_year = date('Y');
        $builder = $this->db->table('icmis.vacation_advance_list_duration');
        $builder->SELECT("*");
        $builder->WHERE('vacation_list_year', $current_year);
        $builder->WHERE('is_active', 't');
        $builder->WHERE('id', 1);
        $query = $builder->get();
        $result= $query->getRowArray();
        if ($result) {
            $current_date = date('Y-m-d H:i:s');
            $activated_from_date = $result['activated_from_date'];
            $activated_to_date = $result['activated_to_date'];

            $current_timestamp = strtotime($current_date);
            $from_timestamp = strtotime($activated_from_date);
            $to_timestamp = strtotime($activated_to_date);

            if ($current_timestamp >= $from_timestamp && $current_timestamp <= $to_timestamp) {
                $output = true;
                //echo 'Website is open ';exit();
            } else {
                $output = false;
                //echo 'Website not open date and time between'; exit();
            }
        }
        return $output;
    }
   public function get_vacation_advance_list($aor_code,$mainhead='F',$decline_condition=null) {
       $decline_condition=(!empty($decline_condition) && $decline_condition =='D') ? " and declined_by_aor='t' " :'';
        $current_year=date('Y');
       /*$query = "SELECT DISTINCT
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
        --vala3.aor_code,
        (
            SELECT
                is_deleted
            FROM
                icmis.vacation_advance_list_advocate
            WHERE
                diary_no = vala3.diary_no
                AND aor_code = $aor_code
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
    LEFT JOIN
        icmis.bar b1 ON adv1.advocate_id = b1.bar_id AND b1.aor_code = vala3.aor_code
    WHERE
        adv1.display = 'Y'
        AND b1.isdead = 'N'
        AND b1.if_aor = 'Y'
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
             --,vala3.aor_code
) x ON val.diary_no = x.diary_no
WHERE
    vacation_list_year = $current_year  $decline_condition
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
    --(CASE WHEN val.is_fixed='Y' THEN 1 ELSE 99 END),
    --CASE WHEN val.conn_key = 0 OR val.conn_key IS null
    --OR val.conn_key = ''
    --OR 
    --val.conn_key = val.diary_no
    
    --THEN val.diary_no ELSE val.conn_key END,
    main_or_connected ASC;";*/

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
        --vala3.aor_code,
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
             --,vala3.aor_code
) x ON val.diary_no = x.diary_no
inner join icmis.vacation_advance_list_advocate vala on vala.diary_no = val.diary_no
WHERE
    val.vacation_list_year = $current_year and
    vala.vacation_list_year = $current_year and vala.aor_code =$aor_code  $decline_condition and
    val.mainhead='".$mainhead."'
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
    --(CASE WHEN val.is_fixed='Y' THEN 1 ELSE 99 END),
    --CASE WHEN val.conn_key = 0 OR val.conn_key IS null
    --OR val.conn_key = ''
    --OR 
    --val.conn_key = val.diary_no
  
    --THEN val.diary_no ELSE val.conn_key END,
    main_or_connected ASC;";
        $query = $this->db->query($query);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return array();
        }


    }

   public function get_matters_declined_by_counter_list($aor_code,$mainhead) {
        $current_year=date('Y');
        $query = "select
        distinct m.diary_no,
        concat('It is Notified that learned counsel (',b1.name,'(Aor Code :',x.aor_code,')), does not want to take up instant matter ',concat(m.reg_no_display,' @ ',CONCAT(m.reg_no_display,' @ ',left((cast(m.diary_no as text)),-4),'/', right((cast(m.diary_no as text)),4))),' mentioned in  Advance List during the Summer Vacation, $current_year.This notification does not require any written request for deletion.') as msg,
        CONCAT(m.reg_no_display,' @ ',left((cast(m.diary_no as text)),-4),'/', right((cast(m.diary_no as text)),4)) AS case_no,
        concat(m.pet_name,' VS ', m.res_name) as cause_title,
        b1.name as decline_by_Advocate,
        TO_CHAR(x.updated_on, 'DD-MM-YYYY HH24:MI:SS') as updated_on,x.aor_code
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
        where adv1.display='Y'  and b1.isdead='N' and b1.if_aor='Y' and vacation_list_year=$current_year and x.mainhead='".$mainhead."'
        order by updated_on desc";

        $query = $this->db->query($query);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return array();
        }


    }
    public function get_vacation_advance_list_advocate($dairyNos, $aor_code, $mainhead) {
        $builder = $this->db->table('icmis.vacation_advance_list_advocate');
        $builder->select("*");
        $builder->whereIn('diary_no', $dairyNos);
        $builder->where('is_deleted', 'f');
        $builder->where('aor_code', $aor_code);
        $builder->WHERE('mainhead', $mainhead);
        $builder->orderBy("aor_code", "asc");
        $query = $builder->get();
        return $query->getResult();
    }

    public function get_vacation_advance_list_advocate_restore($diary_no, $aor_code, $mainhead) {
        $builder = $this->db->table('icmis.vacation_advance_list_advocate');
        $builder->select("*");
        $builder->where('diary_no', $diary_no);
        $builder->where('is_deleted', 't');
        $builder->where('aor_code', $aor_code);
        $builder->WHERE('mainhead', $mainhead);
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

    public function get_sum_report_details($table_supplementary = null)
    {
        $current_year = date('Y');
        $sql = "select 
            mainhead,--count(1) AS total,
            --sum(case when (d.diary_no = d.conn_key OR d.conn_key=0 OR d.conn_key IS NULL) then 1 else 0 end) main,
            --sum(case when NOT (d.diary_no = d.conn_key OR d.conn_key=0 OR d.conn_key IS NULL) then 1 else 0 end) conn,
            
            sum(case  when declined_percentage > 50   and (diary_no = conn_key OR conn_key = 0 OR conn_key IS NULL)  then 1 else 0 end) AS main_more_than_50p,
            sum(case   when declined_percentage > 50  and NOT (diary_no = conn_key OR conn_key = 0 OR conn_key IS NULL)  then 1 else 0 end) AS conn_more_than_50p,

            sum(case  when declined_percentage <= 50 and declined_percentage != 0  and (diary_no = conn_key OR conn_key = 0 OR conn_key IS NULL) then 1 else 0 end) AS main_upto_50p,
            sum(case  when declined_percentage <= 50 and declined_percentage != 0  and NOT (diary_no = conn_key OR conn_key = 0 OR conn_key IS NULL) then 1 else 0 end) AS conn_upto_50p,

            sum(case   when declined_percentage = 0 and (diary_no = conn_key OR conn_key = 0 OR conn_key IS NULL) then 1 else 0 end) AS main_no_consent,
            sum(case when declined_percentage = 0  and NOT (diary_no = conn_key OR conn_key = 0 OR conn_key IS NULL)  then 1 else 0 end) AS conn_no_consent
        from (
            select 
                a.diary_no,
                a.conn_key,
                a.mainhead,
                COALESCE(declined.adv_declined, 0) AS adv_declined,
                COUNT(*) AS total_adv,
                ROUND(COALESCE(declined.adv_declined, 0) * 100.0 / NULLIF(COUNT(*), 0), 2) AS declined_percentage
            from icmis.vacation_advance_list_advocate$table_supplementary a
            left join (
                select 
                    diary_no, 
                    COUNT(*) AS adv_declined
                from icmis.vacation_advance_list_advocate$table_supplementary
                where vacation_list_year = $current_year and is_deleted = 't'
                group BY diary_no
            ) declined ON a.diary_no = declined.diary_no
            where a.vacation_list_year = $current_year
            group BY a.diary_no, a.conn_key, a.mainhead, declined.adv_declined
        ) d
        group BY mainhead
        ORDER BY mainhead desc;
        ";
        $query = $this->db->query($sql);
        $mainData = $query->getResultArray();
        if (!empty($mainData)) {
            $data = array();
            foreach ($mainData as $row) {
                $data[$row['mainhead']] = $row;
            }
        }

        $sql2 = "SELECT COUNT(distinct diary_no) total_matters,
            sum(CASE WHEN (diary_no = conn_key OR conn_key = 0  OR conn_key IS NULL) THEN 1 ELSE 0 END) total_main_matters,
            sum(CASE WHEN NOT (diary_no = conn_key OR conn_key = 0 OR conn_key IS NULL) THEN 1 ELSE 0 END) total_conn_matters,

            sum(CASE WHEN (mainhead = 'M') THEN 1 ELSE 0 END) total_misc_matters,
            sum(CASE WHEN (mainhead = 'M') and (diary_no = conn_key OR conn_key = 0  OR conn_key IS NULL) THEN 1 ELSE 0 END) total_misc_main_matters,
            sum(CASE WHEN (mainhead = 'M') and NOT (diary_no = conn_key OR conn_key = 0 OR conn_key IS NULL) THEN 1 ELSE 0 END) total_misc_conn_matters,

            sum(CASE WHEN (mainhead = 'F') THEN 1 ELSE 0 END) total_regular_matters,
            sum(CASE WHEN (mainhead = 'F') and (diary_no = conn_key OR conn_key = 0  OR conn_key IS NULL) THEN 1 ELSE 0 END) total_regular_main_matters,
            sum(CASE WHEN (mainhead = 'F') and NOT (diary_no = conn_key OR conn_key = 0 OR conn_key IS NULL) THEN 1 ELSE 0 END) total_regular_conn_matters
            FROM icmis.vacation_advance_list$table_supplementary WHERE vacation_list_year = $current_year;
            ";
        $query2 = $this->db->query($sql2);
        $main2_result = $query2->getResultArray();
        $main2 = array();
        if (!empty($main2_result)) {
            $main2 = $main2_result[0];
        }
        $builder = $this->db->table("icmis.vacation_advance_list$table_supplementary a");
        $builder->select(
            " a.mainhead,
                            SUM(CASE WHEN (a.diary_no = a.conn_key OR a.conn_key = '0' OR a.conn_key IS NULL) THEN 1 ELSE 0 END) AS main,
                            SUM(CASE WHEN NOT (a.diary_no = a.conn_key OR a.conn_key = '0' OR a.conn_key IS NULL) THEN 1 ELSE 0  END) AS conn,
                            COUNT(1) AS total"
        );
        $builder->join("icmis.vacation_advance_list_advocate$table_supplementary b", "a.diary_no = b.diary_no AND b.vacation_list_year = $current_year", "left");
        $builder->where("a.vacation_list_year", $current_year);
        $builder->where("b.diary_no IS NULL", null, false);
        $builder->groupBy("a.mainhead");
        $builder->orderBy("a.mainhead", 'desc');
        $query_main = $builder->get();
        $mainData1 = $query_main->getResultArray();
        if (!empty($mainData1)) {
            $data1 = array();
            foreach ($mainData1 as $rows) {
                $data1[$rows['mainhead']] = $rows;
            }
        }
        $resultAll = array("data$table_supplementary" => $data, "main2$table_supplementary" => $main2, "data1$table_supplementary" => $data1);
        return $resultAll;
    }

    public function get_notice()
    {
        $current_year = date('Y');
        $builder = $this->db->table('icmis.vacation_advance_list_duration');
        $builder->SELECT("*");
        $builder->WHERE('vacation_list_year', $current_year);
        $builder->WHERE('is_active', 't');
        $query = $builder->get();
        return $query->getResultArray();
    }
   
}

?>
