<?php
$tarjeta = $methods['tarjeta'] ?? '';
$yape = $methods['yape'] ?? '';
$billetera = $methods['billetera'] ?? '';
$bancaMovil = $methods['bancaMovil'] ?? '';
$agente = $methods['agente'] ?? '';
$cuetealo = $methods['cuetealo'] ?? '';
?>
<label for="fullculqi_logo">
  <div>
    <input  id="fullculqi_methods_tarjeta" name="fullculqi_options[methods][tarjeta]" <?php echo ($tarjeta == 'yes') ? 'checked' : '' ; ?> type="checkbox" value="yes"> Tarjetas débito/credito
  </div>
  <div>
    <input  id="fullculqi_methods_yape" name="fullculqi_options[methods][yape]" <?php echo ($yape == 'yes') ? 'checked' : '' ; ?> type="checkbox" value="yes"> Yape
  </div>
  <div>
    <input  id="fullculqi_methods_wallets" name="fullculqi_options[methods][billetera]" <?php echo ($billetera == 'yes') ? 'checked' : '' ; ?> type="checkbox" value="yes"> Billeteras móviles
    <span class="tool" data-tip="Tus clientes pueden pagar con Yape, Plin y las principales billeteras móviles del país." tabindex="2">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/></svg>
    </span>
  </div>
  <div>
    <input  id="fullculqi_methods_bancaMovil" name="fullculqi_options[methods][bancaMovil]" <?php echo ($bancaMovil == 'yes') ? 'checked' : '' ; ?> type="checkbox" value="yes"> Banca móvil o internet
  </div>
  <div>
    <input  id="fullculqi_methods_agents" name="fullculqi_options[methods][agente]" <?php echo ($agente == 'yes') ? 'checked' : '' ; ?> type="checkbox" value="yes"> Agentes y bodegas
  </div>
  <div>
    <input  id="fullculqi_methods_quotedbcp" name="fullculqi_options[methods][cuetealo]" <?php echo ($cuetealo == 'yes') ? 'checked' : '' ; ?> type="checkbox" value="yes"> Cuotéalo BCP
    <span class="tool" data-tip="Paga en cuotas y sin tarjetas de crédito con Cuotéalo" tabindex="2">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/></svg>
    </span>
  </div>
</label>
<span id="errorpaymentmethods" class="form-text text-muted" style="display: block; color: red"></span>
