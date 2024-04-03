<?php
if ( ! defined( 'ABSPATH' ) )
	exit;
?>

<div class="culqi_payments_box">
	<h2 class="culqi_payments_h2">Culqi ID : <?php echo esc_html( $id ); ?></h2>
	<p class="culqi_payments_subh2"><?php printf( esc_html__( 'Payment via FullCulqi. Paid on %s. Customer IP: %s', 'culqi' ), esc_html( $creation ), esc_html( $ip ) ); ?></p>

	<div class="culqi_data_column_container">
		<div class="culqi_data_column">
			<h3 class="culqi_payments_h3"><?php esc_html_e('Payment Data','culqi'); ?></h3>
			<ul>
				<li><b><?php esc_html_e('Capture Date', 'culqi'); ?> :</b> <?php echo esc_html($capture_date); ?></li>
				<li><b><?php esc_html_e('Currency', 'culqi'); ?> :</b> <?php echo esc_html($currency); ?></li>
				<li><b><?php esc_html_e('Amount', 'culqi'); ?> :</b> <?php echo esc_html($amount); ?></li>
				<li><b><?php esc_html_e('Refund', 'culqi'); ?> :</b> <?php echo esc_html($refunded); ?></li>
				<li><b><?php esc_html_e('Card Brand', 'culqi'); ?> :</b> <?php echo esc_html($card_brand); ?></li>
				<li><b><?php esc_html_e('Card Type', 'culqi'); ?> :</b> <?php echo esc_html($card_type); ?></li>
				<li><b><?php esc_html_e('Card Number', 'culqi'); ?> :</b> <?php echo esc_html($card_number); ?></li>
			</ul>
			<?php
				printf(
					'<mark class="culqi_status_2 %s"><span>%s</span></mark>',
					esc_html( $status ), esc_html( $statuses[ $status ] )
				);

				if( $status == 'captured' || $status == 'authorized' ) {
					echo '&nbsp';

					printf(
						'<a href="%s" class="fullculqi_refund_link">%s</a>',
						esc_url( wp_nonce_url( admin_url( 'admin-ajax.php?action=fullculqi_refund&post_id=' . $post_id ) ), 'fullculqi-wpnonce' ),
						esc_html__( 'Refund payment', 'culqi' )
					);
				}
			?>
			<?php do_action( 'fullculqi/layout_basic/status' ); ?>
		</div>
		<div class="culqi_data_column">
			<h3 class="culqi_payments_h3"><?php esc_html_e('Customer','culqi'); ?></h3>
			<ul>
				<li><b><?php esc_html_e('First Name', 'culqi'); ?> :</b> <?php echo esc_html($first_name); ?></li>
				<li><b><?php esc_html_e('Last Name', 'culqi'); ?> :</b> <?php echo esc_html($last_name); ?></li>
				<li><b><?php esc_html_e('City', 'culqi'); ?> :</b> <?php echo esc_html($city); ?></li>
				<li><b><?php esc_html_e('Country', 'culqi'); ?> :</b> <?php echo esc_html($country); ?></li>
				<li><b><?php esc_html_e('Phone', 'culqi'); ?> :</b> <?php echo esc_html($phone); ?></li>
			</ul>
		</div>
	</div>
	<div class="clear"></div>
</div>
