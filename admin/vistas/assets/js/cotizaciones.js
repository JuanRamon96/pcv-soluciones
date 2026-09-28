function v_cotizaciones() {
	cargarCotizaciones();

	$('#filtroEstatusCot, #bRecargarCot').off('change.pcv click.pcv').on('change.pcv click.pcv', cargarCotizaciones);

	$(document).off('click.pcvVer', '.bVerCotizacion').on('click.pcvVer', '.bVerCotizacion', function () {
		$.post('index.php', { metodo: 'detalles', accion: 'cotizaciones', id: $(this).data('id') }, function (r) {
			var d = typeof r === 'string' ? JSON.parse(r) : r;
			if (!d.ok) return;
			var c = d.data;
			var cleanTel = (c.telefono || '').replace(/[^0-9]/g, '');
			var waLink = cleanTel ? 'https://wa.me/' + cleanTel : '#';

			var html = `
				<div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
					<div>
						<span class="badge bg-light text-dark border px-2 py-1 font-monospace">Folio: ${c.folio || 'N/A'}</span>
					</div>
					<div>
						<span class="badge ${c.estatus === 'cerrada' ? 'bg-success' : (c.estatus === 'en_proceso' ? 'bg-info text-dark' : 'bg-warning text-dark')} px-3 py-1 rounded-pill text-uppercase" style="font-size:0.75rem;">
							${c.estatus || 'nueva'}
						</span>
					</div>
				</div>

				<div class="row g-3 mb-3">
					<div class="col-sm-6">
						<div class="p-3 bg-light rounded-3 border">
							<small class="text-muted text-uppercase fw-bold d-block mb-1"><i class="bi bi-person me-1"></i>Cliente / Solicitante</small>
							<div class="fw-bold text-dark fs-6">${c.nombre || '—'}</div>
							<div class="text-muted small">${c.empresa ? '<i class="bi bi-building me-1"></i>' + c.empresa : 'Particular / No especificado'}</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="p-3 bg-light rounded-3 border">
							<small class="text-muted text-uppercase fw-bold d-block mb-1"><i class="bi bi-telephone me-1"></i>Contacto Directo</small>
							<div><a href="tel:${c.telefono || ''}" class="fw-bold text-dark text-decoration-none">${c.telefono || '—'}</a></div>
							<div class="small"><a href="mailto:${c.correo || ''}" class="text-primary text-decoration-none">${c.correo || '—'}</a></div>
						</div>
					</div>
				</div>

				<div class="p-3 bg-light rounded-3 border mb-3">
					<small class="text-muted text-uppercase fw-bold d-block mb-1"><i class="bi bi-box-seam me-1"></i>Producto, Servicio o Requerimiento</small>
					<div class="fw-bold text-success fs-6">${c.producto_nombre || 'Cotización General / Varios Productos'}</div>
				</div>

				<div class="p-3 bg-light rounded-3 border mb-3">
					<small class="text-muted text-uppercase fw-bold d-block mb-1"><i class="bi bi-chat-left-text me-1"></i>Mensaje / Especificaciones del Cliente</small>
					<div class="text-dark small" style="white-space: pre-wrap; line-height: 1.6;">${c.mensaje || 'Sin mensaje adicional adjunto.'}</div>
				</div>

				<div class="d-flex justify-content-end gap-2 pt-2">
					${cleanTel ? `<a href="${waLink}" target="_blank" class="btn btn-sm btn-success rounded-3 d-inline-flex align-items-center gap-1"><i class="bi bi-whatsapp"></i> Chat WhatsApp</a>` : ''}
					${c.correo ? `<a href="mailto:${c.correo}" class="btn btn-sm btn-outline-primary rounded-3 d-inline-flex align-items-center gap-1"><i class="bi bi-envelope"></i> Redactar Correo</a>` : ''}
				</div>
			`;

			$('#detalleCotizacion').html(html);
			$('#modalCotizacion').modal('show');
		});
	});

	$(document).off('click.pcvEst', '.bEstatusCotizacion').on('click.pcvEst', '.bEstatusCotizacion', function () {
		var id = $(this).data('id'), estatus = $(this).data('estatus');
		$.post('index.php', { metodo: 'modificar', accion: 'cotizaciones', id: id, estatus: estatus }, function (resp) {
			if (String(resp).trim() === 'Correcto') {
				Swal.fire({ icon: 'success', title: 'Estatus Actualizado', timer: 1000, showConfirmButton: false });
				cargarCotizaciones();
			}
		});
	});
}

function cargarCotizaciones() {
	if (typeof crearDataTable === 'function') {
		crearDataTable();
	}
	ajaxMyDatatable({
		table: $('#tablaCotizaciones'),
		url: 'index.php',
		colums: ['folio', 'cliente', 'contacto', 'producto', 'estatus', 'fecha', 'acciones'],
		sort: [0, 'desc'],
		params: {
			metodo: 'consultar',
			accion: 'cotizaciones',
			estatus: $('#filtroEstatusCot').val() || ''
		}
	});
}
