(function() {

    $(function() {
        $("#accordion-controller").click(function() {
            const accordionContent = $("#accordion-content");
            if (accordionContent.hasClass("hidden")) {
                accordionContent.removeClass("hidden");
                $("#accordion-icon").addClass("rotate-90");
            } else {
                accordionContent.addClass("hidden");
                $("#accordion-icon").removeClass("rotate-90");
            }
        })
    });

})();