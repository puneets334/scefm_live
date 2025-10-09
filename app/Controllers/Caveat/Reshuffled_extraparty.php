<?php
namespace App\Controllers;
require_once APPPATH .'controllers/Auth_Controller.php';
class Reshuffled_extraparty extends Auth_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('caveat/Reshuffled_party_model');
        $this->session->set_userdata('caveat_msg',false);
    }

    function index() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_ADMIN, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        $shfulling = explode('##', url_decryption($_POST['shuffled_party'])); // 0 - party-id, 1 - party-num, 2 - party_type, 3 - row-id
        $shfulling_on = explode('##', url_decryption($_POST['shuffled_on'])); // 0 - party-id, 1 - party-num, 2 - party_type, 3 - row-id
        if (count($shfulling) != 4) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade in'>Invalid input for to be 'Shuffled Party'. </div>");
            redirect('caveat/extra_party');
            exit(0);
        }
        if (count($shfulling_on) != 4) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade in'>Invalid input for to be 'Shuffle with Party'. </div>");
            redirect('caveat/extra_party');
            exit(0);
        }
        $shuffling_position_type = url_decryption($_POST['position']);
        if ($shuffling_position_type == '') {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade in'>Invalid input for 'Shuffling Position'. </div>");
            redirect('caveat/extra_party');
            exit(0);
        }
        $shuffling_Id = $shfulling[3];
        $shuffling_on_Id = $shfulling_on[3];
        $shuffling_party_id = $shfulling[0];
        $shuffling_party_no = $shfulling[1];
        $shuffle_on_party_id = $shfulling_on[0];
        $shuffle_on_party_no = $shfulling_on[1];
        $shuffle_party_type = $shfulling[2];
        $shuffle_on_party_type = $shfulling_on[2];
        if ($shuffle_party_type != $shuffle_on_party_type){
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade in'>Shuffling parties must be on same side. </div>");
            redirect('caveat/extra_party');
            exit(0);
        }
        $extra_parties_without_lrs = $this->Reshuffled_party_model->get_extra_parties_without_lrs($_SESSION['efiling_details']['registration_id'], $shuffle_party_type);
        $extra_parties_with_lrs = $this->Reshuffled_party_model->get_extra_parties_with_lrs($_SESSION['efiling_details']['registration_id'], $shuffle_party_type);
        if ($shuffling_party_id > $shuffle_on_party_id) {
            //shifting up
            //$shuffle_start = $shuffle_on_party_no;
            if ($shuffling_position_type == 1) {
                // JUST BEFORE POSITION
                $shuffle_start = $shuffle_on_party_id;
                $shuffle_end = $shuffling_party_id - 1;
                $shuffling_party_new_id = $shuffle_on_party_id;
            } elseif ($shuffling_position_type == 2) {
                // JUST AFTER POSITION
                $shuffle_start = $shuffle_on_party_id + 1;
                $shuffle_end = $shuffling_party_id - 1;
                $shuffling_party_new_id = $shuffle_on_party_id + 1;
            }

            $k = 0;
            $l = 0;

            for ($i = $shuffle_start - 2; $i < $shuffle_end - 1; $i++) {

                $update_party_ids[$k] = array(
                    'id' => $extra_parties_without_lrs[$i]->id,
                    'party_id' => strval($extra_parties_without_lrs[$i]->party_id + 1)
                );

                $parentid_key_array = array_keys(array_column($extra_parties_with_lrs, 'parentid'), $extra_parties_without_lrs[$i]->party_no);

                foreach ($parentid_key_array as $parentid_key) {

                    $partyid_array = explode('.', $extra_parties_with_lrs[$parentid_key]['party_id']);
                    $partyid_array[0] = $extra_parties_without_lrs[$i]->party_id + 1;
                    $partyid = implode('.', $partyid_array);

                    $update_parentid_partyid[$l] = array(
                        'id' => $extra_parties_with_lrs[$parentid_key]['id'],
                        'party_id' => $partyid
                    );
                    $l++;
                }
                $k++;
            }

            $update_party_ids[$k] = array(
                'id' => $shuffling_Id,
                'party_id' => strval($shuffling_party_new_id)
            );

            $parentid_key_array = array_keys(array_column($extra_parties_with_lrs, 'parentid'), $shuffling_party_no);

            foreach ($parentid_key_array as $parentid_key) {

                $partyid_array = explode('.', $extra_parties_with_lrs[$parentid_key]['party_id']);
                $partyid_array[0] = $shuffling_party_new_id;
                $partyid = implode('.', $partyid_array);
                $update_parentid_partyid[$l] = array(
                    'id' => $extra_parties_with_lrs[$parentid_key]['id'],
                    'party_id' => $partyid
                );
                $l++;
            }
        } else {
            // shifting down

            if ($shuffling_position_type == 1) {
                // JUST BEFORE POSITION
                $shuffle_start = $shuffling_party_id + 1;
                $shuffle_end = $shuffle_on_party_id - 1;
                $shuffling_party_new_id = $shuffle_on_party_id - 1;
            } elseif ($shuffling_position_type == 2) {
                // JUST AFTER POSITION
                $shuffle_start = $shuffling_party_id + 1;
                $shuffle_end = $shuffle_on_party_id;

                $shuffling_party_new_id = $shuffle_on_party_id;
            }

            $k = 0;
            $l = 0;


            for ($i = $shuffle_start - 2; $i < $shuffle_end - 1; $i++) {

                $update_party_ids[$k] = array(
                    'id' => $extra_parties_without_lrs[$i]->id,
                    'party_id' => strval($extra_parties_without_lrs[$i]->party_id - 1)
                );

                $parentid_key_array = array_keys(array_column($extra_parties_with_lrs, 'parentid'), $extra_parties_without_lrs[$i]->party_no);

                foreach ($parentid_key_array as $parentid_key) {

                    $partyid_array = explode('.', $extra_parties_with_lrs[$parentid_key]['party_id']);
                    $partyid_array[0] = $extra_parties_without_lrs[$i]->party_id - 1;
                    $partyid = implode('.', $partyid_array);

                    $update_parentid_partyid[$l] = array(
                        'id' => $extra_parties_with_lrs[$parentid_key]['id'],
                        'party_id' => $partyid
                    );
                    $l++;
                }
                $k++;
            }
            $update_party_ids[$k] = array(
                'id' => $shuffling_Id,
                'party_id' => strval($shuffling_party_new_id)
            );

            $parentid_key_array = array_keys(array_column($extra_parties_with_lrs, 'parentid'), $shuffling_party_no);

            foreach ($parentid_key_array as $parentid_key) {

                $partyid_array = explode('.', $extra_parties_with_lrs[$parentid_key]['party_id']);
                $partyid_array[0] = $shuffling_party_new_id;
                $partyid = implode('.', $partyid_array);
                $update_parentid_partyid[$l] = array(
                    'id' => $extra_parties_with_lrs[$parentid_key]['id'],
                    'party_id' => $partyid
                );
                $l++;
            }
        }
        $result = $this->Reshuffled_party_model->update_extra_party_position($update_party_ids, $update_parentid_partyid);

        if ($result) {
            $this->session->set_flashdata('MSG', " <div class='alert alert-success alert-dismissible fade in'>Parties shuffled Successfully </div>");
            redirect('caveat/extra_party');
            exit(0);
        } else {
            $this->session->set_flashdata('MSG', "<div class='alert alert-danger alert-dismissible fade in'>Some Error Occured! Please try again. </div>");
            redirect('caveat/extra_party');
            exit(0);
        }
    }

}
