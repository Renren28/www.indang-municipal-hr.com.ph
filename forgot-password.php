<?php
include("./constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_login);
include($constants_variables);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Human Resources of Municipality of Indang - Forgot Password</title>
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
            <h1 class="login-title">Forgot Password</h1>
            <form action="<?php echo $action_forgotpassword_mailer; ?>" method="POST" autoComplete="off"
                class="login-form">
                <div class="input-container">
                    <div class='inputs-group'>
                        <label for="toBeVerify" class="login-password-toggle mb-0">
                            Enter Your Employee ID
                        </label>
                        <input type="text" name="toBeVerify" autofocus placeholder="REG001" id="toBeVerify"
                            class="login-text-input" required>
                    </div>
                    <?php if (isset($_SESSION['temp_message'])) {
                        ?>
                        <div class="text-center text-white font-monospace">
                            <?php echo $_SESSION['temp_message']; ?>
                        </div>
                        <?php
                    } unset($_SESSION['temp_message']); ?>
                    <div class="d-flex flex-column gap-1 align-items-center">
                        <input type="submit" name="sendForgotPassword" value="Send Verification Link"
                            class="w-100 login-button">
                        <a href="<?php echo $location_login; ?>" class="text-primary">Log In?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php include($components_file_toastify); ?>

</body>

</html>