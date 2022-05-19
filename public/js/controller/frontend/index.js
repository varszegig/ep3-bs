(function() {

    
    $(document).ready(function() {

        $("#calendar-toolbar-datepicker-submit").hide();

        $("#group-select").change(function () {
            var optionValue = $(this).val();
            var url = window.location.origin;
            window.location = url + '?group-select=' + optionValue;
        });

        if (cookieExists('ep3-bs-group-select')) {
            const selectedGroup = document.cookie.split("; ").find(cookie=>cookie.startsWith("ep3-bs-group-select=")).split("=")[1];
        
            if (selectedGroup && $('#group-select').length) {
                $('#group-select').val(selectedGroup);
            }
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

function cookieExists(name) {
    var cks = document.cookie.split(';');
    for(i = 0; i < cks.length; i++)
      if (cks[i].split('=')[0].trim() == name) return true;
}