<?php
$webhostpath = 'C:\xampp\htdocs\www.indang-municipal-hr.com.ph'; // Files of the Includes and Requires
$webhostpage = '/www.indang-municipal-hr.com.ph'; // Location based of the Pages
// $webIphost = 'http://localhost/indang-sil-system'; //Complete Address and will be used for Notification and Other Fetches
$webResetPasswordMessageLink = 'http://localhost/www.indang-municipal-hr.com.ph/reset-password.php'; // Message Base Link For Password Reset

$action_user_login = $webhostpage . '/actions/userLogin.php';

$action_add_employee = $webhostpage . '/actions/addEmployee.php';
$action_edit_employee = $webhostpage . '/actions/editEmployee.php';
$action_delete_employee = $webhostpage . '/actions/deleteEmployee.php';
$action_retrieve_employee = $webhostpage . '/actions/retrieveEmployee.php';

$action_add_employeeInfo = $webhostpage . '/actions/addEmployeeInfo.php';
$action_update_admin = $webhostpage . '/actions/updateAdmin.php';

$action_add_department = $webhostpage . '/actions/addDepartment.php';
$action_edit_department = $webhostpage . '/actions/editDepartment.php';
$action_delete_department = $webhostpage . '/actions/deleteDepartment.php';
$action_retrieve_department = $webhostpage . '/actions/retrieveDepartment.php';

$action_add_leaverecorddata = $webhostpage . '/actions/addLeaveDataRecord.php';
$action_edit_leaverecorddata = $webhostpage . '/actions/editLeaveDataRecord.php';
$action_delete_leaverecorddata = $webhostpage . '/actions/deleteLeaveDataRecord.php';
$action_retrieve_leaverecorddata = $webhostpage . '/actions/retrieveLeaveDataRecord.php';

$action_employee_submit_leaveform = $webhostpage . '/actions/submitEmployeeLeaveForm.php';

$action_add_leaveappform = $webhostpage . '/actions/addLeaveAppForm.php';
$action_edit_leaveappform = $webhostpage . '/actions/editLeaveAppForm.php';
$action_delete_leaveappform = $webhostpage . '/actions/deleteLeaveAppForm.php';
$action_retrieve_leaveappform = $webhostpage . '/actions/retrieveLeaveAppForm.php';

$action_add_designation = $webhostpage . '/actions/addDesignation.php';
$action_edit_designation = $webhostpage . '/actions/editDesignation.php';
$action_delete_designation = $webhostpage . '/actions/deleteDesignation.php';
$action_retrieve_designation = $webhostpage . '/actions/retrieveDesignation.php';

$action_forgotpassword_mailer = $webhostpage . '/actions/forgotPasswordMailer.php';
$action_resetpassword = $webhostpage . '/actions/resetPassword.php';

$action_download_noaoml = $webhostpage. "/actions/downloadNOAOML.php";

$action_upload_leave_record = $webhostpage. "/actions/uploadLateRecord.php";

$action_update_password = $webhostpage . '/actions/updatePassword.php';
$action_update_system_setting = $webhostpage . '/actions/updateSystemSetting.php';

$components_file_error_handler = $webhostpath . '/components/error_handler.php';
$components_file_topnav = $webhostpath . '/components/topnavigation.php';
$components_file_footer = $webhostpath . '/components/footer.php';
$components_file_toastify = $webhostpath . '/components/toastifyAlert.php';
$components_file_navpanel = $webhostpath . '/components/navPanel.php';
$components_file_navpanelstaff = $webhostpath . '/components/navPanelStaff.php';
$components_file_formModal = $webhostpath . '/components/formModal.php';

$constants_file_dbconnect = $webhostpath . '/constants/dbconnect.php';
$constants_file_session_login = $webhostpath . '/constants/loginSession.php';
$constants_file_session_admin = $webhostpath . '/constants/adminSession.php';
$constants_file_session_staff = $webhostpath . '/constants/staffSession.php';
$constants_file_session_client = $webhostpath . '/constants/clientSession.php';
$constants_file_session_authorized = $webhostpath . '/constants/authorizedSession.php';
$constants_file_session_employee = $webhostpath . '/constants/employeeSession.php';
$constants_file_role_menu = $webhostpath . '/constants/roleMenu.php';
$constants_file_html_credits = $webhostpath . '/constants/htmlHead.php';
$constants_variables = $webhostpath . '/constants/globalVariable.php';

$assets_phpmailer = $webhostpath . '/assets/phpmailer/src/PHPMailer.php';
$assets_phpmailer_exception = $webhostpath . '/assets/phpmailer/src/Exception.php';
$assets_phpmailer_smtp = $webhostpath . '/assets/phpmailer/src/SMTP.php';

// Web Host Page - Assets
$assets_script_topnav = $webhostpage . '/assets/js/topnav.js';
$assets_logo_png = $webhostpage . '/assets/images/indang-logo.png';
$assets_logo_icon = $webhostpage . '/assets/images/indang-logo.ico';
$assets_bootstrap_vcss = $webhostpage . '/assets/bootstrap-5.0.2-dist/css/bootstrap.min.css';
$assets_bootstrap_vjs = $webhostpage . '/assets/bootstrap-5.0.2-dist/js/bootstrap.min.js';
$assets_bootstrap_css = $webhostpage . '/assets/bootstrap/dist/css/bootstrap.min.css';
$assets_bootstrap_js = $webhostpage . '/assets/bootstrap/dist/js/bootstrap.min.js';
$assets_jquery = $webhostpage . '/assets/bootstrap/assets/js/vendor/jquery-slim.min.js';
$assets_popper = $webhostpage . '/assets/bootstrap/assets/js/vendor/popper.min.js';
$assets_fontawesome = $webhostpage . '/assets/font-awesome/css/font-awesome.min.css';
$assets_css_styles = $webhostpage . '/assets/css/style.css';
$assets_css_printmedia = $webhostpage . '/assets/css/mediaprint.css';
$assets_datatable_css = $webhostpage . '/assets/datatables/datatables.min.css';
$assets_datatable_js = $webhostpage . '/assets/datatables/datatables.min.js';
$assets_datatable_bootstrap = $webhostpage . '/assets/datatables/DataTables-1.13.7/css/dataTables.bootstrap.css';
$assets_datatable_css_select = $webhostpage . '/assets/datatables/jquery-datatables-checkboxes-1.2.12/css/dataTables.checkboxes.css';
$assets_datatable_js_select = $webhostpage . '/assets/datatables/jquery-datatables-checkboxes-1.2.12/js/dataTables.checkboxes.min.js';
$assets_toastify_css = $webhostpage . '/assets/toastify/toastify.css';
$assets_toastify_js = $webhostpage . '/assets/toastify/toastify.js';
$assets_tailwind = $webhostpage . '/assets/js/tailwind.js';
$assets_file_leavedataform = $webhostpage . '/assets/js/leaveDataForm.js';
$assets_file_employeeListing = $webhostpage . '/assets/js/employeeListing.js';
$assets_file_leaveappform = $webhostpage . '/assets/js/leaveappform.js';
$assets_file_archive = $webhostpage . '/assets/js/archiveListing.js';
$assets_file_incharge_change = $webhostpage . '/assets/js/inChargeChange.js';
$assets_departmentlist_js = $webhostpage. '/assets/js/departmentList.js';
$assets_designationlist_js = $webhostpage. '/assets/js/designationListing.js';
$assets_monthlylaterecordlist_js = $webhostpage. '/assets/js/lateRecords.js';
$assets_adjustableTableCell_js = $webhostpage. '/assets/js/adjustableTableCell.js';

// Web Host Page - Pages
$location_login = $webhostpage;
$location_forgotpassword = $webhostpage . '/forgot-password.php';
$location_resetpassword = $webhostpage . '/reset-password.php';

$location_admin = $webhostpage . '/admin';
$location_admin_profile = $webhostpage . '/admin/profile';
$location_admin_departments = $webhostpage . '/admin/departments';
$location_admin_leaveapplist = $webhostpage . '/admin/leave-transaction';
$location_admin_leaveapplist_view = $webhostpage . '/admin/leave-transaction/view';
$location_admin_departments_office = $webhostpage . '/admin/departments/employee-list';

$location_admin_departments_employee = $webhostpage . '/admin/departments/employee-list/user-info';
$location_admin_departments_employee_leaveappform = $webhostpage . '/admin/departments/employee-list/leave-app-record';
$location_admin_departments_employee_leaveappform_view = $webhostpage . '/admin/departments/employee-list/leave-app-record/view';
$location_admin_departments_employee_leavedataform = $webhostpage . '/admin/departments/employee-list/leave-data-form';

$location_admin_datamanagement = $webhostpage . '/admin/data-management';
$location_admin_datamanagement_laterecords = $webhostpage . '/admin/data-management/late-records';
$location_admin_datamanagement_designation = $webhostpage . '/admin/data-management/designation';
$location_admin_datamanagement_leavetype = $webhostpage . '/admin/data-management/leave-types';
$location_admin_datamanagement_archive = $webhostpage . '/admin/data-management/archive';
$location_admin_datamanagement_archive_employee = $webhostpage . '/admin/data-management/archive/employee';
$location_admin_datamanagement_archive_department = $webhostpage . '/admin/data-management/archive/department';
$location_admin_datamanagement_archive_leaveform = $webhostpage . '/admin/data-management/archive/leaveform';
$location_admin_datamanagement_archive_leavedata = $webhostpage . '/admin/data-management/archive/leavedata';
$location_admin_datamanagement_archive_designation = $webhostpage . '/admin/data-management/archive/job';

$location_employee = $webhostpage . '/employee';
$location_employee_profile = $webhostpage . '/employee/profile';
$location_employee_leave_form = $webhostpage . '/employee/leaveform';
$location_employee_leave_form_record = $webhostpage . '/employee/leaveformrecord';
$location_employee_leave_form_record_view = $webhostpage . '/employee/leaveformrecord/view';
$location_employee_leave_form_record_edit = $webhostpage . '/employee/leaveformrecord/edit';
$location_employee_leave_data_form = $webhostpage . '/employee/leavedataform';

$location_staff = $webhostpage . '/staff';
$location_staff_profile = $webhostpage . '/staff/profile';
$location_staff_departments = $webhostpage . '/staff/departments';
$location_staff_leaveapplist = $webhostpage . '/staff/leave-transaction';
$location_staff_leaveapplist_view = $webhostpage . '/staff/leave-transaction/view';
$location_staff_departments_office = $webhostpage . '/staff/departments/employee-list';

$location_staff_departments_employee = $webhostpage . '/staff/departments/employee-list/user-info';
$location_staff_departments_employee_leaveappform = $webhostpage . '/staff/departments/employee-list/leave-app-record';
$location_staff_departments_employee_leaveappform_view = $webhostpage . '/staff/departments/employee-list/leave-app-record/view';
$location_staff_departments_employee_leavedataform = $webhostpage . '/staff/departments/employee-list/leave-data-form';

$location_staff_leave_form = $webhostpage . '/staff/leaveform';
$location_staff_leave_form_record = $webhostpage . '/staff/leaveformrecord';
$location_staff_leave_form_record_view = $webhostpage . '/staff/leaveformrecord/view';
$location_staff_leave_form_record_edit = $webhostpage . '/staff/leaveformrecord/edit';
$location_staff_leave_data_form = $webhostpage . '/staff/leavedataform';

?>