<?php
/**
 * CPT Class
 * @since  1.0.0
 * @package Includes / CPT
 */
#[\AllowDynamicProperties]
class FullCulqi_Cpt {

	/**
	 * Construct
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_post_type' ] );
	}

	/**
	 * Regiter CPT
	 * @return mixed
	 */
	public function register_post_type() {

		// Charges
		$labels_charge = [
			'name'					=> esc_html__( 'Culqi Charges', 'fullculqi' ),
			'menu_name'				=> esc_html__( 'Charges', 'fullculqi' ),
			'name_admin_bar'		=> esc_html__( 'Charges', 'fullculqi' ),
			'all_items'				=> esc_html__( 'Charges', 'fullculqi'),
			'singular_name'			=> esc_html__( 'Charge', 'fullculqi' ),
			'add_new'				=> esc_html__( 'Add New Charge', 'fullculqi' ),
			'add_new_item'			=> esc_html__( 'Add New Charge','fullculqi' ),
			'edit_item'				=> esc_html__( 'Edit Charge','fullculqi' ),
			'new_item'				=> esc_html__( 'New Charge','fullculqi' ),
			'view_item'				=> esc_html__( 'View Charge','fullculqi' ),
			'search_items'			=> esc_html__( 'Search Charges','fullculqi' ),
			'not_found'				=> esc_html__( 'Nothing found','fullculqi' ),
			'not_found_in_trash'	=> esc_html__( 'Nothing found in Trash','fullculqi' ),
			'parent_item_colon'		=> ''	
		];
		 
		$args_charge = apply_filters( 'fullculqi/cpt/charges', [
			'labels'				=> $labels_charge,
			'public'				=> false,
			'show_in_menu'			=> 'fullculqi_menu',
			'publicly_queryable'	=> false,
			'show_ui'				=> true,
			'query_var'				=> false,
			//'menu_icon'			=> plugins_url( 'images/icon_star.png' , esc_html__FILEesc_html__ ),
			'rewrite'				=> false,
			'hierarchical'			=> false,
			'menu_position'			=> 54.2,
			'supports'				=> false,
			'exclude_from_search'	=> true,
			'show_in_nav_menus'		=> false,
			'map_meta_cap'			=> true,
			'capability_type'		=> [ 'charge', 'charges' ],
			'capabilities'			=> [
				'edit_post'		=> 'edit_charge',
				'read_post'		=> 'read_charge',
				'delete_post'	=> 'delete_charge',

				'edit_posts'			=> 'edit_charges',
				'edit_others_posts'		=> 'edit_others_charges',
				'publish_posts'			=> 'publish_charges',
				'read_private_posts'	=> 'read_private_charges',	

				'read'						=> 'read',
				'delete_posts'				=> 'delete_charges',
				'delete_private_posts'		=> 'delete_private_charges',
				'delete_published_posts'	=> 'delete_published_charges',
				'delete_others_posts'		=> 'delete_others_charges',
				'edit_private_posts'		=> 'edit_private_charges',
				'edit_published_posts'		=> 'edit_published_charges',
				//'create_posts'			=> 'edit_charges',
				'create_posts'				=> 'do_not_allow',
			]
		] );


		// Orders
		$labels_order = [
			'name'					=> esc_html__( 'Culqi Orders', 'fullculqi' ),
			'menu_name'				=> esc_html__( 'Orders', 'fullculqi' ),
			'name_admin_bar'		=> esc_html__( 'Orders', 'fullculqi' ),
			'all_items'				=> esc_html__( 'Orders', 'fullculqi'),
			'singular_name'			=> esc_html__( 'Order', 'fullculqi' ),
			'add_new'				=> esc_html__( 'Add New Order', 'fullculqi' ),
			'add_new_item'			=> esc_html__( 'Add New Order','fullculqi' ),
			'edit_item'				=> esc_html__( 'Edit Order','fullculqi' ),
			'new_item'				=> esc_html__( 'New Order','fullculqi' ),
			'view_item'				=> esc_html__( 'View Order','fullculqi' ),
			'search_items'			=> esc_html__( 'Search Orders','fullculqi' ),
			'not_found'				=> esc_html__( 'Nothing found','fullculqi' ),
			'not_found_in_trash'	=> esc_html__( 'Nothing found in Trash','fullculqi' ),
			'parent_item_colon'		=> ''	
		];
		 
		$args_order = apply_filters( 'fullculqi/cpt/orders', [
			'labels'				=> $labels_order,
			'public'				=> false,
			'show_in_menu'			=> 'fullculqi_menu',
			'publicly_queryable'	=> false,
			'show_ui'				=> true,
			'query_var'				=> false,
			//'menu_icon'			=> plugins_url( 'images/icon_star.png' , esc_html__FILEesc_html__ ),
			'rewrite'				=> false,
			'hierarchical'			=> false,
			'menu_position'			=> 54.3,
			'supports'				=> false,
			'exclude_from_search'	=> true,
			'show_in_nav_menus'		=> false,
			'map_meta_cap'			=> true,
			'capability_type'		=> [ 'order', 'orders' ],
			'capabilities'			=> [
				'edit_post'		=> 'edit_order',
				'read_post'		=> 'read_order',
				'delete_post'	=> 'delete_order',

				'edit_posts'			=> 'edit_orders',
				'edit_others_posts'		=> 'edit_others_orders',
				'publish_posts'			=> 'publish_orders',
				'read_private_posts'	=> 'read_private_orders',	

				'read'						=> 'read',
				'delete_posts'				=> 'delete_orders',
				'delete_private_posts'		=> 'delete_private_orders',
				'delete_published_posts'	=> 'delete_published_orders',
				'delete_others_posts'		=> 'delete_others_orders',
				'edit_private_posts'		=> 'edit_private_orders',
				'edit_published_posts'		=> 'edit_published_orders',
				//'create_posts'			=> 'edit_orders',
				'create_posts'				=> 'do_not_allow',
			]
		] );


		// Customers
		$labels_customer = [
			'name'					=> esc_html__( 'Culqi Customers', 'fullculqi' ),
			'menu_name'				=> esc_html__( 'Customers', 'fullculqi' ),
			'name_admin_bar'		=> esc_html__( 'Customers', 'fullculqi' ),
			'all_items'				=> esc_html__( 'Customers', 'fullculqi' ),
			'singular_name'			=> esc_html__( 'Customer', 'fullculqi' ),
			'add_new'				=> esc_html__( 'Add New Customer', 'fullculqi' ),
			'add_new_item'			=> esc_html__( 'Add New Customer','fullculqi' ),
			'edit_item'				=> esc_html__( 'Edit Customer', 'fullculqi' ),
			'new_item'				=> esc_html__( 'New Customer', 'fullculqi' ),
			'view_item'				=> esc_html__( 'View Customer', 'fullculqi' ),
			'search_items'			=> esc_html__( 'Search Customers','fullculqi' ),
			'not_found'				=> esc_html__( 'Nothing found', 'fullculqi' ),
			'not_found_in_trash'	=> esc_html__( 'Nothing found in Trash', 'fullculqi' ),
			'parent_item_colon'		=> '',
		];
		 
		$args_customer = apply_filters( 'fullculqi/cpt/customer', [
			'labels'				=> $labels_customer,
			'public'				=> false,
			'show_in_menu'			=> 'fullculqi_menu',
			'publicly_queryable'	=> false,
			'show_ui'				=> true,
			'query_var'				=> false,
			//'menu_icon'			=> plugins_url( 'images/icon_star.png' , esc_html__F ILEesc_html__  ),
			'rewrite'				=> false,
			'hierarchical'			=> false,
			'menu_position'			=> 54.4,
			'supports'				=> false,
			'exclude_from_search'	=> true,
			'show_in_nav_menus'		=> false,
			'map_meta_cap'			=> true,
			'capability_type'		=> [ 'customer', 'customers' ],
			'capabilities'			=> [
				'edit_post'		=> 'edit_customer',
				'read_post'		=> 'read_customer',
				'delete_post'	=> 'delete_customer',

				'edit_posts'			=> 'edit_customers',
				'edit_others_posts'		=> 'edit_others_customers',
				'publish_posts'			=> 'publish_customers',
				'read_private_posts'	=> 'read_private_customers',	

				'read'						=> 'read',
				'delete_posts'				=> 'delete_customers',
				'delete_private_posts'		=> 'delete_private_customers',
				'delete_published_posts'	=> 'delete_published_customers',
				'delete_others_posts'		=> 'delete_others_customers',
				'edit_private_posts'		=> 'edit_private_customers',
				'edit_published_posts'		=> 'edit_published_customers',
				//'create_posts'			=> 'edit_customers'
				'create_posts'				=> 'do_not_allow',
			]
		] );

		// Charges CPT
		register_post_type( 'culqi_charges',  $args_charge );

		// Orders CPT
		register_post_type( 'culqi_orders',  $args_order );

		// Customers CPT
		register_post_type( 'culqi_customers', $args_customer );
		
		// Refresh
		flush_rewrite_rules();
	}
}

new FullCulqi_Cpt();
?>