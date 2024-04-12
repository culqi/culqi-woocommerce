<?php if( ! empty( $order_id ) ) : ?>
	<li>
		<b><?php esc_html_e( 'WC Order ID', 'fullculqi' ); ?> : </b>
		<?php
			$url =  get_edit_post_link( $order_id );
			if(!$url) {
				$url =  admin_url( 'admin.php?page=wc-orders&action=edit&id=' . $order_id );
			}
		?>
		<a target="_blank" href="<?php echo esc_url( $url ); ?>">
			<?php echo esc_html($order_id); ?>
		</a>
	</li>
<?php endif; ?>
