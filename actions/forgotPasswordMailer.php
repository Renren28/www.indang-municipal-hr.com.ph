<?php
include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_login);
include($constants_variables);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require($assets_phpmailer_exception);
require($assets_phpmailer);
require($assets_phpmailer_smtp);

if (isset($_REQUEST['sendForgotPassword'])) {
    $toBeVerify = strip_tags(mysqli_real_escape_string($database, $_POST['toBeVerify']));

    $checkExistQuery = "SELECT * FROM tbl_useraccounts WHERE employee_id = ? AND UPPER(archive) != 'DELETED'";
    $checkExistStatement = mysqli_prepare($database, $checkExistQuery);

    mysqli_stmt_bind_param($checkExistStatement, "s", $toBeVerify);
    mysqli_stmt_execute($checkExistStatement);

    $checkExistResult = mysqli_stmt_get_result($checkExistStatement);

    $userData = mysqli_fetch_assoc($checkExistResult);

    if ($userData) {
        $token = bin2hex(random_bytes(50));

        $email_message = '<b>Hello! ' . $userData['firstName'] . ' ' . ($userData['middleName'] != "" ? substr($userData['middleName'], 0, 1) . '. ' : '') . $userData['lastName'] . '</b>
        <h3>We received a request to reset your password with the Employee ID: ' . $userData['employee_id'] . '.</h3>
        <p>Kindly click the below link to reset your password</p>'
            . $webResetPasswordMessageLink . '?resetToken=' . $token .
            '<br><br>
        <p>With regards,</p>
        <b>Human Resources System</b>';

        $email_nonhtml = "Greetings! " . $userData['firstName'] . ' ' . ($userData['middleName'] != "" ? substr($userData['middleName'], 0, 1) . '. ' : '') . $userData['lastName'] . ". We have received your request to reset your password. Click this Link: " . $location_resetpassword . '?resetToken=' . $token;

        $recordResetPasswordTokenQuery = "INSERT INTO tbl_passwordreset_tokens 
        (employee_id, email, resetTokenHash, resetTokenExpiration, status) 
        VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR), 1)";

        $recordResetPasswordTokenStatement = $database->prepare($recordResetPasswordTokenQuery);
        $recordResetPasswordTokenStatement->bind_param("sss", $toBeVerify, $userData['email'], $token);
        $recordResetPasswordTokenStatement->execute();
        $recordResetPasswordTokenStatement->close();

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   //Enable SMTP authentication

            $mail->Username = 'indang.mun.hr.sil@gmail.com';                     //SMTP username
            $mail->Password = 'pxec bzws ouce cehh';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
            $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('indang.mun.hr.sil@gmail.com', 'Human Resources Manager');
            $mail->addAddress($userData['email']);     //Add a recipient
            // $mail->addAddress('ellen@example.com');               //Name is optional
            $mail->addReplyTo('indang.mun.hr.sil@gmail.com', 'Human Resources Manager');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Password Reset';
            $mail->Body = $email_message;

            $mail->AltBody = $email_nonhtml;

            $mail->send();

            $_SESSION['alert_message'] = 'Password Reset Link has been Sent to ' . $userData['email'];
            $_SESSION['alert_type'] = $success_color;
            $_SESSION['temp_message'] = 'Password Reset Link has been Sent to ' . $userData['email'];

        } catch (Exception $e) {
            $_SESSION['alert_message'] = "Password Reset Link could not be Sent. Mailer Error: {$mail->ErrorInfo}";
            $_SESSION['alert_type'] = $error_color;
            header("Location: " . $location_forgotpassword);
            exit();
        }

    } else {
        $_SESSION['alert_message'] = "Useraccount does not exist";
        $_SESSION['alert_type'] = $warning_color;
    }

    mysqli_stmt_close($checkExistStatement);

    header("Location: " . $location_forgotpassword);
    exit();
} else {
    header("Location: " . $location_forgotpassword);
    exit();
}

?>