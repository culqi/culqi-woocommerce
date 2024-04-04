<?php
/**
 * Metaboxes Customers Class
 * @since  1.0.0
 * @package Includes / Admin / Metaboxex / Customers
 */
class FullCulqi_Metaboxes_Customers extends FullCulqi_Metaboxes {

	protected $post_type = 'culqi_customers';

	/**
	 * Name Columns
	 * @param  array  $cols
	 * @return mixed
	 */
	public function column_name( $cols = [] ) {

		$columns[ 'title' ] = esc_html__('ID', 'fullculqi');
		unset( $columns[ 'date' ] );

		foreach( $columns as $key_column => $value_column ) {	
			$newCols[$key_column] = $value_column;

			if( $key_column == 'title' ) {		
				$newCols['culqi_creation']	= esc_html__( 'Creation', 'fullculqi' );
				$newCols['culqi_email']		= esc_html__( 'Email', 'fullculqi' );
				$newCols['culqi_name']		= esc_html__( 'Name', 'fullculqi' );
				$newCols['culqi_country']	= esc_html__( 'Country', 'fullculqi' );
				$newCols['culqi_phone']		= esc_html__( 'Phone', 'fullculqi' );
			}
		}
		
		return apply_filters( 'fullculqi/customers/name_column', $newCols, $cols );
	}


	/**
	 * Value Column
	 * @param  string  $col
	 * @param  integer $post_id
	 * @return mixed
	 */
	public function column_value( $col = '', $post_id = 0 ) {

		$basic = get_post_meta( $post_id, 'culqi_basic', true );

		switch($col) {
			case 'culqi_creation'	:
				$value = get_post_meta( $post_id, 'culqi_creation_date', true ); break;
			case 'culqi_email'		: $value = get_post_meta( $post_id,'culqi_email', true ); break;
			case 'culqi_name'		: $value = $basic['culqi_names']; break;
			case 'culqi_country'	: $value = $basic['culqi_country']; break;
			case 'culqi_phone'		: $value = $basic['culqi_phone']; break;
		}

		echo wp_kses_post( apply_filters( 'fullculqi/customers/value_column', $value, $col, $post_id ) );
	}


	/**
	 * Add Metaboxes
	 * @return mixed
	 */
	public function metaboxes( $post ) {

		// Basic Metaboxes
		add_meta_box(
			'culqi_customers_basic',
			esc_html__( 'Basic', 'fullculqi' ),
			[ $this, 'metabox_basic' ],
			$this->post_type, 'normal', 'high'
		);

		$cards = get_post_meta( $post->ID, 'culqi_cards', true );

		if( ! empty( $cards ) ) {
			
			// Cards Metaboxes
			add_meta_box(
				'culqi_customers_cards',
				esc_html__( 'Cards', 'fullculqi' ),
				[ $this, 'metabox_cards' ],
				$this->post_type, 'normal', 'high'
			);
		}

		// Source Metaboxes
		if( apply_filters( 'fullculqi/customers/metabox_source/enable', false, $post ) ) {
			add_meta_box(
				'culqi_customers_source',
				esc_html__( 'Source', 'fullculqi' ),
				[ $this, 'metabox_source' ],
				$this->post_type, 'normal', 'high'
			);
		}
	}


	
	/**
	 * Basic Metaboxes
	 * @return mixed
	 */
	public function metabox_basic() {
		global $post;

		$basic = get_post_meta( $post->ID, 'culqi_basic', true );

		$args = apply_filters( 'fullculqi/customers/metabox_basic/args', [
			'id'			=> get_post_meta( $post->ID, 'culqi_id', true ),
			'email'			=> get_post_meta( $post->ID, 'culqi_email', true ),
			'creation'		=> $this->setTimezoneCulqi(get_post_meta( $post->ID, 'culqi_creation_date', true )),
			'names'			=> $basic['culqi_names'],
			'first_name'	=> $basic['culqi_first_name'],
			'last_name'		=> $basic['culqi_last_name'],
			'address'		=> $basic['culqi_address'],
			'city'			=> $basic['culqi_city'],
			'country'		=> $basic['culqi_country'],
			'phone'			=> $basic['culqi_phone'],
		], $post );

		fullculqi_get_template(
			'resources/layouts/admin/metaboxes/customer_basic.php', $args
		);
	}

	/**
	 * Metabox Cards
	 * @return mixed
	 */
	public function metabox_cards() {
		global $post;

		$actions = apply_filters('fullculqi/customers/metabox_cards/actions', [] );
		
		$args = apply_filters( 'fullculqi/customers/metabox_cards/args', [
			'cards'		=> get_post_meta( $post->ID, 'culqi_cards', true ),
			'actions'	=> $actions
		], $post );

		fullculqi_get_template(
			'resources/layouts/admin/metaboxes/customer_cards.php', $args
		);
	}


	/**
	 * Source Metaboxes
	 * @return mixed
	 */
	public function metabox_source() {
		global $post;
		
		$args = apply_filters( 'fullculqi/customers/metabox_source/args', [
			'data' => get_post_meta( $post->ID, 'culqi_data', true ),
		], $post );


		fullculqi_get_template(
			'resources/layouts/admin/metaboxes/customer_source.php', $args
		);
	}
}

new FullCulqi_Metaboxes_Customers();