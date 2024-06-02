<?php
include ("../../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_staff);
include ($constants_variables);

$employeeData = [];
$departmentHeadData = [];
// $isLoginEmployeeIsHead = true;
$leaveData = [];
$ownerFormGender = "";
$ownerFormCivilStatus = "";

if (isset($_SESSION['employeeId'])) {
    $employeeId = sanitizeInput($_SESSION['employeeId']);
    $employeeData = getEmployeeData($employeeId);

    if (isset($employeeData['sex'])) {
        $ownerFormGender = $employeeData['sex'];
    }
    if (isset($employeeData['civilStatus'])) {
        $ownerFormCivilStatus = $employeeData['civilStatus'];
    }

    if (isset($employeeData['departmentHead']) && $employeeData['departmentHead'] !== "") {
        $departmentHeadData = getEmployeeData($employeeData['departmentHead']);
        // if ($departmentHeadData['employee_id'] === $employeeData['employee_id']) {
        //     $isLoginEmployeeIsHead = true;
        // }
    }

    $leaveData = getIncentiveLeaveComputation($employeeId);

}

$settingData = getAuthorizedUser();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Human Resources of Municipality of Indang - Staff</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HR - Indang Municipality Staff Page">
    <?php
    include ($constants_file_html_credits);
    ?>
    <link rel="icon" type="image/x-icon" href="<?php echo $assets_logo_icon; ?>">

    <link rel="stylesheet" href="<?php echo $assets_bootstrap_vcss; ?>">
    <script src="<?php echo $assets_bootstrap_vjs; ?>"></script>
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

    <script src="<?php echo $assets_file_leaveappform; ?>"></script>

    <!-- <script src="<?php
    // echo $assets_tailwind; 
    ?>"></script> -->
</head>

<body class="webpage-background-cover">
    <div class="component-container">
        <?php include ($components_file_topnav) ?>
    </div>

    <div class="page-container">
        <div class="page-content">

            <div class='box-container'>

                <div class="text-center font-weight-bold text-uppercase title-text component-container">
                    Leave Application Form
                </div>

                <?php if (strcasecmp($employeeData['status'], 'Active') == 0) { ?>

                    <?php
                    include ($components_file_formModal);
                    ?>

                    <div>
                        <button id="noaomlfLinkBtnDummy" class="custom-regular-button" disabled>Notice of Allocation Of
                            Maternity Leave Form
                        </button>
                    </div>

                    <a id="noaomlfLink" href="<?php echo $action_download_noaoml; ?>">
                        <button id="noaomlfLinkBtn" class="custom-regular-button">Notice of Allocation Of Maternity Leave
                            Form</button>
                    </a>

                    <form action="<?php echo $action_employee_submit_leaveform; ?>" method="post">
                        <div class="button-container component-container mb-2">
                            <input type="submit" name="submitLeaveAppForm" class="custom-regular-button"
                                value="Submit Leave Form" />
                        </div>

                        <div class="print-form-container my-4">
                            <div>
                                <div>CSC Form No. 6</div>
                                <div>Revised 2020</div>
                            </div>

                            <div class="leave-app-form-container-title">
                                <div>Application For Leave</div>
                            </div>

                            <div class="leave-app-form-container">
                                <!-- Department and Full Name -->
                                <div class="leave-app-form-first-row">
                                    <div class="leave-app-form-department-input-container">
                                        <label for="departmentInput" class="leave-app-form-label">1.
                                            Office/Department</label>
                                        <select id="departmentInput" name="departmentName"
                                            class="leave-app-form-input-plain">
                                            <?php
                                            if (($employeeData['department'] == "" || $employeeData['department'] == "Pending") && $employeeData['departmentName'] == "") {
                                                ?>
                                                <option value="Pending" selected>Pending</option>
                                                <?php
                                            } else if ($employeeData['department'] != "" && $employeeData['departmentName'] == "") {
                                                ?>
                                                    <option value="Unassigned" selected>Unassigned</option>
                                                <?php
                                            } else {
                                                ?>
                                                    <option value="<?php echo $employeeData['department']; ?>" selected>
                                                    <?php echo $employeeData['departmentName']; ?>
                                                    </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="leave-app-form-fullname-input-container">
                                        <div>
                                            <div class="leave-app-form-name-container leave-app-form-label">2. Name:</div>
                                        </div>
                                        <div class="leave-app-form-lastname-container">
                                            <label for="lastNameInput">(Last)</label>
                                            <input type="text" id="lastNameInput" name="lastName"
                                                class="leave-app-form-input-plain"
                                                value="<?php echo $employeeData['lastName']; ?>" readonly />
                                        </div>
                                        <div class="leave-app-form-firstname-container">
                                            <label for="firstNameInput">(First)</label>
                                            <input type="text" id="firstNameInput" name="firstName"
                                                class="leave-app-form-input-plain"
                                                value="<?php echo $employeeData['firstName']; ?>" readonly />
                                        </div>
                                        <div class="leave-app-form-middlename-container">
                                            <label for="middleNameInput">(Middle)</label>
                                            <input type="text" id="middleNameInput" name="middleName"
                                                class="leave-app-form-input-plain"
                                                value="<?php echo $employeeData['middleName']; ?>" readonly />
                                        </div>
                                    </div>
                                </div>
                                <!-- Date Filing, Position, Salary -->
                                <div class="leave-app-form-second-row">
                                    <div class="leave-app-form-filingdate-container">
                                        <label for="dateFilingInput" class="leave-app-form-label">3. Date of Filing</label>
                                        <input type="date" id="dateFilingInput" name="dateFiling"
                                            class="leave-app-form-input-grow" value="<?php echo date("Y-m-d"); ?>"
                                            readonly />
                                    </div>
                                    <div class="leave-app-form-position-container">
                                        <label for="positionInput" class="leave-app-form-label">4. Position</label>
                                        <input type="text" id="positionInput" name="position"
                                            class="leave-app-form-input-grow"
                                            value="<?php echo $employeeData['designationName']; ?>" readonly />
                                    </div>
                                    <div class="leave-app-form-salary-container">
                                        <label for="salaryInput" class="leave-app-form-label">5. Salary</label>
                                        <input type="text" id="salaryInput" name="salary" class="leave-app-form-input-grow"
                                            value="" />
                                    </div>
                                </div>
                                <div class="leave-app-form-label-head">
                                    6. Details of Application
                                </div>
                                <!-- Type of Leaves and Classification -->
                                <div class="leave-app-form-third-row">
                                    <div class="leave-app-form-leavetype-container">
                                        <div class='leave-app-form-third-row-head'>6.A Type of Leave to be Availed Of</div>

                                        <div class="leave-app-form-leavetype-detail-container">
                                            <input type='radio' id="vacationLeave" name="typeOfLeave" value="Vacation Leave"
                                                class="custom-checkbox-input" />
                                            <label for="vacationLeave" class='leave-app-form-detail-subject'>Vacation
                                                Leave</label>
                                            <span class="leave-app-form-leavetype-detail-context clickable-element"
                                                data-toggle="modal" data-target="#vacationLeaveModal">(Sec. 51, Rule XVI,
                                                Omnibus Rules Implementing E.O. No. 292)</span>
                                        </div>

                                        <div class="leave-app-form-leavetype-detail-container">
                                            <input type='radio' id="forcedLeave" name="typeOfLeave" value="Forced Leave"
                                                class="custom-checkbox-input" />
                                            <label for="forcedLeave" class='leave-app-form-detail-subject'>
                                                Mandatory / Forced
                                                Leave
                                            </label>
                                            <span class="leave-app-form-leavetype-detail-context clickable-element"
                                                data-toggle="modal" data-target="#forcedLeaveModal">
                                                (Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No.292)
                                            </span>
                                        </div>

                                        <div class="leave-app-form-leavetype-detail-container">
                                            <input type='radio' id="sickLeave" name="typeOfLeave" value="Sick Leave"
                                                class="custom-checkbox-input" />
                                            <label for="sickLeave" class='leave-app-form-detail-subject'> Sick
                                                Leave </label>
                                            <span class="leave-app-form-leavetype-detail-context clickable-element"
                                                data-toggle="modal" data-target="#sickLeaveModal">
                                                (Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)
                                            </span>
                                        </div>

                                        <div class="leave-app-form-leavetype-detail-container">
                                            <input type='radio' id="maternityLeave" name="typeOfLeave"
                                                value="Maternity Leave" class="custom-checkbox-input" <?php echo strtoupper($ownerFormGender) === 'FEMALE' ? '' : 'disabled'; ?> />
                                            <label for="maternityLeave" class='leave-app-form-detail-subject'>
                                                Maternity Leave
                                            </label>
                                            <span class="leave-app-form-leavetype-detail-context clickable-element"
                                                data-toggle="modal" data-target="#maternityLeaveModal">
                                                (R.A. No. 11210 / IRR issued by CSC, DOLE and SSS)
                                            </span>
                                        </div>

                                        <div class="leave-app-form-leavetype-detail-container">
                                            <input type='radio' id="paternityLeave" name="typeOfLeave"
                                                value="Paternity Leave" class="custom-checkbox-input" <?php echo strtoupper($ownerFormGender) === 'MALE' ? '' : 'disabled'; ?> />
                                            <label for="paternityLeave" class='leave-app-form-detail-subject'>
                                                Paternity Leave
                                            </label>
                                            <span class="leave-app-form-leavetype-detail-context clickable-element"
                                                data-toggle="modal" data-target="#paternityLeaveModal">
                                                (R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended)
                                            </span>
                                        </div>

                                        <div class="leave-app-form-leavetype-detail-container">
                                            <input type='radio' id="special" name="typeOfLeave"
                                                value="Special Privilege Leave" class="custom-checkbox-input" />
                                            <label for="special" class='leave-app-form-detail-subject'>
                                                Special Privilege Leave
                                            </label>
                                            <span class="leave-app-form-leavetype-detail-context clickable-element"
                                                data-toggle="modal" data-target="#specialLeaveModal">
                                                (Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292)
                                            </span>
                                        </div>

                                        <div class="leave-app-form-leavetype-detail-container">
                                            <input type='radio' id="soloParent" name="typeOfLeave" value="Solo Parent Leave"
                                                class="custom-checkbox-input" />
                                            <label for="soloParent" class='leave-app-form-detail-subject'>
                                                Solo Parent Leave
                                            </label>
                                            <span class="leave-app-form-leavetype-detail-context clickable-element"
                                                data-toggle="modal" data-target="#soloParentLeaveModal">
                                                (RA No. 8972 / CSC MC No. 8, s. 2004)
                                            </span>
                                        </div>

                                        <div class="leave-app-form-leavetype-detail-container">
                                            <input type='radio' id="studyLeave" name="typeOfLeave" value="Study Leave"
                                                class="custom-checkbox-input" />
                                            <label for="studyLeave" class='leave-app-form-detail-subject-small'>
                                                Doctorate Degree / Study Leave
                                            </label>
                                            <span class="leave-app-form-leavetype-detail-context-small clickable-element"
                                                data-toggle="modal" data-target="#studyLeaveModal">
                                                (Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292)
                                            </span>
                                        </div>

                                        <div class="leave-app-form-leavetype-detail-container">
                                            <input type='radio' id="vawcLeave" name="typeOfLeave" value="10-Day VAWC Leave"
                                                class="custom-checkbox-input" <?php echo strtoupper($ownerFormGender) === 'FEMALE' ? '' : 'disabled'; ?> />
                                            <label for="vawcLeave" class='leave-app-form-detail-subject'>
                                                10-Day VAWC Leave
                                            </label>
                                            <span class="leave-app-form-leavetype-detail-context clickable-element"
                                                data-toggle="modal" data-target="#VAWCLeaveModal">
                                                (RA No. 9262 / CSC MC No. 15, s. 2005)
                                            </span>
                                        </div>

                                        <div class="leave-app-form-leavetype-detail-container">
                                            <input type='radio' id="rehabilitation" name="typeOfLeave"
                                                value="Rehabilitation Privilege" class="custom-checkbox-input" />
                                            <label for="rehabilitation" class='leave-app-form-detail-subject'>
                                                Rehabilitation Privilege
                                            </label>
                                            <span class="leave-app-form-leavetype-detail-context clickable-element"
                                                data-toggle="modal" data-target="#rehabLeaveModal">
                                                (Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292)
                                            </span>
                                        </div>

                                        <div class="leave-app-form-leavetype-detail-container">
                                            <input type='radio' id="specialLeave" name="typeOfLeave"
                                                value="Special Leave Benefits for Women" class="custom-checkbox-input" <?php echo strtoupper($ownerFormGender) === 'FEMALE' ? '' : 'disabled'; ?> />
                                            <label for="specialLeave" class='leave-app-form-detail-subject'>
                                                Special Leave Benefits for Women
                                            </label>
                                            <span class="leave-app-form-leavetype-detail-context clickable-element"
                                                data-toggle="modal" data-target="#specialWomanLeaveModal">
                                                (RA No. 9710 / CSC MC No. 25, s. 2010)
                                            </span>
                                        </div>

                                        <div class="leave-app-form-leavetype-detail-container">
                                            <input type='radio' id="emergencyLeave" name="typeOfLeave"
                                                value="Special Emergency (Calamity) Leave" class="custom-checkbox-input" />
                                            <label for="emergencyLeave" class='leave-app-form-detail-subject'>
                                                Special Emergency (Calamity) Leave
                                            </label>
                                            <span class="leave-app-form-leavetype-detail-context clickable-element"
                                                data-toggle="modal" data-target="#emergencyLeaveModal">
                                                (CSC MC No. 2, s. 2012, as amended)
                                            </span>
                                        </div>

                                        <div class="leave-app-form-leavetype-detail-container">
                                            <input type='radio' id="adoptionLeave" name="typeOfLeave" value="Adoption Leave"
                                                class="custom-checkbox-input" />
                                            <label for="adoptionLeave" class='leave-app-form-detail-subject'>
                                                Adoption Leave
                                            </label>
                                            <span class="leave-app-form-leavetype-detail-context clickable-element"
                                                data-toggle="modal" data-target="#adoptionLeaveModal">
                                                (R.A. No. 8552)
                                            </span>
                                        </div>

                                        <div class="leave-app-form-otherleavetype-detail-container">
                                            <label for="otherTypeOfLeave"
                                                class="leave-app-form-detail-subject font-italic">Others:</label>
                                            <input type="text" id="otherTypeOfLeave" name="otherTypeOfLeave"
                                                class="leave-app-form-input-custom-width" />
                                        </div>

                                    </div>

                                    <div class="leave-app-form-leaveclass-container">
                                        <div class='leave-app-form-third-row-head'>6.B Details of Leave</div>
                                        <div
                                            class="leave-app-form-leaveclass-detail-container leave-app-form-detail-subject font-italic">
                                            In case of Vacation Leave:
                                        </div>
                                        <div class="leave-app-form-leaveclass-detail-container">
                                            <input type='radio' id="withinPhi" name="typeOfVacationLeave"
                                                value="Within the Philippines" class="custom-checkbox-input" />
                                            <label for="withinPhi" class='leave-app-form-detail-subject'>
                                                Within the Philippines
                                            </label>
                                            <input type="text" name="typeOfVacationLeaveWithin"
                                                class='leave-app-form-input-grow' />
                                        </div>
                                        <div class="leave-app-form-leaveclass-detail-container">
                                            <input type='radio' id="abroad" name="typeOfVacationLeave" value="Abroad"
                                                class="custom-checkbox-input" />
                                            <label for="abroad" class='leave-app-form-detail-subject'>
                                                Abroad (Specify)
                                            </label>
                                            <input type="text" name="typeOfVacationLeaveAbroad"
                                                class='leave-app-form-input-grow' />
                                        </div>
                                        <div
                                            class="leave-app-form-leaveclass-detail-container leave-app-form-detail-subject font-italic">
                                            In case of Sick Leave:
                                        </div>
                                        <div class="leave-app-form-leaveclass-detail-container">
                                            <input type='radio' id="inHospital" name="typeOfSickLeave" value="In Hospital"
                                                class="custom-checkbox-input" />
                                            <label for="inHospital" class='leave-app-form-detail-subject'>
                                                In Hospital (Specify Illness)
                                            </label>
                                            <input type="text" name="typeOfSickLeaveInHospital"
                                                class='leave-app-form-input-grow' />
                                        </div>
                                        <div class="leave-app-form-leaveclass-detail-container">
                                            <input type='radio' id="outPatient" name="typeOfSickLeave" value="Out Patient"
                                                class="custom-checkbox-input" />
                                            <label for="outPatient" class='leave-app-form-detail-subject'>
                                                Out Patient (Specify Illness)
                                            </label>
                                            <input type="text" name="typeOfSickLeaveOutPatient"
                                                class='leave-app-form-input-grow' />
                                        </div>
                                        <div class="leave-app-form-leaveclass-detail-container">
                                            <input type="text" name="typeOfSickLeaveOutPatientOne"
                                                class='leave-app-form-input' />
                                        </div>
                                        <div
                                            class="leave-app-form-leaveclass-detail-container leave-app-form-detail-subject font-italic">
                                            In case of Special Leave Benefits for Women:
                                        </div>
                                        <div class="leave-app-form-leaveclass-detail-container">
                                            <label for="specifyIllness" class='leave-app-form-detail-subject'>
                                                (Specify Illness)</label>
                                            <input id="specifyIllness" name="typeOfSpecialLeaveForWomen"
                                                class='leave-app-form-input-grow' />
                                        </div>
                                        <div class="leave-app-form-leaveclass-detail-container">
                                            <input type="text" name="typeOfSpecialLeaveForWomenOne"
                                                class='leave-app-form-input' />
                                        </div>
                                        <div
                                            class="leave-app-form-leaveclass-detail-container leave-app-form-detail-subject font-italic">
                                            In Case of Study Leave:
                                        </div>
                                        <div class="leave-app-form-leaveclass-detail-container">
                                            <input type='radio' id="mastersDegree" name="typeOfStudyLeave"
                                                value="Completion of Master Degree" class="custom-checkbox-input" />
                                            <label for="mastersDegree" class='leave-app-form-detail-subject'>
                                                Completion of Master's Degree
                                            </label>
                                        </div>
                                        <div class="leave-app-form-leaveclass-detail-container">
                                            <input type='radio' id="boardExam" name="typeOfStudyLeave"
                                                value="Board Examination Review" class="custom-checkbox-input" />
                                            <label for="boardExam" class='leave-app-form-detail-subject'>
                                                BAR / Board Examination Review
                                            </label>
                                        </div>
                                        <div
                                            class="leave-app-form-leaveclass-detail-container leave-app-form-detail-subject font-italic">
                                            Other Purpose:
                                        </div>
                                        <div class="leave-app-form-leaveclass-detail-container">
                                            <input type='radio' id="monetizationLeave" name="typeOfOtherLeave"
                                                value="Monetization of Leave Credit" class="custom-checkbox-input" />
                                            <label for="monetizationLeave" class='leave-app-form-detail-subject'>
                                                Monetization of Leave Credit
                                            </label>
                                        </div>
                                        <div class="leave-app-form-leaveclass-detail-container">
                                            <input type='radio' id="terminalLeave" name="typeOfOtherLeave"
                                                value="Terminal Leave" class="custom-checkbox-input" />
                                            <label for="terminalLeave" class='leave-app-form-detail-subject'>
                                                Terminal Leave
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Inclusive Date and Commutation -->
                                <div class="leave-app-form-fourth-row">
                                    <div class="leave-app-form-inclusivedate-container">
                                        <div class='leave-app-form-label'>
                                            <label for="workingDays">
                                                6.C Number of Working Days Applied For</label>
                                        </div>
                                        <div class="leave-app-form-inclusivedate-detail-container">
                                            <input type="number" step="any" min="0" max="3652" id="workingDays"
                                                name="workingDays" class='leave-app-form-input' value="1" />
                                        </div>
                                        <div class="leave-app-form-inclusivedate-detail-container">

                                            <label for="inclusiveDateStart" class='leave-app-form-label'>
                                                Inclusive Dates
                                            </label>
                                            <div class="leave-app-form-inclusivedate-input-container">
                                                <input type="date" id="inclusiveDateStart" name="inclusiveDateStart"
                                                    class='leave-app-form-input-plain'
                                                    value="<?php echo date('Y-m-d'); ?>" />
                                                <span class="inclusive-date-text">to</span>
                                                <input type="date" id="inclusiveDateEnd" name="inclusiveDateEnd"
                                                    class='leave-app-form-input-plain'
                                                    value="<?php echo date('Y-m-d'); ?>" />
                                            </div>

                                        </div>
                                    </div>
                                    <div class="leave-app-form-commutation-container">
                                        <div class='leave-app-form-label'>
                                            6.D Commutation
                                        </div>
                                        <div class="leave-app-form-commutation-detail-container">
                                            <input type='radio' id="notRequested" name="commutation" value="Not Requested"
                                                class="custom-checkbox-input" />
                                            <label for="notRequested" class='leave-app-form-detail-subject'>
                                                Not Requested
                                            </label>
                                        </div>
                                        <div class="leave-app-form-commutation-detail-container">
                                            <input type='radio' id="requested" name="commutation" value="Requested"
                                                class="custom-checkbox-input" />
                                            <label for="requested" class='leave-app-form-detail-subject'>
                                                Requested
                                            </label>
                                        </div>
                                        <div class="leave-app-form-signature-container">
                                            <!-- <input class="leave-app-form-input" readonly /> -->
                                            <div class='leave-app-form-signature-subject'>
                                                (Signature of Applicant)
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                // if ($isLoginEmployeeIsHead) {
                                if (true) {
                                    ?>
                                    <div class="leave-app-form-label-head">
                                        7. Details of Action On Application
                                    </div>
                                    <!-- HR Manager and Department Head -->
                                    <div class="leave-app-form-fifth-row">
                                        <div class="leave-app-form-hrmanager-container">
                                            <div class='leave-app-form-label'>
                                                7.A Certification of Leave Credits
                                            </div>
                                            <div class='leave-app-form-asofdate-container'>
                                                <label for="asOfDate">As of</label>
                                                <input type="date" id="asOfDate" name="asOfDate"
                                                    class='leave-app-form-asofdate-input'
                                                    value="<?php echo $employeeData['dateStarted']; ?>" disabled />
                                            </div>

                                            <div class="leave-app-form-leave-table-container">
                                                <div class="leave-app-form-leave-table-column-container">
                                                    <div class="leave-app-form-leave-table-field"></div>
                                                    <div class="leave-app-form-leave-table-field">Total Earned</div>
                                                    <div class="leave-app-form-leave-table-field">Less This Application</div>
                                                    <div class="leave-app-form-leave-table-field">Balance</div>
                                                </div>
                                                <div class="leave-app-form-leave-table-column-container">
                                                    <div class="leave-app-form-leave-table-field">Vacation Leave</div>
                                                    <div class="leave-app-form-leave-table-field"><input type="number"
                                                            name="vacationLeaveTotalEarned" class='leave-app-form-input-plain'
                                                            value="<?php echo !empty($leaveData) ? number_format($leaveData[count($leaveData) - 1]['vacationLeaveBalance'], 2) : 0 ?>"
                                                            disabled /></div>
                                                    <div class="leave-app-form-leave-table-field"><input type="number"
                                                            name="vacationLeaveLess" class='leave-app-form-input-plain'
                                                            disabled /></div>
                                                    <div class="leave-app-form-leave-table-field"><input type="number"
                                                            name="vacationLeaveBalance" class='leave-app-form-input-plain'
                                                            disabled />
                                                    </div>
                                                </div>
                                                <div class="leave-app-form-leave-table-column-container">
                                                    <div class="leave-app-form-leave-table-field">Sick Leave</div>
                                                    <div class="leave-app-form-leave-table-field"><input type="number"
                                                            name="sickLeaveTotalEarned" class='leave-app-form-input-plain'
                                                            value="<?php echo !empty($leaveData) ? number_format($leaveData[count($leaveData) - 1]['sickLeaveBalance'], 2) : 0 ?>"
                                                            disabled />
                                                    </div>
                                                    <div class="leave-app-form-leave-table-field"><input type="number"
                                                            name="sickLeaveLess" class='leave-app-form-input-plain' disabled />
                                                    </div>
                                                    <div class="leave-app-form-leave-table-field"><input type="number"
                                                            name="sickLeaveBalance" class='leave-app-form-input-plain'
                                                            disabled /></div>
                                                </div>
                                            </div>

                                            <div class="leave-app-form-signature-container">
                                                <!-- <input class="leave-app-form-input" readonly /> -->
                                                <div class="leave-app-form-signature-context mt-2">
                                                    <!-- <?php
                                                    if (count($settingData) > 0) {
                                                        for ($i = 0; $i < count($settingData); $i++) {
                                                            if ($settingData[$i]['settingSubject'] == "Human Resources Manager") {
                                                                echo organizeFullName($settingData[$i]['firstName'], $settingData[$i]['middleName'], $settingData[$i]['lastName'], $settingData[$i]['suffix']);
                                                            }
                                                        }
                                                    } else {
                                                        echo ' ';
                                                    }
                                                    ?> -->
                                                </div>
                                                <div class='leave-app-form-signature-subject'>
                                                    <!-- <?php
                                                    if (count($settingData) > 0) {
                                                        for ($i = 0; $i < count($settingData); $i++) {
                                                            if ($settingData[$i]['settingSubject'] == "Human Resources Manager") {
                                                                if ($settingData[$i]['jobPosition'] != "") {
                                                                    echo $settingData[$i]['jobPosition'];
                                                                } else {
                                                                    echo "Human Resources Manager";
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        echo "Human Resources Manager";
                                                    }
                                                    ?> -->
                                                    (Authorized Officer)
                                                </div>
                                            </div>

                                        </div>
                                        <div class="leave-app-form-departmenthead-container">
                                            <div class='leave-app-form-label'>
                                                7.B Recommendation
                                            </div>
                                            <div class="leave-app-form-departmenthead-detail-container">
                                                <input type='radio' id="forApproval" name="recommendation" value="For Approval"
                                                    class="custom-checkbox-input" disabled />
                                                <label for="forApproval" class='leave-app-form-detail-subject'>
                                                    For Approval
                                                </label>
                                            </div>

                                            <div class="leave-app-form-departmenthead-detail-container">
                                                <input type='radio' id="forDisapprovedDueToApproval" name="recommendation"
                                                    value="For Disapproved Due to" class="custom-checkbox-input" disabled />
                                                <label for="forDisapprovedDueToApproval" class='leave-app-form-detail-subject'>
                                                    For Disapproved Due to
                                                </label>
                                                <input type="text" name="recommendMessage" class='leave-app-form-input-grow'
                                                    disabled />
                                            </div>

                                            <div class="leave-app-form-departmenthead-detail-container-column">
                                                <input type="text" name="recommendMessageOne" class='leave-app-form-input'
                                                    disabled />
                                                <input type="text" name="recommendMessageTwo" class='leave-app-form-input'
                                                    disabled />
                                                <input type="text" name="recommendMessageThree" class='leave-app-form-input'
                                                    disabled />
                                                <input type="text" name="recommendMessageFour" class='leave-app-form-input'
                                                    disabled />
                                            </div>

                                            <div class="leave-app-form-signature-container">
                                                <!-- <input class="leave-app-form-input" readonly /> -->
                                                <!-- <div
                                                    class="leave-app-form-signature-context <?php echo empty($departmentHeadData) ? 'mt-4' : ''; ?>">
                                                    <?php
                                                    if (!empty($departmentHeadData)) {
                                                        echo organizeFullName($departmentHeadData['firstName'], $departmentHeadData['middleName'], $departmentHeadData['lastName'], $departmentHeadData['suffix']);
                                                    }
                                                    ?>
                                                </div> -->
                                                <div class="leave-app-form-signature-context mt-2"></div>
                                                <div class='leave-app-form-signature-subject'>
                                                    <!-- Department Head -->
                                                    (Authorized Officer)
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Municipal Mayor -->
                                    <div class="leave-app-form-sixth-row">
                                        <div class="leave-app-form-mayorapproval-container">
                                            <div class='leave-app-form-label'>
                                                7.C Approved For:
                                            </div>
                                            <div class="leave-app-form-approvaldays-detail-container">
                                                <input id="dayWithPay" type="number" name="dayWithPay"
                                                    class='leave-app-form-input-days' disabled />
                                                <label for="dayWithPay"> days with pay</label>
                                            </div>

                                            <div class="leave-app-form-approvaldays-detail-container">
                                                <input id="dayWithoutPay" type="number" name="dayWithoutPay"
                                                    class='leave-app-form-input-days' disabled />
                                                <label for="dayWithoutPay"> days without pay</label>
                                            </div>

                                            <div class="leave-app-form-approvaldays-detail-container">
                                                <div class="leave-app-form-otherdays-detail-container">
                                                    <input id="otherPay" type="number" name="otherDayPay"
                                                        class='leave-app-form-input-days' disabled />
                                                    <label for="otherPay">Others</label>
                                                </div>
                                                <div class="leave-app-form-otherdays-detail-container">
                                                    <label for="otherPaySpecify">(Specify)</label>
                                                    <input type="text" id="otherPaySpecify" name="otherDaySpecify"
                                                        class='leave-app-form-input-custom-width' disabled />
                                                </div>
                                            </div>

                                        </div>
                                        <div class="leave-app-form-mayordisapproval-container">
                                            <div class='leave-app-form-label'>
                                                7.D Disapproved Due To:
                                            </div>
                                            <div class="leave-app-form-disapprovemessage-detail-container">
                                                <input type="text" name="disapprovedMessage" class='leave-app-form-input'
                                                    disabled />
                                                <input type="text" name="disapprovedMessageOne" class='leave-app-form-input'
                                                    disabled />
                                                <input type="text" name="disapprovedMessageTwo" class='leave-app-form-input'
                                                    disabled />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Municipal Mayor Signature -->
                                    <div class="leave-app-form-seventh-row">
                                        <div class="leave-app-form-mayorsignature-container">
                                            <!-- <div class="leave-app-form-signature-context">
                                                <?php
                                                if (count($settingData) > 0) {
                                                    for ($i = 0; $i < count($settingData); $i++) {
                                                        if ($settingData[$i]['settingSubject'] == "Municipal Mayor") {
                                                            echo organizeFullName($settingData[$i]['firstName'], $settingData[$i]['middleName'], $settingData[$i]['lastName'], $settingData[$i]['suffix']);
                                                        }
                                                    }
                                                } else {
                                                    echo ' ';
                                                }
                                                ?>
                                            </div> -->
                                            <div class="leave-app-form-signature-context mt-2">
                                                <div class='leave-app-form-signature-subject'>
                                                    <!-- <?php
                                                    if (count($settingData) > 0) {
                                                        for ($i = 0; $i < count($settingData); $i++) {
                                                            if ($settingData[$i]['settingSubject'] == "Municipal Mayor") {
                                                                if ($settingData[$i]['jobPosition'] != "") {
                                                                    echo $settingData[$i]['jobPosition'];
                                                                } else {
                                                                    echo "Municipal Mayor";
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        echo "Municipal Mayor";
                                                    }
                                                    ?> -->
                                                    (Authorized Official)
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                }
                                ?>
                                </div>
                            </div>

                    </form>

                <?php } else {
                    ?>
                    <div class="title-text-caption">
                        (Your Account was not Illigible To Submit Form!)
                    </div>
                    <?php
                } ?>

            </div>
        </div>
    </div>

    <div class="component-container">
        <?php
        include ($components_file_footer);
        ?>
    </div>

    <?php include ($components_file_toastify); ?>

</body>

</html>