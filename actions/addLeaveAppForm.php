<?php



// Note that this is not used for submitting. (EMPLOYEE AND STAFF LEAVE FORM PAGE submission)
// USE THE SUBMIT EMPLOYEE LEAVE FORM . php for employee submission and staff submission
// USE EDIT LEAVE APP FORM .php for Validating Leave Request Approval or Disapproval



// This page are meant for the Admin For Adding Leave App Form

include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_employee);
include($constants_variables);

if (isset($_POST['submitLeaveAppForm']) && isset($_SESSION['employeeId'])) {
    $employeeId = strip_tags(mysqli_real_escape_string($database, $_SESSION['employeeId']));
    $departmentName = strip_tags(mysqli_real_escape_string($database, $_POST['departmentName']));
    $lastName = strip_tags(mysqli_real_escape_string($database, $_POST['lastName']));
    $firstName = strip_tags(mysqli_real_escape_string($database, $_POST['firstName']));
    $middleName = strip_tags(mysqli_real_escape_string($database, $_POST['middleName']));
    $dateFiling = strip_tags(mysqli_real_escape_string($database, $_POST['dateFiling']));
    $position = strip_tags(mysqli_real_escape_string($database, $_POST['position']));
    $salary = strip_tags(mysqli_real_escape_string($database, $_POST['salary']));
    $typeOfLeave = strip_tags(mysqli_real_escape_string($database, $_POST['typeOfLeave']));
    $typeOfSpecifiedOtherLeave = strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSpecifiedOtherLeave']));
    $typeOfVacationLeave = strip_tags(mysqli_real_escape_string($database, $_POST['typeOfVacationLeave']));
    $typeOfVacationLeaveWithin = strip_tags(mysqli_real_escape_string($database, $_POST['typeOfVacationLeaveWithin']));
    $typeOfVacationLeaveAbroad = strip_tags(mysqli_real_escape_string($database, $_POST['typeOfVacationLeaveAbroad']));
    $typeOfSickLeave = strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSickLeave']));
    $typeOfSickLeaveInHospital = strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSickLeaveInHospital']));
    $typeOfSickLeaveOutPatient = strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSickLeaveOutPatient']));
    $typeOfSickLeaveOutPatientOne = strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSickLeaveOutPatientOne']));
    $typeOfSpecialLeaveForWomen = strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSpecialLeaveForWomen']));
    $typeOfSpecialLeaveForWomenOne = strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSpecialLeaveForWomenOne']));
    $typeOfStudyLeave = strip_tags(mysqli_real_escape_string($database, $_POST['typeOfStudyLeave']));
    $typeOfOtherLeave = strip_tags(mysqli_real_escape_string($database, $_POST['typeOfOtherLeave']));
    $workingDays = strip_tags(mysqli_real_escape_string($database, $_POST['workingDays']));
    $inclusiveDates = strip_tags(mysqli_real_escape_string($database, $_POST['inclusiveDates']));
    $commutation = strip_tags(mysqli_real_escape_string($database, $_POST['commutation']));
    $asOfDate = strip_tags(mysqli_real_escape_string($database, $_POST['asOfDate']));
    $vacationLeaveTotalEarned = strip_tags(mysqli_real_escape_string($database, $_POST['vacationLeaveTotalEarned']));
    $sickLeaveTotalEarned = strip_tags(mysqli_real_escape_string($database, $_POST['sickLeaveTotalEarned']));
    $vacationLeaveLess = strip_tags(mysqli_real_escape_string($database, $_POST['vacationLeaveLess']));
    $sickLeaveLess = strip_tags(mysqli_real_escape_string($database, $_POST['sickLeaveLess']));
    $vacationLeaveBalance = strip_tags(mysqli_real_escape_string($database, $_POST['vacationLeaveBalance']));
    $sickLeaveBalance = strip_tags(mysqli_real_escape_string($database, $_POST['sickLeaveBalance']));
    $recommendation = strip_tags(mysqli_real_escape_string($database, $_POST['recommendation']));
    $recommendMessage = strip_tags(mysqli_real_escape_string($database, $_POST['recommendMessage']));
    $recommendMessageOne = strip_tags(mysqli_real_escape_string($database, $_POST['recommendMessageOne']));
    $recommendMessageTwo = strip_tags(mysqli_real_escape_string($database, $_POST['recommendMessageTwo']));
    $recommendMessageThree = strip_tags(mysqli_real_escape_string($database, $_POST['recommendMessageThree']));
    $recommendMessageFour = strip_tags(mysqli_real_escape_string($database, $_POST['recommendMessageFour']));
    $dayWithPay = strip_tags(mysqli_real_escape_string($database, $_POST['dayWithPay']));
    $dayWithoutPay = strip_tags(mysqli_real_escape_string($database, $_POST['dayWithoutPay']));
    $otherDayPay = strip_tags(mysqli_real_escape_string($database, $_POST['otherDayPay']));
    $otherDaySpecify = strip_tags(mysqli_real_escape_string($database, $_POST['otherDaySpecify']));
    $disapprovedMessage = strip_tags(mysqli_real_escape_string($database, $_POST['disapprovedMessage']));
    $disapprovedMessageOne = strip_tags(mysqli_real_escape_string($database, $_POST['disapprovedMessageOne']));
    $disapprovedMessageTwo = strip_tags(mysqli_real_escape_string($database, $_POST['disapprovedMessageTwo']));
    $status = 'Submitted';

    $recommendMessage = trim($recommendMessage) . ' ' . trim($recommendMessageOne) . ' ' . trim($recommendMessageTwo) . ' ' . trim($recommendMessageThree) . ' ' . trim($recommendMessageFour);
    $typeOfSickLeaveOutPatient = trim($typeOfSickLeaveOutPatient) . ' ' . trim($typeOfSickLeaveOutPatientOne);
    $typeOfSpecialLeaveForWomen = trim($typeOfSpecialLeaveForWomen) . ' ' . trim($typeOfSpecialLeaveForWomenOne);
    $disapprovedMessage = trim($disapprovedMessage) . ' ' . trim($disapprovedMessageOne) . ' ' . trim($disapprovedMessageTwo);

    if (empty($typeOfLeave) || empty($inclusiveDates)) {
        $_SESSION['alert_message'] = "Please Specify Your Type Leave and Inclusive Dates";
        $_SESSION['alert_type'] = $warning_color;
        header("Location: " . $location_employee_leave_form);
        exit();
    } else {
        try {
            // Check if typeOfVacationLeave is selected and if the required fields are filled
            if ($typeOfLeave === 'Vacation Leave' && empty($typeOfVacationLeaveWithin) && empty($typeOfVacationLeaveAbroad)) {
                $_SESSION['alert_message'] = "Please select either 'Within the Philippines' or 'Abroad' for Vacation Leave";
                $_SESSION['alert_type'] = $warning_color;
                header("Location: " . $location_employee_leave_form);
                exit();
            } else if ($typeOfLeave === 'Sick Leave' && empty($typeOfSickLeaveInHospital) && empty($typeOfSickLeaveInHospital)) {
                $_SESSION['alert_message'] = "Please select either 'In Hospital' or 'Out Patient' for Sick Leave";
                $_SESSION['alert_type'] = $warning_color;
                header("Location: " . $location_employee_leave_form);
                exit();
            } else if ($typeOfLeave === 'Special Leave Benefits for Women' && empty($typeOfSpecialLeaveForWomen)) {
                $_SESSION['alert_message'] = "Please select Specify Illness for Special Leave";
                $_SESSION['alert_type'] = $warning_color;
                header("Location: " . $location_employee_leave_form);
                exit();
            } else if ($typeOfLeave === 'Study Leave' && empty($typeOfStudyLeave)) {
                $_SESSION['alert_message'] = "Please select either 'Completion of Master's Degree or Bar / Board Examination Review' Incase for Study Leave";
                $_SESSION['alert_type'] = $warning_color;
                header("Location: " . $location_employee_leave_form);
                exit();
            } else if ($typeOfLeave === 'Others' && empty($typeOfOtherLeave)) {
                $_SESSION['alert_message'] = "Please select either 'Monetization of Leave Credit or Terminal Leave' Incase for Others";
                $_SESSION['alert_type'] = $warning_color;
                header("Location: " . $location_employee_leave_form);
                exit();
            }

            $query = "INSERT INTO tbl_leaveappform
            (employee_id, departmentName, lastName, firstName, middleName, dateFiling, position, salary,
            typeOfLeave, typeOfSpecifiedOtherLeave, typeOfVacationLeave, typeOfVacationLeaveWithin, typeOfVacationLeaveAbroad,
            typeOfSickLeave, typeOfSickLeaveInHospital, typeOfSickLeaveOutPatient, typeOfSpecialLeaveForWomen, typeOfStudyLeave,
            typeOfOtherLeave, workingDays, inclusiveDates, commutation,
            asOfDate, vacationLeaveTotalEarned, sickLeaveTotalEarned, vacationLeaveLess, sickLeaveLess,
            vacationLeaveBalance, sickLeaveBalance, recommendation, recommendMessage,
            dayWithPay, dayWithoutPay, otherDayPay, otherDaySpecify, disapprovedMessage, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($database, $query);

            mysqli_stmt_bind_param(
                $stmt,
                "sssssssssssssssssssisssddddddssiiisss",
                $employeeId,
                $departmentName,
                $lastName,
                $firstName,
                $middleName,
                $dateFiling,
                $position,
                $salary,
                $typeOfLeave,
                $typeOfSpecifiedOtherLeave,
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
                $inclusiveDates,
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
                $status
            );

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['alert_message'] = "Leave Application Form Successfully Created";
                $_SESSION['alert_type'] = $success_color;

                $notifEmpIdFrom = $_SESSION['employeeId'];
                $notifEmpIdTo = '@Admin';
                $notifSubject = $_SESSION['role'] . ' Submission of Leave Form';

                $notifMessage = $lastName . " " . $firstName . ' is Applying For ' . $typeOfLeave;
                $notifLink = $location_admin_leaveapplist;
                $notifSeen = 'unread';

                $queryNotif = "INSERT INTO tbl_notifications (dateCreated, empIdFrom, empIdTo, subject, message, link, seen) VALUES (CURRENT_TIMESTAMP(), ?, ?, ?, ?, ?, ?)";
                $stmtNotif = mysqli_prepare($database, $queryNotif);

                mysqli_stmt_bind_param($stmtNotif, "ssssss", $notifEmpIdFrom, $notifEmpIdTo, $notifSubject, $notifMessage, $notifLink, $notifSeen);

                mysqli_stmt_execute($stmtNotif);

                header("Location: " . $location_employee_leave_form);
                exit();
            } else {
                $_SESSION['alert_message'] = "Error submitting Leave Application Form: " . mysqli_stmt_error($stmt);
                $_SESSION['alert_type'] = $error_color;
            }
        } catch (Exception $e) {
            $_SESSION['alert_message'] = "An error occurred: " . $e->getMessage();
            $_SESSION['alert_type'] = $error_color;
            header("Location: " . $location_employee_leave_form);
            exit();
        }
    }
    header("Location: " . $location_employee_leave_form);
    exit();
} else {
    echo '<script type="text/javascript">window.history.back();</script>';
    header("Location: " . $location_employee_leave_form);
    exit();
}
?>