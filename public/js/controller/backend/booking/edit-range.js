(function() {

    $(document).ready(function () {

        $("#bf-date-start, #bf-date-end").datepicker();
        initTimepicker(minInterval, minTime, maxTime);

    });

})();