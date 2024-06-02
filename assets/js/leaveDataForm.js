// <!-- Leave Fata Form -->

document.addEventListener("DOMContentLoaded", function () {
    // Get the first element with the class "selectedYear"
    let selectedYearElement = document.getElementById("selectedYear").innerHTML;
    var selectedYear = selectedYearElement;

    var addLeaveDataRecordState = null;
    var currentYear = new Date().getFullYear();
    var addPeriod = null;
    var addPeriodEnd = null;
    var addDateOfAction = null;

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

        // Adding Modal Days

        function updateDays() {
            const period = $('#floatingPeriod').val();
            const periodEnd = $('#floatingPeriodEnd').val();

            // Check if both period and periodEnd have valid values
            if (period && periodEnd) {
                const days = computeDays(period, periodEnd);
                $('#floatingDayInput').val(days);
            }
        }

        $('#floatingPeriod').on('change', function () {
            // Get the values of the two input fields
            var floatingPeriodValue = $('#floatingPeriod').val();
            var floatingPeriodEndValue = $('#floatingPeriodEnd').val();

            // Compare the values
            if (floatingPeriodValue > floatingPeriodEndValue) {
                $('#floatingPeriodEnd').val(floatingPeriodValue);
                Toastify({
                    text: 'Period should not be Greater Than the Period End!',
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
                containerPeriod = $('#floatingPeriod').val();
            }
            updateDays();
        });

        $('#floatingPeriodEnd').on('change', function () {
            // validateDateInput(this);

            // Get the values of the two input fields
            var floatingPeriodValue = $('#floatingPeriod').val();
            var floatingPeriodEndValue = $('#floatingPeriodEnd').val();

            // Compare the values
            if (floatingPeriodValue > floatingPeriodEndValue) {
                $('#floatingPeriodEnd').val(floatingPeriodValue);
                Toastify({
                    text: 'Period should not be Greater Than the Period End!',
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
                containerPeriod = $('#floatingPeriod').val();
            }
            updateDays();
        });

        // Adding New Modal Days

        function updateNewDays() {
            const period = $('#floatingNewPeriod').val();
            const periodEnd = $('#floatingNewPeriodEnd').val();

            // Check if both period and periodEnd have valid values
            if (period && periodEnd) {
                const days = computeDays(period, periodEnd);
                $('#floatingNewDayInput').val(days);
            }
        }

        $('#floatingNewPeriod').on('change', function () {
            // Get the values of the two input fields
            var floatingPeriodValue = $('#floatingNewPeriod').val();
            var floatingPeriodEndValue = $('#floatingNewPeriodEnd').val();

            // Compare the values
            if (floatingPeriodValue > floatingPeriodEndValue) {
                $('#floatingNewPeriodEnd').val(floatingPeriodValue);
                Toastify({
                    text: 'Period should not be Greater Than the Period End!',
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
                containerPeriod = $('#floatingNewPeriod').val();
            }
            updateNewDays();
        });

        $('#floatingNewPeriodEnd').on('change', function () {
            // validateDateInput(this);

            // Get the values of the two input fields
            var floatingPeriodValue = $('#floatingNewPeriod').val();
            var floatingPeriodEndValue = $('#floatingNewPeriodEnd').val();

            // Compare the values
            if (floatingPeriodValue > floatingPeriodEndValue) {
                $('#floatingNewPeriodEnd').val(floatingPeriodValue);
                Toastify({
                    text: 'Period should not be Greater Than the Period End!',
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
                containerPeriod = $('#floatingNewPeriod').val();
            }
            updateNewDays();
        });

        // Adding New Modal Days

        function updateEditDays() {
            const period = $('#floatingEditPeriod').val();
            const periodEnd = $('#floatingEditPeriodEnd').val();

            // Check if both period and periodEnd have valid values
            if (period && periodEnd) {
                const days = computeDays(period, periodEnd);
                $('#floatingEditDayInput').val(days);
            }
        }

        $('#floatingEditPeriod').on('change', function () {
            // Get the values of the two input fields
            var floatingPeriodValue = $('#floatingEditPeriod').val();
            var floatingPeriodEndValue = $('#floatingEditPeriodEnd').val();

            // Compare the values
            if (floatingPeriodValue > floatingPeriodEndValue) {
                $('#floatingEditPeriodEnd').val(floatingPeriodValue);
                Toastify({
                    text: 'Period should not be Greater Than the Period End!',
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
                containerPeriod = $('#floatingEditPeriod').val();
            }
            updateEditDays();
        });

        $('#floatingEditPeriodEnd').on('change', function () {
            // validateDateInput(this);

            // Get the values of the two input fields
            var floatingPeriodValue = $('#floatingEditPeriod').val();
            var floatingPeriodEndValue = $('#floatingEditPeriodEnd').val();

            // Compare the values
            if (floatingPeriodValue > floatingPeriodEndValue) {
                $('#floatingEditPeriodEnd').val(floatingPeriodValue);
                Toastify({
                    text: 'Period should not be Greater Than the Period End!',
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
                containerPeriod = $('#floatingEditPeriod').val();
            }
            updateEditDays();
        });

        // Creating Initial Record

        $('#floatingInitializePeriod').on('change', function () {
            // Get the values of the two input fields
            var floatingPeriodValue = $('#floatingInitializePeriod').val();
            var floatingPeriodEndValue = $('#floatingInitializePeriodEnd').val();

            // Compare the values
            if (floatingPeriodValue > floatingPeriodEndValue) {
                $('#floatingInitializePeriodEnd').val(floatingPeriodValue);
                Toastify({
                    text: 'Period should not be Greater Than the Period End!',
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
                containerPeriod = $('#floatingInitializePeriod').val();
            }
        });

        $('#floatingInitializePeriodEnd').on('change', function () {
            // Get the values of the two input fields
            var floatingPeriodValue = $('#floatingInitializePeriod').val();
            var floatingPeriodEndValue = $('#floatingInitializePeriodEnd').val();

            // Compare the values
            if (floatingPeriodValue > floatingPeriodEndValue) {
                $('#floatingInitializePeriodEnd').val(floatingPeriodValue);
                Toastify({
                    text: 'Period should not be Greater Than the Period End!',
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
                containerPeriod = $('#floatingInitializePeriod').val();
            }
        });

        // Modals Function

        $('#createInitialRecordButton').click(function () {
            addPeriod = $(this).data('period-start');
            addPeriodEnd = formatDate(new Date());
            addDateOfAction = formatDate(new Date());

            // Set form field values
            $('#floatingInitializePeriod').val(addPeriod);
            $('#floatingInitializePeriodEnd').val(addPeriodEnd);
            $('#floatingInitializeDateOfAction').val(addDateOfAction);

            // Save the state
            addLeaveDataRecordState = {
                period: addPeriod,
                periodEnd: addPeriodEnd,
                dateOfAction: addDateOfAction,
            };
        });

        $('.addLeaveDataRecordButton').click(function () {
            // console.log(selectedYear);
            // console.log(currentYear);
            if (selectedYear == currentYear) {
                addPeriod = formatDate(new Date());
                addPeriodEnd = formatDate(new Date());
                addDateOfAction = formatDate(new Date());
            } else {
                // If not the current year, set values to January 01, selectedYear
                addPeriod = addPeriodEnd = formatDate(new Date(selectedYear, 0, 1));
                addDateOfAction = formatDate(new Date(selectedYear, 0, 1));
            }

            // Set form field values
            $('#floatingPeriod').val(addPeriod);
            $('#floatingPeriodEnd').val(addPeriodEnd);
            $('#floatingDateOfAction').val(addDateOfAction);

            // Save the state
            addLeaveDataRecordState = {
                period: addPeriod,
                periodEnd: addPeriodEnd,
                dateOfAction: addDateOfAction,
            };
        });

        $('.addNewLeaveDataRecord').click(function () {
            // addPeriod = addPeriodEnd = formatDate(new Date(selectedYear, 0, 1));
            // addDateOfAction = formatDate(new Date(selectedYear, 0, 1));

            addPeriod = $(this).data('period-date');
            addPeriodEnd = $(this).data('period-end-date');
            addDateOfAction = $(this).data('date-of-action');

            // Set form field values
            $('#floatingNewPeriod').val(addPeriod);
            $('#floatingNewPeriodEnd').val(addPeriodEnd);
            $('#floatingNewDateOfAction').val(addDateOfAction);

            // Save the state
            addLeaveDataRecordState = {
                period: addPeriod,
                periodEnd: addPeriodEnd,
                dateOfAction: addDateOfAction,
            };
        });

        $('.editLeaveDataRecord').click(function () {
            // Set form field values
            var editLeaveDataId = $(this).data('leavedata-id');
            var editPeriodStart = $(this).data('period-start');
            var editPeriodEnd = $(this).data('period-end');
            var editParticularType = $(this).data('particular-type');
            var editParticularLabel = $(this).data('particular-label');
            var editInputDay = $(this).data('input-day');
            var editInputHour = $(this).data('input-hour');
            var editInputMinute = $(this).data('input-minute');
            var editDateOfAction = $(this).data('date-of-action');

            // Set form field values
            $('#floatingEditLeaveDataFormId').val(editLeaveDataId);
            $('#floatingEditPeriod').val(editPeriodStart);
            $('#floatingEditPeriodEnd').val(editPeriodEnd);
            $('#floatingEditParticularType').val(editParticularType);
            $('#floatingEditParticularLabel').val(editParticularLabel);
            $('#floatingEditDayInput').val(editInputDay);
            $('#floatingEditHourInput').val(editInputHour);
            $('#floatingEditMinuteInput').val(editInputMinute);
            $('#floatingEditDateOfAction').val(editDateOfAction);

            // Save the state
            editLeaveDataRecordState = {
                leaveDataId: editLeaveDataId,
                periodStart: editPeriodStart,
                periodEnd: editPeriodEnd,
                particularType: editParticularType,
                particularLabel: editParticularLabel,
                inputDay: editInputDay,
                inputHour: editInputHour,
                inputMinute: editInputMinute,
                dateOfAction: editDateOfAction,
            };
        });

        function setEditDataFromState() {
            // Set form field values from the editLeaveDataRecordState object
            $('#floatingEditPeriod').val(editLeaveDataRecordState.periodStart);
            $('#floatingEditPeriodEnd').val(editLeaveDataRecordState.periodEnd);
            $('#floatingEditParticularType').val(editLeaveDataRecordState.particularType);
            $('#floatingEditParticularLabel').val(editLeaveDataRecordState.particularLabel);
            $('#floatingEditDayInput').val(editLeaveDataRecordState.inputDay);
            $('#floatingEditHourInput').val(editLeaveDataRecordState.inputHour);
            $('#floatingEditMinuteInput').val(editLeaveDataRecordState.inputMinute);
            $('#floatingEditDateOfAction').val(editLeaveDataRecordState.dateOfAction);
        }

        $('.clearEditLeaveDataInputs').click(function () {
            // Reset form fields to their initial values
            $(":input:not(:submit, :hidden)").val('');
            $("select").prop('selectedIndex', 0);
            setEditDataFromState();
        });

        // Function to set data based on the saved state
        function setAddDataFromState() {
            if (addLeaveDataRecordState) {
                // Set form field values based on the saved state
                $('#floatingPeriod').val(addLeaveDataRecordState.period);
                $('#floatingPeriodEnd').val(addLeaveDataRecordState.periodEnd);
                $('#floatingDateOfAction').val(addLeaveDataRecordState.dateOfAction);
                $('#floatingNewPeriod').val(addLeaveDataRecordState.period);
                $('#floatingNewPeriodEnd').val(addLeaveDataRecordState.periodEnd);
                $('#floatingNewDateOfAction').val(addLeaveDataRecordState.dateOfAction);
            }
        }

        // Add click event handler for the Reset button
        $('.clearAddLeaveDataInputs').click(function () {
            // Reset form fields to their initial values
            $(":input:not(:submit, :hidden)").val('');
            $("select").prop('selectedIndex', 0);
            setAddDataFromState();
        });

        function setInitializeDataFromState() {
            if (addLeaveDataRecordState) {
                // Set form field values based on the saved state
                $('#floatingInitializePeriod').val(addLeaveDataRecordState.period);
                $('#floatingInitializePeriodEnd').val(addLeaveDataRecordState.periodEnd);

                $('#vacationBalanceInput').val(1.25);
                $('#vacationUnderWOPayInput').val(0);
                $('#sickBalanceInput').val(1.25);
                $('#sickUnderWOPayInput').val(0);

                $('#floatingInitializeDateOfAction').val(addLeaveDataRecordState.dateOfAction);
            }
        }

        $('.clearInitialize').click(function () {
            // Reset form fields to their initial values
            $(":input:not(:submit, :hidden)").val('');
            $("select").prop('selectedIndex', 0);
            setInitializeDataFromState();
        });

        // Edit Initial Record
        $('.editInitialRecord').click(function () {
            // Set form field values
            var editInitialPeriodStart = $(this).data('period-start');
            var editInitialPeriodEnd = $(this).data('period-end');
            var editInitialParticularLabel = $(this).data('particular-label') || "";

            // Retrieve the data and convert to floating-point number, set to 0 if null
            var editInitialVacationEarned = parseFloat($(this).data('vacation-earned')) || 0;
            var editInitialSickEarned = parseFloat($(this).data('sick-earned')) || 0;
            var editInitialVacationWithoutPay = parseFloat($(this).data('vacation-withoutpay')) || 0;
            var editInitialSickWithoutPay = parseFloat($(this).data('sick-withoutpay')) || 0;

            // Round the values to two decimal places if not zero
            editInitialVacationEarned = editInitialVacationEarned !== 0 ? editInitialVacationEarned.toFixed(2) : '0';
            editInitialSickEarned = editInitialSickEarned !== 0 ? editInitialSickEarned.toFixed(2) : '0';
            editInitialVacationWithoutPay = editInitialVacationWithoutPay !== 0 ? editInitialVacationWithoutPay.toFixed(2) : '0';
            editInitialSickWithoutPay = editInitialSickWithoutPay !== 0 ? editInitialSickWithoutPay.toFixed(2) : '0';

            var editInitialDateOfAction = $(this).data('date-of-action');

            // Set form field values
            $('#floatingEditInitializePeriod').val(editInitialPeriodStart);
            $('#floatingEditInitializePeriodEnd').val(editInitialPeriodEnd);
            $('#floatingEditInitialParticularLabel').val(editInitialParticularLabel);
            $('#editVacationBalanceInput').val(editInitialVacationEarned);
            $('#editSickBalanceInput').val(editInitialSickEarned);
            $('#editVacationUnderWOPayInput').val(editInitialVacationWithoutPay);
            $('#editSickUnderWOPayInput').val(editInitialSickWithoutPay);
            $('#floatingEditInitializeDateOfAction').val(editInitialDateOfAction);

            // Save the state
            editInitialLeaveDataRecordState = {
                editInitialPeriodStart: editInitialPeriodStart,
                editInitialPeriodEnd: editInitialPeriodEnd,
                editInitialParticularLabel: editInitialParticularLabel,
                editInitialVacationEarned: editInitialVacationEarned,
                editInitialSickEarned: editInitialSickEarned,
                editInitialVacationWithoutPay: editInitialVacationWithoutPay,
                editInitialSickWithoutPay: editInitialSickWithoutPay,
                editInitialDateOfAction: editInitialDateOfAction
            };
        });

        function setEditInitialDataFromState() {
            // Set form field values from the editInitialLeaveDataRecordState object
            $('#floatingEditInitializePeriod').val(editInitialLeaveDataRecordState.editInitialPeriodStart);
            $('#floatingEditInitializePeriodEnd').val(editInitialLeaveDataRecordState.editInitialPeriodEnd);
            $('#floatingEditInitialParticularLabel').val(editInitialLeaveDataRecordState.editInitialParticularLabel);
            $('#editVacationBalanceInput').val(editInitialLeaveDataRecordState.editInitialVacationEarned);
            $('#editSickBalanceInput').val(editInitialLeaveDataRecordState.editInitialSickEarned);
            $('#editVacationUnderWOPayInput').val(editInitialLeaveDataRecordState.editInitialVacationWithoutPay);
            $('#editSickUnderWOPayInput').val(editInitialLeaveDataRecordState.editInitialSickWithoutPay);
            $('#floatingEditInitializeDateOfAction').val(editInitialLeaveDataRecordState.editInitialDateOfAction);
        }

        $('.resetEditInitialRecord').click(function () {
            // Reset form fields to their initial values
            $(":input:not(:submit, :hidden)").val('');
            $("select").prop('selectedIndex', 0);
            setEditInitialDataFromState();
        });

    });

});