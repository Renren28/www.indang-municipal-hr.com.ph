<?php
include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_admin);
include($constants_variables);

if (isset($_POST['retrieveDepartment']) && isset($_POST['departmentNum'])) {
    $departmentNum = strip_tags(mysqli_real_escape_string($database, $_POST['departmentNum']));

    $archiveDepartmentQuery = "UPDATE tbl_departments SET archive = '' WHERE department_id = ?";
    $archiveDepartmentStatement = mysqli_prepare($database, $archiveDepartmentQuery);

    if ($archiveDepartmentStatement) {
        mysqli_stmt_bind_param($archiveDepartmentStatement, "s", $departmentNum);
        mysqli_stmt_execute($archiveDepartmentStatement);
        if (mysqli_stmt_affected_rows($archiveDepartmentStatement) > 0) {
            $_SESSION['alert_message'] = "Department Successfully Restored!";
            $_SESSION['alert_type'] = $success_color;
        } else {
            $_SESSION['alert_message'] = "Error Restoring Department: " . mysqli_stmt_error($archiveDepartmentStatement);
            $_SESSION['alert_type'] = $error_color;
        }
        mysqli_stmt_close($archiveDepartmentStatement);
    } else {
        $_SESSION['alert_message'] = "Error Restoring Department!";
        $_SESSION['alert_type'] = $error_color;
    }

    header("Location: " . $location_admin_datamanagement_archive_department);
    exit();

} else if (isset($_POST['retrieveMultipleDepartment']) && isset($_POST['selectedDepartment'])) {
    $selectedDepartments = $_POST['selectedDepartment'];
    $errorMessages = [];

    foreach ($selectedDepartments as $departmentId) {
        $departmentId = strip_tags(mysqli_real_escape_string($database, $departmentId));

        $query = "UPDATE tbl_departments SET archive = '' WHERE department_id = ?";
        $stmt = mysqli_prepare($database, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $departmentId);

            if (mysqli_stmt_execute($stmt)) {
                // Deletion successful
            } else {
                // Capture error message for later display
                $errorMessages[] = "Error restoring department with ID $departmentId: " . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);
        } else {
            // Capture error message for later display
            $errorMessages[] = "Error preparing restore statement: " . mysqli_error($database);
        }
    }

    if (empty($errorMessages)) {
        $_SESSION['alert_message'] = "Selected Departments Successfully Restored!";
        $_SESSION['alert_type'] = $success_color;
    } else {
        $_SESSION['alert_message'] = "Some errors occurred during restoration. Please check the details below.";
        $_SESSION['alert_type'] = $error_color;
        // Store the error messages for display
        $_SESSION['error_messages'] = $errorMessages;
    }

    header("Location: " . $location_admin_datamanagement_archive_department);
    exit();

} else {
    header("Location: " . $location_admin_datamanagement_archive_department);
    exit();
}

?>