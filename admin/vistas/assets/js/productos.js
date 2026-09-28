var archivosNuevosGaleria = [];

function actualizarContadorGaleria() {
	var existentes = $('#contenedorGaleriaThumbs .card-galeria-existente').length;
	var nuevas = 0;
	archivosNuevosGaleria.forEach(function (f) { if (f) nuevas++; });
	var total = existentes + nuevas;
	$('#contadorFotosGaleria').text(total + (total === 1 ? ' foto' : ' fotos'));
}

function resetPreviewPrincipal() {
	$('#imgPrincipalPreview').attr('src', '').addClass('d-none');
	$('#imgPrincipalPlaceholder').removeClass('d-none');
	$('#btnQuitarImgPrincipal, #btnQuitarImgPrincipalText').addClass('d-none');
	$('#prodQuitarImgPrincipal').val('0');
	$('#prodImagen').val('');
}

function setPreviewPrincipal(url) {
	if (url) {
		$('#imgPrincipalPreview').attr('src', url).removeClass('d-none');
		$('#imgPrincipalPlaceholder').addClass('d-none');
		$('#btnQuitarImgPrincipal, #btnQuitarImgPrincipalText').removeClass('d-none');
		$('#prodQuitarImgPrincipal').val('0');
	} else {
		resetPreviewPrincipal();
	}
}

function quitarImagenPrincipal() {
	$('#imgPrincipalPreview').attr('src', '').addClass('d-none');
	$('#imgPrincipalPlaceholder').removeClass('d-none');
	$('#btnQuitarImgPrincipal, #btnQuitarImgPrincipalText').addClass('d-none');
	$('#prodQuitarImgPrincipal').val('1');
	$('#prodImagen').val('');
}

function renderizarFotosGaleria(items) {
	$('#contenedorGaleriaThumbs').empty();
	archivosNuevosGaleria = [];

	if (Array.isArray(items) && items.length > 0) {
		items.forEach(function (img) {
			var card = $(`
				<div class="col-6 col-sm-4 col-md-3 col-lg-2 card-galeria-existente" id="galeriaItem_${img.id}">
					<div class="card h-100 border rounded-3 overflow-hidden shadow-sm position-relative">
						<img src="${img.url}" class="card-img-top" style="height: 105px; object-fit: cover; background: #F8FAFC;" alt="Galería">
						<button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-1 shadow bBorrarImgGaleriaExistente" data-id="${img.id}" title="Eliminar foto de galería">
							<i class="bi bi-trash3-fill"></i>
						</button>
						<div class="p-1 bg-light text-center small text-muted text-truncate px-2" style="font-size: 0.68rem;" title="${img.archivo}">
							Guardada
						</div>
					</div>
				</div>
			`);
			$('#contenedorGaleriaThumbs').append(card);
		});
	}
	actualizarContadorGaleria();
}

function agregarArchivosNuevosGaleria(files) {
	if (!files || files.length === 0) return;
	for (var i = 0; i < files.length; i++) {
		(function (file) {
			if (!file.type.match(/^image\//)) return;
			var idx = archivosNuevosGaleria.length;
			archivosNuevosGaleria.push(file);

			var reader = new FileReader();
			reader.onload = function (e) {
				var sizeKb = (file.size / 1024).toFixed(0);
				var sizeText = file.size > 1048576 ? (file.size / 1048576).toFixed(1) + ' MB (optimizará a &lt; 1MB)' : sizeKb + ' KB';
				var card = $(`
					<div class="col-6 col-sm-4 col-md-3 col-lg-2 card-galeria-nueva" data-idx="${idx}">
						<div class="card h-100 border border-success-subtle rounded-3 overflow-hidden shadow-sm position-relative">
							<img src="${e.target.result}" class="card-img-top" style="height: 105px; object-fit: cover; background: #F8FAFC;" alt="Nueva foto">
							<button type="button" class="btn btn-dark btn-sm rounded-circle position-absolute top-0 end-0 m-1 shadow bQuitarImgGaleriaNueva" data-idx="${idx}" title="Quitar">
								<i class="bi bi-x-lg"></i>
							</button>
							<div class="p-1 bg-success-subtle text-success text-center small fw-semibold text-truncate px-2" style="font-size: 0.68rem;" title="${file.name}">
								${sizeText}
							</div>
						</div>
					</div>
				`);
				$('#contenedorGaleriaThumbs').append(card);
				actualizarContadorGaleria();
			};
			reader.readAsDataURL(file);
		})(files[i]);
	}
}

function v_productos() {
	cargarTablaProductos();

	$('#prodClas').off('change.pcv').on('change.pcv', filtrarSubtipos);

	// Previsualizador dinámico de Imagen Principal
	$('#prodImagen').off('change.pcv').on('change.pcv', function () {
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				setPreviewPrincipal(e.target.result);
			};
			reader.readAsDataURL(this.files[0]);
		}
	});

	// Botones para quitar imagen principal
	$('#btnQuitarImgPrincipal, #btnQuitarImgPrincipalText').off('click.pcv').on('click.pcv', function (e) {
		e.preventDefault();
		quitarImagenPrincipal();
	});

	// Input file múltiple y Drag & Drop para Galería
	$('#prodGaleriaInput').off('change.pcv').on('change.pcv', function () {
		agregarArchivosNuevosGaleria(this.files);
		this.value = '';
	});

	$('#dropzoneGaleria').off('dragover.pcv dragenter.pcv').on('dragover.pcv dragenter.pcv', function (e) {
		e.preventDefault();
		e.stopPropagation();
		$(this).css({ 'background-color': '#F0FDF4', 'border-color': '#22C55E' });
	}).off('dragleave.pcv drop.pcv').on('dragleave.pcv drop.pcv', function (e) {
		e.preventDefault();
		e.stopPropagation();
		$(this).css({ 'background-color': '#FFFFFF', 'border-color': '#94A3B8' });
	});

	$('#dropzoneGaleria').off('drop.pcvDrop').on('drop.pcvDrop', function (e) {
		var dt = e.originalEvent.dataTransfer;
		if (dt && dt.files && dt.files.length) {
			agregarArchivosNuevosGaleria(dt.files);
		}
	});

	// Quitar imagen nueva antes de guardar
	$(document).off('click.pcvQGal', '.bQuitarImgGaleriaNueva').on('click.pcvQGal', '.bQuitarImgGaleriaNueva', function () {
		var idx = $(this).data('idx');
		if (archivosNuevosGaleria[idx] !== undefined) {
			archivosNuevosGaleria[idx] = null;
		}
		$(this).closest('.card-galeria-nueva').remove();
		actualizarContadorGaleria();
	});

	// Eliminar imagen de galería ya guardada en el servidor (borrado físico)
	$(document).off('click.pcvDelGal', '.bBorrarImgGaleriaExistente').on('click.pcvDelGal', '.bBorrarImgGaleriaExistente', function () {
		var imgId = $(this).data('id');
		Swal.fire({
			title: '¿Eliminar foto de la galería?',
			text: 'Se borrará el archivo de imagen de forma permanente.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Sí, eliminar',
			cancelButtonText: 'Cancelar'
		}).then(function (res) {
			if (!(res.value || res.isConfirmed)) return;
			$.post('index.php', { metodo: 'eliminar', accion: 'productos', imagen_id: imgId }, function (resp) {
				if (String(resp).trim() === 'Correcto') {
					$('#galeriaItem_' + imgId).fadeOut(250, function () {
						$(this).remove();
						actualizarContadorGaleria();
					});
					Swal.fire({ icon: 'success', title: 'Foto eliminada', timer: 1000, showConfirmButton: false });
				} else {
					Swal.fire({ icon: 'error', title: 'Error', text: resp });
				}
			});
		});
	});

	$('#bAgregarProducto').off('click.pcv').on('click.pcv', function () {
		$('#formProducto')[0].reset();
		$('#prodId').val('');
		$('#prodActivo').prop('checked', true);
		resetPreviewPrincipal();
		renderizarFotosGaleria([]);
		filtrarSubtipos();
		$('#modalProducto').modal('show');
	});

	// Filtro Rápido (Todos / Productos / Servicios)
	$(document).off('click.pcvFiltroTipo', '.btn-filtro-tipo').on('click.pcvFiltroTipo', '.btn-filtro-tipo', function () {
		var tipo = $(this).data('tipo') || '';
		$('.btn-filtro-tipo').removeClass('active');
		$(this).addClass('active');
		$('#filtroTipo').val(tipo);

		var lbl = 'Todos los registros';
		if (tipo === 'producto') lbl = 'Solo Productos';
		if (tipo === 'servicio') lbl = 'Solo Servicios';
		$('#lblFiltroTipoActivo').text(lbl);

		cargarTablaProductos();
	});

	$('#filtroTipo').off('change.pcv').on('change.pcv', function () {
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

			// Configurar Previsualizador de Imagen Principal
			if (p.imagen_url) {
				setPreviewPrincipal(p.imagen_url);
			} else {
				resetPreviewPrincipal();
			}

			// Renderizar fotos existentes de Galería
			renderizarFotosGaleria(d.galeria || []);

			$('#modalProducto').modal('show');
		});
	});

	$(document).off('click.pcvEl', '.bEliminarProducto').on('click.pcvEl', '.bEliminarProducto', function () {
		var id = $(this).data('id');
		Swal.fire({
			title: '¿Eliminar producto o servicio?',
			text: 'Se eliminará el registro y todas sus imágenes asociadas de forma permanente.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Sí, eliminar',
			cancelButtonText: 'Cancelar'
		}).then(function (res) {
			if (!(res.value || res.isConfirmed)) return;
			$.post('index.php', { metodo: 'eliminar', accion: 'productos', id: id }, function (resp) {
				if (String(resp).trim() === 'Correcto') {
					Swal.fire({ icon: 'success', title: 'Registro e imágenes eliminados', timer: 1400, showConfirmButton: false });
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

		// Adjuntar todas las imágenes seleccionadas para la galería
		fd.delete('imagenes_galeria[]');
		archivosNuevosGaleria.forEach(function (f) {
			if (f) {
				fd.append('imagenes_galeria[]', f);
			}
		});

		var $btnGuardar = $('#bGuardarProducto');
		$btnGuardar.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status"></span> Procesando...');

		$.ajax({
			url: 'index.php',
			method: 'POST',
			data: fd,
			processData: false,
			contentType: false,
			success: function (resp) {
				$btnGuardar.prop('disabled', false).html('<i class="bi bi-check2"></i><span>Guardar Registro</span>');
				if (String(resp).trim() === 'Correcto') {
					Swal.fire({ icon: 'success', title: id ? 'Producto actualizado' : 'Producto registrado', timer: 1300, showConfirmButton: false });
					$('#modalProducto').modal('hide');
					cargarTablaProductos();
				} else {
					Swal.fire({ icon: 'error', title: String(resp) });
				}
			},
			error: function (xhr) {
				$btnGuardar.prop('disabled', false).html('<i class="bi bi-check2"></i><span>Guardar Registro</span>');
				Swal.fire({ icon: 'error', title: 'Error en la petición', text: xhr.responseText || 'Error del servidor' });
			}
		});
	});
}

function actualizarSelectSubtipos() {
	$.post('index.php', { metodo: 'consultar', accion: 'subtipos', combo: 1 }, function (res) {
		var d = typeof res === 'string' ? JSON.parse(res) : res;
		if (!d.ok || !d.data) return;

		var currentVal = $('#prodSub').val();
		var opts = '<option value="">— Subtipo —</option>';
		d.data.forEach(function (f) {
			if (f.activo == 1) {
				opts += '<option value="' + f.id + '" data-clas="' + f.clasificacion_id + '">' + f.nombre + '</option>';
			}
		});
		$('#prodSub').html(opts);
		if (currentVal) $('#prodSub').val(currentVal);
		filtrarSubtipos();
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
	if (typeof crearDataTable === 'function') {
		crearDataTable();
	}
	ajaxMyDatatable({
		table: $('#tablaProductos'),
		url: 'index.php',
		colums: ['imagen', 'tipo', 'nombre', 'clasificacion', 'subtipo', 'estado', 'acciones'],
		sort: [2, 'asc'],
		params: {
			metodo: 'consultar',
			accion: 'productos',
			tipo: $('#filtroTipo').val() || ''
		}
	});
}
