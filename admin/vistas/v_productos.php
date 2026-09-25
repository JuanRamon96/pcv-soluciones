<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
  <div class="d-flex gap-2">
    <input type="search" id="filtroProductos" class="form-control form-control-sm" placeholder="Buscar…">
    <select id="filtroTipo" class="form-select form-select-sm" style="width:auto">
      <option value="">Todos</option>
      <option value="servicio">Servicios</option>
      <option value="producto">Productos</option>
    </select>
  </div>
  <button type="button" class="btn btn-sm btn-pcv" id="bAgregarProducto">Agregar <i class="fas fa-plus"></i></button>
</div>
<div class="table-responsive card p-2">
  <table class="table table-sm table-hover align-middle mb-0" id="tablaProductos">
    <thead><tr><th></th><th>Tipo</th><th>Nombre</th><th>Clasificación</th><th>Subtipo</th><th>Estado</th><th></th></tr></thead>
    <tbody id="tbodyProductos"></tbody>
  </table>
</div>

<div class="modal fade" id="modalProducto" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <form class="modal-content" id="formProducto" enctype="multipart/form-data">
      <div class="modal-header"><h5 class="modal-title">Producto / Servicio</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" name="id" id="prodId">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Tipo</label>
            <select name="tipo" id="prodTipo" class="form-select" required>
              <option value="servicio">Servicio</option>
              <option value="producto">Producto</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Clasificación</label>
            <select name="clasificacion_id" id="prodClas" class="form-select" required>
#opcionesClasificacion#
</select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Subtipo</label>
            <select name="subtipo_id" id="prodSub" class="form-select">
#opcionesSubtipo#
</select>
          </div>
          <div class="col-12">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" id="prodNombre" class="form-control" required>
          </div>
          <div class="col-12">
            <label class="form-label">Resumen</label>
            <textarea name="resumen" id="prodResumen" class="form-control" rows="2"></textarea>
          </div>
          <div class="col-12">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" id="prodDesc" class="form-control" rows="4"></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Imagen principal</label>
            <input type="file" name="imagen" id="prodImagen" class="form-control" accept="image/*">
          </div>
          <div class="col-md-2">
            <label class="form-label">Orden</label>
            <input type="number" name="orden" id="prodOrden" class="form-control" value="0">
          </div>
          <div class="col-md-4 d-flex align-items-end gap-3">
            <div class="form-check"><input class="form-check-input" type="checkbox" name="destacado" id="prodDestacado"><label class="form-check-label" for="prodDestacado">Destacado</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="activo" id="prodActivo" checked><label class="form-check-label" for="prodActivo">Activo</label></div>
          </div>
        </div>
        <p class="small text-muted mt-3 mb-0">Regla de negocio: no se capturan ni muestran precios.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-pcv">Guardar</button>
      </div>
    </form>
  </div>
</div>
<script src="vistas/assets/js/productos.js"></script>
