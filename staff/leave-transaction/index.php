<?php
include ("../../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_staff);
include ($constants_variables);

$leaveAppDataList = [];
$minStartYear = date("Y");
$minEndYear = date("Y");
$mostMinimalYear = date("Y");

try {
    // Determining Minimum Year
    $minYearQuery = "SELECT 
                        MIN(YEAR(leaveapp.inclusiveDateStart)) AS minStartYear, 
                        MIN(YEAR(leaveapp.inclusiveDateEnd)) AS minEndYear
                    FROM 
                        tbl_leaveappform leaveapp
                    LEFT JOIN 
                        tbl_useraccounts users 
                    ON 
                        users.employee_id = leaveapp.employee_id
                    WHERE 
                        UPPER(leaveapp.archive) != 'DELETED'
                        AND UPPER(users.role) = 'EMPLOYEE'";
    $minYearStatement = $database->prepare($minYearQuery);
    $minYearStatement->execute();

    $result = $minYearStatement->get_result();

    if ($result) {
        $minYears = $result->fetch_assoc();
        $minStartYear = $minYears['minStartYear'] ?? date("Y");
        $minEndYear = $minYears['minEndYear'] ?? date("Y");

        if (isset($minStartYear, $minEndYear)) {
            $mostMinimalYear = min($minStartYear, $minEndYear);
        }
    } else {
        throw new Exception("Error fetching data");
    }

    $minYearStatement->close();
} catch (Exception $e) {
    echo "<script>console.error('Error: " . $e->getMessage() . "');</script>";
}

$selectedYear = date("Y");
if (isset($_POST['leaveTransactionYear'])) {
    $selectedYear = sanitizeInput($_POST['selectedYear']);
    $_SESSION['post_transactionyear'] = $selectedYear;
} else if (isset($_SESSION['post_transactionyear'])) {
    $selectedYear = sanitizeInput($_SESSION['post_transactionyear']);
} else {
    $selectedYear = date("Y");
    if (isset($_SESSION['post_transactionyear'])) {
        unset($_SESSION['post_transactionyear']);
    }
}

if ($selectedYear && $selectedYear != 'All') {
    $leavelistsql = "SELECT 
                        leaveapp.*, 
                        users.firstName AS userFirstName, 
                        users.lastName AS userLastName 
                    FROM 
                        tbl_leaveappform leaveapp
                    LEFT JOIN 
                        tbl_useraccounts users 
                    ON 
                        users.employee_id = leaveapp.employee_id
                    WHERE 
                        (YEAR(inclusiveDateStart) = ? OR YEAR(inclusiveDateEnd) = ?) 
                        AND UPPER(leaveapp.archive) != 'DELETED'
                        AND UPPER(users.role) = 'EMPLOYEE'
                    ORDER BY 
                        dateCreated DESC
                    ";

    $leavelist_statement = $database->prepare($leavelistsql);
    $leavelist_statement->bind_param("ss", $selectedYear, $selectedYear);
    $leavelist_statement->execute();

    $result = $leavelist_statement->get_result();

    if ($result->num_rows > 0) {
        while ($leaveform = $result->fetch_assoc()) {
            $leaveAppDataList[] = $leaveform;
        }
    }

    $leavelist_statement->close();

} else if ($selectedYear == 'All') {
    $leavelistsql = "SELECT leaveapp.*,
                        users.firstName AS userFirstName,
                        users.lastName AS userLastName
                    FROM 
                        tbl_leaveappform leaveapp
                    LEFT JOIN
                        tbl_useraccounts users
                    ON
                        users.employee_id = leaveapp.employee_id
                    WHERE
                        UPPER(leaveapp.archive) != 'DELETED'
                        AND UPPER(users.role) = 'EMPLOYEE'
                    ORDER BY
                        dateCreated DESC";

    $leavelist_result = $database->query($leavelistsql);

    if ($leavelist_result->num_rows > 0) {
        while ($leaveform = $leavelist_result->fetch_assoc()) {
            $leaveAppDataList[] = $leaveform;
        }
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
        <?php include ($components_file_topnav); ?>
    </div>

    <div class="page-container">
        <div class="page-content">

            <div class="box-container">
                <h3 class="title-text">Leave Application Transaction</h3>

                <div class="button-container component-container mb-2">
                    <form action="" method="post">
                        <label for="selectedYear">Select a Year:</label>
                        <select name="selectedYear" id="selectedYear" class="custom-regular-button"
                            aria-label="Year Selection">
                            <?php
                            $currentYear = date("Y");

                            $start_year = $mostMinimalYear;

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
                        <input type="submit" name="leaveTransactionYear" value="Load Year Record"
                            class="custom-regular-button">
                    </form>
                </div>

                <table id="leaveAppList" class="text-center hover table-striped cell-border order-column"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Date</th>
                            <th>Name</th>
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
                                        <input type="checkbox" name="selectedLeaveForm[]"
                                            value="<?php echo $ldata['dateLastModified']; ?>" />
                                    </td>
                                    <td>
                                        <?php echo $ldata['dateLastModified']; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (empty($ldata['userLastName']) && empty($ldata['userFirstName'])) {
                                            echo $ldata['lastName'] . ' ' . $ldata['firstName'];
                                        } else {
                                            echo $ldata['userLastName'] . ' ' . $ldata['userFirstName'];
                                        }
                                        ?>
                                    </td>
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
                                        <form action="<?php echo $action_delete_leaveappform; ?>" method="POST">
                                            <a
                                                href="<?php echo $location_staff_leaveapplist_view . '/' . $ldata['leaveappform_id'] . '/'; ?>">
                                                <button type="button" class="custom-regular-button">
                                                    View
                                                </button>
                                            </a>
                                            <input type="hidden" name="recordId"
                                                value="<?php echo $ldata['leaveappform_id']; ?>" />
                                            <input type="submit" name="deleteLeaveAppForm" value="Delete"
                                                class="custom-regular-button" />
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
            columnDefs: [{
                'targets': 0,
                'orderable': false,
                // 'checkboxes': {
                //     'selectRow': true,
                //     // 'page': 'current',
                // }
            },
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
                title: 'List of Leave Application Form Transaction',
                filename: 'List of Leave Application Form Transaction',
                exportOptions: {
                    columns: ':visible:not(:eq(-1))',
                }
            },
            {
                extend: 'csv',
                title: 'List of Leave Application Form Transaction',
                filename: 'List of Leave Application Form Transaction',
                exportOptions: {
                    columns: ':visible:not(:eq(-1))',
                }
            },
            {
                extend: 'pdf',
                title: 'List of Leave Application Form Transaction',
                filename: 'List of Leave Application Form Transaction',
                exportOptions: {
                    columns: ':visible:not(:eq(-1))',
                }
            },
            {
                extend: 'print',
                title: 'List of Leave Application Form Transaction',
                filename: 'List of Leave Application Form Transaction',
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