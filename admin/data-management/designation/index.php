<?php
include ("../../../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
include ($constants_variables);

$designations = [];

$designations = getAllDesignations();

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

    <!-- <script src="<?php
    // echo $assets_tailwind; 
    ?>"></script> -->
</head>

<body class="webpage-background-cover-admin">
    <div>
        <?php include ($components_file_topnav) ?>
    </div>

    <!-- Add Modal -->
    <form action="<?php echo $action_add_designation; ?>" method="post" class="modal fade" id="addDesignation"
        tabindex="-1" role="dialog" aria-labelledby="addDesignationTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDesignationModalLongTitle">Create New Designation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-floating mb-2">
                        <input type="text" name="DesignationName" class="form-control" id="floatingDesignationName"
                            placeholder="Enter Designation Name ..." required>
                        <label for="floatingDesignationName">Name <span class="required-color">*</span></label>
                    </div>

                    <div class="form-floating mb-2">
                        <input type="text" name="DesignationDescription" class="form-control"
                            id="floatingDesignationDescription" placeholder="Enter Description ..." required>
                        <label for="floatingDesignationDescription">Description <span
                                class="required-color">*</span></label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" name="addDesignation" value="Add Designation" class="btn btn-primary" />
                </div>
            </div>
        </div>
    </form>

    <!-- Edit Modal -->
    <form action="<?php echo $action_edit_designation; ?>" method="post" class="modal fade" id="editDesignation"
        tabindex="-1" role="dialog" aria-labelledby="editDesignationTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDesignationModalLongTitle">Modify Designation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="designationId" id="floatingEditDesignationId" />
                    <div class="form-floating mb-2">
                        <input type="text" name="designationName" class="form-control" id="floatingEditDesignationName"
                            placeholder="Enter DesignationName" required>
                        <label for="floatingEditDesignationName">Designation Name <span
                                class="required-color">*</span></label>
                    </div>

                    <div class="form-floating mb-2">
                        <input type="text" name="designationDescription" class="form-control"
                            id="floatingEditDesignationDescription" placeholder="Enter to Edit Description ..."
                            required>
                        <label for="floatingEditDesignationDescription">Description <span
                                class="required-color">*</span></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="resetEditDesignationInputs">Reset</button>
                    <input type="submit" name="editDesignation" value="Save Changes" class="btn btn-primary" />
                </div>
            </div>
        </div>
    </form>

    <div class="page-container">
        <div class="page-content">

            <div class="box-container">
                <div>
                    <a href="<?php echo $location_admin_datamanagement; ?>"><button
                            class="custom-regular-button">Back</button></a>
                    <div class="title-text">List of Designations</div>
                </div>

                <form method="POST" action="<?php echo $action_delete_designation; ?>">
                    <div class="button-container mb-2">
                        <!-- Add Button Modal -->
                        <button type="button" class="custom-regular-button" data-toggle="modal"
                            data-target="#addDesignation">
                            Add Designation
                        </button>
                    </div>

                    <table id="designations" class="text-center hover table-striped cell-border order-column"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Designation Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($designations)) {
                                foreach ($designations as $row) {
                                    ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="designationId"
                                        value="<?php echo $row['designation_id']; ?>" />
                                </td>
                                <td title="<?php echo $row['designationDescription']; ?>">
                                    <?php echo $row['designationName']; ?>
                                </td>
                                <td>
                                    <form method="POST" action="<?php echo $action_delete_designation; ?>">
                                        <button type="button" class="custom-regular-button editDesignationButton"
                                            data-toggle="modal" data-target="#editDesignation"
                                            data-designation-id="<?php echo $row['designation_id']; ?>"
                                            data-designation-name="<?php echo $row['designationName']; ?>"
                                            data-designation-description="<?php echo $row['designationDescription']; ?>">
                                            Edit
                                        </button>

                                        <input type="hidden" name="designationId"
                                            value="<?php echo $row['designation_id']; ?>" />
                                        <input type="submit" name="deleteDesignation" value="Delete"
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

                </form>

                <!-- Data Table Configuration -->

                <script>
                let table = new DataTable('#designations', {
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
                                columns: ':visible:not(:eq(0)):not(:eq(-1))',
                            }
                        },
                        {
                            extend: 'excel',
                            title: 'List of Designation',
                            filename: 'List of Designation',
                            exportOptions: {
                                columns: ':visible:not(:eq(0)):not(:eq(-1))',
                            }
                        },
                        {
                            extend: 'csv',
                            title: 'List of Designation',
                            filename: 'List of Designation',
                            exportOptions: {
                                columns: ':visible:not(:eq(0)):not(:eq(-1))',
                            }
                        },
                        {
                            extend: 'pdf',
                            title: 'List of Designation',
                            filename: 'List of Designation',
                            message: 'Produced and Prepared by the Human Resources System',
                            exportOptions: {
                                columns: ':visible:not(:eq(0)):not(:eq(-1))',
                            }
                        },
                        {
                            extend: 'print',
                            title: 'List of Designation',
                            filename: 'List of Designation',
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

    <script src="<?php echo $assets_designationlist_js; ?>"></script>

    <div>
        <?php
        include ($components_file_footer);
        ?>
    </div>

    <?php include ($components_file_toastify); ?>

</body>

</html>