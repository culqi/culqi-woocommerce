<div id="receipt_page_fullculqi">
	<?php $src_image = apply_filters('fullculqi/receipt_page/image', $src_image); ?>

	<img src="<?php echo esc_html($src_image); ?>" alt="fullculqi" /><br />
	<span>Realiza la compra presionando <strong>Pagar</strong><br>Si deseas cambiar de medio de pago presiona <strong>Cancelar</strong></span><br><br>

	<?php $class_buttonpay = apply_filters('fullculqi/receipt_page/button_class', array('button','alt') ); ?>

	<button id="fullculqi_button" class="<?php echo esc_attr( implode( ' ', $class_buttonpay ) ); ?>"><?php esc_html_e( 'Pay with Culqi', 'culqi' ); ?></button>
	<a href="<?php echo esc_html($url_cancel); ?>" class="button cancel"><?php esc_html_e( 'Cancel', 'culqi' ); ?></a>

	<div id="fullculqi_notify" style="padding:10px 0px;"><?php echo esc_html( apply_filters( 'fullculqi/receipt_page/notify', '', $order_id ) ); ?></div>
</div>
