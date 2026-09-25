<div class="d-flex gap-2 mb-3">
  <select id="filtroEstatusCot" class="form-select form-select-sm" style="width:auto">
    <option value="">Todas</option>
    <option value="nueva">Nuevas</option>
    <option value="en_proceso">En proceso</option>
    <option value="cerrada">Cerradas</option>
  </select>
  <button type="button" class="btn btn-sm btn-outline-primary" id="bRecargarCot"><i class="fas fa-rotate"></i></button>
</div>
<div class="table-responsive card p-2">
  <table class="table table-sm table-hover align-middle mb-0" id="tablaCotizaciones">
    <thead><tr><th>Folio</th><th>Cliente</th><th>Contacto</th><th>Producto</th><th>Estatus</th><th>Fecha</th><th></th></tr></thead>
    <tbody id="tbodyCotizaciones"></tbody>
  </table>
</div>
<div class="modal fade" id="modalCotizacion" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Detalle cotización</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body" id="detalleCotizacion"></div>
  </div></div>
</div>
<script src="vistas/assets/js/cotizaciones.js"></script>
