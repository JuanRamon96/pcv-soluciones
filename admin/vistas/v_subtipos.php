<!-- Spark Admin — Filtros / Subtipos (líneas especializadas) -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <div>
    <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-sliders2 text-primary me-2"></i>Filtros / Líneas Especializadas</h4>
    <small class="text-muted">Catálogo de subtipos por división (Metal Mecánica, Luminaria, Refaccionaria)</small>
  </div>
  <div class="d-flex gap-2">
    <button type="button" class="btn btn-sm btn-outline-secondary cargarVista d-flex align-items-center gap-1 rounded-3" carga="v_subtipos" titulo="Filtros / Líneas">
      <i class="bi bi-arrow-clockwise"></i>
      <span>Recargar</span>
    </button>
    <button type="button" class="btn btn-spark-primary btn-sm d-flex align-items-center gap-1 rounded-3 btnAbrirModalNuevoSubtipo" id="bAgregarSubtipo">
      <i class="bi bi-plus-lg"></i>
      <span>Agregar</span>
    </button>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body p-3">
    <div>
      <table class="table table-hover align-middle mb-0 text-center myDataTable" id="tablaSubtipos" width="100%">
        <thead>
          <tr>
            <th class="text-start ps-3">Línea / Filtro</th>
            <th>División</th>
            <th style="width: 110px;">Productos</th>
            <th style="width: 120px;">Estado</th>
            <th style="width: 140px;" orden="No">Acciones</th>
          </tr>
        </thead>
        <tbody id="tbodySubtipos">
          <!-- Cargado vía AJAX por myDataTable -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Alta / Edición -->
<div class="modal fade" id="modalSubtipo" tabindex="-1" aria-labelledby="modalSubtipoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      <div class="modal-header py-3 px-4" style="background-color: #051C12; color: #FFFFFF;">
        <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2 mb-0" id="modalSubtipoLabel">
          <i class="bi bi-sliders" style="color: #B4F105;"></i>
          <span id="tituloModalSubtipo">Agregar Nuevo Filtro / Línea</span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formSubtipo">
        <div class="modal-body p-4 bg-white">
          <input type="hidden" name="id" id="subtipoVistaId" value="">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="subtipoVistaClas" class="form-label small fw-bold text-dark">División <span class="text-danger">*</span></label>
              <select name="clasificacion_id" id="subtipoVistaClas" class="form-select" required>
                #opcionesClasificacion#
              </select>
            </div>
            <div class="col-md-6">
              <label for="subtipoVistaNombre" class="form-label small fw-bold text-dark">Nombre de la línea / filtro <span class="text-danger">*</span></label>
              <input type="text" name="nombre" id="subtipoVistaNombre" class="form-control" placeholder="Ej: Soldadura TIG, Racks Especiales" required>
            </div>
            <div class="col-md-6">
              <label for="subtipoVistaOrden" class="form-label small fw-bold text-dark">Orden</label>
              <input type="number" name="orden" id="subtipoVistaOrden" class="form-control" value="0" min="0">
            </div>
            <div class="col-md-6">
              <label for="subtipoVistaActivo" class="form-label small fw-bold text-dark">Estado</label>
              <select name="activo" id="subtipoVistaActivo" class="form-select">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer py-3 px-4 bg-light border-top d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary px-3 rounded-3" data-bs-dismiss="modal" data-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary px-4 rounded-3 d-flex align-items-center gap-2">
            <i class="bi bi-check2"></i>
            <span id="lblBtnGuardarSubtipo">Guardar Filtro</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
