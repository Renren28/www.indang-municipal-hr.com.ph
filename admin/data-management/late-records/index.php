<?php
include ("../../../constants/routes.php");
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
include ($constants_variables);

$mostMinimalYear = $systemStartDate;

$selectedYear = $_POST['selectedYear'] ?? date("Y");

// Fetch records for the selected year
$sql = "SELECT * FROM tbl_laterecordfile WHERE monthYearOfRecord LIKE '%$selectedYear%' ORDER BY monthYearOfRecord ASC";
$result = $database->query($sql);

$records = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $records[$row['monthYearOfRecord']] = $row;
    }
}

$months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December"
];
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

    <div class="page-container">
        <div class="page-content">

            <div class="box-container">
                <div>
                    <a href="<?php echo $location_admin_datamanagement; ?>"><button
                            class="custom-regular-button">Back</button></a>
                    <div class="title-text">Employee Late Record</div>
                    <div class="title-text-caption">
                        <h6>Selected Year: <?php echo $selectedYear; ?></h6>
                    </div>
                </div>

                <form action="<?php echo $action_upload_leave_record; ?>" method="post" class="modal fade"
                    id="uploadLeaveRecord" enctype="multipart/form-data" tabindex="-1" role="dialog"
                    aria-labelledby="uploadLeaveRecordTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addEmployeeModalLongTitle">Upload Employee Late Record</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="monthYearName" id="floatingEditMonthlyLateRecord" value="" />
                                <h5 id="monthYearModalLabel" class="w-100 text-center text-uppercase mb-2">
                                </h5>
                                <div class="input-group mb-3">
                                    <input type="file" name="file" class="form-control" id="file" accept=".csv"
                                        autocomplete="off" required>
                                    <label for="file" class="input-group-text">.csv file &nbsp; <span
                                            class="required-color"> *</span></label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <input type="submit" name="upload" value="Upload" class="btn btn-primary" />
                            </div>
                        </div>
                    </div>
                </form>

                <div class="button-container component-container mb-2">
                    <form action="" method="post">
                        <label for="selectedYear">Select a Year:</label>
                        <select name="selectedYear" id="selectedYear" class="custom-regular-button"
                            aria-label="Year Selection">
                            <?php
                            $currentYear = date("Y");

                            $start_year = $mostMinimalYear ?? $currentYear;

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

                <div class="month-records">
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody><?php
                        $currentYear = date("Y");
                        $currentMonth = date("n"); // Get the current month as a number without leading zeros
                        
                        foreach ($months as $key => $month) {
                            // Get the month number from the $months array
                            $monthNumber = $key + 1;

                            // Display months up to the current month for the current year
                            if ($selectedYear == $currentYear && $monthNumber > $currentMonth) {
                                continue;
                            }

                            // Display all months for previous years
                            if ($selectedYear <= $currentYear) {
                                $monthYear = "$month $selectedYear";
                                ?>
                                    <tr>
                                        <td><?php echo $month; ?></td>
                                        <td>
                                            <?php
                                            if (isset($records[$monthYear])) {
                                                $file = "../../../".$records[$monthYear]['fileOfRecord'];
                                                if (file_exists($file)) {
                                                    ?>
                                                    <a href="<?php echo $file; ?>" download>Download</a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    Missing file
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                No record
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <button type="button" class="custom-regular-button uploadMonthlyLateRecord"
                                                data-toggle="modal" data-target="#uploadLeaveRecord"
                                                data-month-year="<?php echo $monthYear; ?>">
                                                Upload Late Record
                                            </button>
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
    </div>

    <script src="<?php echo $assets_monthlylaterecordlist_js; ?>"></script>

    <div>
        <?php
        include ($components_file_footer);
        ?>
    </div>

    <?php include ($components_file_toastify); ?>

</body>

</html>t