<?php

namespace App\Models\FetchIcmisData;
use CodeIgniter\Model;

class SynchDataModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function upsert_in_master_table($data_array, $tablename, $primarykey) {
        foreach($data_array as $data) {
            $cleaned_data = $this->cleanArray($data);
            $this->updateOnDuplicate($tablename, $cleaned_data, $primarykey);
        }
    }

    function updateOnDuplicate($table, $data, $primarykey) {
        if (empty($table) || empty($data))
            return false;
        $duplicate_data = array();
        foreach($data AS $key => $value) {
            $duplicate_data[] = sprintf("%s=EXCLUDED.%s", $key, $key);
        }
        $sql = sprintf("%s ON CONFLICT ($primarykey) DO UPDATE SET %s", $this->db->insert_string("icmis.".$table, $data), implode(',', $duplicate_data));
        $result=$this->db->query($sql);
        $db_error = $this->db->error();
        print_r($db_error['message']);
        if (!empty($db_error['message'])) {
            return false;
        }
        return $result;
    }

    function cleanArray($data) {
        $exclude_column=array('cast');
        $cleaned_data=array();
        foreach($data AS $key => $value) {
            if(!in_array($key,$exclude_column)) {
                if($value=='0000-00-00' || $value=='0000-00-00 00:00:00') {
                    $cleaned_data[$key]=NULL;
                } else {
                    if($this->ifDate($value)) {
                        if(!$this->ifValidDate($value)) {
                            $cleaned_data[$key]=NULL;
                        }
                    } else {
                        $cleaned_data[$key]=$value;
                    }
                }
            }
        }
        return $cleaned_data;
    }

    function ifDate($date) {
        if (preg_match("/^[0-9]{4}-(0[0-9]|1[0-9])-(0[0-9]|[0-9][0-9]|3[0-9])$/",$date)) {
            return true;
        } else {
            return false;
        }
    }

    function ifValidDate($date) {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
            return true;
        } else {
            return false;
        }
    }

}