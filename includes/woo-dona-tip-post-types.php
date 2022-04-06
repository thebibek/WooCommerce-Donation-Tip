<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//creating custom post type
// add_action( 'woocommerce_after_register_post_type', 'wdt_reg_post_type'); //creating custom post

/**
 * Register Post Type
 *
 * Register Custom Post Type for managing registered taxonomy
 *
 * @package WooCommerce Donation and Tip
 * @since 1.0.1
 */
function wdt_reg_post_type() {
	$labels = array(
					    'name'                  => __( 'Donations', 'woodonatip' ),
						'singular_name'         => _x( 'Donation', WOO_DONA_TIP_POST_TYPE.' post type singular name', 'woodonatip' ),
						'add_new'               => __( 'Add donation', 'woodonatip' ),
						'add_new_item'          => __( 'Add new donation', 'woodonatip' ),
						'edit'                  => __( 'Edit', 'woodonatip' ),
						'edit_item'             => __( 'Edit donation', 'woodonatip' ),
						'new_item'              => __( 'New donation', 'woodonatip' ),
						'view_item'             => __( 'View donation', 'woodonatip' ),
						'search_items'          => __( 'Search donations', 'woodonatip' ),
						'not_found'             => __( 'No donations found', 'woodonatip' ),
						'not_found_in_trash'    => __( 'No donations found in trash', 'woodonatip' ),
						'parent'                => __( 'Parent donations', 'woodonatip' ),
						'menu_name'             => _x( 'Donations', 'Admin menu name', 'woodonatip' ),
						'filter_items_list'     => __( 'Filter donations', 'woodonatip' ),
						'items_list_navigation' => __( 'Donations navigation', 'woodonatip' ),
						'items_list'            => __( 'Donations list', 'woodonatip' ),
					);
	$args = array(
				    'labels' 				=> $labels,
				    'public' 				=> false,
				    'exclude_from_search'	=> true,
				    'query_var' 			=> false,
				    'rewrite' 				=> false,
				    'capability_type' 		=> WOO_DONA_TIP_POST_TYPE,
				    'hierarchical' 			=> false,
				    'supports' 				=> array( 'title' ),
					'capabilities' 			=> array( 'create_posts' => 'do_not_allow')
			  ); 
	
	// register_post_type(WOO_DONA_TIP_POST_TYPE, $args);
}