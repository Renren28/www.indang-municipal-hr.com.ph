<?php
include ("../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
include ($constants_variables);

if (isset($_POST['addEmployee'])) {
    $employeeId = sanitizeInput($_POST['employeeId'] ?? null);
    $departmentlabel = sanitizeInput($_POST['departmentlabel'] ?? '');
    $role = sanitizeInput($_POST["role"] ?? '');
    $email = sanitizeInput($_POST["email"] ?? 'default@example.com');
    $password = sanitizeInput($_POST['password'] ?? 'default_password');
    $firstName = sanitizeInput($_POST["firstName"] ?? '');
    $middleName = sanitizeInput($_POST["middleName"] ?? '');
    $lastName = sanitizeInput($_POST["lastName"] ?? '');
    $suffix = sanitizeInput($_POST["suffix"] ?? '');
    $birthdate = sanitizeInput($_POST["birthdate"] ?? '');
    $sex = sanitizeInput($_POST["sex"] ?? '');
    $civilStatus = sanitizeInput($_POST["civilStatus"] ?? '');
    $department = sanitizeInput($_POST["department"] ?? '');
    $jobPosition = sanitizeInput($_POST["jobPosition"] ?? '');
    $dateStarted = sanitizeInput($_POST["dateStarted"] ?? date('Y-m-d'));
    $accountStatus = sanitizeInput($_POST["status"] ?? '');
    $reasonForStatus = sanitizeInput($_POST["reasonForStatus"] ?? '');
    $initialVacationCredit = sanitizeInput($_POST["initialVacationCredit"] ?? 0);
    $initialSickCredit = sanitizeInput($_POST["initialSickCredit"] ?? 0);

    // Variables that are required in conditioning of Automatic Initial Record
    $previousLeaveData = [];

    $proceedCreation = false;
    $noWarning = false;
    $hasPreviousRecord = false;

    if ($birthdate >= $dateStarted || $birthdate > date('Y-m-d')) {
        $_SESSION['alert_message'] = "Invalid Birthdate!";
        $_SESSION['alert_type'] = $warning_color;
    } else if (yearDifference($birthdate, $dateStarted) < $legalAge) {
        $_SESSION['alert_message'] = "Does not meet the Legal Age!";
        $_SESSION['alert_type'] = $warning_color;
    } else if ($dateStarted > $firstDayNextMonth || $dateStarted < $minDate) {
        $_SESSION['alert_message'] = "Starting Date must be at least 1 month from now!";
        $_SESSION['alert_type'] = $warning_color;
    } else {
        $noWarning = true;
    }

    if (!$noWarning) {
        if ($departmentlabel) {
            header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
        } else {
            header("Location: " . $location_admin_departments_office);
        }
        exit();
    }

    $dataRecordType = "Initial Record";
    $initialDateStart = $dateStarted;
    $initialDateEnd = $today;
    $particularLabel = "";
    $vacationBalance = $initialVacationCredit;
    $vacationUnderWOPay = 0;
    $sickBalance = $initialSickCredit;
    $sickUnderWOPay = 0;
    $dateOfAction = $today;
    $archive = "";

    if (strtoupper($accountStatus) == "BANNED" || strtoupper($accountStatus) == "INACTIVE") {
        $archive = "deleted";
    } else {
        $reasonForStatus = "";
    }

    // if ($departmentlabel) {
    //     $_SESSION['departmentlabel'] = $departmentlabel;
    // }

    try {
        $query = "INSERT INTO tbl_useraccounts 
                  (employee_id, role, email, password, firstName, middleName, lastName, suffix, sex, civilStatus, birthdate, department, jobPosition, dateStarted, status , reasonForStatus, archive, dateCreated) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = mysqli_prepare($database, $query);
        mysqli_stmt_bind_param($stmt, "sssssssssssssssss", $employeeId, $role, $email, $password, $firstName, $middleName, $lastName, $suffix, $sex, $civilStatus, $birthdate, $department, $jobPosition, $dateStarted, $accountStatus, $reasonForStatus, $archive);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['alert_message'] = "New Employee Successfully Created";
            $_SESSION['alert_type'] = $success_color;

            // Create an Initial Record in Leave Data Form Based on Date Started

            $sqlFetchPreviousLeaveData = "SELECT * FROM tbl_leavedataform WHERE employee_id = ? AND (period <= ? OR periodEnd <= ?) AND recordType != ? ORDER BY period DESC, dateCreated DESC LIMIT 1";

            $stmtFetchPreviousLeaveData = $database->prepare($sqlFetchPreviousLeaveData);
            $stmtFetchPreviousLeaveData->bind_param("ssss", $employeeId, $initialDateStart, $initialDateEnd, $dataRecordType);
            $stmtFetchPreviousLeaveData->execute();

            $resultFetchPreviousLeaveData = $stmtFetchPreviousLeaveData->get_result();

            if ($resultFetchPreviousLeaveData->num_rows > 0) {
                $previousLeaveData = $resultFetchPreviousLeaveData->fetch_assoc();
                if ($previousLeaveData['period'] < $initialDateStart) {
                    $_SESSION['alert_message'] = "Employee Successfully Added But Initial Record Failed due to Date Started";
                    $_SESSION['alert_type'] = $warning_color;
                    $proceedCreation = false;
                } else if ($previousLeaveData['period'] >= $initialDateStart && $previousLeaveData['periodEnd'] <= $initialDateEnd) {
                    $initialDateEnd = $previousLeaveData['periodEnd'];
                    $hasPreviousRecord = true;
                    $proceedCreation = true;
                }
                // $hasPreviousRecord = true;
                // $_SESSION['alert_message'] = "Initialization is Set from ".$dateStarted." and Before: " . $previousLeaveData['period'];
                // $_SESSION['alert_type'] = $warning_color;
            } else {
                $proceedCreation = true;
            }

            if ($proceedCreation) {
                // Check if an Initial Record already exists for the specified employee and year
                $checkQuery = "SELECT * FROM tbl_leavedataform WHERE employee_id = ? AND recordType = ?";
                $checkStmt = mysqli_prepare($database, $checkQuery);
                mysqli_stmt_bind_param($checkStmt, "ss", $employeeId, $dataRecordType);
                mysqli_stmt_execute($checkStmt);
                mysqli_stmt_store_result($checkStmt);

                if (mysqli_stmt_num_rows($checkStmt) > 0) {
                    // Record already exists, perform an update
                    $updateQuery = "UPDATE tbl_leavedataform 
                        SET period = ?, periodEnd = ?, particularLabel = ?, 
                            vacationLeaveEarned = ?, vacationLeaveBalance = ?, vacationLeaveAbsUndWOP = ?,
                            sickLeaveEarned = ?, sickLeaveBalance = ?, sickLeaveAbsUndWOP = ?, dateOfAction = ? 
                        WHERE employee_id = ? AND recordType = 'Initial Record'";

                    $updateStmt = mysqli_prepare($database, $updateQuery);
                    mysqli_stmt_bind_param(
                        $updateStmt,
                        "ssddddddsss",
                        $initialDateStart,
                        $initialDateEnd,
                        $particularLabel,
                        $vacationBalance,
                        $vacationBalance,
                        $vacationUnderWOPay,
                        $sickBalance,
                        $sickBalance,
                        $sickUnderWOPay,
                        $dateOfAction,
                        $employeeId,
                    );

                    if (mysqli_stmt_execute($updateStmt)) {
                        // Update successful
                        $_SESSION['alert_message'] = "Employee and Initial Record Successfully Updated!";
                        $_SESSION['alert_type'] = $success_color;
                    } else {
                        // Update failed
                        // $_SESSION['alert_message'] = "Initialization Update Failed: " . mysqli_stmt_error($updateStmt);
                        $_SESSION['alert_message'] = "Employee Added Successfully but Initialization Update Failed!";
                        $_SESSION['alert_type'] = $warning_color;
                    }
                } else {
                    $query = "  INSERT INTO tbl_leavedataform 
                  (employee_id, dateCreated, recordType, period, periodEnd, particular, particularLabel,
                  vacationLeaveEarned, vacationLeaveBalance, vacationLeaveAbsUndWOP,
                  sickLeaveEarned, sickLeaveBalance, sickLeaveAbsUndWOP, dateOfAction) 
                VALUES (?, CURRENT_TIMESTAMP(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt = mysqli_prepare($database, $query);
                    mysqli_stmt_bind_param(
                        $stmt,
                        "ssssssdddddds",
                        $employeeId,
                        $dataRecordType,
                        $initialDateStart,
                        $initialDateEnd,
                        $dataRecordType,
                        $particularLabel,
                        $vacationBalance,
                        $vacationBalance,
                        $vacationUnderWOPay,
                        $sickBalance,
                        $sickBalance,
                        $sickUnderWOPay,
                        $dateOfAction
                    );

                    if (mysqli_stmt_execute($stmt)) {
                        $_SESSION['alert_message'] = "Employee and Initialization Successfully Created";
                        $_SESSION['alert_type'] = $success_color;
                    } else {
                        // $_SESSION['alert_message'] = "Initialization Successfully Failed: " . mysqli_stmt_error($stmt);
                        // $_SESSION['alert_type'] = $error_color;
                        $_SESSION['alert_message'] = "Employee Added Successfully but Initialization Failed!";
                        $_SESSION['alert_type'] = $warning_color;
                    }
                }
            }

            if ($proceedCreation && (strtoupper($accountStatus) == "INACTIVE" || strtoupper($accountStatus) == "BANNED")) {
                $labelStatus = "Break Monthly Record";
                $query = "INSERT INTO tbl_leavedataform 
                          (employee_id, dateCreated, recordType, period, periodEnd, particular, dateOfAction) 
                          VALUES (?, CURRENT_TIMESTAMP(), ?, ?, ?, ?, ?)";
            
                $stmt = mysqli_prepare($database, $query);
                if ($stmt) {
                    mysqli_stmt_bind_param(
                        $stmt,
                        "ssssss",
                        $employeeId,
                        $labelStatus,
                        $today,
                        $today,
                        $labelStatus,
                        $dateOfAction
                    );
            
                    if (mysqli_stmt_execute($stmt)) {
                        $_SESSION['alert_message'] = "Employee, Initialization, Inactive Record Successfully Created";
                        $_SESSION['alert_type'] = $success_color;
                    } else {
                        $_SESSION['alert_message'] = "There was an error during initialization: " . mysqli_stmt_error($stmt);
                        $_SESSION['alert_type'] = $error_color;
                    }
            
                    mysqli_stmt_close($stmt);
                } else {
                    $_SESSION['alert_message'] = "Failed to prepare the statement: " . mysqli_error($database);
                    $_SESSION['alert_type'] = $error_color;
                }
            }

            if ($departmentlabel) {
                header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
            } else {
                header("Location: " . $location_admin_departments_office);
            }

            exit();
        } else {
            $_SESSION['alert_message'] = "Error updating employee with ID $employeeId: " . mysqli_stmt_error($stmt);
            $_SESSION['alert_type'] = $error_color;
        }
    } catch (Exception $e) {
        $_SESSION['alert_message'] = "An error occurred: " . $e->getMessage();
        $_SESSION['alert_type'] = $error_color;
        header("Location: " . $_SERVER['PHP_SELF']);
        if ($departmentlabel) {
            header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
        } else {
            header("Location: " . $location_admin_departments_office);
        }
        exit();
        // throw new Exception("Database query failed: " . mysqli_error($database));
    }
    if ($departmentlabel) {
        header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
    } else {
        header("Location: " . $location_admin_departments_office);
    }
    exit();
} else {
    // echo '<script type="text/javascript">window.history.back();</script>';
    if ($departmentlabel) {
        header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
    } else {
        header("Location: " . $location_admin_departments_office);
    }
    exit();
}

?>