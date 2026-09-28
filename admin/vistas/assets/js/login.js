jQuery(document).ready(function ($) {
	$('#formLogin').validate({
		rules: {
			correo: { required: true },
			pass: { required: true }
		},
		messages: {
			correo: {
				required: 'Ingresa tu correo o nombre de usuario'
			},
			pass: { required: 'La contraseña es requerida' }
		},
		submitHandler: function () {
			var data = 'accion=login&correo=' + encodeURIComponent($.trim($('#correo').val())) +
				'&contrasena=' + encodeURIComponent($('#pass').val());

			$.ajax({
				url: 'index.php',
				type: 'POST',
				data: data,
				beforeSend: function () {
					$('#mensaAV').html(`<div class="alert alert-primary alert-dismissible fade show d-flex align-items-center" role="alert">
						<div class="spinner-border spinner-border-sm text-primary me-2 flex-shrink-0" role="status"></div>
						<div class="flex-grow-1"><strong>Verificando credenciales...</strong></div>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
					</div>`);
					$('#mensaAV').show();
					$('#bIngresarLogin').prop('disabled', true);
					$('#bIngresarLogin').html('Iniciando... <div class="spinner-border spinner-border-sm text-light ms-1" role="status"></div>');
				}
			})
			.done(function (res) {
				if ($.trim(res) === 'Correcto') {
					setTimeout(function () {
						$('#mensaAV').html(`<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
							<i class="bi bi-check-circle-fill text-success fs-5 me-2 flex-shrink-0"></i>
							<div class="flex-grow-1"><strong>¡Acceso concedido!</strong> Ingresando al panel...</div>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
						</div>`);
						setTimeout(function () { window.location.reload(); }, 600);
					}, 400);
				} else if ($.trim(res) === '0') {
					setTimeout(function () {
						$('#mensaAV').html(`<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
							<i class="bi bi-exclamation-triangle-fill text-danger fs-5 me-2 flex-shrink-0"></i>
							<div class="flex-grow-1"><strong>Credenciales incorrectas.</strong> Verifica tu correo o contraseña.</div>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
						</div>`);
					}, 400);
				} else {
					setTimeout(function () {
						$('#mensaAV').html(`<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
							<i class="bi bi-x-circle-fill text-danger fs-5 me-2 flex-shrink-0"></i>
							<div class="flex-grow-1"><strong>Error:</strong> No se pudo conectar al servidor.</div>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
						</div>`);
						console.log($.trim(res));
					}, 400);
				}
			})
			.fail(function () { console.log('Error ajax'); })
			.always(function () {
				setTimeout(function () {
					$('#bIngresarLogin').prop('disabled', false);
					$('#bIngresarLogin').html('Iniciar <i class="fas fa-check"></i>');
				}, 1200);
			});
		}
	});
});
