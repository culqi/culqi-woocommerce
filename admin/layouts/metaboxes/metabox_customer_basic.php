<?php
if ( ! defined( 'ABSPATH' ) )
	exit;
?>

<div class="culqi_customers_box">
	<h2 class="culqi_customers_h2"><?php printf(esc_html__('Customer ID : %s','culqi'), esc_html($id)); ?></h2>
	<p class="culqi_customers_subh2"><?php printf(esc_html__('Customer via FullCulqi: %s','culqi'), esc_html($names)); ?></p>

	<div class="culqi_data_column_container">
		<div class="culqi_data_column">
			<h3 class="culqi_customers_h3"><?php esc_html_e('Customer','culqi'); ?></h3>
			<ul>
				<li><b><?php esc_html_e('Email', 'culqi'); ?> :</b> <?php echo esc_html($email); ?></li>
				<li><b><?php esc_html_e('First Name', 'culqi'); ?> :</b> <?php echo esc_html($first_name); ?></li>
				<li><b><?php esc_html_e('Last Name', 'culqi'); ?> :</b> <?php echo esc_html($last_name); ?></li>
				<li><b><?php esc_html_e('City', 'culqi'); ?> :</b> <?php echo esc_html($city); ?></li>
				<li><b><?php esc_html_e('Country', 'culqi'); ?> :</b> <?php echo esc_html($country); ?></li>
				<li><b><?php esc_html_e('Phone', 'culqi'); ?> :</b> <?php echo esc_html($phone); ?></li>
			</ul>
		</div>
		<div class="culqi_data_column">
			<h3 class="culqi_customers_h3"><?php esc_html_e('Cards','culqi'); ?></h3>
			<?php if( is_array($cards) && count($cards) > 0 ) : ?>
			<ul>
				<?php foreach($cards as $card) : ?>
					<li><b><?php esc_html_e('Card Type', 'culqi'); ?> :</b> <?php echo esc_html($card['culqi_card_type']); ?></li>
					<li><b><?php esc_html_e('Card Brand', 'culqi'); ?> :</b> <?php echo esc_html($card['culqi_card_brand']); ?></li>
					<li><b><?php esc_html_e('Card Category', 'culqi'); ?> :</b> <?php echo esc_html($card['culqi_card_category']); ?></li>
					<li><b><?php esc_html_e('Card Bank', 'culqi'); ?> :</b> <?php echo esc_html($card['culqi_card_bank']); ?></li>
					<li><b><?php esc_html_e('Card Number', 'culqi'); ?> :</b> <?php echo esc_html($card['culqi_card_number']); ?></li>
					<li><hr /></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
	</div>
	<div class="clear"></div>
</div>
