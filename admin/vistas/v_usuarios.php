<!-- Spark Admin — Módulo de Usuarios -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <div>
    <h4 class="fw-bold mb-0 text-dark">
      <i class="bi bi-people-fill text-success me-2"></i> Gestión de Usuarios
    </h4>
    <small class="text-muted">Administradores con acceso al panel de control de PCV Soluciones</small>
  </div>
  <div class="d-flex gap-2">
    <button type="button" class="btn btn-sm btn-outline-secondary cargarVista d-flex align-items-center gap-1 rounded-3" carga="v_usuarios" titulo="Usuarios del Sistema">
      <i class="bi bi-arrow-clockwise"></i>
      <span>Recargar</span>
    </button>
    <button type="button" class="btn btn-spark-primary btn-sm d-flex align-items-center gap-1 rounded-3" id="bAgregarUsuario">
      <i class="bi bi-person-plus-fill me-1"></i>
      <span>Nuevo Usuario</span>
    </button>
  </div>
</div>

<!-- Banner de seguridad y aclaración del usuario actual -->
<div class="alert alert-light border border-start-4 border-start-success shadow-xs d-flex align-items-center justify-content-between gap-3 mb-4 rounded-3 p-3">
  <div class="d-flex align-items-center gap-2">
    <i class="bi bi-shield-check text-success fs-4 flex-shrink-0"></i>
    <div class="small">
      <strong>Protección de cuenta activa:</strong> Tu propio usuario no se muestra en esta tabla para evitar auto-eliminación o desactivación accidental. Para cambiar tu nombre, usuario o contraseña, utiliza <a href="javascript:void(0)" class="fw-bold text-success text-decoration-underline cargarVista" carga="v_perfil" titulo="Mi Perfil">Mi Perfil</a>.
    </div>
  </div>
</div>

<!-- Tabla de Usuarios con myDataTable -->
<div class="card border-0 shadow-sm rounded-4">
  <div class="card-body p-3">
    <div>
      <table class="table table-hover align-middle mb-0 text-center myDataTable" id="tablaUsuarios" width="100%">
        <thead>
          <tr>
            <th style="width: 70px;">ID</th>
            <th class="text-start ps-3">Nombre</th>
            <th style="width: 150px;">Usuario</th>
            <th>Correo Electrónico</th>
            <th style="width: 120px;">Estatus</th>
            <th style="width: 160px;">Fecha Registro</th>
            <th style="width: 110px;" orden="No">Acciones</th>
          </tr>
        </thead>
        <tbody id="tbodyUsuarios">
          <!-- Cargado vía AJAX por myDataTable -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal para Alta y Edición de Usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      <div class="modal-header py-3 px-4" style="background-color: #051C12; color: #FFFFFF;">
        <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2 mb-0" id="modalUsuarioLabel">
          <i class="bi bi-person-fill" style="color: #B4F105;"></i>
          <span id="tituloModalUsuario">Nuevo Usuario Administrador</span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <form id="formUsuario" autocomplete="off">
        <div class="modal-body p-4 bg-white">
          <input type="hidden" name="id" id="usrId" value="">

          <div class="mb-3">
            <label for="usrNombre" class="form-label small fw-bold text-dark">Nombre Completo <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person text-muted"></i></span>
              <input type="text" name="nombre" id="usrNombre" class="form-control" placeholder="Ej. Roberto Gómez" required maxlength="120">
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label for="usrUsuario" class="form-label small fw-bold text-dark">Nombre de Usuario <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-at text-muted"></i></span>
                <input type="text" name="usuario" id="usrUsuario" class="form-control" placeholder="Ej. rgomez" required minlength="3" maxlength="80">
              </div>
              <small class="text-muted" style="font-size: 0.72rem;">Sin espacios. Identificador único.</small>
            </div>
            <div class="col-sm-6">
              <label for="usrEstatus" class="form-label small fw-bold text-dark">Estatus <span class="text-danger">*</span></label>
              <select name="estatus" id="usrEstatus" class="form-select">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label for="usrCorreo" class="form-label small fw-bold text-dark">Correo Electrónico <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
              <input type="email" name="correo" id="usrCorreo" class="form-control" placeholder="rgomez@pcvsoluciones.com" required maxlength="160">
            </div>
          </div>

          <div class="mb-2">
            <label for="usrPassword" class="form-label small fw-bold text-dark">
              Contraseña <span id="reqUsrPassword" class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
              <input type="password" name="password" id="usrPassword" class="form-control" placeholder="Mínimo 6 caracteres" minlength="6" autocomplete="new-password">
              <button class="btn btn-outline-secondary toggle-pass-visibility" type="button" data-target="usrPassword" tabindex="-1" title="Ver / Ocultar">
                <i class="bi bi-eye"></i>
              </button>
            </div>
            <small class="text-muted" id="helpUsrPassword" style="font-size: 0.72rem;">Mínimo 6 caracteres.</small>
          </div>
        </div>

        <div class="modal-footer py-3 px-4 bg-light border-top d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary px-3 rounded-3" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-spark-primary px-4 rounded-3 d-flex align-items-center gap-2" id="btnGuardarUsuario">
            <i class="bi bi-check2"></i>
            <span id="lblBtnGuardarUsuario">Guardar Usuario</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
