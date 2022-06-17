<?php
if ( ! defined( 'ABSPATH' ) )
	exit;
?>

<div class="culqi_customers_box">
	<h2 class="culqi_customers_h2"><?php printf(__('Customer ID : %s','letsgo'), $id); ?></h2>
	<p class="culqi_customers_subh2"><?php printf(__('Customer via FullCulqi: %s','letsgo'), $names); ?></p>

	<div class="culqi_data_column_container">
		<div class="culqi_data_column">
			<h3 class="culqi_customers_h3"><?php _e('Customer','letsgo'); ?></h3>
			<ul>
				<li><b><?php _e('Email', 'letsgo'); ?> :</b> <?php echo $email; ?></li>
				<li><b><?php _e('First Name', 'letsgo'); ?> :</b> <?php echo $first_name; ?></li>
				<li><b><?php _e('Last Name', 'letsgo'); ?> :</b> <?php echo $last_name; ?></li>
				<li><b><?php _e('City', 'letsgo'); ?> :</b> <?php echo $city; ?></li>
				<li><b><?php _e('Country', 'letsgo'); ?> :</b> <?php echo $country; ?></li>
				<li><b><?php _e('Phone', 'letsgo'); ?> :</b> <?php echo $phone; ?></li>
			</ul>

			
		</div>
		<div class="culqi_data_column">
			<h3 class="culqi_customers_h3"><?php _e('Cards','letsgo'); ?></h3>
			
			<?php if( is_array($cards) && count($cards) > 0 ) : ?>
			<ul>
				<?php foreach($cards as $card) : ?>
					<li><b><?php _e('Card Type', 'letsgo'); ?> :</b> <?php echo $card['culqi_card_type']; ?></li>
					<li><b><?php _e('Card Brand', 'letsgo'); ?> :</b> <?php echo $card['culqi_card_brand']; ?></li>
					<li><b><?php _e('Card Category', 'letsgo'); ?> :</b> <?php echo $card['culqi_card_category']; ?></li>
					<li><b><?php _e('Card Bank', 'letsgo'); ?> :</b> <?php echo $card['culqi_card_bank']; ?></li>
					<li><b><?php _e('Card Number', 'letsgo'); ?> :</b> <?php echo $card['culqi_card_number']; ?></li>
					<li><hr /></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
	</div>
	<div class="clear"></div>
</div>