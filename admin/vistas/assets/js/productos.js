function v_productos() {
	cargarTablaProductos();

	$('#prodClas').off('change.pcv').on('change.pcv', filtrarSubtipos);

	$('#bAgregarProducto').off('click.pcv').on('click.pcv', function () {
		$('#formProducto')[0].reset();
		$('#prodId').val('');
		$('#prodActivo').prop('checked', true);
		filtrarSubtipos();
		$('#modalProducto').modal('show');
	});

	$('#filtroProductos, #filtroTipo').off('change.pcv keyup.pcv').on('change.pcv keyup.pcv', function () {
		cargarTablaProductos();
	});

	$(document).off('click.pcvEd', '.bEditarProducto').on('click.pcvEd', '.bEditarProducto', function () {
		var id = $(this).data('id');
		$.post('index.php', { metodo: 'detalles', accion: 'productos', id: id }, function (r) {
			var d = typeof r === 'string' ? JSON.parse(r) : r;
			if (!d.ok) return;
			var p = d.data;
			$('#prodId').val(p.id);
			$('#prodTipo').val(p.tipo);
			$('#prodClas').val(p.clasificacion_id);
			filtrarSubtipos();
			$('#prodSub').val(p.subtipo_id || '');
			$('#prodNombre').val(p.nombre);
			$('#prodResumen').val(p.resumen || '');
			$('#prodDesc').val(p.descripcion || '');
			$('#prodOrden').val(p.orden || 0);
			$('#prodDestacado').prop('checked', p.destacado == 1);
			$('#prodActivo').prop('checked', p.activo == 1);
			$('#modalProducto').modal('show');
		});
	});

	$(document).off('click.pcvEl', '.bEliminarProducto').on('click.pcvEl', '.bEliminarProducto', function () {
		var id = $(this).data('id');
		Swal.fire({
			title: '¿Desactivar registro?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Sí',
			cancelButtonText: 'Cancelar'
		}).then(function (res) {
			if (!(res.value || res.isConfirmed)) return;
			$.post('index.php', { metodo: 'eliminar', accion: 'productos', id: id }, function (resp) {
				if (String(resp).trim() === 'Correcto') {
					Swal.fire({ icon: 'success', title: 'Listo', timer: 1200, showConfirmButton: false });
					cargarTablaProductos();
				} else {
					Swal.fire({ icon: 'error', title: resp });
				}
			});
		});
	});

	$('#formProducto').off('submit.pcv').on('submit.pcv', function (e) {
		e.preventDefault();
		var fd = new FormData(this);
		var id = $('#prodId').val();
		fd.append('metodo', id ? 'modificar' : 'insertar');
		fd.append('accion', 'productos');
		if (!$('#prodDestacado').is(':checked')) fd.delete('destacado');
		if (!$('#prodActivo').is(':checked')) fd.delete('activo');
		$.ajax({
			url: 'index.php',
			method: 'POST',
			data: fd,
			processData: false,
			contentType: false,
			success: function (resp) {
				if (String(resp).trim() === 'Correcto') {
					Swal.fire({ icon: 'success', title: 'Guardado', timer: 1200, showConfirmButton: false });
					$('#modalProducto').modal('hide');
					cargarTablaProductos();
				} else {
					Swal.fire({ icon: 'error', title: String(resp) });
				}
			}
		});
	});
}

function filtrarSubtipos() {
	var clas = $('#prodClas').val();
	$('#prodSub option').each(function () {
		var dc = $(this).data('clas');
		if (!dc) { $(this).prop('hidden', false); return; }
		$(this).prop('hidden', String(dc) !== String(clas));
	});
}

function cargarTablaProductos() {
	$.post('index.php', {
		metodo: 'consultar',
		accion: 'productos',
		filtro: $('#filtroProductos').val(),
		tipo: $('#filtroTipo').val()
	}, function (html) {
		$('#tbodyProductos').html(html);
	});
}
