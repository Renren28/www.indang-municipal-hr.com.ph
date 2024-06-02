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

if (isset($_POST['deleteLeaveData']) && isset($_POST['leavedataformId'])) {
    // Only Update the Archive to Deleted
    $leaveDataFormId = strip_tags(mysqli_real_escape_string($database, $_POST['leavedataformId']));
    $empId = strip_tags(mysqli_real_escape_string($database, $_POST['empId']));
    $selectedYear = strip_tags(mysqli_real_escape_string($database, $_POST['selectedYear']));

    if ($selectedYear) {
        $_SESSION['post_dataformyear'] = $selectedYear;
    }

    $archiveLeaveDataQuery = "UPDATE tbl_leavedataform SET archive = 'deleted' WHERE leavedataform_id = ?";
    $archiveLeaveDataStatement = mysqli_prepare($database, $archiveLeaveDataQuery);

    if ($archiveLeaveDataStatement) {
        mysqli_stmt_bind_param($archiveLeaveDataStatement, "i", $leaveDataFormId);
        mysqli_stmt_execute($archiveLeaveDataStatement);
        if (mysqli_stmt_affected_rows($archiveLeaveDataStatement) > 0) {

            $foreignKeyId = '';
            $count = 0;

            $getForeignKeyQuery = "SELECT foreignKeyId FROM tbl_leavedataform WHERE leavedataform_id = ?";
            $getForeignKeyStatement = $database->prepare($getForeignKeyQuery);
            $getForeignKeyStatement->bind_param("i", $leaveDataFormId);
            $getForeignKeyStatement->execute();
            $getForeignKeyStatement->bind_result($foreignKeyId);
            $getForeignKeyStatement->fetch();
            $getForeignKeyStatement->close();

            // Check if the record exists
            $checkExistingQuery = "SELECT COUNT(*) AS count FROM tbl_leaveappform WHERE leaveappform_id = ?";
            $stmtCheckExisting = $database->prepare($checkExistingQuery);
            $stmtCheckExisting->bind_param('s', $foreignKeyId);
            $stmtCheckExisting->execute();
            $stmtCheckExisting->bind_result($count);
            $stmtCheckExisting->fetch();
            $stmtCheckExisting->close();

            if ($count > 0) {
                // If record exists, perform update
                $updateQuery = "UPDATE tbl_leaveappform SET archive = 'deleted' WHERE leaveappform_id = ?";
                $stmtUpdateRecord = $database->prepare($updateQuery);
                $stmtUpdateRecord->bind_param('s', $foreignKeyId);
                $stmtUpdateRecord->execute();

                if ($stmtUpdateRecord->error) {
                    $_SESSION['alert_message'] = "Leave Data Successfully Moved to Trash but Not Leave Form: " . $stmtUpdateRecord->error;
                    $_SESSION['alert_type'] = $warning_color;
                } else {
                    $_SESSION['alert_message'] = "Leave Data and Leave Form Successfully Moved to Trash!";
                    $_SESSION['alert_type'] = $success_color;
                }

                $stmtUpdateRecord->close();
            } else {
                $_SESSION['alert_message'] = "Leave Data Successfully Moved to Trash";
                $_SESSION['alert_type'] = $success_color;
            }
        } else {
            $_SESSION['alert_message'] = "Error Deleting Leave Data: " . mysqli_stmt_error($archiveLeaveDataStatement);
            $_SESSION['alert_type'] = $error_color;
        }
        mysqli_stmt_close($archiveLeaveDataStatement);
    } else {
        $_SESSION['alert_message'] = "Error Deleting Leave Data!";
        $_SESSION['alert_type'] = $error_color;
    }

    if ($accountRole == "admin") {
        if ($empId) {
            header("Location: " . $location_admin_departments_employee_leavedataform . '/' . $empId . '/');
            exit();
        } else {
            header("Location: " . $location_admin_departments_employee);
            exit();
        }
    } else if ($accountRole == "staff") {
        if ($empId) {
            header("Location: " . $location_staff_departments_employee_leavedataform . '/' . $empId . '/');
            exit();
        } else {
            header("Location: " . $location_staff_departments_employee);
            exit();
        }
    } else {
        header("Location: " . $location_login);
    }
    exit();
} else if (isset($_POST['absoluteDeleteLeaveData']) && isset($_POST['leavedataformId'])) {
    $leaveDataFormId = strip_tags(mysqli_real_escape_string($database, $_POST['leavedataformId']));
    $empId = strip_tags(mysqli_real_escape_string($database, $_POST['empId']));
    $selectedYear = strip_tags(mysqli_real_escape_string($database, $_POST['selectedYear']));

    if ($selectedYear) {
        $_SESSION['post_dataformyear'] = $selectedYear;
    }

    $query = "DELETE FROM tbl_leavedataform WHERE leavedataform_id = ?";
    $stmt = mysqli_prepare($database, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $leaveDataFormId);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['alert_message'] = "Leave Record Successfully Deleted";
            $_SESSION['alert_type'] = $success_color;
        } else {
            $_SESSION['alert_message'] = "Error Deleting Leave Record: " . mysqli_stmt_error($stmt);
            $_SESSION['alert_type'] = $error_color;
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['alert_message'] = "Error preparing delete statement: " . mysqli_error($database);
        $_SESSION['alert_type'] = $error_color;
    }

    if ($accountRole == "admin") {
        if ($empId) {
            header("Location: " . $location_admin_departments_employee_leavedataform . '/' . $empId . '/');
            exit();
        } else {
            header("Location: " . $location_admin_departments_employee);
            exit();
        }
    } else if ($accountRole == "staff") {
        if ($empId) {
            header("Location: " . $location_staff_departments_employee_leavedataform . '/' . $empId . '/');
            exit();
        } else {
            header("Location: " . $location_staff_departments_employee);
            exit();
        }
    } else {
        header("Location: " . $location_login);
    }
    exit();
} else {
    // echo '<script type="text/javascript">window.history.back();</script>';
    if ($accountRole == "admin") {
        if ($empId) {
            header("Location: " . $location_admin_departments_employee_leavedataform . '/' . $empId . '/');
            exit();
        } else {
            header("Location: " . $location_admin_departments_employee);
            exit();
        }
    } else if ($accountRole == "staff") {
        if ($empId) {
            header("Location: " . $location_staff_departments_employee_leavedataform . '/' . $empId . '/');
            exit();
        } else {
            header("Location: " . $location_staff_departments_employee);
            exit();
        }
    } else {
        header("Location: " . $location_login);
    }
    exit();
}

?>