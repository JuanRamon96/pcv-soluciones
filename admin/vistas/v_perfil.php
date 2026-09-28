<!-- Spark Admin — Vista Completa de Mi Perfil -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <div>
    <h4 class="fw-bold mb-0 text-dark">
      <i class="bi bi-person-circle text-success me-2"></i> Mi Perfil de Administrador
    </h4>
    <small class="text-muted">Gestiona tus datos personales y credenciales de acceso al panel</small>
  </div>
  <div class="d-flex gap-2">
    <button type="button" class="btn btn-sm btn-outline-secondary cargarVista d-flex align-items-center gap-1 rounded-3" carga="v_perfil" titulo="Mi Perfil">
      <i class="bi bi-arrow-clockwise"></i>
      <span>Recargar</span>
    </button>
  </div>
</div>

<div class="row g-4">
  <!-- Tarjeta Resumen Lateral -->
  <div class="col-lg-4 col-xl-3">
    <div class="card border-0 shadow-sm text-center p-4 rounded-4" style="background: #FFFFFF;">
      <div class="position-relative d-inline-block mx-auto mb-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 90px; height: 90px; background: linear-gradient(135deg, #051C12 0%, #1A4D2E 100%); color: #FFFFFF; border: 3px solid #E2E8F0;">
          <i class="bi bi-person-fill" style="font-size: 3rem; color: #FFFFFF;"></i>
        </div>
        <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle" style="width: 18px; height: 18px; transform: translate(-2px, -2px);" title="En línea"></span>
      </div>
      <h5 class="fw-bold mb-1 text-dark" id="perfilVistaCardNombre">#nombreUsuarioPlain#</h5>
      <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill mb-3">
        <i class="bi bi-shield-check me-1"></i> Administrador PCV
      </span>
      
      <div class="border-top pt-3 text-start small">
        <div class="mb-2 d-flex align-items-center text-muted">
          <i class="bi bi-at fs-5 me-2 text-primary"></i>
          <span class="fw-semibold text-dark" id="perfilVistaCardUsuario">Cargando...</span>
        </div>
        <div class="mb-2 d-flex align-items-center text-muted">
          <i class="bi bi-envelope fs-5 me-2 text-danger"></i>
          <span class="text-dark text-truncate" id="perfilVistaCardCorreo">Cargando...</span>
        </div>
        <div class="d-flex align-items-center text-muted">
          <i class="bi bi-calendar3 fs-5 me-2 text-success"></i>
          <span class="text-muted" id="perfilVistaCardFecha">—</span>
        </div>
      </div>
    </div>

    <!-- Tarjeta de Seguridad Informativa -->
    <div class="card border-0 shadow-sm p-3 mt-3 rounded-4" style="background: linear-gradient(135deg, #051C12 0%, #072F1F 100%); color: #FFFFFF;">
      <div class="d-flex align-items-center gap-2 mb-2">
        <i class="bi bi-shield-lock-fill" style="color: #B4F105; font-size: 1.2rem;"></i>
        <span class="fw-bold small" style="color: #FFFFFF;">Seguridad de la Cuenta</span>
      </div>
      <p class="small text-white-50 mb-0" style="font-size: 0.78rem;">
        Puedes acceder a este panel tanto con tu <strong>Nombre de Usuario</strong> como con tu <strong>Correo Electrónico</strong>.
      </p>
    </div>
  </div>

  <!-- Formulario Principal de Edición -->
  <div class="col-lg-8 col-xl-9">
    <div class="card border-0 shadow-sm rounded-4" style="background: #FFFFFF;">
      <div class="card-header bg-white border-bottom py-3 px-4">
        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
          <i class="bi bi-pencil-square text-success"></i> Editar Información y Credenciales
        </h5>
      </div>
      
      <div class="card-body p-4">
        <div id="alertPerfilVista" style="display: none;" class="mb-4"></div>

        <form id="formPerfilVista" autocomplete="off">
          <!-- 1. Datos Personales -->
          <h6 class="fw-bold text-uppercase text-muted letter-spacing-1 mb-3" style="font-size: 0.78rem;">
            <i class="bi bi-person-lines-fill me-1 text-primary"></i> 1. Datos Personales y Acceso
          </h6>

          <div class="row g-3 mb-4">
            <div class="col-md-12">
              <label for="perfilNombreV" class="form-label fw-bold small text-secondary">Nombre Completo <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person text-muted"></i></span>
                <input type="text" class="form-control" id="perfilNombreV" name="nombre" placeholder="Ej. Administrador PCV" required maxlength="120">
              </div>
            </div>

            <div class="col-md-6">
              <label for="perfilUsuarioV" class="form-label fw-bold small text-secondary">Nombre de Usuario <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-at text-muted"></i></span>
                <input type="text" class="form-control" id="perfilUsuarioV" name="usuario" placeholder="Ej. admin" required minlength="3" maxlength="80">
              </div>
              <small class="text-muted" style="font-size: 0.72rem;">Sin espacios. Permite letras, números, puntos y guiones.</small>
            </div>

            <div class="col-md-6">
              <label for="perfilCorreoV" class="form-label fw-bold small text-secondary">Correo Electrónico <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                <input type="email" class="form-control" id="perfilCorreoV" name="correo" placeholder="admin@pcvsoluciones.com" required maxlength="160">
              </div>
              <small class="text-muted" style="font-size: 0.72rem;">Se utilizará para iniciar sesión y notificaciones.</small>
            </div>
          </div>

          <!-- 2. Cambio de Contraseña -->
          <div class="p-4 rounded-4 mb-4" style="background-color: #F8FAF9; border: 1px dashed #CBD5E1;">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div class="fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bi bi-shield-lock-fill text-success fs-5"></i>
                <span>2. Cambiar Contraseña</span>
              </div>
              <span class="badge bg-white text-secondary border shadow-xs px-2 py-1" style="font-size: 0.7rem; font-weight: 600;">Opcional</span>
            </div>
            <p class="text-muted small mb-3">Si no deseas cambiar tu contraseña, mantén estos campos completamente vacíos.</p>

            <div class="mb-3">
              <label for="perfilPassActualV" class="form-label fw-bold small text-secondary">Contraseña Actual</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-key text-muted"></i></span>
                <input type="password" class="form-control" id="perfilPassActualV" name="password_actual" placeholder="Ingresa tu clave actual para autorizar el cambio" autocomplete="current-password">
                <button class="btn btn-outline-secondary toggle-pass-visibility" type="button" data-target="perfilPassActualV" tabindex="-1" title="Ver / Ocultar">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label for="perfilPassNuevaV" class="form-label fw-bold small text-secondary">Nueva Contraseña</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
                  <input type="password" class="form-control" id="perfilPassNuevaV" name="password_nueva" placeholder="Mínimo 6 caracteres" minlength="6" autocomplete="new-password">
                  <button class="btn btn-outline-secondary toggle-pass-visibility" type="button" data-target="perfilPassNuevaV" tabindex="-1" title="Ver / Ocultar">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-6">
                <label for="perfilPassConfirmarV" class="form-label fw-bold small text-secondary">Confirmar Nueva Contraseña</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock-fill text-muted"></i></span>
                  <input type="password" class="form-control" id="perfilPassConfirmarV" name="password_confirmar" placeholder="Repite la nueva contraseña" minlength="6" autocomplete="new-password">
                  <button class="btn btn-outline-secondary toggle-pass-visibility" type="button" data-target="perfilPassConfirmarV" tabindex="-1" title="Ver / Ocultar">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Botones de Acción -->
          <div class="d-flex justify-content-end gap-2 pt-2">
            <button type="submit" class="btn btn-spark-primary px-4 py-2 d-flex align-items-center gap-2" id="btnGuardarPerfilV">
              <i class="bi bi-check-circle fs-6"></i>
              <span>Guardar Cambios del Perfil</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
