<?php
include ("../../constants/routes.php");
include ($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_employee);
include ($constants_variables);

$employeeData = [];
$fetchLeaveData = [];
$leaveData = [];
$settingData = getAuthorizedUser();

$hasInitialRecord = false;
$hasYearRecord = false;

if (isset($_SESSION['employeeId'])) {
    $employeeId = sanitizeInput($_SESSION['employeeId']);
    $employeeData = getEmployeeData($employeeId);
    $fetchLeaveData = getIncentiveLeaveComputation($employeeId);
}

$selectedYear = date("Y");

if (isset($_POST['leaveFormYear']) && $employeeId) {
    $selectedYear = $_POST['year'];
    if (isset($_SESSION['post_dataformyear'])) {
        unset($_SESSION['post_dataformyear']);
    }
} else if (isset($_SESSION['post_dataformyear'])) {
    $selectedYear = $_SESSION['post_dataformyear'];
} else {
    $selectedYear = date("Y");
}

if ($selectedYear) {
    foreach ($fetchLeaveData as $leaveRecord) {

        $periodYear = date('Y', strtotime($leaveRecord['period']));
        $periodEndYear = date('Y', strtotime($leaveRecord['periodEnd']));
        if ($periodYear <= $selectedYear && $periodEndYear >= $selectedYear) {
            $leaveData[] = $leaveRecord;
        }
    }
}

if (!empty($fetchLeaveData)) {
    foreach ($fetchLeaveData as $fdata) {
        if ($fdata['recordType'] == "Initial Record" && $fdata['particular'] == "Initial Record") {
            $hasInitialRecord = true;
            break; // No need to continue checking, we found one Initial Record
        }
    }
}

if (!empty($leaveData)) {
    $hasYearRecord = true;
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
    include ($constants_file_html_credits);
    ?>
    <link rel="icon" type="image/x-icon" href="<?php echo $assets_logo_icon; ?>">

    <link rel="stylesheet" href="<?php echo $assets_bootstrap_vcss; ?>">
    <script src="<?php echo $assets_bootstrap_vjs; ?>"></script>
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
    <link rel="stylesheet" href="<?php echo $assets_css_printmedia; ?>">

    <!-- <script src="<?php
    // echo $assets_tailwind; 
    ?>"></script> -->
</head>

<body class="webpage-background-cover">
    <div class="component-container">
        <?php include ($components_file_topnav) ?>
    </div>

    <div class="page-container">
        <div class="page-content">

            <div class='box-container'>

                <div class="component-container p-2">
                    <h3 class="title-text">
                        <span>Leave Data Form - Year</span>
                        <span id="selectedYear">
                            <?php echo $selectedYear; ?>
                        </span>
                    </h3>
                </div>

                <div class="button-container component-container mb-2">
                    <form action="" method="post">
                        <label for="year">Select a Year:</label>
                        <select name="year" id="year" class="custom-regular-button" aria-label="Year Selection">
                            <?php
                            $currentYear = date("Y");
                            $start_date = $employeeData['dateStarted'];

                            // Extract the year from the start date
                            $start_year = $start_date ? date("Y", strtotime($start_date)) : $currentYear;

                            if (!$start_year || $start_year <= 1924) {
                                $start_year = $currentYear;
                            }

                            for ($year = $currentYear; $year >= $start_year; $year--) {
                                ?>
                                <option value="<?php echo $year; ?>" <?php echo ($year == $selectedYear) ? 'selected' : ''; ?>>
                                    <?php echo $year; ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                        <input type="submit" name="leaveFormYear" value="Load Year Record"
                            class="custom-regular-button">
                    </form>
                    <button type="button" class="custom-regular-button" onclick="window.print()">Print</button>
                </div>

                <div class="print-form-container">
                    <div class='data-form-detail-container mb-3'>
                        <div>Republic of the Philippines</div>
                        <div>Province of Cavite</div>
                        <div>Municipality of Indang</div>
                    </div>

                    <div class="overflow-auto custom-scrollbar">
                        <table id="adjustable-table" class="data-form-detail-table">
                            <thead>
                                <tr>
                                    <th colspan="3" style="width: 30%;" class="table-head-base-front">
                                        <div>Name</div>
                                        <div class="table-item-base-none">
                                            <?php
                                            if (isset($employeeData['firstName']) && isset($employeeData['lastName'])) {
                                                echo $employeeData['firstName'] . ' ' . $employeeData['lastName'];
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </div>
                                    </th>
                                    <th colspan="5" style="width: 40%;" class="table-head-base-front">
                                        <div>Division/Office</div>
                                        <div class="table-item-base-none">
                                            <?php
                                            if (isset($employeeData['departmentName'])) {
                                                echo $employeeData['departmentName'];
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </div>
                                    </th>
                                    <th colspan="3" style="width: 30%;" class="table-head-base-front">
                                        <div>1st. Day of Service</div>
                                        <div class="table-item-base-none">
                                            <?php
                                            if (isset($employeeData['dateStarted'])) {
                                                echo $employeeData['dateStarted'];
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="width: 10%;" class="table-head-base">Period</th>
                                    <th rowspan="2" style="width: 10%;" class="table-head-base">Particulars</th>
                                    <th colspan="4" style="width: 30%;" class="table-head-base">Vacation Leave</th>
                                    <th colspan="4" style="width: 30%;" class="table-head-base">Sick Leave</th>
                                    <th rowspan="2" style="width: 10%;" class="table-head-base">Date & Action Taken on
                                        Application For Leave</th>
                                </tr>
                                <tr>
                                    <th style="width: 7.5%;" class="table-head-base">Earned</th>
                                    <th style="width: 7.5%;" class="table-head-base">Abs. und. w/p</th>
                                    <th style="width: 7.5%;" class="table-head-base">Bal.</th>
                                    <th style="width: 7.5%;" class="table-head-base">Abs. und. w/o p</th>

                                    <th style="width: 7.5%;" class="table-head-base">Earned</th>
                                    <th style="width: 7.5%;" class="table-head-base">Abs. und. w/p</th>
                                    <th style="width: 7.5%;" class="table-head-base">Bal.</th>
                                    <th style="width: 7.5%;" class="table-head-base">Abs. und. w/o p</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($hasYearRecord) {
                                    foreach ($leaveData as $ldata) {
                                        ?>
                                        <!-- <tr key=""> -->
                                        <?php
                                        if ($ldata['recordType'] == "Monthly Credit" && $ldata['period'] != $selectedYear . "-01-01") {
                                            ?>
                                            <tr>
                                                <td class="table-item-base">
                                                </td>
                                                <td class="table-item-base">
                                                </td>
                                                <td class="table-item-base">
                                                </td>
                                                <td class="table-item-base">
                                                </td>
                                                <td class="table-item-base">
                                                </td>
                                                <td class="table-item-base">
                                                </td>
                                                <td class="table-item-base">
                                                </td>
                                                <td class="table-item-base">
                                                </td>
                                                <td class="table-item-base">
                                                </td>
                                                <td class="table-item-base">
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>

                                        <?php
                                        ?>
                                        <tr>
                                            <td class="table-item-base">
                                                <?php echo $ldata['period'];
                                                if ($ldata['periodEnd'] && $ldata['period'] < $ldata['periodEnd']) {
                                                    echo ' to ' . $ldata['periodEnd'];
                                                }
                                                ?>
                                            </td>
                                            <td title="<?php
                                            if ($ldata['days'] > 0) {
                                                echo ' ' . $ldata['days'] . ' day(s) ';
                                            }
                                            if ($ldata['hours'] > 0) {
                                                echo ' ' . $ldata['hours'] . ' hour(s) ';
                                            }
                                            if ($ldata['minutes'] > 0) {
                                                echo ' ' . $ldata['minutes'] . ' minute(s) ';
                                            }
                                            ?>" class="table-item-base">
                                                <?php
                                                if ($ldata['particular'] == "Others") {
                                                    if ($ldata['particularLabel']) {
                                                        echo $ldata['particularLabel'];
                                                    } else {
                                                        echo $ldata['particular'];
                                                    }
                                                } else {
                                                    echo $ldata['particular'];
                                                    if ($ldata['particularLabel']) {
                                                        echo ' (' . $ldata['particularLabel'] . ')';
                                                    }
                                                }
                                                ?>
                                            </td>

                                            <td class="table-item-base">
                                                <?php echo number_format($ldata['vacationLeaveEarned'], 2); ?>
                                            </td>
                                            <td class="table-item-base">
                                                <?php echo number_format($ldata['vacationLeaveAbsUndWP'], 2); ?>
                                            </td>
                                            <td class="table-item-base">
                                                <?php echo number_format($ldata['vacationLeaveBalance'], 2); ?>
                                            </td>
                                            <td class="table-item-base">
                                                <?php echo number_format($ldata['vacationLeaveAbsUndWOP'], 2); ?>
                                            </td>

                                            <td class="table-item-base">
                                                <?php echo number_format($ldata['sickLeaveEarned'], 2); ?>
                                            </td>
                                            <td class="table-item-base">
                                                <?php echo number_format($ldata['sickLeaveAbsUndWP'], 2); ?>
                                            </td>
                                            <td class="table-item-base">
                                                <?php echo number_format($ldata['sickLeaveBalance'], 2); ?>
                                            </td>
                                            <td class="table-item-base">
                                                <?php echo number_format($ldata['sickLeaveAbsUndWOP'], 2); ?>
                                            </td>

                                            <td class="table-item-base">
                                                <?php echo $ldata['dateOfAction']; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="11" class="py-2">
                                            <div class="py-1 font-weight-light">
                                                There is no Data Found
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>

                <!--
                <div class="px-2 py-4">
                    <?php
                    ?>
                    <div>Prepared by:</div>
                    <div style="width: 18rem;" class="mt-3 text-center underline-input">
                        <?php
                        for ($i = 0; $i < count($settingData); $i++) {
                            if ($settingData[$i]['settingSubject'] == "Human Resources Manager") {
                                echo organizeFullName($settingData[$i]['firstName'], $settingData[$i]['middleName'], $settingData[$i]['lastName'], $settingData[$i]['suffix'], 1);
                            }
                        }
                        ?>
                    </div>
                    <div style="width: 18rem;" class="text-center">
                        <?php
                        for ($i = 0; $i < count($settingData); $i++) {
                            if ($settingData[$i]['settingSubject'] == "Human Resources Manager") {
                                if ($settingData[$i]['jobPosition'] != "") {
                                    echo $settingData[$i]['jobPosition'];
                                } else {
                                    echo "Human Resources Manager";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                    -->

            </div>

        </div>
    </div>

    <?php if ($hasYearRecord) { ?>
        <script src="<?php echo $assets_adjustableTableCell_js; ?>"></script>
    <?php } ?>

    <div class="component-container">
        <?php include ($components_file_footer); ?>
    </div>

    <?php include ($components_file_toastify); ?>

</body>

</html>