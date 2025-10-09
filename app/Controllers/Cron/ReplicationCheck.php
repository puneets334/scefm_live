<?php

namespace App\Controllers\Cron;

use App\Controllers\BaseController;
use Config\Database;

class ReplicationCheck extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        // echo "Hello";
        // $servers=array('10.249.44.169','10.249.44.170');
        $servers=array('10.25.80.170');
	    // $servers = REPLICATION_SERVER_IPS;
        foreach ($servers as $server_ip) {
            echo $server_ip;
            $this->checkReplicationStatus($server_ip);
        }
    }

    private function checkReplicationStatus($serverIp = REPLICATION_SERVER_IP_DEFAULT) {
        $db = Database::connect(REPLICATION_CHECK_CONFIG);
        $maxDelaySeconds = 900;
        echo "Started ".$serverIp;
        $query = $db->query("SELECT EXTRACT(EPOCH FROM now() - pg_last_xact_replay_timestamp()) AS replication_delay");
        $result = $query->getRow();
        var_dump($query);
        $replicationDelay = $result->replication_delay;
        echo "Fetched data from:  ".$serverIp." and delay is:".$replicationDelay;
        $db->close();
        if ($replicationDelay > $maxDelaySeconds) {
            $emailSubject = "PostgreSQL Replication Delay Alert on Server ".$serverIp;
            $emailMessage = "Replication delay on Server ".$serverIp." exceeds threshold. Current delay: $replicationDelay seconds.";
        } else {
            $emailSubject = "PostgreSQL Replication Status";
            $emailMessage = "Replication delay on Server ".$serverIp." is within threshold. Current delay: $replicationDelay seconds.";
        }
        $to_email='sca.aktripathi@sci.nic.in';
        send_mail_msg($to_email, $emailSubject, $emailMessage);
    }

}