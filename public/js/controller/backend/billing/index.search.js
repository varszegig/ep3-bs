(function() {

    $(document).ready(function() {

        /* Autocomplete */

        var searchInput = $("#bbsf-name");

        searchInput.autocomplete({
            "minLength": 1,
            "source": searchInput.data("autocomplete-url")
        });

        /* Filters */

        $("#bbsf-filters-link").on("click", function(event) {
            event.preventDefault();

            var filtersBox = $("#bbsf-filters-box");

            if (filtersBox.length) {
                filtersBox.width($(this).closest("table").width());

                if (filtersBox.is(":visible")) {
                    filtersBox.slideUp();
                } else {
                    filtersBox.slideDown();
                }
            }
        });

        $(".bbsf-filter-snippet").on("click", function(event) {
            event.preventDefault();

            var snippet = $(this).find("code").text();

            searchInput.val(searchInput.val() + " " + snippet);
        });

    });

})();