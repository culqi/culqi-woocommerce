<label for="fullculqi_notpay">
	<input type="text" readonly="true" id="fullculqi_notpay" class="regular-text" name="fullculqi_options[notify_pay]" value="<?php echo site_url( 'fullculqi-api/webhooks' ); ?>"/><br>
	<span class="form-text text-muted"> Si no iniciaste sesión con tu cuenta de CulqiPanel, tienes que configurar esta URL colocando estas credenciales:</span><br>
	<b>Usuario:</b> <?php echo $GLOBALS['username']; ?>	
	<b>Password:</b> <?php echo $GLOBALS['password'];; ?>
</label>
