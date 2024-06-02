<?php
include ("../../../../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
include ($constants_variables);

$leavedatas = [];
$sql_leavedata = "  SELECT leavedata.*, ua.firstName, ua.middleName, ua.lastName, ua.suffix
                    FROM tbl_leavedataform leavedata
                    LEFT JOIN tbl_useraccounts ua ON leavedata.employee_id = ua.employee_id
                    WHERE leavedata.archive COLLATE latin1_general_ci = 'deleted'
                    ORDER BY dateCreated ASC";
$leavedatas = $database->query($sql_leavedata);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Human Resources of Municipality of Indang - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HR - Indang Municipality Admin Page">
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
    <script src="<?php echo $assets_file_archive; ?>"></script>

    <!-- <script src="<?php
    // echo $assets_tailwind; 
    ?>"></script> -->
</head>

<body class="webpage-background-cover-admin">
    <div>
        <?php include ($components_file_topnav) ?>
    </div>

    <div class="page-container">
        <div class="page-content">

            <div class="box-container">
                <div>
                    <a href="<?php echo $location_admin_datamanagement_archive; ?>"><button
                            class="custom-regular-button">Back</button></a>
                    <div class="title-text text-truncate">Archive Leave Data(s)</div>
                </div>

                <form method="POST" action="<?php echo $action_retrieve_leaverecorddata; ?>">
                    <div class="button-container mb-2">
                        <!-- Multiple Retrieve -->
                        <input type="submit" name="retrieveMultipleLeaveData" id="retrieveMultipleLeaveDataBTN"
                            value="Multiple Retrieve" class="custom-regular-button" />
                    </div>

                    <table id="leavedatas" class="text-center hover table-striped cell-border order-column"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Name</th>
                                <th>Type of Leave</th>
                                <th>Duration</th>
                                <th>Period</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($leavedatas->num_rows > 0) {
                                while ($row = $leavedatas->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selectedLeaveData[]"
                                                value="<?php echo $row['leavedataform_id']; ?>" />
                                        </td>
                                        <td>
                                            <?php
                                            echo organizeFullName($row['firstName'], $row['middleName'], $row['lastName'], $row['suffix'], $order = 2);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['particular'] != '') {
                                                echo $row['particular'];
                                            } else {
                                                echo $row['particularLabel'];
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['days'] > 0) {
                                                echo ' ' . $row['days'] . ' day(s) ';
                                            }
                                            if ($row['hours'] > 0) {
                                                echo ' ' . $row['hours'] . ' hour(s) ';
                                            }
                                            if ($row['minutes'] > 0) {
                                                echo ' ' . $row['minutes'] . ' minute(s) ';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $row['period'];
                                            if ($row['periodEnd'] && $row['period'] < $row['periodEnd']) {
                                                echo ' to ' . $row['periodEnd'];
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <form method="POST" action="<?php echo $action_retrieve_leaverecorddata; ?>">
                                                    <input type="hidden" name="leaveDataNum"
                                                        value="<?php echo $row['leavedataform_id']; ?>" />
                                                    <input type="submit" name="retrieveLeaveData" value="Retrieve"
                                                        class="custom-regular-button" />
                                                </form>
                                                <form method="POST" action="<?php echo $action_delete_leaverecorddata; ?>">
                                                    <input type="hidden" name="leavedataformId"
                                                        value="<?php echo $row['leavedataform_id']; ?>" />
                                                    <input type="submit" name="absoluteDeleteLeaveData" value="Delete"
                                                        class="custom-regular-button" />
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>

                </form>

                <!-- Data Table Configuration -->
                <script>
                    let table = new DataTable('#leavedatas', {
                        pagingType: 'full_numbers',
                        scrollCollapse: true,
                        scrollY: '100%',
                        scrollX: true,
                        // 'select': {
                        //     'style': 'multi',
                        // },
                        // ordering: false,
                        columnDefs: [
                            // { targets: [3, 4], visible: false },
                            {
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
                        "buttons": [
                            {
                                extend: 'copy',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'excel',
                                title: 'List of Archived Leave Datas',
                                filename: 'List of Archived Leave Datas',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'csv',
                                title: 'List of Archived Leave Datas',
                                filename: 'List of Archived Leave Datas',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'pdf',
                                title: 'List of Archived Leave Datas',
                                filename: 'List of Archived Leave Datas',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'print',
                                title: 'List of Archived Leave Datas',
                                filename: 'List of Archived Leave Datas',
                                message: 'Produced and Prepared by the Human Resources System',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
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

                <!-- <button onclick="printSelectedValues()">Print Selected Values</button> -->
            </div>
        </div>
    </div>

    <div>
        <?php
        include ($components_file_footer);
        ?>
    </div>

    <?php include ($components_file_toastify); ?>

</body>

</html>