<?php
include("../../../../constants/routes.php");
include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_admin);
include($constants_variables);

$empId = isset($_GET['empid']) ? filter_var($_GET['empid'], FILTER_SANITIZE_STRING) : null;
$employeeData = [];
$personalData = [];
$familyData = [];
$educationalData = [];

if ($empId === 'index.php' || $empId === 'index.html' || $empId === null) {
    $empId = null;
    if (isset($_SESSION['post_empId'])) {
        unset($_SESSION['post_empId']);
    }
} else {
    $_SESSION['post_empId'] = sanitizeInput($empId);
    $employeeData = getEmployeeData($empId);
    $personalData = getEmployeePersonalInfo($empId);
    $familyData = getEmployeeFamilyBackground($empId);
    $educationalData = getEmployeeEducationalBackground($empId);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Human Resources of Municipality of Indang - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HR - Indang Municipality Admin Page">

    <!-- <script>
    function validateForm() {
        var fromYearCol = document.getElementById('floatingCollegePeriod').value;
        var toYearCol = document.getElementById('floatingCollegePeriodEnd').value;

        fromYearCol = parseInt(fromYearCol, 10);
        toYearCol = parseInt(toYearCol, 10);

        var errorMessage = document.getElementById('error-message');

        if (toYearCol <= fromYearCol) {
            errorMessage.textContent = "Should be greater than Period of Attendance From";
            return false;
        } else {
            errorMessage.textContent = "";
        }

        return true;
    }
    </script> -->

    <?php
    include($constants_file_html_credits);
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
        <?php include($components_file_topnav); ?>
    </div>

    <div class="page-container">
        <div class="page-content">

            <div>
                <?php include($components_file_navpanel); ?>
            </div>

            <div class="box-container">
                <h3 class="title-text">Account Profile Information</h3>

                <div class="account-profile-container print-form-container">
                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Employee ID:</span>
                        <span class="account-profile-context">
                            <?php echo $employeeData['employee_id']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Account Role:</span>
                        <span class="account-profile-context">
                            <?php echo $employeeData['role']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Account Status:</span>
                        <span class="account-profile-context">
                            <?php echo $employeeData['status']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Email:</span>
                        <span class="account-profile-context">
                            <?php echo $employeeData['email']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">First Name:</span>
                        <span class="account-profile-context">
                            <?php echo $employeeData['firstName']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Middle Name:</span>
                        <span class="account-profile-context">
                            <?php echo $employeeData['middleName']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Last Name:</span>
                        <span class="account-profile-context">
                            <?php echo $employeeData['lastName']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Suffix:</span>
                        <span class="account-profile-context">
                            <?php echo $employeeData['suffix']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Age:</span>
                        <span class="account-profile-context">
                            <?php echo identifyEmployeeAge($employeeData['birthdate']); ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Sex:</span>
                        <span class="account-profile-context">
                            <?php echo $employeeData['sex']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Civil Status:</span>
                        <span class="account-profile-context">
                            <?php echo $employeeData['civilStatus']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Birthday:</span>
                        <span class="account-profile-context">
                            <?php
                            if (empty($employeeData['birthdate']) || $employeeData['birthdate'] == '0000-00-00') {
                                echo 'Not Specified';
                            } else {
                                echo convertDateFormat($employeeData['birthdate'], "Y-m-d", "m-d-Y");
                            }
                            ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Department:</span>
                        <span class="account-profile-context">
                            <?php
                            if ($employeeData['departmentName']) {
                                echo $employeeData['departmentName'];
                            } else if ($employeeData['department']) {
                                echo $employeeData['department'];
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Job Position:</span>
                        <span class="account-profile-context">
                            <?php echo $employeeData['designationName']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Date Started:</span>
                        <span class="account-profile-context">
                            <?php echo convertDateFormat($employeeData['dateStarted'], "Y-m-d", "m-d-Y"); ?>
                        </span>
                    </div>

                </div>

                <div class="account-profile-container mt-5 print-form-container">
                    <details>
                        <summary class="form-floating mb-2 title-text-caption-small">
                            Click for Personal Information
                        </summary>
                        <div class="form-group mt-2">
                            <div>
                                <h3 class="title-text">Personal Information</h3>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Place of Birth: </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['birthplace']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Height (m): </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['height']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Weight (kg): </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['weight']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Blood Type: </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['bloodtype']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">GSIS ID No. : </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['gsis']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">PAG-IBIG ID No. : </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['pagibig']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">PHILHEALTH No. : </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['philhealth']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">SSS No. : </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['sss']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">TIN No. : </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['tin']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Agency Employee No. : </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['agency']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Citizenship: </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['citizenship']; ?>
                                </span>
                            </div>
                            <div class="font-italic text-center">
                                Permanent Address
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">House/Block/Lot No. : </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['houseNo']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Street: </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['street']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Subdivision/Village : </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['subdivision']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Barangay : </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['barangay']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">City/Municipality : </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['city']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Province : </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['province']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Zip Code: </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['zipCode']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Telephone No. : </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['telephone']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Mobile No. : </span>
                                <span class="account-profile-context">
                                    <?php echo $personalData['mobile']; ?>
                                </span>
                            </div>
                        </div>
                    </details>
                </div>

                <div class="account-profile-container print-form-container">
                    <details>
                        <summary class="form-floating mb-2 title-text-caption-small">
                            Click for Family Background
                        </summary>
                        <div class="form-group mt-2">
                            <div>
                                <h3 class="title-text">Family Background</h3>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Spouse's Surname: </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['spousesurname']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Spouse's First Name: </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['spousename']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Spouse's Middle Name: </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['spousemiddlename']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Name Extension (Jr, Sr): </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['spousenameExtension']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Spouse's Occupation : </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['spouseOccupation']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Employer/Business Name : </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['spouseEmployer']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Business Address : </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['spouseBusinessAddress']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Telephone No. : </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['spouseTelephone']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Name of Children : </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['nameOfChildren']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Father's Surname : </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['fathersSurname']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">First Name: </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['fathersFirstname']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Middle Name: </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['fathersMiddlename']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Name Extension (Jr, Sr): </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['fathersnameExtension']; ?>
                                </span>
                            </div>
                            <div class="font-italic text-center">
                                Mother's Maiden Name
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Surname : </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['MSurname']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">First Name : </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['MName']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-click">Middle Name : </span>
                                <span class="account-profile-context">
                                    <?php echo $familyData['MMName']; ?>
                                </span>
                            </div>
                        </div>
                    </details>
                </div>

                <div class="account-profile-container print-form-container">
                    <details>
                        <summary class="form-floating mb-2 title-text-caption-small">
                            Click for Educational Background
                        </summary>
                        <div class="form-group mt-2">
                            <div>
                                <h3 class="title-text">Educational Background</h3>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Graduate Studies : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['graduateStudies']; ?>
                                </span>
                            </div>
                            <div class="font-italic text-center">
                                Elementary
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Name of School : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['elemschoolName']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Basic Education/Degree/Course : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['elembasicEducation']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Highest Level/Unit Earned : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['elemhighestLevel']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Year Graduated : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['elemYGraduated']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Scholarship/Academic Honors Received :
                                </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['elemScholarship']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Period of Attendance From : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['elemPeriod']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Period of Attendance To : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['elemperiodEnd']; ?>
                                </span>
                            </div>
                            <div class="font-italic text-center">
                                Secondary
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Name of School : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['secondschoolName']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Basic Education/Degree/Course : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['secondbasicEducation']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Highest Level/Unit Earned : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['secondhighestLevel']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Year Graduated : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['secondYGraduated']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Scholarship/Academic Honors Received :
                                </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['secondScholarship']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Period of Attendance From : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['secondPeriod']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Period of Attendance To : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['secondperiodEnd']; ?>
                                </span>
                            </div>
                            <div class="font-italic text-center">
                                Vocational / Trade Course
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Name of School : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['vocationalschoolName']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Basic Education/Degree/Course : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['vocationalbasicEducation']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Highest Level/Unit Earned : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['vocationalhighestLevel']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Year Graduated : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['vocationalYGraduated']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Scholarship/Academic Honors Received :
                                </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['vocationalScholarship']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Period of Attendance From : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['vocationalPeriod']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Period of Attendance To : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['vocationalperiodEnd']; ?>
                                </span>
                            </div>
                            <div class="font-italic text-center">
                                College
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Name of School : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['collegeschoolName']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Basic Education/Degree/Course : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['collegebasicEducation']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Highest Level/Unit Earned : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['collegehighestLevel']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Year Graduated : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['collegeYGraduated']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Scholarship/Academic Honors Received :
                                </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['collegeScholarship']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Period of Attendance From : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['collegePeriod']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-row mb-2 gap-1 align-items-center">
                                <span class="account-profile-subject-educ">Period of Attendance To : </span>
                                <span class="account-profile-context">
                                    <?php echo $educationalData['collegeperiodEnd']; ?>
                                </span>
                            </div>
                        </div>
                    </details>
                </div>

                <!--Button here for Personal Data Sheets -->


                <div style="display: flex; justify-content: flex-end; width: 100%;">
                    <button type="button" class="custom-regular-button"
                        style="white-space: nowrap; padding: 5px 10px; width: auto; font-size: 12px;"
                        data-toggle="modal" data-target="#addEmployee">
                        Update Employee Information
                    </button>
                </div>
            </div>

            <!-- Add Modal -->
            <form action="<?php echo $action_add_employeeInfo; ?>" method="post" class="modal fade" id="addEmployee"
                tabindex="-1" role="dialog" aria-labelledby="addEmployeeTitle" aria-hidden="true"
                onsubmit="return validateForm()">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addEmployeeModalLongTitle">Personal Employee Information
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="employeeId"
                                value="<?php echo $employeeData['employee_id']; ?>" />
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#personalInfo" role="tab">I.
                                        Personal
                                        Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#familyBackground" role="tab">II.
                                        Family
                                        Background</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#educationalBackground" role="tab">III.
                                        Educational
                                        Background</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- Personal Information -->
                                <div class="tab-pane active" id="personalInfo" role="tabpanel">
                                    <input type="hidden" value="<?php echo $departmentlabel; ?>"
                                        name="departmentlabel" />
                                    <div class="form-floating mb-2">
                                        <input type="text" name="birthplace" class="form-control"
                                            id="floatingBirthplace" value="<?php echo $personalData['birthplace']; ?>"
                                            placeholder="Enter place of birth">
                                        <label for="floatingBirthplace">Place of Birth <span
                                                class="required-color">*</span></label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" name="height" class="form-control" id="floatingHeight"
                                            value="<?php echo $personalData['height']; ?>"
                                            placeholder="Enter height in meters">
                                        <label for="floatingHeight">Height (m)</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" name="weight" class="form-control" id="floatingWeight"
                                            value="<?php echo $personalData['weight']; ?>"
                                            placeholder="Enter weight in kg">
                                        <label for="floatingWeight">Weight (kg)</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" name="bloodtype" class="form-control" id="floatingBloodType"
                                            value="<?php echo $personalData['bloodtype']; ?>"
                                            placeholder="Enter blood type">
                                        <label for="floatingBloodType">Blood type</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" name="gsis" class="form-control" id="floatingGSIS"
                                            value="<?php echo $personalData['gsis']; ?>"
                                            placeholder="Enter GSIS ID NO.">
                                        <label for="floatingGSIS">GSIS ID NO. <span
                                                class="required-color">*</span></label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" name="pagibig" class="form-control" id="floatingPagibig"
                                            value="<?php echo $personalData['pagibig']; ?>"
                                            placeholder="Enter PAGIBIG ID NO.">
                                        <label for="floatingPagibig">PAGIBIG ID NO. <span
                                                class="required-color">*</span></label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" name="philhealth" class="form-control"
                                            id="floatingPhilHealth" value="<?php echo $personalData['philhealth']; ?>"
                                            placeholder="Enter PHILHEALTH NO.">
                                        <label for="floatingPhilHealth">PHILHEALTH NO. <span
                                                class="required-color">*</span></label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" name="sss" class="form-control" id="floatingSSS"
                                            value="<?php echo $personalData['sss']; ?>" placeholder="Enter SSS NO.">
                                        <label for="floatingSSS">SSS NO. <span class="required-color">*</span></label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" name="tin" class="form-control" id="floatingTin"
                                            value="<?php echo $personalData['tin']; ?>" placeholder="Enter TIN NO.">
                                        <label for="floatingTin">TIN NO. <span class="required-color">*</span></label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" name="agency" class="form-control" id="floatingAgency"
                                            value="<?php echo $personalData['agency']; ?>"
                                            placeholder="Enter Agency Employee No.">
                                        <label for="floatingAgency">Agency Employee No. <span
                                                class="required-color">*</span></label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <select name="citizenship" class="form-select" id="floatingCitizenship"
                                            aria-label="Floating Citizenship Selection">
                                            <option value="Filipino" selected>Filipino</option>
                                            <option value="Dual Citizenship"
                                                <?php echo $personalData['citizenship']=='Dual Citizenship' ? 'selected' : ''; ?>>
                                                Dual Citizenship</option>
                                            <option value="by birth"
                                                <?php echo $personalData['citizenship']=='by birth' ? 'selected' : ''; ?>>
                                                by birth</option>
                                            <option value="by naturalization"
                                                <?php echo $personalData['citizenship']=='by naturalization' ? 'selected' : ''; ?>>
                                                by naturalization</option>
                                        </select>
                                        <label for="floatingCitizenship">Citizenship <span
                                                class="required-color">*</span></label>
                                    </div>

                                    <div class="container">
                                        <details>
                                            <summary class="form-floating mb-2">
                                                Residential Address
                                            </summary>
                                            <div class="form-group mt-2">
                                                <label for="houseNo">House/Block/Lot/No:</label>
                                                <input type="text" name="houseNo" class="form-control mb-2" id="houseNo"
                                                    value="<?php echo $personalData['houseNo']; ?>"
                                                    placeholder="Enter House/Block/Lot/No">
                                                <label for="street">Street:</label>
                                                <input type="text" name="street" class="form-control mb-2" id="street"
                                                    value="<?php echo $personalData['street']; ?>"
                                                    placeholder="Enter Street">
                                                <label for="subdivision">Subdivision/Village:</label>
                                                <input type="text" name="subdivision" class="form-control mb-2"
                                                    id="subdivision" value="<?php echo $personalData['subdivision']; ?>"
                                                    placeholder="Enter Subdivision/Village">
                                                <label for="barangay">Barangay:</label>
                                                <input type="text" name="barangay" class="form-control mb-2"
                                                    id="barangay" value="<?php echo $personalData['barangay']; ?>"
                                                    placeholder="Enter Subdivision/Village">
                                                <label for="city">City/Municipality:</label>
                                                <input type="text" name="city" class="form-control mb-2" id="city"
                                                    value="<?php echo $personalData['city']; ?>"
                                                    placeholder="Enter City/Municipality">
                                                <label for="province">Province:</label>
                                                <input type="text" name="province" class="form-control mb-2"
                                                    id="province" value="<?php echo $personalData['province']; ?>"
                                                    placeholder="Enter Province">
                                                <label for="zipCode">Zip Code:</label>
                                                <input type="text" name="zipCode" class="form-control mb-2" id="zipCode"
                                                    value="<?php echo $personalData['zipCode']; ?>"
                                                    placeholder="Enter Zip Code">
                                            </div>
                                        </details>
                                    </div>

                                    <div class="form-floating mb-2">
                                        <input type="number" name="telephone" class="form-control"
                                            id="floatingTelephone" value="<?php echo $personalData['telephone']; ?>"
                                            placeholder="Enter Telephone NO.">
                                        <label for="floatingTelephone">Telephone NO.</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" name="mobile" class="form-control" id="floatingMobile"
                                            value="<?php echo $personalData['mobile']; ?>"
                                            placeholder="Enter Mobile NO.">
                                        <label for="floatingMobile">Mobile NO. <span
                                                class="required-color">*</span></label>
                                    </div>
                                </div>

                                <!-- Additional Basic Information Family Background -->
                                <div class="tab-pane" id="familyBackground" role="tabpanel">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="spouseSurname" class="form-control"
                                            id="floatingSpouseSurname"
                                            value="<?php echo $familyData['spousesurname']; ?>"
                                            placeholder="Spouse Surname">
                                        <label for="floatingSpouseSurname">Spouse's Surname</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" name="spouseName" class="form-control"
                                            id="floatingSpouseName" value="<?php echo $familyData['spousename']; ?>"
                                            placeholder="Spouse Name">
                                        <label for="floatingSpouseName">Spouse's Name</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" name="spouseMiddlename" class="form-control"
                                            id="floatingSpouseMiddlename"
                                            value="<?php echo $familyData['spousemiddlename']; ?>"
                                            placeholder="Spouse Middlename">
                                        <label for="floatingSpouseMiddlename">Spouse's Middlename</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" name="spouseNameExtension" class="form-control"
                                            id="floatingSpouseNameExtension"
                                            value="<?php echo $familyData['spousenameExtension']; ?>"
                                            placeholder="Name extension (Jr, Sr)">
                                        <label for="floatingSpouseNameExtension">Name extension (Jr, Sr)
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" name="spouseOccupation" class="form-control"
                                            id="floatingSpouseOccupation"
                                            value="<?php echo $familyData['spouseOccupation']; ?>"
                                            placeholder="Spouse Occupation">
                                        <label for="floatingSpouseOccupation">Spouse's Occupation</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" name="spouseEmployer" class="form-control"
                                            id="floatingSpouseEmployer"
                                            value="<?php echo $familyData['spouseEmployer']; ?>"
                                            placeholder="Employer/Business Name">
                                        <label for="floatingSpouseEmployer">Employer/Business Name </label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" name="spouseBusinessAddress" class="form-control"
                                            id="floatingSpouseBusinessAddress"
                                            value="<?php echo $familyData['spouseBusinessAddress']; ?>"
                                            placeholder="Business Address">
                                        <label for="floatingSpouseBusinessAddress">Business Address
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" name="spouseTelephone" class="form-control"
                                            id="floatingSpouseTelephone"
                                            value="<?php echo $familyData['spouseTelephone']; ?>"
                                            placeholder="Telephone No.">
                                        <label for="floatingSpouseTelephone">Telephone No.
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" name="nameOfChildren" class="form-control"
                                            id="floatingNameOfChildren"
                                            value="<?php echo $familyData['nameOfChildren']; ?>"
                                            placeholder="Name of Children">
                                        <label for="floatingNameOfChildren">Name of Children (Write in fullname)
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" name="fathersSurname" class="form-control"
                                            id="floatingfathersSurname"
                                            value="<?php echo $familyData['fathersSurname']; ?>"
                                            placeholder="Father's Surname">
                                        <label for="floatingfathersSurname">Father's Surname <span
                                                class="required-color">*</span></label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" name="fathersFirstname" class="form-control"
                                            id="floatingfathersFirstname"
                                            value="<?php echo $familyData['fathersFirstname']; ?>"
                                            placeholder="Father's Firstname">
                                        <label for="floatingfathersFirstname">Father's Firstname <span
                                                class="required-color">*</span></label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" name="fathersMiddlename" class="form-control"
                                            id="floatingfathersMiddlename"
                                            value="<?php echo $familyData['fathersMiddleName']; ?>"
                                            placeholder="Father's Middlename">
                                        <label for="floatingfathersMiddlename">Father's Middlename <span
                                                class="required-color">*</span></label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" name="fathersnameExtension" class="form-control"
                                            id="floatingnameExtension"
                                            value="<?php echo $familyData['fathersnameExtension']; ?>"
                                            placeholder="Name Extension">
                                        <label for="floatingnameExtension">Name extension (Jr, Sr)
                                    </div>

                                    <div class="container">
                                        <details>
                                            <summary class="form-floating mb-2">
                                                Mother's Maiden Name <span class="required-color">*</span>
                                            </summary>
                                            <div class="form-group mt-2">
                                                <label for="MSurname">Surname:</label>
                                                <input type="text" name="MSurname" class="form-control mb-2"
                                                    id="MSurname" value="<?php echo $familyData['MSurname']; ?>">
                                                <label for="MName">Name:</label>
                                                <input type="text" name="MName" class="form-control mb-2" id="MName"
                                                    value="<?php echo $familyData['MName']; ?>">
                                                <label for="MMName">Middle Name:</label>
                                                <input type="text" name="MMName" class="form-control mb-2" id="MMName"
                                                    value="<?php echo $familyData['MMName']; ?>">
                                            </div>
                                        </details>
                                    </div>
                                </div>

                                <!-- Educational Background -->
                                <div class="tab-pane" id="educationalBackground" role="tabpanel">
                                    <!-- Educational Background fields go here -->
                                    <div class="form-floating mb-2">
                                        <input type="text" name="graduateStudies" class="form-control"
                                            id="floatinggraduateStudies"
                                            value="<?php echo $educationalData['graduateStudies']; ?>"
                                            placeholder="Enter Graduate Studies (optional)">
                                        <label for="floatinggraduateStudies">Graduate Studies</label>
                                    </div>

                                    <div class="container">
                                        <details>
                                            <summary class="form-floating mb-2">
                                                Elementary
                                            </summary>
                                            <div class="form-group mt-2">
                                                <label for="elemschoolName">Name of School (Write in full)</label>
                                                <input type="text" name="elemschoolName" class="form-control mb-2"
                                                    id="elemschoolName"
                                                    value="<?php echo $educationalData['elemschoolName']; ?>">
                                                <label for="elembasicEducation">BasicEducation/Degree/Course (Write
                                                    in
                                                    full)</label>
                                                <input type="text" name="elembasicEducation" class="form-control mb-2"
                                                    id="elembasicEducation"
                                                    value="<?php echo $educationalData['elembasicEducation']; ?>">
                                                <label for="elemhighestLevel">Highest Level/Units Earned (if not
                                                    graduated)</label>
                                                <input type="text" name="elemhighestLevel" class="form-control mb-2"
                                                    id="elemhighestLevel"
                                                    value="<?php echo $educationalData['elemhighestLevel']; ?>">
                                                <label for="elemYGraduated">Year Graduated</label>
                                                <input type="text" name="elemYGraduated" class="form-control mb-2"
                                                    id="elemYGraduated"
                                                    value="<?php echo $educationalData['elemYGraduated']; ?>">
                                                <label for="elemScholarship">Scholarship/Academic Honors
                                                    Received</label>
                                                <input type="text" name="elemScholarship" class="form-control mb-2"
                                                    id="elemScholarship"
                                                    value="<?php echo $educationalData['elemScholarship']; ?>">

                                                <div class="row g-2 mb-2">

                                                    <div class="col-md">
                                                        <div class="form-floating">
                                                            <input type="number" min="1924" max="3000" step="1"
                                                                name="elemPeriod" class="form-control"
                                                                id="floatingElemPeriod"
                                                                value="<?php echo $educationalData['elemPeriod']; ?>"
                                                                placeholder="2020-12-31">
                                                            <label for="floatingElemPeriod">Period of Attendance
                                                                From<span class="required-color">*</span></label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md">
                                                        <div class="form-floating">
                                                            <input type="number" min="1924" max="3000" step="1"
                                                                name="elemperiodEnd" class="form-control"
                                                                id="floatingElemPeriodEnd"
                                                                value="<?php echo $educationalData['elemperiodEnd']; ?>"
                                                                placeholder="2020-12-31">
                                                            <label for="floatingElemPeriodEnd">Period of Attendance
                                                                To<span class="required-color">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </details>
                                    </div>

                                    <div class="container">
                                        <details>
                                            <summary class="form-floating mb-2">
                                                Secondary
                                            </summary>
                                            <div class="form-group mt-2">
                                                <label for="secondschoolName">Name of School (Write in full)</label>
                                                <input type="text" name="secondschoolName" class="form-control mb-2"
                                                    id="secondschoolName"
                                                    value="<?php echo $educationalData['secondschoolName']; ?>">
                                                <label for="secondbasicEducation">BasicEducation/Degree/Course
                                                    (Write in
                                                    full)</label>
                                                <input type="text" name="secondbasicEducation" class="form-control mb-2"
                                                    id="secondbasicEducation"
                                                    value="<?php echo $educationalData['secondbasicEducation']; ?>">
                                                <label for="secondhighestLevel">Highest Level/Units Earned (if not
                                                    graduated)</label>
                                                <input type="text" name="secondhighestLevel" class="form-control mb-2"
                                                    id="secondhighestLevel"
                                                    value="<?php echo $educationalData['secondhighestLevel']; ?>">
                                                <label for="secondYGraduated">Year Graduated</label>
                                                <input type="text" name="secondYGraduated" class="form-control mb-2"
                                                    id="secondYGraduated"
                                                    value="<?php echo $educationalData['secondYGraduated']; ?>">
                                                <label for="secondScholarship">Scholarship/Academic Honors
                                                    Received</label>
                                                <input type="text" name="secondScholarship" class="form-control mb-2"
                                                    id="secondScholarship"
                                                    value="<?php echo $educationalData['secondScholarship']; ?>">

                                                <div class="row g-2 mb-2">

                                                    <div class="col-md">
                                                        <div class="form-floating">
                                                            <input type="number" min="1924" max="3000" step="1"
                                                                name="secondPeriod" class="form-control"
                                                                id="floatingSecondPeriod"
                                                                value="<?php echo $educationalData['secondPeriod']; ?>"
                                                                placeholder="2020-12-31">
                                                            <label for="floatingSecondPeriod">Period of Attendance
                                                                From<span class="required-color">*</span></label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md">
                                                        <div class="form-floating">
                                                            <input type="number" min="1924" max="3000" step="1"
                                                                name="secondperiodEnd" class="form-control"
                                                                id="floatingSecondPeriodEnd"
                                                                value="<?php echo $educationalData['secondperiodEnd']; ?>"
                                                                placeholder="2020-12-31">
                                                            <label for="floatingSecondPeriodEnd">Period of
                                                                Attendance
                                                                To<span class="required-color">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </details>
                                    </div>

                                    <div class="container">
                                        <details>
                                            <summary class="form-floating mb-2">
                                                Vocational / Trade Course
                                            </summary>
                                            <div class="form-group mt-2">
                                                <label for="vocationalschoolName">Name of School (Write in
                                                    full)</label>
                                                <input type="text" name="vocationalschoolName" class="form-control mb-2"
                                                    value="<?php echo $educationalData['vocationalschoolName']; ?>"
                                                    id="vocationalschoolName">
                                                <label for="vocationalbasicEducation">BasicEducation/Degree/Course
                                                    (Write in
                                                    full)</label>
                                                <input type="text" name="vocationalbasicEducation"
                                                    class="form-control mb-2"
                                                    value="<?php echo $educationalData['vocationalbasicEducation']; ?>"
                                                    id="vocationalbasicEducation">
                                                <label for="vocationalhighestLevel">Highest Level/Units Earned (if
                                                    not
                                                    graduated)</label>
                                                <input type="text" name="vocationalhighestLevel"
                                                    class="form-control mb-2"
                                                    value="<?php echo $educationalData['vocationalhighestLevel']; ?>"
                                                    id="vocationalhighestLevel">
                                                <label for="vocationalYGraduated">Year Graduated</label>
                                                <input type="text" name="vocationalYGraduated" class="form-control mb-2"
                                                    value="<?php echo $educationalData['vocationalYGraduated']; ?>"
                                                    id="vocationYGraduated">
                                                <label for="vocationalScholarship">Scholarship/Academic Honors
                                                    Received</label>
                                                <input type="text" name="vocationalScholarship"
                                                    class="form-control mb-2"
                                                    value="<?php echo $educationalData['vocationalScholarship']; ?>"
                                                    id="vocationalScholarship">

                                                <div class="row g-2 mb-2">

                                                    <div class="col-md">
                                                        <div class="form-floating">
                                                            <input type="number" min="1924" max="3000" step="1"
                                                                name="vocationalPeriod" class="form-control"
                                                                value="<?php echo $educationalData['vocationalPeriod']; ?>"
                                                                id="floatingVocationalPeriod" placeholder="2020-12-31">
                                                            <label for="floatingVocationalPeriod">Period of
                                                                Attendance
                                                                From<span class="required-color">*</span></label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md">
                                                        <div class="form-floating">
                                                            <input type="number" min="1924" max="3000" step="1"
                                                                name="vocationalperiodEnd" class="form-control"
                                                                value="<?php echo $educationalData['vocationalperiodEnd']; ?>"
                                                                id="floatingVocationalPeriodEnd"
                                                                placeholder="2020-12-31">
                                                            <label for="floatingVocationalPeriodEnd">Period of
                                                                Attendance
                                                                To<span class="required-color">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </details>
                                    </div>

                                    <div class="container">
                                        <details>
                                            <summary class="form-floating mb-2">
                                                College
                                            </summary>
                                            <div class="form-group mt-2">
                                                <label for="collegeschoolName">Name of School (Write in
                                                    full)</label>
                                                <input type="text" name="collegeschoolName" class="form-control mb-2"
                                                    value="<?php echo $educationalData['collegeschoolName']; ?>"
                                                    id="collegeschoolName">
                                                <label for="collegebasicEducation">BasicEducation/Degree/Course
                                                    (Write
                                                    in
                                                    full)</label>
                                                <input type="text" name="collegebasicEducation"
                                                    class="form-control mb-2"
                                                    value="<?php echo $educationalData['collegebasicEducation']; ?>"
                                                    id="collegebasicEducation">
                                                <label for="collegehighestLevel">Highest Level/Units Earned (if not
                                                    graduated)</label>
                                                <input type="text" name="collegehighestLevel" class="form-control mb-2"
                                                    value="<?php echo $educationalData['collegehighestLevel']; ?>"
                                                    id="collegehighestLevel">
                                                <label for="collegeYGraduated">Year Graduated</label>
                                                <input type="text" name="collegeYGraduated" class="form-control mb-2"
                                                    value="<?php echo $educationalData['collegeYGraduated']; ?>"
                                                    id="collegeYGraduated">
                                                <label for="collegeScholarship">Scholarship/Academic Honors
                                                    Received</label>
                                                <input type="text" name="collegeScholarship" class="form-control mb-2"
                                                    value="<?php echo $educationalData['collegeScholarship']; ?>"
                                                    id="collegeScholarship">

                                                <div class="row g-2 mb-2">
                                                    <div class="col-md">
                                                        <div class="form-floating">
                                                            <input type="number" min="1924" max="3000" step="1"
                                                                name="collegePeriod" class="form-control"
                                                                id="floatingCollegePeriod"
                                                                value="<?php echo $educationalData['collegePeriod']; ?>"
                                                                placeholder="2020-12-31">
                                                            <label for="floatingCollegePeriod">Period of Attendance
                                                                From<span class="required-color">*</span></label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md">
                                                        <div class="form-floating">
                                                            <input type="number" min="1924" max="3000" step="1"
                                                                name="collegeperiodEnd" class="form-control"
                                                                id="floatingCollegePeriodEnd"
                                                                value="<?php echo $educationalData['collegeperiodEnd']; ?>"
                                                                placeholder="2023">
                                                            <label for="floatingCollegePeriodEnd">Period of Attendance
                                                                To<span class="required-color">*</span></label>
                                                            <div id="error-message" class="error-message"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </details>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="clearAddEmployeeInputs">Clear</button>
                            <input type="submit" name="addEmployeeInfo" value="Add Employee" class="btn btn-primary" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div>
        <?php
        include($components_file_footer);
        ?>
    </div>

    <?php include($components_file_toastify); ?>

</body>

</html>