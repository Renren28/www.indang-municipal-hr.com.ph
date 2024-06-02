<?php
include ("../../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_staff);
include ($constants_variables);

$sql_department = "SELECT
                        d.*,
                        u.firstName AS headFirstName,
                        u.middleName AS headMiddleName,
                        u.lastName AS headLastName,
                        u.suffix AS headSuffix,
                        COUNT(CASE WHEN UPPER(u.archive) != 'DELETED' THEN u.employee_id END) AS departmentCount
                    FROM
                        tbl_departments d
                    LEFT JOIN
                        tbl_useraccounts u ON d.department_id = u.department
                    WHERE 
                        UPPER(d.archive) != 'DELETED'
                    GROUP BY
                        d.department_id
                    ORDER BY 
                        departmentName
                ";
$departments = $database->query($sql_department);

$employeesNameAndId = getAllEmployeesNameAndID();

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
    <div>
        <?php include ($components_file_topnav) ?>
    </div>

    <div class="page-container">
        <div class="page-content">
            <div class="box-container">
                <h3 class="title-text">List of Departments</h3>

                <div class="item-detail-container mb-2">
                    <a href="<?php echo $location_staff_departments_office; ?>" class="item-detail-container-summary">
                        Full Employee List
                    </a>
                </div>

                <?php
                if ($departments->num_rows > 0) {
                    while ($department = $departments->fetch_assoc()) {
                        ?>
                        <details class="item-detail-container">
                            <summary class="item-detail-container-summary">
                                <div title="<?php echo $department['departmentDescription'] ?>">
                                    <?php echo $department['departmentName']; ?>
                                </div>
                            </summary>
                            <div class="item-detail-content">
                                <div>
                                    <span class="font-weight-bold">Department Head Name: </span>
                                    <?php
                                    echo organizeFullName($department['headFirstName'], $department['headMiddleName'], $department['headLastName'], $department['headSuffix'], 1);
                                    ?>
                                </div>
                                <div>
                                    <span class="font-weight-bold">Employee Count: </span>
                                    <?php
                                    echo $department['departmentCount'];
                                    ?>
                                </div>
                                <div class="button-container m-2 justify-content-center">
                                    <a
                                        href="<?php echo $location_staff_departments_office . '/' . $department['department_id'] . '/'; ?>">
                                        <button class="custom-regular-button text-truncate">View</button>
                                    </a>
                                </div>
                            </div>
                        </details>
                        <?php
                    }
                }
                // else {
                ?>
                <!-- <div class="p-5 text-center">There are no existing departments</div> -->
                <?php
                // }
                ?>

                <div class="item-detail-container mt-2">
                    <a href="<?php echo $location_staff_departments_office . '/pending/'; ?>"
                        class="item-detail-container-summary">
                        Others / Pending / Unassigned
                    </a>
                </div>

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