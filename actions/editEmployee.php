<?php
include ("../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
include ($constants_variables);

if (isset($_POST['editEmployee'])) {
    $oldEmployeeID = sanitizeInput($_POST['oldEmployeeId'] ?? null);
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

    $accountRole = "";
    $accountRole = getAccountRole($employeeId);
    if (strcasecmp($accountRole, "Admin") == 0) {
        $role = "Admin";
        $accountStatus = "Active";
        $reasonForStatus = "";
        $archive = "";
    }

    $noWarning = false;

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

    $archive = "";

    if (strtoupper($accountStatus) == "BANNED" || strtoupper($accountStatus) == "INACTIVE") {
        $archive = "deleted";
    } else {
        $reasonForStatus = "";
    }

    $query = "UPDATE tbl_useraccounts SET
              employee_id = ?,
              role = ?,
              email = ?,
              password = ?,
              firstName = ?,
              middleName = ?,
              lastName = ?,
              suffix = ?,
              sex = ?,
              civilStatus = ?,
              birthdate = ?,
              department = ?,
              jobPosition = ?,
              dateStarted = ?,
              status = ?,
              reasonForStatus = ?,
              archive = ?
              WHERE employee_id = ?";

    $stmt = mysqli_prepare($database, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssssssssssssss", $employeeId, $role, $email, $password, $firstName, $middleName, $lastName, $suffix, $sex, $civilStatus, $birthdate, $department, $jobPosition, $dateStarted, $accountStatus, $reasonForStatus, $archive, $oldEmployeeID);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['alert_message'] = "Employee with ID $employeeId successfully updated";
            $_SESSION['alert_type'] = $success_color;
        } else {
            $_SESSION['alert_message'] = "Error updating employee with ID $employeeId: " . mysqli_stmt_error($stmt);
            $_SESSION['alert_type'] = $error_color;
        }

        mysqli_stmt_close($stmt);

        if ((strtoupper($accountStatus) == "INACTIVE" || strtoupper($accountStatus) == "BANNED")) {
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
                    $today
                );

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['alert_message'] = "Account Successfully Update and Moved to Archive! Inactive Record Successfully Created!";
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
    } else {
        $_SESSION['alert_message'] = "Error preparing update statement: " . mysqli_error($database);
        $_SESSION['alert_type'] = $error_color;
    }

    if ($departmentlabel) {
        header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
    } else {
        header("Location: " . $location_admin_departments_office);
    }
    exit();

} else if (isset($_POST['editMultipleEmployee']) && isset($_POST['selectedEmpID'])) {
    try {
        $selectedEmpID = $_POST['selectedEmpID'];
        $departmentlabel = strip_tags(mysqli_real_escape_string($database, $_POST['departmentlabel']));
        // $role = mysqli_real_escape_string($database, strip_tags($_POST['role']));
        // $dateStarted = mysqli_real_escape_string($database, strip_tags($_POST['dateStarted']));
        // $age = mysqli_real_escape_string($database, strip_tags($_POST['age']));
        // $sex = mysqli_real_escape_string($database, strip_tags($_POST['sex']));
        // $civilStatus = mysqli_real_escape_string($database, strip_tags($_POST['civilStatus']));
        // $password = mysqli_real_escape_string($database, strip_tags($_POST['password']));
        // $department = mysqli_real_escape_string($database, strip_tags($_POST['department']));
        // $jobPosition = mysqli_real_escape_string($database, strip_tags($_POST['jobPosition']));

        // Decode the JSON string into an array
        $decodedArray = json_decode($selectedEmpID[0], true);

        $fieldsToUpdate = array('role', 'dateStarted', 'sex', 'civilStatus', 'password', 'department', 'jobPosition', 'status');

        if ($decodedArray !== null) {
            $allUpdated = true; // Flag to track if all employees are updated successfully

            foreach ($decodedArray as $value) {
                $empId = mysqli_real_escape_string($database, strip_tags($value));

                // Check if the corresponding POST field is empty, if not, update the value
                foreach ($fieldsToUpdate as $field) {
                    if (!empty($_POST[$field])) {
                        $sanitizedField = mysqli_real_escape_string($database, strip_tags($_POST[$field]));
                        $query = "UPDATE tbl_useraccounts SET $field = ? WHERE employee_id = ?";
                        $stmt = mysqli_prepare($database, $query);
                        $stmt->bind_param("ss", $sanitizedField, $empId);
                        $result = $stmt->execute();
                        if (!$result) {
                            $allUpdated = false;
                        }
                        $stmt->close();
                    }
                }

                // if (!empty($_POST['role'])) {
                //     $query = "UPDATE tbl_useraccounts SET role = ? WHERE employee_id = ?";
                //     $stmt = mysqli_prepare($database, $query);
                //     $stmt->bind_param("si", $role, $value);
                //     $result = $stmt->execute();
                //     if (!$result) {
                //         $allUpdated = false;
                //     }
                //     $stmt->close();
                // }

                if ($allUpdated) {
                    $_SESSION['alert_message'] = "All Employee Data Updated Successfully";
                    $_SESSION['alert_type'] = $success_color;
                } else {
                    $_SESSION['alert_message'] = "Error updating some employee data";
                    $_SESSION['alert_type'] = $error_color;
                }
            }

            header("Location: " . $_SERVER['PHP_SELF']);
            if ($departmentlabel) {
                header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
            } else {
                header("Location: " . $location_admin_departments_office);
            }
            exit();
        } else {
            $_SESSION['alert_message'] = "Error decoding JSON String";
            $_SESSION['alert_type'] = $error_message;
            header("Location: " . $_SERVER['PHP_SELF']);
            if ($departmentlabel) {
                header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
            } else {
                header("Location: " . $location_admin_departments_office);
            }
            exit();
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
    }

    if ($departmentlabel) {
        header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
    } else {
        header("Location: " . $location_admin_departments_office);
    }
    exit();
} else {
    if ($departmentlabel) {
        header("Location: " . $location_admin_departments_office . '/' . $departmentlabel . '/');
    } else {
        header("Location: " . $location_admin_departments_office);
    }
    exit();
}

?>