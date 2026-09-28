/**
 * PCV Soluciones Industriales — Frontend & Lista de Cotización
 */

(function($) {
  'use strict';

  var STORAGE_KEY = 'pcv_rfq_cart_v1';

  // Obtener carrito RFQ
  function getRfqCart() {
    try {
      var data = localStorage.getItem(STORAGE_KEY);
      return data ? JSON.parse(data) : [];
    } catch (e) {
      return [];
    }
  }

  // Guardar carrito RFQ
  function saveRfqCart(cart) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
    updateRfqUI();
  }

  // Actualizar contadores y badges
  function updateRfqUI() {
    var cart = getRfqCart();
    var count = 0;
    cart.forEach(function(item) {
      count += (item.cantidad || 1);
    });

    $('.rfq-count').text(count);

    // Cambiar estado visual de botones en tarjetas
    $('.btn-rfq-add').each(function() {
      var id = $(this).data('id');
      var inCart = cart.some(function(it) { return it.id == id; });
      if (inCart) {
        $(this).addClass('in-cart').html('<i class="fas fa-check me-1"></i> Agregado');
      } else {
        $(this).removeClass('in-cart').html('<i class="fas fa-plus me-1"></i> A mi lista');
      }
    });

    renderRfqDrawer();
  }

  // Agregar producto o servicio a la lista de cotización
  window.addToRfq = function(item) {
    var cart = getRfqCart();
    var existingIndex = -1;
    for (var i = 0; i < cart.length; i++) {
      if (cart[i].id == item.id) {
        existingIndex = i;
        break;
      }
    }

    if (existingIndex > -1) {
      cart[existingIndex].cantidad = (cart[existingIndex].cantidad || 1) + 1;
    } else {
      item.cantidad = 1;
      cart.push(item);
    }

    saveRfqCart(cart);

    // Feedback visual
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2000,
      timerProgressBar: true
    });
    Toast.fire({
      icon: 'success',
      title: 'Agregado a tu lista de cotización'
    });
  };

  // Quitar producto o servicio
  function removeFromRfq(id) {
    var cart = getRfqCart().filter(function(it) { return it.id != id; });
    saveRfqCart(cart);
  }

  // Modificar cantidad
  function updateItemQty(id, delta) {
    var cart = getRfqCart();
    for (var i = 0; i < cart.length; i++) {
      if (cart[i].id == id) {
        cart[i].cantidad = (cart[i].cantidad || 1) + delta;
        if (cart[i].cantidad <= 0) {
          cart.splice(i, 1);
        }
        break;
      }
    }
    saveRfqCart(cart);
  }

  // Renderizar contenido del Drawer
  function renderRfqDrawer() {
    var cart = getRfqCart();
    var $empty = $('#rfqEmptyState');
    var $list = $('#rfqItemsList');
    var $container = $('#rfqItemsContainer');

    if (!cart || cart.length === 0) {
      $empty.show();
      $list.hide();
      return;
    }

    $empty.hide();
    $list.show();

    var html = '';
    cart.forEach(function(item) {
      html += '<div class="rfq-item-card" data-id="' + item.id + '">';
      html += '  <img src="' + item.imagen + '" alt="' + item.nombre + '" class="rfq-item-img">';
      html += '  <div class="rfq-item-info">';
      html += '    <div class="rfq-item-title text-navy">' + item.nombre + '</div>';
      html += '    <small class="text-muted d-block mb-1">' + (item.clasificacion || 'Industrial') + '</small>';
      html += '    <div class="rfq-item-qty">';
      html += '      <button type="button" class="btn-qty-minus" data-id="' + item.id + '">-</button>';
      html += '      <span>' + (item.cantidad || 1) + '</span>';
      html += '      <button type="button" class="btn-qty-plus" data-id="' + item.id + '">+</button>';
      html += '    </div>';
      html += '  </div>';
      html += '  <button type="button" class="rfq-item-remove" data-id="' + item.id + '" title="Eliminar"><i class="fas fa-trash-can"></i></button>';
      html += '</div>';
    });

    $container.html(html);
  }

  // Inicialización cuando carga el DOM
  $(function() {
    // Render inicial del carrito RFQ
    updateRfqUI();

    // Auto cerrar menú móvil al hacer clic en un enlace de ancla
    $(document).on('click', '#navMain .nav-link:not(.dropdown-toggle)', function() {
      if ($(window).width() < 992) {
        var navCollapse = document.getElementById('navMain');
        if (navCollapse && navCollapse.classList.contains('show')) {
          var bsCollapse = bootstrap.Collapse.getInstance(navCollapse);
          if (bsCollapse) {
            bsCollapse.hide();
          } else {
            $(navCollapse).collapse('hide');
          }
        }
      }
    });

    // Eventos del carrito
    $(document).on('click', '.btn-rfq-add', function(e) {
      e.preventDefault();
      var item = {
        id: $(this).data('id'),
        nombre: $(this).data('nombre'),
        clasificacion: $(this).data('clasificacion'),
        imagen: $(this).data('imagen')
      };
      window.addToRfq(item);
    });

    $(document).on('click', '.rfq-item-remove', function() {
      var id = $(this).data('id');
      removeFromRfq(id);
    });

    $(document).on('click', '.btn-qty-plus', function() {
      var id = $(this).data('id');
      updateItemQty(id, 1);
    });

    $(document).on('click', '.btn-qty-minus', function() {
      var id = $(this).data('id');
      updateItemQty(id, -1);
    });

    $('#btnClearRfq').on('click', function() {
      Swal.fire({
        title: '¿Vaciar lista?',
        text: 'Se quitarán todos los productos y servicios de tu lista de cotización',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0C2F55',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, vaciar',
        cancelButtonText: 'Cancelar'
      }).then(function(result) {
        if (result.isConfirmed) {
          saveRfqCart([]);
        }
      });
    });

    // Enviar a WhatsApp en 1 Clic
    $('#btnSendRfqWhatsApp').on('click', function() {
      var cart = getRfqCart();
      if (!cart || cart.length === 0) return;

      var nombre = $('#rfqNombre').val().trim();
      var empresa = $('#rfqEmpresa').val().trim();
      var tel = $('#rfqTelefono').val().trim();
      var notas = $('#rfqNotas').val().trim();

      if (!nombre || !tel) {
        Swal.fire({
          icon: 'warning',
          title: 'Datos requeridos',
          text: 'Por favor ingresa tu Nombre y Teléfono para dirigir tu cotización.'
        });
        return;
      }

      var msg = "Hola PCV Soluciones Industriales,\n";
      msg += "Soy *" + nombre + "*";
      if (empresa) msg += " de *" + empresa + "*";
      msg += ".\n\nHola, me gustaría pedir cotización para los siguientes productos y servicios de su catálogo:\n\n";

      cart.forEach(function(it, idx) {
        msg += (idx + 1) + ". *" + it.nombre + "* (Cant: " + (it.cantidad || 1) + ")\n";
      });

      if (notas) {
        msg += "\nDetalles/Notas: " + notas + "\n";
      }
      msg += "\nMi Teléfono de contacto: " + tel;

      var waNumber = window.PCV_WHATSAPP || '5213311444743';
      var waUrl = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(msg);

      window.open(waUrl, '_blank');
    });

    // Enviar Solicitud Formal por AJAX (guarda en DB)
    $('#btnSendRfqDirect').on('click', function() {
      var cart = getRfqCart();
      if (!cart || cart.length === 0) return;

      var nombre = $('#rfqNombre').val().trim();
      var empresa = $('#rfqEmpresa').val().trim();
      var tel = $('#rfqTelefono').val().trim();
      var correo = $('#rfqCorreo').val().trim();
      var notas = $('#rfqNotas').val().trim();

      if (!nombre || !tel) {
        Swal.fire({
          icon: 'warning',
          title: 'Datos requeridos',
          text: 'Por favor ingresa tu Nombre y Teléfono para generar tu folio.'
        });
        return;
      }

      var postData = {
        nombre: nombre,
        empresa: empresa,
        telefono: tel,
        correo: correo,
        mensaje: notas,
        items_json: JSON.stringify(cart),
        origen: 'rfq_drawer'
      };

      var $btn = $(this);
      $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Procesando…');

      $.post((window.PCV_BASE_URL || '') + '/cotizar.php', postData, function(resp) {
        $btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i> Solicitar Presupuesto Formal');
        var r = resp;
        try { if (typeof resp === 'string') r = JSON.parse(resp); } catch(err) {}

        if (r && r.ok) {
          Swal.fire({
            icon: 'success',
            title: '¡Cotización Registrada!',
            html: 'Tu folio es: <strong class="text-primary">' + r.folio + '</strong><br>Nuestro equipo técnico se pondrá en contacto a la brevedad.',
            confirmButtonText: 'Continuar por WhatsApp',
            showCancelButton: true,
            cancelButtonText: 'Cerrar'
          }).then(function(result) {
            if (result.isConfirmed && r.whatsapp) {
              window.open(r.whatsapp, '_blank');
            }
            saveRfqCart([]);
            var drawerEl = document.getElementById('rfqDrawer');
            var drawer = bootstrap.Offcanvas.getInstance(drawerEl);
            if (drawer) drawer.hide();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: (r && r.error) || 'Ocurrió un problema al enviar tu solicitud.'
          });
        }
      }).fail(function() {
        $btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i> Solicitar Presupuesto Formal');
        Swal.fire({ icon: 'error', title: 'Error de conexión', text: 'Intenta de nuevo o contáctanos por WhatsApp.' });
      });
    });

    // Filtro en vivo del catálogo (Shopify style instant search)
    $('#catalogSearchInput').on('keyup input', function() {
      var val = $(this).val().toLowerCase().trim();
      $('.catalog-item-col').each(function() {
        var text = $(this).text().toLowerCase();
        if (text.indexOf(val) > -1) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
      checkCatalogEmpty();
    });

    function checkCatalogEmpty() {
      var visible = $('.catalog-item-col:visible').length;
      if (visible === 0) {
        $('#catalogNoResults').show();
      } else {
        $('#catalogNoResults').hide();
      }
    }

    // Máscara de teléfono
    var telInputs = document.querySelectorAll('input[type="tel"]');
    if (telInputs.length && window.IMask) {
      telInputs.forEach(function(el) {
        IMask(el, { mask: '00 0000 0000' });
      });
    }

    // Formulario de cotización tradicional
    $('#formCotizar').on('submit', function(e) {
      e.preventDefault();
      var $f = $(this);
      $.post($f.attr('action') || 'cotizar.php', $f.serialize(), function(resp) {
        var r = resp;
        try { if (typeof resp === 'string') r = JSON.parse(resp); } catch(err) {}
        if (r && r.ok) {
          Swal.fire({
            icon: 'success',
            title: '¡Cotización enviada!',
            text: 'Te redirigimos a WhatsApp…',
            timer: 1800,
            showConfirmButton: false
          }).then(function() {
            if (r.whatsapp) window.open(r.whatsapp, '_blank');
          });
          $f[0].reset();
        } else {
          Swal.fire({ icon: 'error', title: 'No se pudo enviar', text: (r && r.error) || 'Intenta de nuevo' });
        }
      }).fail(function() {
        Swal.fire({ icon: 'error', title: 'Error de red' });
      });
    });

    // Iniciar Simulador CNC Láser si existe en la página
    initCncLaserSimulator();

    // Animación de contadores de estadísticas
    initAnimatedCounters();

    // Sistema de animaciones al hacer scroll (Scroll Reveal a 60fps)
    initScrollReveal();

    // Carruseles automáticos por división con flechas y autoplay
    initCategoryCarousels();

  });

  // ==========================================================================
  // Simulador CNC Láser / Soldadura en Vivo con Foco y Chispas
  // ==========================================================================
  function initCncLaserSimulator() {
    var path = document.getElementById('cncToolpath');
    var head = document.getElementById('cncLaserHead');
    var rail = document.getElementById('cncGantryRail');
    var canvas = document.getElementById('cncSparksCanvas');
    var coordX = document.getElementById('cncCoordX');
    var coordY = document.getElementById('cncCoordY');
    var coordZ = document.getElementById('cncCoordZ');

    if (!path || !head || !canvas) return;

    var ctx = canvas.getContext('2d');
    var pathLength = path.getTotalLength();
    var progress = 0;
    var speed = 0.0022; // velocidad fluida
    var sparks = [];

    function resizeCanvas() {
      if (!canvas) return;
      canvas.width = canvas.offsetWidth || 500;
      canvas.height = canvas.offsetHeight || 290;
    }
    resizeCanvas();
    $(window).on('resize', resizeCanvas);

    function createSpark(x, y) {
      var angle = (Math.PI * 0.15) + Math.random() * (Math.PI * 0.7);
      if (Math.random() > 0.5) angle = -angle;
      var spd = 2 + Math.random() * 5.5;
      return {
        x: x,
        y: y,
        vx: Math.cos(angle) * spd,
        vy: Math.sin(angle) * spd + 1,
        gravity: 0.18,
        size: 1.2 + Math.random() * 2,
        life: 1,
        decay: 0.025 + Math.random() * 0.035,
        color: Math.random() > 0.35 ? '#ffedd5' : '#38bdf8'
      };
    }

    function animateCnc() {
      progress += speed;
      if (progress > 1) progress = 0;

      var pt = path.getPointAtLength(progress * pathLength);
      var svgW = 500;
      var svgH = 290;
      var cW = canvas.width;
      var cH = canvas.height;

      var posX = (pt.x / svgW) * cW;
      var posY = (pt.y / svgH) * cH;

      head.style.left = posX + 'px';
      head.style.top = posY + 'px';
      if (rail) {
        rail.style.top = (posY - 8) + 'px';
      }

      if (coordX) coordX.textContent = (pt.x * 0.85 + 40).toFixed(2);
      if (coordY) coordY.textContent = (pt.y * 0.65 + 15).toFixed(2);
      if (coordZ) coordZ.textContent = (-1.5 + Math.sin(progress * 18) * 0.25).toFixed(2);

      // Emisión de chispas en el punto de contacto
      var count = 3 + Math.floor(Math.random() * 4);
      for (var i = 0; i < count; i++) {
        sparks.push(createSpark(posX, posY));
      }

      // Dibujar partículas
      ctx.clearRect(0, 0, cW, cH);
      for (var j = sparks.length - 1; j >= 0; j--) {
        var s = sparks[j];
        s.x += s.vx;
        s.y += s.vy;
        s.vy += s.gravity;
        s.life -= s.decay;

        if (s.life <= 0) {
          sparks.splice(j, 1);
          continue;
        }

        ctx.beginPath();
        ctx.arc(s.x, s.y, s.size * s.life, 0, Math.PI * 2);
        ctx.fillStyle = s.color;
        ctx.globalAlpha = s.life;
        ctx.shadowBlur = 8;
        ctx.shadowColor = s.color;
        ctx.fill();
        ctx.shadowBlur = 0;
      }
      ctx.globalAlpha = 1;

      requestAnimationFrame(animateCnc);
    }

    requestAnimationFrame(animateCnc);
  }

  // ==========================================================================
  // Animación de Contadores de Estadísticas (Con IntersectionObserver)
  // ==========================================================================
  function initAnimatedCounters() {
    var $stats = $('.stat-num');
    if (!$stats.length) return;

    var animated = false;
    function triggerCounters() {
      if (animated) return;
      animated = true;
      $stats.each(function() {
        var $this = $(this);
        var text = $this.text().trim();
        var prefix = text.indexOf('+') > -1 ? '+' : '';
        var suffix = text.indexOf('%') > -1 ? '%' : (text.indexOf('/7') > -1 ? '/7' : '');
        var target = parseInt(text.replace(/[^0-9]/g, ''), 10) || 0;
        
        if (!target) return;
        var count = 0;
        var step = Math.max(1, Math.floor(target / 25));
        var interval = setInterval(function() {
          count += step;
          if (count >= target) {
            count = target;
            clearInterval(interval);
          }
          $this.text(prefix + count + suffix);
        }, 35);
      });
    }

    if ('IntersectionObserver' in window) {
      var counterObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            triggerCounters();
            counterObserver.disconnect();
          }
        });
      }, { threshold: 0.2 });
      
      var statsWrap = document.querySelector('.stats-strip') || $stats[0];
      if (statsWrap) counterObserver.observe(statsWrap);
    } else {
      $(window).on('scroll.stats', function() {
        var top = $(window).scrollTop() + $(window).height();
        var elemTop = $stats.first().offset().top;
        if (top > elemTop) {
          triggerCounters();
          $(window).off('scroll.stats');
        }
      });
      triggerCounters();
    }
  }

  // ==========================================================================
  // Sistema de Animaciones de Aparición al Scroll (Scroll Reveal & Entrance)
  // Acelerado por GPU a 60fps con IntersectionObserver y Stagger Automático
  // ==========================================================================
  function initScrollReveal() {
    // Si el usuario prefiere no tener movimiento (accesibilidad)
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      $('.pcv-reveal').addClass('is-revealed');
      return;
    }

    // Reglas automáticas de revelado para elementos de la página
    var autoRules = [
      { sel: '.section-head', anim: 'pcv-reveal-up' },
      { sel: '.section-title', anim: 'pcv-reveal-up' },
      { sel: '.stats-strip .stat-item', anim: 'pcv-reveal-up', stagger: 80 },
      { sel: '.division-card', anim: 'pcv-reveal-card', stagger: 140 },
      { sel: '.category-carousel-wrap', anim: 'pcv-reveal-up', stagger: 150 },
      { sel: '.catalog-item-col .shopify-card', anim: 'pcv-reveal-card', stagger: 90 },
      { sel: '.cnc-simulator-card', anim: 'pcv-reveal-zoom' },
      { sel: '.cripar-feature-box', anim: 'pcv-reveal-left' },
      { sel: '.cripar-image-col', anim: 'pcv-reveal-right' },
      { sel: '.cripar-pill', anim: 'pcv-reveal-up', stagger: 60 },
      { sel: '.shimpol-step-card, .process-step, .step-item, .process-card', anim: 'pcv-reveal-card', stagger: 120 },
      { sel: '.materials-card', anim: 'pcv-reveal-up', stagger: 100 },
      { sel: '.materials-strip .col-6, .materials-strip .col-md-4, .materials-strip .col-lg-2, .material-chip, .material-pill', anim: 'pcv-reveal-up', stagger: 40 },
      { sel: '.cta-banner-wrap, .quote-banner, .cta-cripar, .cta-section', anim: 'pcv-reveal-zoom' },
      { sel: '.site-footer .col-lg-4, .site-footer .col-md-6', anim: 'pcv-reveal-up', stagger: 80 }
    ];

    autoRules.forEach(function(r) {
      var $items = $(r.sel);
      $items.each(function(i) {
        var $el = $(this);
        if (!$el.hasClass('pcv-reveal')) {
          $el.addClass('pcv-reveal ' + r.anim);
          if (r.stagger) {
            var delay = (i % 6) * r.stagger;
            $el.css('transition-delay', delay + 'ms');
          }
        }
      });
    });

    if (!('IntersectionObserver' in window)) {
      $('.pcv-reveal').addClass('is-revealed');
      return;
    }

    var observer = new IntersectionObserver(function(entries, obs) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-revealed');
          obs.unobserve(entry.target);
        }
      });
    }, {
      root: null,
      rootMargin: '0px 0px -40px 0px', // Revela con anticipación para sensación instantánea
      threshold: 0.1
    });

    function observeElements() {
      var list = document.querySelectorAll('.pcv-reveal:not(.is-revealed)');
      list.forEach(function(el) {
        var rect = el.getBoundingClientRect();
        // Si ya está dentro de la ventana al cargar
        if (rect.top < window.innerHeight - 40 && rect.bottom > 0) {
          setTimeout(function() {
            el.classList.add('is-revealed');
          }, 80);
        } else {
          observer.observe(el);
        }
      });
    }

    observeElements();

    // Exponer función para reactivar en componentes dinámicos (búsqueda de catálogo, etc.)
    window.refreshScrollReveal = function() {
      observeElements();
    };
  }

  // ==========================================================================
  // Carruseles Automáticos por División con Flechas de Navegación y Autoplay
  // ==========================================================================
  function initCategoryCarousels() {
    $('.category-carousel-wrap').each(function(carouselIdx) {
      var $wrap = $(this);
      var $track = $wrap.find('.category-carousel-track');
      var $btnPrev = $wrap.find('.carousel-arrow-btn.prev');
      var $btnNext = $wrap.find('.carousel-arrow-btn.next');
      var trackElem = $track[0];
      if (!trackElem) return;

      var isHovered = false;
      var autoPlayTimer = null;
      var autoDelay = 3800 + (carouselIdx * 450); // 3.8s, 4.25s, 4.7s desfasados

      function getStepWidth() {
        var $firstSlide = $track.find('.category-carousel-slide').first();
        var width = $firstSlide.outerWidth(true);
        return width > 50 ? width : 320;
      }

      function slideNext() {
        var maxScroll = trackElem.scrollWidth - trackElem.clientWidth;
        if (trackElem.scrollLeft >= maxScroll - 20) {
          trackElem.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
          trackElem.scrollBy({ left: getStepWidth(), behavior: 'smooth' });
        }
      }

      function slidePrev() {
        var maxScroll = trackElem.scrollWidth - trackElem.clientWidth;
        if (trackElem.scrollLeft <= 20) {
          trackElem.scrollTo({ left: maxScroll, behavior: 'smooth' });
        } else {
          trackElem.scrollBy({ left: -getStepWidth(), behavior: 'smooth' });
        }
      }

      $btnNext.off('click').on('click', function(e) {
        e.preventDefault();
        slideNext();
      });

      $btnPrev.off('click').on('click', function(e) {
        e.preventDefault();
        slidePrev();
      });

      // Pausar en hover / touch para permitir al usuario interactuar
      $wrap.on('mouseenter touchstart', function() {
        isHovered = true;
        clearInterval(autoPlayTimer);
      });

      $wrap.on('mouseleave touchend', function() {
        isHovered = false;
        startAutoplay();
      });

      function startAutoplay() {
        clearInterval(autoPlayTimer);
        autoPlayTimer = setInterval(function() {
          if (!isHovered && document.visibilityState === 'visible') {
            slideNext();
          }
        }, autoDelay);
      }

      startAutoplay();
    });
  }

})(jQuery);
