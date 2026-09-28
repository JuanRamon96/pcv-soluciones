function v_subtipos() {
	cargarTablaSubtiposVista();

	$('.btnAbrirModalNuevoSubtipo, #bAgregarSubtipo').off('click.pcvSub').on('click.pcvSub', function () {
		resetFormSubtipoVista();
		$('#modalSubtipo').modal('show');
	});

	$(document).off('click.pcvSubEdit', '.bEditarSubtipoVista').on('click.pcvSubEdit', '.bEditarSubtipoVista', function () {
		$('#subtipoVistaId').val($(this).data('id'));
		$('#subtipoVistaClas').val($(this).data('clas'));
		$('#subtipoVistaNombre').val($(this).data('nombre'));
		$('#subtipoVistaOrden').val($(this).data('orden') || 0);
		$('#subtipoVistaActivo').val(String($(this).data('activo')));
		$('#tituloModalSubtipo').text('Modificar Filtro / Línea');
		$('#lblBtnGuardarSubtipo').text('Actualizar Filtro');
		$('#modalSubtipo').modal('show');
	});

	$('#formSubtipo').off('submit.pcvSub').on('submit.pcvSub', function (e) {
		e.preventDefault();
		var id = $('#subtipoVistaId').val();
		var clasId = $('#subtipoVistaClas').val();
		var nombre = $('#subtipoVistaNombre').val().trim();
		var orden = parseInt($('#subtipoVistaOrden').val(), 10) || 0;
		var activo = $('#subtipoVistaActivo').val();
		if (!nombre || !clasId) {
			Swal.fire({ icon: 'warning', title: 'Campos requeridos', text: 'Completa la división y el nombre' });
			return;
		}
		var postData = {
			metodo: id ? 'modificar' : 'insertar',
			accion: 'subtipos',
			clasificacion_id: clasId,
			nombre: nombre,
			orden: orden,
			activo: activo
		};
		if (id) postData.id = id;
		$.post('index.php', postData, function (resp) {
			if (String(resp).trim() === 'Correcto') {
				Swal.fire({ icon: 'success', title: id ? 'Filtro actualizado' : 'Filtro registrado', timer: 1400, showConfirmButton: false });
				$('#modalSubtipo').modal('hide');
				resetFormSubtipoVista();
				cargarTablaSubtiposVista();
			} else {
				Swal.fire({ icon: 'error', title: 'Error', text: resp });
			}
		});
	});

	$(document).off('click.pcvSubToggle', '.bToggleSubtipoVista').on('click.pcvSubToggle', '.bToggleSubtipoVista', function () {
		var id = $(this).data('id');
		var nombre = $(this).data('nombre');
		var clas = $(this).data('clas');
		var orden = $(this).data('orden') || 0;
		var nuevoActivo = $(this).data('activo') == 1 ? 0 : 1;
		$.post('index.php', {
			metodo: 'modificar',
			accion: 'subtipos',
			id: id,
			clasificacion_id: clas,
			nombre: nombre,
			orden: orden,
			activo: nuevoActivo
		}, function (resp) {
			if (String(resp).trim() === 'Correcto') {
				cargarTablaSubtiposVista();
			} else {
				Swal.fire({ icon: 'error', title: 'Error', text: resp });
			}
		});
	});

	$(document).off('click.pcvSubDel', '.bEliminarSubtipoVista').on('click.pcvSubDel', '.bEliminarSubtipoVista', function () {
		var id = $(this).data('id');
		var nombre = $(this).data('nombre');
		Swal.fire({
			title: '¿Eliminar o desactivar filtro?',
			text: '"' + nombre + '" se ocultará del catálogo público.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Sí, continuar',
			cancelButtonText: 'Cancelar'
		}).then(function (res) {
			if (!(res.value || res.isConfirmed)) return;
			$.post('index.php', { metodo: 'eliminar', accion: 'subtipos', id: id }, function (resp) {
				Swal.fire({ icon: 'info', title: resp, timer: 1500, showConfirmButton: false });
				cargarTablaSubtiposVista();
			});
		});
	});
}

function resetFormSubtipoVista() {
	$('#subtipoVistaId').val('');
	$('#subtipoVistaNombre').val('');
	$('#subtipoVistaClas').val('');
	$('#subtipoVistaOrden').val(0);
	$('#subtipoVistaActivo').val('1');
	$('#tituloModalSubtipo').text('Agregar Nuevo Filtro / Línea');
	$('#lblBtnGuardarSubtipo').text('Guardar Filtro');
}

function cargarTablaSubtiposVista() {
	if (typeof crearDataTable === 'function') {
		crearDataTable();
	}
	if (typeof ajaxMyDatatable === 'function') {
		ajaxMyDatatable({
			table: $('#tablaSubtipos'),
			url: 'index.php',
			colums: ['nombre', 'clasificacion', 'productos', 'estado', 'acciones'],
			sort: [0, 'asc'],
			params: {
				metodo: 'consultar',
				accion: 'subtipos'
			}
		});
	}
}
