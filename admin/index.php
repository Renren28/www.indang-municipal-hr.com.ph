<?php
include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_admin);
include($constants_variables);

// Counts the Total Number of Department
$departmentCount = 0;
$departmentCountQuery = "SELECT COUNT(*) AS departmentCount FROM tbl_departments WHERE UPPER(archive) != 'DELETED'";
$departmentCountResult = $database->query($departmentCountQuery);

if ($departmentCountResult) {
    $row = $departmentCountResult->fetch_assoc();
    $departmentCount = $row['departmentCount'];
}

// Counts the Total Number of the Employees Only
$employeeCount = 0;
$employeeCountQuery = "SELECT COUNT(*) AS employeeCount FROM tbl_useraccounts WHERE UPPER(role) != 'ADMIN' AND UPPER(archive) != 'DELETED'";
$employeeCountResult = $database->query($employeeCountQuery);

if ($employeeCountResult) {
    $row = $employeeCountResult->fetch_assoc();
    $employeeCount = $row['employeeCount'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Human Resources of Municipality of Indang - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HR - Indang Municipality Admin Page">
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

    <link rel="stylesheet" href="<?php echo $assets_datatable_css; ?>">
    <script src="<?php echo $assets_datatable_js; ?>"></script>

    <link rel="stylesheet" href="<?php echo $assets_datatable_css_select; ?>">
    <script src="<?php echo $assets_datatable_js_select; ?>"></script>

    <link rel="stylesheet" href="<?php echo $assets_datatable_bootstrap; ?>">

    <link rel="stylesheet" href="<?php echo $assets_css_styles; ?>">

    <!-- <script src="<?php
    // echo $assets_tailwind; 
    ?>"></script> -->
</head>

<body class="webpage-background-cover-admin">
    <div>
        <?php include($components_file_topnav) ?>
    </div>

    <div class="page-container">
        <div class="page-content">
            <div class="card-container">

                <div class="card">
                    <h1>Total No. of Employees</h1>
                    <h2><?php echo $employeeCount; ?></h2>
                </div>

                <div class="card">
                    <h1>Total No. of Departments</h1>
                    <h2><?php echo $departmentCount; ?></h2>
                </div>

            </div>
        </div>
    </div>

    <div>
        <?php
        include($components_file_footer);
        ?>
    </div>

    <?php include($components_file_toastify); ?>

</body>

</html>