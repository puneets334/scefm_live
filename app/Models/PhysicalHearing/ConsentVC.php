<?php

namespace App\Models\PhysicalHearing;

use CodeIgniter\Model;

class ConsentVC extends Model
{
    function available_court_list($listing_days)
    {
		$listing_days=implode(',',$listing_days);
        
        // $sql="select  distinct case when courtno>=60 then courtno-60  when courtno>=30 then courtno-30 else courtno end as court_no
        //        from heardt h 
        //        left join cl_printed p on p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
        //         inner join roster r on h.roster_id=r.id and r.display='Y' 
        //         inner join roster_bench rb on r.bench_id=rb.id 
        //         inner join master_bench mb on rb.bench_id=mb.id inner join advocate a on h.diary_no=a.diary_no and a.display='Y'
        //        where p.id is not null and h.next_dt in ($listing_days) order by 1";

        $sql="SELECT DISTINCT 
                CASE 
                    WHEN courtno >= 60 THEN courtno - 60
                    WHEN courtno >= 30 THEN courtno - 30
                    ELSE courtno
                END AS court_no
            FROM heardt h
            LEFT JOIN cl_printed p 
                ON p.next_dt = h.next_dt 
                AND p.m_f = h.mainhead 
                AND p.part = h.clno 
                AND p.roster_id = h.roster_id 
                AND p.display = 'Y'
            INNER JOIN roster r 
                ON h.roster_id = r.id 
                AND r.display = 'Y'
            INNER JOIN roster_bench rb 
                ON r.bench_id = rb.id
            INNER JOIN master_bench mb 
                ON rb.bench_id = mb.id
            INNER JOIN advocate a 
                ON h.diary_no = a.diary_no 
                AND a.display = 'Y'
            WHERE p.id IS NOT NULL 
            AND h.next_dt IN ($listing_days)
            ORDER BY 1";

        $query = $this->db->query($sql);
        //echo $this->db->last_query();exit(0);

        return $query->getResultArray();
    }

    function freezed_court_list($listing_days){
        $listing_days=implode(',',$listing_days);
     
        $sql ="SELECT DISTINCT 
                        CASE 
                            WHEN courtno >= 60 THEN courtno - 60 
                            WHEN courtno >= 30 THEN courtno - 30 
                            ELSE courtno 
                        END AS court_no
                    FROM heardt h 
                    LEFT JOIN cl_printed p 
                        ON p.next_dt = h.next_dt 
                        AND p.m_f = h.mainhead 
                        AND p.part = h.clno 
                        AND p.roster_id = h.roster_id 
                        AND p.display = 'Y'
                    INNER JOIN roster r 
                        ON h.roster_id = r.id 
                        AND r.display = 'Y' 
                    INNER JOIN roster_bench rb 
                        ON r.bench_id = rb.id 
                    INNER JOIN master_bench mb 
                        ON rb.bench_id = mb.id 
                    INNER JOIN advocate a 
                        ON h.diary_no = a.diary_no 
                        AND a.display = 'Y'
                    WHERE p.id IS NOT NULL 
                    AND h.next_dt IN ($listing_days) 
                    ORDER BY 1
                    ";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();exit(0);

        return $query->getResultArray();
    }

    function getListedAdvocateMatters($advocateId,$listingDate,$court=null,$requestFor=null){
        $listing_date_condition="";
        if(empty($requestFor))
           $listing_date_condition=" and h.next_dt in (". implode(',',$listingDate).")";


        $where_condition=(!empty($court))?'courtno in (?,?,?)':'1=1';
        $sql="SELECT 
                    next_dt,
                    CASE 
                        WHEN courtno >= 60 THEN courtno - 60 
                        WHEN courtno >= 30 THEN courtno - 30 
                        ELSE courtno 
                    END AS court_no_display,
                    COUNT(1) AS total_cases,
                    courtno
                FROM (
                    SELECT 
                        h.*, 
                        mb.board_type_mb,
                        r.courtno
                    FROM (
                        SELECT 
                            h.diary_no, 
                            m.conn_key,
                            CASE
                                WHEN m.diary_no = m.conn_key 
                                    OR m.conn_key = '' 
                                    OR m.conn_key IS NULL 
                                    OR m.conn_key = '0' THEN m.diary_no
                                ELSE m.conn_key
                            END AS main_case_diary_no,
                            CASE 
                                WHEN m.diary_no = m.conn_key 
                                    OR m.conn_key = '' 
                                    OR m.conn_key IS NULL 
                                    OR m.conn_key = '0' THEN 'M' 
                                ELSE 'C' 
                            END AS main_connected,
                            h.next_dt, 
                            h.mainhead, 
                            h.brd_slno, 
                            h.clno, 
                            h.roster_id, 
                            h.judges, 
                            h.main_supp_flag, 
                            m.reg_no_display,
                            h.board_type,
                            m.active_casetype_id,
                            CONCAT(
                                COALESCE(m.reg_no_display, ''), 
                                ' @ ', 
                                CONCAT(
                                    LEFT(m.diary_no, LENGTH(m.diary_no) - 4), 
                                    '/', 
                                    SUBSTRING(m.diary_no FROM -4)
                                )
                            ) AS case_no,
                            CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title
                        FROM 
                            main m 
                        INNER JOIN 
                            heardt h ON m.diary_no = h.diary_no 
                        WHERE 
                            h.next_dt >= CURRENT_DATE 
                            AND h.main_supp_flag IN (1, 2) 
                            $listing_date_condition

                        UNION 

                        SELECT 
                            h.diary_no, 
                            m.conn_key,
                            CASE
                                WHEN m.diary_no = m.conn_key 
                                    OR m.conn_key = '' 
                                    OR m.conn_key IS NULL 
                                    OR m.conn_key = '0' THEN m.diary_no
                                ELSE m.conn_key
                            END AS main_case_diary_no,
                            CASE 
                                WHEN m.diary_no = m.conn_key 
                                    OR m.conn_key = '' 
                                    OR m.conn_key IS NULL 
                                    OR m.conn_key = '0' THEN 'M' 
                                ELSE 'C' 
                            END AS main_connected,
                            h.next_dt, 
                            h.mainhead, 
                            h.brd_slno, 
                            h.clno, 
                            h.roster_id, 
                            h.judges, 
                            h.main_supp_flag, 
                            m.reg_no_display,
                            h.board_type,
                            m.active_casetype_id,
                            CONCAT(
                                COALESCE(m.reg_no_display, ''), 
                                ' @ ', 
                                CONCAT(
                                    LEFT(m.diary_no, LENGTH(m.diary_no) - 4), 
                                    '/', 
                                    SUBSTRING(m.diary_no FROM -4)
                                )
                            ) AS case_no,
                            CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title
                        FROM 
                            main m 
                        INNER JOIN 
                            last_heardt h ON m.diary_no = h.diary_no 
                        WHERE 
                            h.next_dt >= CURRENT_DATE 
                            AND h.main_supp_flag IN (1, 2) 
                            AND (h.bench_flag = '' OR h.bench_flag IS NULL)
                    ) h
                    LEFT JOIN 
                        cl_printed p ON p.next_dt = h.next_dt 
                        AND p.m_f = h.mainhead 
                        AND p.part = h.clno 
                        AND p.roster_id = h.roster_id 
                        AND p.display = 'Y'
                    INNER JOIN 
                        roster r ON h.roster_id = r.id 
                        AND r.display = 'Y'
                    INNER JOIN 
                        roster_bench rb ON r.bench_id = rb.id
                    INNER JOIN 
                        master_bench mb ON rb.bench_id = mb.id
                    INNER JOIN 
                        advocate a ON h.diary_no = a.diary_no 
                        AND a.display = 'Y'
                    WHERE 
                        p.id IS NOT NULL 
                        AND a.advocate_id = $1
                        AND $where_condition
                    GROUP BY 
                        diary_no, 
                        roster_id
                ) n1
                GROUP BY 
                    next_dt, 
                    court_no_display
                ORDER BY 
                    next_dt, 
                    court_no_display
                        ";  
        $condition=(!empty($court))?array($advocateId, $court,($court+30),($court+60)):array($advocateId);

        $query = $this->db->query($sql,$condition);
       //  echo $this->db->last_query();exit();

        return $query->getResultArray();

    }
    function getFutureListedMatters($advocateId,$listingDate,$court=null){
        $listingDate=implode(',',$listingDate);
        $where_condition=(!empty($court))?'courtno in (?,?,?)':'1=1';

        $sql="SELECT DISTINCT
                    main_case_diary_no AS diary_no,
                    STRING_AGG(
                        case_no || ' (' || main_connected || ')', 
                        ', '
                    ) AS case_no,
                    cause_title,
                    n1.courtno AS court_no,
                    n1.brd_slno AS item_no,
                    COUNT(1) AS case_count,
                    n1.next_dt,
                    n1.roster_id,
                    n1.main_connected,
                    n1.reg_no_display AS main_case_reg_no,
                    STRING_AGG(n1.diary_no, ', ') AS consent_diaries,
                    CASE
                        WHEN courtno >= 60 THEN courtno - 60
                        WHEN courtno >= 30 THEN courtno - 30
                        ELSE courtno
                    END AS court_no_display
                FROM (
                    SELECT
                        h.*,
                        mb.board_type_mb,
                        r.courtno
                    FROM (
                        SELECT
                            h.diary_no,
                            m.conn_key,
                            CASE
                                WHEN m.diary_no = m.conn_key
                                    OR m.conn_key = ''
                                    OR m.conn_key IS NULL
                                    OR m.conn_key = '0' THEN m.diary_no
                                ELSE m.conn_key
                            END AS main_case_diary_no,
                            CASE
                                WHEN m.diary_no = m.conn_key
                                    OR m.conn_key = ''
                                    OR m.conn_key IS NULL
                                    OR m.conn_key = '0' THEN 'M'
                                ELSE 'C'
                            END AS main_connected,
                            h.next_dt,
                            h.mainhead,
                            h.brd_slno,
                            h.clno,
                            h.roster_id,
                            h.judges,
                            h.main_supp_flag,
                            m.reg_no_display,
                            h.board_type,
                            m.active_casetype_id,
                            COALESCE(m.reg_no_display, '') || ' @ ' || 
                            LEFT(m.diary_no, LENGTH(m.diary_no) - 4) || '/' || 
                            SUBSTRING(m.diary_no FROM LENGTH(m.diary_no) - 3 FOR 4) AS case_no,
                            m.pet_name || ' Vs. ' || m.res_name AS cause_title
                        FROM
                            main m
                        INNER JOIN
                            heardt h ON m.diary_no = h.diary_no
                        WHERE
                            h.next_dt >= CURRENT_DATE
                            AND h.next_dt IN ($listingDate)
                            AND h.main_supp_flag IN (1, 2)
                        UNION
                        SELECT
                            h.diary_no,
                            m.conn_key,
                            CASE
                                WHEN m.diary_no = m.conn_key
                                    OR m.conn_key = ''
                                    OR m.conn_key IS NULL
                                    OR m.conn_key = '0' THEN m.diary_no
                                ELSE m.conn_key
                            END AS main_case_diary_no,
                            CASE
                                WHEN m.diary_no = m.conn_key
                                    OR m.conn_key = ''
                                    OR m.conn_key IS NULL
                                    OR m.conn_key = '0' THEN 'M'
                                ELSE 'C'
                            END AS main_connected,
                            h.next_dt,
                            h.mainhead,
                            h.brd_slno,
                            h.clno,
                            h.roster_id,
                            h.judges,
                            h.main_supp_flag,
                            m.reg_no_display,
                            h.board_type,
                            m.active_casetype_id,
                            COALESCE(m.reg_no_display, '') || ' @ ' || 
                            LEFT(m.diary_no, LENGTH(m.diary_no) - 4) || '/' || 
                            SUBSTRING(m.diary_no FROM LENGTH(m.diary_no) - 3 FOR 4) AS case_no,
                            m.pet_name || ' Vs. ' || m.res_name AS cause_title
                        FROM
                            main m
                        INNER JOIN
                            last_heardt h ON m.diary_no = h.diary_no
                        WHERE
                            h.next_dt >= CURRENT_DATE
                            AND h.next_dt IN ($listingDate)
                            AND h.main_supp_flag IN (1, 2)
                            AND (h.bench_flag = '' OR h.bench_flag IS NULL)
                    ) h
                    LEFT JOIN
                        cl_printed p ON p.next_dt = h.next_dt
                        AND p.m_f = h.mainhead
                        AND p.part = h.clno
                        AND p.roster_id = h.roster_id
                        AND p.display = 'Y'
                    INNER JOIN
                        roster r ON h.roster_id = r.id
                        AND r.display = 'Y'
                    INNER JOIN
                        roster_bench rb ON r.bench_id = rb.id
                    INNER JOIN
                        master_bench mb ON rb.bench_id = mb.id
                    INNER JOIN
                        advocate a ON h.diary_no = a.diary_no
                        AND a.display = 'Y'
                    WHERE
                        p.id IS NOT NULL
                        AND a.advocate_id = ?
                        AND $where_condition
                    GROUP BY
                        diary_no,
                        roster_id
                ) n1
                GROUP BY
                    main_case_diary_no;
                    ";
        $condition=(!empty($court))?array($advocateId, $court,($court+30),($court+60)):array($advocateId);
        $query = $this->db->query($sql,$condition);
         //echo $this->db->last_query();
        return $query->getResultArray();

    }


}
