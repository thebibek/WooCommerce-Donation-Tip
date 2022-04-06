jQuery(document).ready(function($) {
	'use strict';
    displayRemoveButton();
    function displayRemoveButton() {
        var amt = $('#wdt_donation_amount').val();
        if(amt > 0) {
            $("#wdt_remove_btn").show();
        } else {
            $("#wdt_remove_btn").hide();
        }
    }

    $(document.body).on( 'updated_cart_totals', function(){
        displayRemoveButton();
    });

	function add_donation(amt, msg) {
		$("#wdt_error").hide();
        $("#wdt_loader").css('visibility', 'visible');

		if(isNaN(amt) || amt < 0){
        	$("#wdt_loader").css('visibility', 'hidden');
            $("#wdt_error").html(wdt_public.nonValidAmt).show();
            return false;
        }

		// Prepare data for post request
        var data = {
            action: 'wdt_add_dona_tip',
            donatip: amt,
            donatipmsg: msg
        };

        // Create POST Request
        $.post(wdt_public.ajaxurl, data, function (response) {
        	$("#wdt_loader").css('visibility', 'hidden');
            $("#wdt_donation_amount").val(amt);
            $("body").trigger('wc_update_cart');
            $("body").trigger('update_checkout');
            displayRemoveButton();
            return false;
        });
	}

	$(document).on('click', "#wdt_donate_btn", function () {
		var amt = $(this).parents('.wdt-form-field-container').find('#wdt_donation_amount').val();
		var msg = wdt_public.wdt_enable_optional_msg ? $(this).parents('.wdt-form-field-container').find('#wdt_optional_msg').val() : '';
        add_donation(amt, msg);
    });

    $(document).on('click', '.wdt-preset-donate-btn', function(el) {
		var amt = $(this).attr('preset-amt');
		var msg = wdt_public.wdt_enable_optional_msg ? $(this).parents('.wdt-form-field-container').find('#wdt_optional_msg').val() : '';
		add_donation(amt);
    });

    $(document).on('click', "#wdt_remove_btn", function () {
        add_donation(0);
    });
});