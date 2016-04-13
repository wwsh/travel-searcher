/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 WW Software House
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

$(function () {
    $('#start_date').datetimepicker({
        locale: 'pl',
        format: 'DD.MM.YYYY'
    });
    $('#end_date').datetimepicker({
        locale: 'pl',
        format: 'DD.MM.YYYY',
        useCurrent: false //Important! See issue #1075
    });

    var setupEndDatePicker = function (date) {
        var newDate = moment(date)
            .add($('#duration option:selected').val(), 'days');
        $('#end_date').data("DateTimePicker")
            .date(newDate);

        $('#end_date').data("DateTimePicker").minDate(newDate);
    };

    $("#start_date").on("dp.change", function (e) {
        setupEndDatePicker(e.date);
    });

    $('#duration select').on('change', function () {
        setupEndDatePicker($('#start_date').data("DateTimePicker").date());
    });

    // lock endDate calendar at startup where required
    var startDate = $('#start_date').data("DateTimePicker").date();
    if (startDate) {
        var minDate = moment(startDate)
            .add($('#duration option:selected').val(), 'days');
        $('#end_date').data("DateTimePicker").minDate(minDate);
    }
});