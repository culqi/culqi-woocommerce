<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Webhooks History', 'fullculqi' ); ?></h1>
    <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
        <a href="admin.php?page=fullculqi_settings" class="nav-tab">Settings</a>
        <a href="edit.php?post_type=culqi_charges" class="nav-tab">Charges</a>
        <a href="edit.php?post_type=culqi_orders" class="nav-tab">Orders</a>
        <a style="display: none" href="edit.php?post_type=culqi_customers" class="nav-tab">Customers</a>
        <a href="admin.php?page=fullculqi_webhooks" class="nav-tab nav-tab-active">Webhooks</a>
    </nav>
	<p>
		<?php esc_html_e('You will be able to see the last 25 notifications from Culqi. ','fullculqi'); ?>
		<a href="https://blog.letsgodev.com/tips-es/usando-los-webhooks-de-culqi-en-wordpress/" target="_blank">
			<?php esc_html_e( 'What is a webhook? How can I use it?', 'fullculqi' ); ?>
		</a>
	</p>

	<p>
		<b><?php printf( esc_html__( 'Webhook : %s', 'fullculqi' ), esc_html( $webhook_url ) ); ?></b>
	</p>

	<br />

	<table class="wp-list-table widefat fixed striped table-view-list">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Event Date', 'fullculqi' ); ?></th>
				<th><?php esc_html_e( 'Event ID', 'fullculqi' ); ?></th>
				<th><?php esc_html_e( 'Event Name', 'fullculqi' ); ?></th>
				<th><?php esc_html_e( 'Webhook ID', 'fullculqi' ); ?></th>
				<th><?php esc_html_e( 'Webhook Description', 'fullculqi' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if( empty( $webhook_list ) ) : ?>

				<tr><td colspan="3">
					<?php esc_html_e( 'There are no events', 'fullculqi' ); ?>
				</td></tr>

			<?php else : ?>

				<?php foreach( $webhook_list as $webhook ) : ?>
					<tr>
						<td><?php echo esc_html($webhook['creation_date']); ?></td>
						<td><?php echo esc_html($webhook['event_id']); ?></td>
						<td><?php echo esc_html($webhook['event_name']); ?></td>
						<td><?php echo esc_html($webhook['data_id']); ?></td>
						<td><?php echo esc_html($webhook['data_description']); ?></td>
					</tr>
				<?php endforeach; ?>

			<?php endif; ?>
		</tbody>
	</table>
</div>
