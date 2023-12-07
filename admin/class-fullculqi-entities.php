<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
#[\AllowDynamicProperties]
abstract class FullCulqi_Entities {

	function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_action( 'add_meta_boxes', [ $this,'addmetabox'] );
		add_filter( 'manage_'.$this->post_type.'_posts_columns', [ $this, 'add_name_column' ] );
		add_action( 'manage_'.$this->post_type.'_posts_custom_column', [ $this, 'add_value_column' ], 10, 2);
	}


	function enqueue_scripts() {
		global $pagenow, $post;

		$post_type = array('culqi_payments');

		if ( !in_array(get_post_type(), $post_type) || !in_array( $pagenow, array( 'post-new.php', 'edit.php', 'post.php' ) ) ) {
			return;
		}

		wp_enqueue_style( 'fullculqi-css', MPCULQI_PLUGIN_URL . 'admin/assets/css/fullculqi_admin.css?_='.time());
	}
}
?>
