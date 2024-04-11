<label for="fullculqi_notpay">
	<input type="text" readonly="true" id="fullculqi_notpay" class="regular-text" name="fullculqi_options[notify_pay]" value="<?php echo esc_url( site_url( 'fullculqi-api/webhooks' ) ); ?>"/><br>
	<span class="form-text text-muted"> Si no iniciaste sesión con tu cuenta de CulqiPanel, tienes que configurar esta URL colocando estas credenciales:</span><br>
	<b>Usuario:</b> <?php echo esc_html( $GLOBALS['username'] ); ?>	
	<b>Password:</b> <?php echo esc_html( $GLOBALS['password'] ); ?>
</label>
