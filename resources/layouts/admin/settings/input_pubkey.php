<label for="fullculqi_pubkey">
    <input type="hidden" id="fullculqi_tokenlogin" />
	<input type="text" required="true" id="fullculqi_pubkey" class="regular-text" name="fullculqi_options[public_key]" value="<?php echo esc_html($public_key); ?>"/><br>
	<span class="form-text text-muted"> Ingresa tu llave pública.</span>
    <span id="errorpubkey" class="form-text text-muted" style="display: block; color: red"></span>
</label>
