document.addEventListener("DOMContentLoaded", function () {
    // Add event listener to the radio buttons with name "typeOfLeave"
    var leaveTypeRadios = document.querySelectorAll('input[name="typeOfLeave"]');
    var otherTypeOfLeave = document.querySelector('input[name="otherTypeOfLeave"]');
    var typeOfVacationLeave = document.querySelectorAll('input[name="typeOfVacationLeave"]');
    var typeOfSickLeave = document.querySelectorAll('input[name="typeOfSickLeave"]');
    var typeOfStudyLeave = document.querySelectorAll('input[name="typeOfStudyLeave"]');
    var typeOfOtherLeave = document.querySelectorAll('input[name="typeOfOtherLeave"]');

    leaveTypeRadios.forEach(function (radio) {
        radio.addEventListener("change", handleLeaveTypeChange);
    });

    otherTypeOfLeave.addEventListener("change", handleLeaveTypeChange);

    typeOfVacationLeave.forEach(function (radio) {
        radio.addEventListener("change", handleLeaveTypeChange);
    });

    typeOfSickLeave.forEach(function (radio) {
        radio.addEventListener("change", handleLeaveTypeChange);
    });

    typeOfStudyLeave.forEach(function (radio) {
        radio.addEventListener("change", handleLeaveTypeChange);
    });

    typeOfOtherLeave.forEach(function (radio) {
        radio.addEventListener("change", handleLeaveTypeChange);
    });

    // Initial state
    handleLeaveTypeChange();

    $(document).ready(function () {
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Computation of Days

        function computeDays(startDate, endDate) {
            const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
            const start = new Date(startDate);
            const end = new Date(endDate);

            const diffDays = Math.round(Math.abs((start - end) / oneDay)) + 1;
            return diffDays;
        }

        function updateWorkDays() {
            const inclusiveDateStart = $('#inclusiveDateStart').val();
            const inclusiveDateEnd = $('#inclusiveDateEnd').val();

            if (inclusiveDateStart && inclusiveDateEnd) {
                const days = computeDays(inclusiveDateStart, inclusiveDateEnd);
                $('#workingDays').val(days);
            }
        }

        function dateDifference(currentDate, days) {
            // Parse the current date
            var date = new Date(currentDate);
    
            // Add or subtract the days
            date.setDate(date.getDate() + days);
    
            // Format the new date as Y-m-d
            var year = date.getFullYear();
            var month = String(date.getMonth() + 1).padStart(2, '0');
            var day = String(date.getDate()).padStart(2, '0');
            var formattedDate = `${year}-${month}-${day}`;
    
            return formattedDate;
        }

        $('#inclusiveDateStart').on('change', function () {
            // Get the values of the two input fields
            var inclusiveDateStart = $('#inclusiveDateStart').val();
            var inclusiveDateEnd = $('#inclusiveDateEnd').val();
            var typeOfLeave = document.querySelector('input[name="typeOfLeave"]:checked') ? document.querySelector('input[name="typeOfLeave"]:checked').value : '';

            if(typeOfLeave === 'Maternity Leave'){
                $('#inclusiveDateEnd').val(dateDifference(inclusiveDateStart, 105));
            }

            // Compare the values
            if (inclusiveDateStart >= inclusiveDateEnd) {
                $('#inclusiveDateEnd').val(inclusiveDateStart);
                Toastify({
                    text: 'Inclusive Start Date should not be Greater Than the Inclusive End Date!',
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: 'top',
                    position: 'center',
                    style: {
                        background: '#fca100',
                    },
                    stopOnFocus: true,
                }).showToast();
            } else {
                containerPeriod = $('#inclusiveDateStart').val();
            }
            updateWorkDays();
        });

        $('#inclusiveDateEnd').on('change', function () {
            // Get the values of the two input fields
            var inclusiveDateStart = $('#inclusiveDateStart').val();
            var inclusiveDateEnd = $('#inclusiveDateEnd').val();

            // Compare the values
            if (inclusiveDateStart > inclusiveDateEnd) {
                $('#inclusiveDateEnd').val(inclusiveDateStart);
                Toastify({
                    text: 'Inclusive Start Date should not be Greater Than the Inclusive End Date!',
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: 'top',
                    position: 'center',
                    style: {
                        background: '#fca100',
                    },
                    stopOnFocus: true,
                }).showToast();
            } else {
                containerPeriod = $('#inclusiveDateStart').val();
            }
            updateWorkDays();
        });

    });
});

// function handleOtherLeaveTypeChange() {
//     var otherTypeOfLeave = document.querySelector('input[name="otherTypeOfLeave"]');
//     var leaveTypeRadios = document.querySelectorAll('input[name="typeOfLeave"]');
//     if(otherTypeOfLeave.value !== ''){
//         leaveTypeRadios.forEach(function (radio) {
//         radio.checked = false;
//     });
//     leaveTypeRadios.value = '';
//     }
// }

function handleLeaveTypeChange() {
    var lastType = "Special Privilege Leave";
    var typeOfLeave = document.querySelector('input[name="typeOfLeave"]:checked') ? document.querySelector('input[name="typeOfLeave"]:checked').value : '';
    var typeOfVacationLeave = document.querySelector('input[name="typeOfVacationLeave"]:checked') ? document.querySelector('input[name="typeOfVacationLeave"]:checked').value : '';
    var typeOfSickLeave = document.querySelector('input[name="typeOfSickLeave"]:checked') ? document.querySelector('input[name="typeOfSickLeave"]:checked').value : '';
    var allLeave = document.getElementById('allLeave');
    var splLeave = document.getElementById('splLeave');
    var workingDays = document.getElementById('workingDays');

    var inclusiveDateStart = document.getElementById('inclusiveDateStart');
    var inclusiveDateEnd = document.getElementById('inclusiveDateEnd');

    var inclusiveDateOne = document.getElementById('inclusiveDateSelectOne');
    var inclusiveDateTwo = document.getElementById('inclusiveDateSelectTwo');
    var inclusiveDateThree = document.getElementById('inclusiveDateSelectThree');

    var noaomlfLink = document.getElementById('noaomlfLink');
    var noaomlfLinkBtnDummy = document.getElementById('noaomlfLinkBtnDummy');
    var today = new Date().toISOString().split('T')[0];

    // Disable all leaveclass inputs
    var leaveClassInputs = document.querySelectorAll('.leave-app-form-leaveclass-container input');
    leaveClassInputs.forEach(function (input) {
        input.disabled = true;
    });

    var vacationLeaveType = document.querySelectorAll('input[name="typeOfVacationLeave"]');
    var sickLeaveType = document.querySelectorAll('input[name="typeOfSickLeave"]');
    var studyLeaveType = document.querySelectorAll('input[name="typeOfStudyLeave"]');
    var otherLeaveType = document.querySelectorAll('input[name="typeOfOtherLeave"]');

    var otherTypeOfLeaveInput = document.querySelector('input[name="otherTypeOfLeave"]');

    // Enable specific inputs based on typeOfLeave
    if (typeOfLeave === 'Vacation Leave') {
        enableInputs(['typeOfVacationLeave']);
        if (typeOfVacationLeave == "Within the Philippines") {
            enableInputs(['typeOfVacationLeaveWithin']);
            resetInputs(['typeOfVacationLeaveAbroad']);
        } else if (typeOfVacationLeave == "Abroad") {
            enableInputs(['typeOfVacationLeaveAbroad']);
            resetInputs(['typeOfVacationLeaveWithin']);
        }

        document.getElementById('requested').checked = true;

        // Resets Inputs
        resetInputs(['typeOfSickLeaveInHospital']);
        resetInputs(['typeOfSickLeaveOutPatient']);
        resetInputs(['typeOfSickLeaveOutPatientOne']);
        sickLeaveType.forEach(function (input) {
            input.checked = false;
        });

        resetInputs(['typeOfSpecialLeaveForWomen']);
        resetInputs(['typeOfSpecialLeaveForWomenOne']);

        studyLeaveType.forEach(function (input) {
            input.checked = false;
        });

        // otherLeaveType.forEach(function (input) {
        //     input.checked = false;
        // });

    } else if (typeOfLeave === 'Sick Leave') {
        enableInputs(['typeOfSickLeave']);
        if (typeOfSickLeave == "In Hospital") {
            enableInputs(['typeOfSickLeaveInHospital']);
            resetInputs(['typeOfSickLeaveOutPatient']);
            resetInputs(['typeOfSickLeaveOutPatientOne']);
        } else if (typeOfSickLeave == "Out Patient") {
            enableInputs(['typeOfSickLeaveOutPatient']);
            enableInputs(['typeOfSickLeaveOutPatientOne']);
            resetInputs(['typeOfSickLeaveInHospital']);
        }

        document.getElementById('notRequested').checked = true;

        // Resets Inputs
        resetInputs(['typeOfVacationLeaveWithin']);
        resetInputs(['typeOfVacationLeaveAbroad']);
        vacationLeaveType.forEach(function (input) {
            input.checked = false;
        });

        resetInputs(['typeOfSpecialLeaveForWomen']);
        resetInputs(['typeOfSpecialLeaveForWomenOne']);

        studyLeaveType.forEach(function (input) {
            input.checked = false;
        });

        // otherLeaveType.forEach(function (input) {
        //     input.checked = false;
        // });

    } else if (typeOfLeave === 'Special Leave Benefits for Women') {
        enableInputs(['typeOfSpecialLeaveForWomen', 'typeOfSpecialLeaveForWomenOne']);

        document.getElementById('requested').checked = true;

        // Resets Inputs
        resetInputs(['typeOfVacationLeaveWithin']);
        resetInputs(['typeOfVacationLeaveAbroad']);
        vacationLeaveType.forEach(function (input) {
            input.checked = false;
        });

        resetInputs(['typeOfSickLeaveInHospital']);
        resetInputs(['typeOfSickLeaveOutPatient']);
        resetInputs(['typeOfSickLeaveOutPatientOne']);
        sickLeaveType.forEach(function (input) {
            input.checked = false;
        });

        studyLeaveType.forEach(function (input) {
            input.checked = false;
        });

        // otherLeaveType.forEach(function (input) {
        //     input.checked = false;
        // });

    } else if (typeOfLeave === 'Study Leave') {
        enableInputs(['typeOfStudyLeave']);

        document.getElementById('requested').checked = true;

        // Resets Inputs
        resetInputs(['typeOfVacationLeaveWithin']);
        resetInputs(['typeOfVacationLeaveAbroad']);
        vacationLeaveType.forEach(function (input) {
            input.checked = false;
        });

        resetInputs(['typeOfSickLeaveInHospital']);
        resetInputs(['typeOfSickLeaveOutPatient']);
        resetInputs(['typeOfSickLeaveOutPatientOne']);
        sickLeaveType.forEach(function (input) {
            input.checked = false;
        });

        resetInputs(['typeOfSpecialLeaveForWomen']);
        resetInputs(['typeOfSpecialLeaveForWomenOne']);

        // otherLeaveType.forEach(function (input) {
        //     input.checked = false;
        // });
    } else {

        if (typeOfLeave == 'Forced Leave') {
            document.getElementById('notRequested').checked = true;
        } else if (typeOfLeave == '') {
            document.getElementById('requested').checked = false;
            document.getElementById('notRequested').checked = false;
        } else {
            document.getElementById('requested').checked = true;
        }

        // Resets Inputs
        resetInputs(['typeOfVacationLeaveWithin']);
        resetInputs(['typeOfVacationLeaveAbroad']);
        vacationLeaveType.forEach(function (input) {
            input.checked = false;
        });

        resetInputs(['typeOfSickLeaveInHospital']);
        resetInputs(['typeOfSickLeaveOutPatient']);
        resetInputs(['typeOfSickLeaveOutPatientOne']);
        sickLeaveType.forEach(function (input) {
            input.checked = false;
        });

        resetInputs(['typeOfSpecialLeaveForWomen']);
        resetInputs(['typeOfSpecialLeaveForWomenOne']);

        studyLeaveType.forEach(function (input) {
            input.checked = false;
        });

        // otherLeaveType.forEach(function (input) {
        //     input.checked = false;
        // });
    }

    if (typeOfLeave === 'Maternity Leave' || typeOfLeave === 'Paternity Leave') {
        noaomlfLink.style.display = 'block';
        noaomlfLinkBtnDummy.style.display = 'none';
    } else {
        noaomlfLink.style.display = 'none';
        noaomlfLinkBtnDummy.style.display = 'block';
    }

    if (typeOfLeave === 'Special Privilege Leave') {
        allLeave.style.display = 'none';
        splLeave.style.display = 'block';
    } else {
        allLeave.style.display = 'block';
        splLeave.style.display = 'none';
    }

    inclusiveDateStart.value = today;
    inclusiveDateEnd.value = today;
    inclusiveDateOne.value = today;
    inclusiveDateTwo.value = today;
    inclusiveDateThree.value = today;
    workingDays.value = 1;

    if (typeOfLeave == "Vacation Leave" || typeOfLeave == 'Forced Leave') {
        inclusiveDateStart.min = dateDifference(today, 5);
        inclusiveDateStart.max = '';
        inclusiveDateEnd.min = dateDifference(today, 5);
        inclusiveDateEnd.max = '';
        inclusiveDateOne.min = '';
        inclusiveDateTwo.min = '';
        inclusiveDateThree.min = '';
        inclusiveDateEnd.readOnly = false;
    }else if (typeOfLeave == "Sick Leave") {
        inclusiveDateStart.min = dateDifference(today, -5);
        inclusiveDateStart.max = dateDifference(today, -1);
        inclusiveDateEnd.min = dateDifference(today, -5);
        inclusiveDateEnd.max = dateDifference(today, -1);
        inclusiveDateOne.min = '';
        inclusiveDateTwo.min = '';
        inclusiveDateThree.min = '';
        inclusiveDateEnd.readOnly = false;
    }else if (typeOfLeave == "Maternity Leave") {
        inclusiveDateStart.min = dateDifference(today, 5);
        inclusiveDateStart.max = '';
        inclusiveDateEnd.min = dateDifference(today, 5);
        inclusiveDateEnd.max = dateDifference(today, 125);
        inclusiveDateOne.min = '';
        inclusiveDateTwo.min = '';
        inclusiveDateThree.min = '';
        inclusiveDateEnd.readOnly = true;
    }else if (typeOfLeave == "Rehabilitation Privilege") {
        inclusiveDateStart.min = today;
        inclusiveDateStart.max = dateDifference(today, 7);
        inclusiveDateEnd.min = today;
        inclusiveDateEnd.max = dateDifference(today, 180);
        inclusiveDateOne.min = '';
        inclusiveDateTwo.min = '';
        inclusiveDateThree.min = '';
        inclusiveDateEnd.readOnly = true;
    }

    // console.log(today);



    if (otherTypeOfLeaveInput.value.trim() !== '') {
        // leaveTypeRadios.forEach(function (radio) {
        //     radio.checked = false;
        // });
        enableInputs(['typeOfOtherLeave']);
    } else {
        document.getElementById('monetizationLeave').checked = false;
        document.getElementById('terminalLeave').checked = false;
    }

    function enableInputs(inputNames) {
        inputNames.forEach(function (inputName) {
            var inputs = document.querySelectorAll('input[name="' + inputName + '"]');
            inputs.forEach(function (input) {
                input.disabled = false;
            });
        });
    }

    function resetInputs(inputNames) {
        inputNames.forEach(function (inputName) {
            var inputs = document.querySelectorAll('input[name="' + inputName + '"]');
            inputs.forEach(function (input) {
                input.value = '';
            });
        });
    }

    function dateDifference(currentDate, days) {
        // Parse the current date
        var date = new Date(currentDate);

        // Add or subtract the days
        date.setDate(date.getDate() + days);

        // Format the new date as Y-m-d
        var year = date.getFullYear();
        var month = String(date.getMonth() + 1).padStart(2, '0');
        var day = String(date.getDate()).padStart(2, '0');
        var formattedDate = `${year}-${month}-${day}`;

        return formattedDate;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Get the date input elements
    const dateInputs = [
        document.getElementById('inclusiveDateSelectOne'),
        document.getElementById('inclusiveDateSelectTwo'),
        document.getElementById('inclusiveDateSelectThree')
    ];

    // Get the workingDays input element
    const workingDaysInput = document.getElementById('workingDays');

    // Function to update working days based on date inputs
    function updateWorkingDays() {
        // Create a set to hold unique dates
        const uniqueDates = new Set();

        // Add non-empty dates to the set
        dateInputs.forEach(input => {
            if (input.value) {
                uniqueDates.add(input.value);
            }
        });

        // Update the workingDays input value based on the size of the set
        workingDaysInput.value = uniqueDates.size;
    }

    // Add event listeners to update working days on date change
    dateInputs.forEach(input => {
        input.addEventListener('change', updateWorkingDays);
    });

    // Initialize the working days value on page load
    updateWorkingDays();
});