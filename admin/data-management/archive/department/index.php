<?php
include ("../../../../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
include ($constants_variables);

$departments = [];

$sql_department = " SELECT 
                        d.*, 
                        CASE 
                            WHEN UPPER(u.archive) = 'DELETED' THEN '' 
                            ELSE u.firstName 
                        END AS headFirstName,
                        CASE 
                            WHEN UPPER(u.archive) = 'DELETED' THEN '' 
                            ELSE u.middleName 
                        END AS headMiddleName,
                        CASE 
                            WHEN UPPER(u.archive) = 'DELETED' THEN '' 
                            ELSE u.lastName 
                        END AS headLastName,
                        CASE 
                            WHEN UPPER(u.archive) = 'DELETED' THEN '' 
                            ELSE u.suffix 
                        END AS headSuffix
                    FROM 
                        tbl_departments d
                    LEFT JOIN 
                        tbl_useraccounts u ON d.departmentHead = u.employee_id 
                    WHERE 
                        UPPER(d.archive) = 'DELETED'
                    ORDER BY 
                        d.departmentName";

$departments = $database->query($sql_department);

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
                    <div class="title-text text-truncate">Archive Department(s)</div>
                </div>

                <form method="POST" action="<?php echo $action_retrieve_department; ?>">
                    <div class="button-container mb-2">
                        <!-- Multiple Retrieve -->
                        <input type="submit" name="retrieveMultipleDepartment" id="retrieveMultipleDepartmentBTN"
                            value="Multiple Retrieve" class="custom-regular-button" />
                    </div>

                    <table id="departments" class="text-center hover table-striped cell-border order-column"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Department Name</th>
                                <th>Department Head</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($departments->num_rows > 0) {
                                while ($row = $departments->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selectedDepartment[]"
                                                value="<?php echo $row['department_id']; ?>" />
                                        </td>
                                        <td title="<?php echo $row['departmentDescription']; ?>">
                                            <?php echo $row['departmentName']; ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo organizeFullName($row['headFirstName'], $row['headMiddleName'], $row['headLastName'], $row['headSuffix'], $order = 2);
                                            ?>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <form method="POST" action="<?php echo $action_retrieve_department; ?>">
                                                    <input type="hidden" name="departmentNum"
                                                        value="<?php echo $row['department_id']; ?>" />
                                                    <input type="submit" name="retrieveDepartment" value="Retrieve"
                                                        class="custom-regular-button" />
                                                </form>
                                                <form method="POST" action="<?php echo $action_delete_department; ?>">
                                                    <input type="hidden" name="departmentId"
                                                        value="<?php echo $row['department_id']; ?>" />
                                                    <input type="submit" name="absoluteDeleteDepartment" value="Delete"
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
                    let table = new DataTable('#departments', {
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
                                title: 'List of Archived Departments',
                                filename: 'List of Archived Departments',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'csv',
                                title: 'List of Archived Departments',
                                filename: 'List of Archived Departments',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'pdf',
                                title: 'List of Archived Departments',
                                filename: 'List of Archived Departments',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'print',
                                title: 'List of Archived Departments',
                                filename: 'List of Archived Departments',
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