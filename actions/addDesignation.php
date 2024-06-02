<?php
include ("../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
include ($constants_variables);

if (isset($_POST['addDesignation'])) {
    $DesignationName = strip_tags(mysqli_real_escape_string($database, $_POST['DesignationName']));
    $DesignationDescription = strip_tags(mysqli_real_escape_string($database, $_POST["DesignationDescription"]));

    try {
        // Check if the department already exists
        $checkQuery = "SELECT * FROM tbl_designations WHERE LOWER(DesignationName) = LOWER(?)";
        $checkStmt = mysqli_prepare($database, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "s", $DesignationName);
        mysqli_stmt_execute($checkStmt);
        $result = mysqli_stmt_get_result($checkStmt);
        $existingDesignation = mysqli_fetch_assoc($result);

        if ($existingDesignation) {
            // Department exists, check archive status
            if (strtolower($existingDesignation['archive']) == "deleted") {

                if (trim($DesignationDescription) != "") {
                    $updateQuery = "UPDATE tbl_designations SET archive = '', DesignationDescription = ? WHERE LOWER(DesignationName) = LOWER(?)";
                    $updateStmt = mysqli_prepare($database, $updateQuery);
                    mysqli_stmt_bind_param($updateStmt, "ss", $DesignationDescription, $DesignationName);
                    mysqli_stmt_execute($updateStmt);

                    $_SESSION['alert_message'] = "Department was on archive and update when retrieved.";
                } else {
                    $updateQuery = "UPDATE tbl_designations SET archive = '' WHERE LOWER(DesignationName) = LOWER(?)";
                    $updateStmt = mysqli_prepare($database, $updateQuery);
                    mysqli_stmt_bind_param($updateStmt, "s", $DesignationName);
                    mysqli_stmt_execute($updateStmt);

                    $_SESSION['alert_message'] = "Department was on archive and retrieved.";
                }
            } else {
                $_SESSION['alert_message'] = "Department already exists.";
            }
            $_SESSION['alert_type'] = $info_color;
        } else {
            // Department doesn't exist, add it
            $insertQuery = "INSERT INTO tbl_designations (DesignationName, DesignationDescription) VALUES (?, ?)";
            $insertStmt = mysqli_prepare($database, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "ss", $DesignationName, $DesignationDescription);
            mysqli_stmt_execute($insertStmt);

            $_SESSION['alert_message'] = "New Designation Successfully Created";
            $_SESSION['alert_type'] = $success_color;
        }
    } catch (Exception $e) {
        $_SESSION['alert_message'] = "An error occurred: " . $e->getMessage();
        $_SESSION['alert_type'] = $error_color;
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    header("Location: " . $location_admin_datamanagement_designation);
    exit();
} else {
    // echo '<script type="text/javascript">window.history.back();</script>';
    header("Location: " . $location_admin_datamanagement_designation);
    exit();
}
?>