(function() {

    var urlProvider;
    var tagProvider;

    $(document).ready(function() {
        
        var repeat = $("#ef-repeat");
        var dateEnd = $("#ef-repeat-end");
        var minInterval = $("#ef-min-interval").data("min-interval");
        var minTime = $("#ef-min-time").data("min-time");
        var maxTime = $("#ef-max-time").data("max-time");        

        if (repeat.val() === "0") {
            disableFormElement(dateEnd);
        } else {
            enableFormElement(dateEnd);
        }

        initTimepicker(minInterval, minTime, maxTime);

        $("#ef-repeat").on("change", updateForm);
        const eventid = document.URL.split("event/edit/")[1];

    });

    function updateForm()
    {

        /* Datepicker on demand for date end */

        var dateEnd = $("#ef-repeat-end");
        var repeat = $("#ef-repeat");

        if (repeat.val() === "0") {
            disableFormElement(dateEnd);
        } else {
            enableFormElement(dateEnd);
        }

    }

    function disableFormElement(element)
    {
        if (typeof element == "string") {
            element = $(element);
        }

        element.attr("disabled", "disabled");
        element.css("opacity", 0.5);
    }

    function enableFormElement(element)
    {
        if (typeof element == "string") {
            element = $(element);
        }

        element.removeAttr("disabled");
        element.css("opacity", 1.0);
    }

})();
