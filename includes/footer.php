<footer class="site-footer mt-5">
  <div class="container py-5">
    <div class="row g-4">
      <div class="col-md-4">
        <img src="<?= pcv_asset('img/logo-header.png') ?>" alt="PCV" class="footer-logo mb-3">
        <p class="mb-0 opacity-75"><?= pcv_esc(PCV_ESLOGAN) ?>.</p>
      </div>
      <div class="col-md-4">
        <h6 class="text-uppercase">Contacto</h6>
        <ul class="list-unstyled small mb-0">
          <li><i class="fas fa-envelope me-2"></i><a href="mailto:<?= pcv_esc(PCV_EMAIL) ?>"><?= pcv_esc(PCV_EMAIL) ?></a></li>
          <li><i class="fas fa-phone me-2"></i><a href="tel:<?= pcv_esc(PCV_TEL1) ?>"><?= pcv_esc(PCV_TEL1) ?></a></li>
          <li><i class="fas fa-phone me-2"></i><a href="tel:<?= pcv_esc(PCV_TEL2) ?>"><?= pcv_esc(PCV_TEL2) ?></a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h6 class="text-uppercase">Síguenos</h6>
        <a class="btn btn-sm btn-outline-light me-2" href="https://instagram.com/<?= pcv_esc(PCV_IG) ?>" target="_blank" rel="noopener"><i class="fab fa-instagram"></i> @<?= pcv_esc(PCV_IG) ?></a>
        <a class="btn btn-sm btn-outline-light" href="<?= pcv_esc(PCV_FB) ?>" target="_blank" rel="noopener"><i class="fab fa-facebook"></i> Facebook</a>
        <div class="mt-3">
          <a class="small text-white-50" href="<?= pcv_asset('img/pcv-brochure.pdf') ?>" target="_blank"><i class="fas fa-file-pdf me-1"></i> Descargar brochure</a>
        </div>
      </div>
    </div>
    <hr class="border-light opacity-25 my-4">
    <div class="small text-center opacity-75">&copy; <?= date('Y') ?> PCV Soluciones Industriales</div>
  </div>
</footer>
<a class="whatsapp-float" href="https://wa.me/<?= pcv_esc(PCV_WHATSAPP) ?>?text=<?= rawurlencode('Hola PCV, me interesa cotizar.') ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
  <i class="fab fa-whatsapp"></i>
</a>
<script src="<?= pcv_asset('plugins/jquery-3.7.1.min.js') ?>"></script>
<script src="<?= pcv_asset('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= pcv_asset('plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script src="<?= pcv_asset('plugins/imask/imask.min.js') ?>"></script>
<script src="<?= pcv_asset('js/site.js') ?>"></script>
</body>
</html>
