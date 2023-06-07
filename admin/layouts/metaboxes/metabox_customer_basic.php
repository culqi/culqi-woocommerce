<?php
if ( ! defined( 'ABSPATH' ) )
	exit;
?>

<div class="culqi_customers_box">
	<h2 class="culqi_customers_h2"><?php printf(__('Customer ID : %s','culqi'), $id); ?></h2>
	<p class="culqi_customers_subh2"><?php printf(__('Customer via FullCulqi: %s','culqi'), $names); ?></p>

	<div class="culqi_data_column_container">
		<div class="culqi_data_column">
			<h3 class="culqi_customers_h3"><?php _e('Customer','culqi'); ?></h3>
			<ul>
				<li><b><?php _e('Email', 'culqi'); ?> :</b> <?php echo esc_html($email); ?></li>
				<li><b><?php _e('First Name', 'culqi'); ?> :</b> <?php echo esc_html($first_name); ?></li>
				<li><b><?php _e('Last Name', 'culqi'); ?> :</b> <?php echo esc_html($last_name); ?></li>
				<li><b><?php _e('City', 'culqi'); ?> :</b> <?php echo esc_html($city); ?></li>
				<li><b><?php _e('Country', 'culqi'); ?> :</b> <?php echo esc_html($country); ?></li>
				<li><b><?php _e('Phone', 'culqi'); ?> :</b> <?php echo esc_html($phone); ?></li>
			</ul>
		</div>
		<div class="culqi_data_column">
			<h3 class="culqi_customers_h3"><?php _e('Cards','culqi'); ?></h3>
			<?php if( is_array($cards) && count($cards) > 0 ) : ?>
			<ul>
				<?php foreach($cards as $card) : ?>
					<li><b><?php _e('Card Type', 'culqi'); ?> :</b> <?php echo esc_html($card['culqi_card_type']); ?></li>
					<li><b><?php _e('Card Brand', 'culqi'); ?> :</b> <?php echo esc_html($card['culqi_card_brand']); ?></li>
					<li><b><?php _e('Card Category', 'culqi'); ?> :</b> <?php echo esc_html($card['culqi_card_category']); ?></li>
					<li><b><?php _e('Card Bank', 'culqi'); ?> :</b> <?php echo esc_html($card['culqi_card_bank']); ?></li>
					<li><b><?php _e('Card Number', 'culqi'); ?> :</b> <?php echo esc_html($card['culqi_card_number']); ?></li>
					<li><hr /></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
	</div>
	<div class="clear"></div>
</div>
