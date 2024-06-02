<?php
include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);

@ob_start();
session_start();

$session_timeout = 8 * 60 * 60;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_timeout)) {
    session_unset();
    session_destroy();
    header("Location: " . $location_login);
}

$_SESSION['last_activity'] = time();

include($constants_variables);

if (isset($_POST['changeUserProfilePassword']) && $_SESSION['employeeId']) {
    $employeeId = $_SESSION['employeeId'];
    $currentPassword = strip_tags(mysqli_real_escape_string($database, $_POST['currentPassword']));
    $newPassword = strip_tags(mysqli_real_escape_string($database, $_POST['newPassword']));
    $confirmPassword = strip_tags(mysqli_real_escape_string($database, $_POST['confirmPassword']));

    // echo $newPassword;
    // echo $currentPassword;
    // echo $confirmPassword;

    $query = "SELECT password FROM tbl_useraccounts WHERE employee_id = ?";
    $stmt = mysqli_prepare($database, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $employeeId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $storedPassword);

        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);

            if ($currentPassword == $storedPassword) {
                if ($newPassword == $confirmPassword) {
                    if ($storedPassword != $newPassword) {
                        $query = "UPDATE tbl_useraccounts SET password = ? WHERE employee_id = ?";
                        $stmt = mysqli_prepare($database, $query);

                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, "ss", $newPassword, $employeeId);

                            if (mysqli_stmt_execute($stmt)) {
                                $_SESSION['alert_message'] = "Password Successfully Changed!";
                                $_SESSION['alert_type'] = $success_color;
                            } else {
                                $_SESSION['alert_message'] = "Password Changing Failed: " . mysqli_stmt_error($stmt);
                                $_SESSION['alert_type'] = $error_color;
                            }

                            mysqli_stmt_close($stmt);
                        } else {
                            $_SESSION['alert_message'] = "Error On Updating Password: " . mysqli_error($database);
                            $_SESSION['alert_type'] = $error_color;
                        }
                    } else {
                        $_SESSION['alert_message'] = "Current Password and the New Password was the Same!";
                        $_SESSION['alert_type'] = $warning_color;
                    }
                } else {
                    $_SESSION['alert_message'] = "New Password and Confirm Password do not match!";
                    $_SESSION['alert_type'] = $warning_color;
                }
            } else {
                $_SESSION['alert_message'] = "Incorrect Current Password!";
                $_SESSION['alert_type'] = $warning_color;
            }
        }
    }

    if (strcasecmp($_SESSION['role'], "Admin") == 0) {
        header("Location: " . $location_admin_profile);
    } else if (strcasecmp($_SESSION['role'], "Employee") == 0) {
        header("Location: " . $location_employee_profile);
    }
    exit();
} else {
    if (isset($_SESSION['role']) && isset($_SESSION['employeeId'])) {
        if (strcasecmp($_SESSION['role'], "Admin") == 0) {
            header("Location: " . $location_admin_profile);
        } else if (strcasecmp($_SESSION['role'], "Employee") == 0) {
            header("Location: " . $location_employee_profile);
        }
    }
    exit();
}
?>