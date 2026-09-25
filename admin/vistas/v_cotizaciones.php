<div class="row" style="margin-bottom: 30px; margin-top: -50px;">
  <div class="col-12 text-right">
    <button type="button" class="btn btn-sm btn-outline-primary cargarVista" carga="v_cotizaciones" titulo="Cotizaciones" id="bRecargarCot"><i class="fas fa-rotate-right"></i></button>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-3">
            <select id="filtroEstatusCot" class="form-control form-control-sm">
              <option value="">Todas</option>
              <option value="nueva">Nuevas</option>
              <option value="en_proceso">En proceso</option>
              <option value="cerrada">Cerradas</option>
            </select>
          </div>
        </div>
      </div>
      <div class="card-content">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered text-center" id="tablaCotizaciones" width="100%">
              <thead>
                <tr>
                  <th>Folio</th>
                  <th>Cliente</th>
                  <th>Contacto</th>
                  <th>Producto</th>
                  <th>Estatus</th>
                  <th>Fecha</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody id="tbodyCotizaciones"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal text-left" id="modalCotizacion" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title white">Detalle cotización</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body" id="detalleCotizacion"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
      </div>
    </div>
  </div>
</div>
