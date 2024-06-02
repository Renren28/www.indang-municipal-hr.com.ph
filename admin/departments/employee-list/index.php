<?php
include ("../../../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
include ($constants_variables);

$departmentlabel = "";
$departmentName = "";
$departments = getAllDepartments();
$designations = getAllDesignations();

if ($_GET['departmentlabel'] !== "index.php" && $_GET['departmentlabel'] !== "index.html") {
    $departmentlabel = sanitizeInput($_GET['departmentlabel']);
    $_SESSION['departmentlabel'] = $departmentlabel;
} else {
    if (isset($_SESSION['departmentlabel'])) {
        unset($_SESSION['departmentlabel']);
    }
}

if ($departmentlabel) {

    if (strcasecmp($departmentlabel, 'pending') == 0 || strcasecmp($departmentlabel, 'other') == 0 || strcasecmp($departmentlabel, 'others') == 0 || strcasecmp($departmentlabel, 'unassigned') == 0 || strcasecmp($departmentlabel, 'unassign') == 0) {
        $departmentName = "Pending and Unassigned";

        $empsql = "SELECT 
                        u.*, 
                        CASE 
                            WHEN UPPER(d.archive) = 'DELETED' THEN '' 
                            ELSE d.departmentName 
                        END AS departmentName,
                        CASE 
                            WHEN UPPER(desig.archive) = 'DELETED' THEN '' 
                            ELSE desig.designationName 
                        END AS designationName
                    FROM 
                        tbl_useraccounts u
                    LEFT JOIN 
                        tbl_departments d ON u.department = d.department_id
                    LEFT JOIN 
                        tbl_designations desig ON u.jobPosition = desig.designation_id
                    WHERE 
                        (d.department_id IS NULL OR UPPER(d.archive) = 'DELETED') 
                        AND UPPER(u.archive) != 'DELETED' AND UPPER(u.role) != 'ADMIN'
                    ORDER BY 
                        u.lastName ASC";

        $employees = $database->query($empsql);

    } else {
        for ($i = 0; $i < count($departments); $i++) {
            if ($departments[$i]['department_id'] == $departmentlabel) {
                $departmentName = $departments[$i]['departmentName'];
                break;
            }
        }

        $empsql = "SELECT
                        ua.*,
                        d.departmentName,
                        CASE 
                            WHEN UPPER(desig.archive) = 'DELETED' THEN '' 
                            ELSE desig.designationName 
                        END AS designationName
                    FROM
                        tbl_useraccounts ua
                    LEFT JOIN
                        tbl_departments d ON ua.department = d.department_id
                    LEFT JOIN
                        tbl_designations desig ON ua.jobPosition = desig.designation_id
                    WHERE
                        ua.department = ? AND UPPER(ua.archive) != 'DELETED' AND UPPER(d.archive) != 'DELETED' AND UPPER(ua.role) != 'ADMIN'
                    ORDER BY
                        ua.lastName ASC";

        $stmt = $database->prepare($empsql);
        $stmt->bind_param("s", $departmentlabel);
        $stmt->execute();

        $employees = $stmt->get_result();

    }

} else {
    $departmentName = "All Employees";
    $empsql = " SELECT
                    ua.*,
                    CASE
                        WHEN UPPER(d.archive) = 'DELETED' THEN ''
                        ELSE d.departmentName
                    END AS departmentName,
                    CASE
                        WHEN UPPER(desig.archive) = 'DELETED' THEN ''
                        ELSE desig.designationName
                    END AS designationName
                FROM
                    tbl_useraccounts ua
                LEFT JOIN
                    tbl_departments d ON ua.department = d.department_id
                LEFT JOIN
                    tbl_designations desig ON ua.jobPosition = desig.designation_id
                WHERE
                    UPPER(ua.archive) != 'DELETED' AND UPPER(ua.role) != 'ADMIN'
                ORDER BY
                    ua.lastName ASC;
                ";

    $employees = $database->query($empsql);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Human Resources of Municipality of Indang - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HR - Indang Municipality Admin Page">
    <?php
    include ($constants_file_html_credits);
    ?>
    <link rel="icon" type="image/x-icon" href="<?php echo $assets_logo_icon; ?>">

    <link rel="stylesheet" href="<?php echo $assets_bootstrap_vcss; ?>">
    <link rel="stylesheet" href="<?php echo $assets_bootstrap_css; ?>">
    <script src="<?php echo $assets_jquery; ?>"></script>
    <script src="<?php echo $assets_popper; ?>"></script>
    <script src='<?php echo $assets_bootstrap_js; ?>'></script>

    <link rel='stylesheet' href="<?php echo $assets_fontawesome; ?>">

    <link rel="stylesheet" href="<?php echo $assets_toastify_css; ?>">
    <script src="<?php echo $assets_toastify_js; ?>"></script>

    <link rel="stylesheet" href="<?php echo $assets_datatable_css; ?>">
    <script src="<?php echo $assets_datatable_js; ?>"></script>

    <link rel="stylesheet" href="<?php echo $assets_datatable_css_select; ?>">
    <script src="<?php echo $assets_datatable_js_select; ?>"></script>

    <link rel="stylesheet" href="<?php echo $assets_datatable_bootstrap; ?>">

    <link rel="stylesheet" href="<?php echo $assets_css_styles; ?>">

    <!-- <script src="<?php
    // echo $assets_tailwind; 
    ?>"></script> -->
</head>

<body class="webpage-background-cover-admin">
    <div>
        <?php include ($components_file_topnav) ?>
    </div>

    <!-- Add Modal -->
    <form action="<?php echo $action_add_employee; ?>" method="post" class="modal fade" id="addEmployee" tabindex="-1"
        role="dialog" aria-labelledby="addEmployeeTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLongTitle">Create New Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="<?php echo $departmentlabel; ?>" name="departmentlabel" />
                    <div class="row g-2 mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" name="employeeId" class="form-control" id="floatingEmployeeId"
                                    placeholder="TEMP0001" value="<?php echo $generatedEmpId; ?>" required>
                                <label for="floatingEmployeeId">Employee ID <span
                                        class="required-color">*</span></label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select name="role" class="form-select" id="floatingSelectRole"
                                    aria-label="Floating Role Selection" required>
                                    <option value="Employee" selected>Employee</option>
                                    <option value="Staff">Staff</option>
                                </select>
                                <label for="floatingSelectRole">Account Role <span
                                        class="required-color">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="email" name="email" class="form-control" id="floatingEmail"
                            placeholder="name@example.com" autocomplete="off" required>
                        <label for="floatingEmail">Email address <span class="required-color">*</span></label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="password" name="password" class="form-control" id="floatingPassword"
                            placeholder="Password" required>
                        <label for="floatingPassword">Password <span class="required-color">*</span></label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" name="firstName" class="form-control" id="floatingFirstName"
                            placeholder="Peter" required>
                        <label for="floatingFirstName">First Name <span class="required-color">*</span></label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" name="middleName" class="form-control" id="floatingMiddleName"
                            placeholder="Benjamin">
                        <label for="floatingMiddleName">Middle Name</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" name="lastName" class="form-control" id="floatingLastName"
                            placeholder="Parker" required>
                        <label for="floatingLastName">Last Name <span class="required-color">*</span></label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" name="suffix" class="form-control" id="floatingSuffix" placeholder="Jr.">
                        <label for="floatingSuffix">Suffix</label>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <select name="sex" class="form-select" id="floatingSex"
                                    aria-label="Floating Sex Selection" required>
                                    <option value="" selected></option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <label for="floatingSex">Sex <span class="required-color">*</span></label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select name="civilStatus" class="form-select" id="floatingCivilStatus"
                                    aria-label="Floating Civil Status Selection" required>
                                    <option value="Single" selected>Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Divorced">Divorced</option>
                                </select>
                                <label for="floatingCivilStatus">Civil Status <span
                                        class="required-color">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="date" name="birthdate" class="form-control" id="floatingBirthdate"
                            placeholder="01-01-2001" value="<?php // echo $loweredDateRange; ?>"
                            min="<?php echo $minDate; ?>" max="<?php echo $loweredDateRange; ?>" required>
                        <label for="floatingBirthdate">Date of Birth <span class="required-color">*</span></label>
                    </div>
                    <div class="form-floating mb-2">
                        <select name="department" class="form-select" id="floatingDepartmentSelect"
                            aria-label="Floating Department Selection" required>
                            <option value="" selected></option>
                            <?php
                            if (!empty($departments)) {
                                foreach ($departments as $department) {
                                    ?>
                                    <option value="<?php echo $department['department_id']; ?>" <?php echo ($department['department_id'] == $departmentlabel) ? 'selected' : ''; ?>>
                                        <?php echo $department['departmentName']; ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                            <option value="Pending" <?php echo (strcasecmp($departmentlabel, 'pending') == 0 || strcasecmp($departmentlabel, 'other') == 0 || strcasecmp($departmentlabel, 'others') == 0 || strcasecmp($departmentlabel, 'unassigned') == 0 || strcasecmp($departmentlabel, 'unassign') == 0) ? 'selected' : ''; ?>>Pending</option>
                        </select>
                        <label for="floatingDepartmentSelect">Department <span class="required-color">*</span></label>
                    </div>
                    <div class="form-floating mb-2">
                        <select name="jobPosition" class="form-select" id="floatingJobPosition"
                            aria-label="Floating Designation Selection" required>
                            <option value="" selected></option>
                            <?php
                            if (!empty($designations)) {
                                foreach ($designations as $designation) {
                                    ?>
                                    <option title="<?php echo $designation['designationDescription']; ?>"
                                        value="<?php echo $designation['designation_id']; ?>">
                                        <?php echo $designation['designationName']; ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <label for="floatingJobPosition">Job Title <span class="required-color">*</span></label>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="date" name="dateStarted" class="form-control" id="floatingDateStarted"
                                    placeholder="12-31-2001" value="<?php echo date('Y-m-d'); ?>"
                                    min="<?php echo $minDate; ?>" max="<?php echo $firstDayNextMonth; ?>" required>
                                <label for="floatingDateStarted">Date Started <span
                                        class="required-color">*</span></label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select name="status" class="form-select" id="floatingSelectStatus"
                                    aria-label="Floating Status Selection" required>
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Banned">Banned</option>
                                </select>
                                <label for="floatingSelectStatus">Status <span class="required-color">*</span></label>
                            </div>
                        </div>
                    </div>
                    <!-- Reason for Inactive and Banning -->
                    <div class="form-floating mb-2" id="reasonForStatusContainer">
                        <input type="text" name="reasonForStatus" class="form-control"
                            id="floatingReasonForStatus" placeholder="Enter Reason..." value="">
                        <label for="floatingReasonForStatus">Reason for Status <span
                                        class="required-color reasonStyle"></span></label>
                    </div>
                    <!-- Initialization if Date Started Month is Less Than the Month of Today  -->
                    <div class="form-floating mb-2">
                        <input type="number" step="any" name="initialVacationCredit" class="form-control"
                            id="floatingInitialVacationCredit" placeholder="1.25" value="1.25" min="0" max="750" required>
                        <label for="floatingInitialVacationCredit">Initial Vacation Credit <span
                                class="required-color">*</span></label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="number" step="any" name="initialSickCredit" class="form-control"
                            id="floatingInitialSickCredit" placeholder="1.25" value="1.25" max="750" required>
                        <label for="floatingInitialSickCredit">Initial Sick Credit <span
                                class="required-color">*</span></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="clearAddEmployeeInputs">Clear</button>
                    <input type="submit" name="addEmployee" value="Add Employee" class="btn btn-primary" />
                </div>
            </div>
        </div>
    </form>

    <!-- Edit Modal -->
    <form action="<?php echo $action_edit_employee; ?>" method="post" class="modal fade" id="editEmployee" tabindex="-1"
        role="dialog" aria-labelledby="editEmployeeTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLongTitle">Edit Employee Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="oldEmployeeId" id="floatingEditOldEmployeeId" />
                    <input type="hidden" value="<?php echo $departmentlabel; ?>" name="departmentlabel" />
                    <div class="row g-2 mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" name="employeeId" class="form-control" id="floatingEditEmployeeId"
                                    placeholder="TEMP0001" required readonly>
                                <label for="floatingEditEmployeeId">Employee ID <span
                                        class="required-color">*</span></label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select name="role" class="form-select" id="floatingEditSelectRole"
                                    aria-label="Floating Role Selection" required>
                                    <option value="Employee" selected>Employee</option>
                                    <option value="Staff">Staff</option>
                                </select>
                                <label for="floatingEditSelectRole">Account Role <span
                                        class="required-color">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="email" name="email" class="form-control" id="floatingEditEmail"
                            placeholder="name@example.com" autocomplete="off" required>
                        <label for="floatingEditEmail">Email address <span class="required-color">*</span></label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="password" name="password" class="form-control" id="floatingEditPassword"
                            placeholder="Password" required>
                        <label for="floatingEditPassword">Password <span class="required-color">*</span></label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" name="firstName" class="form-control" id="floatingEditFirstName"
                            placeholder="Peter" required>
                        <label for="floatingEditFirstName">First Name <span class="required-color">*</span></label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" name="middleName" class="form-control" id="floatingEditMiddleName"
                            placeholder="Benjamin">
                        <label for="floatingEditMiddleName">Middle Name</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" name="lastName" class="form-control" id="floatingEditLastName"
                            placeholder="Parker" required>
                        <label for="floatingEditLastName">Last Name <span class="required-color">*</span></label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" name="suffix" class="form-control" id="floatingEditSuffix" placeholder="Sr.">
                        <label for="floatingEditSuffix">Suffix</label>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <select name="sex" class="form-select" id="floatingEditSex"
                                    aria-label="Floating Sex Selection" required>
                                    <option value="" selected></option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <label for="floatingEditSex">Sex <span class="required-color">*</span></label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select name="civilStatus" class="form-select" id="floatingEditCivilStatus"
                                    aria-label="Floating Civil Status Selection" required>
                                    <option value="Single" selected>Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Divorced">Divorced</option>
                                </select>
                                <label for="floatingEditCivilStatus">Civil Status <span
                                        class="required-color">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="date" name="birthdate" class="form-control" id="floatingEditBirthdate"
                            placeholder="01-01-2001" value="<?php // echo date('Y-m-d'); ?>"
                            min="<?php echo $minDate; ?>" max="<?php echo $loweredDateRange; ?>" required>
                        <label for="floatingEditBirthdate">Date of Birth <span class="required-color">*</span></label>
                    </div>
                    <div class="form-floating mb-2">
                        <select name="department" class="form-select" id="floatingEditDepartmentSelect"
                            aria-label="Floating Department Selection" required>
                            <option value="" selected></option>
                            <?php
                            if (!empty($departments)) {
                                foreach ($departments as $department) {
                                    ?>
                                    <option value="<?php echo $department['department_id']; ?>">
                                        <?php echo $department['departmentName']; ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                            <option value="Pending">Pending</option>
                        </select>
                        <label for="floatingEditDepartmentSelect">Department <span
                                class="required-color">*</span></label>
                    </div>
                    <div class="form-floating mb-2">
                        <select name="jobPosition" class="form-select" id="floatingEditJobPosition"
                            aria-label="Floating Designation Selection" required>
                            <option value="" selected></option>
                            <?php
                            if (!empty($designations)) {
                                foreach ($designations as $designation) {
                                    ?>
                                    <option title="<?php echo $designation['designationDescription']; ?>"
                                        value="<?php echo $designation['designation_id']; ?>">
                                        <?php echo $designation['designationName']; ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <label for="floatingEditJobPosition">Job Title <span class="required-color">*</span></label>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="date" name="dateStarted" class="form-control" id="floatingEditDateStarted"
                                    placeholder="12-31-2001" value="<?php echo date('Y-m-d'); ?>"
                                    min="<?php echo $minDate; ?>" max="<?php echo $firstDayNextMonth; ?>" required>
                                <label for="floatingEditDateStarted">Date Started <span
                                        class="required-color">*</span></label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select name="status" class="form-select" id="floatingEditSelectStatus"
                                    aria-label="Floating Status Selection" required>
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Banned">Banned</option>
                                </select>
                                <label for="floatingEditSelectStatus">Status <span
                                        class="required-color">*</span></label>
                            </div>
                        </div>
                    </div>
                    <!-- Reason for Inactive and Banning -->
                    <div class="form-floating mb-2" id="reasonForStatusEditContainer">
                        <input type="text" name="reasonForStatus" class="form-control"
                            id="floatingEditReasonForStatus" placeholder="Enter Reason..." value="">
                        <label for="floatingEditReasonForStatus">Reason for Status <span
                                        class="required-color reasonStyle"></span></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="resetEditEmployeeInputs">Reset</button>
                    <input type="submit" name="editEmployee" value="Save Changes" class="btn btn-primary" />
                </div>
            </div>
        </div>
    </form>

    <!-- Multiple Edit Modal -->
    <!-- <form action="<?php echo $action_edit_employee; ?>" method="post" class="modal fade" id="editMultipleEmployee"
        tabindex="-1" role="dialog" aria-labelledby="editMultipleEmployeeTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMultipleEmployeeModalLongTitle">Multiple Data
                        Modification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="selectedEmpID[]" id="selectedEmpID" />
                    <input type="hidden" value="<?php echo $departmentlabel; ?>" name="departmentlabel" />
                    <div class="row g-2 mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <select name="role" class="form-select" id="floatingEditMultipleSelectRole"
                                    aria-label="Floating Role Selection">
                                    <option value="" selected></option>
                                    <option value="Employee">Employee</option>
                                    <option value="Staff">Staff</option>
                                </select>
                                <label for="floatingEditMultipleSelectRole">Account Role</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="date" name="dateStarted" class="form-control"
                                    id="floatingEditMultipleDateStarted" placeholder="12-31-2001">
                                <label for="floatingEditMultipleDateStarted">Date Started</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="number" name="age" class="form-control" id="floatingEditMultipleAge"
                                    min="0" max="125" placeholder="32">
                                <label for="floatingEditMultipleAge">Age</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select name="sex" class="form-select" id="floatingEditMultipleSex"
                                    aria-label="Floating Sex Selection">
                                    <option value="" selected></option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Prefer Not To Say">Prefer Not To Say</option>
                                </select>
                                <label for="floatingEditMultipleSex">Sex</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select name="civilStatus" class="form-select" id="floatingEditMultipleCivilStatus"
                                    aria-label="Floating Civil Status Selection">
                                    <option value="" selected></option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Divorced">Divorced</option>
                                </select>
                                <label for="floatingEditMultipleCivilStatus">Civil Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="password" name="password" class="form-control" id="floatingEditMultiplePassword"
                            placeholder="Password">
                        <label for="floatingEditMultiplePassword">Password</label>
                    </div>
                    <div class="form-floating mb-2">
                        <select name="department" class="form-select" id="floatingEditMultipleDepartmentSelect"
                            aria-label="Floating Department Selection">
                            <option value="" selected></option>
                            <?php
                            if (!empty($departments)) {
                                foreach ($departments as $department) {
                                    ?>
                                    <option value="<?php echo $department['department_id']; ?>">
                                        <?php echo $department['departmentName']; ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                            <option value="Pending">Pending</option>
                        </select>
                        <label for="floatingEditMultipleDepartmentSelect">Department</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" name="jobPosition" class="form-control" id="floatingEditMultipleJobPosition"
                            placeholder="IT Personnel">
                        <label for="floatingEditMultipleJobPosition">Job Position</label>
                    </div>
                    <div class="form-floating">
                        <select name="status" class="form-select" id="floatingEditMultipleStatus"
                            aria-label="Floating Multiple Status Selection" required>
                            <option value="" selected></option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Banned">Banned</option>
                        </select>
                        <label for="floatingEditSelectStatus">Status <span class="required-color">*</span></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" name="editMultipleEmployee" value="Save Changes" class="btn btn-primary" />
                </div>
            </div>
        </div>
    </form> -->

    <div class="page-container">
        <div class="page-content">

            <div class="box-container">
                <div>
                    <a href="<?php echo $location_admin_departments; ?>"><button
                            class="custom-regular-button">Back</button></a>
                    <div class="title-text">List of Employees</div>
                    <div class="title-text-caption">
                        (
                        <?php echo $departmentName; ?>)
                    </div>
                </div>

                <form method="POST" action="<?php echo $action_delete_employee; ?>">
                    <div class="button-container mb-2">
                        <input type="hidden" value="<?php echo $departmentlabel; ?>" name="departmentlabel" />
                        <!-- Add Button Modal -->
                        <button type="button" class="custom-regular-button" data-toggle="modal"
                            data-target="#addEmployee">
                            Add Employee
                        </button>
                        <!-- Multiple Edit Button Modal -->
                        <!--
                        <button type="button" class="custom-regular-button" id="editMultipleEmployeeBTN"
                            data-toggle="modal" data-target="#editMultipleEmployee">
                            Multiple Edit
                        </button>
                        -->
                        <!-- Multiple Delete -->
                        <input type="submit" name="deleteMultipleEmployee" id="deleteMultipleEmployeeBTN" value="Delete"
                            class="custom-regular-button" />
                    </div>

                    <table id="employees" class="text-center hover table-striped cell-border order-column"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Job Title</th>
                                <th>Sex</th>
                                <th>Age</th>
                                <th>Civil Status</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($employees->num_rows > 0) {
                                while ($row = $employees->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php if (strtoupper($row['role']) != "ADMIN") { ?>
                                                <input type="checkbox" name="selectedEmployee[]"
                                                    value="<?php echo $row['employee_id']; ?>" />
                                            <?php } else { ?>
                                                <input type="checkbox" name="disabledones"
                                                    value="<?php echo $row['employee_id']; ?>" disabled />
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo organizeFullName($row['firstName'], $row['middleName'], $row['lastName'], $row['suffix'], $order = 2);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['departmentName']) {
                                                echo $row['departmentName'];
                                            } else if (strcasecmp($row['department'], "Pending") == 0) {
                                                echo "Pending";
                                            } else {
                                                echo "Unassigned";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['designationName']) {
                                                echo $row['designationName'];
                                            } else {
                                                echo "Unassigned";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $row['sex']; ?>
                                        </td>
                                        <td>
                                            <?php echo identifyEmployeeAge($row['birthdate']); ?>
                                        </td>
                                        <td>
                                            <?php echo $row['civilStatus']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['status']; ?>
                                        </td>
                                        <td>
                                            <!-- <form method="POST" action="<?php echo $action_delete_employee; ?>"> -->
                                            <a
                                                href="<?php echo $location_admin_departments_employee . '/' . $row['employee_id'] . '/'; ?>">
                                                <button type="button" class="custom-regular-button">
                                                    View
                                                </button>
                                            </a>
                                            <!-- data-photo-url="<?php //echo $row['photoURL']; ?>" -->
                                            <button type="button" class="custom-regular-button editEmployeeButton"
                                                data-toggle="modal" data-target="#editEmployee"
                                                data-employee-id="<?php echo $row['employee_id']; ?>"
                                                data-role="<?php echo $row['role']; ?>"
                                                data-email="<?php echo $row['email']; ?>"
                                                data-password="<?php echo $row['password']; ?>"
                                                data-first-name="<?php echo $row['firstName']; ?>"
                                                data-middle-name="<?php echo $row['middleName']; ?>"
                                                data-last-name="<?php echo $row['lastName']; ?>"
                                                data-suffix="<?php echo $row['suffix']; ?>"
                                                data-sex="<?php echo $row['sex']; ?>"
                                                data-civil-status="<?php echo $row['civilStatus']; ?>"
                                                data-birthdate="<?php echo $row['birthdate']; ?>"
                                                data-department="<?php echo $row['department']; ?>"
                                                data-job-position="<?php echo $row['jobPosition']; ?>"
                                                data-date-started="<?php echo $row['dateStarted']; ?>"
                                                data-account-status="<?php echo $row['status']; ?>">
                                                Edit
                                            </button>

                                            <!-- <input type="hidden" name="employeeNum"
                                                    value="<?php echo $row['employee_id']; ?>" />
                                                <input type="hidden" value="<?php echo $departmentlabel; ?>"
                                                    name="departmentlabel" />
                                                <?php if ($row['employee_id'] != $_SESSION['employeeId']) { ?>
                                                    <input type="submit" name="deleteEmployee" value="Delete"
                                                        class="custom-regular-button" />
                                                <?php } ?> -->
                                            <!-- </form> -->
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>

                </form>

                <!-- Data Table Configuration -->
                <script>
                    let table = new DataTable('#employees', {
                        pagingType: 'full_numbers',
                        scrollCollapse: true,
                        scrollY: '100%',
                        scrollX: true,
                        // 'select': {
                        //     'style': 'multi',
                        // },
                        // ordering: false,
                        columnDefs: [
                            {
                                targets: [<?php if ($departmentlabel != "") {
                                    echo "2,";
                                } ?>5, 7], visible: false
                            },
                            {
                                'targets': 0,
                                'orderable': false,
                                // 'checkboxes': {
                                //     'selectRow': true,
                                //     // 'page': 'current',
                                // }
                            },
                            {
                                'targets': -1,
                                'orderable': false,
                                // 'checkboxes': {
                                //     'selectRow': true,
                                //     // 'page': 'current',
                                // }
                            },
                            // {
                            //     targets: [0],
                            //     orderData: [0, 1]
                            // },
                            // {
                            //     targets: [1],
                            //     orderData: [1, 0]
                            // },
                            // {
                            //     targets: [4],
                            //     orderData: [4, 0]
                            // }
                        ],
                        search: {
                            return: true
                        },
                        "dom": 'Blfrtip',
                        lengthMenu: [
                            [10, 25, 50, 100, -1],
                            [10, 25, 50, 100, 'All']
                        ],
                        // "colReorder": true,
                        "buttons": [
                            {
                                extend: 'copy',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'excel',
                                title: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                filename: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'csv',
                                title: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                filename: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'pdf',
                                title: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                filename: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                message: 'Produced and Prepared by the Human Resources System',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'print',
                                title: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                filename: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                message: 'Produced and Prepared by the Human Resources System',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                "extend": "colvis",
                                text: 'Column Visibility',
                                columns: ':first,:gt(0),:last'
                            }
                        ],
                        // responsive: true,
                    });
                </script>

                <!-- <button onclick="printSelectedValues()">Print Selected Values</button> -->
            </div>
        </div>
    </div>

    <script src="<?php echo $assets_file_employeeListing; ?>"></script>
    <div>
        <?php
        include ($components_file_footer);
        ?>
    </div>

    <?php include ($components_file_toastify); ?>

</body>

</html>