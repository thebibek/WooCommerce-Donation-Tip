<?php

/**
 * WooCommerce Donation and Tip Settings
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WC_Settings_Donation_Tip', false)) :
    class WC_Settings_Donation_Tip extends WC_Settings_Page {

        /**
         * Constructor.
         */
        public function __construct() {
            $this->id = 'wdt';
            $this->label = __('Donation/Tip', 'woodonatip');

            parent::__construct();
        }

        /**
		 * Handles to add sections for Settings tab
		 * 
		 * @package WooCommerce Donation and Tip
		 * @since 1.0.7
		 */
		public function get_sections() {
			// Create array
			$sections = array(
				''          => esc_html__( 'General Settings', 'woodonatip' ),
				'wdt_form'  => esc_html__( 'Donation Form Settings', 'woodonatip' )
			);
			$sections = apply_filters( 'wdt_setting_sections', $sections );
			return $sections;
		}

        /**
         * Get settings array.
         *
         * @return array
         */
        public function get_settings() {
        	// Get global variable
			global $current_section;

            $wdt_display_on = array(
                'cart' => __('Cart Page', 'woodonatip'),
                'checkout' => __('Checkout Page', 'woodonatip'),
                'both' => __('Cart and Checkout Page', 'woodonatip')
            );

            $wdt_cart_position = array(
                'woocommerce_before_cart_table' => __('Before Cart Table', 'woodonatip'),
                'woocommerce_after_cart_table' => __('After Cart Table', 'woodonatip'),
                'woocommerce_cart_coupon' => __('After Coupon Form', 'woodonatip'),
                'woocommerce_after_cart_contents' => __('After Content', 'woodonatip'),
                'woocommerce_cart_collaterals' => __('Cart Collaterals', 'woodonatip'),
                'woocommerce_before_cart_totals' => __('Before Cart Total', 'woodonatip')
            );

            $wdt_checkout_position = array(
            	'woocommerce_before_checkout_form' => __('Before Checkout Form', 'woodonatip'),
            	'woocommerce_checkout_before_customer_details' => __('Before Customer Detail', 'woodonatip'),
            	'woocommerce_before_checkout_billing_form' => __('Before Billing Detail', 'woodonatip'),
            	'woocommerce_after_checkout_billing_form' => __('After Billing Detail', 'woodonatip'),
            	'woocommerce_before_checkout_shipping_form' => __('Before Shiiping Detail', 'woodonatip'),
            	'woocommerce_before_order_notes' => __('Before Order Notes', 'woodonatip'),
            	'woocommerce_after_order_notes' => __('After Order Notes', 'woodonatip'),
            	'woocommerce_checkout_before_order_review' => __('Before Order Review', 'woodonatip'),
            );

            if('wdt_form' == $current_section) {
            	$settings = apply_filters('woocommerce_wdt_form_settings', array(
            		array(
	                    'title' => __('Donation Form Settings', 'woodonatip'),
	                    'type' => 'title',
	                    'desc' => __('Here you can configure your donation form look and feel.', 'woodonatip'),
	                    'id' => 'wdt_donation_form_settings'
	                ),
	                array(
	                    'title' => __('Donation/Tip Title', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Enter the title.', 'woodonatip') . '</p>',
	                    'id' => 'wdt_title',
	                    'default' => __('Donate for a noble cause', 'woodonatip'),
	                    'type' => 'text',
	                ),
	                array(
	                    'title' => __('Donation/Tip Message', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Enter the message.', 'woodonatip') . '</p>',
	                    'id' => 'wdt_message',
	                    'default' => __('Donation Message', 'woodonatip'),
	                    'type' => 'text',
	                ),
            		array(
	                    'title' => __('Donation/Tip Button Label', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Enter the donation button label.', 'woodonatip') . '</p>',
	                    'id' => 'wdt_button_label',
	                    'default' => __('Donate', 'woodonatip'),
	                    'type' => 'text',
	                ),
	                array(
	                    'title' => __('Remove Donation/Tip Button Label', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Enter remove button label.', 'woodonatip') . '</p>',
	                    'id' => 'wdt_remove_button_label',
	                    'default' => __('Remove', 'woodonatip'),
	                    'type' => 'text',
	                ),
	                array(
						'type'      => 'sectionend',
						'id'        => 'wdt_donation_form_settings',
					),
	                array(
						'name' => __( 'Donation Amount field', 'woodonatip' ),
						'type' => 'title',
						'id'   => 'wdt_donation_amt_field_settings',
					),
					array(
	                	'title' => __('Title', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Enter title for donation field.', 'woodonatip') . '</p>',
	                    'id' => 'wdt_donation_field_title',
	                    'default' => __('', 'woodonatip'),
	                    'type' => 'text',
	                ),
	                array(
	                    'title' => __('Placeholder', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Enter placeholder for donation field.', 'woodonatip') . '</p>',
	                    'id' => 'wdt_donation_field_placeholder',
	                    'default' => __('Amount to Donate', 'woodonatip'),
	                    'type' => 'text',
	                ),
	                array(
	                    'title' => __('Default Donation Amount', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Enter default amount.', 'woodonatip') . '</p>',
	                    'id' => 'wdt_amount',
	                    'default' => 0,
	                    'type' => 'number',
	                ),
	                array(
	                    'title' => __('Preset Donation Amounts', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Enter the available donation amounts that your user can choose from. Seperate values with |', 'woodonatip') . '</p>',
	                    'id' => 'wdt_preset_amts',
	                    'type' => 'text',
	                ),
	                array(
						'type'      => 'sectionend',
						'id'        => 'wdt_donation_amt_field_settings',
					),
	                array(
						'name' => __( 'Optional Message field', 'woodonatip' ),
						'type' => 'title',
						'id'   => 'wdt_optional_msg_field_settings',
					),
					array(
						'title'    => __( 'Enable', 'woodonatip' ),
						'id'       => 'wdt_enable_optional_msg',
						'default'  => 'no',
						'type'     => 'checkbox',
						'desc_tip' => __('Select this checkbox to enable optional message field on the donation form.', 'woodonatip'),
					),
					array(
	                	'title' => __('Title', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Enter title for optional message field.', 'woodonatip') . '</p>',
	                    'id' => 'wdt_optn_msg_field_title',
	                    'default' => __('', 'woodonatip'),
	                    'type' => 'text',
	                ),
	                array(
	                    'title' => __('Placeholder', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Enter placeholder for optional message field.', 'woodonatip') . '</p>',
	                    'id' => 'wdt_optn_msg_field_placeholder',
	                    'type' => 'text',
	                ),
	                array(
	                    'title' => __('Default message', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Enter default message.', 'woodonatip') . '</p>',
	                    'id' => 'wdt_default_optn_msg',
	                    'type' => 'text',
	                ),
	                array(
						'type'      => 'sectionend',
						'id'        => 'wdt_optional_msg_field_settings',
					)
            	));

            	return apply_filters('woocommerce_get_settings_wdt_form', $settings);

            } else {
	            $settings = apply_filters('woocommerce_' . $this->id . '_settings', array(
	                array(
	                    'title' => __('General Settings', 'woodonatip'),
	                    'type' => 'title',
	                    'desc' => __('Here you can configure various options for Woocommerce Donation and Tip.', 'woodonatip'),
	                    'id' => 'wcd_general_settings'
	                ),
	                array(
	                    'title' => __('Delete Data', 'woodonatip'),
	                    'id' => 'wdt_delete_data',
	                    'default' => 'no',
	                    'desc_tip' => __('If you don\'t want to use the plugin on your site anymore, you can check the delete options box. This makes sure, that all data gets deleted from the database when you deactivate the plugin.', 'woodonatip'),
	                    'type' => 'checkbox'
	                ),
	                array(
	                    'title' => __('Enable', 'woodonatip'),
	                    'id' => 'wdt_enable',
	                    'default' => 'no',
	                    'desc_tip' => __('Start accepting donation/tips by ticking this checkbox.', 'woodonatip'),
	                    'type' => 'checkbox'
	                ),
	                array(
	                    'id' => 'wdt_display_on',
	                    'name' => __('Display form on', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Choose whether you want to enable the donation/tip form on Cart / Checkout or both page.', 'woodonatip') . '</p>',
	                    'type' => 'select',
	                    'class' => 'wc-enhanced-select',
	                    'options' => $wdt_display_on
	                ),
	                array(
	                    'id' => 'wdt_cart_position',
	                    'name' => __('Cart Page Position', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Here you can select the position of the form on cart page.', 'woodonatip') . '</p>',
	                    'type' => 'select',
	                    'class' => 'wc-enhanced-select',
	                    'options' => $wdt_cart_position
	                ),
	                array(
	                    'id' => 'wdt_checkout_position',
	                    'name' => __('Checkout Page Position', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Here you can select the position of the form on checkout page.', 'woodonatip') . '</p>',
	                    'type' => 'select',
	                    'class' => 'wc-enhanced-select',
	                    'options' => $wdt_checkout_position
	                ),
	                array(
	                    'title' => __('Thankyou message', 'woodonatip'),
	                    'desc' => '<p class="description">' . __('Enter thankyou message for the donation or tip.', 'woodonatip') . ' <br/>' . __('This message will be only displayed when user adds donation/tip and the order is in either processing or completed status. Available shortcodes are:-', 'woodonatip') . '<br/><code>{wdt_donatip_amt}</code>' . __(' - Displays donation/tip amount', 'woocatdisc') . '<br/><code>{wdt_donatip_msg}</code>' . __(' - Displays donation/tip message', 'woocatdisc') . '</p>',
	                    'id' => 'wdt_thankyou_msg',
	                    'type' => 'text',
	                ),
	                array(
						'type'      => 'sectionend',
						'id'        => 'woocommerce_' . $this->id . '_settings',
					)
	            ));

	            return apply_filters('woocommerce_get_settings_' . $this->id, $settings);
            }
        }

    }

    endif;

return new WC_Settings_Donation_Tip();
