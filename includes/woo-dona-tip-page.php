<?php

/**
 * Donation and Tip page
 * 
 * The html markup for the Donation and Tip page
 * 
 * @package WooCommerce Donation and Tip
 * @since 1.0.1
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Load WP_List_Table if not loaded
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WDT_LIST extends WP_List_Table {

	public $model, $per_page, $donatip_total;

	public function __construct() {

        global $wdt_model, $page;

        // Set parent defaults
        parent::__construct( array(
							            'singular'  => 'donatip',	//singular name of the listed records
							            'plural'    => 'donatips',	//plural name of the listed records
							            'ajax'      => false		//does this table support ajax?
							        ) );

		$this->model	= $wdt_model;
		$this->per_page	= apply_filters('wdt_posts_per_page', 20); // Per page
    }

    /**
     * Display Columns
     * 
     * Handles which columns to show in table
     * 
	 * @package WooCommerce Donation and Tip
	 * @since 1.0.1
     */
	public function get_columns() {
		
        $columns = array(
					            'order_id'		=> esc_html__( 'Order ID', 'woodonatip' ),
					            'user'			=> esc_html__( 'User', 'woodonatip' ),
					            'donatip_amt'	=> esc_html__( 'Donation Amount', 'woodonatip' ),
					            'donatip_msg'   => esc_html__( 'Donation Message', 'woodonatip' ),
					            'donatip_date'	=> esc_html__( 'Date', 'woodonatip' )
					        );
		
		return apply_filters( 'wdt_table_columns', $columns );
    }
    
    /**
     * Sortable Columns
     * 
     * Handles soratable columns of the table
     * 
	 * @package WooCommerce Donation and Tip
	 * @since 1.0.1
     */
	public function get_sortable_columns() {
		
        $sortable_columns = array(
							            'order_id'		=> array( 'order_id', true )
							        );
		
        return apply_filters( 'wdt_table_sortable_columns', $sortable_columns );
    }
    
    /**
	 * Mange column data
	 * 
	 * Default Column for listing table
	 * 
	 * @package WooCommerce Donation and Tip
	 * @since 1.0.1
	 */
	public function column_default( $item, $column_name ) {
		
        switch( $column_name ) {
        	case 'order_id':
            case 'donatip_amt':
            case 'donatip_msg':
            case 'donatip_date':
            case 'user':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
        }
    }
    
    /**
	 * Display message when there is no items
	 * 
	 * @package WooCommerce Donation and Tip
	 * @since 1.0.1
	 */
    public function no_items() {
		// Message to show when no records in database table
		esc_html_e( 'No Donations and Tips yet.', 'woodonatip' );
	}
	
	/**
	 * Add totals
	 * 
	 * Handles to add donation totals
	 * 
	 * @package WooCommerce Donation and Tip
	 * @since 1.0.1
	 */
	function extra_tablenav( $which ) {
		?>
		<div class="alignleft actions wdt-totals-wrapper">
			<span><b><?php esc_html_e('Total Donations/Tips:', 'woodonatip'); ?></b></span>
			<span><?php echo wc_price($this->donatip_total); ?></span>
		</div>
		<?php
	}

    /**
	 * Displaying Prodcuts
	 * 
	 * Does prepare the data for displaying the products in the table.
	 * 
	 * @package WooCommerce Donation and Tip
	 * @since 1.0.1
	 */
	public function display_products() {
		
		$prefix 	= WOO_DONA_TIP_META_PREFIX;
		$resultdata = array();
		
		// Taking parameter
		$orderby 	= isset( $_GET['orderby'] )	? urldecode( $_GET['orderby'] )		: 'id';
		$order		= isset( $_GET['order'] )	? $_GET['order']                	: 'DESC';
		
		$args = array(
						'posts_per_page'	=> $this->per_page,
						'page'				=> isset( $_GET['paged'] ) ? $_GET['paged'] : null,
						'orderby'			=> $orderby,
						'order'				=> $order,
						'offset'  			=> ( $this->get_pagenum() - 1 ) * $this->per_page
					);
		
		// Function to retrive data
		$data = $this->model->wdt_get_donatips( $args );

		if( !empty($data['data']) ) {

			// Re generate data
			foreach ($data['data'] as $key => $value) {

				$order = wc_get_order($value['ID']);
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

				$donatip_session= get_post_meta($value['ID'], $prefix.'order_amt', true);
				$donatip_amt	= isset($donatip_session['donatipamt']) && is_numeric($donatip_session['donatipamt']) ? $donatip_session['donatipamt'] : 0;
				$donatip_msg	= isset($donatip_session['donatipmsg']) && !empty($donatip_session['donatipmsg']) ? $donatip_session['donatipmsg'] : 0;

				$resultdata[$key]['order_id'] 		= '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $order->get_id() ) ) . '&action=edit' ) . '" class="wdt-order-view"><strong>#' . esc_attr( $order->get_order_number() ) . '</strong></a>';
				$resultdata[$key]['donatip_amt'] 	= wc_price($donatip_amt);
				$resultdata[$key]['donatip_msg'] 	= $donatip_msg ? esc_html($donatip_msg) : __('-', 'woodonatip');
				$resultdata[$key]['donatip_date'] 	= date_i18n( get_option('date_format'). ' '. get_option('time_format') ,strtotime($value['post_date']));
				$resultdata[$key]['user'] 			= '<a href="'.get_edit_user_link($order->get_customer_id()).'" class="wdt-user-view"><strong>'.$buyer.'</strong></a>';
			}
		}
		
		$result_arr['data']			= !empty($resultdata) 	? $resultdata 		: array();
		$result_arr['total'] 		= isset($data['total']) ? $data['total'] 	: ''; // Total no of data
		$result_arr['donatip_total']= isset($data['donatip_total']) ? $data['donatip_total'] 	: 0; // Total no of data
		
		return $result_arr;
	}
	
	/**
	 * Setup the final data for the table
	 * 
	 * @package WooCommerce Donation and Tip
	 * @since 1.0.1
	 */
	public function prepare_items() {
        
        // Get how many records per page to show
        $per_page	= $this->per_page;
        
        // Get All, Hidden, Sortable columns
        $columns	= $this->get_columns();
        $hidden		= array();
		$sortable	= $this->get_sortable_columns();
        
		// Get final column header
		$this->_column_headers	= array( $columns, $hidden, $sortable );
        
		// Get Data of particular page
		$data_res 	= $this->display_products();
		$data 		= $data_res['data'];
		$this->donatip_total 	= $data_res['donatip_total'];

		// Get current page number
		$current_page	= $this->get_pagenum();

		// Get total count
        $total_items	= $data_res['total'];

        // Get page items
        $this->items	= $data;

		// We also have to register our pagination options & calculations.
		$this->set_pagination_args( array(
										'total_items' => $total_items,
										'per_page'    => $per_page,
										'total_pages' => ceil($total_items/$per_page)
									) );
    }
}

// Create an instance of our package class.
$DonatipTable = new WDT_LIST();

// Fetch, prepare, sort, and filter our data.
$DonatipTable->prepare_items();

$exportcsvurl = add_query_arg(array('woodonatip-exp-csv' => '1')); // Generate CSV URL
?>

<!-- List Table Output Starts Here -->
<div class="wrap wdt-wrap">
    <h2>
    	<?php esc_html_e( 'Donation and Tip', 'woodonatip' ); ?>
    	<a class="add-new-h2 alignright" href="<?php echo esc_url($exportcsvurl); ?>"><?php esc_html_e( 'Generate CSV','woodonatip' ); ?></a>
    </h2>

	<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
	<form id="product-filter" method="get">
	
		<!-- For plugins, we also need to ensure that the form posts back to our current page -->
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
		
		<?php $DonatipTable->views() ?>
		
		<!-- Now we can render the completed list table -->
		<?php $DonatipTable->display() ?>
	
	</form>
	<!-- List Table Output Ends Here -->
</div><!-- end .wdt-wrap -->