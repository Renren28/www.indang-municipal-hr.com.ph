<?php
include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_admin);
include($constants_variables);

if (isset($_POST['editDesignation'])) {
    $designationId = strip_tags(mysqli_real_escape_string($database, $_POST['designationId']));
    $designationName = strip_tags(mysqli_real_escape_string($database, $_POST['designationName']));
    $designationDescription = strip_tags(mysqli_real_escape_string($database, $_POST["designationDescription"]));

    $query = "UPDATE tbl_designations SET
              designationName = ?,
              designationDescription = ?
              WHERE designation_id = ?";

    $stmt = mysqli_prepare($database, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $designationName, $designationDescription, $designationId);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['alert_message'] = "Designation Successfully Updated!";
            $_SESSION['alert_type'] = $success_color;
        } else {
            $_SESSION['alert_message'] = "Error updating Designation: " . mysqli_stmt_error($stmt);
            $_SESSION['alert_type'] = $error_color;
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['alert_message'] = "Error preparing update statement: " . mysqli_error($database);
        $_SESSION['alert_type'] = $error_color;
    }

    header("Location: " . $location_admin_datamanagement_designation);
    exit();
} else {
    header("Location: " . $location_admin_datamanagement_designation);
    exit();
}
?>