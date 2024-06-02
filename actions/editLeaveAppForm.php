<?php

// This is USED for Validating Leave Request Approval or Disapproval

// USE THE SUBMIT EMPLOYEE LEAVE FORM . php for employee submission and staff submission

include ("../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_authorized);
include ($constants_variables);

$accountRole = "";

if (isset($_SESSION['employeeId'])) {
    $accountRole = strtolower(getAccountRole($_SESSION['employeeId']));
}

if (isset($_POST['validateLeaveAppForm'])) {
    // POST AND SESSION GET DATA FETCH
    $initial = "Initial Record";
    $initialRecordData = [];

    $completeField = false;
    $completeVerification = false;

    $employeeData = [];

    if (isset($_SESSION['employeeId'])) {
        $employeeId = sanitizeInput($_SESSION['employeeId']);
        $employeeData = getEmployeeData($employeeId);
    }

    $fullName = organizeFullName($employeeData['firstName'], $employeeData['middleName'], $employeeData['lastName'], $employeeData['suffix']) ?? "";
    $hrName = $fullName;
    $hrPosition = "Authorized Officer";
    $deptHeadName = $fullName;
    $mayorName = $fullName;
    $mayorPosition = "Authorized Official";
    $hrmanager_id = $employeeData['employee_id'] ?? "";
    $depthead_id = $employeeData['employee_id'] ?? "";
    $mayor_id = $employeeData['employee_id'] ?? "";

    $leaveappformId = strip_tags(mysqli_real_escape_string($database, $_POST['leaveappformId']));
    $ownerOfForm = strip_tags(mysqli_real_escape_string($database, $_POST['ownerOfForm']));
    $departmentName = isset($_POST['departmentName']) ? strip_tags(mysqli_real_escape_string($database, $_POST['departmentName'])) : '';
    $lastName = isset($_POST['lastName']) ? strip_tags(mysqli_real_escape_string($database, $_POST['lastName'])) : '';
    $firstName = isset($_POST['firstName']) ? strip_tags(mysqli_real_escape_string($database, $_POST['firstName'])) : '';
    $middleName = isset($_POST['middleName']) ? strip_tags(mysqli_real_escape_string($database, $_POST['middleName'])) : '';
    $dateFiling = isset($_POST['dateFiling']) ? strip_tags(mysqli_real_escape_string($database, $_POST['dateFiling'])) : '';
    $position = isset($_POST['position']) ? strip_tags(mysqli_real_escape_string($database, $_POST['position'])) : '';
    $salary = isset($_POST['salary']) ? strip_tags(mysqli_real_escape_string($database, $_POST['salary'])) : '';
    $typeOfLeave = isset($_POST['typeOfLeave']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfLeave'])) : '';
    $typeOfSpecifiedOtherLeave = isset($_POST['typeOfSpecifiedOtherLeave']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfSpecifiedOtherLeave'])) : '';
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
    $typeOfOtherLeave = isset($_POST['typeOfOtherLeave']) ? strip_tags(mysqli_real_escape_string($database, $_POST['typeOfOtherLeave'])) : '';
    $workingDays = isset($_POST['workingDays']) ? strip_tags(mysqli_real_escape_string($database, $_POST['workingDays'])) : '';
    $inclusiveDateStart = isset($_POST['inclusiveDateStart']) ? strip_tags(mysqli_real_escape_string($database, $_POST['inclusiveDateStart'])) : '';
    $inclusiveDateEnd = isset($_POST['inclusiveDateEnd']) ? strip_tags(mysqli_real_escape_string($database, $_POST['inclusiveDateEnd'])) : '';
    $commutation = isset($_POST['commutation']) ? strip_tags(mysqli_real_escape_string($database, $_POST['commutation'])) : '';
    $asOfDate = isset($_POST['asOfDate']) ? strip_tags(mysqli_real_escape_string($database, $_POST['asOfDate'])) : '';
    $vacationLeaveTotalEarned = isset($_POST['vacationLeaveTotalEarned']) ? strip_tags(mysqli_real_escape_string($database, $_POST['vacationLeaveTotalEarned'])) : '';
    $sickLeaveTotalEarned = isset($_POST['sickLeaveTotalEarned']) ? strip_tags(mysqli_real_escape_string($database, $_POST['sickLeaveTotalEarned'])) : '';
    $vacationLeaveLess = isset($_POST['vacationLeaveLess']) ? strip_tags(mysqli_real_escape_string($database, $_POST['vacationLeaveLess'])) : '';
    $sickLeaveLess = isset($_POST['sickLeaveLess']) ? strip_tags(mysqli_real_escape_string($database, $_POST['sickLeaveLess'])) : '';
    $vacationLeaveBalance = isset($_POST['vacationLeaveBalance']) ? strip_tags(mysqli_real_escape_string($database, $_POST['vacationLeaveBalance'])) : '';
    $sickLeaveBalance = isset($_POST['sickLeaveBalance']) ? strip_tags(mysqli_real_escape_string($database, $_POST['sickLeaveBalance'])) : '';
    $recommendation = isset($_POST['recommendation']) ? strip_tags(mysqli_real_escape_string($database, $_POST['recommendation'])) : '';
    $recommendMessage = isset($_POST['recommendMessage']) ? strip_tags(mysqli_real_escape_string($database, $_POST['recommendMessage'])) : '';
    $recommendMessageOne = isset($_POST['recommendMessageOne']) ? strip_tags(mysqli_real_escape_string($database, $_POST['recommendMessageOne'])) : '';
    $recommendMessageTwo = isset($_POST['recommendMessageTwo']) ? strip_tags(mysqli_real_escape_string($database, $_POST['recommendMessageTwo'])) : '';
    $recommendMessageThree = isset($_POST['recommendMessageThree']) ? strip_tags(mysqli_real_escape_string($database, $_POST['recommendMessageThree'])) : '';
    $recommendMessageFour = isset($_POST['recommendMessageFour']) ? strip_tags(mysqli_real_escape_string($database, $_POST['recommendMessageFour'])) : '';
    $dayWithPay = isset($_POST['dayWithPay']) ? strip_tags(mysqli_real_escape_string($database, $_POST['dayWithPay'])) : '';
    $dayWithoutPay = isset($_POST['dayWithoutPay']) ? strip_tags(mysqli_real_escape_string($database, $_POST['dayWithoutPay'])) : '';
    $otherDayPay = isset($_POST['otherDayPay']) ? strip_tags(mysqli_real_escape_string($database, $_POST['otherDayPay'])) : '';
    $otherDaySpecify = isset($_POST['otherDaySpecify']) ? strip_tags(mysqli_real_escape_string($database, $_POST['otherDaySpecify'])) : '';
    $disapprovedMessage = isset($_POST['disapprovedMessage']) ? strip_tags(mysqli_real_escape_string($database, $_POST['disapprovedMessage'])) : '';
    $disapprovedMessageOne = isset($_POST['disapprovedMessageOne']) ? strip_tags(mysqli_real_escape_string($database, $_POST['disapprovedMessageOne'])) : '';
    $disapprovedMessageTwo = isset($_POST['disapprovedMessageTwo']) ? strip_tags(mysqli_real_escape_string($database, $_POST['disapprovedMessageTwo'])) : '';
    $status = 'Validated';

    if (strtolower($recommendation) == strtolower("For Disapproved Due to")) {
        $status = "Disapproved";
    } else if (strtolower($recommendation) == strtolower("For Approval")) {
        $status = "Approved";
    }

    // Checks the Input of the Leave Application Form
    if (empty($typeOfLeave) || empty($inclusiveDateStart) || empty($inclusiveDateEnd)) {
        $_SESSION['alert_message'] = "Please Specify the Type Leave and Inclusive Dates";
        $_SESSION['alert_type'] = $warning_color;
    } else if ($typeOfLeave === 'Vacation Leave' && empty($typeOfVacationLeave) && (empty($typeOfVacationLeaveWithin) || empty($typeOfVacationLeaveAbroad))) {
        $_SESSION['alert_message'] = "Please select and fill up either 'Within the Philippines' or 'Abroad' for Vacation Leave";
        $_SESSION['alert_type'] = $warning_color;
    } else if ($typeOfLeave === 'Sick Leave' && empty($typeOfSickLeave) && (empty($typeOfSickLeaveInHospital) || empty($typeOfSickLeaveOutPatient))) {
        $_SESSION['alert_message'] = "Please select and fill up either 'In Hospital' or 'Out Patient' for Sick Leave";
        $_SESSION['alert_type'] = $warning_color;
    } else if ($typeOfLeave === 'Special Leave Benefits for Women' && empty($typeOfSpecialLeaveForWomen)) {
        $_SESSION['alert_message'] = "Please fill up Specify Illness for Special Leave";
        $_SESSION['alert_type'] = $warning_color;
    } else if ($typeOfLeave === 'Study Leave' && empty($typeOfStudyLeave)) {
        $_SESSION['alert_message'] = "Please select either 'Completion of Master's Degree or Bar / Board Examination Review' Incase for Study Leave";
        $_SESSION['alert_type'] = $warning_color;
    } else if ($asOfDate === '') {
        $_SESSION['alert_message'] = "Please fill up the As Of Date";
        $_SESSION['alert_type'] = $warning_color;
    } else if ($vacationLeaveTotalEarned == '' || $vacationLeaveLess == '' || $vacationLeaveBalance == '' || $sickLeaveTotalEarned == '' || $sickLeaveLess == '' || $sickLeaveBalance == '') {
        $_SESSION['alert_message'] = "Please Complete The Leave Computation Table";
        $_SESSION['alert_type'] = $warning_color;
    } else if ($recommendation === '') {
        $_SESSION['alert_message'] = "Please Fill Up the Department Approval The Leave Computation Table";
        $_SESSION['alert_type'] = $warning_color;
    } else if ($dayWithPay == '' && $dayWithoutPay == '' && $otherDayPay == '' && $disapprovedMessage == '' && $disapprovedMessageOne == '' && $disapprovedMessageTwo == '') {
        $_SESSION['alert_message'] = "Please Fill Up Either Approval Section or Disapproval Section";
        $_SESSION['alert_type'] = $warning_color;
    } else {
        $completeField = true;
    }

    // Checks first if the Inclusive Dates is Valid
    if ($completeField) {
        // if possible checks if the employee Exist
        $getInitialRecordQuery = "SELECT * FROM tbl_leavedataform WHERE employee_id = ? AND recordType = ? LIMIT 1";
        $stmtForInitialRecord = $database->prepare($getInitialRecordQuery);

        if ($stmtForInitialRecord) {
            $stmtForInitialRecord->bind_param("ss", $ownerOfForm, $initial);
            $stmtForInitialRecord->execute();
            $resultInitialRecord = $stmtForInitialRecord->get_result();

            if ($resultInitialRecord->num_rows > 0) {
                $initialRecordData = $resultInitialRecord->fetch_assoc();

                if ($inclusiveDateStart >= $initialRecordData['periodEnd'] && $inclusiveDateEnd >= $initialRecordData['periodEnd']) {
                    $completeVerification = true;
                } else {
                    $_SESSION['alert_message'] = "The Inclusive Start Date and the Inclusive End Date Should Be Greater Than " . $initialRecordData['periodEnd'];
                    $_SESSION['alert_type'] = $warning_color;
                }
            } else {
                $_SESSION['alert_message'] = "Please Create A Initial Record for This Employee First!";
                $_SESSION['alert_type'] = $warning_color;
            }
        } else {
            // Something
        }
    }

    if ($completeField && $completeVerification) {
        try {
            // Checks if there is an existing Leave App ID
            $checkLeaveFormIdQuery = "SELECT * FROM tbl_leaveappform WHERE leaveappform_id = ?";
            $checkstmt = mysqli_prepare($database, $checkLeaveFormIdQuery);
            mysqli_stmt_bind_param($checkstmt, 's', $leaveappformId);
            mysqli_stmt_execute($checkstmt);
            $result = mysqli_stmt_get_result($checkstmt);
            if (mysqli_num_rows($result) > 0) {

                // Updating the Data to the Database
                $query = "  UPDATE tbl_leaveappform
                            SET departmentName = ?, lastName = ?, firstName = ?, middleName = ?,
                            dateFiling = ?, position = ?, salary = ?,
                            typeOfLeave = ?, typeOfSpecifiedOtherLeave = ?,
                            typeOfVacationLeave = ?, typeOfVacationLeaveWithin = ?, typeOfVacationLeaveAbroad = ?,
                            typeOfSickLeave = ?, typeOfSickLeaveInHospital = ?, typeOfSickLeaveOutPatient = ?,
                            typeOfSpecialLeaveForWomen = ?, typeOfStudyLeave = ?, typeOfOtherLeave = ?,
                            workingDays = ?, inclusiveDateStart = ?, inclusiveDateEnd = ?, commutation = ?,
                            asOfDate = ?, vacationLeaveTotalEarned = ?, sickLeaveTotalEarned = ?,
                            vacationLeaveLess = ?, sickLeaveLess = ?, vacationLeaveBalance = ?, sickLeaveBalance = ?,
                            recommendation = ?, recommendMessage = ?,
                            dayWithPay = ?, dayWithoutPay = ?, otherDayPay = ?, otherDaySpecify = ?, disapprovedMessage = ?,
                            status = ?, hrName = ?, hrPosition = ?, deptHeadName = ?, mayorName = ?, mayorPosition = ?,
                            hrmanager_id = ?, depthead_id = ?, mayor_id = ?
                            WHERE leaveappform_id = ?";
                $stmt = mysqli_prepare($database, $query);
                mysqli_stmt_bind_param(
                    $stmt,
                    "ssssssssssssssssssissssddddddssiiissssssssiiis",
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
                    $status,
                    $hrName,
                    $hrPosition,
                    $deptHeadName,
                    $mayorName,
                    $mayorPosition,
                    $hrmanager_id,
                    $depthead_id,
                    $mayor_id,
                    $leaveappformId
                );

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['alert_message'] = "Leave Application Form Successfully Validated";
                    $_SESSION['alert_type'] = $success_color;

                    $dataRecordType = "Deduction Type";
                    $particularType = "";
                    $particularLabel = "";
                    if ($typeOfLeave != '') {
                        if ($typeOfLeave == "Vacation Leave" || $typeOfLeave == "Sick Leave" || $typeOfLeave == "Forced Leave") {
                            $particularType = $typeOfLeave;
                        } else {
                            $particularType = "Other";
                            $particularLabel = $typeOfLeave;
                        }
                    } else if ($typeOfOtherLeave != '') {
                        $particularType = "Other";
                        $particularLabel = $typeOfOtherLeave;
                    }
                    $days = $workingDays;
                    $hours = 0;
                    $minutes = 0;
                    $dateOfAction = date("Y-m-d");

                    // if there is an existing update of not then insert by foreignkey
                    $count = 0;
                    // Check if the record exists
                    $checkExistingQuery = "SELECT COUNT(*) AS count FROM tbl_leavedataform WHERE foreignKeyId = ?";
                    $stmtCheckExisting = $database->prepare($checkExistingQuery);
                    $stmtCheckExisting->bind_param('s', $leaveappformId);
                    $stmtCheckExisting->execute();
                    $stmtCheckExisting->bind_result($count);
                    $stmtCheckExisting->fetch();
                    $stmtCheckExisting->close();

                    if ($count > 0) {
                        // If record exists, perform update
                        if ($status == "Approved") {
                            $updateQuery = "UPDATE tbl_leavedataform SET employee_id = ?, recordType = ?, period = ?, periodEnd = ?, particular = ?, particularLabel = ?, days = ?, hours = ?, minutes = ?, dateOfAction = ? WHERE foreignKeyId = ?";
                            $stmtUpdateRecord = $database->prepare($updateQuery);
                            $stmtUpdateRecord->bind_param('ssssssiiiss', $ownerOfForm, $dataRecordType, $inclusiveDateStart, $inclusiveDateEnd, $particularType, $particularLabel, $days, $hours, $minutes, $dateOfAction, $leaveappformId);
                            $stmtUpdateRecord->execute();

                            if ($stmtUpdateRecord->error) {
                                $_SESSION['alert_message'] = "Leave Application Form Successfully Validated but Leave Record Updation Failed!: " . $stmtUpdateRecord->error;
                                $_SESSION['alert_type'] = $warning_color;
                            } else {
                                $_SESSION['alert_message'] = "Leave Application Form Successfully Validated and Leave Record Successfully Updated!";
                                $_SESSION['alert_type'] = $success_color;
                            }

                            $stmtUpdateRecord->close();
                        } else if ($status == "Disapproved") {
                            // Delete the record
                            $deleteQuery = "DELETE FROM tbl_leavedataform WHERE foreignKeyId = ?";
                            $stmtDeleteRecord = $database->prepare($deleteQuery);
                            $stmtDeleteRecord->bind_param('s', $leaveappformId);
                            $stmtDeleteRecord->execute();

                            if ($stmtDeleteRecord->error) {
                                $_SESSION['alert_message'] = "Failed to delete the leave record: " . $stmtDeleteRecord->error;
                                $_SESSION['alert_type'] = $error_color;
                            } else {
                                $_SESSION['alert_message'] = "Leave Application Form Disapproved and Leave Record Successfully Deleted!";
                                $_SESSION['alert_type'] = $success_color;
                            }

                            $stmtDeleteRecord->close();
                        }
                    } else {
                        if ($status == "Approved") {
                            // If record doesn't exist, perform insert
                            $insertQuery = "INSERT INTO tbl_leavedataform (employee_id, foreignKeyId, dateCreated, recordType, period, periodEnd, particular, particularLabel, days, hours, minutes, dateOfAction) VALUES (?, ?, CURRENT_TIMESTAMP(), ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmtInsertRecord = $database->prepare($insertQuery);
                            $stmtInsertRecord->bind_param('sssssssiiis', $ownerOfForm, $leaveappformId, $dataRecordType, $inclusiveDateStart, $inclusiveDateEnd, $particularType, $particularLabel, $days, $hours, $minutes, $dateOfAction);
                            $stmtInsertRecord->execute();

                            if ($stmtInsertRecord->error) {
                                $_SESSION['alert_message'] = "Leave Application Form Successfully Validated but Leave Record Failed: " . $stmtInsertRecord->error;
                                $_SESSION['alert_type'] = $warning_color;
                            } else {
                                $_SESSION['alert_message'] = "Leave Application Form Successfully Validated and Leave Record Successfully Added!";
                                $_SESSION['alert_type'] = $success_color;
                            }

                            $stmtInsertRecord->close();
                        } else {
                            $_SESSION['alert_message'] = "Leave Application Form Successfully Validated!";
                            $_SESSION['alert_type'] = $success_color;
                        }
                    }

                    // Notification
                    $notifEmpIdFrom = '@Admin';
                    $notifEmpIdTo = $ownerOfForm;
                    $notifSubject = 'Validation of Leave Form';

                    $notifMessage = "Check this Leave Request Form";
                    if ($status == "Approved") {
                        // $notifMessage = 'Your Leave Application Form has been ' . $status.'.';
                        $notifMessage = 'Your form is approved. Print it out and get it signed.';
                    } else {
                        $notifMessage = 'Your Leave Application Form has been ' . $status;
                    }
                    $notifLink = "";
                    // $notifLink = $location_admin_leaveapplist . '/view/' . $leaveappformId;
                    $notifStatus = 'unseen';

                    $queryNotif = "INSERT INTO tbl_notifications (dateCreated, empIdFrom, empIdTo, subject, message, subjectKey, link, status) VALUES (CURRENT_TIMESTAMP(), ?, ?, ?, ?, ?, ?, ?)";
                    $stmtNotif = mysqli_prepare($database, $queryNotif);

                    mysqli_stmt_bind_param($stmtNotif, "sssssss", $notifEmpIdFrom, $notifEmpIdTo, $notifSubject, $notifMessage, $leaveappformId, $notifLink, $notifStatus);

                    mysqli_stmt_execute($stmtNotif);
                } else {
                    $_SESSION['alert_message'] = "Error submitting Leave Application Form: " . mysqli_stmt_error($stmt);
                    $_SESSION['alert_type'] = $error_color;
                }
            }
            mysqli_stmt_close($checkstmt);

            if ($accountRole == "admin") {
                header("Location: " . $location_admin_leaveapplist);
            } else if ($accountRole == "staff") {
                header("Location: " . $location_staff_leaveapplist);
            } else {
                header("Location: " . $location_login);
            }
            exit();
        } catch (Exception $e) {
            $_SESSION['alert_message'] = "An error occurred: " . $e->getMessage();
            $_SESSION['alert_type'] = $error_color;
            echo $e;
            if ($accountRole == "admin") {
                header("Location: " . $location_admin_leaveapplist);
            } else if ($accountRole == "staff") {
                header("Location: " . $location_staff_leaveapplist);
            } else {
                header("Location: " . $location_login);
            }
            exit();
        }
    } else {
        if ($accountRole == "admin") {
            header("Location: " . $location_admin_leaveapplist_view . '/' . $leaveappformId . '/');
        } else if ($accountRole == "staff") {
            header("Location: " . $location_staff_leaveapplist_view . '/' . $leaveappformId . '/');
        } else {
            header("Location: " . $location_login);
        }
        exit();
    }
} else {
    // echo '<script type="text/javascript">window.history.back();</script>';
    if ($accountRole == "admin") {
        header("Location: " . $location_admin_leaveapplist);
    } else if ($accountRole == "staff") {
        header("Location: " . $location_staff_leaveapplist);
    } else {
        header("Location: " . $location_login);
    }
    exit();
}
?>