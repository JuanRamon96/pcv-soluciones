<!-- Toolbar de acciones de Dashboard -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <div>
    <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-grid-fill text-success me-2"></i>Dashboard General</h4>
    <small class="text-muted">Panel de gestión y control de PCV Soluciones Industriales</small>
  </div>
  <button type="button" class="btn btn-sm btn-outline-secondary cargarVista d-flex align-items-center gap-1 rounded-3" carga="v_dashboard" titulo="Dashboard General">
    <i class="bi bi-arrow-clockwise"></i>
    <span>Actualizar datos</span>
  </button>
</div>

<!-- Spark Metric Cards Row -->
<div class="row g-4 mb-4">
  <div class="col-md-4 col-12">
    <div class="spark-stat-card h-100">
      <div>
        <p class="spark-stat-label">Servicios Activos</p>
        <div class="spark-stat-num" id="statServicios">—</div>
        <small class="text-muted"><i class="bi bi-arrow-up-right text-success me-1"></i>En catálogo público</small>
      </div>
      <div class="spark-stat-icon-wrap forest">
        <i class="bi bi-tools"></i>
      </div>
    </div>
  </div>

  <div class="col-md-4 col-12">
    <div class="spark-stat-card h-100">
      <div>
        <p class="spark-stat-label">Productos y Servicios Activos</p>
        <div class="spark-stat-num" id="statProductos">—</div>
        <small class="text-muted"><i class="bi bi-check-circle text-success me-1"></i>En catálogo público</small>
      </div>
      <div class="spark-stat-icon-wrap green">
        <i class="bi bi-boxes"></i>
      </div>
    </div>
  </div>

  <div class="col-md-4 col-12">
    <div class="spark-stat-card h-100">
      <div>
        <p class="spark-stat-label">Cotizaciones</p>
        <div class="spark-stat-num" id="statCotizaciones">—</div>
        <small class="text-muted"><i class="bi bi-clock-history text-warning me-1"></i>Por atender</small>
      </div>
      <div class="spark-stat-icon-wrap lime">
        <i class="bi bi-file-earmark-text"></i>
      </div>
    </div>
  </div>
</div>

<!-- Gráfica de Cotizaciones en el Tiempo y Acciones Rápidas -->
<div class="row g-4">
  <div class="col-lg-8 col-12">
    <div class="card border-0 shadow-sm h-100 rounded-4" style="background: #FFFFFF;">
      <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
        <div>
          <h5 class="mb-0 fw-bold fs-6 text-dark d-flex align-items-center gap-2">
            <i class="bi bi-graph-up-arrow text-success"></i> Cotizaciones Recibidas en el Tiempo
          </h5>
          <small class="text-muted">Evolución de solicitudes industriales recibidas</small>
        </div>
        <div class="d-flex align-items-center gap-2">
          <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-semibold" id="lblTotalCotizacionesBadge">
            <i class="bi bi-check2-circle me-1"></i> <span id="statCotizacionesTotales">0</span> cotizaciones totales
          </span>
        </div>
      </div>
      <div class="card-body p-4">
        <div id="chartCotizacionesTiempo" style="min-height: 290px;">
          <div class="text-center py-5 text-muted">
            <div class="spinner-border spinner-border-sm text-success me-2" role="status"></div>
            Cargando gráfica de cotizaciones...
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-12">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header py-3">
        <h5 class="mb-0 fw-bold fs-6"><i class="bi bi-lightning-charge text-warning me-2"></i> Acciones Directas</h5>
      </div>
      <div class="card-body d-flex flex-column justify-content-around gap-2">
        <button type="button" class="btn btn-outline-primary d-flex align-items-center justify-content-between p-3 rounded-3 cargarVista" carga="v_productos" titulo="Productos y Servicios">
          <span class="fw-semibold"><i class="bi bi-plus-circle me-2"></i> Gestionar Productos</span>
          <i class="bi bi-chevron-right text-muted"></i>
        </button>
        <button type="button" class="btn btn-outline-success d-flex align-items-center justify-content-between p-3 rounded-3 cargarVista" carga="v_cotizaciones" titulo="Cotizaciones Recibidas">
          <span class="fw-semibold"><i class="bi bi-inbox me-2"></i> Ver Cotizaciones</span>
          <i class="bi bi-chevron-right text-muted"></i>
        </button>
        <a href="#PCV_SITE_URL#" target="_blank" class="btn btn-outline-dark d-flex align-items-center justify-content-between p-3 rounded-3">
          <span class="fw-semibold"><i class="bi bi-globe me-2"></i> Abrir Landing Page</span>
          <i class="bi bi-box-arrow-up-right text-muted"></i>
        </a>
      </div>
    </div>
  </div>
</div>
