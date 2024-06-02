<?php
include ("../constants/routes.php");
// include($components_file_error_handler);
include ($constants_file_dbconnect);
include ($constants_variables);

@ob_start();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userData = [];
    $empId = null;
    if (isset($_SESSION['employeeId'])) {
        $empId = sanitizeInput($_SESSION['employeeId']);
        $userData = getEmployeeData($empId);

        if (!empty($userData)) {
            if (strcasecmp($userData['role'], "Admin") == 0) {
                // Mark the latest 5 notifications as seen
                $updateQuery = "UPDATE tbl_notifications SET status = 'unread' WHERE empIdTo = '@Admin' AND status = 'unseen' ORDER BY dateCreated DESC LIMIT 5";

                if (mysqli_query($database, $updateQuery)) {
                    echo "Latest 5 notifications marked as seen successfully!";
                } else {
                    echo "Error updating notifications: " . mysqli_error($database);
                }

                mysqli_close($database);
            } else if (strcasecmp($userData['role'], "Employee") == 0) {
                // Mark the latest 5 notifications as seen
                $updateQuery = "UPDATE tbl_notifications SET status = 'unread' WHERE empIdTo = '$empId' AND status = 'unseen' ORDER BY dateCreated DESC LIMIT 5";

                if (mysqli_query($database, $updateQuery)) {
                    echo "Latest 5 notifications marked as seen successfully!";
                } else {
                    echo "Error updating notifications: " . mysqli_error($database);
                }

                mysqli_close($database);
            } else if (strcasecmp($userData['role'], "Staff") == 0) {
                // Mark the latest 5 notifications as seen
                $updateQuery = "UPDATE tbl_notifications AS notif
                LEFT JOIN tbl_useraccounts AS users ON users.employee_id = notif.empIdFrom 
                SET notif.status = 'unread'
                WHERE notif.empIdTo IN ('$empId', '@Admin') 
                    AND notif.status = 'unseen'
                    AND (
                        UPPER(users.role) = 'EMPLOYEE' 
                        OR (UPPER(users.role) = 'ADMIN' OR notif.empIdFrom = '@Admin') 
                            AND notif.empIdTo = '$empId'
                    )
                ORDER BY notif.dateCreated DESC 
                LIMIT 5
                ";

                if (mysqli_query($database, $updateQuery)) {
                    echo "Latest 5 notifications marked as seen successfully!";
                } else {
                    echo "Error updating notifications: " . mysqli_error($database);
                }

                mysqli_close($database);
            } else {
                // Something
            }
        }
    }
} else {
    echo "Invalid request method";
}
?>