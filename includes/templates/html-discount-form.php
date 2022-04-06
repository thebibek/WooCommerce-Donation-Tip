<div class="wdt-dona-tip-container">
    <p class="wdt-dona-tip-message">
    	<strong><?php esc_html_e($wdt_message); ?></strong>
    </p>
    <div class="wdt-form-field-container">
    	<div>
    		<label class="wdt-labels" for="wdt_donation_amount"><b><?php esc_html_e($wdt_donation_field_title) ?></b></label>
	        <input type="number" value="<?php esc_html_e($wdt_amount); ?>" class="input-text wdt-donation-amount" 
				id="wdt_donation_amount" name="wdt-donation-amount" placeholder="<?php esc_html_e($wdt_donation_field_placeholder); ?>">
	    </div>
	    <?php if($wdt_enable_optional_msg === 'yes') : ?>
	    <div class="wdt-optn-msg-container">
	    	<label class="wdt-labels" for="wdt_optional_msg"><b><?php esc_html_e($wdt_optn_msg_field_title) ?></b></label>
	    	<input type="text" value="<?php esc_html_e($wdt_default_optn_msg); ?>" class="input-text wdt-optional-msg"
	    		id="wdt_optional_msg" name="wdt-optional-msg" placeholder="<?php esc_html_e($wdt_optn_msg_field_placeholder); ?>">
	    </div>
	    <?php endif; ?>
        <input type="button" value="<?php esc_html_e($wdt_button_label); ?>" class="button wdt-donate-btn" id="wdt_donate_btn" name="wdt-donate-btn">
            <input type="button" value="<?php esc_html_e($wdt_remove_button_label); ?>" class="button wdt-remove-btn" id="wdt_remove_btn" name="wdt-remove-btn">
        <span class="wdt-loader" id="wdt_loader"><img src="<?php echo WOO_DONA_TIP_INC_URL.'/images/loader.gif'; ?>" alt="" /></span>
    </div>
    <div>
    	<?php if(!!$total_preset_count) : ?>
	    <div class="wdt-preset-values-container">
	    	<?php foreach ($wdt_preset_arr as $preset_amt) : ?>
	    		<input type="button" value="<?php esc_html_e(html_entity_decode(strip_tags(wc_price($preset_amt)))); ?>" class="button wdt-preset-donate-btn" 
					id="wdt_preset_donate_btn_<?php echo $preset_amt; ?>" name="wdt-preset-donate-btn" preset-amt="<?php esc_html_e($preset_amt); ?>">
	    	<?php endforeach; ?>
	    </div>
	    <?php endif; ?>
    </div>
    <div class="wdt-error" id="wdt_error"></div>
</div>