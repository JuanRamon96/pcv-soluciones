<!-- Spark Admin Toolbar de Productos -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <div>
    <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-boxes text-success me-2"></i>Catálogo de Productos y Servicios</h4>
    <small class="text-muted">Gestiona las 3 divisiones industriales de PCV Soluciones</small>
  </div>
  <div class="d-flex gap-2">
    <button type="button" class="btn btn-sm btn-outline-secondary cargarVista d-flex align-items-center gap-1 rounded-3" carga="v_productos" titulo="Productos / Servicios">
      <i class="bi bi-arrow-clockwise"></i>
      <span>Recargar</span>
    </button>
    #bAgregar#
  </div>
</div>

<!-- Filtro Rápido Segmentado: Todos | Productos | Servicios -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
  <div class="d-flex align-items-center gap-2">
    <span class="text-uppercase small fw-bold text-muted letter-spacing-1 me-1">
      <i class="bi bi-funnel-fill text-primary"></i> Filtrar por Tipo:
    </span>
    <div class="btn-group p-1 bg-white border rounded-pill shadow-sm" role="group" id="grupoFiltroTipo">
      <input type="hidden" id="filtroTipo" value="">
      <button type="button" class="btn btn-sm rounded-pill px-3 py-1 fw-bold btn-filtro-tipo active" data-tipo="">
        <i class="bi bi-grid-fill me-1"></i> Todos
      </button>
      <button type="button" class="btn btn-sm rounded-pill px-3 py-1 fw-bold btn-filtro-tipo" data-tipo="producto">
        <i class="bi bi-box-seam me-1 text-success"></i> Productos
      </button>
      <button type="button" class="btn btn-sm rounded-pill px-3 py-1 fw-bold btn-filtro-tipo" data-tipo="servicio">
        <i class="bi bi-tools me-1 text-primary"></i> Servicios
      </button>
    </div>
  </div>

  <div class="text-muted small">
    Mostrando: <strong id="lblFiltroTipoActivo" class="text-dark">Todos los registros</strong>
  </div>
</div>

<!-- Main Table Card Spark Style with myDataTable -->
<div class="card border-0 shadow-sm">
  <div class="card-body p-3">
    <div>
      <table class="table table-hover align-middle mb-0 text-center myDataTable" id="tablaProductos" width="100%">
        <thead>
          <tr>
            <th style="width: 70px;" orden="No">Imagen</th>
            <th style="width: 110px;">Tipo</th>
            <th class="text-start">Nombre del Producto o Servicio</th>
            <th>Clasificación</th>
            <th>Subtipo</th>
            <th style="width: 110px;">Estado</th>
            <th style="width: 110px;" orden="No">Acciones</th>
          </tr>
        </thead>
        <tbody id="tbodyProductos">
          <!-- Cargado vía AJAX por myDataTable -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Spark Style para Alta y Edición de Producto / Servicio -->
<div class="modal fade" id="modalProducto" tabindex="-1" role="dialog" aria-labelledby="modalProductoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      <div class="modal-header spark-modal-header py-3 px-4" style="background-color: #051C12; color: #FFFFFF;">
        <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2 mb-0" id="modalProductoLabel">
          <i class="bi bi-box-seam" style="color: #B4F105;"></i>
          <span>Gestión de Producto / Servicio</span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="formProducto" enctype="multipart/form-data">
        <div class="modal-body p-4 bg-white">
          <input type="hidden" name="id" id="prodId">
          <input type="hidden" name="quitar_imagen_principal" id="prodQuitarImgPrincipal" value="0">

          <!-- 1. IMAGEN PRINCIPAL (HASTA ARRIBA DE LA MODAL CON PREVIEW Y BOTÓN QUITAR) -->
          <div class="card border rounded-4 p-3 mb-4 bg-light shadow-sm">
            <div class="d-flex flex-column flex-md-row align-items-center gap-4">
              <!-- Visualizador / Previsualizador -->
              <div class="position-relative text-center flex-shrink-0" style="width: 200px; height: 160px;">
                <div id="wrapperImgPrincipal" class="w-100 h-100 border rounded-3 bg-white d-flex align-items-center justify-content-center overflow-hidden position-relative shadow-sm">
                  <!-- Imagen cargada o preview -->
                  <img id="imgPrincipalPreview" src="" alt="Previsualización Principal" class="w-100 h-100 d-none" style="object-fit: cover;">
                  <!-- Estado vacío -->
                  <div id="imgPrincipalPlaceholder" class="text-center p-3 text-muted">
                    <i class="bi bi-image fs-1 d-block mb-1 text-secondary opacity-50"></i>
                    <span class="small fw-semibold">Sin imagen</span>
                  </div>
                </div>
                <!-- Botón flotante para quitar imagen -->
                <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 translate-middle d-none shadow" id="btnQuitarImgPrincipal" title="Quitar imagen principal">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div>

              <!-- Controles y Descripción de Imagen Principal -->
              <div class="flex-grow-1 w-100">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                  <label for="prodImagen" class="form-label fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-star-fill text-warning"></i>
                    <span>Imagen Principal del Producto / Servicio</span>
                  </label>
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small">
                      <i class="bi bi-shield-check me-1"></i> Optimización autom. &lt; 1MB
                    </span>
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-3 d-none" id="btnQuitarImgPrincipalText">
                      <i class="bi bi-trash3 me-1"></i> Quitar Imagen
                    </button>
                  </div>
                </div>
                <p class="text-muted small mb-2">
                  Fotografía de portada visible en tarjetas de catálogo, cotizaciones y carruseles. Al cambiarla o quitarla, la imagen anterior se elimina permanentemente del servidor.
                </p>
                <div class="input-group">
                  <input type="file" name="imagen" id="prodImagen" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif">
                </div>
                <small class="text-muted d-block mt-1">
                  Formatos aceptados: JPG, PNG, WEBP, GIF. Las imágenes se guardan con un identificador único para evitar sobreescrituras.
                </small>
              </div>
            </div>
          </div>

          <!-- 2. CATEGORIZACIÓN & CLASIFICACIÓN -->
          <div class="row g-3 mb-4">
            <div class="col-12">
              <span class="text-uppercase small fw-bold text-muted letter-spacing-1 d-block mb-1">
                <i class="bi bi-diagram-3 me-1 text-success"></i> Categorización
              </span>
            </div>
            <div class="col-md-4">
              <label for="prodTipo" class="form-label small fw-bold text-dark">Tipo de Registro <span class="text-danger">*</span></label>
              <select name="tipo" id="prodTipo" class="form-select" required>
                <option value="servicio">Servicio Industrial</option>
                <option value="producto">Producto / Fabricación</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="prodClas" class="form-label small fw-bold text-dark">División <span class="text-danger">*</span></label>
              <select name="clasificacion_id" id="prodClas" class="form-select" required>
                #opcionesClasificacion#
              </select>
            </div>
            <div class="col-md-4">
              <label for="prodSub" class="form-label small fw-bold text-dark">Subtipo Especializado</label>
              <select name="subtipo_id" id="prodSub" class="form-select">
                #opcionesSubtipo#
              </select>
            </div>
          </div>

          <!-- 3. INFORMACIÓN TÉCNICA -->
          <div class="row g-3 mb-4">
            <div class="col-12">
              <span class="text-uppercase small fw-bold text-muted letter-spacing-1 d-block mb-1">
                <i class="bi bi-file-text me-1 text-success"></i> Información Técnica
              </span>
            </div>
            <div class="col-12">
              <label for="prodNombre" class="form-label small fw-bold text-dark">Nombre del Producto / Servicio <span class="text-danger">*</span></label>
              <input type="text" name="nombre" id="prodNombre" class="form-control" placeholder="Ej: Maquinado CNC en Torno Suizo" required>
            </div>
            <div class="col-12">
              <label for="prodResumen" class="form-label small fw-bold text-dark">Resumen Corto (Catálogo)</label>
              <textarea name="resumen" id="prodResumen" class="form-control" rows="2" placeholder="Breve extracto visible en tarjetas de catálogo y cotización..."></textarea>
            </div>
            <div class="col-12">
              <label for="prodDesc" class="form-label small fw-bold text-dark">Descripción Técnica Completa</label>
              <textarea name="descripcion" id="prodDesc" class="form-control" rows="4" placeholder="Especificaciones técnicas, tolerancias, capacidades de maquinaria o luxes..."></textarea>
            </div>
          </div>

          <!-- 4. GALERÍA DE MÁS IMÁGENES (PLUGIN MULTI-INPUT FILE) -->
          <div class="card border rounded-4 p-3 mb-4 bg-light">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
              <div>
                <span class="text-uppercase small fw-bold text-dark letter-spacing-1 d-block">
                  <i class="bi bi-images me-1 text-primary"></i> Galería de Más Imágenes
                </span>
                <small class="text-muted">Permite subir fotos adicionales de procesos, acabados o especificaciones técnicas.</small>
              </div>
              <span class="badge bg-white text-dark border px-3 py-2 rounded-pill small fw-bold shadow-sm">
                <i class="bi bi-camera me-1 text-primary"></i> <span id="contadorFotosGaleria">0 fotos</span>
              </span>
            </div>

            <!-- Plugin Dropzone / Input File Múltiple -->
            <div class="pcv-gallery-dropzone border-2 rounded-3 p-4 text-center mb-3 bg-white position-relative shadow-sm" id="dropzoneGaleria" style="border: 2px dashed #94A3B8; cursor: pointer; transition: all 0.2s ease;">
              <input type="file" name="imagenes_galeria[]" id="prodGaleriaInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" multiple accept="image/jpeg,image/png,image/webp,image/gif" style="cursor: pointer; z-index: 5;">
              <div class="py-1 pointer-events-none">
                <i class="bi bi-cloud-arrow-up fs-1 text-primary d-block mb-1"></i>
                <div class="fw-bold text-dark fs-6 mb-1">Haz clic aquí o arrastra varias imágenes para la galería</div>
                <div class="text-muted small">Selecciona múltiples archivos (JPG, PNG, WEBP, GIF). Se comprimirán automáticamente a &lt; 1 MB con nombre único.</div>
              </div>
            </div>

            <!-- Grid de Miniaturas de Galería -->
            <div class="row g-3" id="contenedorGaleriaThumbs">
              <!-- Renderizado dinámicamente con JS (fotos existentes y nuevas seleccionadas) -->
            </div>
          </div>

          <!-- 5. CONFIGURACIÓN Y VISIBILIDAD -->
          <div class="row g-3 align-items-center mb-4 p-3 bg-light rounded-3 border mx-0">
            <div class="col-12">
              <span class="text-uppercase small fw-bold text-muted letter-spacing-1 d-block mb-1">
                <i class="bi bi-gear me-1 text-success"></i> Configuración de Publicación
              </span>
            </div>
            <div class="col-md-3">
              <label for="prodOrden" class="form-label small fw-bold text-dark mb-1">Orden</label>
              <input type="number" name="orden" id="prodOrden" class="form-control" value="0" min="0">
              <small class="text-muted">Prioridad en catálogo</small>
            </div>
            <div class="col-md-4 pt-md-3">
              <div class="form-check form-switch mb-1">
                <input class="form-check-input" type="checkbox" role="switch" name="destacado" id="prodDestacado" value="1">
                <label class="form-check-label small fw-semibold text-dark" for="prodDestacado">Destacar en Inicio (Carrusel)</label>
              </div>
            </div>
            <div class="col-md-5 pt-md-3">
              <div class="form-check form-switch mb-1">
                <input class="form-check-input" type="checkbox" role="switch" name="activo" id="prodActivo" value="1" checked>
                <label class="form-check-label small fw-semibold text-dark" for="prodActivo">Registro Activo (Visible en Catálogo)</label>
              </div>
            </div>
          </div>

          <div class="alert alert-light border mb-0 py-2 px-3 rounded-3 d-flex align-items-center gap-2">
            <i class="bi bi-info-circle text-primary"></i>
            <small class="text-muted"><strong>Nota:</strong> No se muestran precios públicos en la web. Todo se maneja por cotización directa.</small>
          </div>
        </div>

        <div class="modal-footer py-3 px-4 bg-light border-top d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary px-3 rounded-3" data-bs-dismiss="modal" data-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary px-4 rounded-3 d-flex align-items-center gap-2" id="bGuardarProducto">
            <i class="bi bi-check2"></i>
            <span>Guardar Registro</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


