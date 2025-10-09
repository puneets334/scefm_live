<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);
defined('BASEPATH') || define('BASEPATH', APPPATH);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */

define('Draft_Stage', 1); //for user
define('Initial_Approaval_Pending_Stage', 2); //for user
define('New_Filing_Stage', 2);
define('Initial_Approved_Stage', 3); // Make Payment
define('Payment_Awaited_Stage', 3); // for admin
define('Initial_Defected_Stage', 4); // for admin and user
define('I_B_Approval_Pending_Stage', 5); // for User
define('Transfer_to_CIS_Stage', 5); // for admin
define('Get_From_CIS_Stage', 6); // for admin
define('Initial_Defects_Cured_Stage', 7); // for admin
define('Transfer_to_IB_Stage', 8); //for admin
define('I_B_Approval_Pending_Admin_Stage', 9); // for Admin
define('I_B_Defected_Stage', 10); // for User -- for Admin - Waiting to be Cured
define('I_B_Defects_Cured_Stage', 11); // for Admin
define('E_Filed_Stage', 12); // for User and Admin
define('Transfer_to_Dealing_Section', 13);
define('Document_E_Filed', 14);
define('Pending_Payment_Acceptance', 15);
define('Pending_Payment_Receipts', 15);
define('Final_submitted_Stage', 16);
define('Payment_Receipt_Stage', 17);
define('DEFICIT_COURT_FEE', 18); // Deficit Fee
define('DEFICIT_COURT_FEE_PAID', 19); // Deficit Fee PAID
define('I_B_Rejected_Stage', 20); // Rejected stage
define('E_REJECTED_STAGE', 21);
define('LODGING_STAGE', 22);
define('DEFICIT_COURT_FEE_E_FILED', 23);
define('DELETE_AND_LODGING_STAGE', 24);
// define('TRASH_STAGE', 25);
define('IA_E_Filed', 26);
define('CDE_DRAFT_STAGE', 27);
define('CDE_SUBMITTED_STAGE', 28);
define('CDE_ACCEPTED_STAGE', 29);
define('CDE_REJECTED_STAGE', 30);

define('DEPT_DRAFT_STAGE', 30);

define('MENTIONING_E_FILED', 31);

define('CITATION_E_FILED', 32);
define('CERTIFICATE_E_FILED', 33);
define('HOLD', 35);
define('DISPOSED', 36);





/////////////////////////////////Clerk Constants///////////////////////////////////////////////////////////////////

define('SUB_Draft_Stage', 50); //for user
define('SUB_Initial_Approaval_Pending_Stage', 51); //for user
define('SUB_New_Filing_Stage', 52);


define('SUB_Initial_Approved_Stage', 53); // Make Payment
define('SUB_Payment_Awaited_Stage', 53); // for admin
define('SUB_Initial_Defected_Stage', 54); // for admin and user
define('SUB_I_B_Approval_Pending_Stage', 55); // for User
define('SUB_Transfer_to_CIS_Stage', 55); // for admin
define('SUB_Get_From_CIS_Stage', 56); // for admin
define('SUB_Initial_Defects_Cured_Stage', 57); // for admin
define('SUB_Transfer_to_IB_Stage', 58); //for admin
define('SUB_I_B_Approval_Pending_Admin_Stage', 59); // for Admin
define('SUB_I_B_Defected_Stage', 60); // for User -- for Admin - Waiting to be Cured
define('SUB_I_B_Defects_Cured_Stage', 61); // for Admin
define('SUB_E_Filed_Stage', 62); // for User and Admin
define('SUB_Transfer_to_Dealing_Section', 63);
define('SUB_Document_E_Filed', 64);
define('SUB_Pending_Payment_Acceptance', 65);
define('SUB_Pending_Payment_Receipts', 65);
define('SUB_Final_submitted_Stage', 66);
define('SUB_Payment_Receipt_Stage', 67);
define('SUB_DEFICIT_COURT_FEE', 68); // Deficit Fee
define('SUB_DEFICIT_COURT_FEE_PAID', 69); // Deficit Fee PAID
define('SUB_I_B_Rejected_Stage', 70); // Rejected stage
define('SUB_E_REJECTED_STAGE', 71);
define('SUB_LODGING_STAGE', 72);
define('SUB_DEFICIT_COURT_FEE_E_FILED', 73);
define('SUB_DELETE_AND_LODGING_STAGE', 74);
define('SUB_TRASH_STAGE', 75);
define('SUB_IA_E_Filed', 76);



/////////////////////////////////END Clerk Constants///////////////////////////////////////////////////////////////////







define('E_FILING_FOR_HIGHCOURT', 1);
define('E_FILING_FOR_ESTABLISHMENT', 2);
define('E_FILING_FOR_SUPREMECOURT', 3);

define('ENTRY_TYPE_FOR_HIGHCOURT', 1);
define('ENTRY_TYPE_FOR_ESTABLISHMENT', 2);

define('ENABLE_WEBSERVICE_HC', 'hc');
define('ENABLE_WEBSERVICE_ESTAB', 'dc');

define('USER_ADVOCATE', 1);
define('USER_IN_PERSON', 2);
define('USER_ADMIN', 3); define('SC_ADMIN', 'SC-ADMIN');
define('USER_SUPER_ADMIN', 4);
//define('USER_STATE_ADMIN', 5);
define('USER_DISTRICT_ADMIN', 6);
define('USER_MASTER_ADMIN', 7);
define('USER_ACTION_ADMIN', 8);
define('USER_ADVOCATE_CIS', 9);
define('USER_DEPARTMENT', 10);
define('USER_PDE', 11);
define('USER_CLERK', 12);
define('USER_BAR_COUNCIL', 13);
define('USER_REGISTRAR_ACTION', 14);
define('USER_REGISTRAR_VIEW', 15);
define('USER_LIBRARY', 16);
define('JAIL_SUPERINTENDENT',17);
define('USER_EFILING_ASSISTANT', 18);
define('SR_ADVOCATE', 19);
define('ARGUING_COUNSEL', 21);
define('USER_EFILING_ADMIN', 20);
define('USER_ADMIN_READ_ONLY', 22);
define('AMICUS_CURIAE_USER',23);
define('ACCOUNT_STATUS_PENDING_APPROVAL', 1); // When account is to be approved by e-admin or CIS in case of advocate and party in person
define('ACCOUNT_STATUS_ACTIVE', 2); // when account is activated
define('ACCOUNT_STATUS_REJECTED', 3); // when account is rejected by e-admin / cis/ bar
define('ACCOUNT_STATUS_OBJECTION', 4); // when account is placed under objections
define('ACCOUNT_STATUS_DEACTIVE', 0); // when a active account is suspended or de-activated for some time
define('ACCOUNT_STATUS_UPDATED', 5); // when an account record is updated in local CIS
define('ACCOUNT_STATUS_OBJ_CURED', 6); // when an objections cured by user
define('ACCOUNT_STATUS_ACTIVE_BUT_OBJ', 7); // when advocate exists in CIS but place under objection -- allow login but show msg on every login attempt for 30 days.
define('ACCOUNT_STATUS_ACTIVE_BUT_OBJ_CURED', 8); // when advocate exists in CIS but place under objection -- allow login but show msg on every login attempt for 30 days.

define('ACCOUNT_REQUEST_PRACTICE_PLACE', 1); // At the time of registration when advocate or party in person chooses to place of practice
define('ACCOUNT_REQUEST_EXISTS_BUT_NEW', 2); // when advocate exists elsewhere but new for this establishment
define('ACCOUNT_REQUEST_EXISTS_BUT_UPDATE', 3); // when advocate exists for this establishment and update of record
define('ACCOUNT_REQUEST_NEW_PENDING', 0); // Advocate new registration pending for approval from its place of practice or cis or bar

define('ACCOUNT_REQUEST_STATUS_PENDING_AT_PRACTICE_PLACE', 0); //  Request status at particular establishment
define('ACCOUNT_REQUEST_STATUS_PENDING', 1); //  Request status at particular establishment
define('ACCOUNT_REQUEST_STATUS_APPROVED', 2); // Request status at particular establishment
define('ACCOUNT_REQUEST_STATUS_REJECTED', 3); // Request status at particular establishment
define('ACCOUNT_REQUEST_STATUS_OBJECTION', 4); // Request status at particular establishment
define('ACCOUNT_REQUEST_STATUS_UPDATED', 5); // Request status at particular establishment
define('ACCOUNT_REQUEST_STATUS_DEACTIVATED', 6); // Request status at particular establishment
define('ACCOUNT_REQUEST_STATUS_OBJ_CURED', 7); // Request status at particular establishment
define('ACCOUNT_REQUEST_STATUS_ACTIVE_BUT_OBJ', 8); // when advocate exists in CIS but place under objection -- allow login but show msg on every login attempt for 30 days.
define('ACCOUNT_REQUEST_STATUS_ACTIVE_BUT_OBJ_CURED', 9); // when advocate exists in CIS but place under objection -- allow login but show msg on every login attempt for 30 days.


define('ACCOUNT_UPDATED_BY_STATE_BAR', 1);
define('ACCOUNT_UPDATED_BY_E_ADMIN', 2);
define('ACCOUNT_UPDATED_BY_E_ADMIN_CIS', 3);
define('ACCOUNT_UPDATED_BY_CIS', 4);
define('ACCOUNT_UPDATED_BY_ADV', 5);
define('ACCOUNT_UPDATED_BY_DEPT', 6);
define('ACCOUNT_UPDATED_BY_USER', 7);
define('ACCOUNT_UPDATED_ON_PRACTICE_PLACE', 8);


define('BAR_APPROVAL_STATUS_DEACTIVATED', 0);
define('BAR_APPROVAL_STATUS_APPROVED', 1);
define('BAR_APPROVAL_STATUS_PENDING', 2);
define('BAR_APPROVAL_STATUS_ON_HOLD', 3);
define('BAR_APPROVAL_STATUS_OBJECTION', 4);
define('BAR_APPROVAL_STATUS_OBJECTION_CURED', 5);

define('NEW_CASE_EXTRA_INFO', 9999999999);
define('NEW_CASE_SIGN_METHOD', 9999999999);
define('OLD_CASES_REFILING', 13);
define('REFILED_OLD_EFILING_CASE', 13);
define('CAVEAT_UPLOAD_DOCUMENT', 9999999999);
define('E_FILING_TYPE_NEW_CASE', 1);
define('E_FILING_TYPE_MISC_DOCS', 2);
define('E_FILING_TYPE_DEFICIT_COURT_FEE', 3);
define('E_FILING_TYPE_IA', 4);
define('E_FILING_TYPE_CDE', 5);
define('E_FILING_TYPE_PDE', 6);
define('COURT_PAYMENT', 7);
define('E_FILING_TYPE_MENTIONING', 8);
define('E_FILING_TYPE_CITATION', 9);
define('E_FILING_TYPE_JAIL_PETITION', 10);
define('E_FILING_TYPE_CERTIFICATE_REQUEST', 11);
define('E_FILING_TYPE_CAVEAT',12);

define('INTEGER_FIELD_LENGTH', 7);
define('DOC_TITLE_LENGTH', 75);
define('CNR_LENGTH', 16);
define('File_FIELD_LENGTH', 100);
define('For_court_code', 7);
define('FEES_LENGTH', 9);
define('DISAPPROVE_REMARK_LENGTH', 500);

define('MISC_DOCUMENT_COPIES', 1);
define('NEWCASE_DOCUMENT_COPIES', 1);
define('IA_DOCUMENT_COPIES', 1);

// BREAD CRUMB STAGES

define('NEW_CASE_CASE_DETAIL', 1);
define('NEW_CASE_PETITIONER', 2);
define('NEW_CASE_RESPONDENT', 3);
define('NEW_CASE_EXTRA_PARTY', 4);
//define('NEW_CASE_LR_PARTY', 5);
define('NEW_CASE_LRS', 5);
define('NEW_CASE_ACT_SECTION', 6);
define('NEW_CASE_SUBORDINATE_COURT', 7);
define('NEW_CASE_UPLOAD_DOCUMENT', 8);
define('NEW_CASE_INDEX', 9);
define('NEW_CASE_COURT_FEE', 10);
define('NEW_CASE_ADDITIONAL_ADV', 11);
define('NEW_CASE_AFFIRMATION', 12);
define('NEW_CASE_VIEW', 13);

define('JAIL_PETITION_CASE_DETAILS',1);
define('JAIL_PETITION_EXTRA_PETITIONER', 2);
define('JAIL_PETITION_SUBORDINATE_COURT', 3);
define('JAIL_PETITION_SIGN_METHOD', 4);
define('JAIL_PETITION_UPLOAD_DOCUMENT', 5);
define('JAIL_PETITION_AFFIRMATION', 6);
define('JAIL_PETITION_VIEW', 7);

define('MISC_BREAD_CASE_DETAILS', 1);
define('MISC_BREAD_APPEARING_FOR', 2);
define('MISC_BREAD_ON_BEHALF_OF', 3);
define('MISC_BREAD_UPLOAD_DOC', 4);
define('MISC_BREAD_DOC_INDEX', 5);
define('MISC_BREAD_COURT_FEE', 6);
define('MISC_BREAD_SHARE_DOC', 7);
define('MISC_BREAD_AFFIRMATION', 8);
define('MISC_BREAD_VIEW', 9);


define('DEFICIT_BREAD_CNR_DETAILS', 1);
define('DEFICIT_BREAD_SIGN_METHOD', 2);
define('DEFICIT_BREAD_COURTFEE', 3);
define('DEFICIT_BREAD_AFFIRMATION', 4);
define('DEFICIT_BREAD_VIEW', 5);


define('IA_BREAD_CASE_DETAILS', 1);
define('IA_BREAD_APPEARING_FOR', 2);
define('IA_BREAD_ON_BEHALF_OF', 3);
define('IA_BREAD_UPLOAD_DOC', 4);
define('IA_BREAD_DOC_INDEX', 5);
define('IA_BREAD_COURT_FEE', 6);
define('IA_BREAD_SHARE_DOC', 7);
define('IA_BREAD_AFFIRMATION', 8);
define('IA_BREAD_VIEW', 9);


define('MEN_BREAD_CASE_DETAILS', 1);
define('MEN_BREAD_ARGUING_COUNCIL', 2);
define('MEN_BREAD_LISTING', 3);
define('MEN_BREAD_UPLOAD_DOC', 4);
define('MEN_BREAD_DOC_INDEX', 5);
define('MEN_BREAD_COURT_FEE', 6);
define('MEN_BREAD_SHARE_DOC', 7);
define('MEN_BREAD_AFFIRMATION', 8);
define('MEN_BREAD_VIEW', 9);


define('CERTIFICATE_BREAD_CASE_DETAILS', 1);
define('CERTIFICATE_BREAD_REQUEST_DETAILS', 2);

define('CITATION_BREAD_CASE_DETAILS', 1);
define('CITATION_BREAD_CITATION_DETAILS', 2);

define('COURT_PAYMENT_CNR_DETAILS', 1);

define('COURT_PAYMENT_BREAD_COURTFEE', 2);

define('COURT_PAYMENT_VIEW', 3);

//---PAPER BOOK CONSTANT------//

define('MAPPING_TYPE_CODE_AMENDMENT', 1);
define('MAPPING_TYPE_CODE_CONNECTED', 2);
define('MAPPING_TYPE_CODE_REPLY', 3);
define('MAPPING_TYPE_CODE_EVIDENCE_TEXT', 4);
define('MAPPING_TYPE_CODE_NED', 5);




define('PAYMENT_THROUGH_RECEIPT', 1);
define('PAYMENT_THROUGH_GATEWAY', 2);
define('PAYMENT_THROUGH_GRAS', 3);
define('TRANSACTION_NUM_LEN', 17);
define('COURT_FEE_LEN', 9);
define('DD_CHALLAN_CHEQUE_LEN', 9);

define('CIS_Batches', 20);
define('CNR_BATCHES_IMPORT', 10);

define('CASE_TYPE_CIVIL', 1);
define('CASE_TYPE_CRIMINAL', 2);

define('DB_CASE_TYPE_CIVIL', 2);
define('DB_CASE_TYPE_CRIMINAL', 3);

define('SIGNED_AADHAR_CARD', 1);
define('SIGNED_DIGITALLY_TOKEN', 2);
define('SIGNED_MOBILE_OTP', 3);




define('ESIGNED_DOCS_BY_PET', 1);
define('ESIGNED_DOCS_BY_ADV', 2);
define('ESIGNED_DOCS_BY_ADV3', 3);
define('ESIGNED_DOCS_BY_ADV4', 4);
define('EVERIFIED_DOCS_BY_MOB_OTP', 5);

define('VALIDATION_PREG_MATCH', '/^[0-9a-zA-Z\s\r\n,\/@\._ -]+$/');
define('VALIDATION_PREG_MATCH_MSG', 'Only /@\, - ._ and space are allowed');

define('EFILING_NUMBER_LENGTH', '17');


//--------------------Forum Doc Type--------------------//
define('FORUM_DOC_ORIGINAL', 1);
define('FORUM_DOC_APPEAL', 2);
define('FORUM_DOC_APPLICATION', 3);
define('FORUM_DOC_APPLICATION_ORIGINAL', 4);
define('FORUM_DOC_APPLICATION_APPEAL', 5);
define('FORUM_DOC_APPLICATION_BOTH', 6);

//-----------------------------------END Forum Doc Type----------------------------------------//
//--------------------Section Type  USE in Digital Copy--------------------//
define('SECTION_PLAINT', 1);
define('SECTION_REPLY', 2);
define('SECTION_DOCUMENT', 3);
define('SECTION_OTHERS', 4);
//-----------------------------------END Section Type  USE in Digital Copy--------//
//---------------------------------Mail Constant-----------------------------------------------//
define('MAIL_DESCLAIMER', "");
define('MAIL_HEADER', "");
define('MAIL_FOOTER', "");
//--------------------------------END Mail Constant--------------------------------------------//
//--------------------------------END Mail Constant--------------------------------------------//
define('ADVOCATE_DRAFT_SIZE',1000);
define('ADVOCATE_DRAFT_NO',1000);
define('PARTY_IN_PERSON_DRAFT_SIZE',1000);
define('PARTY_IN_PERSON_DRAFT_NO',1000);






define('MIN_PAGES_FOR_OCR_CHECK',10);
define('PERCENT_MIN_PAGES_FOR_OCR',30);
//CAVEAT
define('CAVEAT_BREAD_CAVEATOR', 1);
define('CAVEAT_BREAD_CAVEATEE', 2);
define('CAVEAT_BREAD_EXTRA_PARTY', 3);
define('CAVEAT_BREAD_SUBORDINATE_COURT', 4);
define('CAVEAT_BREAD_UPLOAD_DOC', 5);
define('CAVEAT_BREAD_DOC_INDEX', 6);
define('CAVEAT_BREAD_COURT_FEE', 7);
define('CAVEAT_BREAD_VIEW', 8);
define('PETITIONER_IN_PERSON',584);
define('CAVEATOR_IN_PERSON',616);
define('MARK_AS_ERROR',34);



//SMS Template    1107165900749762632
define('SCISMS_GENERIC_TEMPLATE','1107161243622980738');//Used
define('SCISMS_Ementioning_OTP','1107161234553363660');//Used
define('SCISMS_Diary_Listing','1107161234546630797');
define('SCISMS_Document_Filed_In_Diary','1107161234537876841');//Used
define('SCISMS_New_Mobile_No_Validation','1107161234531487479');//Used
define('SCISMS_Account_Rejection','1107161234525271661');//Used
define('SCISMS_Account_Activation','1107161234520255425');//Used
define('SCISMS_Efiling_Profile_Update','1107161234513632595');//Used
define('SCISMS_Efiling_Role_Assignment','1107161234507765891');//Used
define('SCISMS_Efiling_Trashed','1107161234498468173');//Used
define('SCISMS_Efiling_refiled','1107161234490061028');//Used
define('SCISMS_Submission_after_defect_curing','1107161234484745434');//Used
//define('SCISMS_Submit_Pending','1107161234458319159');//Used
define('SCISMS_Submit_Pending','1107161639473854979');//Used

//define('SCISMS_Efiling_No_Generated','1107161234452460708');//Used
define('SCISMS_Efiling_No_Generated','1107161639449361104');//Used

define('SCISMS_Mentioning_Pending','1107161234446402174');//Used
define('SCISMS_eVerify_document','1107161234426578366');//Used
define('SCISMS_Login_Password_To_Email','1107161234420988667');//Used
define('SCISMS_Payment_Cancelled','1107161234413363263');//Used
define('SCISMS_Payment_failed','1107161234408325166');//Used
define('SCISMS_Payment_pending','1107161234399292942');//Used
define('SCISMS_Payment_Success','1107161234393137847');//Used
define('SCISMS_Efiling_Disapproved','1107161234386293489');//Used
define('SCISMS_Efiling_Approved','1107161234375415118');
define('SCISMS_CITATION_NOTE','1107161234368984184');//Used
define('SCISMS_Initial_Approval','1107161234352323994');//Used
define('SCISMS_Change_Password_OTP','1107161234345817127');//Used
define('SCISMS_Case_Submission_OTP','1107161234339300790');//Used
define('SCISMS_Efiling_OTP_Via_Assistant','1107161234331242512');//Used
define('SCISMS_efiling_OTP','1107161234323414828');//Used
define('SCISMS_Registration_OTP','1107161234316346524');//Used
define('SCISMS_Case_Filed_Diary_No','1107161234603870863');//Used



/*PAPERBOOK_RI_JUDGE_RESIDENCE_UPD  --1107161639483272869
PAPERBOOK_RI_GODOWN_UPD   -1107161639473854979*/
//Submit_Pending_UPD --1107161639473854979
//Efiling_No_Generated_UPD --1107161639449361104
define('SCISMS_Efiling_Acceptance','1107161639428530744');
define('SCISMS_OTP_CDE','1107161639433486882');       //Template ID changed on 12.1.23 as available on VIL Power server
// new template IDs
define('SCISMS_GENRIC_OTP_TEMPLATE','1107165900749762632');
define('EFILING_FEE_RECEIPT_DELETION','1107167350419766555');
define('EFILING_PROFILES','1107167350408564507');
define('ARGUING_COUNSEL_VERIFY_CODE','1107167350476192550');
define('ACCOUNT_DEACTIVE','1107167350467277824');
define('SCISMS_EFILING_CAVEAT','1107167367369166069');


$usernotinlist = serialize(array(2660,2659,2658,2657,2656));
define('USER_NOT_IN_LIST',$usernotinlist) ;


$file_type_id= serialize(array(1,2,4,12));

define('FILE_TYPE_ID',$file_type_id);


define('DOC_TYPE_CHECKLIST', '86');
define('DOC_TYPE_AFFIDAVIT', '87');
define('DOC_TYPE_SLP_CERT', '88');
define('DOC_TYPE_PROFORMA', '89');


$file_type_name = serialize(array("new"=>'N',"misc"=>'M',"ia"=>'I',"caveat"=>'C',"all"=>'A'));
define('FILE_TYPE_NAME',$file_type_name);

$document_generate_case_id = array(9,10,19,20,20,25,26);
define('DOCUMENT_GENERATE_CASE_ID',serialize($document_generate_case_id));
define('INTERLOCUTARY_APPLICATION',8);
define('CONDONATION_OF_DELAY_IN_REFILING_OR_CURING_THE_DEFECTS',226);
define('ASTERISK_RED_MANDATORY','<div class="row"><label class="control-label col-md-6 col-sm-offset-1 col-sm-8 col-xs-12 input-sm"><b> <span style="color: red">Note : </span> <span style="color:black; ">Fields marked in </span><span style="color: red">*</span> <span style="color:black; "> are mandatory</span></b></label></div>');

define('PAYMENT_SERVICES_DOWN_FROM', '');
define('PAYMENT_SERVICES_DOWN_TO',"");

define('WEB_SERVICE_BASE_URL',"http://10.249.41.69/hcdc_efilingservices/");
define('WEB_SERVICE_BASE_URL2',"http://10.249.33.43/ICJS_CaseHistory/");
#GET_FROM_CIS_BASE_URL="http://10.249.41.69/efilingservices_njdg/"
define('CASE_NO_WEB_SERVICE_BASE_URL',"http://10.249.41.69/ecourt_webservice_efiling/hcdc_court/");

#WEB_SERVICE_BASE_URL="http://10.40.192.133/njdg_webservices/"
#WEB_SERVICE_BASE_URL2="http://10.249.33.43/ICJS_CaseHistory/"
#GET_FROM_CIS_BASE_URL="http://10.40.192.133/njdg_webservices/"
#CASE_NO_WEB_SERVICE_BASE_URL="http://10.40.192.133/njdg_webservices/"

#UPLOADED_FILE_SIZE=20971520
define('UPLOADED_FILE_SIZE', 52428800);
define('BAR_ID_CARD_SIZE',2097152);

define('WEB_SERVICE_BASE_URL_IP',"10.249.33.43");

define('SHCIL_PAYMENT_GATEWAY_CODE',"shcil");
define('SHCIL_PAYMENT_GATEWAY_NAME',"Stock Holding");

define('MH_GRAS_PAYMENT_GATEWAY_CODE',"mhgras");
define('MH_GRAS_PAYMENT_GATEWAY_NAME',"MH GRAS");

define('HR_GRAS_PAYMENT_GATEWAY_CODE',"hrgras");
define('HR_GRAS_PAYMENT_GATEWAY_NAME',"HR GRAS");

define('SBI_PAYMENT_GATEWAY_CODE',"sbi");
define('SBI_PAYMENT_GATEWAY_NAME',"SBI");

define('CG_GRAS_PAYMENT_GATEWAY_CODE',"cggras");
define('CG_GRAS_PAYMENT_GATEWAY_NAME',"e-Kosh");

define('PAYMENT_METHOD_CODE_STAMP',"stamp");
define('PAYMENT_METHOD_CODE_STAMP_NAME',"Judicial Stamps");

define('PAYMENT_METHOD_CODE_ONLINE',"online");
define('PAYMENT_METHOD_CODE_ONLINE_NAME',"Online");

#---MH GRASS PAYMENT URL------------#
define('GRASS_PAYMENT_BASE_URL_CURL',"https://115.112.229.254/echallan/challan/");
define('GRASS_PAYMENT_BASE_URL',"https://gras.mahakosh.gov.in/echallan/challan/");
#---------END--------------#

#---SBI PAYMENT URL------------#
define('SBI_PAYMENT_BASE_URL',"https://test.sbiepay.com/secure/AggregatorHostedListener");
define('SBI_PAYMENT_STATUS_URL',"https://test.sbiepay.com/payagg/orderStatusQuery/getOrderStatusQuery");
define('SBI_TRANSACTION_MIS',"https://test.sbiepay.com/payagg/MISSettleReport/transactionMISAPI");
define('SBI_TRANSACTION_PAYOUT',"https://test.sbiepay.com/payagg/transactionPayoutAPI/getTransactionPayoutAPI");
define('SBI_PAYMENT_SECRET_KEY',"BUEPMt7xhYV/BxluRa39gQ==");

define('SBI_PAYMENT_DOUBLE_VARIFICATION_URL',"https://test.sbiepay.com/secure/AggMerchantStatusQueryAction");
define('SBI_PAYMENT_DOUBLE_VARIFICATION_SECRET_KEY',"fBc5628ybRQf88f/aqDUOQ==");



#OPENAPI_URL="http://164.100.79.159/"
define('OPENAPI_URL',"https://api.ecourts.gov.in/");
define('OPENAPI_URL_MULTIPLE',"https://api.ecourts.gov.in/");

define('OPENAPI_IV',"abcdef987654");
define('OPENAPI_KEY',"OTSscUut41I6");
define('OPENAPI_HASHHMAC_KEY',"15081947");
define('OPENAPI_DEPT_NO',"CE00008");
define('OPENAPI_VERSION',"v1.0");
#---------END--------------#

#---HR GRASS PAYMENT URL------------#
#HR_GRASS_PAYMENT_BASE_URL="https://egrashry.nic.in/webpages/EgEChallan_Excise.aspx"
#HR_GRASS_PAYMENT_STATUS_URL="https://egrashry.nic.in/grn_status.asmx/GetGrnDetails_identity"
#HR_GRASS_PAYMENT_CHALLAN_URL="https://egrashry.nic.in/ReturnBase64.asmx/RequestBase64"

#-------END-------------------#


define('ESIGN_SERVICES_DOWN_FROM',"");
define('ESIGN_SERVICES_DOWN_TO',"");
define('ESIGN_OTP_GENERATION_URL',"https://nic-esign2gateway.nic.in/esign/acceptClient");
define('ESIGN_RESPONSE_URL',"https://efiling.ecourts.gov.in/esignature/cdac_response/");
define('ESIGN_SERVICE_URL',"http://10.25.78.22/web_service/index.php/Esigner/sign_doc");
#ESIGN_SERVICE_URL="https://registry.sci.gov.in/api/digital_signature/aadhaar/sign_doc"
#ICMIS_SERVICE_URL="http://10.25.78.22:84/out_service/index.php" #original akg
// define('ICMIS_SERVICE_URL',"http://10.25.78.43:84/out_service/index.php");
//define('ICMIS_SERVICE_URL',"http://10.25.80.170:84/public");
// define('ICMIS_SERVICE_URL',"http://10.40.186.78:83/public");
// define('ICMIS_SERVICE_URL',"http://10.40.186.81:88");
//define('ICMIS_SERVICE_URL',"http://10.25.78.48:83/public");
//define('ICMIS_SERVICE_URL',"http://10.40.186.78:83/public");

define('ICMIS_SERVICE_URL',"http://10.192.105.88:84");
// define('ICMIS_SERVICE_URL',"http://10.25.78.23:85");
// define('ICMIS_SERVICE_URL',"http://10.25.78.61:81");
// define('ICMIS_SERVICE_URL',"http://10.40.186.239:88");

// define('ICMIS_SERVICE_URL',"http://10.40.186.239:83/public");
// define('ICMIS_SERVICE_URL',"http://10.25.78.48:83/public");
#ICMIS_SERVICE_URL="http://10.40.186.102/out_service/index.php");
#ICMIS_SERVICE_URL="http://10.40.186.11/out_service/index.php");

#ESIGN_RESPONSE_URL="http://10.40.192.133/efiling/esignature/cdac_response/");
define('ESIGN_JAVA_BRIDGE_URI',"http://10.25.78.27:8080/JavaBridge/java/Java.inc");
define('ESIGN_REDIRECT_URL_CODE',"88");
# ------------------------------Payment Gateway parameters--------------------------------------------------#


#---STOCK HOLDING PAYMENT URL------------#

#STOCK_HOLDING_PAYMENT_BASE_URL="https://www.shcileservices.com/OnlineE-Payment/sEpsePmtTrans"
#STOCK_HOLDING_PAYMENT_STATUS_URL="https://www.shcileservices.com/OnlineE-Payment/sEpsGetTransStatus"
#STOCK_HOLDING_PAYMENT_CHALLAN_URL="https://www.shcileservices.com/OnlineE-Payment/sEpsPaymentChallan"

#STOCK_HOLDING_LOGIN="dlsupcourt"
#STOCK_HOLDING_PASSWORD="ourtdls"
#STOCK_HOLDING_PRODUCT="EPS-DL-002"
#STOCK_HOLDING_REQHASHKEY="1013061358req985191802"
#STOCK_HOLDING_RESPHASHKEY="1906397444resp1400538108"
#STOCK_HOLDING_UDF1="efiling"
#STOCK_HOLDING_UDF2="efiling"
#STOCK_HOLDING_UDF3="efiling"
#STOCK_HOLDING_UDF4="efiling"
#STOCK_HOLDING_UDF5="efiling"
#STOCK_HOLDING_TXNTYPE="NA"

#---STOCK HOLDING PAYMENT URL------------#

define('STOCK_HOLDING_PAYMENT_BASE_URL',"https://www.shcileservices.com/OnlineE-Payment/sEpsePmtTrans");
define('STOCK_HOLDING_PAYMENT_STATUS_URL',"https://www.shcileservices.com/OnlineE-Payment/sEpsGetTransStatus");
define('STOCK_HOLDING_PAYMENT_CHALLAN_URL',"https://www.shcileservices.com/OnlineE-Payment/sEpsPaymentChallan");

define('STOCK_HOLDING_LOGIN',"dlsupcourt");
define('STOCK_HOLDING_PASSWORD',"ourtdls");
define('STOCK_HOLDING_PRODUCT',"EPS-DL-002");
define('STOCK_HOLDING_REQHASHKEY',"1013061358req985191802");
define('STOCK_HOLDING_RESPHASHKEY',"1906397444resp1400538108");
define('STOCK_HOLDING_UDF1',"efiling");
define('STOCK_HOLDING_UDF2',"efiling");
define('STOCK_HOLDING_UDF3',"efiling");
define('STOCK_HOLDING_UDF4',"efiling");
define('STOCK_HOLDING_UDF5',"efiling");

#    ------------------- STOCK HOLDING PAYMENT for Testing ---------------------------
// define('STOCK_HOLDING_PAYMENT_BASE_URL',"https://dr.shcileservices.com/OnlineE-Payment/sEpsePmtTrans");
// define('STOCK_HOLDING_PAYMENT_STATUS_URL',"https://dr.shcileservices.com/OnlineE-Payment/sEpsGetTransStatus");
// define('STOCK_HOLDING_PAYMENT_CHALLAN_URL',"https://dr.shcileservices.com/OnlineE-Payment/sEpsPaymentChallan");
#STOCK_HOLDING_PAYMENT_BASE_URL="https://115.111.15.137/OnlineE-Payment/sEpsePmtTrans");
#STOCK_HOLDING_PAYMENT_STATUS_URL="https://115.111.15.137/OnlineE-Payment/sEpsGetTransStatus");
#STOCK_HOLDING_PAYMENT_CHALLAN_URL="https://115.111.15.137/OnlineE-Payment/sEpsPaymentChallan");

#STOCK_HOLDING_LOGIN="dlsupcourt"
#STOCK_HOLDING_PASSWORD="ourtdls" #Test@123
#STOCK_HOLDING_PRODUCT="EPS-DL-102" #PHCFEE
#STOCK_HOLDING_REQHASHKEY="1548660093req1405094244"
#STOCK_HOLDING_RESPHASHKEY="1731126985resp732480271"
#STOCK_HOLDING_UDF1="efiling"
#STOCK_HOLDING_UDF2="efiling"
#STOCK_HOLDING_UDF3="efiling"
#STOCK_HOLDING_UDF4="efiling"
#STOCK_HOLDING_UDF5="efiling"
#STOCK_HOLDING_TXNTYPE="NB"
#    ------------------- STOCK HOLDING PAYMENT for Testing ---------------------------


define('HR_GRASS_PAYMENT_SCHEMENAME',"0030-01-101-99-51");

define('MH_GRAS_PAYMENT_SUBSYSTEM',"");


define('EPAY_PAYMENT',"http://10.249.41.69/epay_webservice/");

# -------------------END Payment Gateway parameters--------------------#

define('IP_FOR_CRON',"10.40.192.132");
#URL_FOR_SUPLIS="http://10.25.78.22:85/suplis/index.php/LibraryWebService/"
define('URL_FOR_SUPLIS',"http://10.25.78.22:85/web_service/index.php/LibraryWebService/");
define('URL_FOR_LIBRARY_DATA',"http://10.25.78.22:85/web_service/index.php/CatalogWebService");
define('API_PRISON',"http://10.25.78.22:85/web_service/index.php/Prisondataservice");


#    ------------------- DSpace API URLs : START ---------------------------
define('DSPACE_7_SERVER',"http://10.25.78.26:39321");
define('LOGIN_INTO_DSPACE',DSPACE_7_SERVER."/server/api/authn/login");
define('CHECK_LOGIN_STATUS',DSPACE_7_SERVER."/server/api/authn/status/");
define('LOGOUT_FROM_DSPACE',DSPACE_7_SERVER."/server/api/authn/logout/");
define('AUD_EPERSONS',DSPACE_7_SERVER."/server/api/eperson/epersons/");
define('AUD_COMMUNITIES',DSPACE_7_SERVER."/server/api/core/communities/");
define('AUD_COLLECTIONS',DSPACE_7_SERVER."/server/api/core/collections/");
define('AUD_VIEWPOINTS',DSPACE_7_SERVER."/server/api/statistics/viewevents");
define('SEARCH_WORKSPACE_ITEM',DSPACE_7_SERVER."/server/api/submission/workspaceitems/search/item");


define('AUD_METADATA_SCHEMAS',DSPACE_7_SERVER."/server/api/core/metadataschemas/");
define('AUD_METADATA_FIELDS',DSPACE_7_SERVER."/server/api/core/metadatafields/");
define('AUD_BITSTREAM_TO_WORKSPACE_ITEMS',DSPACE_7_SERVER."/server/api/submission/workspaceitems/");
define('AUD_ITEMS',DSPACE_7_SERVER."/server/api/core/items/");
define('AUD_WORKSPACE_ITEM',DSPACE_7_SERVER."/server/api/submission/workspaceitems");
define('AUD_BUNDLES',DSPACE_7_SERVER."/server/api/core/bundles/");
define('AUD_BITSTREAMS',DSPACE_7_SERVER."/server/api/core/bitstreams/");
define('CHECK_BITSTREAMS_FORMATS',DSPACE_7_SERVER."/server/api/core/bitstreamformats");
define('AUD_BUNDLE_2_BITSTREAM',DSPACE_7_SERVER."/server/api/core/bitstreamformats");

define('AUD_WORKFLOW_STEPS',DSPACE_7_SERVER."/server/api/config/workflowsteps/");
define('AUD_WORKFLOW_ITEM',DSPACE_7_SERVER."/server/api/workflow/workflowitems");

define('AUD_METADATA_SUGGESTIONS',DSPACE_7_SERVER."/server/api/integration/metadata-suggestions");

define('DISPLAY_SOLR_BASED_INDEXES',DSPACE_7_SERVER."/server/api/discover/browses/");
define('DISCOVER_SEARCH',DSPACE_7_SERVER."/server/api/discover/search/objects");
define('FACET_SEARCH',DSPACE_7_SERVER."/server/api/discover/search/facets");
define('AUD_GROUPS',DSPACE_7_SERVER."/server/server/api/eperson/groups");
#DSPACE_USERID="sca.kbpujari@sci.nic.in"
#DSPACE_PASSWORD="kbp@#2020"
define('DSPACE_USERID',"itcell@sci.nic.in");
define('DSPACE_PASSWORD',"Test@4321");
define('DEFAULT_PASSWORD_FOR_NEW_EPERSON',"Test@4321");
define('AUTHORIZATION_TOKEN',"Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJlaWQiOiIyY2M4YjE5Ni1jMjA0LTQwMzktYjljOC05M2U2Yjg0MDYxNGQiLCJzZyI6W10sImV4cCI6MTU4OTQ0NjUzMH0.gPsSPRAfwZOZbM_1HMpZMJBvWmT4k8CFqhc_HUmWR7s");


define('PRODUCTION_SERVER',"http://10.40.186.14:91");
#PRODUCTION_SERVER="http://10.25.78.22:91"
define('CASE_PAPER_BOOK_API',PRODUCTION_SERVER."/index.php/dspace/DefaultController/get_collection_all_items/");
define('SEARCH_PAPER_BOOK_DOCUMENT',PRODUCTION_SERVER."/index.php/dspace/DefaultController/search_and_display_cases_paperbook_docs/");
define('GET_ITEM_ORIGINAL_BUNDLE_UUID',PRODUCTION_SERVER."/index.php/dspace/DefaultController/item_original_bundle_uuid/");
define('GET_BUNDLE_BITSTREAM_UUID',PRODUCTION_SERVER."/index.php/dspace/DefaultController/bundle_bitstream_uuid/");
#CASE_PAPER_BOOK_API="${PRODUCTION_SERVER}/index.php/dspace/DefaultController/get_collection_all_items/"
#SEARCH_PAPER_BOOK_DOCUMENT="${PRODUCTION_SERVER}/index.php/dspace/DefaultController/search_and_display_cases_paperbook_docs/"
#GET_ITEM_ORIGINAL_BUNDLE_UUID="${PRODUCTION_SERVER}/index.php/dspace/DefaultController/item_original_bundle_uuid/"
#GET_BUNDLE_BITSTREAM_UUID="${PRODUCTION_SERVER}/index.php/dspace/DefaultController/bundle_bitstream_uuid/"



define('DSPACE_4_SERVER',"http://10.40.189.152:8080");
define('DSPACE4_LOGIN_ID',"avadhesh.kumar@nic.in");
define('DSPACE4_PASSWORD',"9312570277");
define('LOGIN_DSPACE_4',DSPACE_4_SERVER."/sc/");
define('DISCOVER_SEARCH_DSPACE_4',DSPACE_4_SERVER."/server/api/discover/search/objects");
define('SOLR_SEARCH_DSPACE_4',"http://localhost:8080/solr/search/select");
#    ------------------- DSpace API URLs : END ---------------------------


define('CASE_STATUS_SERVER',"http://10.25.78.22:82");
#CASE_STATUS_SERVER="http://10.40.186.34/php");
define('CASE_STATUS_API',CASE_STATUS_SERVER."/case_status/case_status_process.php");
define('CASE_STATUS_ADDON_API',CASE_STATUS_SERVER."/case_status/addon_pages");






#PSPDFKIT
define('PSPDFKIT_SERVER_URI',"http://10.25.78.22:83");

define('HIGH_COURT_URL',"http://10.25.78.22:85/web_service/index.php/Courtdataservice/");
define('DISTRICT_COURT_URL',"http://10.25.78.22:85/web_service/index.php/DistrictCourtDataService/");

#OUTSERVICE_URL="http://10.40.186.102/out_service/index.php"
define('OUTSERVICE_URL',"http://10.25.78.43:84/out_service/index.php");


#-------------CHANGE ACCORDING COURT/ESTABLISHMENT------------#
#HEADER_INFO="<span class='main_title'>High Court & District Courts</span>"
#HEADER_TITLE="e-Filing - High Court and District Courts" #HC and DC
#HEADER_INFO="<span class='main_title'>High Court</span>"
#HEADER_TITLE="e-Filing - High Court" #HC

define('HEADER_INFO',"<span class='main_title'>Supreme Court</span>");
define('HEADER_TITLE',"e-Filing - Supreme Court");

define('READ_DATA_FROM_JSON',TRUE);

define('ENABLE_FOR_HC',TRUE);
define('ENABLE_FOR_ESTAB',TRUE);

define('ENABLE_E_FILE_IA_FOR_HC',TRUE);
define('ENABLE_E_FILE_IA_FOR_ESTAB',TRUE);

define('ENABLE_VIEW_RECEIPT',FALSE);
define('ENABLE_VIEW_MULTI_PAYMODE',FALSE);
define('ENABLE_JUDICIAL_STAMPS',FALSE);

define('ENABLE_TRANSACTION_DETAIL',TRUE);
define('ENABLE_MH_GRAS_PAYMENT_GATEWAY',FALSE);
define('ENABLE_SHCIL_PAYMENT_GATEWAY',FALSE);
define('ENABLE_HR_GRAS_PAYMENT_GATEWAY',TRUE);
define('ENABLE_SBI_PAYMENT_GATEWAY',FALSE);
define('ENABLE_CG_EKOSH_PAYMENT_GATEWAY',FALSE);
define('ENABLE_COURT_FEE',TRUE); # For court fee required
define('COURT_FEE_OPTIONAL_FOR',"a:2:{i:0;s:7:\"hrgras1\";i:1;s:3:\"sbi\";}"); #serialize(array("hrgras1", "sbi"))
define('ENABLE_DISCLAIMER',"");

define('ENABLE_CASE_DATA_ENTRY',FALSE);
define('ENABLE_EFILING',TRUE);
define('ENABLE_EVERIFICATION_BY_MOBILE_OTP',FALSE);
define('ENABLE_EVERIFICATION_ON_ESIGN_FAIL',TRUE);

define('IA_APPLYING_PARTY_NAME_PHC',TRUE);


define('ENABLE_GET_PAYMENT_STATUS',"+3 minutes");
define('ENABLE_MASTER_N_ACTION_ADMIN',TRUE);

define('SMS_EMAIL_API_USER',"38");

define('VISHNU_SERVER_HOST','10.25.78.5');
define('CLOUD_SMS_SERVER_HOST','10.249.44.165');
// define('CLOUD_SMS_SERVER_HOST',"${CLOUD_SMS_SERVER_HOST}");
define('SMS_SERVER_HOST',VISHNU_SERVER_HOST);
define('EMAIL_SERVER_HOST',VISHNU_SERVER_HOST);
define('EADMINSCI_URI',"http://".VISHNU_SERVER_HOST."/eAdminSCI");
define('CAPTAIN_SERVER_HOST',"10.25.78.69");
define('COURT_ASSIST_URI',"https://".CAPTAIN_SERVER_HOST.":44434");
define('COURT_ASSIST_URI_ALT',"https://".CAPTAIN_SERVER_HOST.":44432");
define('API_CAUSELIST_URI',COURT_ASSIST_URI."/api/schedule/cases");
define('API_SCI_INTERACT_URI',COURT_ASSIST_URI."/api/interact");
define('API_SCI_INTERACT_PAPERBOOK_PDF',COURT_ASSIST_URI."/api/digitization/case_file/");
define('API_UNI_NOTIFY_SEND',"http://10.25.78.111:36521/api/v1/send");


define('API_ICMIS_STATISTICS_URI',COURT_ASSIST_URI_ALT."/api/icmis/statistics");
define('REVERSE_PROXY_SERVER_IPS',"10.25.78.69,10.40.186.11");
define('DB_HOST','10.25.78.22');
define('DB_PORT',45098);
define('DB_USER',"efiling_near");
define('DB_PASSWORD',"SkRhD@#2010");
define('DB_DATABASE',"efiling_near");
define('DB_SCHEMA','');
define('DB_HOST_ALT','10.25.78.22');
define('DB_PORT_ALT',45098);
define('DB_USER_ALT',"efiling_near");
define('DB_PASSWORD_ALT',"SkRhD@#2010");
define('DB_DATABASE_ALT',"efiling_near");
define('DB_SCHEMA_ALT','');
define('OFFLINE_ADHAAR_EKYC_ZIP_ALLOWABLE_FILE_FORMAT','["xml"]');
define('OFFLINE_AADHAAR_EKYC_ZIP_ALLOWABLE_FILE_SIZE',5000000);

#-------------END------------------------------#
define('NEW_MAIL_SERVER_HOST',"http://10.25.78.60/supreme_court/Copying/index.php/Api/eMailSend");
## NEW_MAIL_SERVER_HOST="http://10.249.44.165/sci_email_api/index.php/Api/eMailSend"
define('DRAFT_STAGE',1);
define('INITIAL_DEFECTED_STAGE',4);
define('FINAL_SUBMITTED_STAGE',16);
define('TRASH_STAGE',25);
define('ADMIN_FOR_TYPE_ID',3);
define('ADMIN_FOR_ID',1);
define('AUTO_UPDATE_CRON_USER',2647);
define('SMS_RESEND_LIMIT', 30);
define('LIVE_EMAIL_KEY', "cKLKqvPlW8");

// define('ADMIN_SERVER_URL',"http://10.40.186.78:83/");
define('ADMIN_SERVER_URL',"http://10.192.105.75:83/");
define('ADMIN_AUTO_DIARY_USER_ID_FOR_EFM',7087);
define('ADMIN_AUTO_DIARY_ICMIS_USER_CODE',10531);
define('ADMIN_AUTO_DIARY_EMP_ID',9999);
define('ADMIN_AUTO_DIARY_FIRST_NAME',"AUTO GENERATE eFM");

// for physical hearing common helper
define('session_expiration_time_inseconds', 60);
define('PHYSICAL_HEARING_LOGIN_OTP', '1107161242971352833');
define('CASES_ALLOWD_MAX_LIMIT_OF_AOR',5000000000);
define('SMS_TO_CONCERN','9711475023,9891713636,8920463959');
//define('EMAIL_TO_CONCERN','sec.control@sci.nic.in, admn.gen@sci.nic.in, caretaking.sc@sci.nic.in,sca.mohitjain@sci.nic.in, sca.kbpujari@sci.nic.in,adreg.hsjaggi@sci.nic.in,ppavan.sc@nic.in,sca.garvit@sci.nic.in,reception.office@sci.nic.in'); // for production
define('EMAIL_TO_CONCERN','sca.mohitjain@sci.nic.in,sca.kbpujari@sci.nic.in,'); //for development
define('INVALID_PYSICAL_APPEARANCE', '1107161578900824667');
define('APP_NAME_L1','Physical Hearing');
define('APP_NAME_L2','(with Hybrid Option)');
define('APP_NAME_IN_HEADER',APP_NAME_L1);
define('ANU_GOV_IN', 'https://anu.sci.gov.in/');
define('SCI_GOV_IN', 'https://sci.gov.in');
define('MAIN_SCI_GOV_IN', 'https://main.sci.gov.in');


/* Apearance Constants */
define('CURRENT_DATE', date('Y-m-d'));
define('APPEARANCE_ALLOW_TIME', '11:30:00');
define('APPEARANCE_ALLOW_TIME_STRING', '11:30 A.M.');

define('KEY_TO_SEND_EMAIL', 'A0Oldkflkd31');
define('EMAIL_API_URL', 'http://127.0.0.1/supreme_court/Copying/index.php/Api/eMailSend');

// SMS API settings
define('KEY_TO_SEND_SMS', 'kjuy@98123_-fgbvgAD');
define('SMS_API_URL', 'http://127.0.0.1/eAdminSCI/a-push-sms-gw');

// SMS Template IDs
define('SMS_TEMPLATE_ID_OTP', '1107161242971352833');
define('SMS_TEMPLATE_APPEARANCE_SLIP_SUBMITTED', '1107167091697162626');

// Message settings
define('MSG_TIME_OUT', 'Time Out');
define('OLD_ROP_DB', "rop_text_web");
define('GET_SERVER_IP', "10.0.0.0"); //server ip
define('CEPT_GOV_IN', 'https://api.cept.gov.in/tariff/api/values/gettariff');
define('SCISMS_URL', '10.0.0.0:36521/api/v1/send');
define('SCISMS_e_copying_crn_created','e-copying_crn_created');
define('DOCUMENT_CASETYPE_URL', 'http://10.25.80.170:82/');
define('AIASSISTED_USER_IN_LIST',[2563,2531,1737,1829,1797,5653,6517,1154,2463,2023,1313,2202,1943,892,2569,2361,1935,1199,1933,2184,2445,5658,1378,1671,2417,1571,5579,5456,2513,1253,2256,6396,2345,1593,1475,1801,2072,5479,2270,1626,2003,2305,2077,1163,1500,1774,2479,5824,5642,8660,5827,651,1641,2293,1862,2304,2332,6654,2226,5568,5710,2100,2338,6528,2158,5501,2234,5770,1397,5559,2367,1895,2107,1234,2018,1381,2185,1921,5706,2113,5769,5599,5484,2472,6692,1814,5817,2441,2553,1625,2302,5620,1537,2166,2179,6734,2306,1287,2246,2570,1312,5454,5766,1722,5701,1630,5721,2157,8936,5569,2096,1820,2602,5483,5717,2598,2572,6524,5533,1976,5639,5860,5457,672,2007,1619,782,1900,2486,1709,1866,2019,5671,1967,1345,2450,2138,1432,1515,1562,1529,1923,2620,1655,1453,2392,1731,2607,5668,1428,2414,1711,5546,5792,1460,1425,1548,1668,2249,1485,1242,1881,2439,9109,5606,1297,1210,2314,5728,1552,2499,1919,1601,2611,6725,1325,2403,6483,2272,798,2491,1583,1658,1734,2093,5870,2203,5682,1424,1726,5862,8571,2238,1849,2385,6590,1539,2260,2315,5851,6282,1975,1600,2309,2465,2121]);
define('CHANGE_EFILE_STAGE',['3','5','6','8','9','10','11']);
define('ICMIS_IITM_EFILE_COMPARISON_USERS',['SCI4599','SC-ADMIN']);

define('GVT_AOR_LIMIT',10);
define('NON_GVT_AOR_LIMIT',3);
define('CLERK_ASSOCIATIONS',2);
define('DOCUMENT_CASETYP_URL', "http://10.192.105.105:91/");
define('APPEARANCE_SUBMISSION', "01-01-2023");
define('SEND_TO_APPEARING_REPORT', ['fdsf@gmail.com', 'sasdsad@sci.nic.in']);

define('SAN_ROOT', "/home/SAN_eCopy"); //SAN ADDRESS
// define('NEW_SMS_SERVER_HOST_JIO_URL', "https://uninotify.sci.gov.in/api/v1/send");
define('NEW_SMS_SERVER_HOST_JIO_URL', "http://10.192.105.124:91/api/v1/send");
define('NEW_SMS_SERVER_HOST_JIO_IP', "http://10.192.105.124:91/api/v1/send");
define('LIVE_SMS_KEY_JIO_CLOUD', "askjosx03xn90zn1_34jz93ja"); // for testing
// define('LIVE_SMS_KEY_JIO_CLOUD', "sdfmsdbfjh327654t3ufb58"); //live key
define('LIVE_EMAIL_KEY_JIO_CLOUD', "nx93ns9zllxzn04n29akjwusp"); //for testing
// define('LIVE_EMAIL_KEY_JIO_CLOUD', "sdfmsdbfjh327654t3ufb59"); //live key
// define('NEW_MAIL_SERVER_HOST_JIO_URL', "https://uninotify.sci.gov.in/api/v1/send");
define('NEW_MAIL_SERVER_HOST_JIO_URL', "http://10.192.105.124:91/api/v1/send");
define('NEW_MAIL_SERVER_HOST_JIO_IP', "http://10.192.105.124:91/api/v1/send");

define('AUTHENTICATED_BY_AOR',24);
define('APPEARING_COUNCIL',25);
// define('BHARATKOSH_PAYMENT_GATEWAY','https://training.pfms.gov.in/bharatkosh/bkepay'); //test
define('BHARATKOSH_PAYMENT_GATEWAY','https://bharatkosh.gov.in/BKePay'); //live
define('SCISMS_e_copying_login','e-copying_login');

$REPLICATION_SERVER_IPS_ARRAY = array('10.192.105.148');
define('REPLICATION_SERVER_IPS', $REPLICATION_SERVER_IPS_ARRAY);
define('REPLICATION_SERVER_IP_DEFAULT', '10.25.80.170');


$repCheckConfig = array(
  'DSN'          => '',
  'hostname'     => REPLICATION_SERVER_IP_DEFAULT,
  'username'     => 'postgres',
  'password'     => 'postgres',
  'database'     => 'efiling_near',
  'DBDriver'     => 'Postgre',
  'cacheOn'      => false,
  'cacheDir'     => '',
  'DBPrefix'     => '',
  'pConnect'     => false,
  // 'DBDebug'      => (ENVIRONMENT !== 'development'),
  'charset'      => 'utf8',
  'DBCollat'     => 'utf8_general_ci',
);
define('REPLICATION_CHECK_CONFIG', $repCheckConfig);

define('API_SCI_GOV_IN_URL', 'https://api.sci.gov.in');
define('API_SCI_GOV_IN', 'http://10.192.105.54');

$path = isset($_SERVER['REQUEST_URI']) ? '/' . trim($_SERVER['REQUEST_URI'], '/') : '';
// print_r($path);die;
define('cookie_path', $path);
$t = 'efm_'.time().'_';
define('cookie_time', $t);

define('JANPARICHAY_CLIENT_ACTIVE',true); // JANPARICHAY ALLOW "true" for live

//SELECTED RESTRICT USERS(AORs&PIPs) physical_hearing2.0
define('SELECTED_RESTRICT_USERS_ACTIVE',true);
// define('SELECTED_RESTRICT_USERS_LIST',array(1295,1781,1714,2450,2382,2988,2278,2281,3536,2250,2299,2457,1811,2357,2649,2468,1992,2285,3066,3144)); // When going live
define('SELECTED_RESTRICT_USERS_LIST',array(1295,1781,1714,2450,2382,2988,2278,2281,3536,2250,2299,2457,1811,2357,2649,2468,1992,2285,3066,3144,177,1523,1525,1777,15,2661,105)); // for staging testing
define('SELECTED_RESTRICT_USERS_URL','https://efiling.sci.gov.in');
define('SELECTED_RESTRICT_USERS_ALERT_MESSAGE','Your access has been moved to the new portal.Please log in using the new link:'.SELECTED_RESTRICT_USERS_URL);

define('DEPARTMENT_CODE_UAT', 22);
define('DEPARTMENT_CODE_LIVE', '022');
define('INSTALLATION_ID_UAT', 10051);
define('INSTALLATION_ID_LIVE', 11147);
define('ORDER_CONTENT_UAT', 7220);
define('ORDER_CONTENT_LIVE', 16048);
define('ORDER_CONTENT_RTI', 10680);
define('PAYMENT_TYPE_ID_UAT', 3132);
define('PAYMENT_TYPE_ID_POSTAGE', 9525);
define('PAYMENT_TYPE_ID_FEE_STAMP', 9527);
define('PAYMENT_TYPE_ID_LIVE', 9528);
define('PAYMENT_TYPE_ID_POSTAGE_RTI', 0);
define('PAYMENT_TYPE_ID_FEE_STAMP_RTI', 0);
define('PAYMENT_TYPE_ID_RTI', 10024);
define('PAYMENT_TYPE_ID_COPYING_LIVE', 9527);
define('CHILD_ORDER_CONTENT', 9570);
define('CHILD_PAYMENT_TYPE_ID', 9528);
define('CHILD_PAYMENT_TYPE_ID_FEE_STAMP', 9527);
define('CHILD_PAYMENT_TYPE_ID_POSTAGE', 9525);
define('PAO_CODE', '031709');
define('DDO_CODE', '231710');
define('CART_DESCRIPTION_FOR_SERVICE_CHARGES', 'Copying Service Charges');
define('CART_DESCRIPTION_FOR_FEE_STAMP_RTI', 0);
define('CART_DESCRIPTION_FOR_POSTAGE_RTI', 0);
define('CART_DESCRIPTION_FOR_FEE_STAMP_COPYING', 'Copying Fee in Stamp');
define('CART_DESCRIPTION_FOR_POSTAGE_COPYING', 'Postage');

define('PORTAL_VERSION',3);