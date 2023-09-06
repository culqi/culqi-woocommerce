<?php
/**
 * Logs Class
 * @since  1.0.0
 * @package Includes / Logs
 */
#[\AllowDynamicProperties]
class FullCulqi_Logs {

	protected $post_id = 0;
	protected $permission;
	protected $slug = 'culqi_log';

	/**
	 * Construct
	 * @param integer $post_id
	 * @return mixed
	 */
	public function __construct( $post_id = 0 ) {
		if( ! empty( $post_id ) )
			$this->post_id = $post_id;
	}

	/**
	 * Set Notice
	 * @param string $message
	 * @return mixed
	 */
	public function set_notice( $message = '' ) {
		$this->register( 'notice', $message );
	}

	/**
	 * Set Error
	 * @param string $message
	 * @return mixed
	 */
	public function set_error( $message = '' ) {
		$this->register( 'error', $message );
	}

	/**
	 * Set a log message
	 * @param string $type
	 * @param string $message
	 */
	protected function register( $type = 'notice', $message = '' ) {
		if( empty( $this->post_id ) )
			return;

		$array_msg = get_post_meta( $this->post_id, $this->slug, true );
		$array_msg = ! empty( $array_msg ) ? $array_msg : [];

		$array_msg[] = [
			'dateh'		=> date('Y-m-d H:i:s'),
			'type'		=> $type,
			'message'	=> $message,
		];

		update_post_meta( $this->post_id, $this->slug, $array_msg );

		return true;
	}
}