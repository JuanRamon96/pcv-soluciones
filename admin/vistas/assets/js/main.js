const searchRegExp = new RegExp(',', 'g');

function moneda() {
    $(".dinero").each(function () {
        var n = parseFloat(String($(this).html()).replace('$', '').replace(/,/g, ''));
        if (isNaN(n)) return;
        var fmt = new Intl.NumberFormat('en-US').format(Math.round(Math.abs(n) * 100) / 100);
        $(this).html((n < 0 ? '-$' : '$') + fmt);
    });
}

jQuery(document).ready(function ($) {
    setInterval(function () {
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: 'metodo=renovar'
        }).done(function () {
            console.log('Sesion renovada');
        }).fail(function () {
            console.log('error ajax');
        });
    }, 60000 * 10);

    setTimeout(function () {
        if ($('#bMenuDashboard').length > 0) {
            $('#bMenuDashboard').click();
        } else {
            $('#carga').hide();
        }
    }, 400);

    $(document).on('click', '.cargarVista', function () {
        var nombre = $(this).attr('carga'),
            titulo = $(this).attr('titulo'),
            atri = $(this).attr('atri') || '',
            pesta = $(this).attr('pesta') || '';

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: 'metodo=cambiar&accion=' + nombre + '&atri=' + atri + '&pesta=' + pesta,
            beforeSend: function () {
                $('#carga').show();
            }
        })
        .done(function (res) {
            $('#verVista').html(res);
            $('.vistaTitulo').html(titulo);
            if (typeof crearDataTable === 'function') {
                crearDataTable();
            }
            if (typeof window[nombre] === 'function') {
                window[nombre]();
            }
            moneda();
        })
        .fail(function () {
            console.log('Error ajax');
        })
        .always(function () {
            $('#carga').hide();
        });
    });

    $(document).on('click', '.sidebar-item', function () {
        $('.sidebar-item').removeClass('active');
        $(this).addClass('active');
    });

    // Helper to check if we are on desktop
    function isDesktopSidebar() {
        return window.innerWidth >= 1025;
    }

    // Synchronize toggle button icon (Spark Admin bi-chevron-bar-left / bi-chevron-bar-right)
    function syncSidebarToggleIcon() {
        var $btn = $('#btnToggleSidebar');
        var $icon = $btn.find('i');
        if (isDesktopSidebar()) {
            if ($('body').hasClass('sidebar-minimized')) {
                $icon.attr('class', 'bi bi-chevron-bar-right');
                $btn.attr('title', 'Expandir menú lateral');
            } else {
                $icon.attr('class', 'bi bi-chevron-bar-left');
                $btn.attr('title', 'Minimizar menú lateral');
            }
        } else {
            if ($('.spark-sidebar-wrapper').hasClass('show')) {
                $icon.attr('class', 'bi bi-chevron-bar-left');
                $btn.attr('title', 'Ocultar menú');
            } else {
                $icon.attr('class', 'bi bi-chevron-bar-right');
                $btn.attr('title', 'Mostrar menú');
            }
        }
    }

    // Restore desktop preference from localStorage
    if (isDesktopSidebar() && localStorage.getItem('pcv_admin_sidebar_minimized') === '1') {
        $('body').addClass('sidebar-minimized');
    }
    syncSidebarToggleIcon();

    // Toggle para ocultar y desplegar el menú lateral (Desktop: minimizar 80px / expandir; Mobile: drawer lateral)
    $(document).on('click', '#btnToggleSidebar', function (e) {
        e.preventDefault();
        e.stopPropagation();

        if (isDesktopSidebar()) {
            $('body').toggleClass('sidebar-minimized');
            var isMin = $('body').hasClass('sidebar-minimized');
            localStorage.setItem('pcv_admin_sidebar_minimized', isMin ? '1' : '0');
        } else {
            var $sidebar = $('.spark-sidebar-wrapper');
            var $backdrop = $('#sidebarBackdrop');
            $sidebar.toggleClass('show');
            if ($sidebar.hasClass('show')) {
                $backdrop.addClass('show');
            } else {
                $backdrop.removeClass('show');
            }
        }

        syncSidebarToggleIcon();

        // Redimensionar tablas (myDataTable) y gráficos ApexCharts suavemente
        setTimeout(function () {
            window.dispatchEvent(new Event('resize'));
            if (typeof $.fn.DataTable !== 'undefined') {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            }
        }, 320);
    });

    // Cerrar drawer en móvil al dar click al backdrop
    $(document).on('click', '#sidebarBackdrop', function () {
        $('.spark-sidebar-wrapper').removeClass('show');
        $('#sidebarBackdrop').removeClass('show');
        syncSidebarToggleIcon();
    });

    // Cerrar drawer en móvil al dar click fuera del sidebar
    $(document).on('click', function (e) {
        if (!isDesktopSidebar()) {
            if (!$(e.target).closest('.spark-sidebar-wrapper, #btnToggleSidebar').length) {
                if ($('.spark-sidebar-wrapper').hasClass('show')) {
                    $('.spark-sidebar-wrapper').removeClass('show');
                    $('#sidebarBackdrop').removeClass('show');
                    syncSidebarToggleIcon();
                }
            }
        }
    });

    // Cerrar drawer en móvil al seleccionar una opción de menú
    $(document).on('click', '.spark-sidebar-wrapper .cargarVista, .spark-sidebar-wrapper .spark-sidebar-menu-link', function () {
        if (!isDesktopSidebar()) {
            $('.spark-sidebar-wrapper').removeClass('show');
            $('#sidebarBackdrop').removeClass('show');
            syncSidebarToggleIcon();
        }
    });

    // Sincronizar iconos en cambio de resolución de ventana
    $(window).on('resize', function () {
        syncSidebarToggleIcon();
    });

    $(document).on('click', '.bCerrarSe', function () {
        function ejecutarCierre() {
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: 'metodo=eliminar&accion=login',
                beforeSend: function () {
                    $('#carga').show();
                }
            })
            .done(function () {
                window.location.reload();
            })
            .fail(function () {
                window.location.reload();
            });
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: '¿Estás seguro de que deseas salir del panel administrativo?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1A4D2E',
                cancelButtonColor: '#6B7280',
                confirmButtonText: '<i class="bi bi-box-arrow-right me-1"></i> Sí, cerrar sesión',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    ejecutarCierre();
                }
            });
        } else {
            if (confirm('¿Deseas cerrar la sesión del panel administrativo?')) {
                ejecutarCierre();
            }
        }
    });
});
