<div class="culqi_customers_box">
	<h2 class="metabox_h2">
		<?php printf( esc_html__( 'Customer ID : %s', 'fullculqi' ), esc_html( $id ) ); ?>
	</h2>
	<p class="metabox_subh2">
		<?php printf( esc_html__( 'Customer via FullCulqi: %s', 'fullculqi' ), esc_html( $names ) ); ?>
	</p>

	<div class="metabox_column_container">
		<div class="metabox_column">
			<ul>
				<li>
					<b><?php esc_html_e( 'Email', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($email); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'First Name', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($first_name); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Last Name', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($last_name); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Creation', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($creation); ?>
				</li>
			</ul>
		</div>
		<div class="metabox_column">
			<ul>
				<li>
					<b><?php esc_html_e( 'Address', 'fullculqi' ); ?> : </b>
					<?php echo esc_html($address); ?>
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
