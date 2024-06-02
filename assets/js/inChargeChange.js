
function toggleInputState(checkbox, input, select, originalInputValue, originalSelectValue) {
    if (checkbox.checked) {
        input.readOnly = true;
        select.disabled = false;

        input.value = originalInputValue;
        select.value = originalSelectValue;
    } else {
        input.readOnly = false;
        select.disabled = true;
    }
}


document.addEventListener('DOMContentLoaded', function () {
    var hrCheckbox = document.getElementsByName('isFromDataHR')[0];
    var hrInput = document.getElementsByName('nameOfInCharge')[0];
    var hrSelect = document.getElementsByName('selectedAuthorizedUser')[0];

    var originalInputValueHR = hrInput.value;
    var originalSelectValueHR = hrSelect.value;

    toggleInputState(hrCheckbox, hrInput, hrSelect, originalInputValueHR, originalSelectValueHR);

    hrCheckbox.addEventListener('change', function () {
        toggleInputState(hrCheckbox, hrInput, hrSelect, originalInputValueHR, originalSelectValueHR);
    });
});


document.addEventListener('DOMContentLoaded', function () {
    var mayorCheckbox = document.getElementsByName('isFromDataMayor')[0];
    var mayorInput = document.getElementsByName('nameOfInCharge')[1];
    var mayorSelect = document.getElementsByName('selectedAuthorizedUser')[1];


    var originalInputValueMayor = mayorInput.value;
    var originalSelectValueMayor = mayorSelect.value;

    toggleInputState(mayorCheckbox, mayorInput, mayorSelect, originalInputValueMayor, originalSelectValueMayor);

    mayorCheckbox.addEventListener('change', function () {
        toggleInputState(mayorCheckbox, mayorInput, mayorSelect, originalInputValueMayor, originalSelectValueMayor);
    });
});