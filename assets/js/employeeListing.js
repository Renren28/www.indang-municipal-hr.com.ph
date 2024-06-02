// <!-- Checking Check Values -->
$(document).ready(function () {
    $("#clearAddEmployeeInputs").click(function () {
        $("#addEmployee :input:not(:submit)").val('');
        $("#addEmployee select").prop('selectedIndex', 0);

        var currentDate = new Date().toISOString().split('T')[0];
        $("#floatingDateStarted").val(currentDate);
    });


    // Get all selected rows
    // $('#OLDdeleteEmployees').on('click', function () {
    //     // let selectedRows = table.rows({ selected: true }).data().toArray();
    //     // console.log(selectedRows);

    //     let selectedData = table.rows({ selected: true }).data().pluck(0).toArray();
    //     console.log(selectedData);
    // });
});

function printSelectedValues() {
    var checkboxes = document.getElementsByName('selectedEmployee[]');
    var selectedValues = [];

    // Iterate through checkboxes and check if they are checked
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            selectedValues.push(checkboxes[i].value);
        }
    }

    console.log(selectedValues);
    // console.log("Selected values: " + selectedValues.join(', '));
}

// <!-- Disable Multiple Select Button -->
document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = document.getElementsByName('selectedEmployee[]');
    var deleteEmployeesButton = document.getElementById('deleteMultipleEmployeeBTN');
    // var editEmployeesButton = document.getElementById('editMultipleEmployeeBTN');
    var selectedEmpIDInput = document.getElementById('selectedEmpID');

    // Add event listener to checkboxes
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            updateDeleteEmployeesButtonState();
        });
    });

    // Function to update the state of the delete button
    function updateDeleteEmployeesButtonState() {
        var selectedValues = Array.from(checkboxes).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value);
        deleteEmployeesButton.disabled = selectedValues.length <= 0;
        // editEmployeesButton.disabled = selectedValues.length <= 0;

        // Convert the array to JSON and update the value of the hidden input
        selectedEmpIDInput.value = JSON.stringify(selectedValues);
    }

    // Initial update of the delete button state
    updateDeleteEmployeesButtonState();
});

// <!-- Edit Modal Fetch and Reset -->

// Variable to store the state
var editEmployeeState = null;

$(document).ready(function () {
    // Use event delegation to handle click events on dynamically created elements
    $('#employees').on('click', '.editEmployeeButton', function () {
        // Get data from the button
        var employeeId = $(this).data('employee-id');
        var role = $(this).data('role');
        var email = $(this).data('email');
        var password = $(this).data('password');
        var firstName = $(this).data('first-name');
        var middleName = $(this).data('middle-name');
        var lastName = $(this).data('last-name');
        var suffix = $(this).data('suffix');
        var sex = $(this).data('sex');
        var civilStatus = $(this).data('civil-status');
        var birthdate = $(this).data('birthdate');
        var department = $(this).data('department');
        var jobPosition = $(this).data('job-position');
        var dateStarted = $(this).data('date-started');
        var accountStatus = $(this).data('account-status');

        // Set form field values
        $('#floatingEditOldEmployeeId').val(employeeId);
        $('#floatingEditEmployeeId').val(employeeId);
        $('#floatingEditSelectRole').val(role);
        $('#floatingEditEmail').val(email);
        $('#floatingEditPassword').val(password);
        $('#floatingEditFirstName').val(firstName);
        $('#floatingEditMiddleName').val(middleName);
        $('#floatingEditLastName').val(lastName);
        $('#floatingEditSuffix').val(suffix);
        $('#floatingEditSex').val(sex);
        $('#floatingEditCivilStatus').val(civilStatus);
        $('#floatingEditBirthdate').val(birthdate);
        $('#floatingEditDepartmentSelect').val(department);
        $('#floatingEditJobPosition').val(jobPosition);
        $('#floatingEditDateStarted').val(dateStarted);
        $('#floatingEditSelectStatus').val(accountStatus);

        // Save the state
        editEmployeeState = {
            employeeId: employeeId,
            role: role,
            email: email,
            password: password,
            firstName: firstName,
            middleName: middleName,
            lastName: lastName,
            suffix: suffix,
            sex: sex,
            civilStatus: civilStatus,
            birthdate: birthdate,
            department: department,
            jobPosition: jobPosition,
            dateStarted: dateStarted,
            accountStatus: accountStatus
        };
    });

    // Function to set data based on the saved state
    function setDataFromState() {
        if (editEmployeeState) {
            // Set form field values based on the saved state
            $('#floatingEditOldEmployeeId').val(editEmployeeState.employeeId);
            $('#floatingEditEmployeeId').val(editEmployeeState.employeeId);
            $('#floatingEditSelectRole').val(editEmployeeState.role);
            $('#floatingEditEmail').val(editEmployeeState.email);
            $('#floatingEditPassword').val(editEmployeeState.password);
            $('#floatingEditFirstName').val(editEmployeeState.firstName);
            $('#floatingEditMiddleName').val(editEmployeeState.middleName);
            $('#floatingEditLastName').val(editEmployeeState.lastName);
            $('#floatingEditSuffix').val(editEmployeeState.suffix);
            $('#floatingEditSex').val(editEmployeeState.sex);
            $('#floatingEditCivilStatus').val(editEmployeeState.civilStatus);
            $('#floatingEditBirthdate').val(editEmployeeState.birthdate);
            $('#floatingEditDepartmentSelect').val(editEmployeeState.department);
            $('#floatingEditJobPosition').val(editEmployeeState.jobPosition);
            $('#floatingEditDateStarted').val(editEmployeeState.dateStarted);
            $('#floatingEditSelectStatus').val(editEmployeeState.accountStatus);
            $('#floatingEditReasonForStatus').val('');
        }
    }

    // Add click event handler for the Reset button
    $('#resetEditEmployeeInputs').click(function () {
        // Reset form fields to their initial values
        setDataFromState();
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const statusSelect = document.getElementById("floatingSelectStatus");
    const reasonInput = document.getElementById("reasonForStatusContainer");
    const reasonField = document.getElementById("floatingReasonForStatus");
    const reasonStyleElement = reasonInput.querySelector(".reasonStyle");

    // Function to toggle visibility and requirement of reason input based on status
    function toggleReasonInput() {
        const selectedStatus = statusSelect.value;
        if (selectedStatus === "Active") {
            reasonInput.style.display = "none";
            reasonField.value = ""; // Clear input value
            reasonField.removeAttribute("required"); // Make it not required
            reasonStyleElement.textContent = ""; // Clear reason style content
        } else {
            reasonInput.style.display = "block";
            reasonField.setAttribute("required", "required"); // Make it required
            reasonStyleElement.textContent = "*"; // Set reason style content to "*"
        }
    }

    // Initial call to set initial state
    toggleReasonInput();

    // Event listener for status change
    statusSelect.addEventListener("change", toggleReasonInput);
});

document.addEventListener("DOMContentLoaded", function () {
    const editStatusSelect = document.getElementById("floatingEditSelectStatus");
    const editReasonInput = document.getElementById("reasonForStatusEditContainer");
    const editReasonField = document.getElementById("floatingEditReasonForStatus");
    const editReasonStyleElement = editReasonInput.querySelector(".reasonStyle");

    // Function to toggle visibility and requirement of reason input based on status
    function toggleEditReasonInput() {
        const selectedStatus = editStatusSelect.value;
        if (selectedStatus === "Active") {
            editReasonInput.style.display = "none";
            editReasonField.value = ""; // Clear input value
            editReasonField.removeAttribute("required"); // Make it not required
            editReasonStyleElement.textContent = ""; // Clear reason style content
        } else {
            editReasonInput.style.display = "block";
            editReasonField.setAttribute("required", "required"); // Make it required
            editReasonStyleElement.textContent = "*"; // Set reason style content to "*"
        }
    }

    // Initial call to set initial state
    toggleEditReasonInput();

    // Event listener for status change
    editStatusSelect.addEventListener("change", toggleEditReasonInput);
});
