<?php if( ! empty( $order_id ) ) : ?>
	<li>
		<b><?php esc_html_e( 'WC Order ID', 'fullculqi' ); ?> : </b>
		<a target="_blank" href="<?php echo esc_url( get_edit_post_link( $order_id ) ); ?>">
			<?php echo esc_html($order_id); ?>
		</a>
	</li>
<?php endif; ?>
