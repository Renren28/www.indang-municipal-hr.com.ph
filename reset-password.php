<?php
include("./constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_login);
include($constants_variables);

$hasValidResetToken = false;
$tokenData = [];

if (isset($_POST['validateResetToken'])) {
    $resetToken = strip_tags(mysqli_real_escape_string($database, $_POST['providedResetToken']));
    header("Location: " . $location_resetpassword . '?resetToken=' . $resetToken);
} else if (isset($_GET['resetToken'])) {
    $resetToken = strip_tags(mysqli_real_escape_string($database, $_GET['resetToken']));
    $_SESSION['resetToken'] = $resetToken;

    $checkTokenQuery = "SELECT * FROM tbl_passwordreset_tokens 
                        WHERE resetTokenHash = ? 
                        AND resetTokenExpiration > NOW() AND status > 0";

    $checkTokenStatement = $database->prepare($checkTokenQuery);
    $checkTokenStatement->bind_param("s", $resetToken);
    $checkTokenStatement->execute();

    $checkTokenResult = $checkTokenStatement->get_result();

    if ($checkTokenResult->num_rows > 0) {
        $hasValidResetToken = true;
        $tokenData = $checkTokenResult->fetch_assoc();
    } else {
        $_SESSION['alert_message'] = "Invalid, Expired, Used, Not Existing Reset Token";
        $_SESSION['alert_type'] = $warning_color;
    }

    $checkTokenStatement->close();
}

// Redirects to the Forgot Password
if (!$hasValidResetToken) {
    // $_SESSION['alert_message'] = "Invalid, Expired, Used, Not Existing Reset Token";
    // $_SESSION['alert_type'] = $warning_color;
    header("Location: " . $location_forgotpassword);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Human Resources of Municipality of Indang - Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Welcome to the Municipality of Indang's Human Resources portal, designed to streamline Sick and Vacation Leave processes for employees and offers a straightforward interface for efficient leave applications. Sick Leave benefits include generous allowances with minimal documentation requirements for health-related absences. Vacation Leave features flexible accrual systems and encourages advanced planning, promoting work-life balance. Our committed Human Resources team is ready to assist employees, prioritizing their needs throughout the leave application journey.">
    <?php
    include($constants_file_html_credits);
    ?>
    <link rel="icon" type="image/x-icon" href="<?php echo $assets_logo_icon; ?>">

    <link rel="stylesheet" href="<?php echo $assets_bootstrap_vcss; ?>">
    <link rel="stylesheet" href="<?php echo $assets_bootstrap_css; ?>">
    <script src="<?php echo $assets_jquery; ?>"></script>
    <script src="<?php echo $assets_popper; ?>"></script>
    <script src='<?php echo $assets_bootstrap_js; ?>'></script>

    <link rel='stylesheet' href="<?php echo $assets_fontawesome; ?>">

    <link rel="stylesheet" href="<?php echo $assets_toastify_css; ?>">
    <script src="<?php echo $assets_toastify_js; ?>"></script>

    <link rel="stylesheet" href="<?php echo $assets_css_styles; ?>">

    <!-- <script src="<?php
    // echo $assets_tailwind; 
    ?>"></script> -->
</head>

<body class="login-body">
    <div class="login-page-content custom-scrollbar">
        <div class="login-container">
            <div class="logo-container">
                <img src="./assets/images/indang-logo.png" alt="Web Logo" class="web-logo">
            </div>
            <h1 class="login-title">Reset Password</h1>

            <?php
            if ($hasValidResetToken) {
                ?>

                <form action="<?php echo $action_resetpassword; ?>" method="POST" autoComplete="off" class="login-form">
                    <div class="input-container">
                        <div class='inputs-group'>
                            <label for="resetPassword" class="login-password-toggle mb-0">
                                <!-- <div class="font-weight-bold" >Enter your New Password</div> -->
                                <div><b class="font-weight-bold">Employee ID:</b>
                                    <?php echo isset($tokenData['employee_id']) ? $tokenData['employee_id'] : ''; ?>
                                </div>
                            </label>
                            <input type="password" id="resetPassword" name="newPassword" placeholder="Enter New Password..."
                                class="login-text-input" autofocus required>
                            <label class="login-password-toggle">
                                <input type="checkbox" id="showPassword"> Show Password
                            </label>
                        </div>
                        <div class="d-flex flex-column gap-1 align-items-center">
                            <input type="submit" name="submitResetNewPassword" value="Submit New Password"
                                class="w-100 login-button">
                            <a href="<?php echo $location_login; ?>" class="text-primary">Log In?</a>
                        </div>
                    </div>
                </form>

                <?php
            } else {
                ?>
                <form action="" method="POST" autoComplete="off" class="login-form">
                    <div class="input-container">
                        <div class='inputs-group'>
                            <label for="provideResetToken" class="login-password-toggle mb-0">
                                Provide Reset Token
                            </label>
                            <input type="text" id="provideResetToken" name="providedResetToken"
                                placeholder="Enter Reset Token..." class="login-text-input" autofocus required>
                        </div>
                        <div class="d-flex flex-column gap-1 align-items-center">
                            <input type="submit" name="validateResetToken" value="Validate Reset Token"
                                class="w-100 login-button">
                            <a href="<?php echo $location_forgotpassword; ?>" class="text-primary">Forgot Password?</a>
                        </div>
                    </div>
                </form>
                <?php
            }
            ?>

        </div>
    </div>

    <?php
    if ($hasValidResetToken) {
        ?>
        <script>
            document.getElementById('showPassword').addEventListener('change', function () {
                var passwordInput = document.getElementById('resetPassword');
                if (this.checked) {
                    passwordInput.type = 'text';
                } else {
                    passwordInput.type = 'password';
                }
            });
        </script>

        <?php
    }
    ?>

    <?php include($components_file_toastify); ?>

</body>

</html>