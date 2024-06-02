<?php
include ("../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
include ($constants_variables);

if (isset($_POST['changeUserProfilePassword'])) {
    $employeeId = sanitizeInput($_SESSION['employeeId'] ?? null);
    $email = sanitizeInput($_POST["email"] ?? 'default@example.com');
    $firstName = sanitizeInput($_POST["firstName"] ?? '');
    $middleName = sanitizeInput($_POST["middleName"] ?? '');
    $lastName = sanitizeInput($_POST["lastName"] ?? '');
    $suffix = sanitizeInput($_POST["suffix"] ?? '');
    $birthdate = sanitizeInput($_POST["birthdate"] ?? '');
    $sex = sanitizeInput($_POST["sex"] ?? '');
    $civilStatus = sanitizeInput($_POST["civilStatus"] ?? '');
    $department = sanitizeInput($_POST["department"] ?? '');
    $jobPosition = sanitizeInput($_POST["jobPosition"] ?? '');
    $dateStarted = sanitizeInput($_POST["dateStarted"] ?? date('Y-m-d'));

    $noWarning = false;

    if ($birthdate >= $dateStarted || $birthdate > date('Y-m-d')) {
        $_SESSION['alert_message'] = "Invalid Birthdate!";
        $_SESSION['alert_type'] = $warning_color;
    } else if (yearDifference($birthdate, $dateStarted) < $legalAge) {
        $_SESSION['alert_message'] = "Does not meet the Legal Age!";
        $_SESSION['alert_type'] = $warning_color;
    } else if ($dateStarted > $firstDayNextMonth || $dateStarted < $minDate) {
        $_SESSION['alert_message'] = "Starting Date must be at least 1 month from now!";
        $_SESSION['alert_type'] = $warning_color;
    } else {
        $noWarning = true;
    }

    if (!$noWarning) {
        header("Location: " . $location_admin_profile);
        exit();
    }

    $sql = "
        UPDATE tbl_useraccounts 
        SET email = ?, firstName = ?, middleName = ?, lastName = ?, suffix = ?, birthdate = ?, sex = ?, civilStatus = ?, department = ?, jobPosition = ?, dateStarted = ?
        WHERE UPPER(role) = 'ADMIN' AND employee_id = ?
    ";

    $stmt = $database->prepare($sql);
    if ($stmt === false) {
        $_SESSION['alert_message'] = "Error preparing statement: " . $database->error;
        $_SESSION['alert_type'] = $error_color;
        header("Location: " . $location_admin_profile);
        exit();
    }

    $stmt->bind_param('ssssssssssss', $email, $firstName, $middleName, $lastName, $suffix, $birthdate, $sex, $civilStatus, $department, $jobPosition, $dateStarted, $employeeId);

    if ($stmt->execute()) {
        $_SESSION['alert_message'] = "Profile Updated Successfully!";
        $_SESSION['alert_type'] = $success_color;
    } else {
        $_SESSION['alert_message'] = "Error Updating Profile: " . $stmt->error;
        $_SESSION['alert_type'] = $error_color;
    }

    $stmt->close();

    header("Location: " . $location_admin_profile);
    exit();
} else {
    // echo '<script type="text/javascript">window.history.back();</script>';
    header("Location: " . $location_admin_profile);
    exit();
}

?>