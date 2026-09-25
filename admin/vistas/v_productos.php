<div class="row" style="margin-bottom: 30px; margin-top: -50px;">
  <div class="col-12 text-right">
    <button type="button" class="btn btn-sm btn-outline-primary cargarVista" carga="v_productos" titulo="Productos / Servicios"><i class="fas fa-rotate-right"></i></button>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-4 mb-2 mb-md-0">
            <input type="search" id="filtroProductos" class="form-control form-control-sm" placeholder="Buscar…">
          </div>
          <div class="col-md-3 mb-2 mb-md-0">
            <select id="filtroTipo" class="form-control form-control-sm">
              <option value="">Todos</option>
              <option value="servicio">Servicios</option>
              <option value="producto">Productos</option>
            </select>
          </div>
          <div class="col-md-5 text-md-right">
            #bAgregar#
          </div>
        </div>
      </div>
      <div class="card-content">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered text-center" id="tablaProductos" width="100%">
              <thead>
                <tr>
                  <th></th>
                  <th>Tipo</th>
                  <th>Nombre</th>
                  <th>Clasificación</th>
                  <th>Subtipo</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody id="tbodyProductos"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal text-left" id="modalProducto" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title white">Producto / Servicio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>
      <form id="formProducto" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="id" id="prodId">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Tipo</label>
                <select name="tipo" id="prodTipo" class="form-control" required>
                  <option value="servicio">Servicio</option>
                  <option value="producto">Producto</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Clasificación</label>
                <select name="clasificacion_id" id="prodClas" class="form-control" required>
#opcionesClasificacion#
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Subtipo</label>
                <select name="subtipo_id" id="prodSub" class="form-control">
#opcionesSubtipo#
                </select>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" id="prodNombre" class="form-control" required>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Resumen</label>
                <textarea name="resumen" id="prodResumen" class="form-control" rows="2"></textarea>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" id="prodDesc" class="form-control" rows="4"></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Imagen principal</label>
                <input type="file" name="imagen" id="prodImagen" class="form-control" accept="image/*">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Orden</label>
                <input type="number" name="orden" id="prodOrden" class="form-control" value="0">
              </div>
            </div>
            <div class="col-md-4 d-flex align-items-center">
              <div class="form-check mr-3">
                <input class="form-check-input" type="checkbox" name="destacado" id="prodDestacado" value="1">
                <label class="form-check-label" for="prodDestacado">Destacado</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="activo" id="prodActivo" value="1" checked>
                <label class="form-check-label" for="prodActivo">Activo</label>
              </div>
            </div>
          </div>
          <p class="small text-muted mb-0">Regla de negocio: no se capturan ni muestran precios.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
          <button type="submit" class="btn btn-primary ml-1" id="bGuardarProducto">Guardar <i class="fas fa-save"></i></button>
        </div>
      </form>
    </div>
  </div>
</div>
