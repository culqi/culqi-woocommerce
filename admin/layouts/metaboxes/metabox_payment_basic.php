<?php
if ( ! defined( 'ABSPATH' ) )
	exit;
?>

<div class="culqi_payments_box">
	<h2 class="culqi_payments_h2">Culqi ID : <?php echo $id; ?></h2>
	<p class="culqi_payments_subh2"><?php printf(__('Payment via FullCulqi. Paid on %s. Customer IP: %s','letsgo'), $creation, $ip); ?></p>

	<div class="culqi_data_column_container">
		<div class="culqi_data_column">
			<h3 class="culqi_payments_h3"><?php esc_html_e('Payment Data','letsgo'); ?></h3>
			<ul>
				<li><b><?php esc_html_e('Capture Date', 'letsgo'); ?> :</b> <?php echo $capture_date; ?></li>
				<li><b><?php esc_html_e('Currency', 'letsgo'); ?> :</b> <?php echo $currency; ?></li>
				<li><b><?php esc_html_e('Amount', 'letsgo'); ?> :</b> <?php echo $amount; ?></li>
				<li><b><?php esc_html_e('Refund', 'letsgo'); ?> :</b> <?php echo $refunded; ?></li>
				<li><b><?php esc_html_e('Card Brand', 'letsgo'); ?> :</b> <?php echo $card_brand; ?></li>
				<li><b><?php esc_html_e('Card Type', 'letsgo'); ?> :</b> <?php echo $card_type; ?></li>
				<li><b><?php esc_html_e('Card Number', 'letsgo'); ?> :</b> <?php echo $card_number; ?></li>
			</ul>
			<?php
				printf(
					'<mark class="culqi_status_2 %s"><span>%s</span></mark>',
					$status, $statuses[$status]
				);

				if( $status == 'captured' || $status == 'authorized' ) {
					echo '&nbsp';

					printf(
						'<a href="%s" class="fullculqi_refund_link">%s</a>',
						wp_nonce_url( admin_url( 'admin-ajax.php?action=fullculqi_refund&post_id=' . $post_id ), 'fullculqi-wpnonce' ),
						esc_html__( 'Refund payment', 'letsgo' )
					);
				}
			?>
			<?php do_action( 'fullculqi/layout_basic/status' ); ?>
		</div>
		<div class="culqi_data_column">
			<h3 class="culqi_payments_h3"><?php esc_html_e('Customer','letsgo'); ?></h3>
			<ul>
				<li><b><?php esc_html_e('First Name', 'letsgo'); ?> :</b> <?php echo $first_name; ?></li>
				<li><b><?php esc_html_e('Last Name', 'letsgo'); ?> :</b> <?php echo $last_name; ?></li>
				<li><b><?php esc_html_e('City', 'letsgo'); ?> :</b> <?php echo $city; ?></li>
				<li><b><?php esc_html_e('Country', 'letsgo'); ?> :</b> <?php echo $country; ?></li>
				<li><b><?php esc_html_e('Phone', 'letsgo'); ?> :</b> <?php echo $phone; ?></li>
			</ul>
		</div>
	</div>
	<div class="clear"></div>
</div>