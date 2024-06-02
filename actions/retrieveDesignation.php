<?php
include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_admin);
include($constants_variables);

if (isset($_POST['retrieveDesignation']) && isset($_POST['designationNum'])) {
    $designationNum = strip_tags(mysqli_real_escape_string($database, $_POST['designationNum']));

    $archiveDesignationQuery = "UPDATE tbl_designations SET archive = '' WHERE designation_id = ?";
    $archiveDesignationStatement = mysqli_prepare($database, $archiveDesignationQuery);

    if ($archiveDesignationStatement) {
        mysqli_stmt_bind_param($archiveDesignationStatement, "s", $designationNum);
        mysqli_stmt_execute($archiveDesignationStatement);
        if (mysqli_stmt_affected_rows($archiveDesignationStatement) > 0) {
            $_SESSION['alert_message'] = "Designation Successfully Restored!";
            $_SESSION['alert_type'] = $success_color;
        } else {
            $_SESSION['alert_message'] = "Error Restoring Designation: " . mysqli_stmt_error($archiveDesignationStatement);
            $_SESSION['alert_type'] = $error_color;
        }
        mysqli_stmt_close($archiveDesignationStatement);
    } else {
        $_SESSION['alert_message'] = "Error Restoring Designation!";
        $_SESSION['alert_type'] = $error_color;
    }

    header("Location: " . $location_admin_datamanagement_archive_designation);
    exit();

} else if (isset($_POST['retrieveMultipleDesignation']) && isset($_POST['selectedDesignation'])) {
    $selectedDesignations = $_POST['selectedDesignation'];
    $errorMessages = [];

    foreach ($selectedDesignations as $designationId) {
        $designationId = strip_tags(mysqli_real_escape_string($database, $designationId));

        $query = "UPDATE tbl_designations SET archive = '' WHERE designation_id = ?";
        $stmt = mysqli_prepare($database, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $designationId);

            if (mysqli_stmt_execute($stmt)) {
                // Deletion successful
            } else {
                // Capture error message for later display
                $errorMessages[] = "Error restoring designation with ID $designationId: " . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);
        } else {
            // Capture error message for later display
            $errorMessages[] = "Error preparing restore statement: " . mysqli_error($database);
        }
    }

    if (empty($errorMessages)) {
        $_SESSION['alert_message'] = "Selected Designations Successfully Restored!";
        $_SESSION['alert_type'] = $success_color;
    } else {
        $_SESSION['alert_message'] = "Some errors occurred during restoration. Please check the details below.";
        $_SESSION['alert_type'] = $error_color;
        // Store the error messages for display
        $_SESSION['error_messages'] = $errorMessages;
    }

    header("Location: " . $location_admin_datamanagement_archive_designation);
    exit();

} else {
    header("Location: " . $location_admin_datamanagement_archive_designation);
    exit();
}

?>