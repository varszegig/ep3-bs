(function() {

    $(document).ready(function() {

        var userSearchInput = $("#bs-user-filter");

        userSearchInput.autocomplete({
            "minLength": 1,
            "source": userSearchInput.data("autocomplete-url")
        });

        /* Filters */

        $("#bs-filters-help").on("click", function(event) {
            event.preventDefault();

            var filtersBox = $("#bs-filters-help-box");

            if (filtersBox.length) {
                filtersBox.width($(this).closest("table").width());

                if (filtersBox.is(":visible")) {
                    filtersBox.slideUp();
                } else {
                    filtersBox.slideDown();
                }
            }
        });


    });

})();
