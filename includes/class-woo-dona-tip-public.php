<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Public calss
 *
 * Manage public class
 *
 * @package WooCommerce Donation and Tip
 * @since 1.0.0
 */
class WDT_Public {

	public $model, $wdt_session;

    // Class constructor
    function __construct() {

        global $wdt_model;

        $this->model = $wdt_model;
        $this->wdt_session = 'wdt_session';
    }

    /**
     * Enqueue Scripts
     * 
     * Handles to initialise plugin and render data
     * on cart and checkout page
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function wdt_display_donation_form() {

    	$wdt_enable = get_option('wdt_enable');
    	if(!$wdt_enable) {
    		return;
    	}

    	$wdt_display_on = get_option('wdt_display_on');
        if ($wdt_display_on === "cart" || $wdt_display_on === "both") {

            $cart_form_position = get_option('wdt_cart_position');
            add_action($cart_form_position, array($this, 'wdt_add_donation_form'));
        }

        if ($wdt_display_on === "checkout" || $wdt_display_on === "both") {

            $checkout_form_position = get_option('wdt_checkout_position');
            add_action($checkout_form_position, array($this, 'wdt_add_donation_form'));
        }
    }

    /**
     * Handles to render the donation/tip form
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function wdt_add_donation_form() {

    	$wdt_enable_optional_msg= get_option('wdt_enable_optional_msg');
    	extract($this->model->wdt_get_settings());
    	$donatip_session 		= $this->wdt_get_donatip();
    	$wdt_amount 			= isset($donatip_session['donatipamt']) && $donatip_session['donatipamt'] > 0 ? $donatip_session['donatipamt'] : $wdt_amount;
    	$wdt_preset_arr 		= array();
    	$total_preset_count 	= 0;
    	$wdt_default_optn_msg	= $wdt_enable_optional_msg === 'yes' && $donatip_session['donatipmsg'] ? $donatip_session['donatipmsg'] : $wdt_default_optn_msg;

    	if(!!$wdt_preset_amts) {
    		$wdt_preset_arr = explode('|', $wdt_preset_amts);
    		$total_preset_count = count($wdt_preset_arr);
    	}

    	for($i=0; $i < $total_preset_count; $i++) {

    		if(!is_numeric($wdt_preset_arr[$i])) {
    			unset($wdt_preset_arr[$i]);
    		}
    	}

    	// Include html for fonation/tip form field
        include(WOO_DONA_TIP_INC_DIR . '/templates/html-discount-form.php');
    }

    /**
     * Handles to render shortcode for 
     * donation/tip form
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function wdt_donation_form_func() {

    	ob_start();
    	$this->wdt_add_donation_form();
    	return ob_get_clean();
    }

    /**
     * Handles to add/update donation or tip
     * in WooCommerce session
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function wdt_add_update_donatip() {

    	global $woocommerce;

    	$donatip_session = $this->wdt_get_donatip();
		if(isset($_POST["donatip"]) && is_numeric($_POST["donatip"])) {
			$donatip_session['donatipamt'] = $_POST['donatip'];
		}

		if(isset($_POST["donatipmsg"]) && !empty($_POST["donatipmsg"])) {
			$donatip_session['donatipmsg'] = $this->model->wdt_nohtml_kses($_POST['donatipmsg']);
		} else {
			$donatip_session['donatipmsg'] = get_option('wdt_title');
		}

		if(!empty($donatip_session)) {
			$woocommerce->session->set($this->wdt_session, $donatip_session);
		}
    }

    /**
     * Handles to fetch donation or tip
     * from WooCommerce session
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function wdt_get_donatip() {

    	global $woocommerce;

    	$donatip = $woocommerce->session->get($this->wdt_session);
		if(isset($donatip) && is_array($donatip)) {
        	return $donatip;
		}
		
		return;
    }

    /**
     * Handles to fetch donation or tip
     * from WooCommerce session
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function wdt_add_donatip_to_cart() {
    	global $woocommerce;

        $donatip_session = $this->wdt_get_donatip();
        if ($donatip_session && isset($donatip_session['donatipamt']) && 
        	is_numeric($donatip_session['donatipamt']) && $donatip_session['donatipamt'] > 0) {

        	$wdt_title = get_option('wdt_title');
        	$donatip_message = isset($donatip_session['donatipmsg']) && !empty($donatip_session['donatipmsg']) ? $donatip_session['donatipmsg'] : $wdt_title;
            $woocommerce->cart->add_fee($donatip_message, $donatip_session['donatipamt']);
        }
    }

    /**
     * Handles to update the meta when order is placed
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function wdt_product_purchase($order_id) {
    	global $woocommerce;

    	$prefix  			= WOO_DONA_TIP_META_PREFIX;
        $donatip_session 	= $this->wdt_get_donatip();
        if ($donatip_session && is_numeric($donatip_session['donatipamt']) && $donatip_session['donatipamt'] > 0) {

        	update_post_meta($order_id, $prefix.'order_amt', $donatip_session);
        }
    }

    /**
     * Handles to download the data in CSV format
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.2
     */
    public function wdt_export_csv() {

    	if(isset($_GET['page']) && $_GET['page'] === "woodonatip"
    		&& isset($_GET['woodonatip-exp-csv'])) {

    		$prefix 	= WOO_DONA_TIP_META_PREFIX;
    		$exports 	= '';
    		$total		= 0;

    		// Taking parameter
			$orderby 	= isset( $_GET['orderby'] )	? urldecode( $_GET['orderby'] )		: 'id';
			$order		= isset( $_GET['order'] )	? $_GET['order']                	: 'DESC';

    		// Default argument
			$queryargs = array(
								'post_type' 	=> 'shop_order',
								'post_status'	=> array('wc-processing', 'wc-completed'),
								'meta_query' 	=> array(
										                array(
										                    'key' 		=> $prefix . 'order_amt',
										                    'compare' 	=> 'EXISTS'
										                ),
										            ),
								'orderby'		=> $orderby,
								'order'			=> $order,
								'posts_per_page'=> -1
							);
	
			// Fire query in to table for retriving data
			$donatip_query_res 	= new WP_Query( $queryargs );

			$columns = array(
							esc_html__( 'Order ID', 'woodonatip' ),
							esc_html__( 'User', 'woodonatip' ),
							esc_html__( 'Donation Amount', 'woodonatip' ),
							esc_html__( 'Donation Message', 'woodonatip' ),
							esc_html__( 'Date', 'woodonatip' )
					     );

			// Put the name of all fields
			foreach ($columns as $column) {
				
				$exports .= '"'.$column.'",';
			}
			$exports .="\n";

			foreach($donatip_query_res->posts as $order_post) {

				$order = wc_get_order($order_post->ID);
				$buyer = '';

				if ( $order->get_billing_first_name() || $order->get_billing_last_name() ) {
					/* translators: 1: first name 2: last name */
					$buyer = trim( sprintf( _x( '%1$s %2$s', 'full name', 'woodonatip' ), $order->get_billing_first_name(), $order->get_billing_last_name() ) );
				} elseif ( $order->get_billing_company() ) {
					$buyer = trim( $order->get_billing_company() );
				} elseif ( $order->get_customer_id() ) {
					$user  = get_user_by( 'id', $order->get_customer_id() );
					$buyer = ucwords( $user->display_name );
				}

				$donatip_session= get_post_meta($order_post->ID, $prefix.'order_amt', true);
				$donatip_amt	= isset($donatip_session['donatipamt']) && is_numeric($donatip_session['donatipamt']) ? $donatip_session['donatipamt'] : 0;
				$donatip_msg	= isset($donatip_session['donatipmsg']) && !empty($donatip_session['donatipmsg']) ? $donatip_session['donatipmsg'] : '';
				$total			+= $donatip_amt;

				$exports .= '"'.esc_attr( $order->get_order_number() ).'",';
				$exports .= '"'.$buyer.'",';
				$exports .= '"'.html_entity_decode(strip_tags(wc_price($donatip_amt))).'",';
				$exports .= '"'.html_entity_decode(strip_tags($donatip_msg)).'",';
				$exports .= '"'.date_i18n( get_option('date_format'). ' '. get_option('time_format') , strtotime($order_post->post_date)).'",';
				
				$exports .= "\n";
			}

			// Add blank and total row
			$exports .= "\n";
			$exports .= '"'.esc_html(apply_filters('wdt_total_label', __('Total Donation and Tips', 'woodonatip'))).'",';
			$exports .= '"",';
			$exports .= '"'.html_entity_decode(strip_tags(wc_price($total))).'",';
			$exports .= '"",';

			$wdt_file_name = esc_html(apply_filters('wdt_csv_filename', __('WooCommerce Donation and Tip - Report', 'woodonatip')));
			header("Content-type: text/x-csv");
			header("Content-Disposition: attachment; filename=".$wdt_file_name.".csv");
			echo $exports;
			exit;
    	}
    }

    /**
     * Handles to render shortcode for 
     * total donation/tip on website
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.6
     */
    public function wdt_total_donation_func() {

    	ob_start();
    	echo wc_price($this->model->wdt_get_total_donatip());
    	return ob_get_clean();
    }

    /**
     * Handles to add the fees in a subscription product
     * which is created by WC Subscription plugin
     * when there is free trial and 
     * zero sign up costs
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.7
     */
    public function wdt_add_fees_to_wcs($remove_fees_from_cart, $cart, $recurring_carts) {

    	return false;
    }

    /**
     * Handles to add thankyou message on thankyou page
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.7
     */
    public function wdt_thankyou_message_func($order_id) {

        $prefix = WOO_DONA_TIP_META_PREFIX;
        $wdt_thankyou_msg = get_option('wdt_thankyou_msg');
        if(!empty($wdt_thankyou_msg)) {

            $order = wc_get_order($order_id);
            $order_status = $order->get_status();

            if($order_status === "completed" || $order_status === "processing") {

                $donatip_session    = get_post_meta($order_id, $prefix.'order_amt', true);
                $donatip_amt        = isset($donatip_session['donatipamt']) && is_numeric($donatip_session['donatipamt']) ? $donatip_session['donatipamt'] : 0;
                $donatip_msg        = isset($donatip_session['donatipmsg']) && !empty($donatip_session['donatipmsg']) ? $donatip_session['donatipmsg'] : '';

                $shortcodes         = array('{wdt_donatip_amt}', '{wdt_donatip_msg}');
                $shortcode_val      = array(wc_price($donatip_amt), $donatip_msg);

                $wdt_thankyou_msg   = str_replace($shortcodes, $shortcode_val, $wdt_thankyou_msg);

                echo '<p class="woocommerce-notice woocommerce-notice--success wdt-thankyou-donatip-received">' . __($wdt_thankyou_msg, 'woodonatip') . '</p>';
            }
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

    	// Add action to render form fields
    	add_action('init', array($this, 'wdt_display_donation_form'));

    	// Add action to export data in CSV format
    	add_action('init', array($this, 'wdt_export_csv'));

    	// Add shortcode for adding donation form
    	add_shortcode('wdt_donation_form', array($this, 'wdt_donation_form_func'));

    	// Add shortcode for adding donation form
    	add_shortcode('wdt_total_donation', array($this, 'wdt_total_donation_func'));

    	// Add action to add/update the session
    	add_action('wp_ajax_wdt_add_dona_tip', array($this, 'wdt_add_update_donatip'));
        add_action('wp_ajax_nopriv_wdt_add_dona_tip', array($this, 'wdt_add_update_donatip'));

        // Add action to add donation/tip as fees in WooCommerce cart
        add_action('woocommerce_cart_calculate_fees', array($this, 'wdt_add_donatip_to_cart'));

        // Add action to update donatip meta when order is getting placed
        add_action('woocommerce_checkout_update_order_meta', array($this, 'wdt_product_purchase'));

        // Add filter for WooCommerce Subscription Compatibility
        add_filter('wcs_remove_fees_from_initial_cart', array($this, 'wdt_add_fees_to_wcs'), 999, 3);

        // Add action to display thankyou message when user donates amount and the order is in either processing or completed status
        add_action('woocommerce_thankyou', array($this, 'wdt_thankyou_message_func'), 5);
    }

}
