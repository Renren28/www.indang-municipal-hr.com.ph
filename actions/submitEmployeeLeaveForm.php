<?php

// This is USED for employee Submission and staff Submission of leave form

// USE EDIT LEAVE APP FORM .php for Validating Leave Request Approval or Disapproval

include ("../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_client);
include ($constants_variables);

$accountRole = "";

if (isset($_SESSION['employeeId'])) {
    $accountRole = strtolower(getAccountRole($_SESSION['employeeId']));
}

if (isset($_POST['submitLeaveAppForm']) && isset($_SESSION['employeeId'])) {
    // POST AND SESSION GET DATA FETCH
    $employeeId = strip_tags(mysqli_real_escape_string($database, $_SESSION['employeeId']));
    $departmentName = isset($_POST['departmentName']) ? strip_tags(mysqli_real_escape_string($database, $_POST['departmentName'])) : '';
    $lastName = isset($_POST['lastName']) ? strip_tags(mysqli_real_escape_string($database, $_POST['lastName'])) : '';
    $firstName = isset($_POST['firstName']) ? strip_tags(mysqli_real_escape_string($database, $_POST['firstName'])) : '';
    $middleName = isset($_POST['middleName']) ? strip_tags(mysqli_real_escape_string($database, $_POST['middleName'])) : '';
    $dateFiling = isset($_POST['dateFiling']) ? strip_tags(mysqli_real_escape_string($database, $_POST['dateFiling'])) : '';
    $position = isset($_POST['position']) ? strip_tags(mysqli_real_escape_string($database, $_POST['position'])) : '';
    $salary = isset($_POST['salary']) ? strip_tags(mysqli_real_escape_string($database, $_POST['salary'])) : '';
    $typeOfLeave = isset($_POST['typeOfLeave']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfLeave'])) : '';
    $otherTypeOfLeave = isset($_POST['otherTypeOfLeave']) ? strip_tags(mysqli_real_escape_string($database, $_POST['otherTypeOfLeave'])) : '';
    $typeOfVacationLeave = isset($_POST['typeOfVacationLeave']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfVacationLeave'])) : '';
    $typeOfVacationLeaveWithin = isset($_POST['typeOfVacationLeaveWithin']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfVacationLeaveWithin'])) : '';
    $typeOfVacationLeaveAbroad = isset($_POST['typeOfVacationLeaveAbroad']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfVacationLeaveAbroad'])) : '';
    $typeOfSickLeave = isset($_POST['typeOfSickLeave']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSickLeave'])) : '';
    $typeOfSickLeaveInHospital = isset($_POST['typeOfSickLeaveInHospital']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSickLeaveInHospital'])) : '';
    $typeOfSickLeaveOutPatient = isset($_POST['typeOfSickLeaveOutPatient']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSickLeaveOutPatient'])) : '';
    $typeOfSickLeaveOutPatientOne = isset($_POST['typeOfSickLeaveOutPatientOne']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSickLeaveOutPatientOne'])) : '';
    $typeOfSpecialLeaveForWomen = isset($_POST['typeOfSpecialLeaveForWomen']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSpecialLeaveForWomen'])) : '';
    $typeOfSpecialLeaveForWomenOne = isset($_POST['typeOfSpecialLeaveForWomenOne']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSpecialLeaveForWomenOne'])) : '';
    $typeOfStudyLeave = isset($_POST['typeOfStudyLeave']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfStudyLeave'])) : '';
    $typeOfOtherLeave = isset($_POST['typeOfOtherLeave']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfOtherLeave'])) : 0;
    $workingDays = isset($_POST['workingDays']) ? strip_tags(mysqli_real_escape_string($database, $_POST['workingDays'])) : '';
    $inclusiveDateStart = isset($_POST['inclusiveDateStart']) ? strip_tags(mysqli_real_escape_string($database, $_POST['inclusiveDateStart'])) : '';
    $inclusiveDateEnd = isset($_POST['inclusiveDateEnd']) ? strip_tags(mysqli_real_escape_string($database, $_POST['inclusiveDateEnd'])) : '';
    $commutation = isset($_POST['commutation']) ? strip_tags(mysqli_real_escape_string($database, $_POST['commutation'])) : '';
    $status = 'Submitted';

    // Combining of Text and Inputs
    $typeOfSickLeaveOutPatient = trim($typeOfSickLeaveOutPatient) . ' ' . trim($typeOfSickLeaveOutPatientOne);
    $typeOfSpecialLeaveForWomen = trim($typeOfSpecialLeaveForWomen) . ' ' . trim($typeOfSpecialLeaveForWomenOne);

    // Empty Variable Containers
    $employeeData = [];
    $leaveData = [];
    $settingData = [];
    $departmentHeadData = [];

    // Checks the Input of the Leave Application Form
    if (empty($typeOfLeave) || empty($inclusiveDateStart) || empty($inclusiveDateEnd)) {
        $_SESSION['alert_message'] = "Please Specify Your Type Leave and Inclusive Dates";
        $_SESSION['alert_type'] = $warning_color;

        if ($accountRole == "employee") {
            header("Location: " . $location_employee_leave_form);
        } else if ($accountRole == "staff") {
            header("Location: " . $location_staff_leave_form);
        } else {
            header("Location: " . $location_login);
        }
        exit();
    } else {
        try {
            if ($typeOfLeave === 'Vacation Leave' && empty($typeOfVacationLeave) && (empty($typeOfVacationLeaveWithin) || empty($typeOfVacationLeaveAbroad))) {
                $_SESSION['alert_message'] = "Please select either 'Within the Philippines' or 'Abroad' for Vacation Leave";
                $_SESSION['alert_type'] = $warning_color;
                if ($accountRole == "employee") {
                    header("Location: " . $location_employee_leave_form);
                } else if ($accountRole == "staff") {
                    header("Location: " . $location_staff_leave_form);
                } else {
                    header("Location: " . $location_login);
                }
                exit();
            } else if ($typeOfLeave === 'Sick Leave' && empty($typeOfSickLeave) && (empty($typeOfSickLeaveInHospital) || empty($typeOfSickLeaveOutPatient))) {
                $_SESSION['alert_message'] = "Please select either 'In Hospital' or 'Out Patient' for Sick Leave";
                $_SESSION['alert_type'] = $warning_color;
                if ($accountRole == "employee") {
                    header("Location: " . $location_employee_leave_form);
                } else if ($accountRole == "staff") {
                    header("Location: " . $location_staff_leave_form);
                } else {
                    header("Location: " . $location_login);
                }
                exit();
            } else if ($typeOfLeave === 'Special Leave Benefits for Women' && empty($typeOfSpecialLeaveForWomen)) {
                $_SESSION['alert_message'] = "Please select Specify Illness for Special Leave";
                $_SESSION['alert_type'] = $warning_color;
                if ($accountRole == "employee") {
                    header("Location: " . $location_employee_leave_form);
                } else if ($accountRole == "staff") {
                    header("Location: " . $location_staff_leave_form);
                } else {
                    header("Location: " . $location_login);
                }
                exit();
            } else if ($typeOfLeave === 'Study Leave' && empty($typeOfStudyLeave)) {
                $_SESSION['alert_message'] = "Please select either 'Completion of Master's Degree or Bar / Board Examination Review' Incase for Study Leave";
                $_SESSION['alert_type'] = $warning_color;
                if ($accountRole == "employee") {
                    header("Location: " . $location_employee_leave_form);
                } else if ($accountRole == "staff") {
                    header("Location: " . $location_staff_leave_form);
                } else {
                    header("Location: " . $location_login);
                }
                exit();
            } else if ($typeOfLeave === 'Others' && empty($typeOfOtherLeave)) {
                $_SESSION['alert_message'] = "Please select either 'Monetization of Leave Credit or Terminal Leave' Incase for Others";
                $_SESSION['alert_type'] = $warning_color;
                if ($accountRole == "employee") {
                    header("Location: " . $location_employee_leave_form);
                } else if ($accountRole == "staff") {
                    header("Location: " . $location_staff_leave_form);
                } else {
                    header("Location: " . $location_login);
                }
                exit();
            }

            // Generates Leave Application Id
            $randomBytes = random_bytes(25);
            $randomHex = bin2hex($randomBytes);

            $checkLeaveFormIdQuery = "SELECT * FROM tbl_leaveappform WHERE leaveappform_id = '$randomHex'";
            $result = mysqli_query($database, $checkLeaveFormIdQuery);

            while (mysqli_num_rows($result) > 0) {
                $randomBytes = random_bytes(25);
                $randomHex = bin2hex($randomBytes);

                $checkLeaveFormIdQuery = "SELECT * FROM tbl_leaveappform WHERE leaveappform_id = '$randomHex'";
                $result = mysqli_query($database, $checkLeaveFormIdQuery);
            }

            $leaveappformId = $randomHex;

            // Gets data from the database to process and will be passed as new data to the database.
            $employeeData = getEmployeeData($employeeId);

            $typeOfLeaveLower = strtolower($typeOfLeave);
            $employeeSexUpper = strtoupper($employeeData['sex']);
            
            if ((in_array($typeOfLeaveLower, ["maternity leave", "10-day vawc leave", "special leave benefits for women"]) && $employeeSexUpper != "FEMALE")) {
                $_SESSION['alert_message'] = "You cannot apply for " . $typeOfLeave . " due to being not a Female Character!";
                $_SESSION['alert_type'] = $warning_color;
                if ($accountRole == "employee") {
                    header("Location: " . $location_employee_leave_form);
                } else if ($accountRole == "staff") {
                    header("Location: " . $location_staff_leave_form);
                } else {
                    header("Location: " . $location_login);
                }
                exit();
            } else if ($typeOfLeaveLower == "paternity leave" && $employeeSexUpper != "MALE") {
                $_SESSION['alert_message'] = "You cannot apply for " . $typeOfLeave . " due to being not a Male Character!";
                $_SESSION['alert_type'] = $warning_color;
                if ($accountRole == "employee") {
                    header("Location: " . $location_employee_leave_form);
                } else if ($accountRole == "staff") {
                    header("Location: " . $location_staff_leave_form);
                } else {
                    header("Location: " . $location_login);
                }
                exit();
            }       

            if (isset($employeeData['departmentHead']) && $employeeData['departmentHead'] !== "") {
                $departmentHeadData = getEmployeeData($employeeData['departmentHead']);
            }
            // $leaveData = getIncentiveLeaveComputation($employeeId);
            $settingData = getAuthorizedUser();
            // Can either be not used
            if (($employeeData['department'] == "" || $employeeData['department'] == "Pending") && $employeeData['departmentName'] == "") {
                $departmentName = "Pending";
            } else if ($employeeData['department'] != "" && $employeeData['departmentName'] == "") {
                $departmentName = "Unassigned";
            } else {
                $departmentName = $employeeData['departmentName'];
            }
            $lastName = $employeeData['lastName'];
            $firstName = $employeeData['firstName'];
            $middleName = $employeeData['middleName'];
            $dateFiling = date("Y-m-d");
            $position = $employeeData['designationName'];
            $status = 'Submitted';
            // Automatic Generation Based on the Data
            $asOfDate = $employeeData['dateStarted'];
            // Include
            $vacationLeaveTotalEarned = 0;
            $sickLeaveTotalEarned = 0;
            $vacationLeaveLess = 0;
            $sickLeaveLess = 0;
            $vacationLeaveBalance = 0;
            $sickLeaveBalance = 0;

            // Disinclude if Needed
            // $vacationLeaveTotalEarned = !empty($leaveData) ? number_format($leaveData[count($leaveData) - 1]['vacationLeaveBalance'], 2) : 0;
            // $sickLeaveTotalEarned = !empty($leaveData) ? number_format($leaveData[count($leaveData) - 1]['sickLeaveBalance'], 2) : 0;
            // $vacationLeaveLess = $typeOfLeave === "Vacation Leave" ? $workingDays : 0;
            // $sickLeaveLess = $typeOfLeave === "Sick Leave" ? $workingDays : 0;
            // $vacationLeaveBalance = $typeOfLeave === "Vacation Leave" ? number_format($vacationLeaveTotalEarned - $workingDays, 2) : 0;
            // $sickLeaveBalance = $typeOfLeave === "Sick Leave" ? number_format($sickLeaveTotalEarned - $workingDays, 2) : 0;

            // None
            $recommendation = "";
            $recommendMessage = "";
            $dayWithPay = 0;
            $dayWithoutPay = 0;
            $otherDayPay = 0;
            $otherDaySpecify = "";
            $disapprovedMessage = "";
            // Officials
            $hrName = "";
            $hrPosition = "";
            $hrmanager_id = "";
            if (count($settingData) > 0) {
                for ($i = 0; $i < count($settingData); $i++) {
                    if ($settingData[$i]['settingSubject'] == "Human Resources Manager") {
                        $hrName = organizeFullName($settingData[$i]['firstName'], $settingData[$i]['middleName'], $settingData[$i]['lastName'], $settingData[$i]['suffix']);
                        $hrPosition = $settingData[$i]['jobPosition'] != '' ? $settingData[$i]['jobPosition'] : 'Human Resources Manager';
                        $hrmanager_id = $settingData[$i]['settingKey'];
                    }
                }
            }
            $deptHeadName = "";
            $depthead_id = "";
            if (!empty($departmentHeadData)) {
                $deptHeadName = organizeFullName($departmentHeadData['firstName'], $departmentHeadData['middleName'], $departmentHeadData['lastName'], $departmentHeadData['suffix']);
                $depthead_id = $departmentHeadData['employee_id'];
            }

            $mayorName = "";
            $mayorPosition = "";
            $mayor_id = "";
            if (count($settingData) > 0) {
                for ($i = 0; $i < count($settingData); $i++) {
                    if ($settingData[$i]['settingSubject'] == "Municipal Mayor") {
                        $mayorName = organizeFullName($settingData[$i]['firstName'], $settingData[$i]['middleName'], $settingData[$i]['lastName'], $settingData[$i]['suffix']);
                        $mayorPosition = $settingData[$i]['jobPosition'] != '' ? $settingData[$i]['jobPosition'] : 'Municipal Mayor';
                        $mayor_id = $settingData[$i]['settingKey'];
                    }
                }
            }

            $hrName = "";
            $hrPosition = "";
            $hrmanager_id = "";
            $deptHeadName = "";
            $depthead_id = "";
            $mayorName = "";
            $mayorPosition = "";
            $mayor_id = "";

            // Adding the Data to the Database
            $query = "INSERT INTO tbl_leaveappform
            (leaveappform_id, employee_id, departmentName, lastName, firstName, middleName, dateFiling, position, salary,
            typeOfLeave, typeOfSpecifiedOtherLeave, typeOfVacationLeave, typeOfVacationLeaveWithin, typeOfVacationLeaveAbroad,
            typeOfSickLeave, typeOfSickLeaveInHospital, typeOfSickLeaveOutPatient, typeOfSpecialLeaveForWomen, typeOfStudyLeave,
            typeOfOtherLeave, workingDays, inclusiveDateStart, inclusiveDateEnd, commutation,
            asOfDate, vacationLeaveTotalEarned, sickLeaveTotalEarned, vacationLeaveLess, sickLeaveLess,
            vacationLeaveBalance, sickLeaveBalance, recommendation, recommendMessage,
            dayWithPay, dayWithoutPay, otherDayPay, otherDaySpecify, disapprovedMessage,
            hrName, hrPosition, deptHeadName, mayorName, mayorPosition, hrmanager_id, depthead_id, mayor_id, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($database, $query);

            mysqli_stmt_bind_param(
                $stmt,
                "ssssssssssssssssssssissssddddddssiiisssssssssss",
                $leaveappformId,
                $employeeId,
                $departmentName,
                $lastName,
                $firstName,
                $middleName,
                $dateFiling,
                $position,
                $salary,

                $typeOfLeave,
                $otherTypeOfLeave,
                $typeOfVacationLeave,
                $typeOfVacationLeaveWithin,
                $typeOfVacationLeaveAbroad,

                $typeOfSickLeave,
                $typeOfSickLeaveInHospital,
                $typeOfSickLeaveOutPatient,
                $typeOfSpecialLeaveForWomen,
                $typeOfStudyLeave,

                $typeOfOtherLeave,
                $workingDays,
                $inclusiveDateStart,
                $inclusiveDateEnd,
                $commutation,

                $asOfDate,
                $vacationLeaveTotalEarned,
                $sickLeaveTotalEarned,
                $vacationLeaveLess,
                $sickLeaveLess,

                $vacationLeaveBalance,
                $sickLeaveBalance,
                $recommendation,
                $recommendMessage,

                $dayWithPay,
                $dayWithoutPay,
                $otherDayPay,
                $otherDaySpecify,
                $disapprovedMessage,

                $hrName,
                $hrPosition,
                $deptHeadName,
                $mayorName,
                $mayorPosition,
                $hrmanager_id,
                $depthead_id,
                $mayor_id,
                $status
            );

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['alert_message'] = "Leave Application Form Successfully Created";
                $_SESSION['alert_type'] = $success_color;

                // Notification
                $notifEmpIdFrom = $_SESSION['employeeId'];
                $notifEmpIdTo = '@Admin';
                $notifSubject = $_SESSION['role'] . ' Leave Form Request';

                $notifMessage = $lastName . " " . $firstName . ' is Applying For ' . $typeOfLeave;
                $notifLink = "";
                // $notifLink = $location_admin_leaveapplist . '/view/' . $leaveappformId;
                $notifSeen = 'unseen';

                $queryNotif = "INSERT INTO tbl_notifications (dateCreated, empIdFrom, empIdTo, subject, message, subjectKey, link, status) VALUES (CURRENT_TIMESTAMP(), ?, ?, ?, ?, ?, ?, ?)";
                $stmtNotif = mysqli_prepare($database, $queryNotif);

                mysqli_stmt_bind_param($stmtNotif, "sssssss", $notifEmpIdFrom, $notifEmpIdTo, $notifSubject, $notifMessage, $leaveappformId, $notifLink, $notifSeen);

                mysqli_stmt_execute($stmtNotif);

                if ($accountRole == "employee") {
                    header("Location: " . $location_employee_leave_form_record);
                } else if ($accountRole == "staff") {
                    header("Location: " . $location_staff_leave_form_record);
                } else {
                    header("Location: " . $location_login);
                }
                exit();
            } else {
                $_SESSION['alert_message'] = "Error submitting Leave Application Form: " . mysqli_stmt_error($stmt);
                $_SESSION['alert_type'] = $error_color;
            }
        } catch (Exception $e) {
            $_SESSION['alert_message'] = "An error occurred: " . $e->getMessage();
            $_SESSION['alert_type'] = $error_color;
            echo $e;
            if ($accountRole == "employee") {
                header("Location: " . $location_employee_leave_form);
            } else if ($accountRole == "staff") {
                header("Location: " . $location_staff_leave_form);
            } else {
                header("Location: " . $location_login);
            }
            exit();
        }
    }
} else {
    // echo '<script type="text/javascript">window.history.back();</script>';
    if ($accountRole == "employee") {
        header("Location: " . $location_employee_leave_form);
    } else if ($accountRole == "staff") {
        header("Location: " . $location_staff_leave_form);
    } else {
        header("Location: " . $location_login);
    }
    exit();
}
?>