<div id="fullculqi_receipt_page">
	<img width="400px" src="<?php echo esc_html($src_image); ?>" alt="fullculqi" />
	<br />
	<p>
		<?php esc_html_e( 'Make the purchase clicking the ', 'fullculqi'); ?><strong><?php esc_html_e( 'pay button', 'fullculqi'); ?></strong>
	</p>
	<p>
		<?php esc_html_e( 'If you wish change the payment method, click the ', 'fullculqi' ); ?><strong><?php esc_html_e( 'cancel button', 'fullculqi' ); ?></strong>
	</p>

	<button id="fullculqi_button" class="<?php echo esc_attr( implode( ' ', $class_button ) ); ?>">
		Pagar
	</button>
	<a href="<?php echo esc_html($url_cancel); ?>" class="button cancel">
		<?php esc_html_e( 'Cancel', 'fullculqi' ); ?>
	</a>

	<div id="fullculqi_notify" class="" style="margin:15px 0px;"></div>


	<?php do_action( 'fullculqi/receipt_page/after', $order_id ); ?>
</div>
