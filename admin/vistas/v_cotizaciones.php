<!-- Spark Admin Toolbar de Cotizaciones -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <div>
    <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-file-earmark-text text-warning me-2"></i>Cotizaciones Recibidas</h4>
    <small class="text-muted">Revisa las solicitudes de cotización enviadas desde el sitio web y catálogo</small>
  </div>
  <button type="button" class="btn btn-sm btn-outline-secondary cargarVista d-flex align-items-center gap-1 rounded-3" carga="v_cotizaciones" titulo="Cotizaciones" id="bRecargarCot">
    <i class="bi bi-arrow-clockwise"></i>
    <span>Actualizar</span>
  </button>
</div>

<!-- Main Table Card Spark Style with myDataTable -->
<div class="card border-0 shadow-sm">
  <div class="card-body p-3">
    <div>
      <table class="table table-hover align-middle mb-0 text-center myDataTable" id="tablaCotizaciones" width="100%">
        <thead>
          <tr>
            <th style="width: 100px;">Folio</th>
            <th class="text-start">Cliente / Empresa</th>
            <th class="text-start">Contacto Directo</th>
            <th>Producto Requerido</th>
            <th style="width: 120px;">Estatus</th>
            <th style="width: 140px;">Fecha Solicitud</th>
            <th style="width: 130px;" orden="No">Acciones</th>
          </tr>
        </thead>
        <tbody id="tbodyCotizaciones">
          <!-- Cargado vía AJAX por myDataTable -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Detalle de Cotización Spark Style -->
<div class="modal fade" id="modalCotizacion" tabindex="-1" role="dialog" aria-labelledby="modalCotizacionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      <div class="modal-header py-3 px-4" style="background-color: #051C12; color: #FFFFFF;">
        <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2 mb-0" id="modalCotizacionLabel">
          <i class="bi bi-file-earmark-check" style="color: #B4F105;"></i>
          <span>Detalle de Cotización</span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-white" id="detalleCotizacion">
        <!-- Rellenado dinámicamente por cotizaciones.js -->
      </div>
      <div class="modal-footer py-3 px-4 bg-light border-top">
        <button type="button" class="btn btn-outline-secondary px-3 rounded-3" data-bs-dismiss="modal" data-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
