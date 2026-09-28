/**
 * Módulo de Usuarios — PCV Soluciones
 * Gestión de administradores sin permisos complejos
 */

function v_usuarios() {
    cargarTablaUsuarios();

    // Abrir modal para crear nuevo usuario
    $('#bAgregarUsuario').off('click.pcvUsrAdd').on('click.pcvUsrAdd', function () {
        resetFormUsuario();
        $('#tituloModalUsuario').text('Nuevo Usuario Administrador');
        $('#lblBtnGuardarUsuario').text('Guardar Usuario');
        $('#usrPassword').prop('required', true);
        $('#reqUsrPassword').show();
        $('#helpUsrPassword').text('Mínimo 6 caracteres.');
        $('#modalUsuario').modal('show');
    });

    // Abrir modal para editar usuario existente
    $(document).off('click.pcvUsrEdit', '.bEditarUsuario').on('click.pcvUsrEdit', '.bEditarUsuario', function () {
        var id = $(this).data('id');
        $.post('index.php', { metodo: 'detalles', accion: 'usuarios', id: id }, function (res) {
            var d = typeof res === 'string' ? JSON.parse(res) : res;
            if (!d || !d.ok || !d.data) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar la información del usuario.' });
                return;
            }
            var u = d.data;
            $('#usrId').val(u.id);
            $('#usrNombre').val(u.nombre);
            $('#usrUsuario').val(u.usuario);
            $('#usrCorreo').val(u.correo);
            $('#usrEstatus').val(u.estatus || 'activo');

            // En edición la contraseña es opcional
            $('#usrPassword').val('').prop('required', false).attr('type', 'password');
            $('.toggle-pass-visibility[data-target="usrPassword"] i').removeClass('bi-eye-slash').addClass('bi-eye');
            $('#reqUsrPassword').hide();
            $('#helpUsrPassword').text('Opcional. Deja en blanco para conservar la contraseña actual.');

            $('#tituloModalUsuario').text('Modificar Usuario: ' + u.nombre);
            $('#lblBtnGuardarUsuario').text('Actualizar Usuario');
            $('#modalUsuario').modal('show');
        });
    });

    // Eliminar usuario
    $(document).off('click.pcvUsrDel', '.bEliminarUsuario').on('click.pcvUsrDel', '.bEliminarUsuario', function () {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre') || 'este usuario';

        Swal.fire({
            title: '¿Eliminar usuario?',
            text: '¿Confirmas eliminar a "' + nombre + '"? Perderá el acceso al panel administrativo de forma permanente.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="bi bi-trash3 me-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then(function (result) {
            if (result.isConfirmed) {
                $.post('index.php', { metodo: 'eliminar', accion: 'usuarios', id: id }, function (resp) {
                    if (String(resp).trim() === 'Correcto') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Usuario eliminado',
                            text: 'El usuario ha sido eliminado correctamente.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        cargarTablaUsuarios();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: resp });
                    }
                });
            }
        });
    });

    // Envío del formulario de Usuario (Crear / Modificar)
    $('#formUsuario').off('submit.pcvUsr').on('submit.pcvUsr', function (e) {
        e.preventDefault();

        var id = $('#usrId').val();
        var nombre = $.trim($('#usrNombre').val());
        var usuario = $.trim($('#usrUsuario').val());
        var correo = $.trim($('#usrCorreo').val());
        var password = $('#usrPassword').val();
        var estatus = $('#usrEstatus').val();

        if (!nombre) {
            Swal.fire({ icon: 'warning', title: 'Campo requerido', text: 'El nombre completo es obligatorio.' });
            $('#usrNombre').focus();
            return;
        }

        if (!usuario || usuario.length < 3) {
            Swal.fire({ icon: 'warning', title: 'Campo requerido', text: 'El nombre de usuario debe tener al menos 3 caracteres.' });
            $('#usrUsuario').focus();
            return;
        }

        if (!correo) {
            Swal.fire({ icon: 'warning', title: 'Campo requerido', text: 'El correo electrónico es obligatorio.' });
            $('#usrCorreo').focus();
            return;
        }

        if (!id && (!password || password.length < 6)) {
            Swal.fire({ icon: 'warning', title: 'Contraseña requerida', text: 'Debes asignar una contraseña de al menos 6 caracteres para el nuevo usuario.' });
            $('#usrPassword').focus();
            return;
        }

        if (id && password.length > 0 && password.length < 6) {
            Swal.fire({ icon: 'warning', title: 'Contraseña corta', text: 'La nueva contraseña debe tener al menos 6 caracteres.' });
            $('#usrPassword').focus();
            return;
        }

        var postData = {
            metodo: id ? 'modificar' : 'insertar',
            accion: 'usuarios',
            nombre: nombre,
            usuario: usuario,
            correo: correo,
            estatus: estatus,
            password: password
        };
        if (id) {
            postData.id = id;
        }

        var $btn = $('#btnGuardarUsuario');
        var originalBtnHtml = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status"></span> Guardando...');

        $.post('index.php', postData, function (resp) {
            if (String(resp).trim() === 'Correcto') {
                Swal.fire({
                    icon: 'success',
                    title: id ? 'Usuario actualizado' : 'Usuario registrado',
                    text: id ? 'Los datos del usuario se modificaron con éxito.' : 'El nuevo usuario ya puede acceder al panel.',
                    timer: 1600,
                    showConfirmButton: false
                });
                $('#modalUsuario').modal('hide');
                resetFormUsuario();
                cargarTablaUsuarios();
            } else {
                Swal.fire({ icon: 'error', title: 'Atención', text: resp });
            }
        })
        .fail(function () {
            Swal.fire({ icon: 'error', title: 'Error de servidor', text: 'No se pudo procesar la solicitud.' });
        })
        .always(function () {
            $btn.prop('disabled', false).html(originalBtnHtml);
        });
    });
}

function resetFormUsuario() {
    $('#usrId').val('');
    $('#usrNombre').val('');
    $('#usrUsuario').val('');
    $('#usrCorreo').val('');
    $('#usrPassword').val('').attr('type', 'password');
    $('#usrEstatus').val('activo');
    $('.toggle-pass-visibility[data-target="usrPassword"] i').removeClass('bi-eye-slash').addClass('bi-eye');
}

function cargarTablaUsuarios() {
    if (typeof crearDataTable === 'function') {
        crearDataTable();
    }
    if (typeof ajaxMyDatatable === 'function') {
        ajaxMyDatatable({
            table: $('#tablaUsuarios'),
            url: 'index.php',
            colums: ['id', 'nombre', 'usuario', 'correo', 'estatus', 'creado_en', 'acciones'],
            sort: [0, 'desc'],
            params: {
                metodo: 'consultar',
                accion: 'usuarios'
            }
        });
    }
}
