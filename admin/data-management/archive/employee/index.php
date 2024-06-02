<?php
include ("../../../../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
include ($constants_variables);

$employees = [];
$empsql = "SELECT
                ua.*,
                CASE 
                    WHEN UPPER(d.archive) = 'DELETED' THEN '' 
                    ELSE d.departmentName 
                END AS departmentName
            FROM
                tbl_useraccounts ua
            LEFT JOIN
                tbl_departments d ON ua.department = d.department_id
            WHERE
                UPPER(ua.archive) = 'DELETED'
            ORDER BY
                ua.lastName ASC";
$employees = $database->query($empsql);

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
                    <div class="title-text text-truncate">Archive Employee(s)</div>
                </div>

                <form method="POST" action="<?php echo $action_retrieve_employee; ?>">
                    <div class="button-container mb-2">
                        <!-- Multiple Retrieve -->
                        <input type="submit" name="retrieveMultipleEmployee" id="retrieveMultipleEmployeeBTN"
                            value="Multiple Retrieve" class="custom-regular-button" />
                    </div>

                    <table id="employees" class="text-center hover table-striped cell-border order-column"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Full Name</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($employees->num_rows > 0) {
                                while ($row = $employees->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selectedEmployee[]"
                                                value="<?php echo $row['employee_id']; ?>" />
                                        </td>
                                        <td>
                                            <?php
                                            echo organizeFullName($row['firstName'], $row['middleName'], $row['lastName'], $row['suffix'], $order = 2);
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $row['departmentName']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['status']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['reasonForStatus']; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <form method="POST" action="<?php echo $action_retrieve_employee; ?>">
                                                    <input type="hidden" name="employeeNum"
                                                        value="<?php echo $row['employee_id']; ?>" />
                                                    <input type="submit" name="retrieveEmployee" value="Retrieve"
                                                        class="custom-regular-button" />
                                                </form>
                                                <form method="POST" action="<?php echo $action_delete_employee; ?>">
                                                    <input type="hidden" name="employeeNum"
                                                        value="<?php echo $row['employee_id']; ?>" />
                                                    <input type="submit" name="absoluteDeleteEmployee" value="Delete"
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
                    let table = new DataTable('#employees', {
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
                                title: 'List of Archived Employees',
                                filename: 'List of Archived Employees',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'csv',
                                title: 'List of Archived Employees',
                                filename: 'List of Archived Employees',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'pdf',
                                title: 'List of Archived Employees',
                                filename: 'List of Archived Employees',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'print',
                                title: 'List of Archived Employees',
                                filename: 'List of Archived Employees',
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