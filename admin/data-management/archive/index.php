<?php
include("../../../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_admin);
include($constants_variables);

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
    <div class="component-container">
        <?php include($components_file_topnav); ?>
    </div>

    <div class="page-container">
        <div class="page-content">

            <div class="box-container">
                <div>
                    <a href="<?php echo $location_admin_datamanagement; ?>"><button
                            class="custom-regular-button">Back</button></a>
                    <div class="title-text">Archive Data(s)</div>
                </div>
                <div class="item-detail-container mb-2">
                    <a href="<?php echo $location_admin_datamanagement_archive_employee; ?>"
                        class="item-detail-container-summary">
                        Employee
                    </a>
                </div>
                <div class="item-detail-container mb-2">
                    <a href="<?php echo $location_admin_datamanagement_archive_department; ?>"
                        class="item-detail-container-summary">
                        Department
                    </a>
                </div>
                <div class="item-detail-container mb-2">
                    <a href="<?php echo $location_admin_datamanagement_archive_leaveform; ?>"
                        class="item-detail-container-summary">
                        Leave Form
                    </a>
                </div>
                <div class="item-detail-container mb-2">
                    <a href="<?php echo $location_admin_datamanagement_archive_leavedata; ?>"
                        class="item-detail-container-summary">
                        Leave Data Form
                    </a>
                </div>
                <div class="item-detail-container">
                    <a href="<?php echo $location_admin_datamanagement_archive_designation; ?>"
                        class="item-detail-container-summary">
                        Work Designations
                    </a>
                </div>
            </div>

        </div>
    </div>

    <div class="component-container">
        <?php
        include($components_file_footer);
        ?>
    </div>

    <?php include($components_file_toastify); ?>

</body>

</html>