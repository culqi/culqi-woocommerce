<?php
if ( ! defined( 'ABSPATH' ) )
	exit;
?>
<?php if( is_array($payment_log) && count($payment_log) > 0 ) : $i = 1; ?>
	<table class="widefat">
		<thead>
			<tr>
				<th><?php _e('ID','letsgo'); ?></th>
				<th><?php _e('Date/Hour','letsgo'); ?></th>
				<th><?php _e('Type','letsgo'); ?></th>
				<th><?php _e('Message','letsgo'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($payment_log as $log) : ?>
			<?php if( empty($log) ) continue; ?>
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
	<h2><?php _e('there are no registered logs','letsgo'); ?></h2>
<?php endif; ?>