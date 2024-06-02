<?php
include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_admin);
include($constants_variables);

if (isset($_POST['editDepartment'])) {
    $departmentId = strip_tags(mysqli_real_escape_string($database, $_POST['departmentId']));
    $departmentName = strip_tags(mysqli_real_escape_string($database, $_POST['departmentName']));
    $departmentHead = strip_tags(mysqli_real_escape_string($database, $_POST['departmentHead']));
    $departmentDescription = strip_tags(mysqli_real_escape_string($database, $_POST["departmentDescription"]));

    $query = "UPDATE tbl_departments SET
              departmentName = ?,
              departmentDescription = ?,
              departmentHead = ?
              WHERE department_id = ?";

    $stmt = mysqli_prepare($database, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $departmentName, $departmentDescription, $departmentHead, $departmentId);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['alert_message'] = "Department Successfully Updated!";
            $_SESSION['alert_type'] = $success_color;
        } else {
            $_SESSION['alert_message'] = "Error updating department: " . mysqli_stmt_error($stmt);
            $_SESSION['alert_type'] = $error_color;
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['alert_message'] = "Error preparing update statement: " . mysqli_error($database);
        $_SESSION['alert_type'] = $error_color;
    }

    header("Location: " . $location_admin_departments);
    exit();
} else {
    header("Location: " . $location_admin_departments);
    exit();
}
?>