<?php
if ( ! defined( 'ABSPATH' ) )
	exit;
?>
<?php if( is_array($payment_log) && count($payment_log) > 0 ) : $i = 1; ?>
	<table class="widefat">
		<thead>
			<tr>
				<th><?php esc_html_e( 'ID', 'culqi' ); ?></th>
				<th><?php esc_html_e( 'Date/Hour', 'culqi' ); ?></th>
				<th><?php esc_html_e( 'Type', 'culqi' ); ?></th>
				<th><?php esc_html_e( 'Message', 'culqi' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($payment_log as $log) : ?>
			<?php if( empty($log) ) continue; ?>
			<tr>
				<td><?php echo esc_html($i++); ?></td>
				<td><?php echo esc_html($log['dateh']); ?></td>
				<td><?php echo esc_html($log['type']); ?></td>
				<td><?php echo esc_html($log['message']); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php else : ?>
	<h2><?php esc_html_e( 'there are no registered logs', 'culqi' ); ?></h2>
<?php endif; ?>
