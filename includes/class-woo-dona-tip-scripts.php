<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Scripts Class
 *
 * Handles adding scripts and styles
 * on needed pages
 *
 * @package WooCommerce Donation and Tip
 * @since 1.0.0
 */
class WDT_Scripts {

    public function __construct() {

    }

    /**
     * Enqueue Scripts
     * 
     * Handles to enqueue script on 
     * needed pages
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function wdt_enqueue_settings_scripts($hook_suffix) {

		if($hook_suffix === 'woocommerce_page_wc-settings' && $_GET['tab'] === 'wdt') {
        	// Enqueue Script
            wp_enqueue_script('wdt_admin_scripts', WOO_DONA_TIP_INC_URL . '/js/woo-dona-tip-settings-scripts.js', array('jquery'));
        }
    }

    /**
     * Enqueue Scripts
     * 
     * Handles to enqueue script on 
     * cart and checkout pages
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function wdt_enqueue_public_scripts() {
    	global $post;

    	if(is_cart() || is_checkout() || 
    		(is_singular() && has_shortcode($post->post_content, 'wdt_donation_form'))){

    		wp_enqueue_script('wdt_public_scripts', WOO_DONA_TIP_INC_URL . "/js/woo-dona-tip-public-scripts.js", array('jquery'), false, true);
    		wp_localize_script('wdt_public_scripts', 'wdt_public', array(
                'ajaxurl' 					=> admin_url('admin-ajax.php', ( is_ssl() ? 'https' : 'http')),
                'nonValidAmt' 				=> esc_html(__('Please enter numeric value.', 'woodonatip')),
                'wdt_enable_optional_msg' 	=> get_option('wdt_enable_optional_msg')
            ));
    	}
    }

    /**
     * Enqueue Styles
     * 
     * Handles to enqueue style on 
     * cart and checkout pages
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function wdt_enqueue_public_styles() {
    	global $post;

    	if(is_cart() || is_checkout() || 
    		(is_singular() && has_shortcode($post->post_content, 'wdt_donation_form'))){

    		wp_enqueue_style('wdt_public_style', WOO_DONA_TIP_INC_URL . "/css/woo-dona-tip-public-styles.css");
    	}
    }

    /**
     * Adding Hooks
     *
     * Adding proper hoocks for the scripts.
     *
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function add_hooks() {

        // Add scripts for settings page
        add_action('admin_enqueue_scripts', array($this, 'wdt_enqueue_settings_scripts'));

        // Add scripts on cart and checkout page
        add_action("wp_enqueue_scripts", array($this, "wdt_enqueue_public_scripts"));
        
        // Add styles on cart and checkout page
        add_action("wp_enqueue_scripts", array($this, "wdt_enqueue_public_styles"));
    }

}
