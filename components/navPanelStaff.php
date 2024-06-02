<div class='tab-nav-container custom-scrollbar'>
    <a title="Back" href="
    <?php
    if (isset($_SESSION['departmentlabel'])) {
        echo $location_staff_departments_office . '/' . $_SESSION['departmentlabel'] . '/';
    } else {
        echo $location_staff_departments_office;
    }
    ?>" style="width: 10%; text-align: center;" class="tab-nav-button">
        Back
    </a>
    <a title="Employee Information" href="<?php echo $location_staff_departments_employee . '/' . $empId . '/'; ?>"
        class="tab-nav-button <?php echo ($_SERVER['SCRIPT_NAME'] === $location_staff_departments_employee . '/index.php') ? 'active-tab-nav text-white' : ''; ?>">
        Employee Information
    </a>
    <a title="Leave Application Form Record"
        href="<?php echo $location_staff_departments_employee_leaveappform . '/' . $empId . '/'; ?>"
        class="tab-nav-button <?php echo ($_SERVER['SCRIPT_NAME'] === $location_staff_departments_employee_leaveappform . '/index.php') ? 'active-tab-nav text-white' : ''; ?>">
        Leave Application Record
    </a>
    <a title="Leave Data Form"
        href="<?php echo $location_staff_departments_employee_leavedataform . '/' . $empId . '/'; ?>"
        class="tab-nav-button <?php echo ($_SERVER['SCRIPT_NAME'] === $location_staff_departments_employee_leavedataform . '/index.php') ? 'active-tab-nav text-white' : ''; ?>">
        Leave Data Form
    </a>
</div>