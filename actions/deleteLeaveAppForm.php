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

if (isset($_POST['deleteLeaveAppForm'])) {
    // Only Update the Archive to Deleted
    $empId = isset($_POST['empId']) ? sanitizeInput($_POST['empId']) : null;
    $recordId = isset($_POST['recordId']) ? sanitizeInput($_POST['recordId']) : null;

    $archiveLeaveFormQuery = "UPDATE tbl_leaveappform SET archive = 'deleted' WHERE leaveappform_id = ?";
    $archiveLeaveFormStatement = mysqli_prepare($database, $archiveLeaveFormQuery);

    if ($archiveLeaveFormStatement) {
        mysqli_stmt_bind_param($archiveLeaveFormStatement, "s", $recordId);
        mysqli_stmt_execute($archiveLeaveFormStatement);
        if (mysqli_stmt_affected_rows($archiveLeaveFormStatement) > 0) {

            // if there is an existing foreignkey update it to deleted
            $count = 0;
            // Check if the record exists
            $checkExistingQuery = "SELECT COUNT(*) AS count FROM tbl_leavedataform WHERE foreignKeyId = ?";
            $stmtCheckExisting = $database->prepare($checkExistingQuery);
            $stmtCheckExisting->bind_param('s', $recordId);
            $stmtCheckExisting->execute();
            $stmtCheckExisting->bind_result($count);
            $stmtCheckExisting->fetch();
            $stmtCheckExisting->close();

            if ($count > 0) {
                // If record exists, perform update
                $updateQuery = "UPDATE tbl_leavedataform SET archive = 'deleted' WHERE foreignKeyId = ?";
                $stmtUpdateRecord = $database->prepare($updateQuery);
                $stmtUpdateRecord->bind_param('s', $recordId);
                $stmtUpdateRecord->execute();

                if ($stmtUpdateRecord->error) {
                    $_SESSION['alert_message'] = "Leave Form Successfully Moved to Trash but Not Leave Data: " . $stmtUpdateRecord->error;
                    $_SESSION['alert_type'] = $warning_color;
                } else {
                    $_SESSION['alert_message'] = "Leave Form and Leave Data Successfully Moved to Trash!";
                    $_SESSION['alert_type'] = $success_color;
                }

                $stmtUpdateRecord->close();
            } else {
                $_SESSION['alert_message'] = "Leave Form Successfully Moved to Trash";
                $_SESSION['alert_type'] = $success_color;
            }
        } else {
            $_SESSION['alert_message'] = "Error Deleting Leave Form: " . mysqli_stmt_error($archiveLeaveFormStatement);
            $_SESSION['alert_type'] = $error_color;
        }
        mysqli_stmt_close($archiveLeaveFormStatement);
    } else {
        $_SESSION['alert_message'] = "Error Deleting Leave Form!";
        $_SESSION['alert_type'] = $error_color;
    }

    if ($accountRole == "admin") {
        if ($empId) {
            header("Location: " . $location_admin_departments_employee_leaveappform . '/' . $empId . '/');
        } else {
            header("Location: " . $location_admin_leaveapplist);
        }
    } else if ($accountRole == "staff") {
        if ($empId) {
            header("Location: " . $location_staff_departments_employee_leaveappform . '/' . $empId . '/');
        } else {
            header("Location: " . $location_staff_leaveapplist);
        }
    } else {
        header("Location: " . $location_login);
    }
    exit();
} else if (isset($_POST['absoluteDeleteLeaveAppForm'])) {
    $empId = isset($_POST['empId']) ? sanitizeInput($_POST['empId']) : null;
    $recordId = isset($_POST['recordId']) ? sanitizeInput($_POST['recordId']) : null;

    $query = "DELETE FROM tbl_leaveappform WHERE leaveappform_id = ?";
    $stmt = mysqli_prepare($database, $query);

    mysqli_stmt_bind_param($stmt, "s", $recordId);

    if (mysqli_stmt_execute($stmt)) {
        $deleteLeaveDataRecordQuery = "DELETE FROM tbl_leavedataform WHERE foreignKeyId = ?";
        $deleteLeaveDataRecordStatement = mysqli_prepare($database, $deleteLeaveDataRecordQuery);

        mysqli_stmt_bind_param($deleteLeaveDataRecordStatement, "s", $recordId);

        if (mysqli_stmt_execute($deleteLeaveDataRecordStatement)) {
            $_SESSION['alert_message'] = "Leave Application Form Data Successfully Deleted";
            $_SESSION['alert_type'] = $success_color;
        } else {
            $_SESSION['alert_message'] = "Error Deleting Leave Form Data Record: " . mysqli_stmt_error($deleteLeaveDataRecordStatement);
            $_SESSION['alert_type'] = $error_color;
        }

        mysqli_stmt_close($deleteLeaveDataRecordStatement);
    } else {
        $_SESSION['alert_message'] = "Error Deleting Leave Application Form: " . mysqli_stmt_error($stmt);
        $_SESSION['alert_type'] = $error_color;
    }

    mysqli_stmt_close($stmt);

    if ($accountRole == "admin") {
        if ($empId) {
            header("Location: " . $location_admin_departments_employee_leaveappform . '/' . $empId . '/');
        } else {
            header("Location: " . $location_admin_datamanagement_archive_leaveform);
        }
    } else if ($accountRole == "staff") {
        if ($empId) {
            header("Location: " . $location_staff_departments_employee_leaveappform . '/' . $empId . '/');
        } else {
            header("Location: " . $location_staff_leaveapplist);
        }
    } else {
        header("Location: " . $location_login);
    }
    exit();
} else {
    // $_SESSION['alert_message'] = "Not Yet Available!";
    // $_SESSION['alert_type'] = $warning_color;
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