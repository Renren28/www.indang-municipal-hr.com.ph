<?php
include ("../../../../constants/routes.php");
include ($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_staff);
include ($constants_variables);

$empId = isset($_GET['empid']) ? filter_var($_GET['empid'], FILTER_SANITIZE_STRING) : null;

$leaveAppDataList = [];
$employeeData = [];
$fullName = "";
$allow_op = false;

if ($empId !== 'index.php' && $empId !== 'index.html' && $empId !== null) {
    $_SESSION['post_empId'] = $empId;

    $empId = sanitizeInput($empId);

    $employeeData = getEmployeeData($empId);
    if (!empty($employeeData)) {
        $fullName = organizeFullName($employeeData['firstName'], $employeeData['middleName'], $employeeData['lastName'], $employeeData['suffix'], 1);
        if (strtoupper($employeeData['role']) == "EMPLOYEE") {
            $allow_op = true;
        }
    }

    $selectedYear = date("Y");
    if (isset($_POST['leaveFormYear'])) {
        $selectedYear = sanitizeInput($_POST['selectedYear']);
        $_SESSION['post_leaveformyear'] = $selectedYear;
    } else if (isset($_SESSION['post_leaveformyear'])) {
        $selectedYear = sanitizeInput($_SESSION['post_leaveformyear']);
    } else {
        $selectedYear = date("Y");
        if (isset($_SESSION['post_leaveformyear'])) {
            unset($_SESSION['post_leaveformyear']);
        }
    }

    if ($selectedYear && $selectedYear != 'All') {
        $leaveAppDataList = getLeaveAppFormRecordBasedYear($empId, $selectedYear);
    } else if ($selectedYear == 'All') {
        $leaveAppDataList = getLeaveAppFormRecord($empId);
    }

} else {
    if (isset($_SESSION['post_empId'])) {
        unset($_SESSION['post_empId']);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Human Resources of Municipality of Indang - Staff</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HR - Indang Municipality Staff Page">
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

    <!-- <script src="<?php
    // echo $assets_tailwind; 
    ?>"></script> -->
</head>

<body class="webpage-background-cover-admin">
    <div class="component-container">
        <?php include ($components_file_topnav) ?>
    </div>

    <div class="page-container">
        <div class="page-content">

            <div class="component-container">
                <?php include ($components_file_navpanelstaff); ?>
            </div>

            <div class="box-container">
                <div class="p-2">
                    <h3 class="title-text">Leave Application Record</h3>
                    <div class="title-text-caption">
                        (
                        <?php echo $fullName; ?>)
                    </div>
                </div>

                <div class="button-container component-container mb-2">
                    <form action="" method="post">
                        <label for="selectedYear">Select a Year:</label>
                        <select name="selectedYear" id="selectedYear" class="custom-regular-button"
                            aria-label="Year Selection">
                            <?php
                            $currentYear = date("Y");
                            $start_date = $employeeData['dateStarted'];

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
                </div>

                <table id="leaveAppList" class="text-center hover table-striped cell-border order-column"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>Type of Leave</th>
                            <th>Inclusive Dates</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($leaveAppDataList)) {
                            foreach ($leaveAppDataList as $ldata) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $ldata['typeOfLeave']; ?>
                                    </td>
                                    <td>
                                        <?php echo $ldata['inclusiveDateStart'];
                                        if ($ldata['inclusiveDateEnd'] && $ldata['inclusiveDateStart'] < $ldata['inclusiveDateEnd']) {
                                            echo ' to ' . $ldata['inclusiveDateEnd'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strtolower($ldata['status']) == "submitted") {
                                            echo "Pending";
                                        } else {
                                            echo $ldata['status'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <form action="<?php echo $action_delete_leaveappform; ?>" method="post">
                                            <a
                                                href="<?php echo $location_staff_departments_employee_leaveappform_view . '/' . $ldata['leaveappform_id'] . '/'; ?>">
                                                <button type="button" class="custom-regular-button">
                                                    View
                                                </button>
                                            </a>
                                            <?php if ($allow_op) { ?>
                                                <input type="hidden" name="empId" value="<?php echo $empId; ?>" />
                                                <input type="hidden" name="recordId"
                                                    value="<?php echo $ldata['leaveappform_id']; ?>" />
                                                <input type="submit" name="deleteLeaveAppForm" value="Delete"
                                                    class="custom-regular-button" />
                                            <?php } ?>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Data Table Configuration -->
    <script>
        let table = new DataTable('#leaveAppList', {
            pagingType: 'full_numbers',
            scrollCollapse: true,
            scrollY: '100%',
            scrollX: true,
            // 'select': {
            //     'style': 'multi',
            // },
            // ordering: false,
            columnDefs: [
                // {
                //     'targets': 0,
                //     'orderable': false,
                //     'checkboxes': {
                //         'selectRow': true,
                //         // 'page': 'current',
                //     }
                // },
                {
                    'targets': -1,
                    'orderable': false,
                    // 'checkboxes': {
                    //     'selectRow': true,
                    //     // 'page': 'current',
                    // }
                },
                // {
                //     targets: [0],
                //     orderData: [0, 1]
                // },
                // {
                //     targets: [1],
                //     orderData: [1, 0]
                // },
                // {
                //     targets: [4],
                //     orderData: [4, 0]
                // }
            ],
            search: {
                return: true
            },
            "dom": 'Blfrtip',
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],
            // "colReorder": true,
            "buttons": [{
                extend: 'copy',
                exportOptions: {
                    columns: ':visible:not(:eq(-1))',
                }
            },
            {
                extend: 'excel',
                title: '<?php echo $fullName . ' - Leave Application Record List' ?>',
                filename: '<?php echo $fullName . ' - Leave Application Record List' ?>',
                exportOptions: {
                    columns: ':visible:not(:eq(-1))',
                }
            },
            {
                extend: 'csv',
                title: '<?php echo $fullName . ' - Leave Application Record List' ?>',
                filename: '<?php echo $fullName . ' - Leave Application Record List' ?>',
                exportOptions: {
                    columns: ':visible:not(:eq(-1))',
                }
            },
            {
                extend: 'pdf',
                title: '<?php echo $fullName . ' - Leave Application Record List' ?>',
                filename: '<?php echo $fullName . ' - Leave Application Record List' ?>',
                exportOptions: {
                    columns: ':visible:not(:eq(-1))',
                }
            },
            {
                extend: 'print',
                title: '<?php echo $fullName . ' - Leave Application Record List' ?>',
                filename: '<?php echo $fullName . ' - Leave Application Record List' ?>',
                message: 'Produced and Prepared by the Human Resources System',
                exportOptions: {
                    columns: ':visible:not(:eq(-1))',
                }
            },
            {
                "extend": "colvis",
                text: 'Column Visibility',
                columns: ':first,:gt(0),:last'
            }
            ],
            // responsive: true,
        });
    </script>

    <div class="component-container">
        <?php
        include ($components_file_footer);
        ?>
    </div>

    <?php include ($components_file_toastify); ?>

</body>

</html>