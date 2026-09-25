function v_cotizaciones() {
	cargarCotizaciones();

	$('#filtroEstatusCot, #bRecargarCot').off('change.pcv click.pcv').on('change.pcv click.pcv', cargarCotizaciones);

	$(document).off('click.pcvVer', '.bVerCotizacion').on('click.pcvVer', '.bVerCotizacion', function () {
		$.post('index.php', { metodo: 'detalles', accion: 'cotizaciones', id: $(this).data('id') }, function (r) {
			var d = typeof r === 'string' ? JSON.parse(r) : r;
			if (!d.ok) return;
			var c = d.data;
			$('#detalleCotizacion').html(
				'<p><b>Folio:</b> ' + (c.folio || '') + '</p>' +
				'<p><b>Nombre:</b> ' + (c.nombre || '') + '</p>' +
				'<p><b>Empresa:</b> ' + (c.empresa || '—') + '</p>' +
				'<p><b>Tel:</b> ' + (c.telefono || '') + ' · <b>Email:</b> ' + (c.correo || '—') + '</p>' +
				'<p><b>Producto:</b> ' + (c.producto_nombre || 'General') + '</p>' +
				'<p><b>Mensaje:</b><br>' + (c.mensaje || '') + '</p>'
			);
			$('#modalCotizacion').modal('show');
		});
	});

	$(document).off('click.pcvEst', '.bEstatusCotizacion').on('click.pcvEst', '.bEstatusCotizacion', function () {
		var id = $(this).data('id'), estatus = $(this).data('estatus');
		$.post('index.php', { metodo: 'modificar', accion: 'cotizaciones', id: id, estatus: estatus }, function (resp) {
			if (String(resp).trim() === 'Correcto') {
				Swal.fire({ icon: 'success', title: 'Actualizado', timer: 1000, showConfirmButton: false });
				cargarCotizaciones();
			}
		});
	});
}

function cargarCotizaciones() {
	$.post('index.php', { metodo: 'consultar', accion: 'cotizaciones', estatus: $('#filtroEstatusCot').val() }, function (html) {
		$('#tbodyCotizaciones').html(html);
	});
}
