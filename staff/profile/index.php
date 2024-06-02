<?php
include("../../constants/routes.php");
include($components_file_error_handler);
include($constants_file_dbconnect);
include($constants_file_session_staff);
include($constants_variables);

$employeeData = [];
$personalData = [];
$familyData = [];
$educationalData = [];

if (isset($_SESSION['employeeId'])) {
    $employeeId = sanitizeInput($_SESSION['employeeId']);
    $employeeData = getEmployeeData($employeeId);   
    $personalData = getEmployeePersonalInfo($employeeId);
    $familyData = getEmployeeFamilyBackground($employeeId);
    $educationalData = getEmployeeEducationalBackground($employeeId);
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
    <div class="component-container">
        <?php include($components_file_topnav); ?>
    </div>

    <!-- Change Password -->
    <form action="<?php echo $action_update_password; ?>" method="post" class="modal fade"
        id="changeUserProfilePassword" tabindex="-1" role="dialog" aria-labelledby="changeUserProfilePasswordTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeUserProfilePasswordModalLongTitle">Change User Password
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-floating mb-2">
                        <input type="password" name="currentPassword" class="form-control" id="floatingCurrentPassword"
                            placeholder="Password" required>
                        <label for="floatingCurrentPassword">Current Password: <span
                                class="required-color">*</span></label>
                    </div>

                    <div class="form-floating mb-2">
                        <input type="password" name="newPassword" class="form-control" id="floatingNewPassword"
                            placeholder="New Password" required>
                        <label for="floatingNewPassword">New Password: <span class="required-color">*</span></label>
                    </div>

                    <div class="form-floating mb-2">
                        <input type="password" name="confirmPassword" class="form-control" id="floatingConfirmPassword"
                            placeholder="Confirm Password" required>
                        <label for="floatingConfirmPassword">Confirm Password: <span
                                class="required-color">*</span></label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" name="changeUserProfilePassword" value="Submit" class="btn btn-primary" />
                </div>
            </div>
        </div>
    </form>

    <div class="page-container">
        <div class="page-content">

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
                        <span class="account-profile-subject">Role:</span>
                        <span class="account-profile-context">
                            <?php echo $employeeData['role']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center">
                        <span class="account-profile-subject">Email:</span>
                        <span class="account-profile-context">
                            <?php echo $employeeData['email']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-row gap-1 align-items-center text-primary">
                        <i class="fa fa-lock"></i>
                        <span class="pl-2 clickable-element text-primary" data-toggle="modal"
                            data-target="#changeUserProfilePassword">Change Password</span>
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

                <!-- Insert Modal Here -->
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

            </div>

        </div>
    </div>

    <div class="component-container">
        <?php
        include($components_file_footer);
        ?>
    </div>

    <?php include($components_file_toastify); ?>

</body>

</html>