<?php
include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_employee);
include($constants_variables);

$employeeData = [];
$leaveData = [];

$availableSickLeave = "?";
$availableVacationLeave = "?";
$leaveWithoutPay = "?";

$availableSickLeaveTime = "?";
$availableVacationLeaveTime = "?";
$leaveWithoutPayTime = "?";

if (isset($_SESSION['employeeId'])) {
    $employeeId = sanitizeInput($_SESSION['employeeId']);
    $employeeData = getEmployeeData($employeeId);
    $leaveData = getIncentiveLeaveComputationToday($employeeId);
    
    if (count($leaveData) > 0) {
        $availableSickLeave = number_format($leaveData[count($leaveData) - 1]['sickLeaveBalance'], 2);
        $availableVacationLeave = number_format($leaveData[count($leaveData) - 1]['vacationLeaveBalance'], 2);
        $leaveWithoutPay = number_format(($leaveData[count($leaveData) - 1]['vacationLeaveAbsUndWOP'] + $leaveData[count($leaveData) - 1]['vacationLeaveAbsUndWOP']), 2);
        $availableSickLeaveTime = computeExactTime($leaveData[count($leaveData) - 1]['sickLeaveBalance']);
        $availableVacationLeaveTime = computeExactTime($leaveData[count($leaveData) - 1]['vacationLeaveBalance']);
        $leaveWithoutPayTime = computeExactTime(($leaveData[count($leaveData) - 1]['vacationLeaveAbsUndWOP'] + $leaveData[count($leaveData) - 1]['vacationLeaveAbsUndWOP']));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Human Resources of Municipality of Indang - Employee</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HR - Indang Municipality Employee Page">
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

<body class="webpage-background-cover">
    <div>
        <?php include($components_file_topnav) ?>
    </div>

    <div class="page-container">
        <div class="page-content">
            <div class="card-container">

                <div class="card">
                    <h1>Available Vacation Leave:</h1>
                    <h2 title="<?php echo $availableVacationLeaveTime; ?>"><?php echo $availableVacationLeave; ?></h2>
                </div>

                <div class="card">
                    <h1>Available Sick Leave:</h1>
                    <h2 title="<?php echo $availableSickLeaveTime; ?>"><?php echo $availableSickLeave; ?></h2>
                </div>
                
                <div class="card">
                    <h1>Leave Under W/O Pay:</h1>
                    <h2 title="<?php echo $leaveWithoutPayTime; ?>"><?php echo $leaveWithoutPay; ?></h2>
                </div>

            </div>
        </div>
    </div>

    <div>
        <?php
        include($components_file_footer)
            ?>
    </div>

    <?php include($components_file_toastify); ?>

</body>

</html>