<?php

@ob_start();
session_start();

// Set session timeout to 8 hours
$session_timeout = 8 * 60 * 60;

// Check if the session variable last activity is set
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_timeout)) {
    // Session has expired
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: " . $location_login);
}

// Update last activity time stamp
$_SESSION['last_activity'] = time();

if (isset($_SESSION['employeeId'])) {
    $employeeId = $_SESSION['employeeId'];
    $sql = "SELECT * FROM tbl_useraccounts WHERE employee_id= '$employeeId' AND UPPER(archive) != 'DELETED'";
    $result = $database->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (strcasecmp($row['role'], "Employee") != 0 && strcasecmp($row['role'], "Staff") != 0) {
                if (strcasecmp($row['role'], "Admin") == 0) {
                    header("location: " . $location_admin);
                } else {
                    header("Location: " . $location_login);
                    ?>
                        <script>
                            alert("Error: There is no such role!");
                        </script>
                        <?php
                        session_unset();
                        session_destroy();
                }
            }
        }
    } else {
        header("Location: " . $location_login);
        ?>
        <script>
            alert("An error has occurred: There is no registered employee ID found.");
        </script>
        <?php
        session_unset();
        session_destroy();
    }
} else {
    header("location: " . $location_login);
}
?>