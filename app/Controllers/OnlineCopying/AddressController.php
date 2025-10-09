<?php

namespace App\Controllers\OnlineCopying;

use App\Controllers\BaseController;
use App\Models\OnlineCopying\CommonModel;
use App\Models\OnlineCopying\AddressModel;
use Config\Database;
use App\Libraries\webservices\Ecoping_webservices;

class AddressController extends BaseController
{

    protected $session;
    protected $Common_model;
    protected $AddressModel;
    protected $db2;
    public $ecoping_webservices;
    
    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $_SESSION['is_token_matched'] = 'Yes';
        if (!empty($this->session->get('login')) && ($this->session->get('login')['ref_m_usertype_id'] != AUTHENTICATED_BY_AOR || $this->session->get('login')['ref_m_usertype_id'] != ARGUING_COUNSEL)) {
            if (empty($this->session->get('login'))) {
                return response()->redirect(base_url('/'));
            }
            // else {
            //     is_user_status();
            // }
        }
        $this->db2 = Database::connect('sci_cmis_final'); // Connect to the 'sci_cmis_final' database
        $this->Common_model = new CommonModel();
        $this->AddressModel = new AddressModel();
        $this->ecoping_webservices = new Ecoping_webservices();
        unset($_SESSION['MSG']);
        unset($_SESSION['msg']);
    }

    public function applicantAddress()
    {
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
        }
        // else {
        //     is_user_status();
        // }
        if (!empty($this->session->get('login')) && ($this->session->get('login')['ref_m_usertype_id'] != AUTHENTICATED_BY_AOR || $this->session->get('login')['ref_m_usertype_id'] != ARGUING_COUNSEL)) {
            if (empty(getSessionData('login'))) {
                return response()->redirect(base_url('/'));
            }
            // else{
            //     is_user_status();
            // }
        }
        $mobile_number = $emailid = '';
        if (!empty(getSessionData('login')['mobile_number'])) {
            $mobile_number = getSessionData('login')['mobile_number'];
        }
        if (!empty(getSessionData('login')['emailid'])) {
            $emailid = getSessionData('login')['emailid'];
        }
        $verifyAadhar = $userAddress = $userBarAddress = [];
        $userAddress = $this->ecoping_webservices->getUserAddress($mobile_number, $emailid);
        $userAdharVerify = $this->ecoping_webservices->verifyAadhar($mobile_number, $emailid);
        $userBarAddress = $this->ecoping_webservices->getListedCases($mobile_number, $emailid);
        return $this->render('onlineCopying.applicant_address', @compact('userAddress', 'userAdharVerify', 'userBarAddress'));
    }

    function getClientIP()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function saveApplicantAddress()
    {
        if ($_SESSION['is_token_matched'] == 'Yes' && getSessionData('login')['emailid']) {
            $request = service('request');
            $clientIP = $this->getClientIP();
            $applicantMobile = $applicantEmail = '';
            if (isset(getSessionData('login')['mobile_number'])) {
                $applicantMobile = getSessionData('login')['mobile_number'];
            }
            if (isset(getSessionData('login')['emailid'])) {
                $applicantEmail = getSessionData('login')['emailid'];
            }
            // $userAddress= $this->AddressModel->checkUserAddress($mobile_number,$emailid );
            $db3 = \Config\Database::connect('e_services');
            $session = \Config\Services::session();
            $address = $this->ecoping_webservices->getUserAddress($applicantMobile, $applicantEmail);
            if (!empty($address) && count($address) == 3) {
                $response = ['status' => 'Max 3 Addresses Already Added'];
            } else {
                $validatedName = '';
                if (!empty($address)) {
                    $rowCheck = $address[0];
                    // if (strtoupper($rowCheck['first_name']) == strtoupper($request->getPost('first_name')) && strtoupper($rowCheck['second_name']) == strtoupper($request->getPost('second_name'))) {
                    if (strtoupper($rowCheck['first_name']) == strtoupper($request->getPost('first_name'))) {
                        $validatedName = 'YES'; //ok
                    } else {
                        $response = ['status' => 'First Name and Second Name can not be change'];
                    }
                }
                if ($validatedName == 'YES' || empty($address)) {
                    $data = [
                        'mobile' => $applicantMobile,
                        'email' => $applicantEmail,
                        'first_name' => $request->getPost('first_name'),
                        // 'second_name' => $request->getPost('second_name'),
                        'address_type' => $request->getPost('address_type'),
                        'address' => $request->getPost('postal_add'),
                        'city' => $request->getPost('city'),
                        'district' => $request->getPost('district'),
                        'state' => $request->getPost('state'),
                        'pincode' => $request->getPost('pincode'),
                        'country' => $request->getPost('country'),
                        'entry_time_ip' => $clientIP,
                    ];
                    $insertData = $this->ecoping_webservices->saveUserAddress($data);
                    if ($insertData['status'] == 'success') {
                        $response = ['status' => 'success'];
                        $address = $this->ecoping_webservices->getUserAddress($applicantMobile, $applicantEmail);
                        if (!empty($address)) {
                            $session->set('is_user_address_found', 'YES');
                            $session->set('user_address', $address);
                        } else {
                            $session->set('is_user_address_found', 'NO');
                        }
                    } else {
                        $response = ['status' => 'Error:Record Not Inserted'];
                    }
                }
            }
        } else {
            $response = ['status' => 'Session Expired'];
        }
        return $this->response->setJSON($response);
    }

    public function getPincodeDetails()
    {
        $request = service('request');
        $pincode = $request->getPost('pincode');
        $pincodeDetails = $this->ecoping_webservices->getPincode($pincode);
        if ($pincodeDetails) {
            return $this->response->setJSON([
                'status' => 'success',
                'taluk_name' => $pincodeDetails->taluk_name,
                'district_name' => $pincodeDetails->district_name,
                'state_name' => $pincodeDetails->state,
            ]);
        } else {
            return $this->response->setJSON(['status' => 'error']);
        }
    }

    public function RemoveApplicantAddress()
    {
        $request = service('request');
        $session = \Config\Services::session();
        $clientIP = $this->getClientIP();
        $addressID = $this->request->getPost('address_id');
        $removeAddress = $this->ecoping_webservices->removeApplicantAddress($addressID, $clientIP);
        if ($removeAddress) {
            $array = ['status' => 'success'];
            $mobile =  $applicantMobile = getSessionData('login')['mobile_number'];
            $email =   $applicantEmail = getSessionData('login')['emailid'];
            $addressData = $this->ecoping_webservices->getUserAddress($email, $mobile);
            if (!empty($addressData)) {
                $session->set('is_user_address_found', 'YES');
                $session->set('user_address', $addressData);
            } else {
                $session->set('is_user_address_found', 'NO');
            }
        } else {
            $array = ['status' => 'Error: Not Removed'];
        }
        return $this->response->setJSON($array);
    }

    public function editApplicantAddress()
    {
        $request = service('request');
        $address_id = $request->getPost('address_id');
        $address_type = $request->getPost('address_type');
        $first_name = $request->getPost('first_name');
        // $second_name = $request->getPost('second_name');
        $address = $request->getPost('address');
        $city = $request->getPost('city');
        $district = $request->getPost('district');
        $state = $request->getPost('state');
        $pincode = $request->getPost('pincode');
        $country = $request->getPost('country');
        $data = [
            'address_id' => $address_id,
            'address_type' => $address_type,
            'first_name' => $first_name,
            // 'second_name' => $second_name,
            'address' => $address,
            'city' => $city,
            'district' => $district,
            'state' => $state,
            'pincode' => $pincode,
            'country' => $country
        ];
        return $this->render('onlineCopying.applicant_address_edit', $data);
    }

    public function updateApplicantAddress()
    {
        if ($_SESSION['is_token_matched'] == 'Yes' && getSessionData('login')['emailid'] && getSessionData('login')['mobile_number']) {
            $addressID = '';
            $request = service('request');
            $addressID = $request->getPost('address_id');
            $clientIP = $this->getClientIP();
            $applicantMobile = $applicantEmail = '';
            if (!empty(getSessionData('login')['mobile_number'])) {
                $applicantMobile = getSessionData('login')['mobile_number'];
            }
            if (!empty(getSessionData('login')['emailid'])) {
                $applicantEmail = getSessionData('login')['emailid'];
            }
            $db3 = \Config\Database::connect('e_services');
            $session = \Config\Services::session();
            $address = $this->ecoping_webservices->getUserAddress($applicantMobile, $applicantEmail);
            if (count($address) > 0) {
                $rowCheck = $address[0];
                // if (strtoupper($rowCheck['first_name']) == strtoupper($request->getPost('first_name')) && strtoupper($rowCheck['second_name']) == strtoupper($request->getPost('second_name'))) {
                if (strtoupper($rowCheck['first_name']) == strtoupper($request->getPost('first_name'))) {
                    $removeAddress = $this->ecoping_webservices->removeApplicantAddress($addressID, $clientIP);
                    $data = [
                        'mobile' => $applicantMobile,
                        'email' => $applicantEmail,
                        'first_name' => $request->getPost('first_name'),
                        // 'second_name' => $request->getPost('second_name'),
                        'address_type' => $request->getPost('address_type'),
                        'address' => $request->getPost('postal_add'),
                        'city' => $request->getPost('city'),
                        'district' => $request->getPost('district'),
                        'state' => $request->getPost('state'),
                        'pincode' => $request->getPost('pincode'),
                        'country' => $request->getPost('country'),
                        'entry_time_ip' => $clientIP
                    ];
                    $insertData = $this->ecoping_webservices->saveUserAddress($data);
                    if ($insertData) {
                        $response = ['status' => 'success'];
                        $address = $this->ecoping_webservices->getUserAddress($applicantMobile, $applicantEmail);
                    } else {
                        $response = ['status' => 'Error:Record Not Inserted'];
                    }
                } else {
                    $response = ['status' => 'Full Name cannot be changed'];
                }
            }
        } else {
            $response = ['status' => 'Session Expired'];
        }
        return $this->response->setJSON($response);
    }

    public function user_verification()
    {
        return render('onlineCopying.user_verification');
    }
}