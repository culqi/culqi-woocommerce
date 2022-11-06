<div class="wrap">
	<form method="post" action="options.php">
	<?php
		// This prints out all hidden setting fields
		settings_fields( 'fullculqi_group' );
		do_settings_sections( 'fullculqi_page' );
		submit_button(); 
	?>
	</form>
</div>