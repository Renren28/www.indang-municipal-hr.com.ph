<?php
include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_admin);
include($constants_variables);

if (isset($_POST['changeSetting'])) {
    $settingId = strip_tags(mysqli_real_escape_string($database, $_POST['settingIdentifier']));
    $selectedUser = isset($_POST['selectedAuthorizedUser']) ? strip_tags(mysqli_real_escape_string($database, $_POST['selectedAuthorizedUser'])) : "";
    $nameOfInCharge = isset($_POST['nameOfInCharge']) ? strip_tags(mysqli_real_escape_string($database, $_POST['nameOfInCharge'])) : "";
    $isFromDataHR = isset($_POST['isFromDataHR']) ? strip_tags(mysqli_real_escape_string($database, $_POST['isFromDataHR'])) : "";
    $isFromDataMayor = isset($_POST['isFromDataMayor']) ? strip_tags(mysqli_real_escape_string($database, $_POST['isFromDataMayor'])) : "";

    $settingData = [];
    $departmentSearch = "";

    $settingQuery = "SELECT * FROM tbl_systemsettings WHERE setting_id = ?";

    $settingStmt = mysqli_prepare($database, $settingQuery);

    if ($settingStmt) {
        mysqli_stmt_bind_param($settingStmt, "i", $settingId);
        mysqli_stmt_execute($settingStmt);

        $result = mysqli_stmt_get_result($settingStmt);

        if ($result) {
            $settingData = mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            if ($settingData) {
                if ($isFromDataHR == 'on' || $isFromDataMayor == 'on') {
                    if ($settingData['settingType'] == "Authorized User" && $selectedUser == "") {
                        if ($settingData['settingSubject'] == "Human Resources Manager") {
                            $departmentSearch = "Department of Human Resources";
                        } else if ($settingData['settingSubject'] == "Municipal Mayor") {
                            $departmentSearch = "Municipal Office";
                        }

                        $departmentQuery = "SELECT departmentHead FROM tbl_departments WHERE LOWER(departmentName) = LOWER(?)";
                        $departmentStmt = mysqli_prepare($database, $departmentQuery);

                        if ($departmentStmt) {
                            mysqli_stmt_bind_param($departmentStmt, "s", $departmentSearch);
                            mysqli_stmt_execute($departmentStmt);

                            $departmentResult = mysqli_stmt_get_result($departmentStmt);

                            if ($departmentResult) {
                                $departmentData = mysqli_fetch_assoc($departmentResult);
                                mysqli_free_result($departmentResult);

                                $updateQuery = "UPDATE tbl_systemsettings SET settingKey = ?, settingInCharge = '' WHERE setting_id = ?";
                                $updateStmt = mysqli_prepare($database, $updateQuery);

                                if ($updateStmt) {
                                    mysqli_stmt_bind_param($updateStmt, "ss", $departmentData['departmentHead'], $settingId);

                                    if (mysqli_stmt_execute($updateStmt)) {
                                        $_SESSION['alert_message'] = "Automatically Sets Authorized User!";
                                        $_SESSION['alert_type'] = $success_color;
                                    } else {
                                        $_SESSION['alert_message'] = "Authorized User Changing Failed: " . mysqli_stmt_error($updateStmt);
                                        $_SESSION['alert_type'] = $error_color;
                                    }

                                    mysqli_stmt_close($updateStmt);
                                } else {
                                    $_SESSION['alert_message'] = "Error preparing the update statement: " . mysqli_error($database);
                                    $_SESSION['alert_type'] = $error_color;
                                }

                            } else {
                                $_SESSION['alert_message'] = "Error executing the department query: " . mysqli_stmt_error($departmentStmt);
                                $_SESSION['alert_type'] = $error_color;
                            }

                            mysqli_stmt_close($departmentStmt);
                        } else {
                            $_SESSION['alert_message'] = "Error preparing the department statement: " . mysqli_error($database);
                            $_SESSION['alert_type'] = $error_color;
                        }
                    } else if ($settingData['settingType'] == "Authorized User" && $selectedUser != "") {
                        $updateQuery = "UPDATE tbl_systemsettings SET settingKey = ?, settingInCharge = '' WHERE setting_id = ?";
                        $updateStmt = mysqli_prepare($database, $updateQuery);

                        if ($updateStmt) {
                            mysqli_stmt_bind_param($updateStmt, "ss", $selectedUser, $settingId);

                            if (mysqli_stmt_execute($updateStmt)) {
                                $_SESSION['alert_message'] = "Authorized User Successfully Changed!";
                                $_SESSION['alert_type'] = $success_color;
                            } else {
                                $_SESSION['alert_message'] = "Authorized User Changing Failed: " . mysqli_stmt_error($updateStmt);
                                $_SESSION['alert_type'] = $error_color;
                            }

                            mysqli_stmt_close($updateStmt);
                        } else {
                            $_SESSION['alert_message'] = "Error preparing the update statement: " . mysqli_error($database);
                            $_SESSION['alert_type'] = $error_color;
                        }
                    } else {
                        $_SESSION['alert_message'] = "Not Yet Available!";
                        $_SESSION['alert_type'] = $warning_color;
                    }
                } else {
                    if ($settingData['settingType'] == "Authorized User") {
                        $updateQuery = "UPDATE tbl_systemsettings SET settingInCharge = ?, settingKey = '' WHERE setting_id = ?";
                        $updateStmt = mysqli_prepare($database, $updateQuery);

                        if ($updateStmt) {
                            mysqli_stmt_bind_param($updateStmt, "ss", $nameOfInCharge, $settingId);

                            if (mysqli_stmt_execute($updateStmt)) {
                                $_SESSION['alert_message'] = "Authorized User Successfully Changed!";
                                $_SESSION['alert_type'] = $success_color;
                            } else {
                                $_SESSION['alert_message'] = "Authorized User Changing Failed: " . mysqli_stmt_error($updateStmt);
                                $_SESSION['alert_type'] = $error_color;
                            }

                            mysqli_stmt_close($updateStmt);
                        } else {
                            $_SESSION['alert_message'] = "Error preparing the update statement: " . mysqli_error($database);
                            $_SESSION['alert_type'] = $error_color;
                        }
                    }
                }

            }
        } else {
            $_SESSION['alert_message'] = "Error executing the query: " . mysqli_stmt_error($settingStmt);
            $_SESSION['alert_type'] = $error_color;
        }

        mysqli_stmt_close($settingStmt);
    } else {
        $_SESSION['alert_message'] = "Error preparing the statement: " . mysqli_error($database);
        $_SESSION['alert_type'] = $error_color;
    }

    header("Location: " . $location_admin_datamanagement);
    exit();
} else {
    header("Location: " . $location_admin_datamanagement);
    exit();
}
?>