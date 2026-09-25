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

    $(document).on('click', '.bCerrarSe', function () {
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
            console.log('Error ajax');
        });
    });
});
