<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Model Class
 * 
 * Handles generic plugin functionality.
 * 
 * @package WooCommerce Donation and Tip
 * @since 1.0.0
 */
class WDT_Model {

    public function __construct() {
        
    }

    /**
     * Escape Tags & Strip Slashes From Array
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function wdt_escape_slashes_deep($data = array(), $flag = false, $limited = false) {

        if ($flag != true) {
            $data = $this->wdt_nohtml_kses($data);
        } else {
            if ($limited == true) {
                $data = wp_kses_post($data);
            }
        }

        $data = esc_attr(stripslashes_deep($data));

        return $data;
    }

    /**
     * Strip Html Tags
     * 
     * It will sanitize text input (strip html tags, and escape characters)
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function wdt_nohtml_kses($data = array()) {

        if (is_array($data)) {
            $data = array_map(array($this, 'wdt_nohtml_kses'), $data);
        } elseif (is_string($data)) {
            $data = wp_filter_nohtml_kses($data);
        }

        return $data;
    }

    /**
     * Get all plugin options
     * 
     * It will sanitize text input (strip html tags, and escape characters)
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.0
     */
    public function wdt_get_settings() {
    	return array(
    		'wdt_delete_data' => get_option('wdt_delete_data'),
    		'wdt_enable' => get_option('wdt_enable'),
    		'wdt_display_on' => get_option('wdt_display_on'),
    		'wdt_cart_position' => get_option('wdt_cart_position'),
    		'wdt_checkout_position' => get_option('wdt_checkout_position'),
    		'wdt_button_label' => get_option('wdt_button_label'),
    		'wdt_remove_button_label' => get_option('wdt_remove_button_label'),
    		'wdt_title' => get_option('wdt_title'),
    		'wdt_message' => get_option('wdt_message'),
    		'wdt_amount' => get_option('wdt_amount'),
    		'wdt_preset_amts' => get_option('wdt_preset_amts'),
    		'wdt_donation_field_title' => get_option('wdt_donation_field_title'),
    		'wdt_donation_field_placeholder' => get_option('wdt_donation_field_placeholder'),
    		'wdt_enable_optional_msg' => get_option('wdt_enable_optional_msg'),
    		'wdt_optn_msg_field_title' => get_option('wdt_optn_msg_field_title'),
    		'wdt_optn_msg_field_placeholder' => get_option('wdt_optn_msg_field_placeholder'),
    		'wdt_default_optn_msg' => get_option('wdt_default_optn_msg')
    	);
    }
    
    /**
     * Handles to calculate
     * total donation/tip
     * 
     * @package WooCommerce Donation and Tip
     * @since 1.0.6
     */
    public function wdt_get_total_donatip() {

    	$prefix 	= WOO_DONA_TIP_META_PREFIX;
		$data_res	= array();

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
							'posts_per_page'=> -1
						);

		// Fire query in to table for retriving data
		$donatip_query_res 	= new WP_Query( $queryargs );
		$donatip_total 		= 0;

		foreach($donatip_query_res->posts as $post) {

			$donatip_session	= get_post_meta($post->ID, $prefix.'order_amt', true);
			if(is_numeric($donatip_session)) {

				$donatip_session = $this->wdt_transform_meta($post->ID);
			}
			$donatip_total 		+= is_array($donatip_session) ? $donatip_session['donatipamt'] : 0;
		}

		return $donatip_total;
    }
    
    /**
	 * Get Coupons Data
	 * 
	 * Handles get all donation and tips from database
	 * 
	 * @package WooCommerce Donation and Tip
 	 * @since 1.0.1
	 */
	public function wdt_get_donatips( $args = array() ) {

		$prefix 	= WOO_DONA_TIP_META_PREFIX;
		$data_res	= array();

		// Default argument
		$queryargs = array(
							'post_type' 	=> 'shop_order',
							'post_status'	=> array('wc-processing', 'wc-completed'),
							'meta_query' 	=> array(
									                array(
									                    'key' 		=> $prefix . 'order_amt',
									                    'compare' 	=> 'EXISTS'
									                ),
									            )
						);
		$queryargs = wp_parse_args( $args, $queryargs );
		
		// Fire query in to table for retriving data
		$result = new WP_Query( $queryargs );
		
		//retrived data is in object format so assign that data to array for listing
		$postslist = $this->wdt_object_to_array($result->posts);
		
		$data_res['data'] 	= $postslist;
		
		//To get total count of post using "found_posts" and for users "total_users" parameter
		$data_res['total']	= isset($result->found_posts) ? $result->found_posts : '';

		// To get total donations on the website
		$data_res['donatip_total'] = $this->wdt_get_total_donatip();
		
		return $data_res;
	}
	
	/**
	 * Convert Object To Array
	 *
	 * Converting Object Type Data To Array Type
	 * 
	 * @package WooCommerce Donation and Tip
 	 * @since 1.0.1
	 */
	public function wdt_object_to_array($result)
	{
	    $array = array();
	    foreach ($result as $key=>$value)
	    {	
	        if (is_object($value))
	        {
	            $array[$key]=$this->wdt_object_to_array($value);
	        } else {
	        	$array[$key]=$value;
	        }
	       
	    }
	   
	    return $array;
	}

	/**
	 * Convert mets data
	 * 
	 * @package WooCommerce Donation and Tip
 	 * @since 1.0.4
	 */
	public function wdt_transform_meta($order_id) {

		$prefix 	= WOO_DONA_TIP_META_PREFIX;
		$transformed_meta = array();

		$donatip_session	= get_post_meta($order_id, $prefix.'order_amt', true);
		if(is_numeric($donatip_session)) {

			$transformed_meta['donatipamt'] = $donatip_session;
			$transformed_meta['donatipmsg'] = get_option('wdt_title');
			update_post_meta($order_id, $prefix.'order_amt', $transformed_meta);
			return $transformed_meta;
		}

		return $donatip_session;
	}
}