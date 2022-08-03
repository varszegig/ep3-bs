jQuery(function($) {
	$.datepicker.regional["hu"] = {
        "altFormat": "yy-mm-dd",
        "closeText": "Bezár",
        "currentText": "Ma",
        "dateFormat": "yy-mm-dd",
        "dayNames": ["Vasárnap", "Hétfő", "Kedd", "Szerda", "Csütörtök", "Péntek", "Szombat"],
        "dayNamesMin": ["Va", "Hé", "Ke", "Sze", "Cs", "Pé", "Szo"],
        "dayNamesShort": ["Va", "Hé", "Ke", "Sze", "Cs", "Pé", "Szo"],
        "firstDay": 1,
        "monthNames": ["Január", "Február", "Március", "Április", "Május", "Június", "Július", "Augusztus", "Szeptember", "Október", "November", "December"],
        "monthNamesShort": ["Jan", "Feb", "Már", "Ápr", "Máj", "Jún", "Júl", "Aug", "Szep", "Okt", "Nov", "Dec"],
        "nextText": "Következő",
        "prevText": "Előző"
    };

	$.datepicker.setDefaults($.datepicker.regional["hu"]);

    dateMatch = /^[0-9]{4}\-(0[1-9]|1[0-2])\-(0[1-9]|[1-2][0-9]|3[0-1])$/;
});
