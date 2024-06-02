<?php
include ("../../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
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
    <script src="<?php echo $assets_departmentlist_js; ?>"></script>

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

                <!-- Add Modal -->
                <form action="<?php echo $action_add_department; ?>" method="post" class="modal fade" id="addDepartment"
                    tabindex="-1" role="dialog" aria-labelledby="addDepartmentTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addDepartmentModalLongTitle">Create New Department</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-2">
                                    <input type="text" name="departmentName" class="form-control"
                                        id="floatingDepartmentName" placeholder="Department of Human Resources"
                                        required>
                                    <label for="floatingDepartmentName">Department Name <span
                                            class="required-color">*</span></label>
                                </div>

                                <div class="form-floating mb-2">
                                    <input type="text" name="departmentDescription" class="form-control"
                                        id="floatingDepartmentDescription" placeholder="Enter Description ...">
                                    <label for="floatingDepartmentDescription">Description</label>
                                </div>

                                <div class="form-floating">
                                    <select name="departmentHead" class="form-select" id="floatingDepartmentHead"
                                        aria-label="Floating Department Head Selection">
                                        <option value="" selected></option>
                                        <?php
                                        if (!empty($employeesNameAndId)) {
                                            foreach ($employeesNameAndId as $employee) {
                                                ?>
                                                <option value="<?php echo $employee['employee_id']; ?>">
                                                    <?php echo organizeFullName($employee['firstName'], $employee['middleName'], $employee['lastName'], $employee['suffix'], 2); ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="floatingDepartmentHead">Department Head</label>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <input type="submit" name="addDepartment" value="Add Department"
                                    class="btn btn-primary" />
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Edit Modal -->
                <form action="<?php echo $action_edit_department; ?>" method="post" class="modal fade"
                    id="editDepartment" tabindex="-1" role="dialog" aria-labelledby="editDepartmentTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editDepartmentModalLongTitle">Modify Department</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="departmentId" id="floatingEditDepartmentId" />
                                <div class="form-floating mb-2">
                                    <input type="text" name="departmentName" class="form-control"
                                        id="floatingEditDepartmentName" placeholder="Department of Human Resources"
                                        required>
                                    <label for="floatingEditDepartmentName">Department Name <span
                                            class="required-color">*</span></label>
                                </div>

                                <div class="form-floating mb-2">
                                    <input type="text" name="departmentDescription" class="form-control"
                                        id="floatingEditDepartmentDescription"
                                        placeholder="Enter to Edit Description ...">
                                    <label for="floatingEditDepartmentDescription">Description</label>
                                </div>

                                <div class="form-floating">
                                    <select name="departmentHead" class="form-select" id="floatingEditDepartmentHead"
                                        aria-label="Floating Department Head Selection">
                                        <option value="" selected></option>
                                        <?php
                                        if (!empty($employeesNameAndId)) {
                                            foreach ($employeesNameAndId as $employee) {
                                                ?>
                                                <option value="<?php echo $employee['employee_id']; ?>">
                                                    <?php echo organizeFullName($employee['firstName'], $employee['middleName'], $employee['lastName'], $employee['suffix'], 2); ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="floatingEditDepartmentHead">Department Head</label>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary"
                                    id="resetEditDepartmentInputs">Reset</button>
                                <input type="submit" name="editDepartment" value="Save Changes"
                                    class="btn btn-primary" />
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Delete Modal -->
                <form action="<?php echo $action_delete_department; ?>" method="post" class="modal fade"
                    id="deleteDepartment" tabindex="-1" role="dialog" aria-labelledby="deleteDepartmentTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteDepartmentModalLongTitle">Confirm Department Removal
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="deptId" id="floatingDeleteDeptId" />
                                <input type="hidden" name="deptHead" id="floatingDeleteDeptHead" />
                                <input type="hidden" name="deptDescription" id="floatingDeleteDeptDescription" />

                                <div class="text-center alert alert-warning" role="alert">
                                    Are you sure you want to remove <span class="font-weight-bold text-uppercase" id="floatingDeleteDeptName"></span> in the
                                    list? It has <span class="font-weight-bold text-uppercase" id="floatingDeleteDeptCount"></span> employee(s) under it. Upon
                                    Delete, employees will be unassigned.
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <input type="submit" name="deleteDepartment" value="Yes" class="btn btn-primary" />
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="button-container mb-2">

                    <!-- Add Button Modal -->
                    <button type="button" class="custom-regular-button" data-toggle="modal"
                        data-target="#addDepartment">
                        Add New Department
                    </button>

                </div>

                <div class="item-detail-container mb-2">
                    <a href="<?php echo $location_admin_departments_office; ?>" class="item-detail-container-summary">
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
                                    <!-- Edit Department Modal -->
                                    <button type="button" class="custom-regular-button editDepartmentButton" data-toggle="modal"
                                        data-target="#editDepartment"
                                        data-department-id="<?php echo $department['department_id']; ?>"
                                        data-department-name="<?php echo $department['departmentName']; ?>"
                                        data-department-description="<?php echo $department['departmentDescription']; ?>"
                                        data-department-head="<?php echo $department['departmentHead']; ?>">
                                        Edit
                                    </button>
                                    <a
                                        href="<?php echo $location_admin_departments_office . '/' . $department['department_id'] . '/'; ?>">
                                        <button class="custom-regular-button text-truncate">View</button>
                                    </a>

                                    <?php if ($department['departmentCount'] > 0) { ?>
                                        <!-- Delete Department Modal -->
                                        <button type="button" class="custom-regular-button deleteDepartmentButton"
                                            data-toggle="modal" data-target="#deleteDepartment"
                                            data-dept-id="<?php echo $department['department_id']; ?>"
                                            data-dept-name="<?php echo $department['departmentName']; ?>"
                                            data-dept-description="<?php echo $department['departmentDescription']; ?>"
                                            data-dept-count="<?php echo $department['departmentCount']; ?>"
                                            data-dept-head="<?php echo $department['departmentHead']; ?>">
                                            Delete
                                        </button>
                                    <?php } else { ?>
                                        <form action="<?php echo $action_delete_department; ?>" method="post">
                                            <input type="hidden" name="deptId"
                                                value="<?php echo $department['department_id']; ?>" />
                                            <input type="submit" name="deleteDepartment" value="Delete"
                                                class="custom-regular-button" />
                                        </form>
                                    <?php } ?>
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
                    <a href="<?php echo $location_admin_departments_office . '/pending/'; ?>"
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