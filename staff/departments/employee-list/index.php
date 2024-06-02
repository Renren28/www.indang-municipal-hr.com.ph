<?php
include ("../../../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_staff);
include ($constants_variables);

$departmentlabel = "";
$departmentName = "";
$departments = getAllDepartments();

$generatedEmpId = bin2hex(random_bytes(4));

if ($_GET['departmentlabel'] !== "index.php" && $_GET['departmentlabel'] !== "index.html") {
    $departmentlabel = sanitizeInput($_GET['departmentlabel']);
    $_SESSION['departmentlabel'] = $departmentlabel;
} else {
    if (isset($_SESSION['departmentlabel'])) {
        unset($_SESSION['departmentlabel']);
    }
}

if ($departmentlabel) {

    if (strcasecmp($departmentlabel, 'pending') == 0 || strcasecmp($departmentlabel, 'other') == 0 || strcasecmp($departmentlabel, 'others') == 0 || strcasecmp($departmentlabel, 'unassigned') == 0 || strcasecmp($departmentlabel, 'unassign') == 0) {
        $departmentName = "Pending and Unassigned";

        $empsql = "SELECT 
                        u.*, 
                        CASE 
                            WHEN UPPER(d.archive) = 'DELETED' THEN '' 
                            ELSE d.departmentName 
                        END AS departmentName,
                        CASE 
                            WHEN UPPER(desig.archive) = 'DELETED' THEN '' 
                            ELSE desig.designationName 
                        END AS designationName
                    FROM 
                        tbl_useraccounts u
                    LEFT JOIN 
                        tbl_departments d ON u.department = d.department_id
                    LEFT JOIN 
                        tbl_designations desig ON u.jobPosition = desig.designation_id
                    WHERE 
                        (d.department_id IS NULL OR UPPER(d.archive) = 'DELETED') 
                        AND UPPER(u.archive) != 'DELETED' AND UPPER(u.role) != 'ADMIN'
                    ORDER BY 
                        u.lastName ASC";
        $employees = $database->query($empsql);

    } else {
        for ($i = 0; $i < count($departments); $i++) {
            if ($departments[$i]['department_id'] == $departmentlabel) {
                $departmentName = $departments[$i]['departmentName'];
                break;
            }
        }

        $empsql = "SELECT
                        ua.*,
                        d.departmentName,
                        CASE 
                            WHEN UPPER(desig.archive) = 'DELETED' THEN '' 
                            ELSE desig.designationName 
                        END AS designationName
                    FROM
                        tbl_useraccounts ua
                    LEFT JOIN
                        tbl_departments d ON ua.department = d.department_id
                    LEFT JOIN
                        tbl_designations desig ON ua.jobPosition = desig.designation_id
                    WHERE
                        ua.department = ? AND UPPER(ua.archive) != 'DELETED' AND UPPER(d.archive) != 'DELETED' AND UPPER(ua.role) != 'ADMIN'
                    ORDER BY
                        ua.lastName ASC";

        $stmt = $database->prepare($empsql);
        $stmt->bind_param("s", $departmentlabel);
        $stmt->execute();

        $employees = $stmt->get_result();

    }

} else {
    $departmentName = "All Employees";
    $empsql = " SELECT
                    ua.*,
                    CASE
                        WHEN UPPER(d.archive) = 'DELETED' THEN ''
                        ELSE d.departmentName
                    END AS departmentName,
                    CASE
                        WHEN UPPER(desig.archive) = 'DELETED' THEN ''
                        ELSE desig.designationName
                    END AS designationName
                FROM
                    tbl_useraccounts ua
                LEFT JOIN
                    tbl_departments d ON ua.department = d.department_id
                LEFT JOIN
                    tbl_designations desig ON ua.jobPosition = desig.designation_id
                WHERE
                    UPPER(ua.archive) != 'DELETED' AND UPPER(ua.role) != 'ADMIN'
                ORDER BY
                    ua.lastName ASC;
                ";
    $employees = $database->query($empsql);
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
    <script src="<?php echo $assets_file_employeeListing; ?>"></script>

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
                    <a href="<?php echo $location_staff_departments; ?>"><button
                            class="custom-regular-button">Back</button></a>
                    <div class="title-text">List of Employees</div>
                    <div class="title-text-caption">
                        (
                        <?php echo $departmentName; ?>)
                    </div>
                </div>

                <form method="POST" action="<?php echo $action_delete_employee; ?>">

                    <table id="employees" class="text-center hover table-striped cell-border order-column"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Job Title</th>
                                <th>Sex</th>
                                <th>Age</th>
                                <th>Civil Status</th>
                                <th>Status</th>
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
                                            <?php
                                            if ($row['departmentName']) {
                                                echo $row['departmentName'];
                                            } else if (strcasecmp($row['department'], "Pending") == 0) {
                                                echo "Pending";
                                            } else {
                                                echo "Unassigned";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['designationName']) {
                                                echo $row['designationName'];
                                            } else {
                                                echo "Unassigned";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $row['sex']; ?>
                                        </td>
                                        <td>
                                            <?php echo identifyEmployeeAge($row['birthdate']); ?>
                                        </td>
                                        <td>
                                            <?php echo $row['civilStatus']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['status']; ?>
                                        </td>
                                        <td>
                                            <a
                                                href="<?php echo $location_staff_departments_employee . '/' . $row['employee_id'] . '/'; ?>">
                                                <button type="button" class="custom-regular-button">
                                                    View
                                                </button>
                                            </a>
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
                            {
                                targets: [<?php if ($departmentlabel != "") {
                                    echo "2,";
                                } ?>5, 7], visible: false
                            },
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
                                title: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                filename: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'csv',
                                title: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                filename: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'pdf',
                                title: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                filename: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                exportOptions: {
                                    columns: ':visible:not(:eq(0)):not(:eq(-1))',
                                }
                            },
                            {
                                extend: 'print',
                                title: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
                                filename: '<?php echo $departmentName ? $departmentName . ' - ' : ''; ?>List of Employees',
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