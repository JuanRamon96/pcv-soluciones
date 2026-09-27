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
	$('#tbodySubtipos').html('<tr><td colspan="5" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Cargando filtros...</td></tr>');
	$.post('index.php', { metodo: 'consultar', accion: 'subtipos' }, function (res) {
		var d = typeof res === 'string' ? JSON.parse(res) : res;
		if (!d.ok || !d.data) {
			$('#tbodySubtipos').html('<tr><td colspan="5" class="text-center py-3 text-danger">No se pudieron cargar los filtros</td></tr>');
			return;
		}
		if (d.data.length === 0) {
			$('#tbodySubtipos').html('<tr><td colspan="5" class="text-center py-3 text-muted">No hay filtros registrados</td></tr>');
			return;
		}
		var html = '';
		d.data.forEach(function (f) {
			var isActivo = f.activo == 1;
			var estadoBadge = isActivo
				? '<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small">Activo</span>'
				: '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 small">Inactivo</span>';
			var toggleBtn = isActivo
				? '<button type="button" class="btn btn-sm btn-outline-warning bToggleSubtipoVista py-0 px-1" data-id="' + f.id + '" data-nombre="' + f.nombre + '" data-clas="' + f.clasificacion_id + '" data-orden="' + (f.orden || 0) + '" data-activo="1" title="Desactivar"><i class="bi bi-eye-slash"></i></button>'
				: '<button type="button" class="btn btn-sm btn-outline-success bToggleSubtipoVista py-0 px-1" data-id="' + f.id + '" data-nombre="' + f.nombre + '" data-clas="' + f.clasificacion_id + '" data-orden="' + (f.orden || 0) + '" data-activo="0" title="Activar"><i class="bi bi-eye"></i></button>';
			html += '<tr>' +
				'<td class="ps-3 text-start fw-semibold text-dark">' + f.nombre + '</td>' +
				'<td><span class="badge bg-light text-dark border">' + (f.clasificacion_nombre || '') + '</span></td>' +
				'<td><span class="badge bg-info-subtle text-info-emphasis">' + (f.total_prods || 0) + '</span></td>' +
				'<td>' + estadoBadge + '</td>' +
				'<td>' +
					'<div class="d-inline-flex gap-1">' +
						'<button type="button" class="btn btn-sm btn-outline-primary bEditarSubtipoVista py-0 px-1" data-id="' + f.id + '" data-nombre="' + f.nombre + '" data-clas="' + f.clasificacion_id + '" data-orden="' + (f.orden || 0) + '" data-activo="' + f.activo + '" title="Editar"><i class="bi bi-pencil"></i></button>' +
						toggleBtn +
						'<button type="button" class="btn btn-sm btn-outline-danger bEliminarSubtipoVista py-0 px-1" data-id="' + f.id + '" data-nombre="' + f.nombre + '" title="Eliminar"><i class="bi bi-trash3"></i></button>' +
					'</div>' +
				'</td>' +
			'</tr>';
		});
		$('#tbodySubtipos').html(html);
	});
}
