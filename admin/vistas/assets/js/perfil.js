/**
 * Perfil de Administrador — PCV Soluciones
 * Vista completa (v_perfil)
 */

function v_perfil() {
    cargarDatosPerfilVista();

    $('#formPerfilVista').off('submit.pcvPerfil').on('submit.pcvPerfil', function (e) {
        e.preventDefault();

        var nombre = $.trim($('#perfilNombreV').val());
        var usuario = $.trim($('#perfilUsuarioV').val());
        var correo = $.trim($('#perfilCorreoV').val());
        var passActual = $('#perfilPassActualV').val();
        var passNueva = $('#perfilPassNuevaV').val();
        var passConf = $('#perfilPassConfirmarV').val();

        // Validaciones básicas de cliente
        if (!nombre) {
            mostrarAlertaPerfilVista('El nombre completo es obligatorio.', 'warning');
            $('#perfilNombreV').focus();
            return;
        }

        if (!usuario || usuario.length < 3) {
            mostrarAlertaPerfilVista('El nombre de usuario debe tener al menos 3 caracteres.', 'warning');
            $('#perfilUsuarioV').focus();
            return;
        }

        if (!correo) {
            mostrarAlertaPerfilVista('El correo electrónico es obligatorio.', 'warning');
            $('#perfilCorreoV').focus();
            return;
        }

        // Si se ingresó alguna contraseña
        if (passNueva.length > 0 || passConf.length > 0) {
            if (!passActual) {
                mostrarAlertaPerfilVista('Para modificar tu contraseña, debes ingresar tu contraseña actual.', 'warning');
                $('#perfilPassActualV').focus();
                return;
            }
            if (passNueva.length < 6) {
                mostrarAlertaPerfilVista('La nueva contraseña debe tener al menos 6 caracteres.', 'warning');
                $('#perfilPassNuevaV').focus();
                return;
            }
            if (passNueva !== passConf) {
                mostrarAlertaPerfilVista('La confirmación de la nueva contraseña no coincide.', 'danger');
                $('#perfilPassConfirmarV').focus();
                return;
            }
        }

        var $btn = $('#btnGuardarPerfilV');
        var originalBtnHtml = $btn.html();

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: 'metodo=modificar&accion=perfil&' + $('#formPerfilVista').serialize(),
            dataType: 'json',
            beforeSend: function () {
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status"></span> Guardando...');
                $('#alertPerfilVista').hide().html('');
            }
        })
        .done(function (res) {
            if (res && res.ok) {
                // Actualizar interfaz en tiempo real
                if (res.data) {
                    $('#nombreUserP').text(res.data.nombre);
                    $('.spark-sidebar-profile-name').text(res.data.nombre).attr('title', res.data.nombre);
                    $('#perfilVistaCardNombre').text(res.data.nombre);
                    $('#perfilVistaCardUsuario').text('@' + res.data.usuario);
                    $('#perfilVistaCardCorreo').text(res.data.correo);
                }

                // Limpiar campos de contraseña
                $('#perfilPassActualV').val('').attr('type', 'password');
                $('#perfilPassNuevaV').val('').attr('type', 'password');
                $('#perfilPassConfirmarV').val('').attr('type', 'password');
                $('.toggle-pass-visibility i').removeClass('bi-eye-slash').addClass('bi-eye');

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Perfil Actualizado!',
                        text: res.message || 'Tu información y credenciales se guardaron exitosamente.',
                        confirmButtonColor: '#072F1F',
                        confirmButtonText: '<i class="bi bi-check-lg me-1"></i> Aceptar'
                    });
                }
            } else {
                var err = (res && res.error) ? res.error : 'Ocurrió un error al intentar guardar los cambios.';
                mostrarAlertaPerfilVista(err, 'danger');
            }
        })
        .fail(function (xhr) {
            console.error('Error perfil:', xhr.responseText);
            mostrarAlertaPerfilVista('Error de comunicación con el servidor. Intenta nuevamente.', 'danger');
        })
        .always(function () {
            $btn.prop('disabled', false).html(originalBtnHtml);
        });
    });
}

function cargarDatosPerfilVista() {
    $('#alertPerfilVista').hide().html('');
    $('#perfilPassActualV').val('').attr('type', 'password');
    $('#perfilPassNuevaV').val('').attr('type', 'password');
    $('#perfilPassConfirmarV').val('').attr('type', 'password');
    $('.toggle-pass-visibility i').removeClass('bi-eye-slash').addClass('bi-eye');

    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: 'metodo=consultar&accion=perfil',
        dataType: 'json',
        beforeSend: function () {
            $('#carga').show();
        }
    })
    .done(function (res) {
        if (res && res.ok && res.data) {
            var u = res.data;
            $('#perfilNombreV').val(u.nombre || '');
            $('#perfilUsuarioV').val(u.usuario || '');
            $('#perfilCorreoV').val(u.correo || '');

            $('#perfilVistaCardNombre').text(u.nombre || 'Administrador');
            $('#perfilVistaCardUsuario').text('@' + (u.usuario || 'admin'));
            $('#perfilVistaCardCorreo').text(u.correo || '—');
            if (u.creado_en) {
                $('#perfilVistaCardFecha').text('Miembro desde: ' + u.creado_en.substring(0, 10));
            }
        } else {
            mostrarAlertaPerfilVista('No se pudo cargar la información del perfil.', 'danger');
        }
    })
    .fail(function () {
        mostrarAlertaPerfilVista('Error al consultar datos del perfil.', 'danger');
    })
    .always(function () {
        $('#carga').hide();
    });
}

function mostrarAlertaPerfilVista(mensaje, tipo) {
    var icon = tipo === 'warning' ? 'bi-exclamation-triangle-fill' : 'bi-x-circle-fill';
    var html = '<div class="alert alert-' + tipo + ' alert-dismissible fade show d-flex align-items-center mb-0 py-2" role="alert">' +
        '<i class="bi ' + icon + ' me-2 fs-5 flex-shrink-0"></i>' +
        '<div class="flex-grow-1 small">' + mensaje + '</div>' +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>' +
        '</div>';
    $('#alertPerfilVista').html(html).slideDown(200);
}

// Alternar visibilidad de contraseñas (global)
jQuery(document).ready(function ($) {
    $(document).off('click.pcvPassToggle', '.toggle-pass-visibility').on('click.pcvPassToggle', '.toggle-pass-visibility', function (e) {
        e.preventDefault();
        var targetId = $(this).data('target');
        var $input = $('#' + targetId);
        var $icon = $(this).find('i');

        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            $input.attr('type', 'password');
            $icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });
});
