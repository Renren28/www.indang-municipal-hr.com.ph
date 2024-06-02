<?php
include ("../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
include ($constants_variables);

if (isset($_POST['addDepartment'])) {
    $departmentName = strip_tags(mysqli_real_escape_string($database, $_POST['departmentName']));
    $departmentHead = strip_tags(mysqli_real_escape_string($database, $_POST["departmentHead"]));
    $departmentDescription = strip_tags(mysqli_real_escape_string($database, $_POST["departmentDescription"]));

    try {
        // Check if the department already exists
        $checkQuery = "SELECT * FROM tbl_departments WHERE LOWER(departmentName) = LOWER(?)";
        $checkStmt = mysqli_prepare($database, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "s", $departmentName);
        mysqli_stmt_execute($checkStmt);
        $result = mysqli_stmt_get_result($checkStmt);
        $existingDepartment = mysqli_fetch_assoc($result);

        if ($existingDepartment) {
            // Department exists, check archive status
            if (strtolower($existingDepartment['archive']) == "deleted") {

                if (trim($departmentDescription) != "") {
                    $updateQuery = "UPDATE tbl_departments SET archive = '', departmentDescription = ? WHERE LOWER(departmentName) = LOWER(?)";
                    $updateStmt = mysqli_prepare($database, $updateQuery);
                    mysqli_stmt_bind_param($updateStmt, "ss", $departmentDescription, $departmentName);
                    mysqli_stmt_execute($updateStmt);

                    $_SESSION['alert_message'] = "Department was on archive and update when retrieved.";
                } else {
                    $updateQuery = "UPDATE tbl_departments SET archive = '' WHERE LOWER(departmentName) = LOWER(?)";
                    $updateStmt = mysqli_prepare($database, $updateQuery);
                    mysqli_stmt_bind_param($updateStmt, "s", $departmentName);
                    mysqli_stmt_execute($updateStmt);

                    $_SESSION['alert_message'] = "Department was on archive and retrieved.";
                }
            } else {
                $_SESSION['alert_message'] = "Department already exists.";
            }
            $_SESSION['alert_type'] = $info_color;
        } else {
            // Department doesn't exist, add it
            $insertQuery = "INSERT INTO tbl_departments (departmentName, departmentDescription, departmentHead) VALUES (?, ?, ?)";
            $insertStmt = mysqli_prepare($database, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "sss", $departmentName, $departmentDescription, $departmentHead);
            mysqli_stmt_execute($insertStmt);

            $_SESSION['alert_message'] = "New Department Successfully Created";
            $_SESSION['alert_type'] = $success_color;
        }
    } catch (Exception $e) {
        $_SESSION['alert_message'] = "An error occurred: " . $e->getMessage();
        $_SESSION['alert_type'] = $error_color;
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    header("Location: " . $location_admin_departments);
    exit();
} else {
    // echo '<script type="text/javascript">window.history.back();</script>';
    header("Location: " . $location_admin_departments);
    exit();
}
?>