<label for="fullculqi_estado_pedido">
	<select id="fullculqi_estado_pedido"  class="regular-text" name="fullculqi_options[estado_pedido]">
		<option value="processing" <?php if(esc_html($estado_pedido) == 'processing'): ?> selected="selected"<?php endif; ?>>Processing</option>
		<option value="completed" <?php if(esc_html($estado_pedido) == 'completed'): ?> selected="selected"<?php endif; ?>>Completed</option>
	</select>
</label>
