<?php
include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_admin);
include($constants_variables);

if (isset($_POST['retrieveLeaveData']) && isset($_POST['leaveDataNum'])) {
    $leaveDataNum = strip_tags(mysqli_real_escape_string($database, $_POST['leaveDataNum']));

    $archiveLeaveDataQuery = "UPDATE tbl_leavedataform SET archive = '' WHERE leavedataform_id = ?";
    $archiveLeaveDataStatement = mysqli_prepare($database, $archiveLeaveDataQuery);

    if ($archiveLeaveDataStatement) {
        mysqli_stmt_bind_param($archiveLeaveDataStatement, "s", $leaveDataNum);
        mysqli_stmt_execute($archiveLeaveDataStatement);
        if (mysqli_stmt_affected_rows($archiveLeaveDataStatement) > 0) {
            $_SESSION['alert_message'] = "Leave Data Successfully Restored!";
            $_SESSION['alert_type'] = $success_color;
        } else {
            $_SESSION['alert_message'] = "Error Restoring Leave Data: " . mysqli_stmt_error($archiveLeaveDataStatement);
            $_SESSION['alert_type'] = $error_color;
        }
        mysqli_stmt_close($archiveLeaveDataStatement);
    } else {
        $_SESSION['alert_message'] = "Error Restoring Leave Data!";
        $_SESSION['alert_type'] = $error_color;
    }

    header("Location: " . $location_admin_datamanagement_archive_leavedata);
    exit();

} else if (isset($_POST['retrieveMultipleLeaveData']) && isset($_POST['selectedLeaveData'])) {
    $selectedLeaveDatas = $_POST['selectedLeaveData'];
    $errorMessages = [];

    foreach ($selectedLeaveDatas as $LeaveDataId) {
        $LeaveDataId = strip_tags(mysqli_real_escape_string($database, $LeaveDataId));

        $query = "UPDATE tbl_leavedataform SET archive = '' WHERE leavedataform_id = ?";
        $stmt = mysqli_prepare($database, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $LeaveDataId);

            if (mysqli_stmt_execute($stmt)) {
                // Deletion successful
            } else {
                // Capture error message for later display
                $errorMessages[] = "Error restoring Leave Data with ID $LeaveDataId: " . mysqli_stmt_error($stmt);
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

    header("Location: " . $location_admin_datamanagement_archive_leavedata);
    exit();

} else {
    header("Location: " . $location_admin_datamanagement_archive_leavedata);
    exit();
}

?>