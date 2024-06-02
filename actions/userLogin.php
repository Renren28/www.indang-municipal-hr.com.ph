<?php
include ("../constants/routes.php");
include ($constants_file_dbconnect);
include ($constants_variables);

@ob_start();
session_start();

if (isset($_REQUEST['login'])) {
    $employeeId = mysqli_real_escape_string($database, $_POST['employeeId']);
    $password = mysqli_real_escape_string($database, $_POST['password']);

    if (!empty($employeeId) && !empty($password)) {
        try {
            $query = "SELECT * FROM tbl_useraccounts WHERE employee_id=? AND BINARY password=? AND UPPER(archive) != 'DELETED'";
            $stmt = mysqli_prepare($database, $query);
            mysqli_stmt_bind_param($stmt, "ss", $employeeId, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (!$result) {
                throw new Exception("Database query failed: " . mysqli_error($database));
            }

            $count = mysqli_num_rows($result);

            if ($count > 0) {
                $user_data = mysqli_fetch_assoc($result);

                if (strcasecmp($user_data['status'], 'Banned') == 0) {
                    $_SESSION['alert_message'] = "Your account has been banned!";
                    $_SESSION['alert_type'] = $error_color;
                    header("Location: " . $location_login);
                    exit();
                }

                session_regenerate_id();
                $_SESSION['employeeId'] = $employeeId;
                $_SESSION['role'] = $user_data['role'];

                switch ($_SESSION['role']) {
                    case 'Admin':
                        $redirect_location = $location_admin;
                        break;
                    case 'Staff':
                        $redirect_location = $location_staff;
                        break;
                    case 'Employee':
                        $redirect_location = $location_employee;
                        break;
                    default:
                        session_destroy();
                        $_SESSION['alert_message'] = "Logged In Failed!";
                        $_SESSION['alert_type'] = $error_color;
                        $redirect_location = $location_login;
                        break;
                }

                $_SESSION['alert_message'] = "Logged In Successful!";
                $_SESSION['alert_type'] = $success_color;
                $_SESSION['alert_pass'] = 'Logged In';
                header("Location: " . $redirect_location);
                exit();
            } else {
                $_SESSION['alert_message'] = "Incorrect username or password. Please try again!";
                $_SESSION['alert_type'] = $warning_color;
            }
        } catch (Exception $e) {
            $error_message = "An error occurred: " . $e->getMessage();
            $_SESSION['alert_message'] = $error_message;
            $_SESSION['alert_type'] = $error_color;
        }
    } else {
        $_SESSION['alert_message'] = "Please fill in both Employee ID and Password fields.";
        $_SESSION['alert_type'] = $warning_color;
    }
}

header("Location: " . $location_login);
exit();
?>