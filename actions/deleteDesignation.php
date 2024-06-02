<?php
include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_admin);
include($constants_variables);

if (isset($_POST['deleteDesignation'])) {
    // Only Update the Archive to Deleted
    $designationId = strip_tags(mysqli_real_escape_string($database, $_POST['designationId']));

    $archiveDesignationQuery = "UPDATE tbl_designations SET archive = 'deleted' WHERE designation_id = ?";
    $archiveDesignationStatement = mysqli_prepare($database, $archiveDesignationQuery);

    if ($archiveDesignationStatement) {
        mysqli_stmt_bind_param($archiveDesignationStatement, "i", $designationId);
        mysqli_stmt_execute($archiveDesignationStatement);
        if (mysqli_stmt_affected_rows($archiveDesignationStatement) > 0) {
            $_SESSION['alert_message'] = "Designation Successfully Moved to Trash";
            $_SESSION['alert_type'] = $success_color;
        } else {
            $_SESSION['alert_message'] = "Error Deleting Designation: " . mysqli_stmt_error($archiveDesignationStatement);
            $_SESSION['alert_type'] = $error_color;
        }
        mysqli_stmt_close($archiveDesignationStatement);
    } else {
        $_SESSION['alert_message'] = "Error Deleting Designation!";
        $_SESSION['alert_type'] = $error_color;
    }

    header("Location: " . $location_admin_datamanagement_designation);
    exit();
} else if (isset($_POST['absoluteDeleteDesignation'])) {
    $designationId = strip_tags(mysqli_real_escape_string($database, $_POST['designationId']));

    $query = "DELETE FROM tbl_designation WHERE designation_id = ?";
    $stmt = mysqli_prepare($database, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $designationId);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['alert_message'] = "Designation Successfully Deleted";
            $_SESSION['alert_type'] = $success_color;
        } else {
            $_SESSION['alert_message'] = "Error Deleting Designation: " . mysqli_stmt_error($stmt);
            $_SESSION['alert_type'] = $error_color;
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['alert_message'] = "Error preparing delete statement: " . mysqli_error($database);
        $_SESSION['alert_type'] = $error_color;
    }

    header("Location: " . $location_admin_datamanagement_archive_designation);
    exit();
} else {
    // echo '<script type="text/javascript">window.history.back();</script>';
    header("Location: " . $location_admin_datamanagement_archive_designation);
}

?>
<!-- -->