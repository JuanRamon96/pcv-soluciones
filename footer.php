<footer class="site-footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <img src="<?= pcv_asset('img/logo-white.png') ?>" alt="PCV Soluciones Industriales" class="footer-brand-logo mb-3" style="max-height: 82px; max-width: 290px; width: auto; object-fit: contain;">
        <p class="mb-3 text-white fw-bold">"<?= pcv_esc(PCV_ESLOGAN) ?>."</p>
        <p class="small opacity-75 mb-0">Empresa especializada en la iluminación industrial, el diseño, fabricación y comercialización de partes, equipos, piezas, productos metálicos y de acrílico necesarios para la INDUSTRIA METAL MECÁNICA.</p>
      </div>
      <div class="col-sm-6 col-lg-2">
        <h6>Las 3 Divisiones</h6>
        <ul class="list-unstyled small mb-0">
          <li class="mb-2"><a href="<?= pcv_url('catalogo.php?clasificacion=metal-mecanica') ?>">1. Metal Mecánica</a></li>
          <li class="mb-2"><a href="<?= pcv_url('catalogo.php?clasificacion=luminaria') ?>">2. Iluminación Industrial</a></li>
          <li class="mb-2"><a href="<?= pcv_url('catalogo.php?clasificacion=refaccionaria') ?>">3. Automatización y Mobiliario</a></li>
          <li class="mb-2"><a href="<?= pcv_url('catalogo.php') ?>">Ver Catálogo Completo</a></li>
        </ul>
      </div>
      <div class="col-sm-6 col-lg-3">
        <h6>Contacto Directo</h6>
        <ul class="list-unstyled small mb-0">
          <li class="mb-2"><i class="fas fa-envelope me-2 text-success"></i><a href="mailto:<?= pcv_esc(PCV_EMAIL) ?>"><?= pcv_esc(PCV_EMAIL) ?></a></li>
          <li class="mb-2"><i class="fas fa-phone me-2 text-success"></i><a href="tel:<?= pcv_esc(PCV_TEL1) ?>"><?= pcv_esc(PCV_TEL1) ?></a></li>
          <li class="mb-2"><i class="fas fa-phone me-2 text-success"></i><a href="tel:<?= pcv_esc(PCV_TEL2) ?>"><?= pcv_esc(PCV_TEL2) ?></a></li>
          <li class="mb-2"><i class="fab fa-whatsapp me-2 text-success"></i><a href="https://wa.me/<?= pcv_esc(PCV_WHATSAPP) ?>" target="_blank" rel="noopener">WhatsApp Inmediato</a></li>
        </ul>
      </div>
      <div class="col-lg-3">
        <h6>Atención y Redes</h6>
        <p class="small opacity-75 mb-3">Atención directa para cotizaciones industriales, levantamiento de requerimientos y visitas a planta.</p>
        <div class="d-flex flex-column gap-2">
          <a class="btn btn-sm btn-outline-light d-flex align-items-center justify-content-center gap-2" href="https://instagram.com/<?= pcv_esc(PCV_IG) ?>" target="_blank" rel="noopener">
            <i class="fab fa-instagram text-danger"></i> <span>Síguenos en @<?= pcv_esc(PCV_IG) ?></span>
          </a>
          <a class="btn btn-sm btn-outline-light d-flex align-items-center justify-content-center gap-2" href="<?= pcv_esc(PCV_FB) ?>" target="_blank" rel="noopener">
            <i class="fab fa-facebook-f text-primary"></i> <span>Facebook Oficial</span>
          </a>
        </div>
      </div>
    </div>
    <div class="footer-bottom text-center">
      <div class="container">&copy; <?= date('Y') ?> PCV Soluciones Industriales. Todos los derechos reservados. | Guadalajara, Jalisco, México.</div>
    </div>
  </div>
</footer>

<!-- Botón Flotante Bolsa de Cotización (Shopify RFQ Style) -->
<button class="rfq-floating-btn" id="rfqFloatingBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#rfqDrawer" aria-controls="rfqDrawer" aria-label="Ver lista de cotización">
  <i class="fas fa-file-invoice"></i>
  <span class="rfq-floating-count rfq-count">0</span>
  <span class="d-none d-sm-inline">Mi Cotización</span>
</button>

<!-- Offcanvas Drawer: Lista de Cotización -->
<div class="offcanvas offcanvas-end rfq-drawer" tabindex="-1" id="rfqDrawer" aria-labelledby="rfqDrawerLabel">
  <div class="offcanvas-header rfq-drawer-header d-flex justify-content-between align-items-center">
    <div>
      <h5 class="offcanvas-title" id="rfqDrawerLabel"><i class="fas fa-file-invoice me-2 text-success"></i> Lista de Cotización</h5>
      <small class="opacity-75 d-block">Sin precios públicos · Respuesta técnica inmediata</small>
    </div>
    <button type="button" class="btn-close btn-close-white" data-bs-toggle="offcanvas" data-bs-target="#rfqDrawer" aria-label="Cerrar"></button>
  </div>
  
  <div class="offcanvas-body rfq-drawer-body">
    <!-- Estado Vacío -->
    <div id="rfqEmptyState" class="text-center py-5">
      <div class="mb-3 text-muted" style="font-size: 3.5rem;">
        <i class="fas fa-clipboard-list opacity-50"></i>
      </div>
      <h6 class="fw-bold mb-2">Tu lista de cotización está vacía</h6>
      <p class="small text-muted mb-4">Navega por las 3 divisiones de nuestro catálogo y haz clic en <strong>"+ A mi cotización"</strong> en los servicios o productos que requieras.</p>
      <a href="<?= pcv_url('catalogo.php') ?>" class="btn btn-navy btn-sm" data-bs-dismiss="offcanvas">
        <i class="fas fa-magnifying-glass me-1"></i> Explorar Catálogo
      </a>
    </div>

    <!-- Lista de Productos Agregados -->
    <div id="rfqItemsList" style="display: none;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="small text-muted fw-bold text-uppercase">Productos y servicios elegidos (<span class="rfq-count">0</span>)</span>
        <button class="btn btn-link btn-sm text-danger p-0 small text-decoration-none" id="btnClearRfq">
          <i class="fas fa-trash-can me-1"></i> Vaciar lista
        </button>
      </div>

      <div id="rfqItemsContainer" class="mb-4">
        <!-- Renderizado dinámico vía JavaScript -->
      </div>

      <div class="card p-3 border-0 bg-light mb-3">
        <h6 class="fw-bold mb-2 text-navy" style="font-size: 0.9rem;"><i class="fas fa-user-check me-1 text-primary"></i> Datos para tu presupuesto</h6>
        <div class="row g-2">
          <div class="col-12">
            <input type="text" id="rfqNombre" class="form-control form-control-sm" placeholder="Tu Nombre *" required>
          </div>
          <div class="col-12">
            <input type="text" id="rfqEmpresa" class="form-control form-control-sm" placeholder="Empresa o Razón Social">
          </div>
          <div class="col-12">
            <input type="tel" id="rfqTelefono" class="form-control form-control-sm" placeholder="Teléfono / WhatsApp *" required>
          </div>
          <div class="col-12">
            <input type="email" id="rfqCorreo" class="form-control form-control-sm" placeholder="Correo Electrónico">
          </div>
          <div class="col-12">
            <textarea id="rfqNotas" class="form-control form-control-sm" rows="2" placeholder="Especificaciones, material, tolerancias o cantidad estimada…"></textarea>
          </div>
        </div>
      </div>

      <div class="d-grid gap-2">
        <button type="button" class="btn btn-whatsapp py-2 fw-bold" id="btnSendRfqWhatsApp">
          <i class="fab fa-whatsapp me-2 fs-5"></i> Enviar a WhatsApp en 1 Clic
        </button>
        <button type="button" class="btn btn-navy py-2 fw-bold" id="btnSendRfqDirect">
          <i class="fas fa-paper-plane me-2"></i> Solicitar Presupuesto Formal
        </button>
      </div>
    </div>
  </div>
</div>

<script src="<?= pcv_asset('plugins/jquery-3.7.1.min.js') ?>"></script>
<script src="<?= pcv_asset('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= pcv_asset('plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script src="<?= pcv_asset('plugins/imask/imask.min.js') ?>"></script>
<script>
  window.PCV_BASE_URL = '<?= pcv_esc(pcv_rel_prefix()) ?>';
  window.PCV_WHATSAPP = '<?= PCV_WHATSAPP ?>';
</script>
<script src="<?= pcv_asset('js/site.js?v=' . filemtime(PCV_ROOT . '/assets/js/site.js')) ?>"></script>
</body>
</html>
