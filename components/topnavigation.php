<?php
include ($constants_file_role_menu);

$selectedMenu = [];

if (isset($_SESSION) && isset($_SESSION["role"])) {
    if (strcasecmp($_SESSION['role'], "Employee") == 0) {
        $selectedMenu = $employeeMenu;
    } else if (strcasecmp($_SESSION['role'], "Admin") == 0) {
        $selectedMenu = $adminMenu;
    } else if (strcasecmp($_SESSION['role'], "Staff") == 0) {
        $selectedMenu = $staffMenu;
    }
}

$employeeUserName = [];
$loginUserName = '';

if (isset($_SESSION['employeeId'])) {
    $employeeId = $database->real_escape_string($_SESSION['employeeId']);

    $UserNamequery = "SELECT firstName, lastName FROM tbl_useraccounts WHERE employee_Id = ?";

    $stmtUserName = $database->prepare($UserNamequery);

    if ($stmtUserName) {
        $stmtUserName->bind_param("s", $employeeId);
        $stmtUserName->execute();
        $userResult = $stmtUserName->get_result();

        if ($userResult->num_rows > 0) {
            $employeeUserName = $userResult->fetch_assoc();
        }

        $stmtUserName->close();
    } else {
        // Something
    }
}

if (isset($_REQUEST['logout'])) {
    try {
        if ($_SESSION) {
            $_SESSION['employeeId'] = null;
            $_SESSION['role'] = null;
            $_SESSION['username'] = null;

            unset($_SESSION['employeeId']);
            unset($_SESSION['role']);
            unset($_SESSION['username']);
            session_unset();

            session_destroy();

            header("location: " . $location_login);
        } else {
            header("location: " . $location_login);
        }
    } catch (Exception $e) {
        echo '<script>alert("An error occurred: ' . $e->getMessage() . '");</script>';
    }
}

// if(!empty($employeeUserName)){
//     $loginUserName = strlen($employeeUserName['firstName'] . " " . $employeeUserName['lastName']) > 15 ? substr($employeeUserName['firstName'] . " " . $employeeUserName['lastName'], 0, 15) . "..." : $employeeUserName['firstName'] . " " . $employeeUserName['lastName'];
// }

?>

<nav id="top-nav" class="top-nav-container">

    <div class='top-nav-content'>
        <div id="menu-toggle" onclick="toggleMenu()" class='top-nav-menu-button'>
            <i class="fa fa-bars" aria-hidden="true"></i>
        </div>
        <div class="logo-container">
            <img src="<?php echo $assets_logo_png; ?>" alt="Web Page Logo" class="small-web-logo">
        </div>
        <div class="top-nav-head-content">
            <div class="top-nav-title-content top-nav-title">
                <div>Municipality of Indang</div>
                <div class="top-nav-title-content-props">-</div>
                <div>Cavite</div>
            </div>
            <div class="top-nav-title-abbre">HR - Indang</div>
            <div class="top-nav-section">
                <div class="top-nav-section-medium">
                    Human Resources
                </div>
                <div>
                    <?php
                    if ($_SESSION["role"] == 'Admin') {
                        echo "Admin";
                    } else if ($_SESSION["role"] == 'Staff') {
                        echo "Staff";
                    } else {
                        echo "Employee";
                    }
                    ?>
                    Web Site
                </div>
            </div>
        </div>
    </div>
    <div class="top-nav-content">

        <div id="notification-menu" class="position-relative clickable-element toggle-notification">
            <i class="fa fa-bell text-white"></i>
            <span id="notifCount"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                0
                <span class="visually-hidden">unread messages</span>
            </span>
        </div>

        <div id="notification-container"></div>

        <div class="top-nav-username text-truncate">
            <?php
            if (isset($_SESSION['employeeId']) && !empty($employeeUserName)) {
                // echo $loginUserName;
                echo htmlspecialchars($employeeUserName['firstName'] . " " . $employeeUserName['lastName'], ENT_QUOTES, 'UTF-8');
            } else {
                echo 'Username';
            }
            ?>
        </div>
        <form method="post">
            <button type="submit" name="logout" class="top-nav-logout-button">
                <i class="fa fa-power-off" aria-hidden="true"></i> <span class="top-nav-logout-text">Logout</span>
            </button>
        </form>
    </div>

</nav>

<div id="sidebar">
    <div id="menu-toggle" class="menu-toggle-class" onclick="toggleMenu()"><i class="fa fa-bars" aria-hidden="true"></i>
    </div>

    <ul class='custom-scrollbar'>
        <?php
        foreach ($selectedMenu as $name => $data) {
            $icon = $data["icon"];
            $link = $data["link"];
            ?>
            <li>
                <a title="<?php echo $name; ?>" href="<?php echo $link; ?>">
                    <i class="<?php echo $icon; ?>" aria-hidden="true"></i>
                    <?php echo $name; ?>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>

</div>

<script src="<?php echo $assets_script_topnav; ?>"></script>

<?php
include ($constants_file_dbconnect);
?>