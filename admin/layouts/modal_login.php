<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="modalLoginLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form method="post" id="modal_login_form_culqi">
        <div class="modal-header">
          <span class="modal-title" id="modalLoginLabel">Ingresa tus datos para ayudarte con la configuración.</span>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" required class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="ejemplo@culqi.com" value="">
          </div>
          <div class="form-group">
            <label for="password">Contraseña</label>
            <input required type="password" class="form-control" id="password" name="password" placeholder="Tu contraseña de CulqiPanel" value="">
          </div>
            <div class="form-group">
                <label id="errorlogincpanelculqi" style="color: red"></label>
            </div>
          <!-- <div class="form-group">
            <label for="type_integration">Tipo de Integración</label>
            <select name="type_integration" class="form-control" id="type_integration">
              <option value="0" selected>Test</option>
              <option value="1">Live</option>
            </select>
          </div> -->
        </div>
        <div class="modal-footer">
          <button id="loginbutton" type="submit" name="submit" class="btn btn-primary" style='width:100%'>Iniciar Sesión</button>
        </div>
      </form>
    </div>
  </div>
</div>
