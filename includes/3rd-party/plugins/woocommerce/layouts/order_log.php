<?php if( ! empty( $logs ) ) : $i = 1; ?>
	<table class="widefat">
		<thead>
			<tr>
				<th><?php esc_html_e( 'ID', 'fullculqi' ); ?></th>
				<th><?php esc_html_e( 'Date/Hour', 'fullculqi' ); ?></th>
				<th><?php esc_html_e( 'Type', 'fullculqi' ); ?></th>
				<th><?php esc_html_e( 'Message', 'fullculqi' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach( $logs as $log ) : ?>
			<?php if( empty( $log ) ) continue; ?>
			<tr>
				<td><?php echo $i++; ?></td>
				<td><?php echo $log['dateh']; ?></td>
				<td><?php echo $log['type']; ?></td>
				<td><?php echo $log['message']; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php else : ?>
	<h2><?php esc_html_e( 'there are no registered logs', 'fullculqi' ); ?></h2>
<?php endif; ?>