<?php

namespace App\Models\OnlineCopying;

use CodeIgniter\Model;
use Config\Database;

class AddressModel extends Model
{
    protected $db2;
    protected $db3;
    protected $table = 'user_address';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'mobile',
        'email',
        'first_name',
        'second_name',
        'address_type',
        'address',
        'city',
        'district',
        'state',
        'pincode',
        'country',
        'entry_time_ip',
        'deleted_on',
    ];

    function __construct()
    {
        parent::__construct();
        $this->db2 = Database::connect('sci_cmis_final');
        $this->db3 = Database::connect('e_services'); 

    }

    public function checkUserAddress($mobile, $email)
    {
        $builder = $this->db3->table('user_address'); 
        $builder->where('mobile', $mobile)
            ->where('email', $email)
            ->where('is_active', 'Y');      
        $query = $builder->get();
        if ($query === false) {
            $error =  $this->db3->error();
            $result = [];
        } else {
            $result = $query->getResultArray();
        }
        return $result;
    }

    public function verifyAadhar($mobile, $email)
    {        
        $db3 = \Config\Database::connect('e_services');
        $builder = $db3->table('uidai_offline_kyc');
        $builder->select([
            'adhar_name AS user_name',
            "CONCAT(careof, ', ', house, ', ', landmark, ', ', loc, ', ', po, ', ', street, ', ', vtc) AS address",
            'subdist AS city',
            'district',
            'state',
            'pc AS pincode'
        ]);
        $builder->where("mobile", $mobile);
        $builder->where("email", $email);
        $builder->limit(1);
        $query = $builder->get();
        if ($query === false) {
            return false;
        } else {
            return $query->getResult();
        }
    }

    public function getListedCases($mobile, $email)
    {
        
        // $builder = $this->db2->table('master.bar');
        // // $builder->select('name AS user_name, caddress AS address, ccity AS city, ccity AS district, IFNULL(state, "") AS state, IFNULL(pincode, "") AS pincode');
        // $builder->select([
        //     '"name" AS "user_name"',
        //     '"caddress" AS "address"',
        //     '"ccity" AS "city"',
        //     '"ccity" AS "district"', // Duplicate city as district
        //     "COALESCE('state', '') AS state",
        //     "COALESCE('pincode', '') AS pincode",
        // ]);
        // $builder->where('mobile', $mobile);
        // $builder->where('email', $email);
        // $builder->limit(1);
        // $query = $builder->get();
        // // echo $this->db2->getLastQuery()->getOriginalQuery(); die();
        // if ($query === false) {
        //     return false;
        // } else {
        //     return $query->getResult();
        // }

        $builder = $this->db2->table('bar');
        $builder->select([
            '"name" AS "user_name"',
            '"caddress" AS "address"',
            '"ccity" AS "city"',
            '"ccity" AS "district"',
            "COALESCE('state', '') AS state",
            "COALESCE('pincode', '') AS pincode",
        ]);
        $builder->where('mobile', $mobile);
        $builder->where('email', $email);
        $builder->limit(1);
        $query = $builder->get();
        if ($query === false) {
            return false;
        } else {
            return $query->getResult();
        }
    }

    public function getPincode($pincode)
    {
        // $builder = $this->db2->table('master.post_distance_master');           
        // $builder->select('taluk_name, district_name, state');
        // $builder->where('pincode', $pincode);
        // // $sql = $builder->getCompiledSelect();
        // // echo "<pre>$sql</pre>";
        // $query = $builder->get();
        // if ($query === false) {           
        //     return false;
        // }else {
        //     $result = $query->getRow();           
        // }
        // return $result;

        $builder = $this->db2->table('post_distance_master');           
        $builder->select('taluk_name, district_name, state');
        $builder->where('pincode', $pincode);
        $query = $builder->get();
        if ($query === false) {           
            return false;
        } else {
            $result = $query->getRow();           
        }
        return $result;        
    } 

    public function removeApplicantAddress($id, $deletedIP)
    {
        $builder = $this->db3->table('user_address'); 
        $data = [
            'is_active' => 'N',
            'deleted_on' => date('Y-m-d H:i:s'),
            'deleted_ip' => $deletedIP
        ];
        $builder->where('id', $id);
        $builder->set($data);
        return $builder->update();
    }

    public function getActiveAddresses($email, $mobile)
    {
        $builder = $this->db3->table('user_address');         
        $builder->where('email', $email);
        $builder->where('mobile', $mobile);
        $builder->where('is_active', 'Y');
        return $builder->get()->getResultArray();
    }

}