<div class="culqi_orders_box">
	<h2 class="metabox_h2">
		<?php printf( esc_html__( 'Culqi ID : %s','fullculqi'), $id ); ?>
	</h2>
	<p class="metabox_subh2">
		<?php printf( esc_html__( 'Created via FullCulqi on %s.', 'fullculqi' ), $creation ); ?>
	</p>

	<div class="metabox_column_container">
		<div class="metabox_column">
			<h3 class="metabox_h3">
				<?php esc_html_e( 'Order Data', 'fullculqi' ); ?>
			</h3>
			<ul>
				<li>
					<b><?php esc_html_e( 'Creation Date', 'fullculqi' ); ?> : </b>
					<?php echo $creation; ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Expiration Date', 'fullculqi' ); ?> : </b>
					<?php echo $expiration; ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Last Status Date', 'fullculqi' ); ?> : </b>
					<?php echo $status_date; ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Currency', 'fullculqi' ); ?> : </b>
					<?php echo $currency; ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Amount', 'fullculqi' ); ?> : </b>
					<?php echo $amount; ?>
				</li>
				<li>
					<b><?php esc_html_e( 'CIP Code', 'fullculqi' ); ?> : </b>
					<?php echo $cip; ?>
				</li>
				<?php do_action( 'fullculqi/orders/basic/print_data', $post_id ); ?>
			</ul>
			<?php
				printf(
					'<mark class="metabox_badged %s"><span>%s</span></mark>',
					$status_class, $statuses[$status]
				);
			?>
		</div>
		<div class="metabox_column">
			<h3 class="metabox_h3">
				<?php esc_html_e( 'Customer', 'fullculqi' ); ?>
			</h3>
			<ul>
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