<?php
if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON || $_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE_CIS || $_SESSION['login']['ref_m_usertype_id'] == AMICUS_CURIAE_USER) {
    /* if ($this->uri->segment(2) != 'affirmation') {
      unset($_SESSION['MOB_OTP_VERIFY_UPLOADED_DOCS']);
      }
      if ($_SESSION['login']['account_status'] != ACCOUNT_STATUS_ACTIVE && $_SESSION['login']['account_status'] != ACCOUNT_STATUS_UPDATED && $_SESSION['login']['account_status'] != ACCOUNT_STATUS_ACTIVE_BUT_OBJ && $_SESSION['login']['account_status'] != ACCOUNT_STATUS_ACTIVE_BUT_OBJ_CURED && $_SESSION['login']['bar_approval_status'] != BAR_APPROVAL_STATUS_DEACTIVATED) {
      echo "1"; die;
      include 'inactive_user_header.php';
      } elseif ($_SESSION['login']['bar_approval_status'] == BAR_APPROVAL_STATUS_DEACTIVATED || $_SESSION['login']['bar_approval_status'] == BAR_APPROVAL_STATUS_ON_HOLD) {
      echo"2"; die; include 'inactive_user_header.php';
      } else { echo "3"; die;
      include 'user_header.php';
      } */
    include 'user_header.php';
} elseif ($_SESSION['login']['ref_m_usertype_id'] == USER_PDE) {
    include 'pde_header.php';
} elseif ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK || $_SESSION['login']['ref_m_usertype_id'] == SR_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == AMICUS_CURIAE_USER) {
    include 'user_header.php';
} else {
    include 'admin_header.php';
}
?>