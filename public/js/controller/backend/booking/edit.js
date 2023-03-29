(function() {

    var urlProvider;
    var tagProvider;

    $(document).ready(function() {

        urlProvider = $("#bf-url-provider");
        tagProvider = $("#bf-tag-provider");
        minInterval = $("#bf-min-interval").data("min-interval");
        minTime = $("#bf-min-time").data("min-time");
        maxTime = $("#bf-max-time").data("max-time");

        /* Autocomplete for user */

        var userInput = $("#bf-user");

        userInput.autocomplete({
            "minLength": 1,
            "source": urlProvider.data("user-autocomplete-url")
        });

        /* Datepicker */

        $("#bf-date-start, #bf-date-end").datepicker();
        initTimepicker(minInterval, minTime, maxTime);

        /* Update Form */

        $("#bf-repeat").on("change", updateForm);

        updateForm();

        /* Exclusive edit fields */

        var $editUser = $('#bf input[name="bf-edit-user"]');
        var $editBills = $('#bf input[name="bf-edit-bills"]');

        if ($editUser.length && $editBills.length) {
            $editUser.on('change', function() {
                $editBills.prop('checked', false);
            });

            $editBills.on('change', function() {
                $editUser.prop('checked', false);
            });
        }

        /* Enable form on submit */

        var formSubmit = $("#bf-submit");
        var form = formSubmit.closest("form");

        form.on("submit", function() {
            form.find(":disabled").removeAttr("disabled");
        });

    });

    function updateForm()
    {

        /* Datepicker on demand for date end */

        var dateEnd = $("#bf-date-end");
        var payment = $("#bf-payment");
        var repeat = $("#bf-repeat");

        if (repeat.val() === "0") {
            disableFormElement(dateEnd);
            disableFormElement(payment);
        } else {
            enableFormElement(dateEnd);
            enableFormElement(payment);
        }

        /* Lock specific fields in edit mode */

        var rid = $("#bf-rid");

        if (rid.val()) {
            disableFormElement(repeat);

            var editMode = tagProvider.data("edit-mode-tag");
            console.log(payment.val());

            if (editMode == "booking") {
                disableFormElement("#bf-time-start");
                disableFormElement("#bf-time-end");
                disableFormElement("#bf-date-start");
                disableFormElement("#bf-date-end");
                disableFormElement("#bf-payment");
            } else if (editMode == "reservation") {
                disableFormElement("#bf-user");
                if (payment.val() == "0") {
                    disableFormElement("#bf-status-billing");
                    disableFormElement("#bf-sid");
                    disableFormElement("#bf-notes");
                }
                disableFormElement("#bf-quantity");
                disableFormElement("#bf-payment");
                disableFormElement("#bf-date-end");
            }
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
