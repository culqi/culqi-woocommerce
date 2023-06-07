<?php
/**
 * Metaboxes Class
 * @since  1.0.0
 * @package Includes / Admin / Metaboxes / Metaboxes
 */
abstract class FullCulqi_Metaboxes {

	/**
	 * Construct
	 */
	public function __construct() {

		// Script JS & CSS
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// After - Save Post
		add_action( 'save_post_' . $this->post_type, [ $this, 'save_post' ], 10, 3 );

		// Delete Post
		add_action( 'before_delete_post', [ $this, 'delete_post' ], 10, 2 );

		// Metaboxes
		add_action( 'add_meta_boxes_' . $this->post_type, [ $this, 'metaboxes' ], 10, 1 );

		// Column Name
		add_filter( 'manage_' . $this->post_type . '_posts_columns', [ $this, 'column_name' ] );

		// Column Value
		add_action( 'manage_' . $this->post_type . '_posts_custom_column', [ $this, 'column_value' ], 10, 2);

		// Order by creation
		add_action( 'pre_get_posts', [ $this, 'sort_by_field' ] );
	}


	/**
	 * Add Script in Metaboxes
	 * @return mixed
	 */
	public function enqueue_scripts() {
		global $pagenow, $post;

		$is_list = $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] == $this->post_type;

		$is_edit = in_array( $pagenow, [ 'post-new.php', 'post.php' ] ) && get_post_type() == $this->post_type;

		// CSS
		if( $is_list || $is_edit ) {

			wp_enqueue_style(
				'fullculqi-metaboxes-css',
				MPCULQI_URL . 'resources/assets/css/admin-metaboxes.css'
			);

			wp_enqueue_style(
				'fullculqi-tags-css',
				MPCULQI_URL . 'resources/assets/css/tags.css'
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


			// JS
			if( $is_list ) {

				wp_enqueue_script(
					'fullculqi-js',
					MPCULQI_URL . 'resources/assets/js/admin-metaboxes.js',
					[ 'jquery' ], false, true
				);

                wp_enqueue_script(
                    'fullculqi-tabs-js',
                    MPCULQI_URL . 'resources/assets/js/admin-tabs.js',
                    [ 'jquery' ], false, true
                );

				wp_localize_script( 'fullculqi-js', 'fullculqi_vars',
					apply_filters('fullculqi/metaboxes/localize', [
						'url_ajax'			=> admin_url( 'admin-ajax.php' ),
						'img_loading'		=> $img_loading,
						'img_success'		=> $img_success,
						'img_failure'		=> $img_failure,
						'sync_id'			=> $this->post_type,
						'sync_text'			=> esc_html__( 'Sync from Culqi', 'fullculqi' ),
						'sync_confirm'		=> esc_html__( 'Do you want to start the sync?', 'fullculqi' ),
						'sync_notify'		=> 'notify_' . $this->post_type,
						'sync_loading'		=> esc_html__( 'Synchronizing. It may take several minutes.', 'fullculqi' ),
						'sync_success'		=> esc_html__( 'Synchronization completed.', 'fullculqi' ),
						'sync_continue'		=> esc_html__( 'Oh! there are more items. Please wait.', 'fullculqi' ),
						'sync_failure'		=> esc_html__( 'Error in the synchronization.', 'fullculqi' ),
						'nonce'				=> wp_create_nonce( 'fullculqi-wpnonce' ),
					] )
				);
			}
		}

		$this->add_scripts();

		do_action( 'fullculqi/metaboxes/enqueue_scripts' );
	}


	/**
	 * Sort list posts by meta value
	 * @param  [type] $wp_query
	 * @return mixed
	 */
	public function sort_by_field( $wp_query ) {

		// If WP-Admin
		if( ! is_admin() )
			return;

		// Post Type
		if( ! isset( $wp_query->query['post_type'] ) || $wp_query->query['post_type'] != $this->post_type )
			return;

		global $pagenow;

		// Only in the table posts
		if( $pagenow != 'edit.php' )
			return;

		// orderby value can be any column name
		$wp_query->set( 'orderby', 'meta_value' );
		$wp_query->set( 'meta_key', 'culqi_creation_date' );
		$wp_query->set( 'order', 'DESC' );

		return true;
	}

	public function setTimezoneCulqi($datetime)
	{
		$timezone_config = get_option('gmt_offset');
		$timezone_config = str_replace(".5",":30", $timezone_config);
		if($timezone_config >= 0) {
			$timezone_config = '+'.$timezone_config;
		}
		$timestamp = strtotime($datetime);
		$datetime = new DateTime();
		$datetime->setTimestamp($timestamp);
		$la_time = new DateTimeZone($timezone_config);
		$datetime->setTimezone($la_time);
		return $datetime->format('Y-m-d H:i:s');
	}

	public function save_post( $post_id = 0, $post, $update = false ) {}

	public function delete_post( $post_id = 0, $post = array()) {}

	public function add_scripts() {}
}
?>
