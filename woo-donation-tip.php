<?php

/**
 * Plugin Name: WooCommerce Donation and Tip
 * Description: With WooCommerce Donation and Tips, you can start collection donation and tips from your clients.
 * Version: 1.1.1
 * Author: WildProgrammers
 * Author URI: http://wildprogrammers.com/
 * Text Domain: woodonatip
 * Domain Path: languages
 * 
 * WC tested up to: 4.9.2
 * 
 * @package WooCommerce Donation and Tip
 * @category Core
 * @author WildProgrammers
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Basic plugin definitions
 * 
 * @package WooCommerce Donation and Tip
 * @since 1.0.0
 */
if (!defined('WOO_DONA_TIP_PLUGIN_VERSION')) {
    define('WOO_DONA_TIP_PLUGIN_VERSION', '1.1.1'); //Plugin version number
}
if (!defined('WOO_DONA_TIP_DIR')) {
    define('WOO_DONA_TIP_DIR', dirname(__FILE__)); // plugin dir
}
if (!defined('WOO_DONA_TIP_URL')) {
    define('WOO_DONA_TIP_URL', plugin_dir_url(__FILE__)); // plugin url
}
if (!defined('WOO_DONA_TIP_INC_DIR')) {
    define('WOO_DONA_TIP_INC_DIR', WOO_DONA_TIP_DIR . '/includes'); // Plugin include dir
}
if (!defined('WOO_DONA_TIP_INC_URL')) {
    define('WOO_DONA_TIP_INC_URL', WOO_DONA_TIP_URL . 'includes'); // Plugin include url
}
if (!defined('WOO_DONA_TIP_ADMIN_DIR')) {
    define('WOO_DONA_TIP_ADMIN_DIR', WOO_DONA_TIP_INC_DIR . '/admin'); // plugin admin dir
}
if (!defined('WOO_DONA_TIP_PLUGIN_BASENAME')) {
    define('WOO_DONA_TIP_PLUGIN_BASENAME', basename(WOO_DONA_TIP_DIR)); //Plugin base name
}
if (!defined('WOO_DONA_TIP_META_PREFIX')) {
    define('WOO_DONA_TIP_META_PREFIX', '_wdt_'); // meta data box prefix
}
if(!defined('WOO_DONA_TIP_POST_TYPE')) {
	define('WOO_DONA_TIP_POST_TYPE', 'woodonatip'); // Post type
}
if(!defined('WOO_DONA_TIP_MAIN_MENU_NAME')) {
	define('WOO_DONA_TIP_MAIN_MENU_NAME', 'woocommerce'); // Post type
}

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package WooCommerce Donation and Tip
 * @since 1.0.0
 */
register_activation_hook(__FILE__, 'wdt_install');

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @package WooCommerce Donation and Tip
 * @since 1.0.0
 */
function wdt_install() {
    $wdt_install_version = get_option('wdt_install_version');

    if (empty($wdt_install_version)) {

        //Items need to be saved on installation
        $options = array(
            'wdt_delete_data' => 'no',
            'wdt_enable' => 'no',
            'wdt_display_on' => 'cart',
            'wdt_cart_position' => 'woocommerce_before_cart_table',
            'wdt_checkout_position' => 'woocommerce_before_checkout_form',
            'wdt_button_label' => 'Donate',
            'wdt_remove_button_label' => 'Remove',
            'wdt_title' => 'Donate for a noble cause',
            'wdt_message' => 'Donation Message',
            'wdt_amount' => 0
        );

        foreach ($options as $option_key => $option_val) {

            update_option($option_key, $option_val);
        }

        update_option('wdt_install_version', '1.0.0');
    }

    $wdt_install_version = get_option('wdt_install_version');

    if($wdt_install_version === '1.0.0') {

    	update_option('wdt_preset_amts', '');
    	update_option('wdt_install_version', '1.0.2');
    }
    
    $wdt_install_version = get_option('wdt_install_version');

    if($wdt_install_version === '1.0.2') {

    	update_option('wdt_donation_field_placeholder', '');
    	update_option('wdt_enable_optional_msg', '');
    	update_option('wdt_optn_msg_field_placeholder', '');
    	update_option('wdt_default_optn_msg', '');
    	update_option('wdt_install_version', '1.0.3');
    }
    
    $wdt_install_version = get_option('wdt_install_version');

    if($wdt_install_version === '1.0.3') {

    	update_option('wdt_donation_field_title', '');
    	update_option('wdt_optn_msg_field_title', '');
    	update_option('wdt_install_version', '1.0.8');
    }

    $wdt_install_version = get_option('wdt_install_version');
    if($wdt_install_version === '1.0.8') {
        update_option('wdt_thankyou_msg', 'Thank you. Your {wdt_donatip_amt} donation has been received and it will be used for a noble cause.');
        update_option('wdt_install_version', '1.0.9');
    }
}

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @package WooCommerce Donation and Tip
 *  @since 1.0.0
 */
register_deactivation_hook(__FILE__, 'wdt_uninstall');

/**
 * Plugin Setup (On Deactivation)
 * 
 * @package WooCommerce Donation and Tip
 * @since 1.0.0
 */
function wdt_uninstall() {

	$wdt_delete_data = get_option('wdt_delete_data');
	if ($wdt_delete_data == 'yes') {

		//Items need to delete
        $options = array(
            'wdt_delete_data',
            'wdt_enable',
            'wdt_display_on',
            'wdt_cart_position',
            'wdt_checkout_position',
            'wdt_button_label',
            'wdt_remove_button_label',
            'wdt_title',
            'wdt_message',
            'wdt_amount',
            'wdt_preset_amts',
            'wdt_donation_field_placeholder',
            'wdt_donation_field_title',
    		'wdt_enable_optional_msg',
    		'wdt_optn_msg_field_title',
    		'wdt_optn_msg_field_placeholder',
    		'wdt_default_optn_msg',
        );

        // Delete all options
        foreach ($options as $option) {
            delete_option($option);
        }
	}
}

/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package WooCommerce Donation and Tip
 * @since 1.0.0
 */
function wdt_load_text_domain() {

    // Set filter for plugin's languages directory
    $wdt_lang_dir = dirname(plugin_basename(__FILE__)) . '/languages/';
    $wdt_lang_dir = apply_filters('wdt_languages_directory', $wdt_lang_dir);

    // Traditional WordPress plugin locale filter
    $locale = apply_filters('plugin_locale', get_locale(), 'woodonatip');
    $mofile = sprintf('%1$s-%2$s.mo', 'woodonatip', $locale);

    // Setup paths to current locale file
    $mofile_local = $wdt_lang_dir . $mofile;
    $mofile_global = WP_LANG_DIR . '/' . WOO_DONA_TIP_PLUGIN_BASENAME . '/' . $mofile;

    if (file_exists($mofile_global)) { // Look in global /wp-content/languages/woocommerce-donation-tip folder
        load_textdomain('woodonatip', $mofile_global);
    } elseif (file_exists($mofile_local)) { // Look in local /wp-content/plugins/woocommerce-donation-tip/languages/ folder
        load_textdomain('woodonatip', $mofile_local);
    } else { // Load the default language files
        load_plugin_textdomain('woodonatip', false, $wdt_lang_dir);
    }
}

// Add action to load plugin
add_action('plugins_loaded', 'wdt_plugin_loaded');

/**
 * Load Plugin
 * 
 * Handles to load plugin after
 * dependent plugin is loaded
 * successfully
 * 
 * @package WooCommerce Donation and Tip
 * @since 1.0.0
 */
function wdt_plugin_loaded() {

    //check Woocommerce is activated or not
    if (class_exists('Woocommerce')) {

        // load first plugin text domain
        wdt_load_text_domain();

        // Global variables
        global $wdt_scripts, $wdt_model, $wdt_admin, $wdt_public;

        //includes post types file
		include_once( WOO_DONA_TIP_INC_DIR . '/woo-dona-tip-post-types.php');

        // Script class handles most of script functionalities of plugin
        include_once(WOO_DONA_TIP_INC_DIR . '/class-woo-dona-tip-scripts.php');
        $wdt_scripts = new WDT_Scripts();
        $wdt_scripts->add_hooks();

        // Model class handles most of model functionalities of plugin
        include_once(WOO_DONA_TIP_INC_DIR . '/class-woo-dona-tip-model.php');
        $wdt_model = new WDT_Model();

        include_once(WOO_DONA_TIP_INC_DIR . '/class-woo-dona-tip-public.php');
        $wdt_public = new WDT_Public();
        $wdt_public->add_hooks();

        // Admin class handles most of admin panel functionalities of plugin
        include_once(WOO_DONA_TIP_ADMIN_DIR . '/class-woo-dona-tip-admin.php');
        $wdt_admin = new WDT_Admin();
        $wdt_admin->add_hooks();
    }
}

?>