jQuery(document).ready(function($) {
	'use strict';
	wdt_display_on();
	wdt_optional_message();
    $("#wdt_display_on").change(function () {
        wdt_display_on();
    });
    $('#wdt_enable_optional_msg').change(function() {
    	wdt_optional_message();
    });
    
    function wdt_display_on() {
    	if ($("#wdt_display_on").val() == "cart") {
	        $("#wdt_cart_position").parents('tr').show();
	        $("#wdt_checkout_position").parents('tr').hide();
	    }
	    else if ($("#wdt_display_on").val() == "checkout") {
	        $("#wdt_cart_position").parents('tr').hide();
	        $("#wdt_checkout_position").parents('tr').show();
	    }
	    else {
	        $("#wdt_cart_position").parents('tr').show();
	        $("#wdt_checkout_position").parents('tr').show();
	    }
    }

    function wdt_optional_message() {
    	console.log('Called here');
		if ($('#wdt_enable_optional_msg').is(':checked')) {
			$('#wdt_enable_optional_msg').parents('tr').nextAll('tr').show();
		} else {
			$('#wdt_enable_optional_msg').parents('tr').nextAll('tr').hide();
		}
    }
});