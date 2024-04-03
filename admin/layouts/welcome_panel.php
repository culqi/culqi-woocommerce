<?php
if ( ! defined( 'ABSPATH' ) )
	exit;
?>

<div class="wrap about-wrap full-width-layout">
	<div style="float: right;">
		<img src="<?php echo esc_url( MPCULQI_URL . 'admin/assets/images/culqi_logo.png' ); ?>" alt="FullCulqi Logo" />
	</div>
	<h1><?php esc_html_e( 'FullCulqi Integration', 'culqi' ); ?></h1>

	<p class="about-text"><?php esc_html_e( 'Thanks for use the last version!', 'culqi' ) ?></p>

	<hr />

	<div class="about-wrap-content">

		<div class="feature-section one-col is-wide wp-clearfix">
			<div class="col">
				<h2><?php esc_html_e( 'This will just take a minute!', 'culqi' ); ?></h2>
				<p class="about-description"><?php esc_html_e( 'To continue with this integration, you need provide the public and secret key.','culqi' ); ?></p>
			</div>
		</div>

		<div class="feature-section one-col is-wide wp-clearfix one-col">


			<div class="col">
				<div class="alignleft" style="margin-right: 20px;">
					<img src="<?php echo esc_url( MPCULQI_PLUGIN_URL . 'admin/assets/images/welcome.png' ); ?>" alt="FullCulqi Logo" style="width:100%;" />
				</div>

				<br /><br /><br />

				<?php if(fullculqi_have_posts()) : ?>
					<table>
						<tr><th>
							<div class="alignright" style="margin-left: 20px;">
								<img src="<?php echo esc_url( MPCULQI_PLUGIN_URL . 'admin/assets/images/alert.png' ); ?>" alt="alert" style="margin-bottom: 0;" />
							</div>
							<p><?php esc_html_e( 'We have realized that you have posts related to fullculqi plugin. It is advisable to delete that information before proceeding.', 'culqi' ); ?></p>
						</th></tr>
						<tr><td>
							<button id="fullculqi_delete_all" class="fullculqi_delete_all button button-secondary button-hero"><?php esc_html_e( 'Clear all', 'culqi' ); ?></button>
							<div id="fullculqi_delete_all_loading"></div>
						</td></tr>
					</table>
					<br /><br />
				<?php else : ?>

					<form action="" method="POST">
					<table>
						<tr><td>
							<label for="commerce"><b><?php esc_html_e( 'Commerce Name', 'culqi' ); ?> : </b></label>
						</td><td>
							<input type="text" id="commerce" name="fullculqi_options[commerce]" value="" />
						</td></tr>
						<tr><td>
							<label for="public_key"><b><?php esc_html_e( 'Public Key', 'culqi' ); ?> : </b></label>
						</td><td>
							<input type="text" id="public_key" name="fullculqi_options[public_key]" value="" />
						</td></tr>
						<tr><td>
							<label for="secret_key"><b><?php esc_html_e( 'Secret Key', 'culqi' ); ?> : </b></label>
						</td><td>
							<input type="text" id="secret_key" name="fullculqi_options[secret_key]" value="" />
						</td></tr>
						<tr><td>
							<label for="woo_payment"><b><?php esc_html_e( 'Activate the payment method for Woocommerce', 'culqi' ); ?> : </b></label>
						</td><td>
							<input type="checkbox" name="fullculqi_options[woo_payment]" value="yes" />
						</td></tr>
						<tr><td colspan="2">
							<input type="submit" class="button button-primary button-hero" value="<?php esc_html_e( 'Synchronize with Culqi', 'culqi' ); ?>" />
						</td></tr>
					</table>
					<?php wp_nonce_field( 'fullculqi_wpnonce', 'fullculqi_install' ); ?>
				</form>
				<?php endif; ?>



				<a href="<?php echo esc_url( admin_url( 'admin.php?page=fullculqi_settings' ) ); ?>"><?php esc_html_e( 'Not now', 'culqi' ); ?></a>
			</div>
		</div>

	</div>
</div>
