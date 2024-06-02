<?php
include ("../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
include ($constants_variables);

if (isset($_POST['deleteMultipleEmployee']) && isset($_POST['selectedEmployee'])) {
    // Only Update the Archive to Deleted (Single)
    $selectedEmployees = $_POST['selectedEmployee'];
    $departmentlabel = strip_tags(mysqli_real_escape_string($database, $_POST['departmentlabel']));
    $errorMessages = [];

    foreach ($selectedEmployees as $employeeId) {
        if ($employeeId != $_SESSION['employee_id']) {
            $employeeId = strip_tags(mysqli_real_escape_string($database, $employeeId));

            $query = "UPDATE tbl_useraccounts SET archive = 'deleted' WHERE employee_id = ?";
            $stmt = mysqli_prepare($database, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $employeeId);

                if (mysqli_stmt_execute($stmt)) {
                    // Deletion successful
                } else {
                    // Capture error message for later display
                    $errorMessages[] = "Error deleting employee with ID $employeeId: " . mysqli_stmt_error($stmt);
                }

                mysqli_stmt_close($stmt);
            } else {
                // Capture error message for later display
                $errorMessages[] = "Error preparing delete statement: " . mysqli_error($database);
            }
        }
    }

    if (empty($errorMessages)) {
        $_SESSION['alert_message'] = "Selected employees successfully deleted";
        $_SESSION['alert_type'] = $success_color;
    } else {
        $_SESSION['alert_message'] = "Some errors occurred during deletion. Please check the details below.";
        $_SESSION['alert_type'] = $error_color;
        // Store the error messages for display
        $_SESSION['error_messages'] = $errorMessages;
    }

    // Redirect to the employee list page after deletion
    if ($departmentlabel) {
        header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
    } else {
        header("Location: " . $location_admin_departments_office);
    }
    exit();
} else if (isset($_POST['absoluteDeleteMultipleEmployee']) && isset($_POST['selectedEmployee'])) {
    $selectedEmployees = $_POST['selectedEmployee'];
    $departmentlabel = strip_tags(mysqli_real_escape_string($database, $_POST['departmentlabel']));
    $errorMessages = [];

    foreach ($selectedEmployees as $employeeId) {
        $employeeId = strip_tags(mysqli_real_escape_string($database, $employeeId));

        if ($employeeId != $_SESSION['employee_id']) {
            $query = "DELETE FROM tbl_useraccounts WHERE employee_id = ?";
            $stmt = mysqli_prepare($database, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $employeeId);

                if (mysqli_stmt_execute($stmt)) {
                    // Deletion successful
                } else {
                    // Capture error message for later display
                    $errorMessages[] = "Error deleting employee with ID $employeeId: " . mysqli_stmt_error($stmt);
                }

                mysqli_stmt_close($stmt);
            } else {
                // Capture error message for later display
                $errorMessages[] = "Error preparing delete statement: " . mysqli_error($database);
            }
        }
    }

    if (empty($errorMessages)) {
        $_SESSION['alert_message'] = "Selected employees successfully deleted";
        $_SESSION['alert_type'] = $success_color;
    } else {
        $_SESSION['alert_message'] = "Some errors occurred during deletion. Please check the details below.";
        $_SESSION['alert_type'] = $error_color;
        // Store the error messages for display
        $_SESSION['error_messages'] = $errorMessages;
    }

    // Redirect to the employee list page after deletion
    if ($departmentlabel) {
        header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
    } else {
        header("Location: " . $location_admin_departments_office);
    }
    exit();
} else if (isset($_POST['deleteEmployee']) && isset($_POST['employeeNum'])) {
    // Only Update the Archive to Deleted (Single)
    $employeeNum = strip_tags(mysqli_real_escape_string($database, $_POST['employeeNum']));
    $departmentlabel = strip_tags(mysqli_real_escape_string($database, $_POST['departmentlabel']));

    $archiveEmployeeQuery = "UPDATE tbl_useraccounts SET archive = 'deleted' WHERE employee_id = ?";
    $archiveEmployeeStatement = mysqli_prepare($database, $archiveEmployeeQuery);

    if ($archiveEmployeeStatement) {
        mysqli_stmt_bind_param($archiveEmployeeStatement, "s", $employeeNum);
        mysqli_stmt_execute($archiveEmployeeStatement);
        if (mysqli_stmt_affected_rows($archiveEmployeeStatement) > 0) {
            $_SESSION['alert_message'] = "Employee Successfully Moved to Trash";
            $_SESSION['alert_type'] = $success_color;
        } else {
            $_SESSION['alert_message'] = "Error Deleting Employee: " . mysqli_stmt_error($archiveEmployeeStatement);
            $_SESSION['alert_type'] = $error_color;
        }
        mysqli_stmt_close($archiveEmployeeStatement);
    } else {
        $_SESSION['alert_message'] = "Error Deleting Employee!";
        $_SESSION['alert_type'] = $error_color;
    }

    if ($departmentlabel) {
        header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
    } else {
        header("Location: " . $location_admin_departments_office);
    }
    exit();
} else if (isset($_POST['absoluteDeleteEmployee']) && isset($_POST['employeeNum'])) {
    $employeeNum = strip_tags(mysqli_real_escape_string($database, $_POST['employeeNum']));
    $departmentlabel = strip_tags(mysqli_real_escape_string($database, $_POST['departmentlabel']));
    $errorMessages = [];

    $query = "DELETE FROM tbl_useraccounts WHERE employee_id = ?";
    $stmt = mysqli_prepare($database, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $employeeNum);

        if (mysqli_stmt_execute($stmt)) {
            // Deletion successful
            $_SESSION['alert_message'] = "Employee with ID $employeeNum successfully deleted";
            $_SESSION['alert_type'] = $success_color;
        } else {
            // Capture error message for later display
            $_SESSION['alert_message'] = "Error deleting employee with ID $employeeNum: " . mysqli_stmt_error($stmt);
            $_SESSION['alert_type'] = $error_color;
        }

        mysqli_stmt_close($stmt);
    } else {
        // Capture error message for later display
        $_SESSION['alert_message'] = "Error preparing delete statement: " . mysqli_error($database);
        $_SESSION['alert_type'] = $error_color;
    }

    // Redirect to the employee list page after deletion
    if ($departmentlabel) {
        header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
    } else {
        header("Location: " . $location_admin_datamanagement_archive_employee);
    }
    exit();
} else {
    // echo '<script type="text/javascript">window.history.back();</script>';
    if ($departmentlabel) {
        header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
    } else {
        header("Location: " . $location_admin_departments_office);
    }
}
?>