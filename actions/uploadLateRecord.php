<?php
include ("../constants/routes.php");
include ($constants_file_dbconnect);
include ($constants_file_session_admin);
include ($constants_variables);

$upload_directory = "../files/upload/laterecords/";

if (isset($_POST['upload'])) {
    // Check if monthYearName is provided
    if (empty($_POST['monthYearName'])) {
        $_SESSION['alert_message'] = "Month and Year Name are not provided!";
        $_SESSION['alert_type'] = $warning_color;
    } else {
        $monthYearName = $_POST['monthYearName'];
        $currentMonthYear = date('F Y'); // Current month and year
        $selectedMonthYear = date_create_from_format('F Y', $monthYearName);

        // Check if the selected monthYear is ahead of the current month
        if ($selectedMonthYear > new DateTime()) {
            $_SESSION['alert_message'] = "Selected Month and Year is ahead of the current month!";
            $_SESSION['alert_type'] = $warning_color;
        } else {
            // Check if file is uploaded
            if (!isset($_FILES['file'])) {
                $_SESSION['alert_message'] = "File not uploaded!";
                $_SESSION['alert_type'] = $warning_color;
            } else {
                $fileName = $_FILES['file']['name'];
                $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

                if ($file_ext != 'csv') {
                    $_SESSION['alert_message'] = "Invalid file type. Please upload a CSV file.";
                    $_SESSION['alert_type'] = $warning_color;
                } else {
                    $inputFileNamePath = $_FILES['file']['tmp_name'];

                    try {
                        $file = new SplFileObject($inputFileNamePath);

                        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::READ_AHEAD);

                        // $newFileName = pathinfo($fileName, PATHINFO_FILENAME) . '-' . trim($monthYearName) . '-' . date('Y-m-d-His') . '.' . $file_ext;
                        $newFileName = trim($monthYearName) . '-' . date('Y-m-d-His') . '.' . $file_ext;

                        move_uploaded_file($inputFileNamePath, $upload_directory . $newFileName);

                        $totalEmp = 0;
                        $errors = 0;
                        $noupdate = 0;
                        $notExist = 0;
                        $noInitialRecord = 0;
                        $update = 0;
                        $success = 0;
                        $failed = 0;
                        $uploadSuccess = 0;

                        foreach ($file as $key => $csv) {
                            if ($key == 0) {
                                continue; // Skips csv header
                            }

                            $employee_id = sanitizeInput(trim($csv[0])); // First column of csv
                            $total_minutes = sanitizeInput(trim($csv[1])); // Second column of csv

                            if (!empty($employee_id)) {
                                $totalEmp++;
                                $type = 'Late';

                                $total_minutes = (int) $total_minutes;
                                $formatted_duration = getFormattedDurationInMinutes($total_minutes);
                                $days = $formatted_duration['days'];
                                $hours = $formatted_duration['hours'];
                                $minutes = $formatted_duration['minutes'];

                                $period_start = date("Y-m-01", strtotime($monthYearName));
                                $period_end = date("Y-m-t", strtotime($monthYearName));

                                $date_of_action = date('Y-m-d');

                                // Check if the employee exists
                                $empStatement = $database->prepare("SELECT * FROM tbl_useraccounts WHERE employee_id = ? AND UPPER(archive) != 'DELETED'");
                                $empStatement->bind_param("s", $employee_id);
                                $empStatement->execute();
                                $empResult = $empStatement->get_result();

                                if ($empResult->num_rows > 0) {
                                    // Employee exists, now check for initial record
                                    $initialRecordStatement = $database->prepare("SELECT * FROM tbl_leavedataform WHERE recordType = 'Initial Record' AND employee_id = ? AND periodEnd IS NOT NULL");
                                    $initialRecordStatement->bind_param("s", $employee_id);
                                    $initialRecordStatement->execute();
                                    $initialRecordResult = $initialRecordStatement->get_result();

                                    if ($initialRecordResult->num_rows > 0) {
                                        $initialRecord = $initialRecordResult->fetch_assoc();
                                        $initialRecordPeriodEnd = $initialRecord['periodEnd'];

                                        // Compare period start with initial record periodEnd
                                        if ((new DateTime($initialRecord['periodEnd']))->format('Y-m') === (new DateTime($period_start))->format('Y-m')) {
                                            $period_start = $initialRecordPeriodEnd;
                                            // Check if a record with particular = "Late", period = $period_start, periodEnd = $period_end, and employee_id = $employee_id exists
                                            $checkStatement = $database->prepare("SELECT * FROM tbl_leavedataform WHERE particular = ? AND period = ? AND periodEnd = ? AND employee_id = ?");
                                            $checkStatement->bind_param("ssss", $type, $period_start, $period_end, $employee_id);
                                            $checkStatement->execute();
                                            $checkResult = $checkStatement->get_result();

                                            if ($checkResult->num_rows > 0) {
                                                // If a record exists, update days, hours, and minutes
                                                $updateStatement = $database->prepare("UPDATE tbl_leavedataform SET days = ?, hours = ?, minutes = ? WHERE particular = ? AND period = ? AND periodEnd = ? AND employee_id = ?");
                                                $updateStatement->bind_param("iiissss", $days, $hours, $minutes, $type, $period_start, $period_end, $employee_id);
                                                $updateStatement->execute();

                                                // Check if any rows were affected by the update
                                                if ($updateStatement->affected_rows > 0) {
                                                    // Update successful
                                                    $update++;
                                                } else {
                                                    // No rows were updated (possibly because the values are the same)
                                                    $noupdate++;
                                                }
                                            } else {
                                                // If no record exists, insert a new record
                                                $insertStatement = $database->prepare("INSERT INTO tbl_leavedataform (employee_id, period, periodEnd, particular, dateOfAction, days, hours, minutes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                                                $insertStatement->bind_param("sssssiii", $employee_id, $period_start, $period_end, $type, $date_of_action, $days, $hours, $minutes);
                                                $insertStatement->execute();

                                                // Check if the insert was successful
                                                if ($insertStatement->affected_rows > 0) {
                                                    // Insert successful
                                                    $success++;
                                                } else {
                                                    $failed++;
                                                }
                                            }
                                        } else if ($period_start >= $initialRecordPeriodEnd) {
                                            // Check if a record with particular = "Late", period = $period_start, periodEnd = $period_end, and employee_id = $employee_id exists
                                            $checkStatement = $database->prepare("SELECT * FROM tbl_leavedataform WHERE particular = ? AND period = ? AND periodEnd = ? AND employee_id = ?");
                                            $checkStatement->bind_param("ssss", $type, $period_start, $period_end, $employee_id);
                                            $checkStatement->execute();
                                            $checkResult = $checkStatement->get_result();

                                            if ($checkResult->num_rows > 0) {
                                                // If a record exists, update days, hours, and minutes
                                                $updateStatement = $database->prepare("UPDATE tbl_leavedataform SET days = ?, hours = ?, minutes = ? WHERE particular = ? AND period = ? AND periodEnd = ? AND employee_id = ?");
                                                $updateStatement->bind_param("iiissss", $days, $hours, $minutes, $type, $period_start, $period_end, $employee_id);
                                                $updateStatement->execute();

                                                // Check if any rows were affected by the update
                                                if ($updateStatement->affected_rows > 0) {
                                                    // Update successful
                                                    $update++;
                                                } else {
                                                    // No rows were updated (possibly because the values are the same)
                                                    $noupdate++;
                                                }
                                            } else {
                                                // If no record exists, insert a new record
                                                $insertStatement = $database->prepare("INSERT INTO tbl_leavedataform (employee_id, period, periodEnd, particular, dateOfAction, days, hours, minutes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                                                $insertStatement->bind_param("sssssiii", $employee_id, $period_start, $period_end, $type, $date_of_action, $days, $hours, $minutes);
                                                $insertStatement->execute();

                                                // Check if the insert was successful
                                                if ($insertStatement->affected_rows > 0) {
                                                    // Insert successful
                                                    $success++;
                                                } else {
                                                    $failed++;
                                                }
                                            }
                                        } else {
                                            // The employee initial record is in range of date
                                            $noupdate++;
                                        }
                                    } else {
                                        // No initial record found
                                        $noInitialRecord++;
                                    }
                                } else {
                                    // Employee does not exist
                                    $notExist++;
                                }

                                // // Echo the result
                                // echo "Total Minutes: $total_minutes<br>";
                                // echo "Employee ID: $employee_id<br>";
                                // echo "Days: $days, Hours: $hours, Minutes: $minutes<br>";
                                // echo "Date of Action: $date_of_action<br>";
                                // echo "Type: $type<br><br>";
                            }
                        }

                        $fileOfRecord = "files/upload/laterecords/" . $newFileName;

                        // Check if the monthYearOfRecord already exists
                        $checkStatement = $database->prepare("SELECT COUNT(*) FROM tbl_laterecordfile WHERE monthYearOfRecord = ?");
                        $checkStatement->bind_param("s", $monthYearName);
                        $checkStatement->execute();
                        $checkStatement->bind_result($count);
                        $checkStatement->fetch();
                        $checkStatement->close();

                        if ($count == 0) {
                            // Insert new record
                            $insertStatement = $database->prepare("INSERT INTO tbl_laterecordfile (monthYearOfRecord, fileOfRecord) VALUES (?, ?)");
                            $insertStatement->bind_param("ss", $monthYearName, $fileOfRecord);
                            $insertStatement->execute();

                            if ($insertStatement->affected_rows > 0) {
                                $uploadSuccess++;
                            } else {
                                $uploadSuccess--;
                            }
                            $insertStatement->close();
                        } else {
                            // Update existing record
                            $updateStatement = $database->prepare("UPDATE tbl_laterecordfile SET fileOfRecord = ? WHERE monthYearOfRecord = ?");
                            $updateStatement->bind_param("ss", $fileOfRecord, $monthYearName);
                            $updateStatement->execute();

                            if ($updateStatement->affected_rows > 0) {
                                $uploadSuccess += 2;
                            } else {
                                $uploadSuccess -= 2;
                            }
                            $updateStatement->close();
                        }

                        echo "Number of employees without initial records: $noInitialRecord<br>";
                        echo "Number of employees that do not exist: $notExist<br>";
                        echo "Total number of employees: $totalEmp<br>";
                        echo "Number of employees without updates: $noupdate<br>";
                        echo "Number of employees updated: $update<br>";
                        echo "Number of successful records: $success<br>";
                        echo "Number of failed updates: $failed<br>";
                        echo "File Uploaded: $uploadSuccess<br>";

                        if ($uploadSuccess == 1) {
                            $_SESSION['alert_message'] = "File Has Successfully Uploaded!";
                            $_SESSION['alert_type'] = "";
                        } else if ($uploadSuccess == 2) {
                            $_SESSION['alert_message'] = "File Has Successfully Updated!";
                            $_SESSION['alert_type'] = "";
                        }else if ($uploadSuccess == -1) {
                            $_SESSION['alert_message'] = "File Does Not Uploaded!";
                            $_SESSION['alert_type'] = $error_color;
                        }else if ($uploadSuccess == -2) {
                            $_SESSION['alert_message'] = "File Has Failed to Update!";
                            $_SESSION['alert_type'] = $error_color;
                        }else{

                        }
                    } catch (Exception $exception) {
                        $_SESSION['alert_message'] = $exception->getMessage();
                        $_SESSION['alert_type'] = $error_color;
                    }
                }
            }
        }
    }

    header("Location: " . $location_admin_datamanagement_laterecords);
    exit();
} else {
    header("Location: " . $location_admin_datamanagement_laterecords);
    exit();
}

function getFormattedDurationInMinutes(int $totalMinutes): array
{
    $days = floor($totalMinutes / 1440); // 1440 minutes in a day
    $hours = floor(($totalMinutes % 1440) / 60); // Remaining minutes divided by 60 to get hours
    $minutes = $totalMinutes % 60; // Remaining minutes after accounting for days and hours

    return [
        'days' => $days,
        'hours' => $hours,
        'minutes' => $minutes,
    ];
}
?>