<div class="culqi_customers_box">
	<h2 class="metabox_h2">
		<?php esc_html_e( 'Cards', 'fullculqi' ); ?>
	</h2>

	<div class="metabox_column_container">

		<?php if( ! empty( $cards ) ) : ?>
			<table class="widefat">
				<thead>
					<tr>
						<td><?php esc_html_e( 'Number', 'fullculqi' ); ?></td>
						<td><?php esc_html_e( 'Brand', 'fullculqi' ); ?></td>
						<td><?php esc_html_e( 'Type', 'fullculqi' ); ?></td>
						<td><?php esc_html_e( 'Creation', 'fullculqi' ); ?></td>

						<?php if( ! empty( $actions ) ) : ?>
							<td><?php esc_html_e( 'Actions', 'fullculqi' ); ?></td>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
				<?php foreach( $cards as $card ) : ?>
					<tr>
						<td><?php echo esc_html($card['culqi_number']); ?></td>
						<td><?php echo esc_html($card['culqi_brand']); ?></td>
						<td><?php echo esc_html($card['culqi_type']); ?></td>
						<td><?php echo esc_html($card['culqi_creation']); ?></td>

						<?php if( ! empty( $actions ) ) : ?>
						<td>
							<?php foreach( $actions as $action ) : ?>
								<a href="<?php echo esc_html($action['url']); ?>" class="button button-secondary customer_card_action" id="<?php echo esc_html($action['id']); ?>" data-id="<?php echo esc_html($card['culqi_card_id']); ?>">
								<?php echo esc_html($action['name']); ?>
								</a>
							<?php endforeach; ?>
						</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

		<?php else: ?>

			<p><?php esc_html_e( 'There is no cards', 'fullculqi' ); ?></p>

		<?php endif; ?>

	</div>
</div>
