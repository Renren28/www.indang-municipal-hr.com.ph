<?php
include ("../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_authorized);
include ($constants_variables);

$accountRole = "";

if (isset($_SESSION['employeeId'])) {
    $accountRole = strtolower(getAccountRole($_SESSION['employeeId']));
}

if (isset($_POST['editLeaveDataRecord'])) {
    $empId = isset($_POST['empId']) ? sanitizeInput($_POST['empId']) : null;
    $leaveDataFormId = isset($_POST['leavedataformId']) ? sanitizeInput($_POST['leavedataformId']) : null;
    $selectedYear = isset($_POST['selectedYear']) ? sanitizeInput($_POST['selectedYear']) : null;
    $period = isset($_POST['period']) ? sanitizeInput($_POST['period']) : null;
    $periodEnd = isset($_POST['periodEnd']) ? sanitizeInput($_POST['periodEnd']) : null;
    $particularType = isset($_POST['particularType']) ? sanitizeInput($_POST['particularType']) : null;
    $particularLabel = isset($_POST['particularLabel']) ? sanitizeInput($_POST['particularLabel']) : null;
    $days = isset($_POST['dayInput']) ? sanitizeInput($_POST['dayInput']) : null;
    $hours = isset($_POST['hourInput']) ? sanitizeInput($_POST['hourInput']) : null;
    $minutes = isset($_POST['minuteInput']) ? sanitizeInput($_POST['minuteInput']) : null;
    $dateOfAction = isset($_POST['dateOfAction']) ? sanitizeInput($_POST['dateOfAction']) : null;

    if ($selectedYear) {
        $_SESSION['post_dataformyear'] = $selectedYear;
    }

    if ($days < 0 || $hours < 0 || $minutes < 0) {
        $_SESSION['alert_message'] = "The Values Should Not Be Negative!";
        $_SESSION['alert_type'] = $warning_color;
        if ($accountRole == "admin") {
            $redirect_location = $empId ? $location_admin_departments_employee_leavedataform . "/" . $empId . "/" : $location_admin_departments_employee;
            header("Location: $redirect_location");
            exit();
        } else if ($accountRole == "staff") {
            $redirect_location = $empId ? $location_staff_departments_employee_leavedataform . "/" . $empId . "/" : $location_staff_departments_employee;
            header("Location: $redirect_location");
            exit();
        } else {
            header("Location: " . $location_login);
        }
        exit();
    }

    $initial = "Initial Record";
    $initialRecordData = [];

    $getInitialRecordQuery = "SELECT * FROM tbl_leavedataform WHERE employee_id = ? AND recordType = ? AND UPPER(archive) != 'DELETED' LIMIT 1";
    $stmtForInitialRecord = $database->prepare($getInitialRecordQuery);

    if ($stmtForInitialRecord) {
        $stmtForInitialRecord->bind_param("ss", $empId, $initial);
        $stmtForInitialRecord->execute();
        $resultInitialRecord = $stmtForInitialRecord->get_result();

        if ($resultInitialRecord->num_rows > 0) {
            $initialRecordData = $resultInitialRecord->fetch_assoc();

            if ($period >= $initialRecordData['periodEnd'] && $periodEnd >= $initialRecordData['periodEnd']) {
                // Prepare the SQL query for update
                $sql = "UPDATE tbl_leavedataform 
                        SET period = ?, periodEnd = ?, particular = ?, particularLabel = ?, days = ?, hours = ?, minutes = ?, dateOfAction = ? 
                        WHERE leavedataform_id = ? AND employee_id = ? AND UPPER(archive) != 'DELETED'";

                // Prepare and bind the statement
                $stmt = $database->prepare($sql);
                $stmt->bind_param('ssssiiisis', $period, $periodEnd, $particularType, $particularLabel, $days, $hours, $minutes, $dateOfAction, $leaveDataFormId, $empId);

                $stmt->execute();

                if ($stmt->error) {
                    $_SESSION['alert_message'] = "Update Failed: " . $stmt->error;
                    $_SESSION['alert_type'] = $error_color;
                } else {
                    $_SESSION['alert_message'] = "Successfully Updated!";
                    $_SESSION['alert_type'] = $success_color;
                }

                $stmt->close();
            } else {
                $_SESSION['alert_message'] = "The Period and the Period End Should Be Greater Than " . $initialRecordData['periodEnd'];
                $_SESSION['alert_type'] = $warning_color;
            }
        }
    } else {
        // Something
    }

    if ($accountRole == "admin") {
        $redirect_location = $empId ? $location_admin_departments_employee_leavedataform . "/" . $empId . "/" : $location_admin_departments_employee;
        header("Location: $redirect_location");
        exit();
    } else if ($accountRole == "staff") {
        $redirect_location = $empId ? $location_staff_departments_employee_leavedataform . "/" . $empId . "/" : $location_staff_departments_employee;
        header("Location: $redirect_location");
        exit();
    } else {
        header("Location: " . $location_login);
    }
    exit();
} else if (isset($_POST['editInitialLeaveDataRecord'])) {
    $empId = isset($_POST['empId']) ? sanitizeInput($_POST['empId']) : null;
    $selectedYear = isset($_POST['selectedYear']) ? sanitizeInput($_POST['selectedYear']) : null;
    $period = isset($_POST['period']) ? sanitizeInput($_POST['period']) : null;
    $periodEnd = isset($_POST['periodEnd']) ? sanitizeInput($_POST['periodEnd']) : null;
    $vacationBalance = isset($_POST['vacationBalance']) ? sanitizeInput($_POST['vacationBalance']) : null;
    $sickBalance = isset($_POST['sickBalance']) ? sanitizeInput($_POST['sickBalance']) : null;
    $vacationUnderWOPay = isset($_POST['vacationUnderWOPay']) ? sanitizeInput($_POST['vacationUnderWOPay']) : null;
    $sickUnderWOPay = isset($_POST['sickUnderWOPay']) ? sanitizeInput($_POST['sickUnderWOPay']) : null;
    $particularLabel = isset($_POST['particularLabel']) ? sanitizeInput($_POST['particularLabel']) : null;
    $dateOfAction = isset($_POST['dateOfAction']) ? sanitizeInput($_POST['dateOfAction']) : null;

    if ($selectedYear) {
        $_SESSION['post_dataformyear'] = $selectedYear;
    }

    if ($vacationBalance < 0 || $sickBalance < 0 || $vacationUnderWOPay < 0 || $sickUnderWOPay < 0) {
        $_SESSION['alert_message'] = "The Values Should Not Be Negative!";
        $_SESSION['alert_type'] = $warning_color;
        if ($accountRole == "admin") {
            $redirect_location = $empId ? $location_admin_departments_employee_leavedataform . "/" . $empId . "/" : $location_admin_departments_employee;
            header("Location: $redirect_location");
            exit();
        } else if ($accountRole == "staff") {
            $redirect_location = $empId ? $location_staff_departments_employee_leavedataform . "/" . $empId . "/" : $location_staff_departments_employee;
            header("Location: $redirect_location");
            exit();
        } else {
            header("Location: " . $location_login);
        }
        exit();
    }

    $sqlCheckEmployeeId = "SELECT * FROM tbl_useraccounts WHERE employee_id = ? AND UPPER(archive) != 'DELETED'";
    $stmtCheckEmployeeId = $database->prepare($sqlCheckEmployeeId);
    $stmtCheckEmployeeId->bind_param("s", $empId);
    $stmtCheckEmployeeId->execute();
    $resultCheckEmployeeId = $stmtCheckEmployeeId->get_result();

    if ($resultCheckEmployeeId->num_rows > 0) {
        $dataRecordType = "Initial Record";

        $sqlFetchPreviousLeaveData = "SELECT * FROM tbl_leavedataform WHERE employee_id = ? AND (period < ? OR periodEnd < ?) AND recordType != ? AND UPPER(archive) != 'DELETED' ORDER BY period DESC, dateCreated DESC LIMIT 1";
        $stmtFetchPreviousLeaveData = $database->prepare($sqlFetchPreviousLeaveData);
        $stmtFetchPreviousLeaveData->bind_param("ssss", $empId, $period, $periodEnd, $dataRecordType);
        $stmtFetchPreviousLeaveData->execute();
        $resultFetchPreviousLeaveData = $stmtFetchPreviousLeaveData->get_result();

        if ($resultFetchPreviousLeaveData->num_rows > 0) {
            $previousLeaveData = $resultFetchPreviousLeaveData->fetch_assoc();
            $_SESSION['alert_message'] = "Initialization Should Be Earlier Than " . $previousLeaveData['period'];
            $_SESSION['alert_type'] = $warning_color;
        } else {
            $checkQuery = "SELECT * FROM tbl_leavedataform WHERE employee_id = ? AND recordType = ? AND UPPER(archive) != 'DELETED'";
            $checkStmt = mysqli_prepare($database, $checkQuery);
            mysqli_stmt_bind_param($checkStmt, "ss", $empId, $dataRecordType);
            mysqli_stmt_execute($checkStmt);
            mysqli_stmt_store_result($checkStmt);

            if (mysqli_stmt_num_rows($checkStmt) > 0) {
                $updateQuery = "UPDATE tbl_leavedataform SET period = ?, periodEnd = ?, particularLabel = ?, vacationLeaveEarned = ?, vacationLeaveBalance = ?, vacationLeaveAbsUndWOP = ?, sickLeaveEarned = ?, sickLeaveBalance = ?, sickLeaveAbsUndWOP = ?, dateOfAction = ? WHERE employee_id = ? AND recordType = 'Initial Record'";
                $updateStmt = mysqli_prepare($database, $updateQuery);
                mysqli_stmt_bind_param($updateStmt, "ssddddddsss", $period, $periodEnd, $particularLabel, $vacationBalance, $vacationBalance, $vacationUnderWOPay, $sickBalance, $sickBalance, $sickUnderWOPay, $dateOfAction, $empId);

                $alert_message = "Initialization Successfully Updated";
            } else {
                $query = "INSERT INTO tbl_leavedataform (employee_id, dateCreated, recordType, period, periodEnd, particular, particularLabel, vacationLeaveEarned, vacationLeaveBalance, vacationLeaveAbsUndWOP, sickLeaveEarned, sickLeaveBalance, sickLeaveAbsUndWOP, dateOfAction) VALUES (?, CURRENT_TIMESTAMP(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($database, $query);
                mysqli_stmt_bind_param($stmt, "ssssssdddddds", $empId, $dataRecordType, $period, $periodEnd, $dataRecordType, $particularLabel, $vacationBalance, $vacationBalance, $vacationUnderWOPay, $sickBalance, $sickBalance, $sickUnderWOPay, $dateOfAction);

                $alert_message = "Initialization Successfully Created";
            }

            if (isset($updateStmt) ? mysqli_stmt_execute($updateStmt) : mysqli_stmt_execute($stmt)) {
                $_SESSION['alert_message'] = $alert_message;
                $_SESSION['alert_type'] = $success_color;
            } else {
                $_SESSION['alert_message'] = "Initialization Failed: " . (isset($updateStmt) ? mysqli_stmt_error($updateStmt) : mysqli_stmt_error($stmt));
                $_SESSION['alert_type'] = $error_color;
            }
        }
    } else {
        $_SESSION['alert_message'] = "There are no existing Employees!";
        $_SESSION['alert_type'] = $warning_color;
    }

    if ($accountRole == "admin") {
        $redirect_location = $empId ? $location_admin_departments_employee_leavedataform . "/" . $empId . "/" : $location_admin_departments_employee;
        header("Location: $redirect_location");
        exit();
    } else if ($accountRole == "staff") {
        $redirect_location = $empId ? $location_staff_departments_employee_leavedataform . "/" . $empId . "/" : $location_staff_departments_employee;
        header("Location: $redirect_location");
        exit();
    } else {
        header("Location: " . $location_login);
    }
    exit();
} else {
    if ($accountRole == "admin") {
        header("Location: " . $location_admin);
    } else if ($accountRole == "staff") {
        header("Location: " . $location_staff);
    } else {
        header("Location: " . $location_login);
    }
    exit();
}
?>