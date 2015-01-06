/* 
 *
 *
 */

jQuery(function($) {

		// LOCALIZATION
		$.timepicker.regional['am'] = {
            timeOnlyTitle: localization.timeOnlyTitle,
            timeText: localization.timeText,
            hourText: localization.hourText,
            minuteText: localization.minuteText,
            secondText: localization.secondText,
            millisecText: localization.millisecText,
            timezoneText: localization.timezoneText,
            currentText: localization.currentText,
            closeText: localization.closeText,
            timeFormat: localization.timeFormat,
            amNames: [localization.amNames, localization.amNamesShort],
            pmNames: [localization.pmNames, localization.pmNamesShort],
            isRTL: false
        }
        $.timepicker.setDefaults($.timepicker.regional['am']);
        
        $.datepicker.regional['am'] = {
            clearText: localization.clearText , 
            clearStatus: localization.clearStatus,
            closeText: localization.closeText, closeStatus: localization.closeStatus,
            prevText: localization.prevText, 
			prevStatus: localization.prevStatus,
            nextText: localization.nextText, 
			nextStatus: localization.nextStatus,
            currentText: localization.currentText, 
			currentStatus: localization.currentStatus,
            monthNames: [localization.january,
				localization.february,
				localization.march,
				localization.april,
				localization.may,
				localization.june,
				localization.july,
				localization.august,
				localization.september,
				localization.october,
				localization.november,
				localization.december],
            monthNamesShort: [localization.januaryShort,
				localization.februaryShort,
				localization.marchShort,
				localization.aprilShort,
				localization.mayShort,
				localization.juneShort,
				localization.julyShort,
				localization.augustShort,
				localization.septemberShort,
				localization.octoberShort,
				localization.novemberShort,
				localization.decemberShort],
            monthStatus: localization.monthStatus, 
			yearStatus: localization.yearStatus,
            weekHeader: localization.weekHeader, 
			weekStatus: localization.weekStatus,
            dayNames: [localization.dayNameFullSun,
				localization.dayNameFullMon,
				localization.dayNameFullTue,
				localization.dayNameFullWed,
				localization.dayNameFullThu,
				localization.dayNameFullFri,
				localization.dayNameFullSat],
            dayNamesShort: [localization.dayNameShortSun,
				localization.dayNameShortMon,
				localization.dayNameShortTue,
				localization.dayNameShortWed,
				localization.dayNameShortThu,
				localization.dayNameShortFri,
				localization.dayNameShortSat],
            dayNamesMin: [localization.dayNameMinSun,
				localization.dayNameMinMon,
				localization.dayNameMinTue,
				localization.dayNameMinWed,
				localization.dayNameMinThu,
				localization.dayNameMinFri,
				localization.dayNameMinSat],
            dayStatus: localization.dayStatus, 
			dateStatus: localization.dateStatus,
            dateFormat: localization.dateFormat, firstDay: 0, 
            initStatus: localization.initStatus, 
			isRTL: localization.isRTL
        };
        $.datepicker.setDefaults($.datepicker.regional['am']);

        // REPEAT INPUTS
        jQuery('#am_recurrent').click(function() {
            jQuery('#am_recurrent_fields')[this.checked ? "show" : "hide"]();
        });

        // DATETIME PICKERS
        var startDateTextBox = $('#am_startdate');
        var endDateTextBox = $('#am_enddate');

        startDateTextBox.datetimepicker({
                stepMinute: parseInt(localization.minuteStep),
                onClose: function(dateText, inst) {
                        if (endDateTextBox.val() != '') {
                                var testStartDate = startDateTextBox.datetimepicker('getDate');
                                var testEndDate = endDateTextBox.datetimepicker('getDate');
                                if (testStartDate > testEndDate)
                                        endDateTextBox.datetimepicker('setDate', testStartDate);
                        }
                        else {
                                endDateTextBox.val(dateText);
                        }
                },
                onSelect: function (selectedDateTime){
                        endDateTextBox.datetimepicker('option', 'minDate', startDateTextBox.datetimepicker('getDate') );
                }
        });
        endDateTextBox.datetimepicker({ 
                stepMinute: parseInt(localization.minuteStep),
                onClose: function(dateText, inst) {
                        if (startDateTextBox.val() != '') {
                                var testStartDate = startDateTextBox.datetimepicker('getDate');
                                var testEndDate = endDateTextBox.datetimepicker('getDate');
                                if (testStartDate > testEndDate)
                                        startDateTextBox.datetimepicker('setDate', testEndDate);
                        }
                        else {
                                startDateTextBox.val(dateText);
                        }
                },
                onSelect: function (selectedDateTime){
                        startDateTextBox.datetimepicker('option', 'maxDate', endDateTextBox.datetimepicker('getDate') );
                }
        });
		
        
});




