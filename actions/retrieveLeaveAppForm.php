<?php
include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_admin);
include($constants_variables);

if (isset($_POST['retrieveLeaveForm']) && isset($_POST['leaveFormNum'])) {
    $leaveFormNum = strip_tags(mysqli_real_escape_string($database, $_POST['leaveFormNum']));

    $archiveLeaveFormQuery = "UPDATE tbl_leaveappform SET archive = '' WHERE leaveappform_id = ?";
    $archiveLeaveFormStatement = mysqli_prepare($database, $archiveLeaveFormQuery);

    if ($archiveLeaveFormStatement) {
        mysqli_stmt_bind_param($archiveLeaveFormStatement, "s", $leaveFormNum);
        mysqli_stmt_execute($archiveLeaveFormStatement);
        if (mysqli_stmt_affected_rows($archiveLeaveFormStatement) > 0) {
            $_SESSION['alert_message'] = "Leave Form Successfully Restored!";
            $_SESSION['alert_type'] = $success_color;
        } else {
            $_SESSION['alert_message'] = "Error Restoring Leave Form: " . mysqli_stmt_error($archiveLeaveFormStatement);
            $_SESSION['alert_type'] = $error_color;
        }
        mysqli_stmt_close($archiveLeaveFormStatement);
    } else {
        $_SESSION['alert_message'] = "Error Restoring Leave Form!";
        $_SESSION['alert_type'] = $error_color;
    }

    header("Location: " . $location_admin_datamanagement_archive_leaveform);
    exit();

} else if (isset($_POST['retrieveMultipleLeaveForm']) && isset($_POST['selectedLeaveForm'])) {
    $selectedLeaveForms = $_POST['selectedLeaveForm'];
    $errorMessages = [];

    foreach ($selectedLeaveForms as $leaveFormId) {
        $leaveFormId = strip_tags(mysqli_real_escape_string($database, $leaveFormId));

        $query = "UPDATE tbl_leaveappform SET archive = '' WHERE leaveappform_id = ?";
        $stmt = mysqli_prepare($database, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $leaveFormId);

            if (mysqli_stmt_execute($stmt)) {
                // Deletion successful
            } else {
                // Capture error message for later display
                $errorMessages[] = "Error restoring Leave Form with ID $leaveFormId: " . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);
        } else {
            // Capture error message for later display
            $errorMessages[] = "Error preparing restore statement: " . mysqli_error($database);
        }
    }

    if (empty($errorMessages)) {
        $_SESSION['alert_message'] = "Selected Leave Forms Successfully Restored!";
        $_SESSION['alert_type'] = $success_color;
    } else {
        $_SESSION['alert_message'] = "Some errors occurred during restoration. Please check the details below.";
        $_SESSION['alert_type'] = $error_color;
        // Store the error messages for display
        $_SESSION['error_messages'] = $errorMessages;
    }

    header("Location: " . $location_admin_datamanagement_archive_leaveform);
    exit();

} else {
    header("Location: " . $location_admin_datamanagement_archive_leaveform);
    exit();
}

?>