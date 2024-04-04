<?php
/**
 * Metaboxes_Charges Class
 * @since  1.0.0
 * @package Includes / Admin / Metaboxes / Charges
 */
class FullCulqi_Metaboxes_Charges extends FullCulqi_Metaboxes {

	protected $post_type = 'culqi_charges';


	/**
	 * Add Custom Script to this PostType
	 * @return mixed
	 */
	public function add_scripts() {
		global $pagenow, $post;

		$allowed_pages = [ 'post-new.php', 'post.php' ];

		if( ! in_array( $pagenow, $allowed_pages ) || $this->post_type != get_post_type() )
			return;

		wp_enqueue_script(
			'fullculqi-charges-js',
			MPCULQI_URL . 'resources/assets/js/admin-charges.js',
			[ 'jquery' ], false, true
		);

		// Loading Gif
		$img_loading = sprintf(
			'<img src="%s" style="width: auto;" />',
			admin_url( 'images/spinner.gif' )
		);

		// Success Icon
		$img_success = sprintf(
			'<img src="%s" style="width: auto;" />',
			admin_url( 'images/yes.png' )
		);

		// Failure Icon
		$img_failure = sprintf(
			'<img src="%s" style="width: auto;" />',
			admin_url('images/no.png')
		);

		wp_localize_script( 'fullculqi-charges-js', 'fullculqi_charges_vars',
			apply_filters('fullculqi/metaboxes/charges/localize', [
				'url_ajax'			=> admin_url( 'admin-ajax.php' ),
				'img_loading'		=> $img_loading,
				'img_success'		=> $img_success,
				'img_failure'		=> $img_failure,
				'refund_confirm'	=> esc_html__( 'Do you want to start the refund?', 'fullculqi' ),
				'refund_loading'	=> esc_html__( 'Processing the refund.', 'fullculqi' ),
				'refund_success'	=> esc_html__( 'Refund completed.', 'fullculqi' ),
				'refund_failure'	=> esc_html__( 'Refund Error.', 'fullculqi' ),
				'nonce'				=> wp_create_nonce( 'fullculqi-wpnonce' ),
			] )
		);
	}

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
				$newCols['culqi_email']		= esc_html__( 'Email', 'fullculqi' );
				$newCols['culqi_amount']	= esc_html__( 'Amount', 'fullculqi' );
				//$newCols['culqi_refunded']	= esc_html__( 'Refunded', 'fullculqi' );
				$newCols['culqi_status']	= esc_html__( 'Status', 'fullculqi' );
				$newCols['culqi_creation']	= esc_html__( 'Creation', 'fullculqi' );
			}
		}

		return apply_filters('fullculqi/charges/column_name', $newCols, $cols );
	}

	/**
	 * Column Value
	 * @param  string  $col
	 * @param  integer $post_id
	 * @return mixed
	 */
	public function column_value( $col = '', $post_id = 0 ) {

		$basic = get_post_meta( $post_id, 'culqi_basic', true );

		$value = '';

		switch( $col ) {
			case 'culqi_id'			:
					$value = get_post_meta( $post_id, 'culqi_id', true ); break;
			case 'culqi_creation'	:
					$value = $this->setTimezoneCulqi(get_post_meta( $post_id, 'culqi_creation_date', true )); break;
			case 'culqi_email'		:

				$culqi_customer_id 	= get_post_meta( $post_id, 'culqi_customer_id', true );
				$post_customer_id = fullculqi_post_from_meta( 'culqi_id', $culqi_customer_id );

				if( ! empty( $post_customer_id ) ) {
					$customer_email = get_post_meta( $post_customer_id, 'culqi_email', true );

					$value = sprintf(
						'<a target="_blank" href="%s">%s</a>',
						get_edit_post_link( $post_customer_id ), $customer_email
					);
				} else {
					$customer = get_post_meta( $post_id, 'culqi_customer', true );
					$value = $customer['culqi_email'];
				}

				break;

			//case 'culqi_currency'	: $value = $basic['culqi_currency']; break;
			case 'culqi_amount'		:
				$value = fullculqi_format_price(
					$basic['culqi_amount'], $basic['culqi_currency']
				);
				break;

			// case 'culqi_refunded'	:
			// 	$value = fullculqi_format_price(
			// 		$basic['culqi_amount_refunded'], $basic['culqi_currency']
			// 	);
			// 	break;

			case 'culqi_status'		:

				$statuses = fullculqi_charges_statuses();
				$status = get_post_meta( $post_id, 'culqi_status', true );

				$class = fullculqi_class_from_status( $status, 'charges' );

				if( ! empty( $status ) && isset( $statuses[$status] ) ) {
					$value = sprintf(
						'<mark class="metabox_badged %s"><span>%s</span></mark>',
						$class, $statuses[$status]
					);
				}

				break;
		}

		echo wp_kses_post( apply_filters( 'fullculqi/charges/column_value', $value, $col, $post_id ) );
	}

	/**
	 * Add Meta Boxes to Shop Order CPT
	 * @param  WP_POST $post
	 * @return mixed
	 */
	public function metaboxes( $post ) {

		// Basic Metabox
		add_meta_box(
			'culqi_charges_basic',
			esc_html__( 'Basic', 'fullculqi'),
			[ $this, 'metabox_basic' ],
			$this->post_type,
			'normal', 'high'
		);

		// Source Metabox
		add_meta_box(
			'culqi_charges_source',
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

		$culqi_customer_id 	= get_post_meta( $post->ID, 'culqi_customer_id', true );
		$post_customer_id = fullculqi_post_from_meta( 'culqi_id', $culqi_customer_id );

		if( ! empty( $post_customer_id ) ) {
			$customer 	= get_post_meta( $post_customer_id, 'culqi_basic', true );

			$customer_email	= get_post_meta( $post_customer_id, 'culqi_email', true );
			$customer_email = sprintf(
				'<a target="_blank" href="%s">%s</a>',
				get_edit_post_link( $post_customer_id ), $customer_email
			);
		} else  {
			$customer 	= get_post_meta( $post->ID, 'culqi_customer', true );
			$customer_email = $customer['culqi_email'];
		}

		// Status
		$status = get_post_meta( $post->ID, 'culqi_status', true );
		$status_class = fullculqi_class_from_status( $status, 'charges' );

		// Capture
		$capture = get_post_meta( $post->ID, 'culqi_capture', true );
		$capture_date = get_post_meta( $post->ID, 'culqi_capture_date', true );


		$args = apply_filters( 'fullculqi/charges/metabox_basic/args', [
			'post_id'		=> $post->ID,
			'id'			=> get_post_meta( $post->ID, 'culqi_id', true ),
			'ip'			=> get_post_meta( $post->ID, 'culqi_ip', true ),
			'order_id'		=> get_post_meta( $post->ID, 'culqi_order_id', true ),
			'creation_date'	=> $this->setTimezoneCulqi(get_post_meta( $post->ID, 'culqi_creation_date', true )),
			'currency'		=> $basic['culqi_currency'],
			'amount'		=> $basic['culqi_amount'],
			'refunded'		=> $basic['culqi_amount_refunded'],
			'statuses'		=> fullculqi_charges_statuses(),
			'status'		=> $status,
			'status_class'	=> $status_class,
			'capture'		=> $capture,
			'capture_date'	=> $this->setTimezoneCulqi($capture_date),
			'email'			=> $customer_email,
			'first_name'	=> $customer['culqi_first_name'],
			'last_name'		=> $customer['culqi_last_name'],
			'city'			=> $customer['culqi_city'],
			'country'		=> $customer['culqi_country'],
			'phone'			=> $customer['culqi_phone'],
		], $post );

		fullculqi_get_template( 'resources/layouts/admin/metaboxes/charge_basic.php', $args );
	}

	/**
	 * Metabox Source
	 * @return html
	 */
	public function metabox_source() {
		global $post;

		$args = apply_filters( 'fullculqi/charges/metabox_source/args', [
			'data' => get_post_meta( $post->ID, 'culqi_data', true ),
		], $post );

		fullculqi_get_template( 'resources/layouts/admin/metaboxes/charge_source.php', $args );
	}
}

new FullCulqi_Metaboxes_Charges();
