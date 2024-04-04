<?php
/**
 * Metaboxes_Orders Class
 * @since  1.0.0
 * @package Includes / Admin / Metaboxes / Orders
 */
class FullCulqi_Metaboxes_Orders extends FullCulqi_Metaboxes {

	protected $post_type = 'culqi_orders';

	/**
	 * Column Name
	 * @param  array $cols
	 * @return array
	 */
	public function column_name( $cols = [] ) {

		$settings = fullculqi_get_settings();

		$cols[ 'title' ] = esc_html__( 'ID', 'fullculqi' );
		unset( $cols[ 'date' ] );

		foreach($cols as $key_column => $value_column) {	
			$newCols[ $key_column ] = $value_column;

			if( $key_column == 'title' ) {
				$newCols['culqi_cip']			= esc_html__( 'CIP Code', 'fullculqi' );
				$newCols['culqi_creation']		= esc_html__( 'Creation', 'fullculqi' );
				$newCols['culqi_expiration']	= esc_html__( 'Expiration', 'fullculqi' );
				$newCols['culqi_email']			= esc_html__( 'Email', 'fullculqi' );
				$newCols['culqi_amount']		= esc_html__( 'Amount', 'fullculqi' );
				$newCols['culqi_status']		= esc_html__( 'Status', 'fullculqi' );
			}
		}
		
		return apply_filters('fullculqi/orders/column_name', $newCols, $cols );
	}

	/**
	 * Column Value
	 * @param  string  $col
	 * @param  integer $post_id
	 * @return mixed
	 */
	public function column_value( $col = '', $post_id = 0 ) {

		$basic 		= get_post_meta( $post_id, 'culqi_basic', true );
		$customer 	= get_post_meta( $post_id, 'culqi_customer', true );

		$value = '';

		switch( $col ) {
			case 'culqi_cip'		: $value = get_post_meta( $post_id, 'culqi_cip', true );
				break;
			case 'culqi_creation'	:
				$value = $this->setTimezoneCulqi(get_post_meta( $post_id, 'culqi_creation_date', true )); break;
			case 'culqi_expiration'	: $value = $this->setTimezoneCulqi($basic['culqi_expiration']); break;
			case 'culqi_email'		:

				if( ! empty( $customer['post_id'] ) ) {

					$value = sprintf(
						'<a target="_blank" href="%s">%s</a>',
						get_edit_post_link( $customer['post_id'] ), $customer['culqi_email']
					);

				} else
					$value = $customer['culqi_email'];

				break;
			case 'culqi_amount'		:
				$value = fullculqi_format_price( $basic['culqi_amount'] ); break;
			
			case 'culqi_status'		:
				$statuses = fullculqi_multipayments_statuses();
				$status = get_post_meta( $post_id, 'culqi_status', true );
				$class = fullculqi_class_from_status( $status, 'orders' );

				$value = sprintf(
					'<mark class="metabox_badged %s"><span>%s</span></mark>',
					$class, $statuses[$status]
				);

				break;
		}

		echo wp_kses_post( apply_filters( 'fullculqi/orders/column_value', $value, $col, $post_id ) );
	}



	/**
	 * Add Meta Boxes to Shop Order CPT
	 * @param  WP_POST $post
	 * @return mixed
	 */
	public function metaboxes( $post ) {

		// Basic Metabox
		add_meta_box(
			'culqi_orders_basic',
			esc_html__( 'Basic', 'fullculqi'),
			[ $this, 'metabox_basic' ],
			$this->post_type,
			'normal', 'high'
		);

		// Source Metabox
		add_meta_box(
			'culqi_orders_source',
			esc_html__( 'Source', 'fullculqi' ),
			[ $this, 'metabox_source' ],
			$this->post_type,
			'normal', 'high'
		);
	}

	/**
	 * Metabox Basic
	 * @return html
	 */
	public function metabox_basic() {
		global $post;

		$basic 		= get_post_meta( $post->ID, 'culqi_basic', true );
		$customer 	= get_post_meta( $post->ID, 'culqi_customer', true );
		$status 	= get_post_meta( $post->ID, 'culqi_status', true );
		$status_date = get_post_meta( $post->ID, 'culqi_status_date', true );
		$cip		= get_post_meta( $post->ID, 'culqi_cip', true );

		$status_class = fullculqi_class_from_status( $status, 'orders' );

		$args = apply_filters( 'fullculqi/orders/metabox_basic/args', [
			'post_id'		=> $post->ID,
			'id'			=> get_post_meta( $post->ID, 'culqi_id', true ),
			'order_id'		=> get_post_meta( $post->ID, 'culqi_order_id', true ),
			'creation'		=> $this->setTimezoneCulqi(get_post_meta( $post->ID, 'culqi_creation_date', true )),
			'expiration'	=> $this->setTimezoneCulqi($basic['culqi_expiration']),
			'currency'		=> $basic['culqi_currency'],
			'amount'		=> $basic['culqi_amount'],
			'cip'			=> $cip,
			'statuses'		=> fullculqi_multipayments_statuses(),
			'status'		=> $status,
			'status_class'	=> $status_class,
			'status_date'	=> $this->setTimezoneCulqi($status_date),
			'email'			=> $customer['culqi_email'],
			'first_name'	=> $customer['culqi_first_name'],
			'last_name'		=> $customer['culqi_last_name'],
			'city'			=> $customer['culqi_city'],
			'country'		=> $customer['culqi_country'],
			'phone'			=> $customer['culqi_phone'],
		], $post );

		fullculqi_get_template( 'resources/layouts/admin/metaboxes/order_basic.php', $args );
	}


	/**
	 * Metabox Source
	 * @return html
	 */
	public function metabox_source() {
		global $post;
		
		$args = apply_filters( 'fullculqi/orders/metabox_source/args', [
			'data' => get_post_meta( $post->ID, 'culqi_data', true ),
		], $post );

		fullculqi_get_template( 'resources/layouts/admin/metaboxes/order_source.php', $args );	
	}
}

new FullCulqi_Metaboxes_Orders();