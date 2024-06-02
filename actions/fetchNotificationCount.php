<?php
include("../constants/routes.php");
// include($components_file_error_handler);
include($constants_file_dbconnect);
// include($constants_file_session_admin);
include($constants_variables);

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
                // Counts the unread notifications
                $countQuery = "SELECT COUNT(*) as count FROM tbl_notifications WHERE empIdTo = '@Admin' AND status = 'unseen'";
                $countResult = mysqli_query($database, $countQuery);
                $countRow = mysqli_fetch_assoc($countResult);
                $unreadCount = $countRow['count'];

                echo $unreadCount;
                mysqli_close($database);
            }else if (strcasecmp($userData['role'], "Staff") == 0) {
                // Counts the unread notifications
                $countQuery = " SELECT 
                                    COUNT(*) as count 
                                FROM 
                                    tbl_notifications notif
                                LEFT JOIN 
                                    tbl_useraccounts users 
                                ON 
                                    users.employee_id = notif.empIdFrom
                                WHERE 
                                    notif.empIdTo IN ('$empId', '@Admin') 
                                    AND notif.status = 'unseen'
                                    AND (
                                        UPPER(users.role) = 'EMPLOYEE' 
                                        OR ((UPPER(users.role) = 'ADMIN' OR notif.empIdFrom = '@Admin') AND notif.empIdTo = '$empId')
                                    )";
                $countResult = mysqli_query($database, $countQuery);
                $countRow = mysqli_fetch_assoc($countResult);
                $unreadCount = $countRow['count'];

                echo $unreadCount;
                mysqli_close($database);
            }else if (strcasecmp($userData['role'], "Employee") == 0) {
                // Counts the unread notifications
                $countQuery = "SELECT COUNT(*) as count FROM tbl_notifications WHERE empIdTo = '$empId' AND status = 'unseen'";
                $countResult = mysqli_query($database, $countQuery);
                $countRow = mysqli_fetch_assoc($countResult);
                $unreadCount = $countRow['count'];

                echo $unreadCount;
                mysqli_close($database);
            }else{
                echo '?';
            }
        }
    }
} else {
    echo "Invalid request method";
}
?>