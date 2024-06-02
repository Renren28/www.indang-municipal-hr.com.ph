<?php
include("../constants/routes.php");
include($constants_file_dbconnect);
include($constants_file_session_login);
include($constants_variables);

if (isset($_POST['submitResetNewPassword']) && $_SESSION['resetToken']) {
    $resetToken = $_SESSION['resetToken'];
    $newPassword = strip_tags(mysqli_real_escape_string($database, $_POST['newPassword']));

    $checkTokenQuery = "SELECT employee_id FROM tbl_passwordreset_tokens 
                        WHERE resetTokenHash = ? 
                        AND resetTokenExpiration > NOW() AND status > 0";
    $checkTokenStatement = mysqli_prepare($database, $checkTokenQuery);
    mysqli_stmt_bind_param($checkTokenStatement, "s", $resetToken);
    mysqli_stmt_execute($checkTokenStatement);
    $checkTokenResult = mysqli_stmt_get_result($checkTokenStatement);

    if ($checkTokenResult && $checkTokenResult->num_rows > 0) {
        $row = $checkTokenResult->fetch_assoc();
        $fetchEmployeeId = $row['employee_id'];

        $updatePasswordQuery = "UPDATE tbl_useraccounts SET password = ? WHERE employee_id = ?";
        $updatePasswordStatement = mysqli_prepare($database, $updatePasswordQuery);
        mysqli_stmt_bind_param($updatePasswordStatement, "ss", $newPassword, $fetchEmployeeId);
        mysqli_stmt_execute($updatePasswordStatement);

        $updateTokenStatusQuery = "UPDATE tbl_passwordreset_tokens SET status = status - 1 WHERE resetTokenHash = ?";
        $updateTokenStatusStatement = mysqli_prepare($database, $updateTokenStatusQuery);
        mysqli_stmt_bind_param($updateTokenStatusStatement, "s", $resetToken);
        mysqli_stmt_execute($updateTokenStatusStatement);

        $_SESSION['alert_message'] = "Password Successfully Reset and Updated!";
        $_SESSION['alert_type'] = $success_color;

        header("Location: " . $location_login);
        exit();
    } else {
        $_SESSION['alert_message'] = "Invalid, Expired, Used Reset Token!";
        $_SESSION['alert_type'] = $warning_color;
        header("Location: " . $location_login);
        exit();
    }
} else {
    header("Location: " . $location_login);
    exit();
}
?>