<div class="culqi_orders_box">
	<h2 class="metabox_h2">
		<?php printf( esc_html__( 'Culqi ID : %s', 'fullculqi' ), esc_html( $id ) ); ?>
	</h2>
	<p class="metabox_subh2">
		<?php printf( esc_html__( 'Created via FullCulqi on %s.', 'fullculqi' ), esc_html( $creation ) ); ?>
	</p>

	<div class="metabox_column_container">
		<div class="metabox_column">
			<h3 class="metabox_h3">
				<?php esc_html_e( 'Order Data', 'fullculqi' ); ?>
			</h3>
			<ul>
				<li>
					<b><?php esc_html_e( 'Creation Date', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($creation); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Expiration Date', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($expiration); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Last Status Date', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($status_date); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Currency', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($currency); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Amount', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($amount); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'CIP Code', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($cip); ?>
				</li>
				<?php do_action( 'fullculqi/orders/basic/print_data', $post_id ); ?>
			</ul>
			<?php
				printf(
					'<mark class="metabox_badged %s"><span>%s</span></mark>',
					esc_html( $status_class ), esc_html( $statuses[$status] )
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
					<?php echo esc_html($first_name); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Last Name', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($last_name); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'City', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($city); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Country', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($country); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Phone', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($phone); ?>
				</li>
			</ul>
		</div>
	</div>
	<div class="clear"></div>
</div>
