<div class="culqi_charges_box">
	<h2 class="metabox_h2">
		<?php printf( esc_html__( 'Culqi ID : %s','fullculqi'), $id ); ?>
	</h2>
	<p class="metabox_subh2">
		<?php printf( esc_html__( 'Charge via FullCulqi. Paid on %s. Customer IP: %s', 'fullculqi' ), $creation_date, $ip ); ?>
	</p>

	<div class="metabox_column_container">
		<div class="metabox_column">
			<h3 class="metabox_h3">
				<?php esc_html_e( 'Charge Data', 'fullculqi' ); ?>
			</h3>
			<ul>
				<li>
					<b><?php esc_html_e( 'Creation Date', 'fullculqi' ); ?> : </b>
					<?php echo $creation_date; ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Capture Date', 'fullculqi' ); ?> : </b>
					<?php echo $capture_date; ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Currency', 'fullculqi' ); ?> : </b>
					<?php echo $currency; ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Amount', 'fullculqi' ); ?> : </b>
					<?php echo $amount; ?>
				</li>
				<!-- <li>
					<b><?php esc_html_e( 'Refund', 'fullculqi' ); ?> : </b>
					<?php echo $refunded; ?>
				</li> -->
				<?php do_action( 'fullculqi/charges/basic/print_data', $post_id ); ?>
			</ul>
			<?php
				if( ! empty( $status ) && isset( $statuses[$status] ) ) {
					printf(
						'<mark class="metabox_badged %s"><span>%s</span></mark>',
						$status_class, $statuses[$status]
					);

					if( $status == 'captured' || $status == 'authorized' ) {
						echo '&nbsp';

						printf(
							'<a href="" id="culqi_refunds" class="metabox_simple_link" data-post="%d">%s</a>',
							$post_id, esc_html__( 'Refund Charge', 'fullculqi' )
						);

						echo '&nbsp';

						echo '<span id="culqi_refunds_notify"></span>';
					}
				}
			?>
			<?php do_action( 'fullculqi/layout_basic/status' ); ?>
		</div>
		<div class="culqi_data_column" style="display: none">
			<h3 class="metabox_h3">
				<?php esc_html_e( 'Customer', 'fullculqi' ); ?>
			</h3>
			<ul>
				<li>
					<b><?php esc_html_e( 'Email', 'fullculqi' ); ?> : </b>
					<?php echo $email; ?>
				</li>
				<li>
					<b><?php esc_html_e( 'First Name', 'fullculqi' ); ?> : </b>
					<?php echo $first_name; ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Last Name', 'fullculqi' ); ?> : </b>
					<?php echo $last_name; ?>
				</li>
				<li>
					<b><?php esc_html_e( 'City', 'fullculqi' ); ?> : </b>
					<?php echo $city; ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Country', 'fullculqi' ); ?> : </b>
					<?php echo $country; ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Phone', 'fullculqi' ); ?> : </b>
					<?php echo $phone; ?>
				</li>
			</ul>
		</div>
	</div>
	<div class="clear"></div>
</div>
