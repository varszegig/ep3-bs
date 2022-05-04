(function() {

    var urlProvider;
    var tagProvider;

    $(document).ready(function() {
        
        var repeat = $("#ef-repeat");
        var dateEnd = $("#ef-repeat-end");

        if (repeat.val() === "0") {
            disableFormElement(dateEnd);
        } else {
            enableFormElement(dateEnd);
        }

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
