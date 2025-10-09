<?php

namespace App\Models\OnlineCopying;

use CodeIgniter\Model;
use Config\Database;

class CommonModel extends Model
{

    protected $db2;

    function __construct()
    {
        parent::__construct();
        $this->db2 = Database::connect('sci_cmis_final');
    }

    function getCategory(){
        // $builder = $this->db2->table('master.copy_category');
        // $builder->select('id, code');
        // $builder->orderBy('id');
        // $query = $builder->get();
        // if ($query === false) {
        //     $error = $this->db2->error();
        //     log_message('error', 'Query Error: ' . print_r($error, true));
        //     return false;
        // }
        // $result = $query->getResult();
        // return $result;

        $builder = $this->db2->table('copy_category');
        $builder->select('id, code');
        $builder->orderBy('id');
        $query = $builder->get();
        if ($query === false) {
            $error = $this->db2->error();
            log_message('error', 'Query Error: ' . print_r($error, true));
            return false;
        }
        $result = $query->getResult();
        return $result;
    }

    function copyFaq(){
        // $builder = $this->db2->table('master.icmis_faqs');
        // $builder->where('main_menu', 'ecopying');
        // $builder->orderBy('created_on');
        // $query = $builder->get();
        // if ($query === false) {
        //     $error = $this->db2->error();
        //     log_message('error', 'Query Error: ' . print_r($error, true)); 
        //     return false;
        // }
        // $result = $query->getResult();
        // return $result;

        $builder = $this->db2->table('icmis_faqs');
        $builder->where('main_menu', 'ecopying');
        $builder->orderBy('created_on');
        $query = $builder->get();
        if ($query === false) {
            $error = $this->db2->error();
            log_message('error', 'Query Error: ' . print_r($error, true)); 
            return false;
        }
        $result = $query->getResult();
        return $result;
    }

    function getCaseType(){
        // $builder = $this->db2->table('master.casetype');
        // $results = $builder->select('casecode, skey, casename, short_description')
        //     ->where('display', 'Y')
        //     ->where('casecode !=', 9999)
        //     ->whereNotIn('casecode', [15, 16])
        //     ->orderBy('casecode')
        //     ->orderBy('short_description')
        //     ->get()
        //     ->getResult();

        // return $results;

        $builder = $this->db2->table('casetype');
        $results = $builder->select('casecode, skey, casename, short_description')
            ->where('display', 'Y')
            ->where('casecode !=', 9999)
            ->whereNotIn('casecode', [15, 16])
            ->orderBy('casecode')
            ->orderBy('short_description')
            ->get()
            ->getResult();

        return $results;
    }

    function getCatogoryForApplication($id){
        // $builder = $this->db2->table('master.copy_category');
        // $builder->select('id, urgent_fee, per_certification_fee, per_page, code, description');
        // $builder->where('id', $id);
        // // $builder->where('to_date', '0000-00-00');
        // $query = $builder->get();
        // return $query->getRow();

        $builder = $this->db2->table('copy_category');
        $builder->select('id, urgent_fee, per_certification_fee, per_page, code, description');
        $builder->where('id', $id);
        // $builder->where('to_date', '0000-00-00');
        $query = $builder->get();
        return $query->getRow();
    }

}