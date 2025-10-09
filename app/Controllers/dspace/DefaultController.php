<?php
namespace App\Controllers;

class DefaultController extends BaseController
{


    public function __construct()
    {
        parent::__construct();
        //$this->load->model('dspace/Get_details_model');
        $this->load->library('form_validation');
    }
    public function index()
    {
        //echo base_url();
        // Cases community uuid 11b74cad-f31d-445f-b897-0c4e919d1e4c
        $test_bundle_id='9c400732-b606-4943-b8e8-3dac4c259754';
        $data['uploaded_files'] = $this->display_bundle_files($test_bundle_id);
        $this->load->view('templates/admin_header');
        $this->load->view('dspace/upload_to_dspace_view',$data);
        $this->load->view('templates/footer');
    }

    public function jailPetition()
    {
        $this->load->view('templates/admin_header');
        $this->load->view('dspace/jail_petition_upload_view',$data);
        $this->load->view('templates/footer');
    }


    public function create_user_in_dspace7($user_name=null,$firstName=null,$lastName=null,$emailId=null,$password=null)
    {

        if(!empty($_POST['email_id']) && !empty($_POST['password']) && !empty($_POST['user_name']) )
        {
            $user_name=$_POST['user_name'];
            $first_name=isset($_POST['first_name'])?$_POST['first_name']:null;
            $last_name=isset($_POST['last_name'])?$_POST['last_name']:null;
            $email_id=$_POST['email_id'];
            $password=$_POST['password'];
        }
        else
        {
            $user_name=$user_name;
            $first_name=$firstName;
            $last_name=$lastName;
            $email_id=$emailId;
            $password=$password;
        }
        $authorization_bearer_token='Authorization: '.$this->login();

        $metadata='{
          "name":"'.$user_name.'",
          "metadata": {
            "eperson.firstname": [
              {
                "value": "'.$first_name.'",
                "language": null,
                "authority": "",
                "confidence": -1
              }
            ],
            "eperson.lastname": [
              {
                "value": "'.$last_name.'",
                "language": null,
                "authority": "",
                "confidence": -1
              }
            ]
          },
          "canLogIn": true,
          "email": "'.$email_id.'",
          "password": "'.$password.'",
          "requireCertificate": false,
          "selfRegistered": true,
          "type": "eperson"
        }';

        // echo $metadata;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_EPERSONS,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$metadata,
            CURLOPT_HTTPHEADER => array($authorization_bearer_token,"Content-Type: application/json"),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response=json_decode($response,true);

        return($response["email"]);


    }

    public function update_dspace7_user_password($eperson_uuid,$new_password)
    {
        $authorization_bearer_token='Authorization: '.$this->login();
        $curl = curl_init();

        $metadata='[{ "op": "replace", "path": "/password", "value": '.$new_password.'}]';
        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_EPERSONS.$eperson_uuid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS =>$metadata,
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

    }

    public function delete_dspace7_users($user_uuid)
    {
        $authorization_bearer_token='Authorization: '.$this->login();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_EPERSONS.'ba6ac499-8c6b-44b2-b20e-2835fa4a00c3',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function update_user_password_dspace7()
    {

    }
    public function login($dspace_user_id=null,$dspace_user_password=null)
    {

        if(!empty($dspace_user_id) && !empty($dspace_user_password))
        {
            $dspace_userid=$dspace_user_id;
            $dspace_password=$dspace_user_password;
        }
        else
        {
            $dspace_userid=DSPACE_USERID;
            $dspace_password=DSPACE_PASSWORD;
        }
        $check_logged_in_status=$this->login_status();
        if($check_logged_in_status!=1) {
            $headers = [];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => LOGIN_INTO_DSPACE,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER=>true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "user=".$dspace_userid."&password=".$dspace_password,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/x-www-form-urlencoded"
                ),
            ));
            curl_setopt($curl, CURLOPT_HEADERFUNCTION,
                function($curl, $header) use (&$headers)
                {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) // ignore invalid headers
                        return $len;
                    $headers[strtolower(trim($header[0]))][] = trim($header[1]);
                    return $len;
                }
            );
            $response = curl_exec($curl);
            curl_close($curl);
            //  print_r($headers);

            return($headers['authorization'][0]);  //Authorization Bearer Token
        }
    }

    public function login_status()
    {
        $is_authenticated=0;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => CHECK_LOGIN_STATUS,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(AUTHORIZATION_TOKEN),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response=json_decode($response,true);
        //var_dump($response);
        foreach ($response as $key => $value) {
            if($key=='authenticated') {
                //echo $key.':'.$value.'<br>';
                $is_authenticated = $value;
                break;
            }
        }
        // return $is_authenticated; // original code
        return 0; // for testing
    }
    public function collection_search($search_value,$dsoType)
    {
        //echo $search_value.''.$dsoType;exit();
        $authorization_bearer_token='Authorization: '.$this->login();
        //echo  DISCOVER_SEARCH."?query=".$search_value."&dsoType=".$dsoType;
        //exit();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => DISCOVER_SEARCH."?query=".$search_value."&dsoType=".$dsoType,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response=(array)json_decode($response,true);
        //var_dump($response["_embedded"]["searchResult"]["_embedded"]["objects"][0]["_embedded"]["indexableObject"]);

        return($response["_embedded"]["searchResult"]["_embedded"]["objects"][0]["_embedded"]["indexableObject"]["uuid"]);
    }
    public function find_item_collection($item_id)
    {
        $authorization_bearer_token = 'Authorization: ' . $this->login();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_ITEMS.$item_id."/owningCollection",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 100,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response=json_decode($response,true);
        return($response["uuid"]);
    }



    public function item_search($search_value,$dsoType)
    {
        $authorization_bearer_token='Authorization: '.$this->login();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => DISCOVER_SEARCH."?query=".$search_value."&dsoType=".$dsoType,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response=(array)json_decode($response,true);
        //var_dump($response["_embedded"]["searchResult"]["_embedded"]["objects"][0]["_embedded"]["indexableObject"]);

        $item_id=($response["_embedded"]["searchResult"]["_embedded"]["objects"][0]["_embedded"]["indexableObject"]["uuid"]);
        $item_owing_collection_id=$this->find_item_collection($item_id);
        return $item_owing_collection_id;
    }
    public function item_discover_facets($search_value,$title_facet_value=null,$author_facet_value=null,$subject_facet_value=null,$issue_date_facet=null,$dsoType) //not used yet
    {
        //echo $search_value."#".$title_facet_value.'#'.$author_facet_value.'#'.$subject_facet_value.'#'.$issue_date_facet;

        $condition='';
        if(!empty($title_facet_value)) { $condition.="&f.title=$title_facet_value,contains";}
        if(!empty($author_facet_value)) { $condition.="&f.author=$author_facet_value,contains";}
        if(!empty($subject_facet_value)) { $condition.="&f.subject=$subject_facet_value,contains";}
        if(!empty($issue_date_facet)) { $condition.="&f.dateIssued=$issue_date_facet,contains";}

        //echo FACET_SEARCH."?query=".$search_value."&dsoType=".$dsoType.$condition;

        $authorization_bearer_token='Authorization: '.$this->login();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => FACET_SEARCH."?query=".$search_value."&dsoType=".$dsoType.$condition,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $response=(array)json_decode($response,true);

        //var_dump($response["_embedded"]["searchResult"]["_embedded"]["objects"][0]["_embedded"]["indexableObject"]);

        $item_id=($response["_embedded"]["searchResult"]["_embedded"]["objects"][0]["_embedded"]["indexableObject"]["uuid"]);
        $already_created_item_uuid=$this->find_item_collection($item_id);
        return $already_created_item_uuid;
    }


    public function create_collection($diary_id,$parent_community,$metadata)
    {
        //echo "within create collection function".$diary_id."#".$parent_community;
        // exit();
        //echo $parent_community;exit();

        $authorization_bearer_token = 'Authorization: ' . $this->login();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_COLLECTIONS."?parent=".$parent_community,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>$metadata,
            CURLOPT_HTTPHEADER => array($authorization_bearer_token,"Content-Type: application/json"),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        // var_dump($response);
        $response=json_decode($response,true);
        return($response["uuid"]);
    }

    public function aud_viewpoint($metadata,$method)
    {
        $authorization_bearer_token='Authorization: '.$this->login();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_VIEWPOINTS,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST =>$method,
            CURLOPT_POSTFIELDS =>$metadata,
            CURLOPT_HTTPHEADER => array($authorization_bearer_token,"Content-Type: application/json"),
        ));

        curl_exec($curl);
        if (!curl_errno($curl)) {
            $info = curl_getinfo($curl);
            return $info['http_code'];
        }
    }

    public function create_blank_workspace_item($collection_uuid)
    {
        $authorization_bearer_token = 'Authorization: ' . $this->login();
        // echo $authorization_bearer_token;
        $curl = curl_init();
        $metadata="{}";
        //echo AUD_WORKSPACE_ITEM."?projection=full&owningCollection=".$collection_uuid;exit();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_WORKSPACE_ITEM."?projection=full&owningCollection=".$collection_uuid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>$metadata,
            CURLOPT_HTTPHEADER => array($authorization_bearer_token,"Content-Type: application/json"),
        ));

        $response = curl_exec($curl);
        $response=json_decode($response,true);
        curl_close($curl);
        // var_dump($response);
        //echo 'created woskspace item id :'.$response["id"].'#'.$response["_embedded"]["item"]["uuid"];


        return($response["id"].'#'.$response["_embedded"]["item"]["uuid"]);
    }

    public function add_item_metadata($metadata,$item_uuid)
    {
        $authorization_bearer_token = 'Authorization: ' . $this->login();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_ITEMS."$item_uuid",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT =>100,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>$metadata,
            CURLOPT_HTTPHEADER => array($authorization_bearer_token,"Content-Type: application/json"),
        ));

        $response = curl_exec($curl);
        $response=(array)json_decode($response,true);

        curl_close($curl);
        //var_dump($response);
    }
    public function update_item_metadata($metadata,$item_uuid)
    {
        $authorization_bearer_token='Authorization: '.$this->login();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_ITEMS."$item_uuid",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS =>$metadata,
            CURLOPT_HTTPHEADER => array($authorization_bearer_token,"Content-Type: application/json"),
        ));

        $response = curl_exec($curl);
        $response=(array)json_decode($response,true);

        curl_close($curl);
        //var_dump($response);
    }
    private function trim_value(&$value)
    {
        echo $value;
        $value = trim($value);
        $value= preg_replace('/\s+/\n', '', $value);
    }

    public function update_item_metadata_via_patch($metadata_array=null,$metadata_field=null,$item_uuid=null,$metadata_patch_operation=null,$replace_to_position=null,$move_from_position=null,$move_to_position=null)
    {
        //metadata_patch_operation: add,replace,remove and move
        //sample metadata array:

        /*
          [
            {"case_no":"C.A.No.011571-011576/2016","reg_date":"04-09-2010"},
            {"case_no":"S.L.P.(C)...CC No.014152-014156/2010","reg_date":"25-10-2010"},
            {"case_no":"SLP(C) No.031099-031104/2010","reg_date":"25-10-2010"}
        ]

        */


        /* we can save the values as follows also
        [
              {
                "op": "add",
                "path": "/metadata/sc.paperbook.document_groups",
                "value": [{"value":"[{'C.A. No. 011571 - 011576 / 2016 '=>'Reg.Dt.04-09-2010','S.L.P.(C)...CC No. 014152 - 014156 / 2010'=>'Reg.Dt.25-10-2010'}]]","language":"en","authority":null,"confidence":-1 },
                        {"value":"Affidavit 2","language":"en","authority":null,"confidence":0 } ]
              }
        ]

        */

        if(!empty($_POST))
        {
            $metadata_array=$_POST['metadata_array'];
            $metadata_field=$_POST['metadata_field'];
            $item_uuid=$_POST['item_uuid'];
            $metadata_patch_operation=$_POST['metadata_patch_operation'];
            $replace_to_position=$_POST['replace_to_position'];
            $move_from_position=$_POST['move_from_position'];
            $move_to_position=$_POST['move_to_position'];
        }
        else
        {
            $metadata_array=array_walk($_POST['metadata_array'], 'trim_value');
            $metadata_field=$metadata_field;
            $item_uuid=$item_uuid;
            $metadata_patch_operation=$metadata_patch_operation;
            $replace_to_position=$replace_to_position;
            $move_from_position=$move_from_position;
            $move_to_position=$move_to_position;
        }
        if(!empty($metadata_patch_operation) && !empty($item_uuid))
        {
            switch($metadata_patch_operation)
            {
                case('add'):
                    {
                        if(!empty($metadata_array) && !empty($metadata_field) && !empty($item_uuid))
                        {
                            $json = preg_replace('/\r| \n/','\n',trim($metadata_array));
                            $metadata_array = json_decode($json,true);
                            $metadata_size=sizeof($metadata_array);

                            $metadata='[';
                            foreach($metadata_array as $key=>$val)
                            {

                                $row_json=  trim(json_encode($val,JSON_UNESCAPED_SLASHES));
                                $row_json=str_replace('"','\"',$row_json);
                                $row_json=str_replace(" ","/t",$row_json);

                                //echo $row_json;


                                $loop_metadata='{
                                "op": "'.$metadata_patch_operation.'",
                                        "path": "/metadata/'.$metadata_field.'/'.$key.'",
                                        "value": [ { "value": "['.$row_json.']","language":"en","authority":null,"confidence":-1  } ]
                                      }';
                                //var_dump($val);
                                $metadata.=$loop_metadata;
                                if(++$key<$metadata_size)
                                {
                                    $metadata.=',';
                                }

                            }
                            $metadata.=']';
                            echo $metadata;
                        }
                        else
                        {
                            echo "Pls give all require parameter for add operation";
                        }
                        break;
                    }
                case('replace'):
                    {

                        $metadata_array = json_decode($metadata_array,true);
                        $metadata_size=sizeof($metadata_array);

                        if(!empty($metadata_array) && !empty($metadata_field) && !empty($item_uuid) && ($replace_to_position !='' && $replace_to_position !=null)  && $metadata_size ==1)
                        {
                            $val=$metadata_array[0];

                            $row_json=  trim(json_encode($val,JSON_UNESCAPED_SLASHES));
                            $row_json=str_replace('"','\"',$row_json);
                            $row_json=str_replace(" ","/t",$row_json);


                            $metadata='[{
                                        "op": "'.$metadata_patch_operation.'",
                                        "path": "/metadata/'.$metadata_field.'/'.$replace_to_position.'",
                                        "value": [ { "value": "['.$row_json.']","language":"en","authority":null,"confidence":-1  } ]
                                      }]';

                            echo $metadata;

                        }
                        else
                        {
                            echo "Pls give all require parameter for replace operation and metadata array contain only one set of data";
                        }

                        break;
                    }
                case('remove'):
                    {
                        if(!empty($metadata_field) && !empty($item_uuid))
                        {
                            $metadata='[
                                        {
                                        "op": "remove",
                                        "path": "/metadata/'.$metadata_field.'"
                                        }]';

                            echo $metadata;
                        }
                        else
                        {
                            echo "Pls give all require parameter for remove operation";
                        }
                        break;
                    }
                case('move'):
                    {
                        if(!empty($metadata_field) && !empty($item_uuid) && $move_from_position>=0 && $move_to_position>=0)
                        {
                            $metadata='[
                                              {
                                                "op": "move",
                                                "from": "/metadata/'.$metadata_field.'/'.$move_from_position.'",
                                                "path": "/metadata/'.$metadata_field.'/'.$move_to_position.'"
                                              }
                                            ]';
                            echo $metadata;
                        }
                        else
                        {
                            echo "Pls give all require parameter for remove operation";
                        }
                        break;
                    }
                default:
                    {
                        echo "Matadata Patch Operation field Cannot be left blank";
                        break;
                    }
            }
        }
        else
        {
            echo "Please give the consent for Matadata Patch Operation and Item id cannot be left blank";
        }


        $authorization_bearer_token='Authorization: '.$this->login();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_ITEMS."$item_uuid",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_POSTFIELDS =>$metadata,
            CURLOPT_HTTPHEADER => array($authorization_bearer_token,"Content-Type: application/json"),
        ));
        curl_exec($curl);
        if (!curl_errno($curl)) {

            $info = curl_getinfo($curl);
            // var_dump($info);
            echo $info['http_code'];
        }

        curl_close($curl);
        //var_dump($response);
    }
    public function aud_item_metadata($item_uuid,$metadata,$method)
    {
        $authorization_bearer_token='Authorization: '.$this->login();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_ITEMS."$item_uuid",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST =>$method,
            CURLOPT_POSTFIELDS =>$metadata,
            CURLOPT_HTTPHEADER => array($authorization_bearer_token,"Content-Type: application/json"),
        ));

        curl_exec($curl);
        if (!curl_errno($curl)) {

            $info = curl_getinfo($curl);
            //var_dump($info);exit();
            return $info['http_code'];
        }
        //$response=(array)json_decode($response,true);
    }
    private function upload_bitstream($workspace_item_id,$file,$dspace_user_id=null,$dspace_password=null)
    {
        //echo "data from bitstream upload function";
        //var_dump($file);
        //exit();

        if(!empty($dspace_user_id) && !empty($dspace_password))
            $authorization_bearer_token='Authorization: '.$this->login($dspace_user_id,$dspace_password);
        else
            $authorization_bearer_token='Authorization: '.$this->login();


        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => AUD_BITSTREAM_TO_WORKSPACE_ITEMS.$workspace_item_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $file,

            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));

        $response = curl_exec($ch);
        // var_dump($response);exit();

        $response=(array)json_decode($response,true);
        //var_dump($response);

        if (!curl_errno($ch)) {
            $info = curl_getinfo($ch);
            $response_code= $info['http_code'];
        }
        // var_dump($response["sections"]["upload"]["files"]);exit();

        return($response_code.'#'.$response["sections"]["upload"]["files"][0]["uuid"]);
    }
    private function build_data_files($files){
        $data = '';
        $eol = "\r\n";

        $delimiter = '-------------';

        foreach ($files as $name => $content) {
            $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $name . '"' . $eol
                //. 'Content-Type: image/png'.$eol
                . 'Content-Transfer-Encoding: binary'.$eol
            ;

            $data .= $eol;
            $data .= $content . $eol;
        }
        $data .= "--" . $delimiter . "--".$eol;


        return $data;
    }
    public function uploaded_bitstream_with_path($workspace_item_id,$file_path,$dspace_user_id=null,$dspace_password=null)
    {
        //echo $file_path;
        $filenames = array("$file_path");

        $files = array();
        foreach ($filenames as $f){
            $files[$f] = file_get_contents($f);
        }

        $delimiter = '-------------';
        $post_data = $this->build_data_files($files);
        //var_dump($post_data);
        //$filecontent= file_get_contents($file_path,true);
        //echo $filecontent;exit();
        //$file =$this->makeCurlFile($file_path);
        // var_dump($file);exit();



        if(!empty($dspace_user_id) && !empty($dspace_password))
            $authorization_bearer_token='Authorization: '.$this->login($dspace_user_id,$dspace_password);
        else
            $authorization_bearer_token='Authorization: '.$this->login();


        /*  $authorization_bearer_token='Authorization: '.$this->login();
          if(empty($owing_collection))
          {
              $owing_collection="f85ce075-eafd-45df-a6e6-d0cc2c79a963";
              $new_workspace_item_id=$this->create_workspace_item($owing_collection);

          }
          else
              $new_workspace_item_id=45;*/

        $ch = curl_init();


        curl_setopt_array($ch, array(
                CURLOPT_URL => AUD_BITSTREAM_TO_WORKSPACE_ITEMS.$workspace_item_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_HTTPHEADER => array(
                    $authorization_bearer_token,
                    "Content-Type: multipart/form-data; boundary=" . $delimiter,
                    "Content-Length: " . strlen($post_data)))
        );
        //CURLOPT_POSTFIELDS => array('file' => new CURLFILE('file:@///home/ubuntu/blank%20establishments.pdf'))


        $response = curl_exec($ch);
        var_dump($response);exit();

        $response=(array)json_decode($response,true);
        //var_dump($response);

        if (!curl_errno($ch)) {
            $info = curl_getinfo($ch);
            $response_code= $info['http_code'];
        }
        //var_dump($response["sections"]["upload"]["files"]);

        return($response_code.'#'.$response["sections"]["upload"]["files"][0]["uuid"]);
    }

    private function update_submission_process($workspace_item,$metadata,$method)
    {
        // {"op":"add","path":"/sections/traditionalpageone/dc.contributor.author", "value":[{"value":"sca.kbpujari@sci.nic.in"}]}
        $authorization_bearer_token='Authorization: '.$this->login();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_WORKSPACE_ITEM.'/'.$workspace_item,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS =>$metadata,
            CURLOPT_HTTPHEADER => array($authorization_bearer_token,"Content-Type: application/json"),
        ));

        $response = curl_exec($curl);
        $response=(array)json_decode($response,true);

        curl_close($curl);
        //var_dump($response);

    }
    private function move_workspace_item_into_workflow_item($workspace_item_id)
    {

        $authorization_bearer_token='Authorization: '.$this->login();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_WORKFLOW_ITEM."?projection=full",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>AUD_BITSTREAM_TO_WORKSPACE_ITEMS.$workspace_item_id,
            CURLOPT_HTTPHEADER => array($authorization_bearer_token,"Content-Type: text/uri-list"),
        ));



        $response = curl_exec($curl);
        //$response=(array)json_decode($response,true);
        curl_close($curl);
        // var_dump($response);


    }
    private function get_not_indexed_workspaceitem_list()
    {
        $authorization_bearer_token='Authorization: '.$this->login();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_WORKSPACE_ITEM.'?size=50000',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response=(array)json_decode($response,true);
        $response=$response["_embedded"]["workspaceitems"];
        return $response;
    }

    private function get_workspace_item_id_from_item_id($item_id=null)
    {
        $item_uuid=(!empty($_POST['item_id']))?$_POST['item_id']:$item_id;

        $authorization_bearer_token='Authorization: '.$this->login();
       $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SEARCH_WORKSPACE_ITEM.'?uuid='.$item_uuid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));

        $response = curl_exec($curl);
        $response=(array)json_decode($response,true);

        curl_close($curl);
        return($response["id"]);
    }

    private function delete_workspace_item($workspaceitemid=null)
    {
        $workspace_item_id=(!empty($_POST['workspace_item_id']))?$_POST['workspace_item_id']:$workspaceitemid;

        $authorization_bearer_token='Authorization: '.$this->login();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_WORKSPACE_ITEM.'/'.$workspace_item_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));

        curl_exec($curl);
        if (!curl_errno($curl)) {
            $info = curl_getinfo($curl);
            return  $info['http_code'];
        }
    }

    private function delete_item($item_id=null)
    {
        $item_uuid=(!empty($_POST['item_id']))?$_POST['item_id']:$item_id;

        $authorization_bearer_token = 'Authorization: ' . $this->login();
       // echo AUD_ITEMS.$item_uuid;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => AUD_ITEMS.$item_uuid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));

        curl_exec($curl);
        if (!curl_errno($curl)) {
            $info = curl_getinfo($curl);
            return $info['http_code'];
        }
    }

    public function delete_all_not_submitted_workspace_items()
    {
        $date1=date("Y-m-d");
        $get_not_indexed_workspaceitem_list=$this->get_not_indexed_workspaceitem_list();

        if(!empty($get_not_indexed_workspaceitem_list))
        {
            foreach ($get_not_indexed_workspaceitem_list as $workspaceitem)
            {
                $workspace_item_id=$workspaceitem["id"];

                $delete_unprocessed_workspace_item=$this->delete_workspace_item($workspace_item_id);
                echo 'Workspace Item : '.$workspace_item_id.' and its all dependency are successfully deleted.<br>';
            }

        }
        else
        {
            echo 'No Workspace Item left for get deleted .<br>';
        }

    }

    public function dspace_cron_submission_process()
    {
        $date1=date("Y-m-d");

            $get_not_indexed_workspaceitem_list=$this->get_not_indexed_workspaceitem_list();
            //var_dump($get_not_indexed_workspaceitem_list);exit();

            if(!empty($get_not_indexed_workspaceitem_list))
            {
                foreach($get_not_indexed_workspaceitem_list as $workspaceitem)
                {
                    $workspace_item_id=$workspaceitem["id"];
                    $file_upload_status=$workspaceitem['sections']['upload']['files'];

                    $submitter_metadata=$workspaceitem['_embedded']['submitter'];
                    $item_submitter_uuid=$submitter_metadata["uuid"];
                    $item_submitter_name=$submitter_metadata["name"];

                    $item_metadata=$workspaceitem['_embedded']['item'];
                    $uploaded_document_title=$item_metadata["metadata"]["dc.subject"][0]["value"];
                    $uploaded_document_item_uuid=$item_metadata['uuid'];



                    if(!empty($file_upload_status) && !empty($uploaded_document_item_uuid)) // indexing and submission will be done only those items in which at least one file(document)  has been uploaded
                    {

                        /* $submission_process_metadata1="[
         {\"op\":\"add\",\"path\":\"/sections/traditionalpageone/dc.contributor.author\", \"value\":[{\"value\":\"$item_submitter_name\"}]}
         ]";*/
                        $submission_process_metadata2="[
                    {\"op\":\"add\",\"path\":\"/sections/traditionalpageone/dc.contributor.author\", \"value\":[{\"value\":\"$item_submitter_name\"}]},
                    {\"op\":\"add\",\"path\":\"/sections/upload/files/0/metadata/dc.description\", \"value\":[{\"value\":\"$uploaded_document_title\"}]},    
                    {\"op\":\"add\",\"path\":\"/sections/traditionalpageone/dc.date.issued\",\"value\":[{\"value\":\"$date1\",\"language\":null,\"authority\":null,\"display\":\"$date1\",\"confidence\":-1,\"place\":0,\"otherInformation\":null}]},
                    {\"op\":\"add\",\"path\":\"/sections/license/granted\", \"value\": true}
    ]";



                        /*$submission_process_status=$this->update_submission_process($workspace_item_id,$submission_process_metadata1,'PATCH');*/
                        $submission_process_status=$this->update_submission_process($workspace_item_id,$submission_process_metadata2,'PATCH');

                        $move_workspace_to_workflow_status=$this->move_workspace_item_into_workflow_item($workspace_item_id);

                        echo 'Workspace Item : '.$workspace_item_id.' : Submission, Workflow and Indexing process are successfully executed.<br>';

                    }
                    else
                    {

                        $delete_unprocessed_workspace_item=$this->delete_workspace_item($workspace_item_id);
                        if($delete_unprocessed_workspace_item==204)
                            echo 'Workspace Item : '.$workspace_item_id.' and its all dependency are successfully deleted.<br>';
                    }
                }
                $i++;

            }
            else
            {
                echo 'No Workspace Item left for Submission, Workflow and Indexing process .<br>';
            }




    }
    private function makeCurlFile($file){
        $mime = mime_content_type($file);
        $info = pathinfo($file);
        $name = $info['basename'];
        $output = new CURLFile($file, $mime, $name);
        return $output;
    }
    function download_remote_file($file_url, $save_to)
    {
        $content = file_get_contents($file_url);
        file_put_contents($save_to, $content);
    }
    private function download_remote_file_with_curl($file_url, $save_to)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch,CURLOPT_URL,$file_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file_content = curl_exec($ch);
        curl_close($ch);

        $downloaded_file = fopen($save_to, 'w');
        fwrite($downloaded_file, $file_content);
        fclose($downloaded_file);
    }
    private function get_formatted_json_data($metadata_array)
    {
        if (!empty($metadata_array)) {
            $json = preg_replace('/\r| \n/', '\n', trim($metadata_array));
            $metadata_array = json_decode($json, true);
            $metadata_size = sizeof($metadata_array);
            /* sample data output   [
                      {
                          "value": "Union of India or {json}",
                        "language": "en",
                        "authority": null,
                        "confidence": -1
                      },
                      {
                          "value": "NCT OF DELHI  or {json}",
                        "language": "en",
                        "authority": null,
                        "confidence": -1
                      },
                    {
                        "value": "NDMC or {json}",
                        "language": "en",
                        "authority": null,
                        "confidence": -1
                      }
                ]
            */

            $metadata = '[';
            $count=0;
            foreach ($metadata_array as $key => $val) {
                $count++;
                $row_json = trim(json_encode($val, JSON_UNESCAPED_SLASHES));
                $row_json = str_replace('"', '\"', $row_json);
                $row_json = str_replace(" ", "/t", $row_json);

                //echo $row_json;



                $loop_metadata = '{
                                    "value": "[' . $row_json . ']","language":"en","authority":null,"confidence":-1  
                                  }';
                //var_dump($val);
                $metadata .= $loop_metadata;
                if (++$key < $metadata_size) {
                    $metadata .= ',';
                }

            }
            $metadata .= ']';

            if($count<1)
                $metadata=null;

            return $metadata;


        }
    }


    public function data_submission()
    {
        $data=null;
        $transaction_status = 0;
        $delete_response=null;

        $this->form_validation->set_rules("diary_id", "Diray ID.", "trim|required");
        $this->form_validation->set_rules("parent_community", "Parent Community ID", "trim|required");
        $this->form_validation->set_rules("parent", "Parent information", "trim|required");
        $this->form_validation->set_rules("document_id", "Document Id", "trim|required");
        $this->form_validation->set_rules("document_title", "Document Title", "trim|required");
        $this->form_validation->set_rules("document_type", "Document Type", "trim|required");


        if($this->form_validation->run() == FALSE && !isset($_REQUEST['upload'])) {
            $message = '<div class = "alert alert-danger">Diary No and Parent Community are mendatory!</div>';
            $this->session->set_flashdata('msg',$message);
            redirect('dspace');
        } else {


            $ingestion_start=true;
            $document_existing_added_item_id=(!empty($_POST['item_id']))?$_POST['item_id']:null;
            if(!empty($document_existing_added_item_id)) {
                $delete_item_response = $this->delete_item($document_existing_added_item_id);
                if($delete_item_response==204)
                    $ingestion_start=true;
                else {
                    //it may be situation that the item replace request come before its submission process done.
                    $workspace_item_id=$this->get_workspace_item_id_from_item_id($document_existing_added_item_id);
                    if(!empty($workspace_item_id))
                    {
                        $delete_workspace_item_response = $this->delete_workspace_item($workspace_item_id);
                        if($delete_workspace_item_response==204)
                            $ingestion_start=true;
                        else
                            $ingestion_start = false;
                    }
                }
            }
           if($ingestion_start)
           {
               //echo 'Existing Item :'.$document_existing_added_item_id.'has been deleted';
               if(empty($_FILES) ) {
                   $file_path=(!empty($_POST['file']))?$_POST['file']:null;
               }
               $date=date("Y-m-d H:i:s");
               $date1=date("Y-m-d");
               $diary_id=$_POST['diary_id'];
               //collection metadata
               $diary_no=substr($diary_id, 0, -4);
               $diary_year=substr($diary_id, -4);
               $case_year=(!empty($_POST['case_year']))?$_POST['case_year']:null;
               $filing_date=(!empty($_POST['filed_on']))?$_POST['filed_on']:null;
               $registration_date=(!empty($_POST['registered_on']))?$_POST['registered_on']:null;
               $petitioner_name=(!empty($_POST['petitioner_name']))?$_POST['petitioner_name']:null;
               $respondent_name=(!empty($_POST['respondent_name']))?$_POST['respondent_name']:null;
               $case_type_id=(!empty($_POST['case_type_id']))?$_POST['case_type_id']:null;
               $advocate_id=(!empty($_POST['advocate_id']))?$this->get_formatted_json_data($_POST['advocate_id']):null;
               $aor_code=(!empty($_POST['aor_code']))?$this->get_formatted_json_data($_POST['aor_code']):null;
               $case_type_name=(!empty($_POST['case_type_name']))?$_POST['case_type_name']:null;
               $case_type_name_short=(!empty($_POST['case_type_name_short']))?$_POST['case_type_name_short']:null;
               $case_type_acronym=(!empty($_POST['case_type_acronym']))?$_POST['case_type_acronym']:null;
               $case_no_from=(!empty($_POST['case_no_from']))?$_POST['case_no_from']:null;
               $case_no_to=(!empty($_POST['case_no_to']))?$_POST['case_no_to']:null;
               $reg_no_display=(!empty($_POST['reg_no_display']))?$_POST['reg_no_display']:null;
               $registration_numbers=(!empty($_POST['registration_numbers']))?$this->get_formatted_json_data($_POST['registration_numbers']):null;
               $petitioners=(!empty($_POST['petitioners']))?$this->get_formatted_json_data($_POST['petitioners']):null;
               $respondents=(!empty($_POST['respondents']))?$this->get_formatted_json_data($_POST['respondents']):null;
               $petitioner_advocates=(!empty($_POST['petitioner_advocates']))?$this->get_formatted_json_data($_POST['petitioner_advocates']):null;
               $respondent_advocates=(!empty($_POST['respondent_advocates']))?$this->get_formatted_json_data($_POST['respondent_advocates']):null;
               $subject_categories=(!empty($_POST['subject_categories']))?$this->get_formatted_json_data($_POST['subject_categories']):null;
               $lower_court_details=(!empty($_POST['lower_court_details']))?$this->get_formatted_json_data($_POST['lower_court_details']):null;
               $status=(!empty($_POST['status']))?$_POST['status']:null;
               $stage=(!empty($_POST['stage']))?$_POST['stage']:null;
               $update_from_module=(!empty($_POST['update_from_module']))?$_POST['update_from_module']:null;
               $sclsc_diary_no=(!empty($_POST['sclsc_diary_no']))?$_POST['sclsc_diary_no']:null;
               $sclsc_diary_year=(!empty($_POST['sclsc_diary_year']))?$_POST['sclsc_diary_year']:null;
               $sclsc_registration_id=(!empty($_POST['sclsc_registration_id']))?$_POST['sclsc_registration_id']:null;

               //item metadata

               $parent_community=(!empty($_POST['parent_community']))?$_POST['parent_community']:'11b74cad-f31d-445f-b897-0c4e919d1e4c';
               $document_title=$_POST['document_title'];
               $document_type=$_POST['document_type'];
               $parent=$_POST['parent'];
               $document_groups=(!empty($_POST['groups']))?$this->get_formatted_json_data($_POST['groups']):null;
               $document_type_id=(!empty($_POST['document_type_id']))?$_POST['document_type_id']:null;
               $document_hierarchy_level=(!empty($_POST['document_hierarchy_level']))?$_POST['document_hierarchy']:null;
               $document_id=$_POST['document_id'];
               $document_parent_id=$_POST['document_parent_id'];
               $index_page_no_from=$_POST['index_page_no_from'];
               $index_page_no_to=$_POST['index_page_no_to'];
               $display_sequence_number=$_POST['display_sequence_number'];
               $page_count=(!empty($_POST['page_count']))?$_POST['page_count']:null;
               $filed_on=(!empty($_POST['filed_on']))?$_POST['filed_on']:$date;
               $order_dated=(!empty($_POST['order_date']))?$_POST['order_date']:$filed_on;
               $created_on=(!empty($_POST['created_on']))?$_POST['created_on']:$date;
               $updated_on=(!empty($_POST['updated_on']))?$_POST['updated_on']:$date;
               $digital_signatory_name=(!empty($_POST['digital_signatory_name']))?$_POST['digital_signatory_name']:null;
               $digital_signatory_place=(!empty($_POST['digital_signatory_place']))?$_POST['digital_signatory_name']:null;
               $digital_signature_timestamp=(!empty($_POST['digital_signature_timestamp']))?$_POST['digital_signature_timestamp']:$date;
               $digitally_signed_hash_value=(!empty($_POST['digitally_signed_hash_value']))?$_POST['digitally_signed_hash_value']:null;
               $logged_in_user_id=DSPACE_USERID;

               //echo $petitioner_advocates;

               if(empty($advocate_id)) {
                   $post_advocate_id=$_POST['advocate_id'];
                   $advocate_id = "[{\"value\": \"$post_advocate_id\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}]";
               }
               if(empty($aor_code)) {
                   $post_aor_code=$_POST['aor_code'];
                   $aor_code = "[{\"value\": \"$post_aor_code\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}]";
               }
               if(empty($registration_numbers)) {
                   $post_registration_numbers=$_POST['registration_numbers'];
                   $registration_numbers = "[{\"value\": \"$post_registration_numbers\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}]";
               }
               if(empty($petitioners)) {
                   $post_petitioners=$_POST['petitioners'];
                   $petitioners = "[{\"value\": \"$post_petitioners\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}]";
               }
               if(empty($respondents)) {
                   $post_respondents=$_POST['respondents'];
                   $respondents = "[{\"value\": \"$post_respondents\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}]";
               }

               if(empty($petitioner_advocates)) {
                   $post_petitioner_advocates=$_POST['petitioner_advocates'];
                   $petitioner_advocates = "[{\"value\": \"$post_petitioner_advocates\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}]";
               }
               if(empty($respondent_advocates)) {
                   $post_respondent_advocates=$_POST['respondent_advocates'];
                   $respondent_advocates = "[{\"value\": \"$post_respondent_advocates\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}]";
               }
               if(empty($subject_categories)) {
                   $post_subject_categories=$_POST['subject_categories'];
                   $subject_categories = "[{\"value\": \"$post_subject_categories\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}]";
               }
               if(empty($lower_court_details)) {
                   $post_lower_court_details=$_POST['lower_court_details'];
                   $lower_court_details = "[{\"value\": \"$post_lower_court_details\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}]";
               }
               if(empty($document_groups)) {
                   $post_groups=$_POST['groups'];
                   $document_groups = "[{\"value\": \"$post_groups\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}]";
               }



               $collection_metadata="{
            \"type\":{\"value\":\"community\"},   
            \"metadata\":
            {
                \"dc.title\":[{\"language\":null,\"value\":\"$diary_id\"}],               
                \"sc.case.diaryId\":[{\"value\": \"$diary_id\",\"language\": \"\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.diaryNo\":[{\"value\": \"$diary_no\",\"language\": \"\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.diaryYear\":[{\"value\": \"$diary_year\",\"language\": \"\",\"authority\": null,\"confidence\": -1}],
                
                \"sc.petitioner.name\":[{\"value\": \"$petitioner_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.respondent.name\":[{\"value\": \"$respondent_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],                
                \"sc.case.caseTypeId\":[{\"value\": \"$case_type_id\",\"language\": \"\",\"authority\": null,\"confidence\": -1 }],                
                \"sc.case.caseTypeName\":[{\"value\": \"$case_type_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.caseTypeNameShort\":[{\"value\": \"$case_type_name_short\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.caseTypeAcronym\":[{\"value\": \"$case_type_acronym\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.registrationNumbers\":$registration_numbers,
                \"sc.contributor.petitioners\":$petitioners,
                \"sc.contributor.respondents\":$respondents,
                \"sc.contributor.petitionerAdvocates\":$petitioner_advocates,
                \"sc.contributor.respondentAdvocates\":$respondent_advocates,
                 \"sc.case.advocate_id\":$advocate_id,
                \"sc.case.aor_code\":$aor_code,                
                \"sc.contributor.lowerCourtDetails\":$lower_court_details,
                \"sc.case.subjectCategories\":$subject_categories,
                \"sc.case.status\":[{\"value\": \"$status\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.stage\":[{\"value\": \"$stage\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                
                \"sc.date.filed\":[{\"value\": \"$filing_date\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],   
                \"sc.date.registered\":[{\"value\": \"$registration_date\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.caseNoFrom\":[{\"value\": \"$case_no_from\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.caseNoTo\":[{\"value\": \"$case_no_to\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.caseYear\":[{\"value\": \"$case_year\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.regNoDisplay\":[{\"value\": \"$reg_no_display\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
               \"sc.case.sclsc_diary_no\":[{\"value\":\"$sclsc_diary_no\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
\"sc.case.sclsc_diary_year\":[{\"value\":\"$sclsc_diary_year\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
\"sc.case.sclsc_registration_id\":[{\"value\":\"$sclsc_registration_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"dc.description.abstract\":[{\"value\": \"$update_from_module\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"dc.rights\":[{\"language\":null}],
                \"dc.description.tableofcontents\":[{\"language\":null}]                              
            }
        }";


               $authorization_bearer_token = 'Authorization: ' . $this->login();

               $owing_collection_uuid=null;
               $owing_collection_uuid=$this->collection_search($diary_id,'COLLECTION');
               if(empty($owing_collection_uuid))
                   $owing_collection_uuid=$this->item_search($diary_id,'ITEM'); // search collection again if it is already created and not found at first time


               if(empty($owing_collection_uuid)) {

                   $owing_collection_uuid = $this->create_collection($diary_id, $parent_community, $collection_metadata);

               }

               $viewpoint_metadata="{\"targetId\":\"$owing_collection_uuid\",\"targetType\":\"collection\"}";
               $update_viewpoint = $this->aud_viewpoint($viewpoint_metadata,'POST');



               $check_item_entry_already_exist = $this->item_discover_facets($diary_id,$diary_id,null,$document_title,null,'ITEM');

               //echo 'created collection uuid:'.$owing_collection_uuid;


               // collection is created with
               /* write the code segment to check the already added item with multiple parameter like
               and digital_page_no etdiary_no,document_type,physical_page from and to noc.if it is already exist prompt the message "already uploaded" else
               create a workspace item and don't forgrt to add dc.date.issue matadata field, which is mendatory.
              $is_item_already_exist=$this->search_in_items_metadata($diaryNo);
               */


               $aud_method='PUT';
               $workspace_item=$this->create_blank_workspace_item($owing_collection_uuid);
               $workspace_item_id=explode("#", $workspace_item)[0];
               $item_id=explode("#", $workspace_item)[1];

                echo 'created workspace item id:'.$item_id;

               // \"dc.contributor.author\":[{\"language\":null,\"value\":\"$logged_in_user_id\"}],

               $item_metadata="{
            \"type\":{\"value\":\"items\"},
            \"id\":\"$item_id\",
            \"uuid\":\"$item_id\",    
            \"metadata\":
            {  
                \"sc.case.diaryId\":[{\"value\": \"$diary_id\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],                
                \"sc.petitioner.name\":[{\"value\": \"$petitioner_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.respondent.name\":[{\"value\": \"$respondent_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],                
                \"sc.case.caseTypeId\":[{\"value\": \"$case_type_id\",\"language\": \"\",\"authority\": null,\"confidence\": -1 }],                
                \"sc.case.caseTypeName\":[{\"value\": \"$case_type_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.caseTypeNameShort\":[{\"value\": \"$case_type_name_short\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.caseTypeAcronym\":[{\"value\": \"$case_type_acronym\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.registrationNumbers\":$registration_numbers,
                \"sc.contributor.petitioners\":$petitioners,
                \"sc.contributor.respondents\":$respondents,
                \"sc.contributor.petitionerAdvocates\":$petitioner_advocates,
                \"sc.contributor.respondentAdvocates\":$respondent_advocates,
                \"sc.case.advocate_id\":$advocate_id,
                \"sc.case.aor_code\":$aor_code,            
                \"sc.contributor.lowerCourtDetails\":$lower_court_details,
                \"sc.case.subjectCategories\":$subject_categories,
                \"sc.case.status\":[{\"value\": \"$status\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.stage\":[{\"value\": \"$stage\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],                
                \"sc.date.filed\":[{\"value\": \"$filing_date\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],   
                \"sc.date.registered\":[{\"value\": \"$registration_date\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.caseNoFrom\":[{\"value\": \"$case_no_from\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.caseNoTo\":[{\"value\": \"$case_no_to\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.caseYear\":[{\"value\": \"$case_year\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.regNoDisplay\":[{\"value\": \"$reg_no_display\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
               \"sc.case.sclsc_diary_no\":[{\"value\":\"$sclsc_diary_no\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
\"sc.case.sclsc_diary_year\":[{\"value\":\"$sclsc_diary_year\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
\"sc.case.sclsc_registration_id\":[{\"value\":\"$sclsc_registration_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"dc.description.abstract\":[{\"value\": \"$update_from_module\",\"language\": \"en\",\"authority\": null,\"confidence\":-1}],      
                 \"dc.title\":[{\"language\":null,\"value\":\"$document_title\"}], 
                \"dc.type\":[{\"language\":null,\"value\":\"$document_type \"}], 
                \"dc.subject\":[{\"value\":\"$document_title\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}], 
                \"sc.case.documentPageCount\":[{\"value\": \"$page_count\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.paperbook.parent\":[{\"value\": \"$parent\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],  
                \"sc.paperbook.document_groups\":$document_groups, 
                \"sc.paperbook.document_type_id\":[{\"value\":\"$document_type_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
\"sc.paperbook.hierarchy_level\":[{\"value\":\"$document_hierarchy_level\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
\"sc.paperbook.document_id\":[{\"value\":\"$document_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
\"sc.paperbook.document_parent_id\":[{\"value\":\"$document_parent_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
            
                \"sc.paperbook.indexPageNoFrom\":[{\"value\": \"$index_page_no_from\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.paperbook.indexPageNoTo\":[{\"value\": \"$index_page_no_to\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.paperbook.displaySequenceNo\":[{\"value\": \"$display_sequence_number\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.digitalSignature.signatoryName\":[{\"value\": \"$digital_signatory_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.digitalSignature.signatoryPlace\":[{\"value\": \"$digital_signatory_place\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.digitalSignature.updatedOn\":[{\"value\": \"$digital_signature_timestamp\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.digitalSignature.hashvalue\":[{\"value\": \"$digitally_signed_hash_value\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"dc.date.issued\":[{\"value\": \"$order_dated\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.order_date\":[{\"value\": \"$order_dated\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],                
                \"sc.date.created\":[{\"value\": \"$created_on\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.date.updated\":[{\"value\": \"$updated_on\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.sclsc_diary_no\":[{\"value\":\"$sclsc_diary_no\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
\"sc.case.sclsc_diary_year\":[{\"value\":\"$sclsc_diary_year\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
\"sc.case.sclsc_registration_id\":[{\"value\":\"$sclsc_registration_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }] ,
\"dc.contributor.author\":[{\"value\": \"$logged_in_user_id\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],              
            }
             
        }";
               /*
                           $submission_process_metadata="[
                   {\"op\":\"add\",\"path\":\"/sections/traditionalpageone/dc.date.issued\",\"value\":[{\"value\":\"$date1\",\"language\":null,\"authority\":null,\"display\":\"$date1\",\"confidence\":-1,\"place\":0,\"otherInformation\":null}]},
                   {\"op\":\"move\",\"from\":\"/sections/traditionalpageone/dc.contributor.author/1\", \"path\":\"/sections/traditionalpageone/dc.contributor.author/0\"},
                   {\"op\":\"add\",\"path\":\"/sections/upload/files/0/metadata/dc.description\", \"value\":[{\"value\":\"$document_title\"}]},
                   {\"op\":\"add\",\"path\":\"/sections/license/granted\", \"value\": true}
               ]";*/
               $submission_process_metadata1="[          
    {\"op\":\"add\",\"path\":\"/sections/traditionalpageone/dc.contributor.author\", \"value\":[{\"value\":\"itcell@sci.nic.in\"}]}    
    ]";
               $submission_process_metadata2="[
            {\"op\":\"add\",\"path\":\"/sections/upload/files/0/metadata/dc.description\", \"value\":[{\"value\":\"$document_title\"}]},
    {\"op\":\"add\",\"path\":\"/sections/traditionalpageone/dc.contributor.author\", \"value\":[{\"value\":\"itcell@sci.nic.in\"}]},
    {\"op\":\"add\",\"path\":\"/sections/traditionalpageone/dc.date.issued\",\"value\":[{\"value\":\"$date1\",\"language\":null,\"authority\":null,\"display\":\"$date1\",\"confidence\":-1,\"place\":0,\"otherInformation\":null}]},
    {\"op\":\"add\",\"path\":\"/sections/license/granted\", \"value\": true}
    ]";


               /* bitstream upload and update item metadata portion start */
               $filePath='';
               $bitstream_upload_status=0;
               //if (isset($_FILES) && !empty($_FILES) && !empty($item_metadata) && $_FILES['file']['size'] > 10)
               //echo "hello";


               if (!empty($item_metadata))
               {

                   $aud_metadata_status=$this->aud_item_metadata($item_id,$item_metadata,$aud_method);
                  // echo "item metadata updated";
                   if ($aud_metadata_status==200)
                   {
                       //echo "item metadata updated with 200 response code";

                       $transactstatus=1;
                       if(isset($_FILES) && !empty($_FILES))
                       {

                           $post_data = array('file' => new CURLFILE($_FILES['file']['tmp_name']),'name' => $document_title);

                       }
                       elseif(!empty($file_path))
                       {
                           $arrContextOptions=array(
                               "ssl"=>array(
                                   "verify_peer"=>false,
                                   "verify_peer_name"=>false,
                               ),
                           );
                           $file_name=uniqid('file_');
                           //$file_content=file_get_contents($file_path,true);
                           $file_content = file_get_contents($file_path, false, stream_context_create($arrContextOptions));
                           $file_to_save = '/var/tmp/'.$file_name.'.pdf';//save the pdf file on the server
                           $file_put_content_result=file_put_contents($file_to_save, $file_content);
                           //echo '#'.$fpcontent.'#';
                           if((int)($file_put_content_result)>0)
                               $post_data = array('file' => new CURLFILE($file_to_save),'name' => $document_title);
                           else
                           {
                               $post_data=null;
                           }
                       }
                       else
                       {
                           $post_data=null;
                           $transaction_status=0;
                           echo "Pls upload the file";

                           exit();
                       }

                       if(!empty($post_data))
                       {
                           $bitstream_upload=$this->upload_bitstream($workspace_item_id,$post_data);
                           $bitstream_upload_status=explode("#", $bitstream_upload)[0];
                           $bitstream_id=explode("#", $bitstream_upload)[1];
                           //echo 'upload bitstream id'.$bitstream_id;

                           if($bitstream_upload_status == 201 || $bitstream_upload_status == 200 ) {
                               unlink($file_to_save);
                               if(!empty($file_path))
                               {
                                   $unlink_status=unlink($file_to_save);
                                   //echo $unlink_status;
                               }
                               $transaction_status = 1;
                               // echo "start submission process";
                               //$submission_process_status=$this->update_submission_process($workspace_item_id,$submission_process_metadata1,'PATCH');
                               // $submission_process_status=$this->update_submission_process($workspace_item_id,$submission_process_metadata2,'PATCH');
                               //$move_workspace_to_workflow_status=$this->move_workspace_item_into_workflow_item($workspace_item_id);
                           }
                           else
                               $transaction_status=0;
                       }
                       else
                           $transaction_status=0;
                   }
                   else
                       $transaction_status=0;
               }
               else {
                   $transaction_status=0;
               }
               //echo 'final status eeeeee:'.$transaction_status;
               if($transaction_status==1)
               {
                   //return(array('collection_id'=>$owing_collection_uuid,'item_id'=>$item_id,'bitstream_id'=>$bitstream_id));
                   echo json_encode((array('collection_id'=>$owing_collection_uuid,'item_id'=>$item_id,'bitstream_id'=>$bitstream_id)));
               }
               else
               {
                   $delete_unprocessed_workspace_item=$this->delete_workspace_item($workspace_item_id);
                   if($delete_unprocessed_workspace_item==204)
                       echo 'request not processed successfully i.e Workspace Item : '.$workspace_item_id.' and its all dependency are successfully deleted.<br>';
               }

               /* bitstream upload and update item metadata portion end */


               /*
                           if($transaction_status==1)
                               $message = '<div class = "alert alert-success">Casefile updated successfully! </div>';

                           else
                               $message = "<div class = \"alert alert-danger\">Some error occured while data submission!</div>";


                           $this->session->set_flashdata('msg', $message);
                           redirect('dspace');*/

           }
           else
           {
               echo $document_existing_added_item_id.' item id not deleted with response code:'.$delete_response;
           }



        }
    }

    public function display_bitstream_content($bitstream_id)
    {
        $authorization_bearer_token='Authorization: '.$this->login();
        //echo $bitstream_id;
        $file_path=AUD_BITSTREAMS.$bitstream_id."/content";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $file_path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        header('Content-type: application/pdf');
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        echo $response;
    }

    public function display_bundle_files($bundle_uuid)   // function to display all files within one Bundle(i.e can be used for display all pdf(documents) uploaded within one case paper book)
    {
        //58d7e676-84a0-4a6a-a160-f19ff7727a76 -- bundle_uuid for testing
        //echo $bundle_uuid.'<br>';
        $authorization_bearer_token='Authorization: '.$this->login();
        //echo $authorization_bearer_token.'<br>';
        $file_path=AUD_BUNDLES.$bundle_uuid."/bitstreams";
        //echo $file_path;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $file_path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $response=(array)json_decode($response,true);
        $response=$response["_embedded"]["bitstreams"];

        return $response;
    }

    public function get_item_original_bundle_uuid($item_uuid)
    {

        $item_original_bundle_id='';

        $authorization_bearer_token='Authorization: '.$this->login();
        $file_path=AUD_ITEMS.$item_uuid."/bundles";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $file_path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $response=(array)json_decode($response,true);
        $response=$response["_embedded"]["bundles"];


        foreach ($response as  $row)
        {
            if($row['name']=='ORIGINAL')
                $item_original_bundle_id=$row["uuid"];
        }
        //echo 'item uuid:'.$item_uuid.'bundle_original_id'.$item_original_bundle_id.'<br>';
        //echo $item_original_bundle_id;

        return  $item_original_bundle_id;
    }
    public function item_original_bundle_uuid($item_uuid)
    {
        $item_orginal_bundle_uuid=$this->get_item_original_bundle_uuid($item_uuid);
        echo $item_orginal_bundle_uuid;

    }
    private function get_bundle_bitstream_uuid($bundle_uuid)
    {
        $bitstream_uuid='';

        $authorization_bearer_token='Authorization: '.$this->login();
        $file_path=AUD_BUNDLES.$bundle_uuid."/bitstreams";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $file_path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array($authorization_bearer_token),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $response=(array)json_decode($response,true);

        $response=$response["_embedded"]["bitstreams"];
        $bitstream_uuid=$response[0]["uuid"];

        return  $bitstream_uuid;
    }
    public function bundle_bitstream_uuid($bundle_uuid)
    {
        $original_bundle_bitstream_uuid=$this->get_bundle_bitstream_uuid($bundle_uuid);
        echo $original_bundle_bitstream_uuid;
    }


    public function generatePaperBook($datas, $parent = '', $limit=0){


        if($limit > 2000) return ''; // Make sure not to have an endless recursion
        $tree = '<tr>';
        static $top_level_element_id;
        static $sub_parent_id;
        $count=0;
        $main_paper_book_count=0;
        //var_dump($datas);
        $doc_url=base_url().'dspace/DefaultController/display_bitstream_content/';

        for($i=0, $ni=count($datas); $i < $ni; $i++){

            if($datas[$i]["_embedded"]["indexableObject"]["metadata"]["sc.paperbook.document_parent_id"][0]["value"] == '' || $i>0)
            {

                if($datas[$i]["_embedded"]["indexableObject"]["metadata"]["sc.paperbook.document_parent_id"][0]["value"]==$parent)
                {
                    $main_paper_book_count++;
                    $tree .= '<tr>';
                    $tree .= '<td colspan="6"><strong>'.$main_paper_book_count.'. '.$datas[$i]["_embedded"]["indexableObject"]["metadata"]["dc.subject"][0]["value"].' </strong></td>';
                    $top_level_element_id=$datas[$i]["_embedded"]["indexableObject"]["uuid"];
                }
                else
                {
                    // echo 'Top Level Element is:'.$top_level_element_id.'<br>';
                    // echo $datas[$i]["_embedded"]["indexableObject"]["metadata"]["sc.paperbook.document_parent_id"][0]["value"].'#'.$top_level_element_id.'<br>';
                    if($datas[$i]["_embedded"]["indexableObject"]["metadata"]["sc.paperbook.document_parent_id"][0]["value"]==$top_level_element_id)
                    {

                        $tree .= '<tr>';
                        $tree .= '<td>&nbsp;</td><td colspan="5"><strong>';
                        $tree .= $datas[$i]["_embedded"]["indexableObject"]["metadata"]["dc.subject"][0]["value"].'</strong></td>';
                        $count = 0;
                    }
                    else
                    {
                        $item_uuid=$datas[$i]["_embedded"]["indexableObject"]["uuid"];
                        $item_orginal_bundle_uuid=$this->get_item_original_bundle_uuid($item_uuid);
                        $original_bundle_bitstream_uuid=$this->get_bundle_bitstream_uuid($item_orginal_bundle_uuid);

                        if($count==1)
                        {

                            // $sub_parent_id=$datas[$i]["_embedded"]["indexableObject"]["uuid"];
                            $sub_parent_id=$datas[$i]["_embedded"]["indexableObject"]["metadata"]["sc.paperbook.document_parent_id"][0]["value"];
                        }
                        //http:10.25.78.22:91/dspace/DefaultController/display_bitstream_content/
                        $tree .= '<tr>';
                        $tree .= '<td width="5%" colspan="3" align="right">'.($count+1).'.</td><td><a href='.$doc_url.$original_bundle_bitstream_uuid.' target=\'_blank\'>';

                        $tree .= $datas[$i]["_embedded"]["indexableObject"]["metadata"]["dc.subject"][0]["value"];
                        $tree .= '</td><td>'.$datas[$i]["_embedded"]["indexableObject"]["metadata"]["sc.paperbook.indexPageNoFrom"][0]["value"].'</td><td>'.$datas[$i]["_embedded"]["indexableObject"]["metadata"]["sc.paperbook.indexPageNoTo"][0]["value"].'</td>';


                        // echo 'document parent id : '.$datas[$i]["_embedded"]["indexableObject"]["metadata"]["sc.paperbook.document_parent_id"][0]["value"].'=='.$sub_parent_id.'document self id :'.$item_uuid.'<br>';

                        if($datas[$i]["_embedded"]["indexableObject"]["metadata"]["sc.paperbook.document_parent_id"][0]["value"]==$sub_parent_id )
                            $count++;
                        else
                            $count = 1;
                    }
                }
                //$tree .= $this->generatePaperBook($datas, $datas[$i]["_embedded"]["indexableObject"]["metadata"]["sc.paperbook.document_id"][0]["value"], $limit++);
            }
            $tree .= '</tr>';
        }




        return $tree;
    }
    public function search_case_document($searchText,$filters,$searchIn,$size=5000)
    {

        // echo "http://10.25.78.26:39321/server/api/discover/search/objects?query=sca.kbpujari@sci.nic.in&dsoType=ITEM&f.author=sca.kbpujari@sci.nic.in,equals&f.subject=Record";exit();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => DISCOVER_SEARCH."?query=$searchText&dsoType=$searchIn&size=$size&sort=dc.date.accessioned,asc",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT =>100,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response=(array)json_decode($response,true);

    }
    public function search_and_display_cases_paperbook_docs($search_text=null,$diary_no=null,$aor_code=null,$bar_id=null,$advocate_name=null,$mobile=null,$email=null,$scope=null)
    {

        //http://10.25.78.26:39321/server/api/discover/search/objects?query=sca.kbpujari@sci.nic.in&dsoType=ITEM&f.author=sca.kbpujari@sci.nic.in,equals&f.subject=Record
        //http://10.25.78.26:39321/server/api/discover/search/objects?query=2020-09-14&dsoType=ITEM
        //http://10.25.78.26:39321/server/api/discover/search/facets?query=79922020&dsoType=ITEM&f.title=Record,contains&f.author=pujari, K B,equals
        $search_url='';
        // var_dump($_POST);


        $diary_no=(!empty($_POST['diary_no'])?$_POST['diary_no']:null);
        $search_text=(!empty($_POST['search_text'])?str_replace(' ','%20',$_POST['search_text']):null);

        $aor_code=(!empty($_POST['aor_code'])?$_POST['aor_code']:$aor_code);
        $bar_id=(!empty($_POST['bar_id'])?$_POST['bar_id']:$bar_id);
        $advocate_name=(!empty($_POST['advocate_name'])?str_replace(' ','%20',$_POST['advocate_name']):$advocate_name);
        /*$mobile=(!empty($_POST['mobile_no'])?$_POST['mobile_no']:$mobile);
        $email=(!empty($_POST['email_id'])?$_POST['email_id']:$email);*/
        $scope=(!empty($_POST['scope'])?$_POST['scope']:$scope);



        if(empty($diary_no))
        {

            $search_url=DISCOVER_SEARCH.'?query='.$search_text.'&dsoType=Item&f.subject='.$search_text;
        }
        else
        {
            $search_url=DISCOVER_SEARCH.'?query='.$diary_no.'&dsoType=Item&f.subject='.$search_text;
        }
        //http://10.25.78.26:39321/server/api/discover/search/objects?query=Synopsis&scope=11b74cad-f31d-445f-b897-0c4e919d1e4c&dsoType=Item&f.advocates=97,contains&f.advocates=72,contains&f.advocates=BHARGAVA V. DESAI,contains&sort=dc.date.accessioned,asc
        if(!empty($aor_code))
            $search_url.='&f.advocates='.$aor_code.',contains';
        if(!empty($bar_id))
            $search_url.='&f.advocates='.$bar_id.',contains';
        if(!empty($advocate_name))
            $search_url.='&f.advocates='.$advocate_name.',contains';
        if(!empty($mobile))
            $search_url.='&f.advocates='.$mobile.',contains';
        if(!empty($email))
            $search_url.='&f.advocates='.$email.',contains';
        if(!empty($scope))
            $search_url.='&scope='.$scope;


        $search_url.='&page=0&size=100000&sort=dc.date.accessioned,asc';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $search_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

        $response=(array)json_decode($response,true);
        $response=$response["_embedded"]['searchResult']['_embedded']['objects'];
        curl_close($curl);
        echo(json_encode($response));
    }

    public function get_collection_all_items($diary_id=null)
    {

        $diaryId='';
        if(empty($diary_id))
        {
            $diaryId=$_POST['diaryId'];
        }
        else
            $diaryId=$diary_id;

        if(!empty($diaryId))
        {

            //var_dump($_POST);
            // echo DISCOVER_SEARCH."?query=$diaryId&dsoType=ITEM&size=50000&sort=dc.date.accessioned,asc";


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => DISCOVER_SEARCH."?query=$diaryId&dsoType=ITEM&size=50000&sort=dc.date.accessioned,asc",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT =>100,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $response=(array)json_decode($response,true);
            $response=$response["_embedded"]["searchResult"]["_embedded"]["objects"];
            $case_paper_book=$this->generatePaperBook($response);
            // echo "$case_paper_book";



            if(!empty($response))
            {
                //$response=asort($response);

                $count=0;
                $response_html='';
                $response_header_html="<div class=\"col-md-12 col-sm-12 col-xs-12 table-responsive\">
                    <table border='1'  id=\"datatable-responsive\" class=\"table table-striped table-bordered dt-responsive nowrap uk-table uk-table-divider\" cellpadding='6' cellspacing=\"0\" width=\"100%\" style='font-size: 18px;border-color:GRAY>
                        <thead>
                        <tr class=\"success\">
                            <th width= align='left'>&nbsp;</th>
                            <th width= align='left'>&nbsp;</th>
                             <th width= align='left'>&nbsp;</th>
                            <th width=\"3%\" align='left'>#</th>
                            <th align='left' >Particulars</th>
                            <th align='left' style='float:right'>From Page</th>
                            <th align='left'>Upto Page</th>                            
                        </tr>
                        </thead>
                        <tbody>";
                $response_html1=$case_paper_book;
                $sr_no=0;

                /*$response_html1.="<tr>";
                $response_html1.="<td colspan='6'>Main Paper Book</td>";
                $response_html1.="</tr>";

                $response_html1.="<tr>";
                $response_html1.="<td>&nbsp;</td>";
                $response_html1.="<td colspan='5'>Index of Content</td>";
                $response_html1.="</tr>";


                foreach ($response as $row)
                {
                    $item_uuid=$row["_embedded"]["indexableObject"]["uuid"];
                    $row=$row["_embedded"]["indexableObject"]["metadata"];

                    //echo $row["sc.paperbook.parent"][0]["value"].'<br>';

                    $parent_content=explode(">>",trim($row["sc.paperbook.parent"][0]["value"]));
                    $parent_content1=$parent_content[0];
                    $parent_content2=$parent_content[1];
                    //echo $parent_content1.'#'.$parent_content2;
                    // var_dump($parent_content);

                    $item_orginal_bundle_uuid=$this->get_item_original_bundle_uuid($item_uuid);
                    $original_bundle_bitstream_uuid=$this->get_bundle_bitstream_uuid($item_orginal_bundle_uuid);

                    $sr_no++;
                    $response_html1.="<tr>";
                    $response_html1.="<td>&nbsp;</td>";
                    $response_html1.="<td>&nbsp;</td>";
                    $response_html1.="<td>".$sr_no."</td>";
                    $response_html1.="<td><a href='/dspace/DefaultController/display_bitstream_content/$original_bundle_bitstream_uuid' target='_blank'>".$row["dc.subject"][0]["value"]."</a></td>";
                    $response_html1.="<td>".$row["sc.paperbook.indexPageNoFrom"][0]["value"]."</td>";
                    $response_html1.="<td>".$row["sc.paperbook.indexPageNoTo"][0]["value"]."</td>";
                    $response_html1.="</tr>";
                    //var_dump($row["_embedded"]["indexableObject"]["id"]);

                    $count++;
                }*/
                $response_footer_html="</tbody></table></div>";
                $response_html.=$response_header_html.$response_html1.$response_footer_html;
            }
            else
            {
                $response_html='<h4>Data Not Found...</h4>';
            }
        }
        else
        {
            $response_html='<h4>Data Not Found...</h4>';
        }


        echo $response_html;
        //echo $count;
    }

    public function get_jail_petition_collection_all_items($registration_id=null)
    {

        $diaryId='';
        if(empty($registration_id))
        {
            $registrationId=$_POST['registrationId'];
        }
        else
            $registrationId=$registration_id;


        $curl = curl_init();
        if(!empty($registrationId))
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => DISCOVER_SEARCH."?query=$registrationId&dsoType=ITEM&size=50000&sort=dc.date.accessioned,asc",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT =>100,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $response=(array)json_decode($response,true);
            $response=$response["_embedded"]["searchResult"]["_embedded"]["objects"];

            if(!empty($response))
            {
                //$response=asort($response);

                $count=0;
                $response_html='';
                $response_header_html="<div class=\"col-md-12 col-sm-12 col-xs-12 table-responsive\">
                    <table border='1'  id=\"datatable-responsive\" class=\"table table-striped table-bordered dt-responsive nowrap uk-table uk-table-divider\" cellpadding='6' cellspacing=\"0\" width=\"100%\" style='font-size: 18px;border-color:GRAY>
                        <thead>
                        <tr class=\"success\">
                            <th width= align='left'>&nbsp;</th>
                            <th width= align='left'>&nbsp;</th>
                             <th width= align='left'>&nbsp;</th>
                            <th width=\"3%\" align='left'>#</th>
                            <th align='left' >Particulars</th>
                            <th align='left' style='float:right'>From Page</th>
                            <th align='left'>Upto Page</th>                            
                        </tr>
                        </thead>
                        <tbody>";

                $sr_no=0;

                $response_html1="<tr>";
                $response_html1.="<td colspan='6'>Main Paper Book</td>";
                $response_html1.="</tr>";

                $response_html1.="<tr>";
                $response_html1.="<td>&nbsp;</td>";
                $response_html1.="<td colspan='5'>Index of Content</td>";
                $response_html1.="</tr>";


                foreach ($response as $row)
                {
                    $item_uuid=$row["_embedded"]["indexableObject"]["uuid"];
                    $row=$row["_embedded"]["indexableObject"]["metadata"];

                    //echo $row["sc.paperbook.parent"][0]["value"].'<br>';

                    $parent_content=explode(">>",trim($row["sc.paperbook.parent"][0]["value"]));
                    $parent_content1=$parent_content[0];
                    $parent_content2=$parent_content[1];
                    //echo $parent_content1.'#'.$parent_content2;
                    // var_dump($parent_content);

                    $item_orginal_bundle_uuid=$this->get_item_original_bundle_uuid($item_uuid);
                    $original_bundle_bitstream_uuid=$this->get_bundle_bitstream_uuid($item_orginal_bundle_uuid);

                    $sr_no++;
                    $response_html1.="<tr>";
                    $response_html1.="<td>&nbsp;</td>";
                    $response_html1.="<td>&nbsp;</td>";
                    $response_html1.="<td>".$sr_no."</td>";
                    $response_html1.="<td><a href='/dspace/DefaultController/display_bitstream_content/$original_bundle_bitstream_uuid' target='_blank'>".$row["dc.subject"][0]["value"]."</a></td>";
                    $response_html1.="<td>".$row["sc.paperbook.indexPageNoFrom"][0]["value"]."</td>";
                    $response_html1.="<td>".$row["sc.paperbook.indexPageNoTo"][0]["value"]."</td>";
                    $response_html1.="</tr>";
                    //var_dump($row["_embedded"]["indexableObject"]["id"]);

                    $count++;
                }
                $response_footer_html="</tbody></table></div>";
                $response_html.=$response_header_html.$response_html1.$response_footer_html;
            }
            else
            {
                $response_html='<h4>Data Not Found...</h4>';
            }
        }
        else
        {
            $response_html='<h4>Data Not Found...</h4>';
        }

        echo $response_html;
        //echo $count;
    }
    public function update_solr_indexes($url)
    {

        //[dspace]/bin/dspace update-discovery-index [-cbhf[r <item handle>]]
        //[dspace]/bin/dspace update-discovery-index -o

    }

    public function create_sub_community($parent_community_uuid,$sub_community_name)
    {


    }



    public function delete_test_data()
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->truncate('bundle2bitstream');

        //$this->db->where("1=1")->delete('public.bundle2bitstream')->where("1=1");
        /* $this->db->delete('bitstream');
         $this->db->delete('item2bundle');
         $this->db->delete('bundle');
         $this->db->delete('workspaceitem');
         $this->db->delete('collection2item');
         $this->db->delete('item');
         $this->db->delete('handle');
         $this->db->delete('collection2item');
         $this->db->delete('community2collection');
         $this->db->delete('collection');
         $this->db->delete('mytable');*/

        $this->db->trans_complete(); # Completing transaction
    }

    //dspace 4 code for e-copying:start
    public function mydspace($login_cookie)
    {

        if(empty($login_cookie))
        {
            $get_password_login_cookie=$this->dspace4_get_session_cookie();
        }
        else {
            $get_password_login_cookie = $login_cookie;
        }
        $check_logged_in_status=0;

        $ch = curl_init(LOGIN_DSPACE_4.'/mydspace');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, GET);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, array(
            "Cookie: $get_password_login_cookie"
        ));


        $result = curl_exec($ch);

        curl_close($ch);
    }

    public function dspace4_get_session_cookie()
    {
        $ch = curl_init(LOGIN_DSPACE_4.'/password-login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, GET);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        $result = curl_exec($ch);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);        // get cookie
        $cookies = array();
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        return($cookies["JSESSIONID"]);
    }
    public function dspace4_login()
    {
        $get_password_login_cookie=$this->dspace4_get_session_cookie();
        $check_logged_in_status=0;

        $ch = curl_init(LOGIN_DSPACE_4.'/password-login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, POST);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "login_email=".DSPACE4_LOGIN_ID."&login_password=".DSPACE4_PASSWORD."&login_submit=Log+In");
        curl_setopt($ch, CURLOPT_HTTP_VERSION, array(
            "Content-Type: application/x-www-form-urlencoded",
            "Cookie: $get_password_login_cookie"
        ));

        $result = curl_exec($ch);
        curl_close($ch);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);        // get cookie
        $cookies = array();
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        return $cookies["JSESSIONID"].'#'.$get_password_login_cookie;
    }



    public function search_cases_in_dspace4($case_type,$case_no,$case_year,$order_date=null,$judge_name=null,$petitioner_name=null,$respondent_name=null,$petitioner_advocate_name=null,$respondent_advocate_name=null,$lower_forum=null)
    {

        $post_password_login_cookie=$this->dspace4_login();
        $all_cookies=explode('#',$post_password_login_cookie,2);
        $response_cookie_after_successful_login= $all_cookies[0];

        $this->mydspace($response_cookie_after_successful_login);


        if(!empty($response_cookie_after_successful_login))
        {

            $caseNo=$case_no;
            $caseYear=$case_year;
            $caseType=$case_type;
            if(isset($_POST) && !empty($_POST)) {
                $this->form_validation->set_rules("case_no", "Case No", "trim|required");
                $this->form_validation->set_rules("case_type", "Case Type", "trim|required");
                $this->form_validation->set_rules("case_year", "Case Year", "trim|required");

                if ($this->form_validation->run() == FALSE) {
                    $message = '<div class = "alert alert-danger">Case No, Case Year and Case Type are Mandatory</div>';
                    $this->session->set_flashdata('msg', $message);
                    redirect('dspace');
                } else {
                    $caseNo=$_POST["case_no"];
                    $caseType=$_POST["case_type"];
                    $caseNo=$_POST["case_year"];
                }
            }
            else
            {
                $caseNo=$case_no;
                $caseYear=$case_year;
                $caseType=$case_type;
            }
            $handle_id=null;
            $curl = curl_init();
            // echo "http://10.40.189.152:8080/sc/simple-search?query=title:$caseNo%20AND%20dateIssued:$caseYear&filtername=CaseType&filterquery=$caseType&filtertype=equals";


            curl_setopt_array($curl, array(
                CURLOPT_URL => DSPACE_4_SERVER."sc/simple-search?query=title:$caseNo%20AND%20dateIssued:$caseYear&filtername=CaseType&filterquery=$caseType&filtertype=equals",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "Cookie: JSESSIONID=$response_cookie_after_successful_login"
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            $regex = "(\:[0-9]{2,5})?"; // Port
            $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
            $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
            $regex .= "(a#handle[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor

            $matches = array();
            $pattern = "/$regex/";
            preg_match_all($pattern, $response, $matches);
            $handle_id=implode("<br>", array_values(preg_grep('/\bhandle\b/i', $matches[0])));

            $this->get_item_handle_pdf_list($handle_id,null,$response_cookie_after_successful_login);
        }

    }
    public function get_item_handle_pdf_list($handle_id1,$handle_id2=null,$cookie=null)
    {
        // echo "http://10 exit();
        if(!empty($handle_id2))
            $url=DSPACE_4_SERVER.$handle_id1."/".$handle_id2;
        else
            $url=DSPACE_4_SERVER.$handle_id1;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 50,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cookie: JSESSIONID=$cookie"
            )
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $first_step = explode( '<div class="panel panel-info">' , $response );
        $second_step = explode("</div>" , $first_step[1] );
        // var_dump($second_step);

        $final_result=str_replace("/sc/bitstream",DSPACE_4_SERVER."/sc/bitstream",$second_step[1]);
        $final_result=str_replace("href","class='showDspacePDF' data-path",$final_result);
        $final_result=str_replace("primary","info",$final_result);
        $final_result=str_replace("View/Open","Split",$final_result);
        //$final_result=str_replace("</td></tr>","</td><td class=\"standard\" align=\"center\"><a  class='open-pdf btn-link' data-toggle='modal' href='#splitPDFDialog' data-id=''>Split PDF</a></td></tr>",$final_result);

        echo $final_result;
    }
    public function display_dspace4_bitstream_content($data1,$data2,$data3,$data4)
    {
        //echo $data1.'#'.$data2.'#'.$data3.'#'.$data4;
        //exit();
        $file_url=DSPACE_4_SERVER.'/sc/bitstream/'.$data1.'/'.$data2.'/'.$data3.'/'.$data4;
        //extract($_POST);
        //exit();

        $post_password_login_cookie=$this->dspace4_login();
        $this->mydspace(null);
        $all_cookies=explode('#',$post_password_login_cookie,2);
        $response_cookie_after_successful_login= $all_cookies[0];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $file_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array("Cookie: JSESSIONID=$response_cookie_after_successful_login"),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        //var_dump($response);
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/pdf');
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        echo $response;
    }

    //dspace 4 code for e-copying:end


    //e-prison project --start

    public function data_submission_eprison()
    {

        if(!empty($_FILES) )
        {

            //$filecontentPSPDF = file_get_contents($_FILES['file']['tmp_name']);

            // Eprison Connect - main community - uuid:2be365ac-a2af-490a-a032-322a32674e6a
            // "Jail Petitions" sub-community of "Eprison Connect" uuid:075dc6a7-869e-42d2-88ee-eb55b69d46db
            $data=null;
            $transaction_status = 0;

            $this->form_validation->set_rules("registration_id", "Registration ID.", "trim|required");
            $this->form_validation->set_rules("parent_community", "Parent Community ID", "trim|required");
            $this->form_validation->set_rules("document_title", "Document Title", "trim|required");
            $this->form_validation->set_rules("ref_user_type_id", "Document Uploaded by user type", "trim|required");
            $this->form_validation->set_rules("eprison_logged_in_user_id", "Logged in User E-prision ID", "trim|required");



            if($this->form_validation->run() == FALSE) {
                echo "data validation failed";exit();
                $message = '<div class = "alert alert-danger">Diary No and Parent Community are mendatory!</div>';
                $this->session->set_flashdata('msg',$message);
                redirect('dspace');
            } else {
                //echo 'data validation SUCCESS';
                $date=date("Y-m-d H:i:s");
                $date1=date("Y-m-d");

                //jail_petition_related_metadata_fields
                $parent_community=(!empty($_POST['parent_community']))?$_POST['parent_community']:'075dc6a7-869e-42d2-88ee-eb55b69d46db';
                $registration_id=$_POST['registration_id'];
                $document_title=$_POST['document_title'];
                $eprison_logged_in_user_id=$_POST['eprison_logged_in_user_id'];
                $eprison_logged_in_user_email_id=(!empty($_POST['eprison_logged_in_user_email_id']))?$_POST['eprison_logged_in_user_email_id']:'itcell@sci.nic.in';
                $eprison_logged_in_user_first_name=(!empty($_POST['first_name']))?$_POST['first_name']:null;
                $eprison_logged_in_user_last_name=(!empty($_POST['last_name']))?$_POST['last_name']:null;

                $efiling_for_type_id=(!empty($_POST['efiling_for_type_id']))?$_POST['efiling_for_type_id']:null;
                $efiling_no=(!empty($_POST['efiling_no']))?$_POST['efiling_no']:null;
                $efiling_year=(!empty($_POST['efiling_year']))?$_POST['efiling_year']:null;
                $efiling_type_id=(!empty($_POST['efiled_type_id']))?$_POST['efiled_type_id']:null;
                $jail_code=(!empty($_POST['jail_code']))?$_POST['jail_code']:null;
                $jail_name=(!empty($_POST['jail_name']))?$_POST['jail_name']:null;
                $registration_date=(!empty($_POST['jail_petition_date']))?$_POST['jail_petition_date']:null;
                $case_type_id=(!empty($_POST['sc_case_type_id']))?$_POST['sc_case_type_id']:null;
                $earlier_applied=(!empty($_POST['earlier_applied']))?$_POST['earlier_applied']:null;
                $doc_id=(!empty($_POST['doc_id']))?$_POST['doc_id']:null;
                $pdf_id=(!empty($_POST['pdf_id']))?$_POST['pdf_id']:null;
                $index_no=(!empty($_POST['index_no']))?$_POST['index_no']:null;
                $document_type_id=(!empty($_POST['document_type_id']))?$_POST['document_type_id']:null;
                $sub_document_type_id=(!empty($_POST['sub_document_type_id']))?$_POST['sub_document_type_id']:null;
                $file_name=(!empty($_POST['fil_name']))?$_POST['fil_name']:null;
                $file_path=(!empty($_POST['file_path']))?$_POST['file_path']:null;
                $index_page_no_from=(!empty($_POST['index_page_no_from']))?$_POST['index_page_no_from']:null;
                $index_page_no_to=(!empty($_POST['index_page_no_to']))?$_POST['index_page_no_to']:null;
                $display_sequence_number=(!empty($_POST['page_no']))?$_POST['page_no']:null;
                $no_of_copies=(!empty($_POST['no_of_copies']))?$_POST['no_of_copies']:null;
                $file_size=(!empty($_POST['file_size']))?$_POST['file_size']:null;
                $doc_hashed_value=(!empty($_POST['doc_hashed_value']))?$_POST['doc_hashed_value']:null;
                $is_active=(!empty($_POST['is_active']))?$_POST['is_active']:null;
                $ref_user_type_id=(!empty($_POST['ref_user_type_id']))?$_POST['ref_user_type_id']:null;
                $created_on=(!empty($_POST['created_on']))?$_POST['created_on']:$date;
                $updated_on=(!empty($_POST['updated_on']))?$_POST['updated_on']:$date;
                $sclsc_diary_id=(!empty($_POST['sclsc_diary_id']))?$_POST['sclsc_diary_id']:null;
                $sclsc_file_id=(!empty($_POST['sclsc_file_id']))?$_POST['sclsc_file_id']:null;
                $sclsc_registration_id=(!empty($_POST['sclsc_registration_id']))?$_POST['sclsc_registration_id']:null;
                $prisoner_id=(!empty($_POST['prisoner_id']))?$_POST['prisoner_id']:null;

                //case other metadata related to icmis
                $update_from_module=(!empty($_POST['update_from_module']))?$_POST['update_from_module']:null;
                $petitioner_name=(!empty($_POST['petitioner_name']))?$_POST['petitioner_name']:null;
                $respondent_name=(!empty($_POST['respondent_name']))?$_POST['respondent_name']:null;
                $sclsc_diary_no=(!empty($_POST['sclsc_diary_no']))?$_POST['sclsc_diary_no']:null;
                $sclsc_diary_year=(!empty($_POST['sclsc_diary_year']))?$_POST['sclsc_diary_year']:null;
                $advocate_id=(!empty($_POST['advocate_id']))?json_encode($_POST['advocate_id']):null;
                $aor_code=(!empty($_POST['aor_code']))?json_encode($_POST['aor_code']):null;
                $lower_court_details=(!empty($_POST['lower_court_details']))?json_encode($_POST['lower_court_details']):null;
                $diary_id=(!empty($_POST['diary_id']))?$_POST['diary_id']:null;
                $diary_no=(!empty($_POST['diary_id']))?substr($diary_id, 0, -4):null;
                $diary_year=(!empty($_POST['diary_id']))?substr($diary_id, -4):null;
                $case_year=(!empty($_POST['case_year']))?$_POST['case_year']:null;
                $filing_date=(!empty($_POST['filed_on']))?$_POST['filed_on']:null;
                $case_type_name=(!empty($_POST['case_type_name']))?$_POST['case_type_name']:null;
                $case_type_name_short=(!empty($_POST['case_type_name_short']))?$_POST['case_type_name_short']:null;
                $case_type_acronym=(!empty($_POST['case_type_acronym']))?$_POST['case_type_acronym']:null;
                $case_no_from=(!empty($_POST['case_no_from']))?$_POST['case_no_from']:null;
                $case_no_to=(!empty($_POST['case_no_to']))?$_POST['case_no_to']:null;
                $reg_no_display=(!empty($_POST['reg_no_display']))?$_POST['reg_no_display']:null;
                $registration_numbers=(!empty($_POST['registration_numbers']))?json_encode($_POST['registration_numbers']):null;
                $petitioners=(!empty($_POST['petitioners']))?json_encode($_POST['petitioners']):null;
                $respondents=(!empty($_POST['respondents']))?json_encode($_POST['respondents']):null;
                $petitioner_advocates=(!empty($_POST['petitioner_advocates']))?json_encode($_POST['petitioner_advocates']):null;
                $respondent_advocates=(!empty($_POST['respondent_advocates']))?json_encode($_POST['respondent_advocates']):null;
                $subject_categories=(!empty($_POST['subject_categories']))?json_encode($_POST['subject_categories']):null;
                $status=(!empty($_POST['status']))?$_POST['status']:null;
                $stage=(!empty($_POST['stage']))?$_POST['stage']:null;
                $update_from_module=(!empty($_POST['update_from_module']))?$_POST['update_from_module']:null;
                $document_type=(!empty($_POST['document_type']))?$_POST['document_type']:null;
                $parent=(!empty($_POST['parent']))?json_encode($_POST['parent']):null;
                $document_groups=(!empty($_POST['groups']))?json_encode($_POST['groups']):null;
                $document_hierarchy_level=(!empty($_POST['document_hierarchy_level']))?$_POST['document_hierarchy']:null;
                $document_parent_id=(!empty($_POST['document_parent_id']))?$_POST['document_parent_id']:null;
                $page_count=(!empty($_POST['page_count']))?$_POST['page_count']:null;
                $filed_on=(!empty($_POST['filed_on']))?$_POST['filed_on']:$date;
                $order_dated=(!empty($_POST['order_date']))?$_POST['order_date']:$filed_on;
                $digital_signatory_name=(!empty($_POST['digital_signatory_name']))?$_POST['digital_signatory_name']:null;
                $digital_signatory_place=(!empty($_POST['digital_signatory_place']))?$_POST['digital_signatory_name']:null;
                $digital_signature_timestamp=(!empty($_POST['digital_signature_timestamp']))?$_POST['digital_signature_timestamp']:null;
                $digitally_signed_hash_value=(!empty($_POST['digitally_signed_hash_value']))?$_POST['digitally_signed_hash_value']:null;
                $document_pspdfkit_document_id=(!empty($_POST['pspdfkit_document_id']))?$_POST['pspdfkit_document_id']:null;






                $collection_metadata="{
            \"type\":{\"value\":\"community\"},   
            \"metadata\":
            {
                \"dc.title\":[{\"language\":null,\"value\":\"$registration_id\"}],
                \"sc.jailPetition.registration_id\":[{\"value\":\"$registration_id\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.jailPetition.efiling_no\":[{\"value\":\"$efiling_no\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.jailPetition.efiling_year\":[{\"value\":\"$efiling_year\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.jailPetition.efiling_for_type_id\":[{\"value\":\"$efiling_for_type_id\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.jailPetition.jail_code\":[{\"value\":\"$jail_code\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.date.registered\":[{\"value\": \"$registration_date\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.jailPetition.jail_name\":[{\"value\":\"$jail_name\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.date.created\":[{\"value\":\"$created_on\",\"language\":\"en\",\"authority\":null,\"confidence\":-1}],
                \"sc.case.sclsc_diary_no\":[{\"value\":\"$sclsc_diary_no\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"sc.case.sclsc_diary_year\":[{\"value\":\"$sclsc_diary_year\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"sc.case.sclsc_diary_id\":[{\"value\":\"$sclsc_diary_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"sc.case.sclsc_file_id\":[{\"value\":\"$sclsc_file_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"sc.case.sclsc_registration_id\":[{\"value\":\"$sclsc_registration_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"sc.jailPetition.prisoner_id\":[{\"value\":\"$prisoner_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                    
                \"sc.case.diaryNo\":[{\"value\": \"$diary_no\",\"language\": \"\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.diaryYear\":[{\"value\": \"$diary_year\",\"language\": \"\",\"authority\": null,\"confidence\": -1}],                
                \"sc.petitioner.name\":[{\"value\": \"$petitioner_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.respondent.name\":[{\"value\": \"$respondent_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],                
                \"sc.case.caseTypeId\":[{\"value\": \"$case_type_id\",\"language\": \"\",\"authority\": null,\"confidence\": -1 }],                
                \"sc.case.caseTypeName\":[{\"value\": \"$case_type_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.caseTypeNameShort\":[{\"value\": \"$case_type_name_short\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.caseTypeAcronym\":[{\"value\": \"$case_type_acronym\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.registrationNumbers\":[{\"value\": \"$registration_numbers\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.contributor.petitioners\":[{\"value\": \"$petitioners\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.contributor.respondents\":[{\"value\": \"$respondents\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.contributor.petitionerAdvocates\":[{\"value\": \"$petitioner_advocates\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.contributor.respondentAdvocates\":[{\"value\": \"$respondent_advocates\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.advocate_id\":[{\"value\": \"$advocate_id\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.aor_code\":[{\"value\":\"$aor_code\",\"language\":\"en\",\"authority\": null,\"confidence\": -1}],                
                \"sc.contributor.lowerCourtDetails\":[{\"value\":\"$lower_court_details\",\"language\":\"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.subjectCategories\":[{\"value\": \"$subject_categories\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.status\":[{\"value\": \"$status\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.stage\":[{\"value\": \"$stage\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.date.filed\":[{\"value\": \"$filing_date\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }], 
                \"sc.case.caseNoFrom\":[{\"value\": \"$case_no_from\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.caseNoTo\":[{\"value\": \"$case_no_to\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.caseYear\":[{\"value\": \"$case_year\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.regNoDisplay\":[{\"value\": \"$reg_no_display\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"dc.description.abstract\":[{\"value\": \"$update_from_module\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"dc.rights\":[{\"language\":null}],
                \"dc.description.tableofcontents\":[{\"language\":null}]                              
            }
        }";
                $dspace_user_id='';
                $token=null;
                if(!empty($eprison_logged_in_user_email_id))
                    $dspace_user_id=$eprison_logged_in_user_email_id;
                else
                    $dspace_user_id=$eprison_logged_in_user_id.'@test.nic.in';
                $authorization_bearer_token = 'Authorization: ' . $this->login($dspace_user_id,DEFAULT_PASSWORD_FOR_NEW_EPERSON);
                //echo $authorization_bearer_token;
                $token= ltrim(strstr($authorization_bearer_token, ':'), ':');

                if($token==" " || $token==null)
                {
                    $dspace_user_id=$this->create_user_in_dspace7($dspace_user_id, $eprison_logged_in_user_first_name, $eprison_logged_in_user_last_name, $dspace_user_id, DEFAULT_PASSWORD_FOR_NEW_EPERSON);
                    $authorization_bearer_token = 'Authorization: ' . $this->login($dspace_user_id,DEFAULT_PASSWORD_FOR_NEW_EPERSON);
                }
                //echo $authorization_bearer_token;

                $owing_collection_uuid=null;
                $owing_collection_uuid=$this->collection_search($registration_id,'COLLECTION');
                if(empty($owing_collection_uuid))

                    $owing_collection_uuid=$this->item_search($registration_id,'ITEM'); // search collection again if it is already created and not found at first time


                if(empty($owing_collection_uuid)) {
                    //echo "Not found any existing collection with same diary no";
                    //exit();
                    $owing_collection_uuid = $this->create_collection($registration_id, $parent_community, $collection_metadata);

                }
                $viewpoint_metadata="{\"targetId\":\"$owing_collection_uuid\",\"targetType\":\"collection\"}";
                $update_viewpoint = $this->aud_viewpoint($viewpoint_metadata,'POST');



                $check_item_entry_already_exist = $this->item_discover_facets($registration_id,$registration_id,null,$document_title,null,'ITEM');

                //echo 'created collection uuid:'.$owing_collection_uuid;

                //exit();
                // collection is created with
                /* write the code segment to check the already added item with multiple parameter like
                and digital_page_no etdiary_no,document_type,physical_page from and to noc.if it is already exist prompt the message "already uploaded" else
                create a workspace item and don't forgrt to add dc.date.issue matadata field, which is mendatory.
               $is_item_already_exist=$this->search_in_items_metadata($diaryNo);
                */


                $aud_method='PUT';
                $workspace_item=$this->create_blank_workspace_item($owing_collection_uuid);
                $workspace_item_id=explode("#", $workspace_item)[0];
                $item_id=explode("#", $workspace_item)[1];

                // echo 'created workspace item id:'.$item_id;

                // \"dc.contributor.author\":[{\"language\":null,\"value\":\"$logged_in_user_id\"}],

                $item_metadata="{
            \"type\":{\"value\":\"items\"},
            \"id\":\"$item_id\",
            \"uuid\":\"$item_id\",    
            \"metadata\":
            {  
                \"dc.title\":[{\"language\":null,\"value\":\"$registration_id\"}],
                \"sc.jailPetition.registration_id\":[{\"value\":\"$registration_id\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.jailPetition.efiling_no\":[{\"value\":\"$efiling_no\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.jailPetition.efiling_year\":[{\"value\":\"$efiling_year\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.jailPetition.ref_m_efiled_type_id\":[{\"value\":\"$efiling_type_id\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.jailPetition.efiling_for_type_id\":[{\"value\":\"$efiling_for_type_id\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.jailPetition.jail_code\":[{\"value\":\"$jail_code\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.date.registered\":[{\"value\": \"$registration_date\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.jailPetition.jail_name\":[{\"value\":\"$jail_name\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.case.caseTypeId\":[{\"value\": \"$case_type_id\",\"language\": \"\",\"authority\": null,\"confidence\": -1 }],
                \"sc.jailPetition.earlier_applied\":[{\"value\":\"$earlier_applied\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.jailPetition.doc_id\":[{\"value\":\"$doc_id\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.jailPetition.pdf_id\":[{\"value\":\"$pdf_id\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.jailPetition.index_no\":[{\"value\":\"$index_no\",\"language\":\"\",\"authority\":null,\"confidence\":-1}],
                \"sc.paperbook.document_type_id\":[{\"value\":\"$document_type_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"sc.paperbook.sub_document_type_id\":[{\"value\":\"$sub_document_type_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"sc.jailPetition.file_name\":[{\"value\":\"$file_name\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"sc.jailPetition.file_path\":[{\"value\":\"$file_path\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"sc.paperbook.indexPageNoFrom\":[{\"value\": \"$index_page_no_from\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.paperbook.indexPageNoTo\":[{\"value\": \"$index_page_no_to\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
              \"sc.paperbook.displaySequenceNo\":[{\"value\": \"$display_sequence_number\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"dc.subject\":[{\"value\":\"$document_title\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}], 
                \"sc.jailPetition.no_of_copies\":[{\"value\":\"$no_of_copies\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}], 
                \"sc.jailPetition.file_size\":[{\"value\":\"$file_size\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],  
                \"sc.jailPetition.doc_hashed_value\":[{\"value\":\"$doc_hashed_value\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}], 
                \"sc.jailPetition.is_active\":[{\"value\":\"$is_active\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}], 
                \"sc.jailPetition.ref_user_type_id\":[{\"value\":\"$ref_user_type_id\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}], 
                \"sc.date.created\":[{\"value\":\"$created_on\",\"language\":\"en\",\"authority\":null,\"confidence\":-1}],                
                \"sc.case.sclsc_diary_no\":[{\"value\":\"$sclsc_diary_no\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"sc.case.sclsc_diary_year\":[{\"value\":\"$sclsc_diary_year\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"sc.case.sclsc_diary_id\":[{\"value\":\"$sclsc_diary_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"sc.case.sclsc_file_id\":[{\"value\":\"$sclsc_file_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                \"sc.case.sclsc_registration_id\":[{\"value\":\"$sclsc_registration_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
                
                \"sc.case.diaryId\":[{\"value\": \"$diary_id\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],                
                \"sc.petitioner.name\":[{\"value\": \"$petitioner_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.respondent.name\":[{\"value\": \"$respondent_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.caseTypeName\":[{\"value\": \"$case_type_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.caseTypeNameShort\":[{\"value\": \"$case_type_name_short\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.caseTypeAcronym\":[{\"value\": \"$case_type_acronym\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.registrationNumbers\":[{\"value\": \"$registration_numbers\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.contributor.petitioners\":[{\"value\": \"$petitioners\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.contributor.respondents\":[{\"value\": \"$respondents\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.contributor.petitionerAdvocates\":[{\"value\": \"$petitioner_advocates\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.contributor.respondentAdvocates\":[{\"value\": \"$respondent_advocates\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.advocate_id\":[{\"value\":\"$advocate_id\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.aor_code\":[{\"value\":\"$aor_code\",\"language\":\"en\",\"authority\": null,\"confidence\": -1}],            
                \"sc.contributor.lowerCourtDetails\":[{\"value\":\"$lower_court_details\",\"language\":\"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.subjectCategories\":[{\"value\": \"$subject_categories\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.status\":[{\"value\": \"$status\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.case.stage\":[{\"value\": \"$stage\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                
                \"sc.date.filed\":[{\"value\": \"$filing_date\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],  
             
                \"sc.case.caseNoFrom\":[{\"value\": \"$case_no_from\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.caseNoTo\":[{\"value\": \"$case_no_to\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.caseYear\":[{\"value\": \"$case_year\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.case.regNoDisplay\":[{\"value\": \"$reg_no_display\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],             
                \"dc.description.abstract\":[{\"value\": \"$update_from_module\",\"language\": \"en\",\"authority\": null,\"confidence\":-1}],      
                \"dc.type\":[{\"language\":null,\"value\":\"$document_type \"}], 
              
                \"sc.case.documentPageCount\":[{\"value\": \"$page_count\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.paperbook.parent\":[{\"value\": \"$parent\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],  
                \"sc.paperbook.document_groups\":[{\"value\":\"$document_groups\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
\"sc.paperbook.hierarchy_level\":[{\"value\":\"$document_hierarchy_level\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
\"sc.paperbook.document_parent_id\":[{\"value\":\"$document_parent_id\",\"language\":\"en\",\"authority\":null,\"confidence\":-1 }],
            
               
                \"sc.digitalSignature.signatoryName\":[{\"value\": \"$digital_signatory_name\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.digitalSignature.signatoryPlace\":[{\"value\": \"$digital_signatory_place\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.digitalSignature.updatedOn\":[{\"value\": \"$digital_signature_timestamp\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"sc.digitalSignature.hashvalue\":[{\"value\": \"$digitally_signed_hash_value\",\"language\": \"en\",\"authority\": null,\"confidence\": -1 }],
                \"dc.date.issued\":[{\"value\": \"$order_dated\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],                
                \"dc.contributor.author\":[{\"value\": \"$dspace_user_id\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}],
                \"sc.jailPetition.document_pspdfkit_document_id\":[{\"value\": \"$document_pspdfkit_document_id\",\"language\": \"en\",\"authority\": null,\"confidence\": -1}]               
            }
             
        }";


                /* bitstream upload and update item metadata portion start */
                $filePath='';
                $bitstream_upload_status=0;

                if (isset($_FILES) && !empty($_FILES) && !empty($item_metadata))
                {
                    //echo $item_metadata;exit();
                    $aud_metadata_status=$this->aud_item_metadata($item_id,$item_metadata,$aud_method);
                    //echo $aud_metadata_status;
                    if ($aud_metadata_status==200)
                    {
                        //echo "hello"; exit();

                        $transaction_status=1;
                        $post_data = array('file' => new CURLFILE($_FILES['file']['tmp_name']),'name' => $document_title);

                        $bitstream_upload=$this->upload_bitstream($workspace_item_id,$post_data);

                        $bitstream_upload_status=explode("#", $bitstream_upload)[0];
                        $bitstream_id=explode("#", $bitstream_upload)[1];
                        //echo 'upload bitstream id'.$bitstream_id;
                        if($bitstream_upload_status == 201 || $bitstream_upload_status == 200 ) {
                            $transaction_status = 1;
                        }
                        else
                            $transaction_status=0;
                    }
                    else
                        $transaction_status=0;
                }
                else {
                    $post_data = array('file' => new CURLFILE($filePath)); //file with path
                }
                if($transaction_status==1)
                {
                    echo json_encode(array('collection_id'=>$owing_collection_uuid,'item_id'=>$item_id,'bitstream_id'=>$bitstream_id));

                    // we can call the submission process method from here to make item searchable
                }

                /* bitstream upload and update item metadata portion end */

            }

        }
        else
        {
            echo " You cannot save the details without Uploading any document";
        }

    }

    //e-prison-end

}


?>