// function printSelectedValues() {
//     var checkboxes = document.getElementsByName('selectedDepartment[]');
//     var selectedValues = [];

//     // Iterate through checkboxes and check if they are checked
//     for (var i = 0; i < checkboxes.length; i++) {
//         if (checkboxes[i].checked) {
//             selectedValues.push(checkboxes[i].value);
//         }
//     }

//     console.log(selectedValues);
//     // console.log("Selected values: " + selectedValues.join(', '));
// }



// Function to set up multiple select button behavior
function setupMultipleSelectButton(checkboxes, button) {
    // Check if the button is found
    if (button) {
        // Add event listener to checkboxes
        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                updateButtonState();
            });
        });

        // Function to update the state of the button
        function updateButtonState() {
            var selectedValues = Array.from(checkboxes).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value);
            button.disabled = selectedValues.length < 1;
        }

        // Update button state initially
        updateButtonState();
    } 
    // else {
    //     // Handle the case where the button is not found
    //     console.error('Button not found.');
    // }
}


// <!-- Disable Multiple Select Button in Department -->
document.addEventListener('DOMContentLoaded', function () {
    var departmentCheckboxes = document.getElementsByName('selectedDepartment[]');
    var retrieveDepartmentButton = document.getElementById('retrieveMultipleDepartmentBTN');
    setupMultipleSelectButton(departmentCheckboxes, retrieveDepartmentButton);
});

// <!-- Disable Multiple Select Button in Employee -->
document.addEventListener('DOMContentLoaded', function () {
    var employeeCheckboxes = document.getElementsByName('selectedEmployee[]');
    var retrieveEmployeeButton = document.getElementById('retrieveMultipleEmployeeBTN');
    setupMultipleSelectButton(employeeCheckboxes, retrieveEmployeeButton);
});

// <!-- Disable Multiple Select Button in Leave Form -->
document.addEventListener('DOMContentLoaded', function () {
    var leaveFormCheckboxes = document.getElementsByName('selectedLeaveForm[]');
    var retrieveLeaveFormButton = document.getElementById('retrieveMultipleLeaveFormBTN');
    setupMultipleSelectButton(leaveFormCheckboxes, retrieveLeaveFormButton);
});

// <!-- Disable Multiple Select Button in Leave Data -->
document.addEventListener('DOMContentLoaded', function () {
    var leaveDataCheckboxes = document.getElementsByName('selectedLeaveData[]');
    var retrieveLeaveDataButton = document.getElementById('retrieveMultipleLeaveDataBTN');
    setupMultipleSelectButton(leaveDataCheckboxes, retrieveLeaveDataButton);
});

// <!-- Disable Multiple Select Button in Work Designations -->
document.addEventListener('DOMContentLoaded', function () {
    var workDesignationCheckboxes = document.getElementsByName('selectedDesignation[]');
    var retrieveWorkDesignationButton = document.getElementById('retrieveMultipleDesignationBTN');
    setupMultipleSelectButton(workDesignationCheckboxes, retrieveWorkDesignationButton);
});