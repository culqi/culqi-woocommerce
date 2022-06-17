<div class="culqi_customers_box">
	<h2 class="metabox_h2">
		<?php printf( esc_html__( 'Customer ID : %s','fullculqi'), $id ); ?>
	</h2>
	<p class="metabox_subh2">
		<?php printf( esc_html__( 'Customer via FullCulqi: %s', 'fullculqi' ), $names ); ?>
	</p>

	<div class="metabox_column_container">
		<div class="metabox_column">
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
					<b><?php esc_html_e( 'Creation', 'fullculqi' ); ?> : </b>
					<?php echo $creation; ?>
				</li>
			</ul>
		</div>
		<div class="metabox_column">
			<ul>
				<li>
					<b><?php esc_html_e( 'Address', 'fullculqi' ); ?> : </b>
					<?php echo $address; ?>
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