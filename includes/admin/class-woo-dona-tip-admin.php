<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Admin Class
 *
 * Manage Admin Panel Class
 *
 * @package WooCommerce Donation and Tip
 * @since 1.0.0
 */
class WDT_Admin {

	/**
	 * Settings tab
	 *
	 * Add donation/tip settings in WooCommerce settings
	 *
	 * @package WooCommerce Donation and Tip
	 * @since 1.0.0
	 */
	function wdt_admin_settings_tab($settings) {
		// Add settings in array
        $settings[] = include( WOO_DONA_TIP_ADMIN_DIR . '/class-woo-dona-tip-admin-settings-tabs.php' );

        return $settings; // Return
	}

	/**
	 * Admin Menu for donation and tip reports
	 *
	 * Manage Admin class for donation and tip reports
	 *
	 * @package WooCommerce Donation and Tip
	 * @since 1.0.1
	 */
	function wdt_admin_menu() {

		// Add menu page
        $donatip_page = add_submenu_page(WOO_DONA_TIP_MAIN_MENU_NAME, esc_html__('Donation and Tip', 'woodonatip'), esc_html__('Donation and Tip', 'woodonatip'), 'read', 'woodonatip', array($this, 'woodonatip_page'));
	}

	/**
	 * Admin Menu for reports
	 *
	 * Manage Admin Panel Class
	 *
	 * @package WooCommerce Donation and Tip
	 * @since 1.0.1
	 */
	function woodonatip_page() {

		include_once(WOO_DONA_TIP_INC_DIR . '/woo-dona-tip-page.php' );
	}
	
	/**
     * Adding Hooks
     *
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    function add_hooks() {

    	//add new admin menu page
		add_action('admin_menu', array($this, 'wdt_admin_menu'));

        // Add filter for adding plugin settings
        add_filter('woocommerce_get_settings_pages', array($this, 'wdt_admin_settings_tab'));
    }

}

?>
