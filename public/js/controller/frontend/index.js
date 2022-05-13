(function() {

    
    $(document).ready(function() {

        $("#calendar-toolbar-datepicker-submit").hide();

        $("#group-select").change(function () {
            var optionValue = $(this).val();
            var url = window.location.origin;
            window.location = url + '?group-select=' + optionValue;
            // Save value in localstorage
            $.cookie("group-select", $(this).val());            
        });

        if ("group-select" in localStorage && $('#group-select').length) {
            $('#group-select').val($.cookie("group-select"));
         }

        /* Beautify messages panel */

        var messagesPanel = $(".messages-panel");
        var calendar = $("#calendar");

        if (messagesPanel.length && calendar.length) {
            messagesPanel.css({
                "position": "absolute",
                "z-index": 2048,
                "min-width": 384
            }).position({
                "my": "center top+24",
                "at": "center top",
                "of": calendar
            }).delay(5000).fadeOut(3000, function() {
                $(this).remove();
            });

            $(document).trigger("updateLayout");
        }

    });

})();