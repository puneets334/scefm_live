<?php
namespace App\Controllers;

class Citation_notes extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('mycases/Lists_model');
        $this->load->model('common/Common_model');
        $this->load->model('mycases/Citation_notes_model');
    }

    public function index($type = NULL) {

        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE_CIS && $_SESSION['login']['ref_m_usertype_id'] != USER_DEPARTMENT && $_SESSION['login']['ref_m_usertype_id'] != USER_CLERK) {
            redirect('login');
            exit(0);
        }
        $data['total_matters'] = $this->Lists_model->get_total_matters($_SESSION['login']['adv_sci_bar_id']);
        $data['pending_matters'] = $this->Lists_model->get_pending_matters($_SESSION['login']['adv_sci_bar_id']);
        $data['pending_reg_matters'] = $this->Lists_model->get_pending_reg_matters($_SESSION['login']['adv_sci_bar_id']);
        $data['pending_reg_pet_res_matters'] = $this->Lists_model->get_pending_reg_pet_res_matters($_SESSION['login']['adv_sci_bar_id']);
        $data['pending_un_reg_matters'] = $this->Lists_model->get_pending_un_reg_matters($_SESSION['login']['id']);
        $data['pending_un_reg_pet_res_matters'] = $this->Lists_model->get_pending_un_reg_pet_res_matters($_SESSION['login']['adv_sci_bar_id']);
        $data['disposed_matter'] = $this->Lists_model->get_disposed_matters($_SESSION['login']['adv_sci_bar_id']);
        $data['pending_reg_matters_data'] = $this->Lists_model->get_pending_reg_matters_data($_SESSION['login']['adv_sci_bar_id']);

        $this->load->view('templates/header');
        $this->load->view('mycases/mycases_updation_view', $data);
        $this->load->view('templates/footer');
    }

    /* public function add_citation() {

         $citation_description = script_remove($this->input->post("citation_description"));
         $remark_length = strip_tags($citation_description);
         $current_url = script_remove($this->input->post("current_url"));
         if (strlen($remark_length) > DISAPPROVE_REMARK_LENGTH) {
             $_SESSION['MSG'] = message_show("fail", 'Description should be max ' . DISAPPROVE_REMARK_LENGTH . ' characters!');
             redirect($current_url);
             exit(0);
         }
         if (empty($citation_description)) {
             $_SESSION['MSG'] = message_show("fail", 'Write Description to add is required !');
             redirect($current_url);
             exit(0);
         }
         $dno = $this->input->post("citation_cnr_no");
         $description = array(
             'advocate_id' => $_SESSION['login']['id'],
             'diary_no' => $dno,
             'description' => $citation_description,
             'created_date' => date('Y-m-d H:i:s'),
             'desc_type' => 1,
             'created_by_ip' => getClientIP()
         );

         $result = $this->Citation_notes_model->add_citation_n_notes($description);
         if ($result) {
             echo '2@@@Citation added successfully!';
         } else {
             echo "1@@@Error while trying to add!";
         }
     }*/
    public function add_citation_mycases() {


        // $citation_description = $this->input->post("temp");
        //  $remark_length = strip_tags($citation_description);
        //   $current_url = script_remove($this->input->post("current_url"));
        /*  if (strlen($remark_length) > DISAPPROVE_REMARK_LENGTH) {
              $_SESSION['MSG'] = message_show("fail", 'Description should be max ' . DISAPPROVE_REMARK_LENGTH . ' characters!');
              redirect($current_url);
              exit(0);
          }
          if (empty($citation_description)) {
              $_SESSION['MSG'] = message_show("fail", 'Write Description to add is required !');
              redirect($current_url);
              exit(0);
          }*/
        $c_description=$_POST['temp'];
        $dno = $_POST['dno'];

        /* var_dump($c_description);
         var_dump($dno);*/


        $description = array(
            'advocate_id' => $_SESSION['login']['id'],
            'diary_no' => $dno,
            'description' => $c_description,
            'created_date' => date('Y-m-d H:i:s'),
            'desc_type' => 1,
            'created_by_ip' => getClientIP()
        );

        $result = $this->Citation_notes_model->add_citation_n_notes($description);
        if ($result) {
            echo '2@@@Citation added successfully!';
        } else {
            echo "1@@@Error while trying to add!";
        }
    }
    public function update_citation_mycases() {

        $c_description=$_POST['temp'];
        $id = url_decryption($_POST['citation_id']);
        $dno = $_POST['dno'];

        /* var_dump($c_description);
         var_dump($dno);*/


        $description = array(
            'description' => $c_description,
            'created_date' => date('Y-m-d H:i:s'),
            'created_by_ip' => getClientIP()
        );
        //echo '<pre>';print_r($description);echo '</pre>';exit();
        $result = $this->Citation_notes_model->update_citation_n_notes($id,$description);
        if ($result) {
            echo '2@@@Citation Update Successfully!';
        } else {
            echo "1@@@Error while trying to add!";
        }
    }
    public function update_notes_mycases() {

        $c_description=$_POST['temp'];
        $id = url_decryption($_POST['notes_id']);
        $dno = $_POST['dno'];
        $description = array(
            'description' => $c_description,
            'created_date' => date('Y-m-d H:i:s'),
            'created_by_ip' => getClientIP()
        );
        //echo '<pre>';print_r($description);echo '</pre>';exit();
        $result = $this->Citation_notes_model->update_citation_n_notes($id,$description);
        if ($result) {
            echo '2@@@Notes Update Successfully!';
        } else {
            echo "1@@@Error while trying to add!";
        }
    }

    public function send_jail_otp()
    {
        $uid=$_POST['uname'];
        //$mobile_no= '9891122213';
        send_otp('Logging in Jail Module', '40');
    }
    public function add_notes_mycases() {


        // $citation_description = $this->input->post("temp");
        //  $remark_length = strip_tags($citation_description);
        //   $current_url = script_remove($this->input->post("current_url"));
        /*  if (strlen($remark_length) > DISAPPROVE_REMARK_LENGTH) {
              $_SESSION['MSG'] = message_show("fail", 'Description should be max ' . DISAPPROVE_REMARK_LENGTH . ' characters!');
              redirect($current_url);
              exit(0);
          }
          if (empty($citation_description)) {
              $_SESSION['MSG'] = message_show("fail", 'Write Description to add is required !');
              redirect($current_url);
              exit(0);
          }*/
        $c_description=$_POST['temp'];
        $dno = $_POST['dno'];

        /* var_dump($c_description);
         var_dump($dno);*/


        $description = array(
            'advocate_id' => $_SESSION['login']['id'],
            'diary_no' => $dno,
            'description' => $c_description,
            'created_date' => date('Y-m-d H:i:s'),
            'desc_type' => 2,
            'created_by_ip' => getClientIP()
        );

        $result = $this->Citation_notes_model->add_citation_n_notes($description);
        if ($result) {
            echo '2@@@Citation added successfully!';
        } else {
            echo "1@@@Error while trying to add!";
        }
    }


    public function add_notes() {

        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE_CIS) {
            redirect('login');
            exit(0);
        }

        $notes_description = script_remove($this->input->post("notes_description"));
        $remark_length = strip_tags($notes_description);
        $current_url = script_remove($this->input->post("current_url"));
        if (strlen($remark_length) > DISAPPROVE_REMARK_LENGTH) {
            $_SESSION['MSG'] = message_show("fail", 'Description should be max ' . DISAPPROVE_REMARK_LENGTH . ' characters!');
            redirect($current_url);
            exit(0);
        }
        if (empty($notes_description)) {
            $_SESSION['MSG'] = message_show("fail", 'Write Description to add is required !');
            redirect($current_url);
            exit(0);
        }
        $dno = $this->input->post("notes_cnr_no");
        if (empty($dno)) {
            $_SESSION['MSG'] = message_show("fail", 'Invalid Diary No. !');
            redirect($current_url);
            exit(0);
        }

        $description = array(
            'advocate_id' => $_SESSION['login']['id'],
            'diary_no' => $dno,
            'description' => $notes_description,
            'created_date' => date('Y-m-d H:i:s'),
            'desc_type' => 2,
            'created_by_ip' => getClientIP());

        $result = $this->Citation_notes_model->add_citation_n_notes($description);
        if ($result) {
            echo '2@@@Notes added successfully!';
        } else {
            echo '1@@@rror while trying to add!';
        }
    }

    public function get_citation_and_notes_list() {

        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE_CIS) {
            redirect('login');
            exit(0);
        }

        $after_delete = $this->input->post("after_delete");
        if (!empty($after_delete)) {
            $type = url_decryption($this->input->post("type"));
            // $type=$this->input->post("type");
            $dno = url_decryption($this->input->post("cnr_number"));
        } else {
            $type = $this->input->post("type");
            $dno = $this->input->post("cnr_number");
        }


        if (empty($dno)) {
            echo '1@@@Invalid Diary Number';
            exit(0);
        }
        if ($type != '1' && $type != '2') {
            echo '1@@@Invalid Type';
            exit(0);
        }
        if ($type == '1') {
            $header = 'CITATION DETAILS';
        } else {
            $header = 'NOTES DETAILS';
        }

        $result = $this->Citation_notes_model->get_citation_and_notes_list($type, $dno);
        //echo '<pre>';print_r($result);echo '</pre>';
        if (!empty($result)) {
            echo '2@@@<div class="x_content"><div>';
            $i = 1;

            foreach ($result as $dataRes) {

                $description = str_replace('<br>', ' ', $dataRes['description']);
                $description = str_replace('<b>', ' ', $description);
                $description = str_replace('</b>', ' ', $description);
                $description = str_replace('<ol>', ' ', $description);
                $description = str_replace('<li>', ' ', $description);
                $description = str_replace('</li>', ' ', $description);
                $description = str_replace('</ol>', ' ', $description);

                //--START : Share All Citation & Notes --------//

                $whatsapp_message .= '*DESCRIPTION* : ' . htmlentities($description, ENT_QUOTES) . '%0A' . '*CREATED DATE* : ' . date("d-m-Y H:i:s A", strtotime(htmlentities($dataRes['created_date'], ENT_QUOTES))) . '%0A';

                //--END : Share All Citation & Notes --------//
                //--START : Share All Citation & Notes ------//

                $whatsapp_msg_single = '*DESCRIPTION* : ' . htmlentities($description, ENT_QUOTES) . '%0A' . '*CREATED DATE* : ' . date("d-m-Y H:i:s A", strtotime(htmlentities($dataRes['created_date'], ENT_QUOTES))) . '%0A';

                //--END : Share All Citation & Notes --------//

                echo '<b>( ' . $i++ . ' ). </b>' . $dataRes['description'] . ' Created Date : <b>' . date("d-m-Y H:i:s A", strtotime(htmlentities($dataRes['created_date'], ENT_QUOTES))) . '</b>
                      <a href="javascript:void(0);" onclick ="edit_citation_n_notes(\'' . $dataRes['description'] . '\',\'' . url_encryption($dataRes['id']) . '\',\'' . ($type) . '\');" >&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-xs"><i class="fa fa-pencil-square-o"></i> Edit</button> </a>&nbsp;&nbsp;             
                      <a href="javascript:void(0);" onclick ="delete_citation_n_notes(\'' . url_encryption($dataRes['diary_no']) . '\',\'' . htmlentities($dataRes['created_date'], ENT_QUOTES) . '\',\'' . ($type) . '\');" ><button type="button" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</button> </a>             
                      <a href="javascript:void(0);" onclick="whatsapp_openWin_CitationNotes(\'' . $whatsapp_msg_single . '\')" charset="utf-8" class="fa fa-whatsapp" style="color:green;font-weight: bold; font-size: 20px;"></a><br><br>';
            }


            exit(0);
        } else {
            echo '1@@@Record not found for this Diary.';
            exit(0);
        }
    }


    public function get_contact_list() {

        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE_CIS) {
            redirect('login');
            exit(0);
        }

        $type = $this->input->post("type");
        $dno = $this->input->post("cnr_number");

        if (empty($dno)) {
            echo '1@@@Invalid Diary Number';
            exit(0);
        }

        $header = '<i class="fa fa-users"></i> Case Contacts';

        $result = $this->Citation_notes_model->get_case_contact_data($_SESSION['login']['id'], $dno);
        if (!empty($result)) {
            echo '2@@@';
            $i = 1;
            ?>

            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="90%">
                <thead>
                <tr class="success input-sm" role="row" >
                    <th>#</th>
                    <th>Name</th>
                    <th>Email </th>
                    <th>Mobile </th>
                    <th>Other Contact</th>
                    <th>Contact Type</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                foreach ($result as $dataRes) {

                    $btn_name = "Edit";
                    $class = 'btn btn-warning btn-xs';

                    if (!empty($dataRes['contact_type'])) {
                        $contact_type = ucfirst(strtolower($dataRes['contact_type']));
                    } else {
                        if ($dataRes['partyid'] == 'MP' && $dataRes['partytype'] == 'P') {
                            $contact_type = "Main Petitioner";
                        } elseif ($dataRes['partyid'] == 'MR' && $dataRes['partytype'] == 'R') {
                            $contact_type = "Main Respondent";
                        } elseif ($dataRes['partyid'] != 'MP' && $dataRes['partytype'] == 'P') {
                            $contact_type = "Petitioner Extra Party";
                        } elseif (!$dataRes['partyid'] != 'MR' && $dataRes['partytype'] == 'R') {
                            $contact_type = "Respondent Extra Party";
                        }
                    }
                    ?>

                    <tr>
                        <td width="1%"> <?php echo htmlentities($i++, ENT_QUOTES); ?></td>
                        <td width="6%"><?php echo htmlentities($dataRes['p_name'], ENT_QUOTES); ?></td>
                        <td width="6%"><?php echo wordwrap(htmlentities($dataRes['p_email'], ENT_QUOTES), 15, "<br>\n", TRUE); ?></td>
                        <td width="5%"><?php echo htmlentities($dataRes['p_mobile'], ENT_QUOTES); ?></td>
                        <td width="15%"><?php echo wordwrap(htmlentities($dataRes['p_other_contact'], ENT_QUOTES), 11, "<br>\n", TRUE); ?></td>
                        <td width="15%"><?php echo htmlentities($contact_type, ENT_QUOTES); ?></td>
                        <td width="12%">
                            <a href="javascript:void(0)" onclick="get_contacts('<?php echo htmlentities($dataRes['id'], ENT_QUOTES); ?>')" title="View "><?php echo htmlentities($btn_name, ENT_QUOTES); ?></a>
                            <!--  <a data-toggle="modal" href="#edit_contact_model" id="edit_contact" class="edit_contact_value" data-id="<?php echo htmlentities(url_encryption($dataRes['id']), ENT_QUOTES); ?>" title="Edit Contact"><?php echo $btn_name; ?></a>-->
                        <?php echo '<a href="javascript:void(0);" onclick ="delete_contacts(\'' . $dataRes['id'] . '\',\'' . htmlentities($dataRes['diary_no'], ENT_QUOTES) . '\',\'' . ($type) . '\');" ><button type="button" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</button> </a>';?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php
            //--START : Share All Citation & Notes --------//

            exit(0);
        } else {
            echo '1@@@Record not found for this Diary.';
            exit(0);
        }
    }

    public function delete_contacts() {
        $id = $_POST['id'];
        if (!empty($id) && $id !=null) {
            $result = $this->Citation_notes_model->delete_contacts($id);
            if ($result) {
                echo '2@@@Data deleted successfully!';
                exit(0);
            } else {
                echo '1@@@Fail while trying to delete!';
                exit(0);
            }
        } else {
            echo '1@@@Invalid post!';
            exit(0);
        }
    }
    public function delete_citation_n_notes() {

        $date = $_POST['date'];
        $type = ($_POST['type']);
        $diary_no=$_POST['diary_no'];

        if ($type != '1' && $type != '2') {
            echo '1@@@Invalid post';
            exit(0);
        }
        if (!empty($date) && !empty($type)) {
            $result = $this->Citation_notes_model->delete_citation_n_notes($type, $date,$diary_no);
            if ($result) {
                echo '2@@@Data deleted successfully!';
                exit(0);
            } else {
                echo '1@@@Fail while trying to delete!';
                exit(0);
            }
        } else {
            echo '1@@@Invalid post!';
            exit(0);
        }
    }
    public function update_citation_n_notes() {

        $date = $_POST['date'];
        $type = ($_POST['type']);
        $diary_no=$_POST['diary_no'];

        if ($type != '1' && $type != '2') {
            echo '1@@@Invalid post';
            exit(0);
        }
        if (!empty($date) && !empty($type)) {
            $result = $this->Citation_notes_model->update_citation_n_notes($type, $date,$diary_no);
            if ($result) {
                echo '2@@@Data deleted successfully!';
                exit(0);
            } else {
                echo '1@@@Fail while trying to delete!';
                exit(0);
            }
        } else {
            echo '1@@@Invalid post!';
            exit(0);
        }
    }

    function add_case_contact() {

        $dno = ($_POST['diary_no']);
        $name = strtoupper($_POST['name']);
        $email = $_POST['email_id'];
        $mobile = $_POST['mobile_no'];
        $o_contact = $_POST['o_contact'];
        $contact_type = strtoupper($_POST['contact_type']);


        $validate_name = validate_names($name, TRUE, 3, 99, 'Name');
        if ($validate_name['response'] == FALSE) {
            echo '1@@@' . htmlentities($validate_name['msg']['field_name'], ENT_QUOTES);
            exit(0);
        }
        $email_pattern = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/";
        if (!empty($email)) {
            if (!preg_match($email_pattern, $email)) {
                echo '1@@@' . htmlentities('Please enter valid email id', ENT_QUOTES);
                exit(0);
            }
        }
        if (!empty($mobile)) {
            if (preg_match('/[^0-9]/i', $mobile) || strlen($mobile) != 10) {
                echo '1@@@' . htmlentities('Please enter valid mobile', ENT_QUOTES);
                exit(0);
            }
        }


        $validate_name = validate_names($contact_type, TRUE, 3, 99, 'Contact Type');
        if ($validate_name['response'] == FALSE) {
            echo '1@@@' . htmlentities($validate_name['msg']['field_name'], ENT_QUOTES);
            exit(0);
        }


        $contact_id = url_decryption($_POST['contact_id']);

        if (empty($contact_id)) {
            if (empty($dno)) {
                echo '1@@@Enter valid Diary Number';
                exit(0);
            }
            $data = array(
                'userid' => $_SESSION['login']['id'],
                'diary_no' => $dno,
                'p_name' => $name,
                'p_email' => $email,
                'p_mobile' => $mobile,
                'p_other_contact' => $o_contact,
                'contact_type' => $contact_type,
                'created_by' => $_SESSION['login']['id'],
                'created_on' => date('Y-m-d H:i:s'),
                'create_ip' => getClientIP()
            );

            $result = $this->Citation_notes_model->add_case_contact_data($data);
            if ($result) {
                echo '2@@@' . htmlentities('Contact added successfully !', ENT_QUOTES);
            } else {
                echo '1@@@' . htmlentities('Some Error Occured !', ENT_QUOTES);
            }
        } else {
            $data = array(
                'userid' => $_SESSION['login']['id'],
                'p_name' => $name,
                'p_email' => $email,
                'p_mobile' => $mobile,
                'p_other_contact' => $o_contact,
                'contact_type' => $contact_type,
                'updated_by' => $_SESSION['login']['id'],
                'updated_on' => date('Y-m-d H:i:s'),
                'update_ip' => getClientIP()
            );

            $result = $this->Citation_notes_model->update_case_contact_data($data, $contact_id);
            if ($result) {
                echo '2@@@' . htmlentities('Contact Updated successfully !', ENT_QUOTES);
            } else {
                echo htmlentities('Some Error Occured !', ENT_QUOTES);
            }
        }
    }
    public function update_case_contacts() {
        $dno = ($_POST['diary_no']);
        $name = strtoupper($_POST['name']);
        $email = $_POST['email_id'];
        $mobile = $_POST['mobile_no'];
        $o_contact = $_POST['o_contact'];
        $contact_type = strtoupper($_POST['contact_type']);


        $validate_name = validate_names($name, TRUE, 3, 99, 'Name');
        if ($validate_name['response'] == FALSE) {
            echo '1@@@' . htmlentities($validate_name['msg']['field_name'], ENT_QUOTES);
            exit(0);
        }
        $email_pattern = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/";
        if (!empty($email)) {
            if (!preg_match($email_pattern, $email)) {
                echo '1@@@' . htmlentities('Please enter valid email id', ENT_QUOTES);
                exit(0);
            }
        }
        if (!empty($mobile)) {
            if (preg_match('/[^0-9]/i', $mobile) || strlen($mobile) != 10) {
                echo '1@@@' . htmlentities('Please enter valid mobile', ENT_QUOTES);
                exit(0);
            }
        }


        $validate_name = validate_names($contact_type, TRUE, 3, 99, 'Contact Type');
        if ($validate_name['response'] == FALSE) {
            echo '1@@@' . htmlentities($validate_name['msg']['field_name'], ENT_QUOTES);
            exit(0);
        }


        $contact_id = $_POST['contact_id'];

        if (empty($contact_id)) {
            if (empty($dno)) {
                echo '1@@@Enter valid Diary Number';
                exit(0);
            }
            $data = array(
                'userid' => $_SESSION['login']['id'],
                'diary_no' => $dno,
                'p_name' => $name,
                'p_email' => $email,
                'p_mobile' => $mobile,
                'p_other_contact' => $o_contact,
                'contact_type' => $contact_type,
                'created_by' => $_SESSION['login']['id'],
                'created_on' => date('Y-m-d H:i:s'),
                'create_ip' => getClientIP()
            );

            $result = $this->Citation_notes_model->update_case_contact_data($data,$dno);
            if ($result) {
                echo '2@@@' . htmlentities('Contact Updated successfully !', ENT_QUOTES);
            } else {
                echo '1@@@' . htmlentities('Some Error Occured !', ENT_QUOTES);
            }
        } else {
            $data = array(
                'userid' => $_SESSION['login']['id'],
                'p_name' => $name,
                'p_email' => $email,
                'p_mobile' => $mobile,
                'p_other_contact' => $o_contact,
                'contact_type' => $contact_type,
                'updated_by' => $_SESSION['login']['id'],
                'updated_on' => date('Y-m-d H:i:s'),
                'update_ip' => getClientIP()
            );

            $result = $this->Citation_notes_model->update_case_contact_data($data, $contact_id);
            if ($result) {
                echo '2@@@' . htmlentities('Contact Updated successfully !', ENT_QUOTES);
            } else {
                echo htmlentities('Some Error Occured !', ENT_QUOTES);
            }
        }

    }

    public function get_recipients_mail() {


         $cnr = str_replace("/", "", $_POST['subject']);

        // $get_contact = $this->Mycases_model->contact_cases_list_detail($_SESSION['login']['id'], $cnr);
        $get_contact = $this->Citation_notes_model->get_case_contact_data($_SESSION['login']['id'], $cnr);
        $re_emails = "";

        $re_emails = '<div class="uk-panel-scrollable" uk-float-right><ul style="padding-left: 0 !important;">
          <li class="dropdown" style="list-style-type: none;">
          <a data-toggle="dropdown" class="dropdown-toggle">Select Contacts <b class="caret" style="float:right;"></b></a><ul class="dropdown-menu col-xs-11">';
        $i = 1;
        ?>
        <table border =1 >
        <?php
        foreach ($get_contact as $contact) {
            $re_emails .= '<div  align=" left" class="checkbox" style="margin-left: 45px;">';

            if ($_POST['type'] == 'mail') {
                if (!empty($contact['p_email'])) {


                    $re_emails .= '<tr><td><input type="checkbox" value="' . $contact['p_mobile'] . '" name="emailMulti" id="email_' . $i . '" onclick="updateTextArea(' . $i . ',$(this))">' . $contact['p_name']  .'<td></tr>';
                }
            }
            ?></table>
            <?php
            if ($_POST['type'] == 'sms') {

                if (!empty($contact['p_mobile'])) {
                    /*  if (!empty($contact['p_other_contact'])) {
                      $other_contact=  ','. $contact['p_other_contact'];
                      }else{
                      $other_contact='';
                      } */
                    $re_emails .= '<label><input type="checkbox" value="' . $contact['p_mobile'] . '" name="mobile[]" id="mob_' . $i . '" onclick="updatemobArea(' . $i . ',$(this))">' . $contact['p_name'] . '</label>';
                }
            }
            $i++;
            $re_emails .= '</div>
          </li>';
        }
        $re_emails .= '</li></ul>';

        echo !empty(rtrim($re_emails) ? rtrim($re_emails, ', ') . "@@@" . str_replace(",,", ",", rtrim($re_emails, ', ')) : '';
        echo "<script type='text/javascript'>
             
               function updatemobArea(id,mob_checked) {
                 var mob='';
                    if (mob_checked.is(':checked')) {
                       mob = $('#mob_' + id).val();
                       }
                    if (mob_checked.is(':unchecked')) {
                      var uncheck =$('#mob_' + id).val();
                     
                      }
                     $('#mobids').append(mob + ',');
                       var em = $('#mobids').html();
                        if (em.indexOf(uncheck) != -1) {
                         em = em.replace(uncheck+',', '');
                         
                         $('#mobids').html(em);
                         }
                         else{
                             $('#mobids').html(em);
                          }
                       var mobno = $('#mobids').html();
                       $('#recipient_no').val(mobno);

                      }

                </script>";
    }



        public function send_sms()    //function added by Preeti Agrawal on 2 jan 2021
    {
        $message=$_POST['message'];
        $mobile_no=$_POST['mobile_no'];
        if(empty($mobile_no))
        {
            echo '1@@@At least one Mobile Number is required!';
            exit(0);
        }
        if (isset($mobile_no) && !empty($mobile_no)) {
            $mob_ids = !empty($mobile_no) ? explode(",", rtrim($mobile_no, ",")) : '';
            $recp_mob_ids = $mob_ids;
            for ($i = 0; $i< count($recp_mob_ids); $i++) {
                $validate_nums = validate_number($recp_mob_ids[$i], TRUE, 10, 10, 'Recipient Mobile No.');
                if ($validate_nums['response'] == FALSE) {
                    echo '1@@@' . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES);
                    exit(0);
                }
            }
        }
        $response=sendSMS(41,$mobile_no,$message,SCISMS_Diary_Listing);
        if ($response=="success") {
            echo '2@@@SMS sent successfully!'.$message;
        } else {
            echo '1@@@some error occured while sending SMS!';
        }
    }


    public function send_sms_and_mail() {


        $InputArray = $this->input->post();

        $sender_name = $_SESSION['login']['first_name'] . " " . $_SESSION['login']['last_name'];
        $sender_email = $_SESSION['login']['emailid'];

        if (isset($InputArray['recipient_mail']) || !empty($InputArray['recipient_mail'])) {
            $email_ids = !empty($InputArray['recipient_mail']) ? explode(",", rtrim($InputArray['recipient_mail'], ',')) : '';
            $recp_email_ids = $email_ids;

            for ($i = 0; $i > count($recp_email_ids); $i++) {
                //$validate_email = validate_email_id($recp_email_ids[$i], TRUE, 5, 50, 'Recipient Email');
                $validate_email['response']=TRUE;
                if (!filter_var($recp_email_ids[$i], FILTER_VALIDATE_EMAIL)) {
                    $validate_email['response']=FALSE;
                }
                 if ($validate_email['response'] == FALSE) {
                    echo '1@@@' . htmlentities($validate_email['msg']['field_name'], ENT_QUOTES);
                    exit(0);
                }
            }

            $validate_names = validate_names_for_cis_master($InputArray['mail_subject'], TRUE, 3, 200, 'Subject');
            if ($validate_names['response'] == FALSE) {
                echo '1@@@' . htmlentities($validate_names['msg']['field_name'], ENT_QUOTES);
                exit(0);
            }

            /*  if ($this->session->userdata['captchaWord'] != $InputArray['captcha_code']) {
                  echo '1@@@' . htmlentities('Invalid Captcha', ENT_QUOTES);
                  exit(0);
              }*/
            $case_details_sms=ucwords(strtolower($InputArray['case_details_sms'] . 'SEND BY :  Advocate : '.$_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'].'. - Supreme Court of India'));
             $subject = $InputArray['mail_subject'];
            $sms_message=$subject.$case_details_sms;

            for ($k = 0; $k < count($recp_email_ids); $k++) {
                if (!empty($recp_email_ids[$k])) {
                    $email = $recp_email_ids[$k];
                    $typeId="41";

                    $response= send_mobile_sms($recp_email_ids[$k], $sms_message,SCISMS_CITATION_NOTE);

                     // $response .= send_mail_msg($email, $subject, $InputArray['case_details_mail'], $sender_name);
                      //var_dump($response);exit();
                    /* function for sms */
                    //  echo  $email .rawurlencode($InputArray['case_details_mail']);
                    // $sms_url='http://'.SMS_SERVER_HOST.'/eAdminSCI/a-push-sms-gw?mobileNos='.$email.'&message='.rawurlencode($InputArray['case_details_mail']).'&typeId='.$typeId.'&myUserId=NIC001001&myAccessId=root&authCode=sldkfjsklf126534__sdgdg-sf154ncvbvziu789asdsagd1235';

                    /* end of the function*/
                }
            }

            echo '2@@@SMS send successfully!';

        } elseif (isset($InputArray['recipient_no']) || empty($InputArray['recipient_no'])) {

            $mob_ids = !empty($InputArray['recipient_no']) ? explode(",", rtrim($InputArray['recipient_no'], ",")) : '';
            $recp_mob_ids = $mob_ids;

            for ($i = 0; $i > count($recp_mob_ids); $i++) {
                $validate_nums = validate_number($recp_mob_ids[$i], TRUE, 10, 10, 'Recipient Mobile No.');
                if ($validate_nums['response'] == FALSE) {
                    echo '1@@@' . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES);
                    exit(0);
                }
            }

            /*    if ($this->session->userdata['captchaWord'] != $InputArray['captcha_code']) {
                    echo '1@@@' . htmlentities('Invalid Captcha', ENT_QUOTES);
                    exit(0);
                }*/

            $case_details_sms = $InputArray['case_details_sms'] . '<br>SEND BY : <b> Advocate : </b>' . strtoupper($_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name']);
            $subject = $InputArray['mail_subject'];
            $sms_message=$subject.$case_details_sms;
            for ($k = 0; $k < count($recp_mob_ids); $k++) {

                if (!empty($mobile_nums[$k])) {
                    $response .= send_mobile_sms($recp_mob_ids[$k], $sms_message,SCISMS_CITATION_NOTE);
                }
            }
            if ($response) {
                echo '2@@@SMS send successfully.!';
            } else {
                echo '1@@@some error accured while send SMS.!';
            }
        }
    }


    function case_contact() {

        $id = $_POST['id'];

        $results = $this->Citation_notes_model->get_cnr_case_contact_data($id);

        if (!empty($results)) {
            echo '2@@@<div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="text-align: center;"><i class="fa fa-users"></i> Edit Case Contacts </h4>
                <div class="contact-response" id="mail_msg" ></div>
            </div>';

            $attribute = array("name" => "edit_case_contact_details", "id" => "edit_case_contact_details", "autocomplete" => "off");
            echo form_open("#", $attribute);

            echo '
                <div class="modal-body">
                   <div class="row" id="contact_div_hide">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12" id="contact">
                                   </div>

                            </div>
                            <div id="contactids" style="display: none;"></div>
                            <div class="clearfix"></div><br>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12" >
                                   <input type="text" size="50" id="p_name" name="name" value="' . htmlentities($results[0]['p_name'], ENT_QUOTES) . '" class="form-control" maxlength="250" placeholder="Name">

                            </div>

                            </div>
                            <div class="clearfix"></div><br>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" size="50" id="p_email_id" name="email_id" value="' . htmlentities($results[0]['p_email'], ENT_QUOTES) . '" class="form-control" maxlength="250" placeholder="Email Id">
                                </div>

                            </div> <div class="clearfix"></div><br>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" size="50" id="p_mobile_no" name="mobile_no" value="' . htmlentities($results[0]['p_mobile'], ENT_QUOTES) . '" class="form-control" maxlength="10" placeholder="Mobile">
                                </div>

                            </div>
                            <div class="clearfix"></div><br>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" size="50" id="p_o_contact" name="o_contact" value="' . htmlentities($results[0]['p_other_contact'], ENT_QUOTES) . '" class="form-control" maxlength="55"  placeholder="Other contact">
                                </div>

                            </div>
                            <div class="clearfix"></div><br>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="contact_id" id="contact_id" value="' . htmlentities(url_encryption($results[0]['id']), ENT_QUOTES) . '"/>
                                    <input type="text" size="50" id="p_contact_type" name="contact_type" value="' . htmlentities($results[0]['contact_type'], ENT_QUOTES) . '" class="form-control" maxlength="100" placeholder="Petitioner, Respondent, Witness, Other">
                                </div>

                            </div>

                            <div class="clearfix"></div><br>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <input type="button"   class="uk-button uk-button-default uk-modal-close " value="Cancel"> 
                               <input type="button" onClick="update_case_contacts(this.id)" id="' . htmlentities($results[0]['id'], ENT_QUOTES) . '" name="edit_contacts" class="uk-button uk-button-primary" value="Submit">
<br>                            
</div><br>

                        </div>
                    </div>
                </div>'
                . form_close() .
                '</div>';
            ?>
            <script>
                $(".cancel").click(function () {
                    $('#emailids').html("");
                    $('#mobids').html("");
                    $("#article_mail_modal").modal("hide");
                    $("#article_sms_modal").modal("hide");
                    $("#add_contact_model").modal("hide");
                    $("#edit_contact_data").hide();
                    $(".modal-backdrop").hide();

                });
                $('#edit_case_contact_details').on('submit', function (e) {

                    e.preventDefault();

                    if ($('#edit_case_contact_details').valid()) {

                        var form_data = $(this).serialize();
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $.ajax({
                            type: "POST",
                            data: form_data,
                            url: "<?php echo base_url('mycases/citation_notes/add_case_contact'); ?>",
                            success: function (data) {

                                $('#msg').show();
                                if (data.charAt(0) == 1) {
                                    $("#msg_div").hide();
                                    $("#invalid_msg_div").show();
                                    $("#invalid_msg_div").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + data.substring(4, data.length) + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                }
                                if (data.charAt(0) == 2) {
                                    $("#msg_div").show();
                                    $("#invalid_msg_div").hide();
                                    $("#msg_div").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + data.substring(4, data.length) + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                }
                                $("#edit_contact_data").hide();
                                $(".modal-backdrop").hide();

                                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            },
                            error: function () {
                                $.getJSON("<?php echo base_url() . 'Login/get_csrf_new'; ?>", function (result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            }
                        });

                    } else {
                        return false;
                    }

                });


                $('#edit_case_contact_details').validate({
                    focusInvalid: false,
                    ignore: ":hidden",
                    rules: {
                        cnr_no: {
                            required: true,

                        }, name: {
                            required: true,

                            maxlength: 249
                        }, email_id: {
                            required: false,
                            valid_email: true,
                        }, mobile_no: {
                            required: false,
                            digits: true,
                            maxlength: 10
                        },
                        o_contact: {
                            required: false,
                            num_hyphen_comma: true

                        },
                        contact_type: {
                            required: true,

                        },

                    },
                    messages: {
                        cnr_no: {
                            required: 'Select CNR',

                        }, name: {
                            required: 'Enter Name',

                        }, email_id: {
                            required: 'Enter Valid Email id',
                        }, mobile_no: {
                            required: 'Enter Valid Mobile no.',
                        },
                        o_contact: {required: ''},
                        contact_type: {
                            required: 'Enter Contact type.',

                        },

                    },
                    highlight: function (element) {
                        //$(element).closest('.form-group').addClass('has-error');
                    },
                    unhighlight: function (element) {
                        //$(element).closest('.form-group').removeClass('has-error');
                    },
                    errorElement: 'span',
                    errorClass: 'error-tip',
                    errorPlacement: function (error, element) {
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });</script>
            <?php
        } else {
            echo '1@@@Record not found for this CNR.';
            exit(0);
        }
    }

    public function view_contact_list() {

        if ($_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE && $_SESSION['login']['ref_m_usertype_id'] != USER_ADVOCATE_CIS) {
            redirect('login');
            exit(0);
        }

        $dno = url_decryption($_POST['diary_no']);

        if (empty($dno)) {
            echo '1@@@Invalid Diary Number';
            exit(0);
        }

        $header = '<i class="fa fa-users"></i> Case Contacts';

        $result = $this->Citation_notes_model->get_case_contact_data($_SESSION['login']['id'], $dno);
        if (!empty($result)) {


            echo '2@@@<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="90%">
                <thead>
                    <tr class="success input-sm" role="row" >
                        <th>#</th>
                        <th>Name</th>
                        <th>Email </th>
                        <th>Mobile </th>
                        <th>Other Contact</th>
                        <th>Contact Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

            $i = 1;
            foreach ($result as $dataRes) {

                $btn_name = "Edit";
                $class = 'btn btn-warning btn-xs';

                if (!empty($dataRes['contact_type'])) {
                    $contact_type = ucfirst(strtolower($dataRes['contact_type']));
                } else {
                    if ($dataRes['partyid'] == 'MP' && $dataRes['partytype'] == 'P') {
                        $contact_type = "Main Petitioner";
                    } elseif ($dataRes['partyid'] == 'MR' && $dataRes['partytype'] == 'R') {
                        $contact_type = "Main Respondent";
                    } elseif ($dataRes['partyid'] != 'MP' && $dataRes['partytype'] == 'P') {
                        $contact_type = "Petitioner Extra Party";
                    } elseif (!$dataRes['partyid'] != 'MR' && $dataRes['partytype'] == 'R') {
                        $contact_type = "Respondent Extra Party";
                    }
                }

                echo '<tr>
                            <td width="1%"> ' . htmlentities($i++, ENT_QUOTES) . '</td>
                            <td width="6%">' . htmlentities($dataRes['p_name'], ENT_QUOTES) . '</td>
                            <td width="6%">' . wordwrap(htmlentities($dataRes['p_email'], ENT_QUOTES), 15, "<br>\n", TRUE) . '</td>  
                            <td width="5%">' . htmlentities($dataRes['p_mobile'], ENT_QUOTES) . '</td>
                            <td width="15%">' . wordwrap(htmlentities($dataRes['p_other_contact'], ENT_QUOTES), 11, "<br>\n", TRUE) . '</td>
                            <td width="15%">' . htmlentities($contact_type, ENT_QUOTES) . '</td>
                            <td width="12%"><a href="javascript:void(0)" onclick="get_contacts(' . htmlentities($dataRes['id'], ENT_QUOTES) . ')" title="View ">' . htmlentities($btn_name, ENT_QUOTES) . '</a></td> 
                  
                </tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '1@@@Record not found for this Diary.';
            exit(0);
        }
    }

    function aor_contact_list() {


        $dropDownOptions = '<option value="">Select Contact</option>';
        $aor_contact = $this->Citation_notes_model->get_aor_contact();

        foreach ($aor_contact as $aor) {

            $dropDownOptions .= '<option value="' . escape_data($aor['name'] . "#$" . $aor['mobile'] . "#$" . $aor['email']) . '">' . escape_data(strtoupper($aor['name'])) . '</option>';
        }

        echo $dropDownOptions;
    }

}