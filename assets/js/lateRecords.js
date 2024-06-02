$('.uploadMonthlyLateRecord').click(function () {
    var monthYearName = $(this).data('month-year');

    $('#floatingEditMonthlyLateRecord').val(monthYearName);
    $('#monthYearModalLabel').text(monthYearName);
});
