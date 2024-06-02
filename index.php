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
    <title>Human Resources of Municipality of Indang - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Welcome to the Municipality of Indang's Human Resources portal, designed to streamline Sick and Vacation Leave processes for employees and offers a straightforward interface for efficient leave applications. Sick Leave benefits include generous allowances with minimal documentation requirements for health-related absences. Vacation Leave features flexible accrual systems and encourages advanced planning, promoting work-life balance. Our committed Human Resources team is ready to assist employees, prioritizing their needs throughout the leave application journey.">
    <?php
    include($constants_file_html_credits);
    ?>
    <link rel="icon" type="image/x-icon" href="<?php echo $assets_logo_icon; ?>">
    <meta name="theme-color" content="#000000">
    <link rel="apple-touch-icon" href="./assets/images/logo192.png">

    <link rel="stylesheet" href="<?php echo $assets_bootstrap_vcss; ?>">
    <link rel="stylesheet" href="<?php echo $assets_bootstrap_css; ?>">
    <script src="<?php echo $assets_jquery; ?>"></script>
    <script src="<?php echo $assets_popper; ?>"></script>
    <script src='<?php echo $assets_bootstrap_js; ?>'></script>

    <link rel='stylesheet' href="<?php echo $assets_fontawesome; ?>">

    <link rel="stylesheet" href="<?php echo $assets_toastify_css; ?>">
    <script src="<?php echo $assets_toastify_js; ?>"></script>

    <link rel="stylesheet" href="<?php echo $assets_css_styles; ?>">
    <link rel="manifest" href="manifest.json">
    <script src="index.js"></script>

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
            <h1 class="login-title">Log In</h1>
            <form action="<?php echo $action_user_login; ?>" method="POST" autoComplete="off" class="login-form">
                <div class="input-container">
                    <div class='inputs-group'>
                        <input type="text" autofocus id="employeeId" name="employeeId" placeholder="Employee ID..."
                            class="login-text-input" required>
                        <input type="password" id="password" name="password" placeholder="Enter Password..."
                            class="login-text-input" required>
                        <label class="login-password-toggle">
                            <input type="checkbox" id="showPassword"> Show Password
                        </label>
                    </div>
                    <div class="d-flex flex-column gap-1 align-items-center">
                        <input type="submit" name="login" value="Login" class="w-100 login-button">
                        <a href="<?php echo $location_forgotpassword; ?>" class="text-primary">Forgot Password?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('showPassword').addEventListener('change', function () {
            var passwordInput = document.getElementById('password');
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    </script>

    <?php include($components_file_toastify); ?>

</body>

</html>